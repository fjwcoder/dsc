<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\api\v2\index\controllers;

class Index extends \app\api\Controller
{
	public function actionIndex()
	{
		$method = 'method=ecapi.goods.img.get&app_key=123&sign_method=md5&sign=111&timestamp=2015-01-01 12:00:00&format=json&v=1';
		$params = array('id' => 11);
		$instance = $this->getMethod($method);

		if (class_exists($instance['class'])) {
			$handler = new $instance['class']();
			$data = $handler->$instance['method']($params);
			$this->response($data);
		}
		else {
			$this->response(array('msg' => 'api not found.'), 'json', 404);
		}
	}

	private function getMethod($method, $version = 'v1')
	{
		$method = str_replace('ecapi', 'app.repositories.' . $version, $method);
		$class = '\\';
		$res = explode('.', $method);
		$length = count($res);

		foreach ($res as $key => $vo) {
			if ((2 < $key) && ($key < ($length - 1))) {
				$class .= ucfirst($vo);
			}
			else {
				$class .= $vo . '\\';
			}

			if (($length - 2) == $key) {
				break;
			}
		}

		return array('class' => $class, 'method' => end($res));
	}

	private function convertUnderline($str, $ucfirst = true)
	{
		$str = ucwords(str_replace('_', ' ', $str));
		$str = str_replace(' ', '', lcfirst($str));
		return $ucfirst ? ucfirst($str) : $str;
	}
}

?>
