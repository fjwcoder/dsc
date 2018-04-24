<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class GoodsTransportExtend extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'goods_transport_extend';
	public $timestamps = false;
	protected $fillable = array('tid', 'ru_id', 'admin_id', 'area_id', 'top_area_id', 'sprice');
	protected $guarded = array();
}

?>
