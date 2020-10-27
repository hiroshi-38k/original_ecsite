<?php
/**
 * 購入履歴レコード取得
 * @param str $user_id ユーザID
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 */
function get_history_record ($user_id, $err_msg, $dbh) {
    
    $history_rows = array();
    try {
        // SQL文作成
        $sql = 'SELECT history_id, img, name, category_name, purchase_price, sf_history.amount, sf_history.createdate'.
               ' FROM sf_history'.
               ' INNER JOIN sf_items ON sf_history.item_id=sf_items.item_id'.
               ' INNER JOIN sf_categories ON sf_items.category_id=sf_categories.category_id'.
               ' WHERE sf_history.user_id=?'.
               ' ORDER BY history_id DESC';
        // 値をバインド
        $bindvalue = [$user_id];
        // クエリ実行
        $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
        // レコード取得
        $history_rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        $err_msg[] = '購入履歴取得に失敗しました。';
    }
    return $history_rows;
}
?>