<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>インフォメーション</title>
        <link rel="stylesheet" href="./CSS/information.css">
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
                        <section class="menu_bar">
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
                                <li><a href="manage_category.php">カテゴリ管理</a></li>
                            <?php } ?>
                            </ul>
                        </section>
                    </nav>
                    <nav class="itemlist">
                        <section>
                            <h2>当サイトのご利用方法について</h2>
                            <div class="topic_inner">
                                <ul>
                                    <li>当サイトは、家具専門のオンラインショッピングを楽しめるWEBサービスです。</li>
                                    <li>検索エンジン（当サイト上部）：指定したワードで商品を検索できます。</li>
                                    <li>アカウントページ：お客様のアカウント情報を確認／変更できます。</li>
                                    <li>商品一覧ページ：当サイトが取り扱う商品がご覧になれます。カート追加もこちらで行います。</li>
                                    <li>カートページ：お客様のカート一覧が確認できます。ここから購入処理に移れます。</li>
                                    <li>最新情報ページ：最新の家具情報が確認できます。</li>
                                    <li>セール情報ページ：お得な商品情報が確認できます。</li>
                                </ul>
                            </div>
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