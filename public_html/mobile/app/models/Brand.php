<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class Brand extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'brand';
	protected $primaryKey = 'brand_id';
	public $timestamps = false;
	protected $fillable = array('brand_name', 'brand_letter', 'brand_logo', 'brand_desc', 'site_url', 'sort_order', 'is_show', 'is_delete', 'audit_status', 'add_time');
	protected $guarded = array();
}

?>
