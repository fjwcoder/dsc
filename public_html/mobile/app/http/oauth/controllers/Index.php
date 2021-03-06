<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\http\oauth\controllers;

class Index extends \app\http\base\controllers\Frontend
{
	public function __construct()
	{
		parent::__construct();
		L(require LANG_PATH . C('shop.lang') . '/other.php');
		$this->load_helper('passport');
	}

	public function actionIndex()
	{
		$type = I('get.type');
		$back_url = I('get.back_url', '', 'urldecode');
		$file = ADDONS_PATH . 'connect/' . $type . '.php';

		if (file_exists($file)) {
			include_once $file;
		}
		else {
			show_message(L('msg_plug_notapply'), L('msg_go_back'), url('user/login/index'));
		}

		$url = url('/', array(), false, true);
		$param = array('m' => 'oauth', 'type' => $type, 'back_url' => empty($back_url) ? url('user/index/index') : $back_url);
		$url .= '?' . http_build_query($param, '', '&');
		$config = $this->getOauthConfig($type);

		if (!$config) {
			show_message(L('msg_plug_notapply'), L('msg_go_back'), url('user/login/index'));
		}

		$obj = new $type($config);
		if (isset($_GET['code']) && ($_GET['code'] != '')) {
			if ($res = $obj->callback($url, $_GET['code'])) {
				$param = get_url_query($back_url);
				$up_uid = get_affiliate();
				$res['parent_id'] = !empty($param['u']) && ($param['u'] == $up_uid) ? intval($param['u']) : 0;
				$res['unionid'] = $res['openid'];
				session('unionid', $res['unionid']);
				session('parent_id', $res['parent_id']);

				if ($this->oauthLogin($res) === true) {
					redirect($back_url);
				}

				$bind_url = url('/', array(), false, true);
				$bind_param = array('m' => 'oauth', 'c' => 'index', 'a' => 'bind', 'type' => $type, 'back_url' => empty($back_url) ? url('user/index/index') : $back_url);
				$bind_url .= '?' . http_build_query($bind_param, '', '&');
				redirect($bind_url);
			}
			else {
				show_message(L('msg_authoriza_error'), L('msg_go_back'), url('user/login/index'), 'error');
			}

			return NULL;
		}

		$url = $obj->redirect($url);
		redirect($url);
	}

	public function actionBind()
	{
		if (IS_POST) {
			$username = I('username', '', 'trim');
			$form = new \Touch\Form();

			if ($form->isMobile($username, 1)) {
				$user_name = dao('users')->field('user_name')->where(array('mobile_phone' => $username))->find();
				$username = $user_name['user_name'];
			}

			if ($form->isEmail($username, 1)) {
				$user_name = dao('users')->field('user_name')->where(array('email' => $username))->find();
				$username = $user_name['user_name'];
			}

			$password = I('password', '', 'trim');
			$type = I('type', '', 'trim');
			$back_url = I('back_url', '', 'urldecode');
			if (!$form->isEmpty($username, 1) || !$form->isEmpty($password, 1)) {
				show_message(L('msg_input_namepwd'), L('msg_go_back'), '', 'error');
			}

			$bind_user_id = $this->users->check_user($username, $password);
			if ((0 < $bind_user_id) && !empty($_SESSION['unionid'])) {
				$rs = dao('connect_user')->field('user_id')->where(array('user_id' => $bind_user_id))->count();

				if (0 < $rs) {
					show_message(L('msg_account_bound'), L('msg_rebound'), '', 'error');
				}

				$res = array('unionid' => session('unionid'), 'nickname' => session('nickname'), 'user_id' => $bind_user_id);
				$this->update_connnect_user($res, $type);

				if (is_dir(APP_WECHAT_PATH)) {
					$result = dao('wechat_user')->where(array('ect_uid' => $bind_user_id))->order('ect_uid DESC')->find();

					if (!empty($result)) {
						show_message(L('msg_account_bound'), L('msg_go_back'), '', 'error');
					}

					if (isset($_SESSION['unionid']) && !empty($_SESSION['unionid'])) {
						$condition['openid'] = $_SESSION['openid'];
						$condition['unionid'] = $_SESSION['unionid'];
						$condition['_logic'] = 'OR';
						dao('wechat_user')->data(array('ect_uid' => $bind_user_id))->where($condition)->save();
					}
				}

				$this->doLogin($username);
				$back_url = (empty($back_url) ? url('user/index/index') : $back_url);
				redirect($back_url);
			}
			else {
				show_message(L('msg_account_bound_fail'), L('msg_rebound'), '', 'error');
			}

			return NULL;
		}

		if (empty($_SESSION['unionid']) || !isset($_SESSION['unionid'])) {
			show_message(L('msg_authoriza_error'), L('msg_go_back'), url('user/login/index'), 'error');
		}

		$is_auto = I('get.is_auto', 0, 'intval');
		$type = I('get.type', '', 'trim');
		$back_url = I('back_url', '', 'urldecode');
		if (($is_auto == 1) && !empty($_SESSION['unionid']) && isset($_SESSION['unionid'])) {
			$res['unionid'] = session('unionid');
			$res['parent_id'] = session('parent_id');
			$this->doRegister($res, $type, $back_url);
		}

		$this->assign('type', $type);
		$this->assign('back_url', $back_url);
		$this->assign('page_title', L('msg_bound_account'));
		$this->display();
	}

