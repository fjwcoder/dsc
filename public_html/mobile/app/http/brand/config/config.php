<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
return array(
	'HTML_CACHE_ON'    => true,
	'HTML_CACHE_TIME'  => 60,
	'HTML_FILE_SUFFIX' => '.shtml',
	'HTML_CACHE_RULES' => array(
		'*' => array('{$_SERVER.REQUEST_URI|md5}')
		)
	);

?>
