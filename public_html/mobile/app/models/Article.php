<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class Article extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'article';
	protected $primaryKey = 'article_id';
	public $timestamps = false;
	protected $hidden = array('article_id', 'cat_id', 'is_open', 'open_type', 'author_email', 'article_type');
	protected $visible = array();
	protected $appends = array('id', 'url', 'album', 'moremohu');
	protected $fillable = array('cat_id', 'title', 'content', 'author', 'author_email', 'keywords', 'article_type', 'is_open', 'add_time', 'file_url', 'open_type', 'link', 'description');
	protected $guarded = array();

	public function extend()
	{
		return $this->hasOne('app\\models\\ArticleExtend', 'article_id', 'article_id');
	}

	public function comment()
	{
		return $this->hasMany('app\\models\\Comment', 'id_value', 'article_id');
	}

	public function goodsArticle()
	{
		return $this->hasOne('app\\models\\GoodsArticle', 'article_id', 'article_id');
	}

	public function getAddTimeAttribute()
	{
		return local_date('Y-m-d', $this->attributes['add_time']);
	}

	public function getMoreMoHuAttribute()
	{
		return friendlyDate($this->attributes['add_time'], 'moremohu');
	}

	public function getIdAttribute()
	{
		return $this->attributes['article_id'];
	}

	public function getAlbumAttribute()
	{
		$pattern = '/<[img|IMG].*?src=[\\\'|"](.*?(?:[\\.gif|\\.jpg|\\.png|\\.bmp|\\.jpeg]))[\\\'|"].*?[\\/]?>/';
		preg_match_all($pattern, $this->attributes['content'], $match);
		$album = array();

		if (0 < count($match[1])) {
			foreach ($match[1] as $img) {
				if (strtolower(substr($img, 0, 4)) != 'http') {
					$realpath = mb_substr($img, stripos($img, 'images/'));
					$album[] = get_image_path($realpath);
				}
			}
		}

		if (3 < count($album)) {
			$album = array_slice($album, 0, 3);
		}

		return $album;
	}

	public function getUrlAttribute()
	{
		return url('article/index/detail', array('id' => $this->attributes['article_id']));
	}
}

?>
