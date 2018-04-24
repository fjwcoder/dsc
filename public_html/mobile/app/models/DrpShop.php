<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class DrpShop extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'drp_shop';
	public $timestamps = false;
	protected $fillable = array('user_id', 'shop_name', 'real_name', 'mobile', 'qq', 'shop_img', 'cat_id', 'goods_id', 'create_time', 'isbuy', 'audit', 'status', 'shop_money', 'shop_points');
	protected $guarded = array();
}

?>
