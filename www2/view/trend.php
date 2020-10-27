<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>最新情報ページ</title>
        <link rel="stylesheet" href="./CSS/trend.css">
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
                    <nav class="itemlist">
                        <section>
                            <h2>爽やかな朝を求めるあなたに、ナチュラル家具！</h2>
                            <div class="topic_inner">
                                <form method="post" action="search.php">
                                    <div class="topic_img">
                                        <input type="hidden" name="process_type" value="search">
                                        <input type="hidden" name="category_name" value="すべての商品">
                                        <input type="hidden" name="search" value="編み椅子">
                                        <p><input type="image" src="<?php print IMG_DIR; ?>23a9917cbfe890ce11aa4fb7a1daa73879d6a7b4.jpg" alt="送信する"></p>
                                    </div>
                                </form>
                                <div class="topic_sentents">
                                    <p>　夏が近づき、暑さを感じる今！そんな中、植物の葉で作られた編み椅子が話題を呼んでいます。</p>
                                    <p>　緑色を基調としたデザインは清涼感を与えること間違いなし！今の季節にぴったりの商品です。</p>
                                </div>
                            </div>
                        </section>
                        <section>
                            <h2>いま流行中！壁にお気に入りの小物をディスプレイ！</h2>
                            <div class="topic_inner">
                                <form method="post" action="search.php">
                                    <div class="topic_img">
                                        <input type="hidden" name="process_type" value="search">
                                        <input type="hidden" name="category_name" value="すべての商品">
                                        <input type="hidden" name="search" value="ウォールシェルフ">
                                        <p><input type="image" src="<?php print IMG_DIR; ?>05feb1dbf7631278057c31039d98de55a081e645.jpg" alt="送信する"></p>
                                    </div>
                                </form>
                                <div class="topic_sentents">
                                    <p>　小物を並べたいがこれ以上棚にスペースが無い！！お客様のそんな気持ちを沸かす商品がこちらの壁掛け型のミニシェルフ。</p>
                                    <p>　壁に取り付けたシェルフ内に小物を置けて高さ方向の置きスペース増大！小物に限らず、日用品置き場にもできます。</p>
                                </div>
                            </div>
                        </section>
                        <section>
                            <h2>白いお部屋を作りたいあなたへホワイト家具をご紹介！</h2>
                            <div class="topic_inner">
                                <form method="post" action="search.php">
                                    <div class="topic_img">
                                        <input type="hidden" name="process_type" value="search">
                                        <input type="hidden" name="category_name" value="すべての商品">
                                        <input type="hidden" name="search" value="オープンラック（白）">
                                        <p><input type="image" src="<?php print IMG_DIR; ?>4d5fb1ef8bdf8cbbc11de9553b6617fce3b37ef4.jpg" alt="送信する"></p>
                                    </div>
                                </form>
                                <div class="topic_sentents">
                                    <p>　白基調の部屋は、見た目がすっきり見えて、明るく柔らかい印象を与えてくれます！</p>
                                    <p>　ホワイト家具を揃えて、自分だけのスペースを作り上げましょう！</p>
                                </div>
                            </div>
                        </section>
                        <section>
                            <h2>家をダイニングバー風に！テーブルセット</h2>
                            <div class="topic_inner">
                                <form method="post" action="search.php">
                                    <div class="topic_img">
                                        <input type="hidden" name="process_type" value="search">
                                        <input type="hidden" name="category_name" value="すべての商品">
                                        <input type="hidden" name="search" value="ダイニングテーブルセット">
                                        <p><input type="image" src="<?php print IMG_DIR; ?>11b0c90f17d23f8d4196602b4fdcb72f27b25ce8.jpg" alt="送信する"></p>
                                    </div>
                                </form>
                                <div class="topic_sentents">
                                    <p>　家でお酒を飲むときに雰囲気を持たせたい…。そんな方へ向けたダイニングテーブルセットのご紹介。</p>
                                    <p>　お洒落な長机・長椅子1点ずつと木製椅子2点をセットで販売。複数人でお酒を楽しむのに打ってつけ！</p>
                                </div>
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