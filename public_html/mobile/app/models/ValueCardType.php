<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ValueCardType extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'value_card_type';
	public $timestamps = false;
	protected $fillable = array('name', 'vc_desc', 'vc_value', 'vc_prefix', 'vc_dis', 'vc_limit', 'use_condition', 'spec_goods', 'spec_cat', 'vc_indate', 'is_rec', 'add_time');
	protected $guarded = array();
}

?>
