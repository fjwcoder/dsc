<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\http\user\controllers;

class Profile extends \app\http\base\controllers\Frontend
{
	public $user_id;
	public $email;
	public $mobile;
	public $sex;
	public $nickname; // add by fjw in 18.4.23

	public function __construct()
	{
		parent::__construct();
		L(require LANG_PATH . C('shop.lang') . '/user.php');
		$file = array('passport', 'clips');
		$this->load_helper($file);
		$this->user_id = $_SESSION['user_id'];
		$this->actionchecklogin();
		$this->assign('lang', L());
	}

	public function actionIndex()
	{

		$this->parameter();
		$sql = 'SELECT user_id,user_name,sex FROM {pre}users WHERE user_id = ' . $this->user_id;
		$user_info = $this->db->getRow($sql);
		$wechat_user = get_wechat_user_info($user_info['user_id']);
		$this->assign('user_name', $wechat_user['nick_name']);
		$this->assign('user_sex', $user_info['sex']);
		$this->assign('page_title', L('profile'));
		$this->display();
	}

	public function actionChangeHeader()
	{
		$result = $this->upload('data/images_user', false, 2);
		$imagePath = '';

		if ($result['error'] <= 0) {
			$imagePath = $result['url']['img']['url'];
			$sql = 'UPDATE {pre}users SET user_picture = \'' . $imagePath . '\' WHERE user_id = ' . $this->user_id;
			$update = $this->db->query($sql);

			if (!$update) {
				$data = array('error' => 0, 'msg' => '头像替换失败');
			}
			else {
				$sql = 'SELECT user_picture FROM {pre}users WHERE user_id = ' . $this->user_id;
				$users = $this->db->getRow($sql);
				$data = array('error' => 0, 'msg' => '头像替换成功', 'path' => $imagePath);
			}
		}
		else {
			$data = array('error' => 1, 'msg' => '头像替换失败');
		}

		$this->ajaxReturn($data);
	}

	public function actionEditProfile()
	{
		$this->parameter();

		if (IS_POST) {
			if (!empty($this->sex)) {
				$update = ' sex = \'' . $this->sex . '\'';
			}

			$where = ' WHERE user_id = \'' . $this->user_id . '\'';
			if (isset($update) && isset($where)) {
				$sql = 'UPDATE {pre}users SET ' . $update . ' ' . $where;
				$this->db->query($sql);
			}

			$info = get_user_default($this->user_id);
			echo json_encode($info);
			exit();
		}
	}
	// add by fjw in 18.4.23: ajax版本的修改手机号
	public function actionModifyNickname(){
		$this->parameter();

		if (IS_POST) {
			if (!empty($this->nickname)) {
				$update = ' nick_name = \'' . $this->nickname . '\'';
			}

			$where = ' WHERE user_id = \'' . $this->user_id . '\'';
			if (isset($update) && isset($where)) {
				$sql = 'UPDATE {pre}users SET ' . $update . ' ' . $where;
				$this->db->query($sql);
			}

			$info = get_user_default($this->user_id);
			echo json_encode($info);
			exit();
		}
	}
	// add by fjw in 18.4.23: ajax版本的修改手机号
	public function actionModifyMobile(){
		$this->parameter();

		if (IS_POST) {
			if (!empty($this->mobile)) {
				$update = ' mobile_phone = \'' . $this->mobile . '\'';
			}

			$where = ' WHERE user_id = \'' . $this->user_id . '\'';
			if (isset($update) && isset($where)) {
				$sql = 'UPDATE {pre}users SET ' . $update . ' ' . $where;
				$this->db->query($sql);
			}

			$info = get_user_default($this->user_id);
			echo json_encode($info);
			exit();
		}
	}

	// add by fjw in 18.4.23: ajax版本的修改邮箱
	public function actionModifyEmail(){
		$this->parameter();

		if (IS_POST) {
			if (!empty($this->email)) {
				$update = ' email = \'' . $this->email . '\'';
			}

			$where = ' WHERE user_id = \'' . $this->user_id . '\'';
			if (isset($update) && isset($where)) {
				$sql = 'UPDATE {pre}users SET ' . $update . ' ' . $where;
				$this->db->query($sql);
			}

			$info = get_user_default($this->user_id);
			echo json_encode($info);
			exit();
		}
	}


	// 原版
	// public function actionUserEditMobile()
	// {
	// 	$this->parameter();
	// 	$sql = 'SELECT user_id,user_name,mobile_phone FROM {pre}users WHERE user_id = ' . $this->user_id;
	// 	$user_info = $this->db->getRow($sql);
	// 	if (IS_POST && (I('sms_signin') == 1)) {
	// 		$sms_code = I('sms_code');

	// 		if ($sms_code !== $_SESSION['sms_code']) {
	// 			show_message(L('msg_auth_code_error'));
	// 			exit();
	// 		}

	// 		if (empty($this->mobile)) {
	// 			show_message(L('msg_input_mobile'));
	// 			exit();
	// 		}

