<?php
define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require ROOT_PATH . '/includes/lib_area.php';
assign_template();
if ($_REQUEST['act'] == 'service') {
	$user_id = $_SESSION['user_id'];
	$IM_menu = $ecs->url() . '/online.php?act=service_menu';
	$goods = goods_info(intval($_GET['goods_id']));  
  
	$goods['goods_name'] = (isset($_GET['goods_id'])) ? $goods['goods_name']: '右下角客服中心' ;  
	$_SERVER['HTTP_REFERER'] = (isset($_GET['goods_id'])) ?'https://'.$_SERVER['HTTP_HOST'].'/goods.php?id='.$_GET['goods_id'] :$_SERVER['HTTP_REFERER']  ;  
	$url = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER']: 'https://'.$_SERVER['HTTP_HOST'].'/' ;
  
  
	$ru_id = intval($_GET['ru_id']);

	if (!empty($ru_id)) {
		$ru_id = $ru_id;
	}
	else {
		$ru_id = $goods['user_id'];
	}

	$sql = 'select kf_appkey,kf_secretkey,kf_touid, kf_logo, kf_welcomeMsg from ' . $ecs->table('seller_shopinfo') . ' where ru_id=\'' . $ru_id . '\'';
	$basic_info = $db->getRow($sql);
	IM($basic_info['kf_appkey'], $basic_info['kf_secretkey']);
	if (empty($basic_info['kf_logo']) || ($basic_info['kf_logo'] == 'http://')) {
		$basic_info['kf_logo'] = 'http://dsc-kf.oss-cn-shanghai.aliyuncs.com/dsc_kf/p16812444.jpg';
	}

	if ($user_id) {
		$user_info = user_info($_SESSION['user_id']);
		$user_info['user_name'] = $_SESSION['user_name'];
		$user_info['user_id'] = 'uid' . $_SESSION['user_id'];

		if (empty($user_info['user_picture'])) {
			$user_logo = 'http://dsc-kf.oss-cn-shanghai.aliyuncs.com/dsc_kf/dsc_kf_user_logo.jpg';
		}
		else {
			$user_logo = $ecs->get_domain() . '/' . $user_info['user_picture'];
		}
	}
	else {
		$user_info['user_id'] = 'uid'.$_COOKIE['ECS_ID'];
		$user_info['user_name'] = '游客' ;
		$user_logo = 'http://dsc-kf.oss-cn-shanghai.aliyuncs.com/dsc_kf/dsc_kf_user_logo.jpg';
	}

	$smarty->assign('user_id', $user_info['user_id']);
	$smarty->assign('user_name', $user_info['user_name']);
	$smarty->assign('user_logo', $user_logo);
	$smarty->assign('kf_appkey', $basic_info['kf_appkey']);
	$smarty->assign('kf_touid', $basic_info['kf_touid']);
	$smarty->assign('kf_logo', $basic_info['kf_logo']);
	$smarty->assign('kf_welcomeMsg', $basic_info['kf_welcomeMsg']);
	$smarty->assign('IM_menu', $IM_menu);
	$smarty->assign('goods_name', $goods['goods_name']);
	$smarty->assign('url', $url);
	$smarty->display('chats.dwt');
}

if ($_REQUEST['act'] == 'service_menu') {
	$smarty->display('chats_menu.dwt');
}

if ($_REQUEST['act'] == 'history') {
	$request = json_decode($_POST['q'], true);
	$itemId = $request['itemsId'][0];
	$url = $ecs->url();
	echo $current_url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	exit();
	$goods = goods_info($itemId);
	echo "    {\r\n    \"code\": \"200\",\r\n    \"desc\": \"powered by 366802485\",\r\n    \"itemDetail\": [\r\n            {\r\n                \"userid\": \"" . $request['userid'] . "\",\r\n                \"itemid\": \"" . $itemId . "\",\r\n                \"itemname\": \"" . $goods['goods_name'] . "\",\r\n                \"itempic\": \"" . $url . $goods['goods_thumb'] . "\",\r\n                \"itemprice\": \"" . $goods['shop_price'] . "\",\r\n                \"itemurl\": \"" . $current_url . "\",\r\n                \"extra\": {}\r\n            }\r\n        ]\r\n    }";
}

?>
