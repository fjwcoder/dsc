<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class AutoManage extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'auto_manage';
	public $timestamps = false;
	protected $fillable = array('item_id', 'type', 'starttime', 'endtime');
	protected $guarded = array();
}

?>
