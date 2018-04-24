<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class BrandExtend extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'brand_extend';
	public $timestamps = false;
	protected $fillable = array('brand_id', 'is_recommend');
	protected $guarded = array();
}

?>
