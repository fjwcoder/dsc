<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class MerchantsStepsProcess extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'merchants_steps_process';
	public $timestamps = false;
	protected $fillable = array('process_steps', 'process_title', 'process_article', 'steps_sort', 'is_show', 'fields_next');
	protected $guarded = array();
}

?>
