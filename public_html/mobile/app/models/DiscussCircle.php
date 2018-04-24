<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class DiscussCircle extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'discuss_circle';
	protected $primaryKey = 'dis_id';
	public $timestamps = false;
	protected $fillable = array('dis_browse_num', 'like_num', 'parent_id', 'quote_id', 'goods_id', 'user_id', 'order_id', 'dis_type', 'dis_title', 'dis_text', 'add_time', 'user_name');
	protected $guarded = array();
}

?>
