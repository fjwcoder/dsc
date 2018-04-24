<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class Template extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'template';
	public $timestamps = false;
	protected $fillable = array('filename', 'region', 'library', 'sort_order', 'number', 'type', 'theme', 'remarks');
	protected $guarded = array();
}

?>
