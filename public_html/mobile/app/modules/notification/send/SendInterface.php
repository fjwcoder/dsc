<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\modules\notification\send;

interface SendInterface
{
	public function __construct($config);

	public function push($to, $title, $content, $data = array());

	public function getError();
}


?>
