<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class Role extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'role';
	protected $primaryKey = 'role_id';
	public $timestamps = false;
	protected $fillable = array('role_name', 'action_list', 'role_describe');
	protected $guarded = array();
}

?>
