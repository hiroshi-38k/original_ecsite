<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>購入完了</title>
        <link rel="stylesheet" href="./CSS/purchase.css">
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
            <nav>
                <section>
                    <h2>購入品一覧</h2>
                    <h3><?php print $purchase_comment; ?></h3>
                    <ul>
<?php foreach ( $err_msg as $value ) { ?>
                                <li><?php print $value; ?></li>
<?php } ?>
                    </ul>
<?php if ( count($err_msg) === 0 ) { ?>
                    <table class="cart_list">
                        <tr>
                            <th>イメージ</th>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>数量</th>
                        </tr>
    <?php foreach ( $purchase_rows as $value ) { ?>
                        <tr>
                            <td><img src="<?php print IMG_DIR . $value['img']; ?>"></td>
                            <td><?php print $value['name']; ?></td>
                            <td><?php print $value['purchase_price']; ?>円</td>
                            <td><?php print $value['amount'] ?>個</td>
                        </tr>
    <?php } ?>
                    </table>
                    <table class="sum">
                            <tr>
                                <th>合計金額</th>
                            </tr>
                            <tr>
                                <td><?php print $total_fee; ?>円</td>
                            </tr>
                    </table>
<?php } ?>          
                    <p><a href="./history.php">購入履歴ページへ移動</a></p>
                    <p><a href="./home.php">TOPページへ移動</a></p>   
                </section>
            </nav>
        </main>
    </body>
</html>