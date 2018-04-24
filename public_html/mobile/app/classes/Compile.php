<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\classes;

class Compile
{
	static public $savePath = '';

	static public function init()
	{
		self::$savePath = ROOT_PATH . 'storage/app/diy';

		if (!is_dir(self::$savePath)) {
			$fs = new \Symfony\Component\Filesystem\Filesystem();
			$fs->mkdir(self::$savePath);
		}
	}

	static public function setModule($file = 'index', $data = array())
	{
		self::init();

		if (!empty($data)) {
			$data = '<?php exit("no access");' . serialize($data);
			file_put_contents(self::$savePath . '/' . $file . '.php', $data);
		}
	}

	static public function getModule($file = 'index')
	{
		self::init();
		$filePath = self::$savePath . '/' . $file . '.php';

		if (is_file($filePath)) {
			$data = file_get_contents($filePath);
			$data = str_replace('<?php exit("no access");', '', $data);
			return unserialize($data);
		}

		return false;
	}

	static public function cleanModule($file = 'index')
	{
		self::init();
		$filePath = self::$savePath . '/' . $file . '.php';

		if (is_file($filePath)) {
			return unlink($filePath);
		}

		return true;
	}

	static public function replace_img($data)
	{
		$data = str_replace(array('http://localhost/'), '/', $data);
		return str_replace(array('/ecmoban0309/', '/dscmall/'), rtrim(dirname(__URL__), '/') . '/', $data);
	}

