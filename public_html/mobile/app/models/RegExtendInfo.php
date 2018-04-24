<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class RegExtendInfo extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'reg_extend_info';
	protected $primaryKey = 'Id';
	public $timestamps = false;
	protected $fillable = array('user_id', 'reg_field_id', 'content');
	protected $guarded = array();
}

?>
