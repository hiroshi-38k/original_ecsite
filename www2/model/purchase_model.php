<?php
/*
* 購入処理・購入完了ページ用モデル
*
*/

/**
 * 注文データ取得
 * @param str $user_id ユーザID
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 * @return array 配列
 */
function get_order_data ( $user_id, &$err_msg, $dbh ) {
    
    // SQL文作成
    try {
        $sql = 'SELECT sf_items.item_id, cart_id, name, stock, list_price, sale_price, amount, onsale, num_sold'.
               ' FROM sf_items'.
               ' INNER JOIN sf_carts'.
               ' ON sf_items.item_id=sf_carts.item_id'.
               ' WHERE sf_items.status=1 AND user_id=? AND purchased=0';
        // バインド値
        $bindvalue = [$user_id];
        // クエリ実行
        $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
        // レコード取得
        $array = $stmt->fetchAll();
        if ( empty($array) ) {
            $err_msg[] = '現在取り扱われていない商品が含まれている、又は既に購入済みの状態で購入処理が行われました。';
            // SQL文作成
            $sql = 'SELECT cart_id FROM sf_carts' .
                   ' INNER JOIN sf_items' .
                   ' ON sf_items.item_id=sf_carts.item_id' .
                   ' WHERE sf_items.status=0 AND user_id=? AND purchased=0';
            // バインド値 
            $bindvalue = [$user_id];
            // クエリ実行
            $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
            // レコード取得
            $cancel_array = $stmt->fetchAll();
            if ( !empty($cancel_array) ) {
                foreach( $cancel_array as $value ) {
                    cancel($value['cart_id'], $err_msg, $dbh);
                }
            }
        }
        return $array;
    } catch (PDOException $e) {
        $err_msg[] = '注文データの取得に失敗しました。再度お試しください。' . $e->getMessage();
    }
}

/**
 * 購入金額
 * @param array $order_data 注文データ
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 * @return array 購入金額
 */
function purchase_price ( $order_data, &$err_msg ) {

    // 購入金額計算
    foreach ($order_data as $value) {
        if ( $value['onsale'] === 0 ) {
            $purchase_price[$value['name']] = $value['list_price'];
        } elseif ( $value['onsale'] === 1 ) {
            $purchase_price[$value['name']] = $value['sale_price'];
        } else {
            $err_msg[] = '購入確定に失敗しました。再度お試しください。';
        }
    }
    return $purchase_price;
}

/**
 * 在庫残数計算
 * @param array $order_data 注文データ
 *        array $err_msg エラーメッセージ（参照渡し）
 * @return array 在庫残数
 */
function after_stock ( $order_data, &$err_msg ) {
    
    // 在庫残数
    foreach ($order_data as $value) {
        $after_stock[$value['name']] = $value['stock'] - $value['amount'];
        if ( $after_stock[$value['name']] < 0 ) {
            $err_msg[] = $value['name'] . 'の在庫が不足しています。カートページをご確認ください。';
        }
    }
    return $after_stock;
}

/**
 * 累計売上個数計算
 * @param array $order_data 注文データ
 *        array $err_msg エラーメッセージ（参照渡し）
 * @return array 累計売上個数
 */
function num_sold ( $order_data, &$err_msg ) {
    
    $num_sold = array();
    if ( count($err_msg) === 0 ) {
        // 累計売上個数
        foreach ($order_data as $value) {
            $num_sold[$value['name']] = $value['num_sold'] + $value['amount'];
        }    
    }
    return $num_sold;
}

/**
 * 商品情報テーブルの在庫数変更
 * @param str $user_id ユーザID
 *        array $after_stock 在庫残数
 *        array $num_sold 累計売上数
 *        str $date 日付
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 */
function update_item_table ( $user_id, $after_stock, $num_sold, $date, &$err_msg, $dbh ) {
    
    try {
        // SQL文作成
        $sql = 'UPDATE sf_items'.
               ' INNER JOIN sf_carts ON sf_items.item_id=sf_carts.item_id'.
               ' SET stock=?, num_sold=?, sf_items.updatedate=? WHERE name=? AND user_id=?';
        foreach ( $after_stock as $key => $value ) {
            // 値をバインド
            $bindvalue = [$value, $num_sold[$key], $date, $key, $user_id];
            // クエリ実行
            sql_bindvalue($sql, 5, $bindvalue, $dbh);
        }
    } catch (PDOException $e) {
        $err_msg[] = '在庫更新に失敗しました。再度お試しください。';
    }
}