	// 		if (is_mobile($this->mobile) == false) {
	// 			show_message(L('msg_mobile_format_error'));
	// 			exit();
	// 		}

	// 		if (!empty($user_info)) {
	// 			$sql = 'UPDATE {pre}users SET mobile_phone = \'' . $this->mobile . '\' WHERE user_id = \'' . $this->user_id . '\'';
	// 			$this->db->query($sql);
	// 		}
	// 	}

	// 	if (IS_POST && (I('sms_signin') == 0)) {
	// 		$sql = 'SELECT user_id FROM {pre}users WHERE mobile_phone=\'' . $this->mobile . '\'AND user_id!=' . $_SESSION['user_id'];
	// 		$mobile_phone = $this->db->getOne($sql);

	// 		if (!empty($mobile_phone)) {
	// 			show_message(L('msg_mobile_exist'));
	// 			exit();
	// 		}

	// 		$sql = 'SELECT user_id FROM {pre}users WHERE user_name=\'' . $this->mobile . '\'AND user_id!=' . $_SESSION['user_id'];
	// 		$user_name = $this->db->getOne($sql);

	// 		if (!empty($user_name)) {
	// 			show_message(L('msg_mobile_exist'));
	// 			exit();
	// 		}

	// 		if (!empty($this->mobile)) {
	// 			$sql = 'UPDATE {pre}users SET mobile_phone = \'' . $this->mobile . '\' WHERE user_id = \'' . $this->user_id . '\'';
	// 			$up = $this->db->query($sql);
	// 			ecs_header('Location: ' . url('user/profile/index'));
	// 		}
	// 	}

	// 	$_SESSION['sms_code'] = $sms_code = md5(mt_rand(1000, 9999));
	// 	$this->assign('sms_code', $sms_code);
	// 	$this->assign('mobile', $user_info['mobile_phone']);
	// 	$this->assign('sms_signin', C('shop.sms_signin'));
	// 	$this->assign('page_title', L('edit_mobile'));
	// 	$this->display();
	// }

	// public function actionUserEditEmail()
	// {
	// 	$this->parameter();
	// 	$sql = 'SELECT user_id,email FROM {pre}users WHERE user_id = ' . $this->user_id;
	// 	$user_info = $this->db->getRow($sql);

	// 	if (IS_POST) {
	// 		$sql = 'SELECT user_id FROM {pre}users WHERE email=\'' . $this->email . '\'AND user_id!=' . $_SESSION['user_id'];
	// 		$email = $this->db->getOne($sql);

	// 		if (!empty($email)) {
	// 			show_message(L('msg_email_registered'));
	// 			exit();
	// 		}

	// 		$sql = 'SELECT user_id FROM {pre}users WHERE user_name=\'' . $this->email . '\'AND user_id!=' . $_SESSION['user_id'];
	// 		$user_email = $this->db->getOne($sql);

	// 		if (!empty($user_email)) {
	// 			show_message(L('msg_email_registered'));
	// 			exit();
	// 		}

	// 		if (!empty($this->email)) {
	// 			$sql = 'UPDATE {pre}users SET email = \'' . $this->email . '\' WHERE user_id = \'' . $this->user_id . '\'';
	// 			$this->db->query($sql);
	// 		}

	// 		ecs_header('Location: ' . url('user/profile/index'));
	// 	}

	// 	$this->assign('emails', $user_info['email']);
	// 	$this->assign('page_title', L('edit_email'));
	// 	$this->display();
	// }

	private function parameter()
	{
		$this->user_id = $_SESSION['user_id'];

		if (empty($this->user_id)) {
			ecs_header("Location: ./\n");
		}

		$this->mobile = I('mobile');
		$this->sex = I('sex');
		$this->email = I('email');
		$this->postbox = I('postbox');
		$this->nickname = I('nickname');

		$this->assign('info', get_user_default($this->user_id));
	}

	public function actionchecklogin()
	{
		if (!$this->user_id) {
			$url = urlencode(__HOST__ . $_SERVER['REQUEST_URI']);

			if (IS_POST) {
				$url = urlencode($_SERVER['HTTP_REFERER']);
			}

			ecs_header('Location: ' . url('user/login/index', array('back_act' => $url)));
			exit();
		}
	}

