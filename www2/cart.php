<?php
/*
* ショッピングカートページの表示
*
*/
// 設定ファイルの読み込み
require_once './conf/common_setting.php';
// 関数ファイルの読み込み
require_once './model/common_model.php';
require_once './model/cart_model.php';

$err_msg = array();
$cart_rows = array();
$process_type = '';
$bill = 0;
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
// リクエストメソッド確認
if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    // 日付取得
    $date = date('Y-m-d H:i:s');
    // プロセスタイプ確認
    $process_type = get_post_data('process_type');
    // カート追加
    if ( $process_type === 'insert_cart' ) {
        // POST値取得
        $item_id = get_post_data('item_id');
        // カート追加データチェック
        $err_msg = check_insert_cart($item_id, $user_id, $err_msg, $dbh);
        // カート情報テーブルへ商品追加
        if ( count($err_msg) === 0 ) {
            insert_cart($item_id, $user_id, $date, $err_msg, $dbh);
        }
    }
    // 購入個数変更
    if ( $process_type === 'change_amount' ) {
        // POST値取得
        $item_id = get_post_data('item_id');
        $amount  = get_post_data('amount');
        // 数量変更データチェック
        $err_msg = check_change_amount($item_id, $user_id, $amount, $err_msg, $dbh);
        // カート情報テーブルに数量変更を反映
        if ( count($err_msg) === 0 ) {
            change_amount($item_id, $user_id, $amount, $date, $err_msg, $dbh);
        }
    }
    // 注文取消
    if ( $process_type === 'cancel' ) {
        // POST値取得
        $item_id = get_post_data('item_id');
        // キャンセル商品のカートID取得
        $cancel_cart_id = cancel_cart_id($item_id, $user_id, $err_msg, $dbh);
        // カート情報テーブルから商品削除
        if ( count($err_msg) === 0) {
            cancel($cancel_cart_id, $err_msg, $dbh);
        }
    }
}
// レコード取得
$cart_rows = get_cart_record($user_id, $dbh);
$category_rows = get_category_record($dbh);
$ranking_rows = get_ranking_record($dbh);
// HTMLエンティティ化
$cart_rows = entity_assoc_array($cart_rows);
$category_rows = entity_assoc_array($category_rows);
$ranking_rows = entity_assoc_array($ranking_rows);
// カートページ用コメント
$subtopic = get_subtopic($cart_rows);
// 合計金額
$bill = get_bill($cart_rows);
// 表示ファイルの出力
include_once './view/cart.php';
?>