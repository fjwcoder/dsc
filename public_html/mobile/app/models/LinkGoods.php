<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class LinkGoods extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'link_goods';
	public $timestamps = false;
	protected $fillable = array('goods_id', 'link_goods_id', 'is_double', 'admin_id');
	protected $guarded = array();
}

?>
