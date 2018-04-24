<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class CollectStore extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'collect_store';
	protected $primaryKey = 'rec_id';
	public $timestamps = false;
	protected $fillable = array('user_id', 'ru_id', 'add_time', 'is_attention');
	protected $guarded = array();
}

?>
