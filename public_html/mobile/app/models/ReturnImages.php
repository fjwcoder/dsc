<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ReturnImages extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'return_images';
	public $timestamps = false;
	protected $fillable = array('rg_id', 'rec_id', 'user_id', 'img_file', 'add_time');
	protected $guarded = array();
}

?>