	public function actionRealname()
	{
		if (IS_POST) {
			$step = I('step', '', 'trim');
			$real_id = I('real_id', 0, 'intval');
			$real_user = I('post.data', '', 'trim');
			$real_user['user_id'] = $this->user_id;
			$real_user['bank_mobile'] = I('mobile_phone', '');

			if (empty($real_user['real_name'])) {
				exit(json_encode(array('status' => 1, 'msg' => '真实姓名不可为空')));
			}

			if (empty($real_user['self_num'])) {
				exit(json_encode(array('status' => 1, 'msg' => '身份证号不可为空')));
			}

			if (empty($real_user['bank_name'])) {
				exit(json_encode(array('status' => 1, 'msg' => '银行不可为空')));
			}

			if (empty($real_user['bank_card'])) {
				exit(json_encode(array('status' => 1, 'msg' => '银行卡号不可为空')));
			}

			if (empty($real_user['bank_mobile'])) {
				exit(json_encode(array('status' => 1, 'msg' => '手机号不可为空')));
			}

			$form = new \Touch\Form();

			if (!$form->isMobile($real_user['bank_mobile'], 1)) {
				exit(json_encode(array('status' => 1, 'msg' => '手机号码格式不正确')));
			}

			if (strpos($real_user['self_num'], '*') == false) {
				if (!$form->isCreditNo($real_user['self_num'], 1)) {
					exit(json_encode(array('status' => 1, 'msg' => '身份证号码格式不正确')));
				}
			}

			if (strpos($real_user['bank_card'], '*') == false) {
				if (is_numeric($real_user['bank_card']) == false) {
					exit(json_encode(array('status' => 1, 'msg' => '银行卡号格式不正确')));
				}
			}

			$mobile_code = I('mobile_code', '');

			if (!empty($real_user['bank_mobile'])) {
				if (!empty($mobile_code)) {
					if (($real_user['bank_mobile'] != $_SESSION['sms_mobile']) || ($mobile_code != $_SESSION['sms_mobile_code'])) {
						exit(json_encode(array('status' => 1, 'msg' => '手机号或短信验证码错误')));
					}
				}
				else {
					exit(json_encode(array('status' => 1, 'msg' => '短信验证码不可为空')));
				}
			}

			$count_user = dao('users_real')->where(array('user_id' => $this->user_id))->count();
			if ($real_id && ($count_user == 1)) {
				if (strpos($real_user['self_num'], '*') == true) {
					unset($real_user['self_num']);
				}

				if (strpos($real_user['bank_card'], '*') == true) {
					unset($real_user['bank_card']);
				}

				dao('users_real')->data($real_user)->where(array('real_id' => $real_id, 'user_id' => $this->user_id))->save();
				exit(json_encode(array('status' => 0, 'msg' => '修改成功')));
			}
			else {
				if (($count_user == 0) && ($step == 'first')) {
					$real_user['add_time'] = gmtime();
					dao('users_real')->data($real_user)->where(array('user_id' => $this->user_id))->add();
					exit(json_encode(array('status' => 0, 'msg' => '实名认证申请成功，请等待管理员审核！')));
				}
				else {
					exit(json_encode(array('status' => 1, 'msg' => '您已经实名认证过了，不需要重复认证')));
				}
			}
		}

		$step = I('step', '', 'trim');
		$count_user = dao('users_real')->where(array('user_id' => $this->user_id))->count();
		if (($count_user == 1) && ($step != 'edit')) {
			$this->redirect('user/profile/realnameok');
		}

		$step = ($count_user == 0 ? 'first' : 'edit');
		$real_user = dao('users_real')->where(array('user_id' => $this->user_id))->find();

		if (!empty($real_user)) {
			if (empty($real_user['bank_mobile'])) {
				$mobile_phone = dao('users')->where(array('user_id' => $this->user_id))->getField('mobile_phone');

				if ($mobile_phone) {
					$real_user['bank_mobile'] = $mobile_phone;
				}
			}

			$real_user['self_num'] = string_to_star($real_user['self_num'], 4);
			$real_user['bank_card'] = string_to_star($real_user['bank_card'], 4);
		}

		$this->assign('real_user', $real_user);
		$this->assign('step', $step);
		$this->assign('page_title', '实名认证');
		$this->display();
	}

	public function actionRealnameSend()
	{
		if (IS_AJAX) {
			$_SESSION['sms_mobile'] = I('mobile');
			$_SESSION['sms_mobile_code'] = rand(1000, 9999);
			$form = new \Touch\Form();

			if (!$form->isMobile($_SESSION['sms_mobile'], 1)) {
				$result['error'] = 1;
				$result['content'] = '手机号码格式不正确';
				exit(json_encode($result));
			}

			$message = array('code' => $_SESSION['sms_mobile_code']);
			$send_result = send_sms($_SESSION['sms_mobile'], 'sms_code', $message);

			if ($send_result === true) {
				$result['error'] = 0;
				$result['content'] = '发送短信成功';
			}
			else {
				$result['error'] = 1;
				$result['content'] = '发送短信失败';
			}

			exit(json_encode($result));
		}
	}

	public function actionRealnameOk()
	{
		$real_user = dao('users_real')->where(array('user_id' => $this->user_id))->find();

		if (!$real_user) {
			$this->redirect('user/profile/realname');
		}

		$real_user['validate_time'] = local_date('Y-m-d H:i:s', $real_user['add_time']);
		$real_user['self_num'] = string_to_star($real_user['self_num'], 4);
		$real_user['bank_card'] = string_to_star($real_user['bank_card'], 4);
		$this->assign('real_user', $real_user);
		$this->assign('page_title', '实名认证信息');
		$this->display();
	}
}

?>
