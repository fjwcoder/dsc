<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ImConfigure extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'im_configure';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = array('id', 'ser_id', 'type', 'content', 'is_on');
	protected $guarded = array();
}

?>
