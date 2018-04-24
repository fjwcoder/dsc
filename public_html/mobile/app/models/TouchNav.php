<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class TouchNav extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'touch_nav';
	public $timestamps = false;
	protected $fillable = array('ctype', 'cid', 'name', 'ifshow', 'vieworder', 'opennew', 'url', 'type', 'pic');
	protected $guarded = array();
}

?>
