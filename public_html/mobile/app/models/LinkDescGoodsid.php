<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class LinkDescGoodsid extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'link_desc_goodsid';
	public $timestamps = false;
	protected $fillable = array('d_id', 'goods_id');
	protected $guarded = array();
}

?>
