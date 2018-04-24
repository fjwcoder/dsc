<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class FloorContent extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'floor_content';
	protected $primaryKey = 'fb_id';
	public $timestamps = false;
	protected $fillable = array('filename', 'region', 'id_name', 'brand_id', 'brand_name', 'theme');
	protected $guarded = array();
}

?>
