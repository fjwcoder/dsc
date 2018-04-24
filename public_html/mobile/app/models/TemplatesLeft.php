<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class TemplatesLeft extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'templates_left';
	public $timestamps = false;
	protected $fillable = array('ru_id', 'seller_templates', 'bg_color', 'img_file', 'if_show', 'bgrepeat', 'align', 'type');
	protected $guarded = array();
}

?>
