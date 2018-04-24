<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class MerchantsGrade extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'merchants_grade';
	public $timestamps = false;
	protected $fillable = array('ru_id', 'grade_id', 'add_time', 'year_num', 'amount');
	protected $guarded = array();
}

?>
