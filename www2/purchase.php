<?php
/*
* 購入処理・購入完了ページ
*
*/
// 設定ファイルの読み込み
require_once './conf/common_setting.php';
// 関数ファイルの読み込み
require_once './model/common_model.php';
require_once './model/purchase_model.php';

$err_msg = array();
$cart_rows = array();
$bill = 0;

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
    // プロセスタイプ確認
    $process_type = get_post_data('process_type');
    if ( $process_type === 'purchase' ) {
        // 日付取得
        $date = date('Y-m-d H:i:s');
        // トランザクション処理開始
        $dbh->beginTransaction();
        try {
            // 注文データ取得
            $order_data = get_order_data($user_id, $err_msg, $dbh);
            // 購入データ確定
            if ( count($err_msg) === 0 ) {
                // 金額
                $purchase_price = purchase_price($order_data, $err_msg);
                // 在庫
                $after_stock = after_stock($order_data, $err_msg);
                // 累計売上個数
                $num_sold = num_sold($order_data, $err_msg);
            }
            // 購入処理
            if ( count($err_msg) === 0 ) {
                // 商品情報テーブルの更新
                update_item_table($user_id, $after_stock, $num_sold, $date, $err_msg, $dbh);
                // 購入履歴テーブルへ追加
                insert_history($user_id, $order_data, $purchase_price, $date, $err_msg, $dbh);
                // カート情報テーブルの更新
                update_cart_table($order_data, $date, $err_msg, $dbh);
                // 購入品レコード取得
                $purchase_rows = get_purchase_record($user_id, $order_data, $err_msg, $dbh);
                // HTMLエンティティ化
                $purchase_rows = entity_assoc_array($purchase_rows);
                // 合計金額
                $total_fee = get_total_fee($purchase_rows);
            }
            // コミット処理
            $dbh->commit();    
        } catch (PDOException $e) {
            // ロールバック処理
            $err_msg[] = '購入に失敗しました。再度お試しください。';
            $dbh->rollback();
            throw $e;
        }
    }
} else {
    $err_msg[] = '購入エラー。カートページから購入を実行してください。';
}
// カテゴリーレコード
$category_rows = get_category_record($dbh);
$category_rows = entity_assoc_array($category_rows);
// 購入完了コメント
$purchase_comment = get_purchase_comment($err_msg);
// 表示ファイルの出力
include_once './view/purchase.php';
?>