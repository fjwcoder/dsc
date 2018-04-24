<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ReturnCause extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'return_cause';
	protected $primaryKey = 'cause_id';
	public $timestamps = false;
	protected $fillable = array('cause_name', 'parent_id', 'sort_order', 'is_show');
	protected $guarded = array();
}

?>
