<?php
/*
* 商品一覧ページ用モデル
*
*/
/**
 * カテゴリ選択データチェック
 * 
 * @param str $category_name カテゴリ名
 *        array $err_msg エラーメッセージ（追加前）
 *        obj $dbh DBハンドル
 * @return array エラーメッセージ
 */
function check_select_category ($category_name, $err_msg, $dbh) {
    
    if ( trim($category_name) === '' ) {
        $err_msg[] = 'カテゴリを選択することでお好きな種類の商品が表示されます。';
    }
    if ( count($err_msg) === 0 ) {
        try {
            // SQL文作成
            $sql = 'SELECT category_name FROM sf_categories WHERE category_name=?';
            // バインド値
            $bindvalue = [$category_name];
            // クエリ実行
            $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
            // レコード取得
            $array = $stmt->fetchAll();
            // カテゴリ照合
            if ( empty($array) ) {
                $err_msg[] = 'カテゴリエラー。再度お試しください。';
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }
    return $err_msg;
}

/**
 * ランキング商品データチェック
 * @param str $item_id 商品ID
 *        array $err_msg エラーメッセージ（追加前）
 *        obj $dbh DBハンドル
 * @return array エラーメッセージ
 */
function check_ranking_item ( $item_id, $err_msg, $dbh ) {
    
    if ( trim($item_id) === '' ) {
        $err_msg[] = 'IDエラー。再度お試しください。';
    }
    if ( count($err_msg) === 0 ) {
        try {
            // SQL文作成
            $sql = 'SELECT item_id FROM sf_items WHERE item_id=?';
            // バインド値
            $bindvalue = [$item_id];
            // クエリ実行
            $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
            // レコード取得
            $array = $stmt->fetchAll();
            // ID照合
            if ( empty($array) ) {
                $err_msg[] = 'IDエラー。再度お試しください。';
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }
    return $err_msg;    
}

/**
 * カテゴリ商品レコード取得
 * 
 * @param str $category_name カテゴリ名
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 * @return array レコード
 */
function get_categoryitem_record($category_name, &$err_msg, $dbh) {
    
    try {
        // SQL文作成
        $sql = 'SELECT item_id, name, list_price, sale_price, img, sf_items.status, onsale, stock, category_name, comment'.
               ' FROM sf_items INNER JOIN sf_categories'.
               ' ON sf_items.category_id=sf_categories.category_id'.
               ' WHERE sf_items.status=1 AND category_name=?';
        // バインド値
        $bindvalue = [$category_name];
        // クエリ実行
        $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
        // レコード取得
        $rows = $stmt->fetchAll();
        return $rows;
    } catch (PDOException $e) {
        $err_msg[] = 'レコード取得に失敗しました。';
        throw $e;
    }
}

/**
 * ランキング商品レコード取得
 * 
 * @param str $item_id 商品ID
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 * @return array レコード
 */
function get_rankingitem_record($item_id, &$err_msg, $dbh) {
    
    try {
        // SQL文作成
        $sql = 'SELECT item_id, name, list_price, sale_price, img, sf_items.status, onsale, stock, category_name, comment'.
               ' FROM sf_items INNER JOIN sf_categories'.
               ' ON sf_items.category_id=sf_categories.category_id'.
               ' WHERE sf_items.status=1 AND item_id=?';
        // バインド値
        $bindvalue = [$item_id];
        // クエリ実行
        $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
        // レコード取得
        $rows = $stmt->fetchAll();
        return $rows;
    } catch (PDOException $e) {
        $err_msg[] = 'レコード取得に失敗しました。' . $e->getMessage();
        throw $e;
    }
}
?>