<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class PicAlbum extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'pic_album';
	protected $primaryKey = 'pic_id';
	public $timestamps = false;
	protected $fillable = array('pic_name', 'album_id', 'pic_file', 'pic_thumb', 'pic_image', 'pic_size', 'pic_spec', 'ru_id', 'add_time');
	protected $guarded = array();
}

?>
