<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class MerchantsServer extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'merchants_server';
	protected $primaryKey = 'server_id';
	public $timestamps = false;
	protected $fillable = array('user_id', 'suppliers_desc', 'suppliers_percent');
	protected $guarded = array();
}

?>
