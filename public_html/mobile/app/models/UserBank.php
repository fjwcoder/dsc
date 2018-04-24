<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class UserBank extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'user_bank';
	public $timestamps = false;
	protected $fillable = array('bank_name', 'bank_card', 'bank_region', 'bank_user_name', 'user_id');
	protected $guarded = array();
}

?>