/**
 * 購入履歴テーブルへ追加
 * @param str $user_id ユーザID
 *        array $order_data 注文データ
 *        array $purchased_price 購入金額
 *        str $date 日付
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 */
function insert_history ( $user_id, $order_data, $purchase_price, $date, &$err_msg, $dbh ) {
    
    if ( count($err_msg) === 0 ) {
        try {
            // SQL文作成
            $sql = 'INSERT INTO sf_history'. 
                   ' (cart_id, item_id, user_id, purchase_price, amount, createdate)'.
                   ' VALUES (?, ?, ?, ?, ?, ?)';
            foreach ($order_data as $value) {
                // 値をバインド
                $bindvalue = [ $value['cart_id'], 
                               $value['item_id'], 
                               $user_id, 
                               $purchase_price[$value['name']],
                               $value['amount'],
                               $date ];
                // クエリ実行
                sql_bindvalue($sql, 6, $bindvalue, $dbh);
            }
            
        } catch (PDOException $e) {
            $err_msg[] = '購入履歴の追加に失敗しました。再度お試しください。';
        }
    }
}

/**
 * カート情報テーブルの更新
 * @param str $user_id ユーザID
 *        array $order_data 注文データ
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 */
function update_cart_table ( $order_data, $date, &$err_msg, $dbh ) {
    
    if ( count($err_msg) === 0 ) {
        try {
            // SQL文作成
            $sql = 'UPDATE sf_carts SET purchased=1 WHERE cart_id=?';
            foreach ( $order_data as $value ) {
                // 値をバインド
                $bindvalue = [$value['cart_id']];
                // クエリ実行
                sql_bindvalue($sql, 1, $bindvalue, $dbh);
            }
        } catch (PDOException $e) {
            $err_msg[] = 'カート更新に失敗しました。再度お試しください。';
        }
    }
}

/**
 * 購入品レコード取得
 * @param str $user_id ユーザID
 *        obj $dbh DBハンドル
 * @return array レコード
 */
function get_purchase_record($user_id, $order_data, &$err_msg, $dbh) {
    
    try {
        // SQL文作成
        $sql = 'SELECT img, name, purchase_price, sf_carts.amount'.
               ' FROM sf_carts'.
               ' INNER JOIN sf_items ON sf_items.item_id=sf_carts.item_id'.
               ' INNER JOIN sf_history ON sf_history.cart_id=sf_carts.cart_id'.
               ' WHERE sf_carts.cart_id=? AND purchased=1';
        foreach ( $order_data as $value ) {
            // 値をバインド
            $bindvalue = [$value['cart_id']];
            // クエリ実行
            $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
            // レコード取得
            $rows[] = $stmt->fetch();
        }
        return $rows;
    } catch (PDOException $e) {
        $err_msg[] = 'レコード取得に失敗しました。';
        throw $e;
    }
}

/**
 * 合計金額取得
 * @param array $cart_rows カートレコード
 * @return str 合計金額
 */
function get_total_fee ($purchase_rows) {
    
    $total_fee = 0;
    foreach ( $purchase_rows as $value ) {
        $total_fee = $total_fee + intval($value['purchase_price']) * intval($value['amount']);
    }
    return $total_fee;
}

/**
 * 購入完了コメント
 * 
 * 
 */
function get_purchase_comment ( $err_msg ) {
    
    if ( count($err_msg) === 0 ) {
        $purchase_comment = '購入を完了しました。';
    } else {
        $purchase_comment = '';
    }
    return $purchase_comment;
}
?>