<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>SMART FURNITURE ログインページ</title>
        <link rel="stylesheet" href="./CSS/login.css">
    </head>
    <body>
        <header>
            <div>
                <img src="<?php print IMG_DIR;?>title_logo.jpg">
            </div>
            <h1>ログインページ</h1>
        </header>
        <main>
<?php if ( count($err_msg) > 0 ) { ?>
            <ul>
    <?php foreach ( $err_msg as $value ) { ?>
                <li><?php print $value; ?></li>
    <?php } ?>
            </ul>
<?php } ?>
            <form method="post">
                <p>ユーザー名：<input type="text" name="user_name" value="<?php if ( isset($user_name) ) { print $user_name; } ?>"></p>
                <p>パスワード：<input type="text" name="password" value="<?php if ( isset($password) ) { print $password; } ?>"></p>
                <p><input type="submit" value="ログイン"></p>
            </form>
            <p><a href="./registr.php">新規作成の方はこちら</a></p>    
            <p>(1) 本ページは架空のECサイトです。</p>
            <p>(2) お好きなユーザー名・パスワードを登録し、ログインしてください。</p>
        </main>
    </body>
</html>