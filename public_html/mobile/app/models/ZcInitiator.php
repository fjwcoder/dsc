<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ZcInitiator extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'zc_initiator';
	public $timestamps = false;
	protected $fillable = array('name', 'company', 'img', 'intro', 'describe', 'rank');
	protected $guarded = array();
}

?>
