<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>商品管理ページ</title>
        <link rel="stylesheet" href="./CSS/manage_item.css">
    </head>
    <body>
        <header>
            <div>
                <img src="<?php print IMG_DIR;?>title_logo.jpg">
            </div>
            <h1>商品管理ページ</h1>
        </header>
<?php if (count($err_msg) > 0) { ?>
        <ul>
    <?php foreach ($err_msg as $value) { ?>
            <li><?php print $value; ?></li>
    <?php } ?>
        </ul>
<?php } ?>
<?php if ($is_posted === TRUE) { ?>
        <p><?php print $complete; ?></p>
<?php } ?>
        <h2>新規商品追加</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="process_type" value="new_item">
            <p>名前：<input type="text" name="name" value="<?php if ( isset($name) ) { print $name; } ?>"></p>
            <p>
                カテゴリー：
                <select name="category_name">
<?php foreach ($category_rows as $key => $value) { ?>
                    <option><?php print $value['category_name']; ?></option>
<?php } ?>
                </select>
            </p>
            <p>商品詳細：<input type="text" name="comment" value="<?php if ( isset($comment) ) { print $comment; } ?>"></p>
            <p>定価：<input type="text" name="list_price" value="<?php if ( isset($list_price) ) { print $list_price; } ?>"></p>
            <p>在庫：<input type="text" name="stock" value="<?php if ( isset($stock) ) { print $stock; } ?>"></p>
            <p>画像：<input type="file" name="new_img"></p>
            <p>
                公開ステータス：
                <select name="status">
                    <option value="0">非公開</option>
                    <option value="1">公開</option>
                </select>
            </p>
            <p><input type="submit" value="商品追加"></p>
        </form>
        <h2>商品情報変更</h2>
        <a>商品一覧</a>
        <table>
            <tr>
                <th>商品画像</th>
                <th>商品名</th>
                <th>カテゴリー</th>
                <th>商品詳細</th>
                <th>価格</th>
                <th>在庫</th>
                <th>公開ステータス</th>
                <th>セール</th>
            </tr>
<?php foreach ($item_rows as $value) { ?>
            <tr class="<?php if ($value['status'] === '0') {print 'private';} ?>">
                <td><img src="<?php print IMG_DIR . $value['img']; ?>"></td>
                <td><?php print $value['name']; ?></td>
                <td><?php print $value['category_name']; ?></td>
                <td>
                    <details>
                        <summary>詳細を見る</summary>
                        <?php print $value['comment']; ?>
                    </details>
                </td>
    <?php if ( $value['onsale'] === '0' ) { ?>
                <td>
                    <p>定価</p>
                    <p><?php print $value['list_price']; ?>円</p>
                </td>
    <?php } else if ( $value['onsale'] === '1' ) { ?>
                <td>
                    <form method="post">
                        <input type="hidden" name="process_type" value="change_price">
                        <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
                        <p>セールス価格</p>
                        <p><input type="text" name="sale_price" value="<?php print $value['sale_price']; ?>">円</p>
                        <input type="submit" value="変更">
                    </form>
                </td>
    <?php } ?>
                <td>
                    <form method="post">
                        <input type="hidden" name="process_type" value="change_stock">
                        <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
                        <p><input type="text" name="change_stock" value="<?php print $value['stock']; ?>">個</p>
                        <input type="submit" value="変更">
                    </form>
                </td>
                <td>
                    <form method="post">
                        <input type="hidden" name="process_type" value="change_status">
                        <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
                        <input type="hidden" name="change_status" value="<?php print $value['status']; ?>">
    <?php if ( $value['status'] === '0' ) { ?>
                        <p><input type="submit" value="非→公"></p>
    <?php } else if ( $value['status'] === '1' ) { ?>
                        <p><input type="submit" value="公→非"></p>
    <?php } ?>
                    </form>
                </td>
                <td>
                    <form method="post">
                        <input type="hidden" name="process_type" value="change_onsale">
                        <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
                        <input type="hidden" name="change_onsale" value="<?php print $value['onsale']; ?>">
    <?php if ( $value['onsale'] === '0' ) { ?>
                        <p><input type="submit" value="OFF → ON"></p>
    <?php } else if ( $value['onsale'] === '1' ) { ?>
                        <p><input type="submit" value="ON → OFF"></p>
    <?php } ?>
                    </form>
                </td>
            </tr>
<?php } ?>
        </table>
    </body>
</html>