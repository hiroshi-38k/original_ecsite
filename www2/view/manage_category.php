<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>カテゴリ管理ページ</title>
        <link rel="stylesheet" href="./CSS/manage_category.css">
    </head>
        <header>
            <div>
                <img src="<?php print IMG_DIR;?>title_logo.jpg">
            </div>
            <h1>カテゴリ管理ページ</h1>
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
        <h2>新規カテゴリ追加</h2>
        <form method="post">
            <input type="hidden" name="process_type" value="new_category">
            <p>カテゴリ名：<input type="text" name="category_name" value="<?php if ( isset($category_name) ) { print $category_name; } ?>"></p>
            <p>
                公開ステータス：
                <select name="status">
                    <option value="0">非公開</option>
                    <option value="1">公開</option>
                </select>
            </p>
            <input type="submit" value="カテゴリ追加">
        </form>
        <h2>カテゴリ情報変更</h2>
        <a>カテゴリ一覧</a>
        <table>
            <tr>
                <th>カテゴリ名</th>
                <th>公開ステータス</th>
            </tr>
<?php foreach ($rows as $value) { ?>
            <tr class="<?php if ($value['status'] === '0') {print 'private';} ?>">
                <td><?php print $value['category_name']; ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="process_type" value="change_status">
                        <input type="hidden" name="category_id" value="<?php print $value['category_id']; ?>">
                        <input type="hidden" name="change_status" value="<?php print $value['status']; ?>">
    <?php if ( $value['status'] === '0' ) { ?>
                        <p><input type="submit" value="非→公"></p>
    <?php } else if ( $value['status'] === '1' ) { ?>
                        <p><input type="submit" value="公→非"></p>
    <?php } ?>
                    </form>
                </td>
            </tr>
<?php } ?>
        </table>
</html>