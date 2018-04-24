<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class Pack extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'pack';
	protected $primaryKey = 'pack_id';
	public $timestamps = false;
	protected $fillable = array('user_id', 'pack_name', 'pack_img', 'pack_fee', 'free_money', 'pack_desc');
	protected $guarded = array();
}

?>