	public function actionRegister()
	{
		if (IS_POST) {
			$username = I('username', '', 'trim');
			$password = I('password', '', 'trim');
			$email = time() . rand(1, 9999) . '@qq.com';
			$type = I('type', '', 'trim');
			$back_url = I('back_url', '', 'urldecode');
			if (empty($username) || empty($password)) {
				show_message(L('msg_input_namepwd'), L('msg_go_back'), '', 'error');
			}

			$extends = array('parent_id' => session('parent_id'), 'nick_name' => session('nickname'), 'user_picture' => session('avatar'));
			$sql = 'SELECT u.user_name, u.user_id FROM {pre}users u, {pre}connect_user cu WHERE u.user_id = cu.user_id AND cu.open_id = \'' . $_SESSION['unionid'] . '\' order by u.user_id DESC ';
			$userinfo = $this->db->getRow($sql);

			if (empty($userinfo)) {
				if (register($username, $password, $email, $extends) !== false) {
					$res = array('unionid' => session('unionid'), 'nickname' => session('nickname'), 'user_id' => session('user_id'));
					$this->update_connnect_user($res, $type);

					if (is_dir(APP_WECHAT_PATH)) {
						$result = dao('wechat_user')->where(array('ect_uid' => $_SESSION['user_id']))->order('ect_uid DESC')->find();

						if (!empty($result)) {
							show_message(L('msg_account_bound'), L('msg_go_back'), '', 'error');
						}

						if (isset($_SESSION['unionid']) && !empty($_SESSION['unionid'])) {
							$condition['openid'] = $_SESSION['openid'];
							$condition['unionid'] = $_SESSION['unionid'];
							$condition['_logic'] = 'OR';
							dao('wechat_user')->data(array('ect_uid' => $_SESSION['user_id']))->where($condition)->save();
							$this->sendBonus();
						}
					}

					$back_url = (empty($back_url) ? url('user/index/index') : $back_url);
					redirect($back_url);
				}
				else {
					show_message(L('msg_author_register_error'), L('msg_re_registration'), '', 'error');
				}
			}
			else {
				show_message(L('msg_account_bound'), L('msg_go_back'), url('user/index/index'), 'error');
			}

			return NULL;
		}

		$type = I('get.type', '', 'trim');
		$back_url = I('back_url', '', 'urldecode');
		$this->assign('type', $type);
		$this->assign('back_url', $back_url);
		$this->assign('page_title', L('msg_author_register'));
		$this->display();
	}

