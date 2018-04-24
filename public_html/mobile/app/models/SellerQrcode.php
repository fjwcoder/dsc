<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class SellerQrcode extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'seller_qrcode';
	protected $primaryKey = 'qrcode_id';
	public $timestamps = false;
	protected $fillable = array('ru_id', 'qrcode_thumb');
	protected $guarded = array();
}

?>
