<?php
/**
 * ユーザ名チェック
 * @param obj $dbh データベースハンドル
 *        str $user_name ユーザ名
 *        array $err_msg エラーメッセージ（追加前）
 * @return array エラーメッセージ（追加後）
 */
function check_user_name ($dbh, $user_name, $err_msg) {
    
    if ( $user_name === '' ) {
        $err_msg[] = 'ユーザ名を入力してください。';
    } elseif ( mb_strlen($user_name) > MAX_NUM ) {
        $err_msg[] = 'ユーザ名は100文字以内で入力してください。';
    }
    if ( count($err_msg) === 0) {
        // SQL文作成
        $sql = "
            SELECT
                user_name
            FROM
                sf_users
            WHERE
                user_name=?";
        //バインド値
        $bindvalue = [$user_name];
        // クエリ実行
        $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
        // レコード取得
        $array = $stmt->fetchAll();
        // 商品名照合
        if ( isset($array[0]['user_name']) ) {
            $err_msg[] = '既に存在するユーザ名です。';
        }
    }
    return $err_msg;
}

/**
 * パスワードチェック
 * @param str $password パスワード
 *        array $err_msg エラーメッセージ（追加前）
 * @return array エラーメッセージ（追加後）
 */
function check_password ($password, $err_msg) {

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
 * ユーザ情報テーブルにユーザ名変更を反映
 * @param str $user_name ユーザ名
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 */
function update_user_name ( $user_id, $user_name, $date, $err_msg, $dbh ) {
    
    if ( count($err_msg) === 0 ) {
        try {
            // SQL文作成
            $sql = 'UPDATE sf_users SET user_name=?, updatedate=? WHERE user_id=?';
            // バインド値
            $bindvalue = [$user_name, $date, $user_id];
            // クエリ実行
            sql_bindvalue($sql, 3, $bindvalue, $dbh);
        } catch (PDOException $e) {
            $err_msg[] = 'ユーザ名変更に失敗しました。再度お試しください。';
        }  
    }
}

/**
 * ユーザ情報テーブルにユーザ名変更を反映
 * @param str $user_name ユーザ名
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 */
function update_password ( $user_id, $password, $date, $err_msg, $dbh ) {
    
    if ( count($err_msg) === 0 ) {
        try {
            // SQL文作成
            $sql = 'UPDATE sf_users SET password=?, updatedate=? WHERE user_id=?';
            // バインド値
            $bindvalue = [$password, $date, $user_id];
            // クエリ実行
            sql_bindvalue($sql, 3, $bindvalue, $dbh);
        } catch (PDOException $e) {
            $err_msg[] = 'パスワード変更に失敗しました。再度お試しください。';
        }  
    }
}

/**
 * アカウント情報レコード取得
 * @param str $user_id ユーザID
 *        array $err_msg エラーメッセージ（参照渡し）
 *        obj $dbh DBハンドル
 * @return array アカウント情報レコード
 */
function get_account_record ( $user_id, &$err_msg, $dbh ) {
    
    $rows = array();
    try {
        // SQL文作成
        $sql = "
            SELECT
                user_name, password
            FROM
                sf_users
            WHERE
                user_id=?
        ";
        // バインド値
        $bindvalue=[$user_id];
        // クエリ実行
        $stmt = sql_bindvalue($sql, 1, $bindvalue, $dbh);
        // レコード取得
        $rows = $stmt->fetchAll();
        if ( empty($rows) ) {
            $err_msg[] = 'アカウント照合エラー。ログアウト後、再ログインしてください。';
        }
    } catch (PDOException $e) {
        $err_msg[] = 'アカウント情報の取得に失敗しました。再度お試しください。';
    }
    return $rows;
}

/**
 * アカウントページコメント取得
 * @param str $account_rows アカウント情報レコード
 * @return str アカウントページコメント
 */
function get_account_comment($account_rows) {
    
    if ( empty($account_rows) ) {
        $subtopic = 'アカウント照合エラー。ログアウト後、改めてログインしてください。';
    } else {
        $subtopic = '';
    }
    return $subtopic;
}
?>