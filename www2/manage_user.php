<?php
/*
* ユーザ一覧表示
*
*/

// 設定ファイルの読み込み
require_once './conf/common_setting.php';
// 関数ファイルの読み込み
require_once './model/common_model.php';
require_once './model/manage_model.php';

// セッション開始
session_start();
if ( isset($_SESSION['user_id'])
  && $_SESSION['user_id'] === 1) {
    $user_id = $_SESSION['user_id'];
} else {
    // 非ログインの場合、ログインページへリダイレクト
    header('Location: login.php');
    exit;
}

// DB接続
$dbh = get_db_connect();
// レコード取得
$rows = get_user_record($dbh);
// HTMLエンティティ化
$rows = entity_assoc_array($rows);
// 表示ファイルの出力
include_once './view/manage_user.php';
?>