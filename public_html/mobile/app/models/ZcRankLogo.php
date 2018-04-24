<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ZcRankLogo extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'zc_rank_logo';
	public $timestamps = false;
	protected $fillable = array('logo_name', 'img', 'logo_intro');
	protected $guarded = array();
}

?>
