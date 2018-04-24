<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class Nav extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'nav';
	public $timestamps = false;
	protected $fillable = array('ctype', 'cid', 'name', 'ifshow', 'vieworder', 'opennew', 'url', 'type');
	protected $guarded = array();
}

?>
