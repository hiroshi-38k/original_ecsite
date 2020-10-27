<?php
/**
 * DBハンドルを取得
 * @return obj $dbh DBハンドル
 */
function get_db_connect() {
    
    try {
        // // データベースに接続
        $dbh = new PDO(DSN, DB_USER, DB_PASSWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => DB_CHARSET));
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
        echo '接続できませんでした。理由：' . $e->getMessage();
        return $e;
    }
    return $dbh;
}

/**
 * POSTデータから任意データの取得
 * @param str $key キー
 * @return str データ
 */
function get_post_data($key) {
    $str = '';
    if ( isset($_POST[$key]) ) {
        $str = $_POST[$key];
    }
    return $str;
}

/**
 * 特殊文字をHTMLエンティティに変換する（2次元配列の値）
 * @param array $assoc_array 変換前の配列
 * @return array 変換後の配列
 */
function entity_assoc_array($assoc_array) {
    
    foreach ($assoc_array as $key => $value) {
        foreach ($value as $keys => $values) {
            $assoc_array[$key][$keys] = entity_str($values);
        }
    }
    return $assoc_array;
}

/**
 * 特殊文字をHTMLエンティティに変換する
 * @param str $str 変換前の文字
 * @return str 変換後の文字
 */
function entity_str($str) {
    
    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}

/**
 * クエリを実行しその結果を配列で取得する
 * 
 * @param obj $dbh DBハンドル
 * @param str $sql SOL文
 * @return array 結果配列データ
 */
function get_as_array($dbh, $sql) {
    try {
        // SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        // SQLを実行
        $stmt->execute();
        // レコードの取得
        $rows = $stmt->fetchAll();
    } catch(PDOException $e) {
        throw $e;
    }
    
    return $rows;
}

/**
 * プレースホルダへ値をバインド後、クエリを実行してプリペアドステートメントを取得
 * @param str $sql SQL文
 *        int $num バインド数
 *        array $bindvalue バインド値
 *        array $err_msg エラーメッセージ
 *        obj $dbh DBハンドル
 * @return obj プリペアドステートメント
 */
function sql_bindvalue($sql, $num, $bindvalue, $dbh) {
    
    try {
        // SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        // 値をバインド
        $i = 1;
        while ($i <= $num) {
            $stmt->bindValue($i, $bindvalue[$i-1], PDO::PARAM_STR);
            $i++;
        }
        // SQLを実行
        $stmt->execute();
        return $stmt;
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * 商品レコード取得
 * @param obj $dbh DBハンドル
 * @return array レコード
 */
function get_item_record($dbh) {
    
    try {
        // SQL文作成
        $sql = 'SELECT item_id, name, list_price, sale_price, img, sf_items.status, onsale, stock, category_name, comment'.
               ' FROM sf_items INNER JOIN sf_categories'.
               ' ON sf_items.category_id=sf_categories.category_id' .
               ' WHERE sf_items.status=1 AND sf_categories.status=1';
        // レコード取得
        $rows = get_as_array($dbh, $sql);
        return $rows;
    } catch (PDOException $e) {
        echo 'レコード取得に失敗しました。';
        throw $e;
    }
}

/**
 * カテゴリレコード取得
 * @param obj $dbh DBハンドル
 * @return array レコード
 */
function get_category_record($dbh) {
    
    try {
        // SQL文作成
        $sql = 'SELECT category_id, category_name, status FROM sf_categories' .
               ' WHERE status=1';
        // レコード取得
        $rows = get_as_array($dbh, $sql);
        return $rows;
    } catch (PDOException $e) {
        echo 'レコード取得に失敗しました。理由：';
        throw $e;
    }
}

/**
 * ランキング商品レコード取得
 * @param obj $dbh DBハンドル
 * @return array レコード
 */
function get_ranking_record($dbh) {
    
    try {
        // SQL文作成
        $sql = 'SELECT item_id, img, name FROM sf_items' .
               ' INNER JOIN sf_categories ON sf_items.category_id=sf_categories.category_id' .
               ' WHERE sf_items.status=1 AND sf_categories.status=1' .
               ' ORDER BY num_sold DESC';
        // レコード取得
        $rows = get_as_array($dbh, $sql);
        return $rows;
    } catch (PDOException $e) {
        echo 'レコード取得に失敗しました。理由：';
        throw $e;
    }
}

/**
 * 新着商品レコード取得
 * @param obj $dbh DBハンドル
 * @return array レコード
 */
function get_newitem_record($dbh) {
    
    try {
        // SQL文作成
        $sql = 'SELECT sf_items.item_id, img, name' . 
               ' FROM sf_items' .
               ' INNER JOIN sf_categories ON sf_items.category_id=sf_categories.category_id'.
               ' WHERE sf_items.status=1 AND sf_categories.status=1' .
               ' ORDER BY item_id DESC';
        // レコード取得
        $rows = get_as_array($dbh, $sql);
        return $rows;
    } catch (PDOException $e) {
        echo 'レコード取得に失敗しました。理由：';
        throw $e;
    }
}

/**
 * カート情報テーブルから商品削除
 * @param str $cancel_cart_id キャンセル商品のカートID
 *        str $date 日付
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 */
function cancel ($cancel_cart_id, &$err_msg, $dbh) {
    
    try {
        // SQL文作成
        $sql ='DELETE FROM sf_carts WHERE cart_id=?';
        // 値をバインド
        $bindvalue = [$cancel_cart_id];
        // クエリ実行
        sql_bindvalue($sql, 1, $bindvalue, $dbh);
    } catch (PDOException $e) {
        $err_msg[] = '注文取消に失敗しました。再度お試しください。';
    }
}
?>