<?php
/*
* カートページ用モデル
*
*/
/**
 * カート追加データチェック
 * 
 * @param str $item_id 商品ID
 *        str $user_id ユーザID
 *        array $err_msg エラーメッセージ（追加前）
 *        obj $dbh DBハンドル
 * @return array エラーメッセージ
 */
function check_insert_cart ($item_id, $user_id, $err_msg, $dbh) {
    
    try {
        // SQL文作成
        $sql = 'SELECT item_id FROM sf_items WHERE status=1 AND item_id=?';
        // バインド値
        $bindvalue = [$item_id];
        // クエリ実行
        $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
        // レコード取得
        $array = $stmt->fetchAll();
        // 商品照合
        if ( empty($array) ) {
            $err_msg[] = '対象の商品は現在取り扱われておりません。';
        }
        if ( count($err_msg) === 0 ) {
            // SQL文作成
            $sql = 'SELECT cart_id FROM sf_carts'.
                   ' WHERE item_id=? AND user_id=? AND purchased=0';
            // バインド値
            $bindvalue = [$item_id, $user_id];
            // クエリ実行
            $stmt = sql_bindvalue($sql, 2, $bindvalue, $dbh);
            // レコード取得
            $array = $stmt->fetchAll();
            // カート照合
            if ( isset($array[0]['cart_id']) ) {
                $err_msg[] = '既にカートへ追加されています。';
            }
        }
    } catch (PDOException $e) {
        throw $e;
    }
    return $err_msg;
}

/**
 * 数量変更データチェック
 * 
 * @param str $item_id 商品ID
 *        str $user_id ユーザID
 *        str $amount 数量
 *        array $err_msg エラーメッセージ（追加前）
 *        obj $dbh DBハンドル
 * @return array エラーメッセージ
 */
function check_change_amount ($item_id, $user_id, $amount, $err_msg, $dbh) {
    
    if ( trim($item_id) === '' ) {
        $err_msg[] = '数量変更に失敗しました。再度お試しください。';
    } elseif ( preg_match(INT_REGEX, $amount) !== 1 || $amount <= 0 ) {
        $err_msg[] = '数量は1以上の整数を入力してください。';
    }
    if ( count($err_msg) === 0 ) {
        try {
            // SQL文作成
            $sql = 'SELECT cart_id, stock FROM sf_carts'.
                   ' INNER JOIN sf_items ON sf_carts.item_id=sf_items.item_id'.
                   ' WHERE sf_carts.item_id=? AND user_id=?';
            // バインド値
            $bindvalue = [$item_id, $user_id];
            // クエリ実行
            $stmt = sql_bindvalue($sql, 2, $bindvalue, $dbh);
            // レコード��得
            $array = $stmt->fetchAll();
            // 商品照合
            if ( !isset($array[0]['cart_id']) ) {
                $err_msg[] = 'カートに存在しません。再度お試しください。';
            } elseif ( $amount > $array[0]['stock'] ) {
                $err_msg[] = '在庫が不足しております。';
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }
    return $err_msg;
}

/**
 * キャンセル商品のカートID取得
 * 
 * @param str $item_id 商品ID
 *        str $user_id ユーザID
 *        str $date 日付
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 * @return str カートID
 */
function cancel_cart_id ($item_id, $user_id, &$err_msg, $dbh) {
    
    
    if ( trim($item_id) === '' ) {
        $err_msg[] = '注文取消に失敗しました。再度お試しください。';
    }
    if ( count($err_msg) === 0 ) {
        try {
            // SQL文作成
            $sql = 'SELECT cart_id FROM sf_carts WHERE sf_carts.item_id=? AND user_id=? AND purchased=0';
            // バインド値
            $bindvalue = [$item_id, $user_id];
            // クエリ実行
            $stmt = sql_bindvalue($sql, 2, $bindvalue, $dbh);
            // レコード取得
            $array = $stmt->fetchAll();
            // 商品照合
            if ( empty($array) ) {
                $err_msg[] = '対象の商品は取り扱われておりません。';
            }
            if ( count($err_msg) === 0 ) {
                return $array[0]['cart_id'];   
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }
}


/**
 * カート情報テーブルへ商品追加
 * @param str $item_id 商品ID
 *        str $user_id ユーザID
 *        str $date 日付
 *        obj $dbh DBハンドル
 */
function insert_cart ($item_id, $user_id, $date, &$err_msg, $dbh) {
    
    try {
        // SQL文作成
        $sql = 'INSERT INTO sf_carts'.
               ' (item_id, user_id, amount, createdate, updatedate)'.
               ' VALUES (?,?,?,?,?)';
        // 値をバインド
        $bindvalue = [$item_id, $user_id, 1, $date, $date];
        // クエリ実行
        sql_bindvalue($sql, 5, $bindvalue, $dbh);
    } catch (PDOException $e) {
        $err_msg[] = 'カート追加に失敗しました。再度お試しください。';
    }
}

/**
 * カート情報テーブルに数量変更を反映
 * @param str $item_id 商品ID
 *        str $user_id ユーザID
 *        str $amount 数量
 *        str $date 日付
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 */
function change_amount ($item_id, $user_id, $amount, $date, &$err_msg, $dbh) {
    
    try {
        // SQL文作成
        $sql = 'UPDATE sf_carts SET amount=?, updatedate=? WHERE item_id=? AND user_id=?';
        // 値をバインド
        $bindvalue = [$amount, $date, $item_id, $user_id];
        // クエリ実行
        sql_bindvalue($sql, 4, $bindvalue, $dbh);
    } catch (PDOException $e) {
        $err_msg[] = '数量変更に失敗しました。再度お試しください。';
    }
}

/**
 * カートレコード取得
 * @param str $user_id ユーザID
 *        obj $dbh DBハンドル
 * @return array レコード
 */
function get_cart_record($user_id, $dbh) {
    
    try {
        // SQL文作成
        $sql = 'SELECT sf_carts.item_id, img, name, category_name, stock, list_price, sale_price, onsale, amount'.
               ' FROM sf_carts'.
               ' INNER JOIN sf_items'.
               ' ON sf_carts.item_id=sf_items.item_id'.
               ' INNER JOIN sf_categories'.
               ' ON sf_items.category_id=sf_categories.category_id'.
               ' WHERE user_id=? AND purchased=0';
        // 値をバインド
        $bindvalue = [$user_id];
        // クエリ実行
        $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
        // レコード取得
        $rows = $stmt->fetchAll();
        return $rows;
    } catch (PDOException $e) {
        echo 'レコード取得に失敗しました。';
        throw $e;
    }
}

/**
 * 合計金額取得
 * @param array $cart_rows カートレコード
 * @return str 合計金額
 */
function get_bill ($cart_rows) {
    
    $bill = 0;
    foreach ( $cart_rows as $value ) {
        if ( $value['onsale'] === '0' ) {
            $bill = $bill + intval($value['list_price']) * intval($value['amount']);
        } elseif ( $value['onsale'] === '1' ) {
            $bill = $bill + intval($value['sale_price']) * intval($value['amount']);
        }
    }
    return $bill;
}

/**
 * カートページ用コメント取得
 * @param $cart_rows カートレコード
 * @return str カートページ用コメント
 */
function get_subtopic ($cart_rows) {
    if ( empty($cart_rows) ) {
        $subtopic = 'カートは空です。商品を購入するとリストが表示されます。';
    } else {
        $subtopic = '';
    }
    return $subtopic;
}
?>