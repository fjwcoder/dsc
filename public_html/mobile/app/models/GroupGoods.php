<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class GroupGoods extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'group_goods';
	public $timestamps = false;
	protected $fillable = array('parent_id', 'goods_id', 'goods_price', 'admin_id', 'group_id');
	protected $guarded = array();
}

?>
