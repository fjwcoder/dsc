<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class OpenApi extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'open_api';
	public $timestamps = false;
	protected $fillable = array('name', 'app_key', 'action_code', 'is_open', 'add_time');
	protected $guarded = array();
}

?>
