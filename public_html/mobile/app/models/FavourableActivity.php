<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class FavourableActivity extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'favourable_activity';
	protected $primaryKey = 'act_id';
	public $timestamps = false;
	protected $fillable = array('act_name', 'start_time', 'end_time', 'user_rank', 'act_range', 'act_range_ext', 'min_amount', 'max_amount', 'act_type', 'act_type_ext', 'activity_thumb', 'gift', 'sort_order', 'user_id', 'userFav_type');
	protected $guarded = array();
}

?>
