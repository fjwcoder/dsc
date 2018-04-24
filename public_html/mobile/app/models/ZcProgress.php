<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ZcProgress extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'zc_progress';
	public $timestamps = false;
	protected $fillable = array('pid', 'progress', 'add_time', 'img');
	protected $guarded = array();
}

?>
