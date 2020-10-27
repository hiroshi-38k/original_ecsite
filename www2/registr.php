<?php
/*
* 新規ユーザー登録処理
*
*/
// 設定ファイルの読み込み
require_once './conf/common_setting.php';
require_once './conf/login_setting.php';
// 関数ファイルの読み込み
require_once './model/common_model.php';
require_once './model/login_model.php';

$err_msg = array();

// セッション開始
session_start();
// セッション変数からログイン済みか確認
if ( isset($_SESSION['user_id']) ) {
    // ログイン済みの場合、ホームページへリダイレクト
    header( 'Location: home.php' );
    exit;
}
// リクエストメソッド確認
if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    // POST値取得
    $user_name = get_post_data('user_name');
    $password  = get_post_data('password');
    // 新規登録データのチェック
    $err_msg = check_new_data($user_name, $password, $err_msg);
    // メールアドレスをCookieへ保存
    setcookie('user_name', $user_name, time() + 60 * 60 * 24 * 365);
    if ( count($err_msg) === 0 ) {
        // DB接続
        $dbh = get_db_connect();
        // 新規ユーザデータの登録
        registr_new_data($user_name, $password, $err_msg, $dbh);
        if ( count($err_msg) === 0 ) {
            // ユーザデータの取得
            $user_data = get_user_data($user_name, $password, $err_msg, $dbh);
            // ユーザデータを取得できたか確認
            $flag = check_user_data($user_data);
            if ( $flag === TRUE ) {
                // セッション変数にuser_idを保存
                $_SESSION['user_id'] = $user_data['user_id'];
                // ログイン済みユーザのホームページへリダイレクト
                header('Location: home.php');
                exit;
            } else {
                $err_msg[] = 'ユーザデータの取得に失敗しました。';
                // HTMLエンティティ化
                $user_name = entity_str($user_name);
                $password  = entity_str($password);
            }
        }
    }

}
// 表示ファイル
include_once './view/registr.php';
?>