	public function actionMergeUsers()
	{
		if ($_SESSION['user_id']) {
			if (IS_POST) {
				$username = I('username', '', 'trim');
				$form = new \Touch\Form();

				if ($form->isMobile($username, 1)) {
					$user_name = dao('users')->field('user_name')->where(array('mobile_phone' => $username))->find();
					$username = $user_name['user_name'];
				}

				if ($form->isEmail($username, 1)) {
					$user_name = dao('users')->field('user_name')->where(array('email' => $username))->find();
					$username = $user_name['user_name'];
				}

				$password = I('password', '', 'trim');
				$back_url = I('back_url', '', 'urldecode');
				if (!$form->isEmpty($username, 1) || !$form->isEmpty($password, 1)) {
					show_message(L('msg_input_namepwd'), L('msg_go_back'), '', 'error');
				}

				$from_user_id = $_SESSION['user_id'];
				$new_user_id = $this->users->check_user($username, $password);

				if (0 < $new_user_id) {
					$from_connect_user = dao('connect_user')->field('user_id')->where(array('user_id' => $from_user_id))->select();

					if (!empty($from_connect_user)) {
						foreach ($from_connect_user as $key => $value) {
							dao('connect_user')->where('user_id = ' . $value['user_id'])->setField('user_id', $new_user_id);
						}
					}

					if (is_dir(APP_WECHAT_PATH)) {
						$from_wechat_user = dao('wechat_user')->field('ect_uid')->where(array('ect_uid' => $from_user_id))->find();

						if (!empty($from_wechat_user)) {
							dao('wechat_user')->where('ect_uid = ' . $from_wechat_user['ect_uid'])->setField('ect_uid', $new_user_id);
						}
					}

					$res = merge_user($from_user_id, $new_user_id);

					if ($res == true) {
						$this->users->logout();
						$back_url = (empty($back_url) ? url('user/index/index') : $back_url);
						show_message(L('logout'), array(L('back_up_page'), '返回首页'), array($back_url, url('/')), 'success');
					}

					return NULL;
				}
				else {
					show_message(L('msg_account_bound_fail'), L('msg_rebound'), '', 'error');
				}

				return NULL;
			}

			$back_url = I('back_url', '', 'urldecode');
			$this->assign('back_url', $back_url);
			$this->assign('page_title', '重新绑定帐号');
			$this->display();
		}
		else {
			show_message('请登录', L('msg_go_back'), url('user/login/index'), 'error');
		}
	}

	private function getOauthConfig($type)
	{
		$sql = 'SELECT auth_config FROM {pre}touch_auth WHERE `type` = \'' . $type . '\' AND `status` = 1';
		$info = $this->db->getRow($sql);

		if ($info) {
			$res = unserialize($info['auth_config']);
			$config = array();

			foreach ($res as $key => $value) {
				$config[$value['name']] = $value['value'];
			}

			return $config;
		}

		return false;
	}

	private function oauthLogin($res)
	{
		$sql = 'SELECT u.user_name, u.user_id FROM {pre}users u, {pre}connect_user cu WHERE u.user_id = cu.user_id AND cu.open_id = \'' . $res['unionid'] . '\' order by u.user_id DESC ';
		$userinfo = $this->db->getRow($sql);

		if ($userinfo) {
			$this->doLogin($userinfo['user_name']);
			$user_data = array('nick_name' => !empty($_SESSION['nickname']) ? $_SESSION['nickname'] : $res['name'], 'user_picture' => !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : $res['avatar']);
			dao('users')->data($user_data)->where(array('user_id' => $userinfo['user_id']))->save();
			return true;
		}
		else {
			return false;
		}
	}

	private function doLogin($username)
	{
		$this->users->set_session($username);
		$this->users->set_cookie($username);
		update_user_info();
		recalculate_price();
	}

