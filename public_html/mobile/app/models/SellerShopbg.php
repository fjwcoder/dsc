<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class SellerShopbg extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'seller_shopbg';
	public $timestamps = false;
	protected $fillable = array('bgimg', 'bgrepeat', 'bgcolor', 'show_img', 'is_custom', 'ru_id', 'seller_theme');
	protected $guarded = array();
}

?>
