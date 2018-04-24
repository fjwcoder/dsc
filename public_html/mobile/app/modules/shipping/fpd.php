<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
class fpd
{
	/**
     * 配置信息
     */
	public $configure;

	public function fpd($cfg = array())
	{
	}

	public function calculate($goods_weight, $goods_amount)
	{
		return 0;
	}

	public function query($invoice_sn)
	{
		return $invoice_sn;
	}
}

defined('IN_ECTOUCH') || exit('Deny Access');

?>
