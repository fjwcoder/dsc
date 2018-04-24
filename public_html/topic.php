<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require ROOT_PATH . '/includes/lib_visual.php';

if ((DEBUG_MODE & 2) != 2) {
	$smarty->caching = true;
}

require ROOT_PATH . '/includes/lib_area.php';
$topic_id = (empty($_REQUEST['topic_id']) ? 0 : intval($_REQUEST['topic_id']));
$sql = 'SELECT topic_id,user_id FROM ' . $ecs->table('topic') . 'WHERE topic_id = \'' . $topic_id . '\' and  ' . gmtime() . ' >= start_time and ' . gmtime() . '<= end_time AND review_status = 3';
$topic = $db->getRow($sql);

if (empty($topic)) {
	ecs_header("Location: ./\n");
	exit();
}

$pc_page['tem'] = 'topic_' . $topic_id;
$filename = ROOT_PATH . 'data/topic' . '/topic_' . $topic['user_id'] . '/' . $pc_page['tem'] . '/' . 'pc_html.php';
$pc_page['out'] = get_html_file($filename);
$pc_page['out'] = str_replace('../data/gallery_album/', 'data/gallery_album/', $pc_page['out'], $i);
$pc_page['out'] = str_replace('../data/seller_templates/', 'data/seller_templates/', $pc_page['out'], $i);
$pc_page['out'] = str_replace('../data/topic/', 'data/topic/', $pc_page['out'], $i);

if ($GLOBALS['_CFG']['open_oss'] == 1) {
	$bucket_info = get_bucket_info();
	$endpoint = $bucket_info['endpoint'];
}
else {
	$endpoint = (!empty($GLOBALS['_CFG']['site_domain']) ? $GLOBALS['_CFG']['site_domain'] : '');
}

if ($pc_page['out'] && $endpoint) {
	$desc_preg = get_goods_desc_images_preg($endpoint, $pc_page['out']);
	$pc_page['out'] = $desc_preg['goods_desc'];
}

$sql = 'SELECT * FROM ' . $ecs->table('topic') . ' WHERE topic_id = \'' . $topic_id . '\'';
$topic = $db->getRow($sql);
assign_template();
$position = assign_ur_here(0, $topic['title']);
$smarty->assign('page_title', $position['title']);
$smarty->assign('ur_here', $position['ur_here']);
$smarty->assign('helps', get_shop_help());
$smarty->assign('show_marketprice', $_CFG['show_marketprice']);
$smarty->assign('sort_goods_arr', $sort_goods_arr);
$smarty->assign('topic', $topic);
$smarty->assign('keywords', $topic['keywords']);
$smarty->assign('description', $topic['description']);
$smarty->assign('site_domain', $_CFG['site_domain']);

if (defined('THEME_EXTENSION')) {
	$categories_pro = get_category_tree_leve_one();
	$smarty->assign('categories_pro', $categories_pro);
}

$smarty->assign('pc_page', $pc_page);
$smarty->display('topic.dwt');

?>
