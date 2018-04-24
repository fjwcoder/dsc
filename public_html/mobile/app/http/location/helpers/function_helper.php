<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
function getLetter($str)
{
	$i = 0;

	while ($i < strlen($str)) {
		$tmp = bin2hex(substr($str, $i, 1));

		if ('B0' <= $tmp) {
			$object = new \Touch\Pinyin();
			$pyobj = $object->output($str);
			$pinyin = (isset($pyobj[0]) ? $pyobj[0] : '');
			return strtoupper(substr($pinyin, 0, 1));
			$i += 2;
		}
		else {
			return strtoupper(substr($str, $i, 1));
			$i++;
		}
	}
}


?>
