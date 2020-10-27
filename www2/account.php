<?php
/*
* アカウントページ
*
*/
// 設定ファイルの読み込み
require_once './conf/common_setting.php';
// 関数ファイルの読み込み
require_once './model/common_model.php';
require_once './model/account_model.php';

$err_msg = array();
$_rows = array();
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 日付取得
    $date = date('Y-m-d H:i:s');
    // プロセスタイプ確認
    $process_type = get_post_data('process_type');
    // ユーザ名変更
    if ( $process_type === 'change_user_name' ) {
        // POST値取得
        $user_name = get_post_data('user_name');
        // データチェック
        $err_msg = check_user_name($dbh, $user_name, $err_msg);
        // ユーザ情報テーブルにユーザ名変更を反映
        update_user_name($user_id, $user_name, $date, $err_msg, $dbh);
    }
    // パスワード変更
    if ( $process_type === 'change_password' ) {
        // POST値取得
        $password = get_post_data('password');
        // データチェック
        $err_msg = check_password($password, $err_msg);
        // ユーザ情報テーブルにパスワード変更を反映
        update_password($user_id, $password, $date, $err_msg, $dbh);
    }
}
// レコード取得
$account_rows = get_account_record($user_id, $err_msg, $dbh);
$category_rows = get_category_record($dbh);
$ranking_rows = get_ranking_record($dbh);
// HTMLエンティティ化
$account_rows = entity_assoc_array($account_rows);
$category_rows = entity_assoc_array($category_rows);
$ranking_rows = entity_assoc_array($ranking_rows);
// アカウントページコメント
$subtopic = get_account_comment($account_rows);
// 表示ファイルの出力
include_once './view/account.php';
?>