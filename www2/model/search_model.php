<?php
/*
* 検索用モデル
* 
*/
/**
 * 検索用ワード取得
 * @param str $category_name カテゴリ名
 *        str $search 検索用ワード（加工前）
 *        array $err_msg エラーメッセージ（追加前）
 * @return str 検索用ワード
 */
function search_data ($category_name, $search, &$err_msg) {
    
    if ( $category_name === '' ) {
        $err_msg[] = 'カテゴリを選択してください。';
    }
    if ( $search === '' ) {
        $err_msg[] = 'キーワードを入力してください。';
    }
    if ( count($err_msg) === 0 ) {
        $search = '%'. $search .'%';
    }
    return $search;
}

/**
 * 検索結果レコード取得
 * @param str $category_name カテゴリ名
 *        str $search サーチワード
 * @return array
 */
function get_search_record ( $category_name, $search, &$err_msg, $dbh) {
    
    $search_rows = array();
    try {
        // SQL文作成
        $sql_first = 'SELECT item_id, img, name, category_name, comment, list_price,'.
                     ' sale_price, stock, onsale, sf_items.status'.
                     ' FROM sf_items'.
                     ' INNER JOIN sf_categories ON sf_items.category_id=sf_categories.category_id'.
                     " WHERE name LIKE ? OR comment LIKE ? AND sf_items.status=1";
        // SQL文・バインド値設定
        if ( $category_name === 'すべての商品' ) {
            $sql_after = '';
            $bindvalue = [$search, $search];
            $num = 2;
        } else {
            $sql_after = ' AND category_name=?';
            $bindvalue = [$search, $search, $category_name];
            $num = 3;
        }
        // SQL文実行
        $sql = $sql_first . $sql_after;
        $stmt = sql_bindvalue($sql, $num, $bindvalue, $dbh);
        // レコード取得
        $search_rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        $err_msg[] = '検索に失敗しました。再度お試しください。';
    }
    return $search_rows;
}
?>