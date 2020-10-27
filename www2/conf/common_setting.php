<?php
// データベースの接続情報
define('DB_USER', '****'); // MySQLのユーザ名
define('DB_PASSWD', '****'); // MySQLのパスワード
define('DB_NAME', 'codecamp34469'); // MySQLのDB名
define('DB_CHARSET', 'SET NAMES utf8mb4'); // MySQLのcharset
define('DSN', 'mysql:dbname=' .DB_NAME. ';host=localhost;charset=utf8'); // データベースのDSN情報
// その他共通変数
define('HTML_CHARACTER_SET', 'UTF-8'); // HTML文字エンコーディング
define('IMG_DIR','./img/'); // 画像ディレクトリ
define('INT_REGEX', '/^[0-9]+$/'); // 整数表現
define('PW_REGEX','/^[a-zA-Z0-9]+$/');
define('PW_MIN',6);
define('MAX_NUM', 100); // 最大文字数
define('JPG', 'jpg'); // JPG拡張子
define('JPEG', 'jpeg'); // JPEG拡張子
?>