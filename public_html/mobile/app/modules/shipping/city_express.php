<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
class city_express
{
	/**
     * 配置信息
     */
	public $configure;

	public function city_express($cfg = array())
	{
		foreach ($cfg as $key => $val) {
			$this->configure[$val['name']] = $val['value'];
		}
	}

	public function calculate($goods_weight, $goods_amount)
	{
		if ((0 < $this->configure['free_money']) && ($this->configure['free_money'] <= $goods_amount)) {
			return 0;
		}
		else {
			return $this->configure['base_fee'];
		}
	}

	public function query($invoice_sn)
	{
		return $invoice_sn;
	}
}

defined('IN_ECTOUCH') || exit('Deny Access');

?>