	private function doRegister($res, $type = '', $back_url = '')
	{
		$username = substr(md5($res['unionid']), -2) . time() . rand(100, 999);
		$password = mt_rand(100000, 999999);
		$email = $username . '@qq.com';
		$extends = array('parent_id' => $res['parent_id'], 'nick_name' => !empty($_SESSION['nickname']) ? $_SESSION['nickname'] : $res['name'], 'user_picture' => !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : $res['avatar']);
		$sql = 'SELECT u.user_name, u.user_id FROM {pre}users u, {pre}connect_user cu WHERE u.user_id = cu.user_id AND cu.open_id = \'' . $res['unionid'] . '\' order by u.user_id DESC ';
		$userinfo = $this->db->getRow($sql);

		if (empty($userinfo)) {
			if (register($username, $password, $email, $extends) !== false) {
				$res2 = array('unionid' => $res['unionid'], 'nickname' => session('nickname'), 'user_id' => session('user_id'));
				$this->update_connnect_user($res2, $type);

				if (is_dir(APP_WECHAT_PATH)) {
					$result = dao('wechat_user')->where(array('ect_uid' => $_SESSION['user_id']))->order('ect_uid DESC')->find();

					if (!empty($result)) {
						show_message(L('msg_account_bound'), L('msg_go_back'), '', 'error');
					}

					if (isset($_SESSION['unionid']) && !empty($_SESSION['unionid'])) {
						$condition['openid'] = $_SESSION['openid'];
						$condition['unionid'] = $_SESSION['unionid'];
						$condition['_logic'] = 'OR';
						dao('wechat_user')->data(array('ect_uid' => $_SESSION['user_id']))->where($condition)->save();
						$this->sendBonus();
					}
				}

				$back_url = (empty($back_url) ? url('user/index/index') : $back_url);
				redirect($back_url);
			}
			else {
				show_message(L('msg_author_register_error'), L('msg_re_registration'), '', 'error');
			}
		}
		else {
			show_message(L('msg_account_bound'), L('msg_go_back'), url('user/index/index'), 'error');
		}

		return NULL;
	}

	private function update_connnect_user($res, $type = '')
	{
		$data = array('connect_code' => 'sns_' . $type, 'user_id' => $res['user_id'], 'open_id' => $res['unionid'], 'profile' => serialize($res), 'create_at' => gmtime());
		$where = array('open_id' => $res['unionid']);
		$connect_userinfo = dao('connect_user')->field('user_id, open_id')->where($where)->order('user_id DESC')->find();

		if (0 < $res['user_id']) {
			if (empty($connect_userinfo)) {
				dao('connect_user')->data($data)->add();
			}
			else {
				dao('connect_user')->data($data)->where($where)->save();
			}
		}
	}

	private function sendBonus()
	{
		$wxinfo = dao('wechat')->field('id, token, appid, appsecret, encodingaeskey')->where(array('default_wx' => 1, 'status' => 1))->find();

		if ($wxinfo) {
			$rs = $this->db->query('SELECT name, keywords, command, config FROM {pre}wechat_extend WHERE command = \'bonus\' and enable = 1 and wechat_id = ' . $wxinfo['id'] . ' ORDER BY id ASC');
			$addons = reset($rs);
			$file = ADDONS_PATH . 'wechat/' . $addons['command'] . '/' . ucfirst($addons['command']) . '.php';

			if (file_exists($file)) {
				require_once $file;
				$new_command = '\\app\\modules\\wechat\\' . $addons['command'] . '\\' . ucfirst($addons['command']);
				$wechat = new $new_command();
				$data = $wechat->returnData($_SESSION['openid'], $addons);

				if (!empty($data)) {
					$config['token'] = $wxinfo['token'];
					$config['appid'] = $wxinfo['appid'];
					$config['appsecret'] = $wxinfo['appsecret'];
					$config['encodingaeskey'] = $wxinfo['encodingaeskey'];
					$weObj = new \Touch\Wechat($config);
					$weObj->sendCustomMessage($data['content']);
				}
			}
		}
	}
}

?>
