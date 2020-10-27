<?php
/**
 * セール商品レコード取得
 * 
 * @param obj $dbh DBハンドル
 * @return array レコード
 */
function get_sale_record($dbh) {
    
    $rows = array();
    try {
        // SQL文作成
        $sql = 'SELECT item_id, name, list_price, sale_price, img, sf_items.status, onsale, stock, category_name, comment'.
               ' FROM sf_items INNER JOIN sf_categories'.
               ' ON sf_items.category_id=sf_categories.category_id'.
               ' WHERE onsale=1 AND sf_items.status=1 AND sf_categories.category_id';
        // レコード取得
        $rows = get_as_array($dbh, $sql);
    } catch (PDOException $e) {
        $err_msg[] =  'レコード取得に失敗しました。再度お試しください。';
    }
    return $rows;
}

/**
 * セール商品ページコメント
 * @param array $sale_rows セール商品レコード
 * @return str コメント
 */
function get_sale_comment ($sale_rows) {
    
    if ( empty($sale_rows) ) {
        $subtopic = '現在セール中の商品はございません。';
    } else {
        $subtopic = '下記の商品がセール中です！';
    }
    return $subtopic;
}

?>