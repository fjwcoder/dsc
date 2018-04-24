<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class UserRank extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'user_rank';
	protected $primaryKey = 'rank_id';
	public $timestamps = false;
	protected $fillable = array('rank_name', 'min_points', 'max_points', 'discount', 'show_price', 'special_rank');
	protected $guarded = array();
}

?>
