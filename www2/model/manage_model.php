<?php
/*
* 商品管理ページ用モデル
*
*/
/**
 * 新商品 データチェック
 * @param  array $new_item_data 新商品情報
 *         array $err_msg エラーメッセージ
 */
function check_new_item_data($new_item_data, $err_msg) {
   
    // 入力チェック
    if ( trim($new_item_data['name']) === '' ) {
        $err_msg[] = '商品名を入力してください。';
    }
    if ( trim($new_item_data['category_name']) === '' ) {
        $err_msg[] = 'カテゴリーを入力してください。';
    }
    if ( trim($new_item_data['comment']) === '' ) {
        $err_msg[] = '商品詳細を入力してください。';
    } elseif ( mb_strlen($new_item_data['comment']) > MAX_NUM ) {
        $err_msg[] = 'コメントは100文字以内で入力してください。';
    }
    if ( trim($new_item_data['list_price']) === '') {
        $err_msg[] = '価格を入力してください。';
    } else {
        if (preg_match(INT_REGEX, $new_item_data['list_price']) !== 1) {
            $err_msg[] = '値段は0以上の整数を入力してください';
        }
    }
    if ( trim($new_item_data['stock']) === '' ) {
        $err_msg[] = '在庫数を入力してください。';
    } else {
        if (preg_match(INT_REGEX, $new_item_data['stock']) !== 1) {
            $err_msg[] = '在庫数は0以上の整数を入力してください';
        }
    }
    if ($new_item_data['status'] !== '0' && $new_item_data['status'] !== '1') {
        $err_msg[] = 'ステータスエラー。再度お試しください。';
    }
    return $err_msg;
}

/**
 * 在庫数変更 データチェック
 * @param  str   $item_id 商品ID
 *         str   $stock 在庫数
 *         array $err_msg エラーメッセージ
 */
function check_change_stock($item_id, $stock, $err_msg) {
    
    if ($stock === '') {
        $err_msg[] = '個数を入力してください。';
    } else {
        if (preg_match(INT_REGEX, $stock) !== 1) {
            $err_msg[] = '個数は0以上の整数を入力してください';
        }
    }
    if (preg_match(INT_REGEX, $item_id) !== 1) {
        $err_msg[] = 'IDエラー。再度お試しください。';
    }
    return $err_msg;
}

/**
 * セール価格変更 データチェック
 * @param  str   $item_id 商品ID
 *         str   $stock 在庫数
 *         array $err_msg エラーメッセージ
 */
function check_change_price($item_id, $sale_price, $err_msg) {
    
    if ($sale_price === '') {
        $err_msg[] = '価格を入力してください。';
    } else {
        if (preg_match(INT_REGEX, $sale_price) !== 1) {
            $err_msg[] = '価格は0以上の整数を入力してください';
        }
    }
    if (preg_match(INT_REGEX, $item_id) !== 1) {
        $err_msg[] = 'IDエラー。再度お試しください。';
    }
    return $err_msg;
}

/**
 * 新商品画像ファイル保存
 * @param  str   $tmpname         一時ファイル名
 *         str   $filename        アップロード時ファイル名
 *         str   $new_img_filename ファイル名
 * 　　　　array エラーメッセージ（参照渡し）
 */
function save_imgdata($tmpname, $filename, &$new_img_filename, &$array) {
    
    // 画像の拡張子取得
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    // 指定の拡張子であるかどうかチェック
    if ($extension === JPG || $extension === JPEG) {
        // ファイル名生成
        $new_img_filename = sha1(uniqid(mt_rand(), true)). '.' . $extension;
        // 同名ファイルが存在するかどうかチェック
        if (is_file(IMG_DIR . $new_img_filename) !== TRUE) {
            // ファイルを指定ディレクトリへ移動
            if (move_uploaded_file($tmpname, IMG_DIR . $new_img_filename) !== TRUE) {
                $array[] = 'ファイルアップロードに失敗しました。';
            }
        } else {
            $array[] = 'ファイルアップロードに失敗しました。再度お試しください。';
        }
    } else {
        $array[] = 'ファイル形式が異なります。画像ファイルはJPEGのみ利用可能です。';
    }
}

