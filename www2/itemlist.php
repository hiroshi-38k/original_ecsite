<?php
/*
* ログイン済みユーザのホームページ
*/
// 設定ファイルの読み込み
require_once './conf/common_setting.php';
// 関数ファイルの読み込み
require_once './model/common_model.php';
require_once './model/itemlist_model.php';

$err_msg = array();
$process_type = '';
$subtopic = '';
$i = 0;

// セッション開始
session_start();
if ( isset($_SESSION['user_id']) ) {
    $user_id = $_SESSION['user_id'];
} else {
    // 非ログインの場合、ログインページへリダイレクト
    header('Location: login.php');
    exit;
}

// DB接続
$dbh = get_db_connect();
if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    // プロセス確認
    $process_type = get_post_data('process_type');
    // カテゴリ選択
    if ( $process_type === 'select_category' ) {
        // POST値取得
        $category_name = get_post_data('category_name');
        // データチェック
        $err_msg = check_select_category($category_name, $err_msg, $dbh);
        // エラーが無ければレコード取得
        if ( count($err_msg) === 0 ) {
            $subtopic = 'カテゴリ：'. $category_name; 
            $item_rows = get_categoryitem_record($category_name, $err_msg, $dbh);
        }
    // ランキング商品の表示
    } else if ( $process_type === 'ranking' ) {
        // POST値取得
        $item_id = get_post_data('item_id');
        // データチェック
        $err_msg = check_ranking_item($item_id, $err_msg, $dbh);
        // エラーが無ければレコード取得
        if ( count($err_msg) === 0 ) {
            $subtopic = ''; 
            $item_rows = get_rankingitem_record($item_id, $err_msg, $dbh);
        }
    } else {
        $process_type = '';
    }
}

// レコード取得
if ( $process_type === '' || count($err_msg) > 0 ) {
    $item_rows = get_item_record($dbh);
}
$category_rows = get_category_record($dbh);
$ranking_rows = get_ranking_record($dbh);
// HTMLエンティティ化
$item_rows = entity_assoc_array($item_rows);
$category_rows = entity_assoc_array($category_rows);
$ranking_rows = entity_assoc_array($ranking_rows);
// 表示ファイルの出力
include_once './view/itemlist.php';
?>