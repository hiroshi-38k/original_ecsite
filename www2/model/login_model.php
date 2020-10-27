<?php
/**
 * 新規登録データのチェック
 * @param str $user_name ユーザ名
 *        str $password パスワード
 *        array $err_msg エラーメッセージ（追加前）
 * @return array エラーメッセージ（追加後）
 */
function check_new_data($user_name, $password, $err_msg) {
    
    if ( $user_name === '' ) {
        $err_msg[] = 'ユーザ名を入力してください。';
    } elseif ( mb_strlen($user_name) > MAX_NUM ) {
        $err_msg[] = 'ユーザ名は100文字以内で入力してください。';
    }
    if ( $password === '' ) {
        $err_msg[] = 'パスワードを入力してください。';
    } elseif ( preg_match(PW_REGEX, $password) !== 1 || mb_strlen($password) < PW_MIN ) {
        $err_msg[] = 'パスワードは6文字以上の半角英数にしてください。';
    } elseif ( mb_strlen($password) > MAX_NUM ) {
        $err_msg[] = 'パスワードは100文字以内で入力してください。';
    }
    return $err_msg;
}

/**
 * 新規ユーザデータの登録
 * @param str $user_name ユーザ名
 *        str $password パスワード
 *        array $err_msg エラーメッセージ
 *        obj $dbh DBハンドル
 */
function registr_new_data($user_name, $password, &$err_msg, $dbh) {
    
    try {
        // SQL文作成
        $sql = "
            SELECT
                user_name
            FROM
                sf_users
            where
                user_name=?
        ";
        // 値をバインドしてクエリ実行
        $bindvalue = [$user_name];
        $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
        // レコード取得
        $array = $stmt->fetchAll();
        // アドレス照合
        if ( isset($array[0]['user_name']) ) {
            $err_msg[] = '既に存在するユーザ名です。';
        } elseif ( count($err_msg) === 0 ) {
            // SQL文作成
            $sql = "
                INSERT INTO
                    sf_users (user_name, password)
                VALUES 
                    (?,?)
            ";
            $bindvalue = [$user_name, $password];
            // 値をバインドしてクエリ実行
            sql_bindvalue($sql, 2, $bindvalue, $dbh);
        }
    } catch (PDOException $e) {
        $err_msg[] = 'ユーザデータの登録に失敗しました。' . $e->getMessage();
    }
}

/**
 * ログインデータのチェック
 * @param str $user_name ユーザ名
 *        str $password パスワード
 *        array $err_msg エラーメッセージ（追加前）
 * @return array エラーメッセージ（追加後）
 */
function check_login_data($user_name, $password, $err_msg) {
    
    if ( $user_name === '' ) {
        $err_msg[] = 'ユーザ名を入力してください。';
    }
    if ( $password === '' ) {
        $err_msg[] ='パスワードを入力してください。';
    }
    return $err_msg;
}

/**
 * ログインユーザデータの取得
 * @param str $user_name ユーザ名
 *        str $password パスワード
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 * @return array ユーザデータ
 */
function get_user_data($user_name, $password, &$err_msg, $dbh) {
    
    try {
        // SQL文作成
        $sql = "
            SELECT
                user_id, user_name, password
            FROM
                sf_users
            WHERE
                user_name=? AND password=?
        ";
        // 値をバインドしてクエリ実行
        $bindvalue = [$user_name, $password];
        $stmt = sql_bindvalue($sql, 2, $bindvalue, $dbh);
        // レコード取得
        $array = $stmt->fetchAll();
        // アドレス・パスワード照合
        if ( !empty($array) ) {
            return $array[0];
        } else {
            $err_msg[] = 'ユーザ名又はパスワードが異なります。';
        }
    } catch (PDOException $e) {
        $err_msg[] = 'ユーザデータの取得に失敗しました。';
    }
}

/**
 * ユーザデータ取得の確認
 * @param array $user_data ユーザデータ
 * @return boolean $flag フラグ
 */
function check_user_data($user_data) {

    // データ取得の確認   
    $flag = get_flag($user_data['user_id']);
    $flag = get_flag($user_data['user_name']);
    $flag = get_flag($user_data['password']);
    return $flag;
}

/**
 * データ取得の確認
 * @param str $data データ
 * @return boolean $flag フラグ
 */
function get_flag($data) {
    
    $flag = TRUE;
    if ( !isset($data) ) {
        $flag = FALSE;
    }
    return $flag;
}
?>