<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>カートページ</title>
        <link rel="stylesheet" href="./CSS/cart.css">
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
                                <li><a href="sale.php">セール情報ページ</a></li>
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
                    <nav>
                        <section>
                            <h2>カート内商品一覧</h2>
                            <h3><?php print $subtopic; ?></h3>
                            <ul>
<?php foreach ( $err_msg as $value ) { ?>
                                <li><?php print $value; ?></li>
<?php } ?>
                            </ul>
<?php if ( !empty($cart_rows) ) { ?>
                            <table class="cart_list">
                                <tr>
                                    <th>イメージ</th>
                                    <th>商品名</th>
                                    <th>カテゴリ</th>
                                    <th>価格</th>
                                    <th>数量</th>
                                    <th>在庫</th>
                                    <th>注文取消</th>
                                </tr>
    <?php foreach ( $cart_rows as $value ) { ?>
                                <tr>
                                    <td><img src="<?php print IMG_DIR . $value['img']; ?>"></td>
                                    <td><?php print $value['name']; ?></td>
                                    <td><?php print $value['category_name']; ?></td>
                                    <td>
        <?php if ( $value['stock'] > 0 ) { ?>
                                        <?php if ( $value['onsale'] === '0' ) {
                                            print $value['list_price'];
                                        } elseif ( $value['onsale'] === '1' ) {
                                            print $value['sale_price'];
                                        }
                                        ?>
                                        <p>円</p>
        <?php } else { ?>
                                        <p class="sold_out">売り切れ</p>
        <?php } ?>
                                    </td>
                                    <td>
                                        <form method="post" action="cart.php">
                                            <input type="hidden" name="item_id" value="<?php print $value['item_id']?>">
                                            <input type="hidden" name="process_type" value="change_amount">
                                            <input type="text" name="amount" value="<?php print $value['amount'] ?>">
                                            <p>個</p>
                                            <input type="submit" value="変更">
                                        </form>
                                    </td>
                                    <td><?php print $value['stock']; ?>個</td>
                                    <td>
                                        <form method="post" action="cart.php">
                                            <input type="hidden" name="item_id" value="<?php print $value['item_id']?>">
                                            <input type="hidden" name="process_type" value="cancel">
                                            <input type="submit" value="キャンセル">    
                                        </form>
                                    </td>
                                </tr>
    <?php } ?>
                            </table>
                            <form method="post" action="purchase.php">
                                <input type="hidden" name="process_type" value="purchase"> 
                                <table class="sum">
                                        <tr>
                                            <th>合計金額</th>
                                            <th>購入</th>
                                        </tr>
                                        <tr>
                                            <td><?php print $bill; ?>円</td>
                                            <td>
                                                    <input type="submit" value="購入確定">
                                            </td>
                                        </tr>
                                </table>
                            </form>
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