<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
class cod
{
	public function get_code($order, $payment)
	{
		return '';
	}

	public function response()
	{
		return NULL;
	}

	public function callback($data)
	{
	}

	public function notify($data)
	{
	}

	public function query($order, $payment)
	{
	}
}

defined('IN_ECTOUCH') || exit('Deny Access');

?>
