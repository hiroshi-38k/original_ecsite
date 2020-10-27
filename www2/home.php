<?php
/*
* ログイン済みユーザのホームページ
*/

// 設定ファイルの読み込み
require_once './conf/common_setting.php';
// 関数ファイルの読み込み
require_once './model/common_model.php';

$i=0;
$j=0;

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
// レコード取得
$category_rows = get_category_record($dbh);
$ranking_rows = get_ranking_record($dbh);
$new_item_rows = get_newitem_record($dbh);
// HTMLエンティティ化
$category_rows = entity_assoc_array($category_rows);
$ranking_rows = entity_assoc_array($ranking_rows);
$new_item_rows = entity_assoc_array($new_item_rows);
// ホームページ表示
include_once './view/home.php';
?>