	static public function initModule()
	{
		$data = array();
		$data = unserialize(str_replace('<?php exit("no access");', '', file_get_contents(ROOT_PATH . 'storage/app/diy/default.php')));

		foreach ($data as $key => $value) {
			$data[$key]['moreLink'] = self::replace_img($value['moreLink']);
			$data[$key]['icon'] = self::replace_img($value['icon']);

			if (isset($value['data']['icon'])) {
				$data[$key]['data']['icon'] = self::replace_img($value['data']['icon']);
			}

			if (isset($value['data']['moreLink'])) {
				$data[$key]['data']['moreLink'] = self::replace_img($value['data']['moreLink']);
			}

			foreach ($value['data']['imgList'] as $ke => $val) {
				if (isset($val['img'])) {
					$data[$key]['data']['imgList'][$ke]['img'] = self::replace_img($val['img']);
				}

				if (isset($val['link'])) {
					$data[$key]['data']['imgList'][$ke]['link'] = self::replace_img($val['link']);
				}
			}

			foreach ($value['data']['contList'] as $ke => $val) {
				if (isset($val['url'])) {
					$data[$key]['data']['contList'][$ke]['url'] = self::replace_img($val['url']);
				}
			}
		}

		self::setModule('index', $data);
		return $data;
		$search = self::load('search');
		$data[] = $search;
		$slider = self::load('picture');
		$res = insert_ads(array('id' => 256, 'num' => 10), true);
		$picImgList = array();

		foreach ($res as $key => $vo) {
			$picImgList[$key] = array('desc' => '', 'img' => get_data_path($vo['ad_code'], 'afficheimg'), 'link' => $vo['ad_link']);
		}

		$slider['data']['imgList'] = $picImgList;
		$data[] = $slider;
		$nav = self::load('nav');
		$res = dao('touch_nav')->where('ifshow=1')->order('vieworder asc, id asc')->select();
		$navImgList = array();

		foreach ($res as $key => $vo) {
			$navImgList[$key] = array('desc' => $vo['name'], 'img' => get_image_path('data/attached/nav/' . $vo['pic']), 'link' => $vo['url']);
		}

		$nav['data']['imgList'] = $navImgList;
		$data[] = $nav;
		$notice = self::load('Announcement');
		$condition = array('is_open' => 1, 'cat_id' => 12);
		$list = dao('article')->field('article_id, title, author, add_time, file_url, open_type')->where($condition)->order('article_type DESC, article_id DESC')->limit(5)->select();
		$res = array();

		foreach ($list as $key => $vo) {
			$res[$key]['text'] = $vo['title'];
			$res[$key]['url'] = build_uri('article', array('aid' => $vo['article_id']));
		}

		$notice['data']['contList'] = $res;
		$data[] = $notice;
		$slider = self::load('picture');
		$slider['data']['isStyleSel'] = 1;
		$slider['data']['isSizeSel'] = 1;
		$slider['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('index_top_adt.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('index_top_ads.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $slider;
		$blank = self::load('Blank');
		$blank['data']['valueHeight'] = 5;
		$data[] = $blank;
		$spike = self::load('Spike');
		$list = self::goodsList(array('intro' => 'promotion'));
		$res = array();
		$endtime = gmtime();

		foreach ($list as $key => $vo) {
			$res[$key]['desc'] = $vo['name'];
			$res[$key]['sale'] = $vo['sales_volume'];
			$res[$key]['stock'] = $vo['goods_number'];
			$res[$key]['price'] = $vo['shop_price'];
			$res[$key]['marketPrice'] = $vo['market_price'];
			$res[$key]['img'] = $vo['goods_thumb'];
			$res[$key]['link'] = $vo['url'];
			$endtime = ($endtime < $vo['promote_end_date'] ? $vo['promote_end_date'] : $endtime);
		}

		$spike['data']['moreLink'] = url('category/index/search', array('intro' => 'new'));
		$spike['data']['imgList'] = $res;
		$spike['data']['endTime'] = date('Y-m-d H:i:s', $endtime);
		$data[] = $spike;
		$blank = self::load('Blank');
		$blank['data']['valueHeight'] = 5;
		$data[] = $blank;
		$slider = self::load('picture');
		$slider['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('index_banner_1.png', 'afficheimg'), 'link' => '')
	);
		$data[] = $slider;
		$blank = self::load('Blank');
		$blank['data']['valueHeight'] = 5;
		$data[] = $blank;
		$ad_block_config = array(
			'widthSize'    => '33.33333%',
			'isStyleSel'   => '1',
			'isTextSel'    => '1',
			'isBorderSel'  => '0',
			'isGapSel'     => array(),
			'showGapClass' => array('nav-gap-in' => false, 'nav-gap-out' => false, 'nav-border' => true),
			'isShowText'   => false,
			'isShowBorder' => false
			);
		$slider = self::load('picture');
		$slider['data']['isStyleSel'] = 1;
		$slider['data']['isSizeSel'] = 1;
		$slider['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('index_title_1.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $slider;
		$ads = self::load('nav');
		$ads['data'] = array_merge($ads['data'], $ad_block_config);
		$ads['data']['isStyleSel'] = '0';
		$ads['data']['widthSize'] = '50%';
		$ads['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('ads/b2.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('ads/b1.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $ads;
		$ads = self::load('nav');
		$ads['data'] = array_merge($ads['data'], $ad_block_config);
		$ads['data']['isStyleSel'] = '0';
		$ads['data']['widthSize'] = '50%';
		$ads['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('ads/b1.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('ads/b2.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $ads;
		$ads = self::load('nav');
		$ads['data'] = array_merge($ads['data'], $ad_block_config);
		$ads['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('ads/a1.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('ads/a2.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('ads/a3.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $ads;
		$slider = self::load('picture');
		$slider['data']['isStyleSel'] = 1;
		$slider['data']['isSizeSel'] = 1;
		$slider['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('index_title_2.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $slider;
		$ads = self::load('nav');
		$ads['data'] = array_merge($ads['data'], $ad_block_config);
		$ads['data']['isStyleSel'] = '0';
		$ads['data']['widthSize'] = '50%';
		$ads['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('ads/b2.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('ads/b1.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $ads;
		$ads = self::load('nav');
		$ads['data'] = array_merge($ads['data'], $ad_block_config);
		$ads['data']['isStyleSel'] = '0';
		$ads['data']['widthSize'] = '50%';
		$ads['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('ads/b1.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('ads/b2.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $ads;
		$ads = self::load('nav');
		$ads['data'] = array_merge($ads['data'], $ad_block_config);
		$ads['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('ads/a1.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('ads/a2.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('ads/a3.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $ads;
		$slider = self::load('picture');
		$slider['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('index_banner_2.png', 'afficheimg'), 'link' => '')
	);
		$data[] = $slider;
		$slider = self::load('picture');
		$slider['data']['isStyleSel'] = 1;
		$slider['data']['isSizeSel'] = 1;
		$slider['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('index_title_3.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $slider;
		$ads = self::load('nav');
		$ads['data'] = array_merge($ads['data'], $ad_block_config);
		$ads['data']['isStyleSel'] = '0';
		$ads['data']['widthSize'] = '50%';
		$ads['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('ads/b2.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('ads/b1.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $ads;
		$ads = self::load('nav');
		$ads['data'] = array_merge($ads['data'], $ad_block_config);
		$ads['data']['isStyleSel'] = '0';
		$ads['data']['widthSize'] = '50%';
		$ads['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('ads/b1.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('ads/b2.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $ads;
		$ads = self::load('nav');
		$ads['data'] = array_merge($ads['data'], $ad_block_config);
		$ads['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('ads/a1.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('ads/a2.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('ads/a3.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $ads;
		$slider = self::load('picture');
		$slider['data']['isStyleSel'] = 1;
		$slider['data']['isSizeSel'] = 1;
		$slider['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('index_title_4.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $slider;
		$ads = self::load('nav');
		$ads['data'] = array_merge($ads['data'], $ad_block_config);
		$ads['data']['isStyleSel'] = '0';
		$ads['data']['widthSize'] = '50%';
		$ads['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('ads/b2.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('ads/b1.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $ads;
		$ads = self::load('nav');
		$ads['data'] = array_merge($ads['data'], $ad_block_config);
		$ads['data']['isStyleSel'] = '0';
		$ads['data']['widthSize'] = '50%';
		$ads['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('ads/b1.jpg', 'afficheimg'), 'link' => ''),
	array('desc' => '', 'img' => get_data_path('ads/b2.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $ads;
		$slider = self::load('picture');
		$slider['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('index_banner_3.png', 'afficheimg'), 'link' => '')
	);
		$data[] = $slider;
		$slider = self::load('picture');
		$slider['data']['isStyleSel'] = 1;
		$slider['data']['isSizeSel'] = 1;
		$slider['data']['imgList'] = array(
	array('desc' => '', 'img' => get_data_path('index_title_5.jpg', 'afficheimg'), 'link' => '')
	);
		$data[] = $slider;
		$product = self::load('Product');
		$list = self::goodsList(array('intro' => 'hot'));
		$res = array();

		foreach ($list as $key => $vo) {
			$res[$key]['desc'] = $vo['name'];
			$res[$key]['sale'] = $vo['sales_volume'];
			$res[$key]['stock'] = $vo['goods_number'];
			$res[$key]['price'] = $vo['shop_price'];
			$res[$key]['marketPrice'] = $vo['market_price'];
			$res[$key]['img'] = $vo['goods_thumb'];
			$res[$key]['link'] = $vo['url'];
		}

		$product['data']['imgList'] = $res;
		$product['data']['isTagSel'] = array('-1', '-1');
		$data[] = $product;
		self::setModule('index', $data);
		return $data;
	}

	static public function load($module = '')
	{
		$modulePath = BASE_PATH . 'modules/components/' . ucfirst($module) . '.php';
		if (!empty($module) && is_file($modulePath)) {
			return require $modulePath;
		}

		return false;
	}

	static public function goodsList($param = array())
	{
		$data = array('id' => 0, 'brand' => 0, 'intro' => '', 'price_min' => 0, 'price_max' => 0, 'filter_attr' => 0, 'sort' => 'goods_id', 'order' => 'desc', 'keyword' => '', 'isself' => 0, 'hasgoods' => 0, 'promotion' => 0, 'page' => 1, 'type' => 1, 'size' => 10, config('VAR_AJAX_SUBMIT') => 1);
		$data = array_merge($data, $param);
		$cache_id = md5(serialize($data));
		$list = cache($cache_id);

		if ($list === false) {
			$url = url('category/index/products', $data, false, true);
			$res = \Touch\Http::doGet($url);

			if ($res) {
				$data = json_decode($res, 1);
				$list = (empty($data['list']) ? false : $data['list']);
				cache($cache_id, $list, 600);
			}
		}

		return $list;
	}
}


?>
