<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class Attribute extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'attribute';
	protected $primaryKey = 'attr_id';
	public $timestamps = false;
	protected $fillable = array('cat_id', 'attr_name', 'attr_cat_type', 'attr_input_type', 'attr_type', 'attr_values', 'color_values', 'attr_index', 'sort_order', 'is_linked', 'attr_group', 'attr_input_category');
	protected $guarded = array();
}

?>
