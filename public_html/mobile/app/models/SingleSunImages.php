<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class SingleSunImages extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'single_sun_images';
	public $timestamps = false;
	protected $fillable = array('user_id', 'order_id', 'goods_id', 'img_file', 'img_thumb', 'cont_desc', 'comment_id', 'img_type');
	protected $guarded = array();
}

?>
