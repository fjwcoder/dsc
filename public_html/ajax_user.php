<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
function get_ajax_user_order_comment_list($user_id, $type = 0, $sign = 0, $rec_id)
{
	$where = ' AND og.rec_id = ' . $rec_id . ' ';

	if ($sign == 0) {
		$where .= ' AND (SELECT count(*) FROM ' . $GLOBALS['ecs']->table('comment') . ' AS c WHERE c.comment_type = 0 AND c.id_value = g.goods_id AND c.rec_id = og.rec_id AND c.parent_id = 0 AND c.user_id = \'' . $user_id . '\') = 0 ';
	}
	else if ($sign == 1) {
		$where .= ' AND (SELECT count(*) FROM ' . $GLOBALS['ecs']->table('comment') . ' AS c WHERE c.comment_type = 0 AND c.id_value = g.goods_id AND c.rec_id = og.rec_id AND c.parent_id = 0 AND c.user_id = \'' . $user_id . '\') > 0 ';
		$where .= ' AND (SELECT count(*) FROM ' . $GLOBALS['ecs']->table('comment_img') . ' AS ci, ' . $GLOBALS['ecs']->table('comment') . ' AS c' . ' WHERE c.comment_type = 0 AND c.id_value = g.goods_id AND ci.rec_id = og.rec_id AND c.parent_id = 0 AND c.user_id = \'' . $user_id . '\' AND ci.comment_id = c.comment_id ) = 0 ';
	}
	else if ($sign == 2) {
		$where .= ' AND (SELECT count(*) FROM ' . $GLOBALS['ecs']->table('comment') . ' AS c WHERE c.comment_type = 0 AND c.id_value = g.goods_id AND c.rec_id = og.rec_id AND c.parent_id = 0 AND c.user_id = \'' . $user_id . '\') > 0 ';
		$where .= ' AND (SELECT count(*) FROM ' . $GLOBALS['ecs']->table('comment_img') . ' AS ci, ' . $GLOBALS['ecs']->table('comment') . ' AS c' . ' WHERE c.comment_type = 0 AND c.id_value = g.goods_id AND ci.rec_id = og.rec_id AND c.parent_id = 0 AND c.user_id = \'' . $user_id . '\' AND ci.comment_id = c.comment_id ) > 0 ';
	}

	$sql = 'SELECT og.rec_id, og.order_id, og.goods_id, og.goods_name, oi.add_time, g.goods_thumb, g.goods_product_tag, og.ru_id,oi.order_sn,og.goods_number,og.goods_price FROM ' . $GLOBALS['ecs']->table('order_goods') . ' AS og ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('order_info') . ' AS oi ON og.order_id = oi.order_id ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON og.goods_id = g.goods_id ' . 'WHERE og.goods_id = g.goods_id AND oi.user_id = \'' . $user_id . '\' ' . $where;
	$row = $GLOBALS['db']->getRow($sql);

	if ($row) {
		$row['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['add_time']);
		$row['goods_thumb'] = get_image_path($goods_id, $row['goods_thumb'], true);
		$row['impression_list'] = !empty($row['goods_product_tag']) ? explode(',', $row['goods_product_tag']) : array();
		$row['goods_url'] = build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']);
		$row['goods_price'] = price_format($row['goods_price']);
		$row['comment'] = get_order_goods_comment($row['goods_id'], $row['rec_id'], $user_id);
	}

	return $row;
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require ROOT_PATH . 'includes/cls_json.php';
include_once ROOT_PATH . 'includes/lib_clips.php';
require ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/user.php';
$smarty->assign('lang', $_LANG);
$user_id = (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
$json = new JSON();
$result = array('error' => 0, 'message' => '', 'content' => '');
$is_jsonp = (isset($_REQUEST['is_jsonp']) && !empty($_REQUEST['is_jsonp']) ? intval($_REQUEST['is_jsonp']) : 0);

if ($_REQUEST['act'] == 'comments_form') {
	$sql = 'SELECT id, comment_img, img_thumb FROM ' . $ecs->table('comment_img') . ' WHERE user_id = \'' . $user_id . '\' AND comment_id = 0';
	$img_list = $db->getAll($sql);
	if ((intval($_CFG['captcha']) & CAPTCHA_COMMENT) && (0 < gd_version())) {
		$smarty->assign('enabled_captcha', 1);
		$smarty->assign('rand', mt_rand());
	}

	foreach ($img_list as $key => $val) {
		get_oss_del_file(array($val['comment_img'], $val['img_thumb']));
		@unlink(ROOT_PATH . $val['comment_img']);
		@unlink(ROOT_PATH . $val['img_thumb']);
	}

	$sql = 'DELETE FROM ' . $ecs->table('comment_img') . ' WHERE user_id = \'' . $user_id . '\' AND comment_id = 0';
	$db->query($sql);
	$rec_id = (isset($_REQUEST['rec_id']) && !empty($_REQUEST['rec_id']) ? intval($_REQUEST['rec_id']) : 0);
	$sign = (isset($_REQUEST['sign']) && !empty($_REQUEST['sign']) ? intval($_REQUEST['sign']) : 0);
	$comment = get_ajax_user_order_comment_list($user_id, 0, $sign, $rec_id);
	$smarty->assign('item', $comment);
	$smarty->assign('user_id', $user_id);
	$smarty->assign('sessid', SESS_ID);
	$smarty->assign('sign', $sign);
	$result['content'] = $smarty->fetch('library/comments_form.lbi');
}

if ($is_jsonp) {
	echo $_GET['jsoncallback'] . '(' . $json->encode($result) . ')';
}
else {
	echo $json->encode($result);
}

?>