/**
 * 新商品を商品情報テーブルに追加
 * @param array $new_item_data 新商品情報
 *        str 画像ファイル名
 *        str $date 日付
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 */
function insert_new_item_data($new_item_data, $new_img_filename, $date, &$err_msg, $dbh) {
    try {
        // SQL文作成
        $sql = 'SELECT name FROM sf_items WHERE name=?';
        //バインド値
        $bindvalue = [$new_item_data['name']];
        // クエリ実行
        $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
        // レコード取得
        $array = $stmt->fetchAll();
        // 商品名照合
        if ( isset($array[0]['name']) ) {
            $err_msg[] = '既に存在する商品名です。';
        } else {
            // SQL文作成
            $sql = 'SELECT category_id FROM sf_categories WHERE category_name=?';
            //バインド値
            $bindvalue = [$new_item_data['category_name']];
            // クエリ実行
            $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
            // レコード取得
            $array = $stmt->fetchAll();
            // カテゴリid照合
            if ( !isset($array[0]['category_id']) ) {
                $err_msg[] = 'カテゴリエラー。再度お試しください。';
            }
            if ( count($err_msg) === 0 ) {
                // SQL文作成
                $sql = 'INSERT INTO sf_items'.
                       ' (name, list_price, sale_price, img, status, stock, category_id, comment, createdate, updatedate)'.
                       ' VALUES (?,?,?,?,?,?,?,?,?,?)';
                // バインド値
                $bindvalue = [  $new_item_data['name'],
                                $new_item_data['list_price'],
                                $new_item_data['list_price'],
                                $new_img_filename,
                                $new_item_data['status'],
                                $new_item_data['stock'],
                                $array[0]['category_id'],
                                $new_item_data['comment'],
                                $date, $date ];
                // クエリ実行
                sql_bindvalue($sql, 10, $bindvalue, $dbh);
            }   
        }
    } catch (PDOException $e) {
        echo '商品情報テーブル更新に失敗しました。';
        throw $e;
    }
}

/**
 * 商品公開ステータスの変更
 * @param str $item_id 商品ID
 *        str $status   公開ステータス
 *        str $date     日付
 *        obj $dbh      DBハンドル
 */
