<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class Wholesale extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'wholesale';
	protected $primaryKey = 'act_id';
	public $timestamps = false;
	protected $fillable = array('user_id', 'goods_id', 'goods_name', 'rank_ids', 'prices', 'enabled');
	protected $guarded = array();
}

?>
