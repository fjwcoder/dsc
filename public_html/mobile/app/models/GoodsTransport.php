<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class GoodsTransport extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'goods_transport';
	protected $primaryKey = 'tid';
	public $timestamps = false;
	protected $fillable = array('ru_id', 'type', 'title', 'update_time');
	protected $guarded = array();
}

?>
