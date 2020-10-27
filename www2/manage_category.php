<?php
/*
* カテゴリ登録・更新処理
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

$err_msg          = array();
$complete         = '';
$is_posted        = FALSE;

// DB接続
$dbh = get_db_connect();
// 新規商品追加 POST受信
if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    if ( isset($_POST['process_type'] ) === TRUE &&
         $_POST['process_type'] === 'new_category' ) {
        // POST値取得
        $category_name   = get_post_data('category_name');
        $status = get_post_data('status');
        // 日付取得
        $date   = date('Y-m-d H:i:s');
        // データエラーチェック
        $err_msg = check_new_category_data($category_name, $status, $err_msg);
        // エラーが無ければDB更新
        if ( count($err_msg) === 0 ) {
            // トランザクション処理開始
            $dbh->beginTransaction();
            try {
                // カテゴリ情報テーブルへ新カテゴリ追加
                insert_new_category_data($category_name, $status, $date, $err_msg, $dbh);
                // 更新完了フラグ
                if ( count($err_msg) === 0 ) {
                    $is_posted = TRUE;
                    $complete  = 'カテゴリを追加しました';
                }
                // コミット処理
                $dbh->commit();
            } catch (PDOException $e) {
                // ロールバック処理
                $dbh->rollback();
                throw $e;
            }
        }
    }
}
// 公開ステータス変更 POST受信
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['process_type']) === TRUE &&
        $_POST['process_type'] === 'change_status') {
            // ステータス変更
            $category_id = get_post_data('category_id');
            $status  = get_post_data('change_status');
            $err_msg = check_change_status($category_id, $status, $err_msg);
            $status = change_status($status);
            // 日付取得
            $date = date('Y-m-d H:i:s');
            // DB更新
            if (count($err_msg) === 0) {
                // トランザクション処理開始
                $dbh->beginTransaction();
                try {
                    // ステータス更新
                    update_category_status($category_id, $status, $date, $dbh);
                    // 更新完了フラグ
                    $is_posted = TRUE;
                    $complete  = 'ステータスを変更しました。';
                    // コミット処理
                    $dbh->commit();
                } catch (PDOException $e) {
                    // ロールバック処理
                    $dbh->rollback();
                    throw $e;
                }
            }
    }
}
// レコード取得
$rows = get_managecategory_record($dbh);
// HTMLエンティティ化
$rows = entity_assoc_array($rows);
// 表示ファイルの出力
include_once './view/manage_category.php';
?>