<?php
/*
* 商品情報登録・更新処理
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
$new_item_data    = array();
$new_img_filename = '';
$complete         = '';
$is_posted        = FALSE;

// DB接続
$dbh = get_db_connect();

// 新規商品追加 POST受信
if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    if ( isset($_POST['process_type'] ) === TRUE &&
         $_POST['process_type'] === 'new_item' ) {
        // POST値取得
        $new_item_data['name']          = get_post_data('name');
        $new_item_data['comment']       = get_post_data('comment');
        $new_item_data['category_name'] = get_post_data('category_name');
        $new_item_data['list_price']    = get_post_data('list_price');
        $new_item_data['stock']         = get_post_data('stock');
        $new_item_data['status']        = get_post_data('status');
        // 日付取得
        $date = date('Y-m-d H:i:s');
        // データエラーチェック
        $err_msg = check_new_item_data( $new_item_data, $err_msg);
        // 画像ファイルのアップロードチェック
        if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) {
            $tmpname = $_FILES['new_img']['tmp_name'];
            $filename = $_FILES['new_img']['name'];
            // 画像ファイル保存
            save_imgdata($tmpname, $filename, $new_img_filename, $err_msg);
        } else {
            $err_msg[] = 'ファイルを選択してください';
        }
        // DB更新
        if (count($err_msg) === 0) {
            // トランザクション処理開始
            $dbh->beginTransaction();
            try {
                // 商品情報テーブルへ新商品追加
                insert_new_item_data($new_item_data, $new_img_filename, $date, $err_msg, $dbh);
                // 更新完了フラグ
                if ( count($err_msg) === 0 ) {
                    $is_posted = TRUE;
                    $complete  = '商品を追加しました';
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

// 在庫数変更 POST受信
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['process_type']) === TRUE &&
        $_POST['process_type'] === 'change_stock') {
            // POST値取得
            $item_id      = get_post_data('item_id');
            $stock        = get_post_data('change_stock');
            // 日付取得
            $date = date('Y-m-d H:i:s');
            // 入力データチェック
            $err_msg = check_change_stock($item_id, $stock, $err_msg);
            // DB更新
            if ( count($err_msg) === 0 ) {
                // トランザクション処理開始
                $dbh->beginTransaction();
                try {
                    // 在庫情報変更
                    update_stock($item_id, $stock, $date, $err_msg, $dbh);
                    if ( count($err_msg) === 0 ) {
                        // 更新完了フラグ
                        $is_posted = TRUE;
                        $complete  = '在庫数を変更しました';
                        // コミット処理
                        $dbh->commit();
                    }
                } catch (PDOException $e) {
                    // ロールバック処理
                    $dbh->rollback();
                    throw $e;
                }
            }
    }
}

// セール価格変更 POST受信
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['process_type']) === TRUE &&
        $_POST['process_type'] === 'change_price') {
            // POST値取得
            $item_id      = get_post_data('item_id');
            $sale_price   = get_post_data('sale_price');
            // 日付取得
            $date = date('Y-m-d H:i:s');
            // 入力データチェック
            $err_msg = check_change_price($item_id, $sale_price, $err_msg);
            // DB更新
            if ( count($err_msg) === 0 ) {
                // トランザクション処理開始
                $dbh->beginTransaction();
                try {
                    // 在庫情報変更
                    update_price($item_id, $sale_price, $date, $err_msg, $dbh);
                    if ( count($err_msg) === 0 ) {
                        // 更新完了フラグ
                        $is_posted = TRUE;
                        $complete  = '価格を変更しました';
                        // コミット処理
                        $dbh->commit();
                    }
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
            $item_id = get_post_data('item_id');
            $status  = get_post_data('change_status');
            $err_msg = check_change_status($item_id, $status, $err_msg);
            $status = change_status($status);
            // 日付取得
            $date = date('Y-m-d H:i:s');
            // DB更新
            if (count($err_msg) === 0) {
                // トランザクション処理開始
                $dbh->beginTransaction();
                try {
                    // ステータス更新
                    update_item_status($item_id, $status, $date, $dbh);
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

// セール変更 POST受信
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['process_type']) === TRUE &&
        $_POST['process_type'] === 'change_onsale') {
            // ステータス変更
            $item_id = get_post_data('item_id');
            $onsale  = get_post_data('change_onsale');
            $err_msg = check_change_status($item_id, $onsale, $err_msg);
            $onsale = change_status($onsale);
            // 日付取得
            $date = date('Y-m-d H:i:s');
            // DB更新
            if (count($err_msg) === 0) {
                // トランザクション処理開始
                $dbh->beginTransaction();
                try {
                    // ステータス更新
                    update_item_onsale($item_id, $onsale, $date, $dbh);
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
$item_rows = get_manageitem_record($dbh);
$category_rows = get_category_record($dbh);
// HTMLエンティティ化
$item_rows = entity_assoc_array($item_rows);
$category_rows = entity_assoc_array($category_rows);
// 表示ファイルの出力
include_once './view/manage_item.php';

?>