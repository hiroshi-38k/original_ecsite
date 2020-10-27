<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>SMART FURNITURE 会員様専用ホームページ</title>
        <link rel="stylesheet" href="./CSS/home.css">
    </head>
    <body>
        <header>
            <div class="container">
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
        <main class="container">
            <div>
                <form method="post" action="itemlist.php">
                    <h2>商品一覧ページ</h2>
                    <p>
                        カテゴリ：
                        <input type="hidden" name="process_type" value="select_category">
<?php foreach ( $category_rows as $value ) { ?>
                        <input type="radio" name="category_name" value="<?php print $value['category_name']; ?>"><?php print $value['category_name']; ?>
<?php } ?>
                    </p>
                    <p><input type="submit" value="商品表示"></p>
                </form>
                <nav>
                    <section class="same_container">
                        <h2><a href="sale.php">最新情報ページ</a></h2>
                        <p>最新の家具情報を紹介！</p>
                        <p><a href="trend.php"><img src="<?php print IMG_DIR; ?>trend.jpg"></a></p>
                        <div class="details">
                            <a href="trend.php">続きを読む</a>
                        </div>
                    </section>
                    <section class="same_container">
                        <h2><a href="sale.php">セール情報ページ</a></h2>
                        <p>お得なキャンペーン・セール情報を紹介！</p>
                        <p><a href="sale.php"><img src="<?php print IMG_DIR; ?>sale.jpg" href="sale.php"></a></p>
                        <div class="details">
                            <a href="sale.php">続きを読む</a>
                        </div>
                    </section>
                </nav>
                <nav>
                    <section>
                        <h2><a href="information.php">メニュー</a></h2>
                        <ul>
                        　 <li><a href="home.php">TOPページ</li>
                            <li><a href="information.php">サイト利用方法</a></li>
                            <li><a href="itemlist.php">商品一覧</li>
                            <li><a href="trend.php">最新情報</a></li>
                            <li><a href="sale.php">セール情報</a></li>
                            <li><a href="history.php">購入履歴</a></li>
                        <?php if($user_id === 1){?>
                            <li><a href="manage_item.php">商品管理</a></li>
                            <li><a href="manage_category.php">カテゴリ管理</a></li>
                        <?php } ?>
                        </ul>
                    </section>
                    <section>
                        <h2>新着商品ピックアップ</h2>
                        <div class="new_item">
<?php while ($i < 5) { ?>
    <?php $i++; ?>
                            <article>
                                <h3><?php print $new_item_rows[$i-1]['name']; ?></h3>
                                <form method="post" action="itemlist.php">
                                        <input type="hidden" name="process_type" value="ranking">
                                        <input type="hidden" name="item_id" value="<?php print $new_item_rows[$i-1]['item_id']; ?>">
                                        <p><input type="image" src="<?php print IMG_DIR . $new_item_rows[$i-1]['img']; ?>" alt="送信する"></p>
                                </form>
                            </article>
<?php } ?>
                        </div>
                    </section>
                </nav>
            </div>
            <div class="ranking">
                <h2>売上ランキング</h2>
<?php while ($j < 5) { ?>
    <?php $j++; ?>
                <article>
                    <h3><?php print $j; ?>位：<?php print $ranking_rows[$j-1]['name']; ?></h3>
                    <form method="post" action="itemlist.php">
                            <input type="hidden" name="process_type" value="ranking">
                            <input type="hidden" name="item_id" value="<?php print $ranking_rows[$j-1]['item_id']; ?>">
                            <p><input type="image" src="<?php print IMG_DIR . $ranking_rows[$j-1]['img']; ?>" alt="送信する"></p>
                    </form>
                </article>
<?php } ?>
            </div>
        </main>
    </body>
</html>