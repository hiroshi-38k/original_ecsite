<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>セール商品ページ</title>
        <link rel="stylesheet" href="./CSS/sale.css">
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
                <form method="post" action="itemlist.php">
                    <h2>商品一覧ページ</h2>
                    <p>
                        カテゴリ：
                        <input type="hidden" name="process_type" value="select_category">
<?php foreach ( $category_rows as $value ) { ?>
                        <input type="radio" name="category_name" value="<?php print $value['category_name']; ?>"><?php print $value['category_name']; ?>
<?php } ?>
                    </p>
                    <p><input type="submit" value="商品一覧ページへ移動"></p>
                </form>
                <div class="container">
                    <nav>
                        <section>
                            <h2>メニュー</h2>
                            <ul>
                                <li><a href="home.php">TOPページ</li>
                                <li><a href="itemlist.php">商品一覧ページ</li>
                                <li><a href="trend.php">最新情報ページ</a></li>
                                <li><a href="sale.php">セール商品ページ</a></li>
                                <li><a href="history.php">購入履歴ページ</a></li>
                            </ul>
                        </section>
                        <section>
                            <h2><a href="information.php">お知らせ</a></h2>
                            <p>サイト利用方法</p>
                            <div class="information">
                                <a href="information.php">続きを読む</a>
                            </div>
                        </section>
                    </nav>
                    <nav class="itemlist">
                        <section>
                            <h2>セール商品一覧</h2>
                            <h3><?php print $subtopic; ?></h3>
<?php if (!empty($sale_rows)) { ?>
                            <table>
                                <tr>
                                    <th>商品画像</th>
                                    <th>商品名</th>
                                    <th>カテゴリ名</th>
                                    <th>商品詳細</th>
                                    <th>価格</th>
                                    <th>カート</th>
                                </tr>
    <?php foreach ( $sale_rows as $value ) { ?>
                                <tr>
                                    <td><img src="<?php print IMG_DIR . $value['img']; ?>"></td>
                                    <td><?php print $value['name']; ?></td>
                                    <td><?php print $value['category_name']; ?></td>
                                    <td><?php print $value['comment']?></td>
                                    <td>
                                        <p><a class="list_price"><?php print $value['list_price']; ?></a></p>
                                        <p><a class="sale_price"><?php print $value['sale_price']; ?></a></p>
                                        <p>円</p>
                                    </td>
                                    <td>
                <?php if($value['stock'] > 0) { ?>
                                        <form method="post" action="cart.php">
                                            <input type="hidden" name="process_type" value="insert_cart">
                                            <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
                                            <input type="submit" value="購入">
                                        </form>
                <?php } else { ?>
                                        <p class="sold_out">売り切れ</p>        
                <?php } ?>
                                    </td>
                                </tr>
    <?php } ?>
                            </table>
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