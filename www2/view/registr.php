<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>SMART FURNITURE 新規登録ページ</title>
        <link rel="stylesheet" href="./CSS/login.css">
    </head>
    <body>
        <header>
            <div>
                <img src="<?php print IMG_DIR;?>title_logo.jpg">
            </div>
            <h1>新規登録ページ</h1>
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
                <p>ユーザ名：<input type="text" name="user_name" value="<?php if ( isset($user_name) ) { print $user_name; } ?>"></p>
                <p>パスワード：<input type="text" name="password" value="<?php if ( isset($password) ) { print $password; } ?>"></p>
                <p><input type="submit" value="新規登録してログイン"></p>
            </form>
            <p><a href="./login.php">既に登録済みの方はこちら</a></p>    
        </main>
    </body>
</html>