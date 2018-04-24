<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require ROOT_PATH . '/includes/lib_visual.php';

if ($_REQUEST['act'] == 'list') {
	$available_templates = array();
	$available_templates = array();
	$dir = ROOT_PATH . 'data/home_Templates/' . $GLOBALS['_CFG']['template'] . '/';

	if (file_exists($dir)) {
		$template_dir = @opendir($dir);

		while ($file = readdir($template_dir)) {
			if (($file != '.') && ($file != '..') && ($file != '.svn') && ($file != 'index.htm')) {
				$available_templates[] = get_seller_template_info($file, 0, $GLOBALS['_CFG']['template']);
			}
		}

		$available_templates = get_array_sort($available_templates, 'sort');
		@closedir($template_dir);
	}

	$sql = 'SELECT value FROM' . $GLOBALS['ecs']->table('shop_config') . ' WHERE code= \'hometheme\' AND store_range = \'' . $GLOBALS['_CFG']['template'] . '\'';
	$default_tem = $GLOBALS['db']->getOne($sql);
	$smarty->assign('default_tem', $default_tem);
	$smarty->assign('available_templates', $available_templates);
	$smarty->display('visualhome_list.dwt');
}
else if ($_REQUEST['act'] == 'visual') {
	$des = ROOT_PATH . 'data/home_Templates/' . $GLOBALS['_CFG']['template'];
	$code = (!empty($_REQUEST['code']) ? trim($_REQUEST['code']) : '');
	if (empty($code) || !file_exists($des . '/' . $code)) {
		$redirect_url = 'visualhome.php?act=excessive';
		header('location:' . $redirect_url);
	}

	if (!file_exists($des . '/' . $code . '/nav_html.php')) {
		$sql = 'SELECT id, name, ifshow, vieworder, opennew, url, type' . ' FROM ' . $GLOBALS['ecs']->table('nav') . 'WHERE type = \'middle\'';
		$navigator = $db->getAll($sql);
		$smarty->assign('navigator', $navigator);
	}

	$filename = $des . '/' . $code . '/pc_page.php';
	$page = str_replace('http://localhost/ecmoban_dsc2.0.5_20170518', $GLOBALS['ecs']->url(), $filename);
	$arr['tem'] = $code;
	$arr['out'] = get_html_file($filename);
	$content = getleft_attr('content', 0, $arr['tem'], $GLOBALS['_CFG']['template']);
	$smarty->assign('content', $content);
	$smarty->assign('pc_page', $arr);
	$smarty->display('visualhome.dwt');
}
else if ($_REQUEST['act'] == 'file_put_visual') {
	require ROOT_PATH . '/includes/cls_json.php';
	$json = new JSON();
	$result = array('suffix' => '', 'error' => '');
	$temp = (isset($_REQUEST['temp']) ? intval($_REQUEST['temp']) : 0);
	$content = (isset($_REQUEST['content']) ? unescape($_REQUEST['content']) : '');
	$content = (!empty($content) ? stripslashes($content) : '');
	$content_html = (isset($_REQUEST['content_html']) ? unescape($_REQUEST['content_html']) : '');
	$content_html = (!empty($content_html) ? stripslashes($content_html) : '');
	$des = ROOT_PATH . 'data/home_Templates/' . $GLOBALS['_CFG']['template'];
	$suffix = (!empty($_REQUEST['suffix']) ? addslashes($_REQUEST['suffix']) : get_new_dirName(0, $des));
	$dir = ROOT_PATH . 'data/home_Templates/' . $GLOBALS['_CFG']['template'] . '/' . $suffix;
	$pc_page_name = 'pc_page.php';

	if ($temp == 1) {
		$pc_html_name = 'nav_html.php';
	}
	else if ($temp == 2) {
		$pc_html_name = 'topBanner.php';
	}
	else {
		$pc_html_name = 'pc_html.php';
	}

	$create_html = create_html($content_html, $adminru['ru_id'], $pc_html_name, $suffix, 3);
	$create = create_html($content, $adminru['ru_id'], $pc_page_name, $suffix, 3);
	$result['error'] = 0;
	$result['suffix'] = $suffix;
	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'edit_information') {
	require ROOT_PATH . '/includes/cls_json.php';
	$json = new JSON();
	$result = array('suffix' => '', 'error' => '');
	$allow_file_types = '|GIF|JPG|PNG|';
	include_once ROOT_PATH . '/includes/cls_image.php';
	$image = new cls_image($_CFG['bgcolor']);
	$check = (!empty($_REQUEST['check']) ? intval($_REQUEST['check']) : 0);
	$tem = (isset($_REQUEST['tem']) ? addslashes($_REQUEST['tem']) : '');
	$name = (isset($_REQUEST['name']) ? 'tpl name：' . addslashes($_REQUEST['name']) : 'tpl name：');
	$version = (isset($_REQUEST['version']) ? 'version：' . addslashes($_REQUEST['version']) : 'version：');
	$author = (isset($_REQUEST['author']) ? 'author：' . addslashes($_REQUEST['author']) : 'author：');
	$author_url = (isset($_REQUEST['author_url']) ? 'author_uri：' . $_REQUEST['author_url'] : 'author_uri：');
	$description = (isset($_REQUEST['description']) ? 'description：' . addslashes($_REQUEST['description']) : 'description：');

	if ($tem == '') {
		$des = ROOT_PATH . 'data/home_Templates/' . $GLOBALS['_CFG']['template'];
		$tem = get_new_dirName(0, $des);
		$code_dir = $des . '/' . $tem;

		if (!is_dir($code_dir)) {
			mkdir($code_dir, 511, true);
		}
	}

	$file_url = '';
	$format = array('png', 'gif', 'jpg');
	$file_dir = '../data/home_Templates/' . $GLOBALS['_CFG']['template'] . '/' . $tem;

	if (!is_dir($file_dir)) {
		mkdir($file_dir, 511, true);
	}

	if ((isset($_FILES['ten_file']['error']) && ($_FILES['ten_file']['error'] == 0)) || (!isset($_FILES['ten_file']['error']) && isset($_FILES['ten_file']['tmp_name']) && ($_FILES['ten_file']['tmp_name'] != 'none'))) {
		if (!check_file_type($_FILES['ten_file']['tmp_name'], $_FILES['ten_file']['name'], $allow_file_types)) {
			$result['error'] = 1;
			$result['message'] = '图片格式不正确';
			exit(json_encode($result));
		}

		if ($_FILES['ten_file']['name']) {
			$ext_cover = explode('.', $_FILES['ten_file']['name']);
			$ext_cover = array_pop($ext_cover);
		}
		else {
			$ext_cover = '';
		}

		$file_name = $file_dir . '/screenshot' . '.' . $ext_cover;

		if (move_upload_file($_FILES['ten_file']['tmp_name'], $file_name)) {
			$file_url = $file_name;
		}
	}

	if ($file_url == '') {
		$file_url = $_POST['textfile'];
	}

	if ((isset($_FILES['big_file']['error']) && ($_FILES['big_file']['error'] == 0)) || (!isset($_FILES['big_file']['error']) && isset($_FILES['big_file']['tmp_name']) && ($_FILES['big_file']['tmp_name'] != 'none'))) {
		if (!check_file_type($_FILES['big_file']['tmp_name'], $_FILES['big_file']['name'], $allow_file_types)) {
			$result['error'] = 1;
			$result['message'] = '图片格式不正确';
			exit(json_encode($result));
		}

		if ($_FILES['big_file']['name']) {
			$ext_big = explode('.', $_FILES['big_file']['name']);
			$ext_big = array_pop($ext_big);
		}
		else {
			$ext_big = '';
		}

		$file_name = $file_dir . '/template' . '.' . $ext_big;

		if (move_upload_file($_FILES['big_file']['tmp_name'], $file_name)) {
			$big_file = $file_name;
		}
	}

	$template_dir_img = @opendir($file_dir);

	while ($file = readdir($template_dir_img)) {
		foreach ($format as $val) {
			if (($val != $ext_cover) && ($ext_cover != '')) {
				if (file_exists($file_dir . '/screenshot.' . $val)) {
					@unlink($file_dir . '/screenshot.' . $val);
				}
			}

			if (($val != $ext_big) && ($ext_big != '')) {
				if (file_exists($file_dir . '/template.' . $val)) {
					@unlink($file_dir . '/template.' . $val);
				}
			}
		}
	}

	@closedir($template_dir_img);
	$end = '------tpl_info------------';
	$tab = "\n";
	$html = $end . $tab . $name . $tab . 'tpl url：' . $file_url . $tab . $description . $tab . $version . $tab . $author . $tab . $author_url . $tab . $end;

	if (file_put_contents($file_dir . '/tpl_info.txt', iconv('UTF-8', 'GB2312', $html), LOCK_EX) === false) {
		$result['error'] = 1;
		$result['message'] = $file_dir . '/tpl_info.txt没有写入权限，请修改权限';
	}
	else {
		if ($check == 1) {
			$seller_dir = ROOT_PATH . 'data/home_Templates/' . $GLOBALS['_CFG']['template'] . '/';
			$template_dir = @opendir($seller_dir);

			while ($file = readdir($template_dir)) {
				if (($file != '.') && ($file != '..') && ($file != '.svn') && ($file != 'index.htm')) {
					$available_templates[] = get_seller_template_info($file, 0, $GLOBALS['_CFG']['template']);
				}
			}

			$available_templates = get_array_sort($available_templates, 'sort');
			@closedir($template_dir);
			$smarty->assign('available_templates', $available_templates);
			$sql = 'SELECT value FROM' . $GLOBALS['ecs']->table('shop_config') . ' WHERE code= \'hometheme\' AND store_range = \'' . $GLOBALS['_CFG']['template'] . '\'';
			$default_tem = $GLOBALS['db']->getOne($sql);
			$smarty->assign('default_tem', $default_tem);
			$smarty->assign('temp', 'homeTemplates');
			$result['content'] = $GLOBALS['smarty']->fetch('library/dialog.lbi');
		}

		$result['error'] = 0;
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'removeTemplate') {
	require ROOT_PATH . '/includes/cls_json.php';
	$json = new JSON();
	$result = array('error' => '', 'content' => '', 'url' => '');
	$code = (isset($_REQUEST['code']) ? addslashes($_REQUEST['code']) : '');
	$sql = 'SELECT value FROM' . $GLOBALS['ecs']->table('shop_config') . ' WHERE code= \'hometheme\'AND store_range = \'' . $GLOBALS['_CFG']['template'] . '\'';
	$default_tem = $GLOBALS['db']->getOne($sql);

	if ($default_tem == $code) {
		$result['error'] = 1;
		$result['content'] = '该模板正在使用中，不能删除！欲删除请先更改模板！';
	}
	else {
		$dir = ROOT_PATH . 'data/home_Templates/' . $GLOBALS['_CFG']['template'] . '/' . $code;
		$rmdir = del_DirAndFile($dir);

		if ($rmdir == true) {
			$sql = 'DELETE FROM' . $ecs->table('templates_left') . 'WHERE seller_templates = \'' . $code . '\' AND theme = \'' . $GLOBALS['_CFG']['template'] . '\'';
			$db->query($sql);
			$result['error'] = 0;
			$seller_dir = ROOT_PATH . 'data/home_Templates/' . $GLOBALS['_CFG']['template'] . '/';
			$template_dir = @opendir($seller_dir);

			while ($file = readdir($template_dir)) {
				if (($file != '.') && ($file != '..') && ($file != '.svn') && ($file != 'index.htm')) {
					$available_templates[] = get_seller_template_info($file, 0, $GLOBALS['_CFG']['template']);
				}
			}

			$available_templates = get_array_sort($available_templates, 'sort');
			@closedir($template_dir);
			$smarty->assign('available_templates', $available_templates);
			$smarty->assign('default_tem', $default_tem);
			$smarty->assign('temp', 'homeTemplates');
			$result['content'] = $GLOBALS['smarty']->fetch('library/dialog.lbi');
		}
		else {
			$result['error'] = 1;
			$result['content'] = '系统出错，请重试！';
		}
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'setupTemplate') {
	require ROOT_PATH . '/includes/cls_json.php';
	$json = new JSON();
	$result = array('error' => '', 'content' => '', 'url' => '');
	$code = (isset($_REQUEST['code']) ? trim($_REQUEST['code']) : '');
	$dir = ROOT_PATH . 'data/home_Templates/' . $GLOBALS['_CFG']['template'] . '/' . $code;
	if (file_exists($dir) && $code) {
		$sql = 'UPDATE' . $ecs->table('shop_config') . 'SET value=\'' . $code . '\',store_range = \'' . $GLOBALS['_CFG']['template'] . '\'  WHERE code = \'hometheme\'';
		$db->query($sql);
		$result['error'] = 0;
	}
	else {
		$result['error'] = 1;
		$result['message'] = '改模板不存在，请检查';
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'export_tem') {
	$checkboxes = (!empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : array());

	if (!empty($checkboxes)) {
		include_once 'includes/cls_phpzip.php';
		$zip = new PHPZip();
		$dir = ROOT_PATH . 'data/home_Templates/' . $GLOBALS['_CFG']['template'] . '/';
		$dir_zip = $dir;
		$file_mune = array();

		foreach ($checkboxes as $v) {
			if ($v) {
				$addfiletozip = $zip->get_filelist($dir_zip . $v);

				foreach ($addfiletozip as $k => $val) {
					if ($v) {
						$addfiletozip[$k] = $v . '/' . $val;
					}
				}

				$file_mune = array_merge($file_mune, $addfiletozip);
			}
		}

		foreach ($file_mune as $v) {
			if (file_exists($dir . '/' . $v)) {
				$zip->add_file(file_get_contents($dir . '/' . $v), $v);
			}
		}

		header('Cache-Control: max-age=0');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename=templates_list.zip');
		header('Content-Type: application/zip');
		header('Content-Transfer-Encoding: binary');
		header('Content-Type: application/unknown');
		exit($zip->file());
	}
	else {
		$link[0]['text'] = '返回列表';
		$link[0]['href'] = 'visualhome.php?act=list';
		sys_msg('请选择导出的模板', 1, $link);
	}
}
else if ($_REQUEST['act'] == 'model_delete') {
	require ROOT_PATH . '/includes/cls_json.php';
	$json = new JSON();
	$result = array('error' => '', 'message' => '');
	$code = (isset($_REQUEST['suffix']) ? trim($_REQUEST['suffix']) : '');
	$dir = ROOT_PATH . 'data/home_Templates/' . $GLOBALS['_CFG']['template'] . '/' . $code;
	if (empty($code) && file_exists($dir)) {
		$result['error'] = 1;
		$result['message'] = '改模板不存在，请刷新重试';
	}
	else {
		if (file_exists($dir . '/topBanner.php')) {
			unlink($dir . '/topBanner.php');
		}

		$result['error'] = 0;
	}

	exit(json_encode($result));
}

?>
