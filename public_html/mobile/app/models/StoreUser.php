<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class StoreUser extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'store_user';
	public $timestamps = false;
	protected $fillable = array('ru_id', 'store_id', 'parent_id', 'stores_user', 'stores_pwd', 'tel', 'email', 'store_action', 'add_time', 'ec_salt', 'store_user_img');
	protected $guarded = array();
}

?>
