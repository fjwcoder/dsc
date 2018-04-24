<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ZcTopic extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'zc_topic';
	protected $primaryKey = 'topic_id';
	public $timestamps = false;
	protected $fillable = array('parent_topic_id', 'reply_topic_id', 'topic_status', 'topic_content', 'user_id', 'pid', 'add_time');
	protected $guarded = array();
}

?>
