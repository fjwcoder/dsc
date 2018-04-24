<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class GoodsType extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'goods_type';
	protected $primaryKey = 'cat_id';
	public $timestamps = false;
	protected $fillable = array('user_id', 'cat_name', 'enabled', 'attr_group');
	protected $guarded = array();
}

?>