function update_item_status($item_id, $status, $date, $dbh) {
    try {
        // SQL文作成
        $sql = 'UPDATE sf_items SET status=?, updatedate=? WHERE item_id=?';
        // バインド値
        $bindvalue = [$status, $date, $item_id];
        // SQL実行
        sql_bindvalue($sql, 3, $bindvalue, $dbh);
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * 商品セールステータスの変更
 * @param str $item_id 商品ID
 *        str $status  セールステータス
 *        str $date 日付
 *        obj $dbh DBハンドル
 */
function update_item_onsale($item_id, $onsale, $date, $dbh) {
    try {
        // SQL文作成
        $sql = 'UPDATE sf_items SET onsale=?, updatedate=? WHERE item_id=?';
        // バインド値
        $bindvalue = [$onsale, $date, $item_id];
        // SQL実行
        sql_bindvalue($sql, 3, $bindvalue, $dbh);
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * 在庫情報更新
 * @param str $item_id 商品ID
 *        str $stock 在庫数
 *        str $date 日付
 *        array $err_msg エラーメッセージ(参照渡し)
 *        obj $dbh DBハンドル
 */
function update_stock($item_id, $stock, $date, &$err_msg, $dbh) {
    
    try {
        // SQL文作成
        $sql = 'UPDATE sf_items SET stock=?, updatedate=? WHERE item_id=?';
        // SQL実行準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $stock, PDO::PARAM_STR);
        $stmt->bindValue(2, $date, PDO::PARAM_STR);
        $stmt->bindValue(3, $item_id, PDO::PARAM_STR);
        // SQL文を実行
        $stmt->execute();
    } catch (PDOException $e) {
        $err_msg[] = '在庫情報の変更に失敗しました。';
        throw $e;
    }
}

/**
 * セール価格情報更新
 * @param str $item_id 商品ID
 *        str $sale_price セール価格
 *        str $date 日付
 *        array $err_msg エラーメッセージ(参照渡し)
 *        obj $dbh DBハンドル
 */
function update_price($item_id, $sale_price, $date, &$err_msg, $dbh) {
    
    try {
        // SQL文作成
        $sql = 'UPDATE sf_items SET sale_price=?, updatedate=? WHERE item_id=?';
        // SQL実行準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $sale_price, PDO::PARAM_STR);
        $stmt->bindValue(2, $date, PDO::PARAM_STR);
        $stmt->bindValue(3, $item_id, PDO::PARAM_STR);
        // SQL文を実行
        $stmt->execute();
    } catch (PDOException $e) {
        $err_msg[] = '在庫情報の変更に失敗しました。';
        throw $e;
    }
}

/**
 * 商品レコード取得
 * @param obj $dbh DBハンドル
 * @return array レコード
 */
function get_manageitem_record($dbh) {
    
    try {
        // SQL文作成
        $sql = 'SELECT item_id, name, list_price, sale_price, img, sf_items.status, onsale, stock, category_name, comment'.
               ' FROM sf_items INNER JOIN sf_categories'.
               ' ON sf_items.category_id=sf_categories.category_id';
        // レコード取得
        $rows = get_as_array($dbh, $sql);
        return $rows;
    } catch (PDOException $e) {
        echo 'レコード取得に失敗しました。';
        throw $e;
    }
}

/*
* カテゴリ管理用モデル
*
*/
/**
 * 新カテゴリデータチェック
 * @param  str   $category_name カテゴリー名
 *         str   $status        公開フラグ
 *         array $err_msg エラーメッセージ(追加前)
 * @return array エラーメッセージ(追加後)
 */
function check_new_category_data($category_name, $status, $err_msg) {
   
    // 入力チェック
    if ( trim($category_name) === '' ) {
        $err_msg[] = 'カテゴリ名を入力してください。';
    }
    if ($status !== '0' && $status !== '1') {
        $err_msg[] = 'ステータスエラー。再度お試しください。';
    }
    return $err_msg;
}

/**
 * 新カテゴリをカテゴリ情報テーブルに追加
 * @param str $category_name  カテゴリ名
 *        str $status 公開ステータス
 *        str $date  日付
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh   DBハンドル
 */
function insert_new_category_data($category_name, $status, $date, &$err_msg, $dbh) {
    try {
        // SQL文作成
        $sql = 'SELECT category_name FROM sf_categories where category_name=?';
        //バインド値
        $bindvalue = [$category_name];
        // クエリ実行
        $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
        // レコード取得
        $array = $stmt->fetchAll();
        // カテゴリ名照合
        if ( isset($array[0]['category_name']) ) {
            $err_msg[] = '既に存在するカテゴリ名です。';
        }
        if ( count($err_msg) === 0 ) {
            // SQL文作成
            $sql = 'INSERT INTO sf_categories (category_name, status, createdate, updatedate) VALUES (?,?,?,?)';
            // バインド値
            $bindvalue = [$category_name, $status, $date, $date];
            // クエリ実行
            sql_bindvalue($sql, 4, $bindvalue, $dbh);
        }
    } catch (PDOException $e) {
        echo '商品情報テーブル更新に失敗しました。';
        throw $e;
    }
}

/**
 * カテゴリ公開ステータスの変更
 * @param str $category_id ID
 *        str $status   公開ステータス
 *        str $date     日付
 *        obj $dbh      DBハンドル
 */
function update_category_status($category_id, $status, $date, $dbh) {
    try {
        // SQL文作成
        $sql = 'UPDATE sf_categories SET status=?, updatedate=? WHERE category_id=?';
        // バインド値
        $bindvalue = [$status, $date, $category_id];
        // SQL実行
        sql_bindvalue($sql, 3, $bindvalue, $dbh);
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * カテゴリレコード取得
 * @param obj $dbh DBハンドル
 * @return array レコード
 */
function get_managecategory_record($dbh) {
    
    try {
        // SQL文作成
        $sql = 'SELECT category_id, category_name, status FROM sf_categories';
        // レコード取得
        $rows = get_as_array($dbh, $sql);
        return $rows;
    } catch (PDOException $e) {
        echo 'レコード取得に失敗しました。理由：';
        throw $e;
    }
}

/*
* ユーザ管理ページ用モデル
* 
*/
/**
 * ユーザ管理レコード取得
 * @param obj $dbh DBハンドル
 * @return array レコード
 */
function get_user_record($dbh) {
    
    try {
        // SQL文作成
        $sql = "
            SELECT
                user_name, password
            FROM
                sf_users
        ";
        // レコード取得
        $rows = get_as_array($dbh, $sql);
        return $rows;
    } catch (PDOException $e) {
        echo 'レコード取得に失敗しました。理由：';
        throw $e;
    }
}

/*
* 共通モデル
*
*/
/**
 * ステータス変更データチェック
 * @param  str   $id 
 *         str   $change_status
 *         array $array エラーメッセージ
 */
function check_change_status($id, $status, $err_msg) {
    
    if (preg_match(INT_REGEX, $id) !== 1) {
        $err_msg[] = 'IDエラー。再度お試しください。';
    }
    if ($status !== '0' && $status !== '1') {
        $err_msg[] = 'ステータスエラー。再度お試しください。';
    }
    return $err_msg;
}

/**
 * ステータス変更
 * @param str $before_status ステータス（変更前）
 * @return str $after_status ステータス（変更後）
 */
function change_status($before_status) {
    
    if ($before_status === '0') {
        $after_status = '1';
    } else if ($before_status === '1') {
        $after_status = '0';
    } else {
        $after_status = '';
    }
    return $after_status;
}

?>