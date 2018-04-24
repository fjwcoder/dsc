<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class MerchantsGoodsComment extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'merchants_goods_comment';
	public $timestamps = false;
	protected $fillable = array('goods_id', 'comment_start', 'comment_end', 'comment_last_percent');
	protected $guarded = array();
}

?>
