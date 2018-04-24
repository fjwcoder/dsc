<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class Topic extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'topic';
	public $timestamps = false;
	protected $fillable = array('topic_id', 'user_id', 'title', 'intro', 'start_time', 'end_time', 'data', 'template', 'css', 'topic_img', 'title_pic', 'base_style', 'htmls', 'keywords', 'description');
	protected $guarded = array();
}

?>
