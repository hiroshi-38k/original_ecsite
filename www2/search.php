<?php
/*
* 商品検索
*
*/
// 設定ファイル読込
require_once './conf/common_setting.php';
// 関数ファイル読込
require_once './model/common_model.php';
require_once './model/search_model.php';

$search_rows = array();
$err_msg = array();
$process_type = '';
$hit_num = 0;
$i = 0;

// DB接続
$dbh = get_db_connect();

// リクエストメソッド確認
if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    // プロセスタイプ確認
    $process_type = get_post_data('process_type');
    if ( $process_type === 'search' ) {
        // POST値取得
        $category_name = get_post_data('category_name');
        $search = get_post_data('search');
        // 検索データ
        $search = search_data($category_name, $search, $err_msg);
        if ( count($err_msg) === 0 ) {
            // 検索結果レコード取得
            $search_rows = get_search_record($category_name, $search, $err_msg, $dbh);
            // HTMLエンティティ化
            $search_rows = entity_assoc_array($search_rows);
            // ヒット数カウント
            $hit_num = count($search_rows);  
        }
    }
}
// レコード取得
$category_rows = get_category_record($dbh);
$ranking_rows = get_ranking_record($dbh);
// HTMLエンティティ化
$category_rows = entity_assoc_array($category_rows);
$ranking_rows = entity_assoc_array($ranking_rows);
// 表示ファイル出力
include_once './view/search.php';
?>