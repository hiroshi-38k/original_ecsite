<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>アカウント情報ページ</title>
        <link rel="stylesheet" href="./CSS/history.css">
    </head>
<body>
        <header>
            <div class="fixed_container">
                <img src="<?php print IMG_DIR;?>title_logo.jpg">
                <form method="post" action="search.php">
                    <input type="hidden" name="process_type" value="search">
                    <p>
                        カテゴリ：
                        <select name="category_name">
                            <option value="すべての商品">すべての商品</option>
<?php foreach ( $category_rows as $value ) { ?>
                            <option value="<?php print $value['category_name']; ?>"><?php print $value['category_name']; ?></option>
<?php } ?>
                        </select>
                    </p>
                    <p>
                        <input type="text" name="search" placeholder="商品名で検索">
                        <input type="submit" value="検索">
                    </p>
                </form>
                <div>
                    <a href="cart.php"><img src="<?php print IMG_DIR; ?>black_cart.jpeg"></a>
                    <a href="cart.php">カートページ</a>
                </div>
                <div>
                    <a href="account.php"><img src="<?php print IMG_DIR; ?>black_account.jpeg"></a>
                    <a href="account.php">アカウントページ</a>
                </div>
                <div>
                    <a href="logout.php"><img src="<?php print IMG_DIR; ?>black_logout.jpeg"></a>
                    <a href="logout.php">ログアウト</a>
                </div>
            </div>
        </header>
        <main class="fixed_container">
            <div class="contents">
                <div class="container">
                    <nav>
                        <section>
                            <h2>メニュー</h2>
                            <ul>
                        　      <li><a href="home.php">TOPページ</li>
                                <li><a href="information.php">サイト利用方法</a></li>
                                <li><a href="itemlist.php">商品一覧</li>
                                <li><a href="trend.php">最新情報</a></li>
                                <li><a href="sale.php">セール情報</a></li>
                                <li><a href="history.php">購入履歴</a></li>
                        <?php if($user_id === 1){?>
                                <li><a href="manage_item.php">商品管理</a></li>
                            <   li><a href="manage_category.php">カテゴリ管理</a></li>
                        <?php } ?>
                            </ul>
                        </section>
                    </nav>
                    <nav class="itemlist">
                        <section>
                            <h2>アカウント情報</h2>
                            <p><?php print $subtopic;?></p>
                            <ul>
<?php foreach ( $err_msg as $value ) { ?>
                                <li><?php print $value; ?></li>
<?php } ?>
                            </ul>
<?php if ( !empty($account_rows) ) { ?>
    <?php foreach ( $account_rows as $value ) { ?>
                            <table class="account_list">
                                <tr>
                                    <th>ユーザ名</th>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="process_type" value="change_user_name">
                                            <input type="text" name="user_name" value="<?php print $value['user_name'] ?>">
                                            <input type="submit" value="新しいユーザ名に変更">
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <th>パスワード</th>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="process_type" value="change_password">
                                            <input type="text" name="password" value="<?php print $value['password'] ?>">
                                            <input type="submit" value="新しいパスワードに変更">
                                        </form>
                                    </td>
                                </tr>
                            </table>
    <?php } ?>
<?php } ?>
                        </section>
                    </nav>
                </div>
            </div>
            <div class="ranking">
                <h2>売上ランキング</h2>
<?php while ($i < 5) { ?>
    <?php $i++; ?>
                <article>
                    <h3><?php print $i; ?>位：<?php print $ranking_rows[$i-1]['name']; ?></h3>
                    <form method="post" action="itemlist.php">
                            <input type="hidden" name="process_type" value="ranking">
                            <input type="hidden" name="item_id" value="<?php print $ranking_rows[$i-1]['item_id']; ?>">
                            <p><input type="image" src="<?php print IMG_DIR . $ranking_rows[$i-1]['img']; ?>" alt="送信する"></p>
                    </form>
                </article>
<?php } ?>
            </div>
        </main>
    </body>
</html>