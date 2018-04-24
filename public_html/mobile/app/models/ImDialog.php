<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ImDialog extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'im_dialog';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = array('id', 'customer_id', 'services_id', 'goods_id', 'store_id', 'start_time', 'end_time', 'origin');
	protected $guarded = array();
}

?>
