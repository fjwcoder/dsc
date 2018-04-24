<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\http\article\controllers;

class Index extends \app\http\base\controllers\Frontend
{
	protected $category;
	protected $article;

	public function __construct(\app\repositories\article\CategoryRepository $category, \app\repositories\article\ArticleRepository $article)
	{
		parent::__construct();
		L(require LANG_PATH . C('shop.lang') . '/user.php');
		$this->category = $category;
		$this->article = $article;
	}

	public function actionIndex()
	{
		if (IS_AJAX) {
			$cat_id = I('post.cat_id', 0, 'intval');
			$article = $this->article->getCatArt($cat_id, 10);

			foreach ($article as $key => $value) {
				$article[$key]['likenum'] = $value['extend'][0]['likenum'];
				$article[$key]['click'] = !empty($value['extend'][0]['click']) ? $value['extend'][0]['click'] : 0;

				if (count($article[$key]['album']) == 0) {
					$article[$key]['imgNumPattern'] = 0;
				}
				else {
					if ((count($article[$key]['album']) == 1) || (count($article[$key]['album']) == 2)) {
						$article[$key]['imgNumPattern'] = 1;
					}
					else {
						$article[$key]['imgNumPattern'] = 2;
					}
				}
			}

			$num = ceil(count($article) / 10);
			$this->response(array('list' => $article, 'totalPage' => $num), 'json', 200);
		}

		$category = $this->category->getTop();
		$this->assign('page_title', lang('文章频道'));
		$this->assign('data', $category);
		$this->display('index.index');
	}

	public function actionDetail($id)
	{
		$datails = $this->article->detail($id);
		$this->assign('page_title', lang('文章详情'));
		$this->assign('article', $datails);
		$this->display('index.detail');
	}

	public function actionView()
	{
		$article_id = I('id', 0, 'intval');

		if (IS_AJAX) {
			dao('article_extend')->where(array('article_id' => $article_id))->setInc('click', '1');
			$view_num = dao('article_extend')->where(array('article_id' => $article_id))->getField('click');
			echo json_encode(array('view_num' => $view_num, 'is_like' => 0, 'article_id' => $article_id));
		}
	}

	public function actionLike()
	{
		$article_id = I('id', 0, 'intval');

		if (IS_AJAX) {
			if ($_COOKIE[$article_id . 'islike'] == '1') {
				dao('article_extend')->where(array('article_id' => $article_id))->setInc('likenum', '-1');
				$like_num = dao('article_extend')->where(array('article_id' => $article_id))->getField('likenum');
				setcookie($article_id . 'islike', '0', gmtime() - 86400);
				echo json_encode(array('like_num' => $like_num, 'is_like' => 0, 'article_id' => $article_id));
			}
			else {
				dao('article_extend')->where(array('article_id' => $article_id))->setInc('likenum', '1');
				$like_num = dao('article_extend')->where(array('article_id' => $article_id))->getField('likenum');
				setcookie($article_id . 'islike', '1', gmtime() + 86400);
				echo json_encode(array('like_num' => $like_num, 'is_like' => 1, 'article_id' => $article_id));
			}
		}
	}

	public function actionComment($id)
	{
		if (I('content')) {
			$user_id = $_SESSION['user_id'];
			$user_name = $_SESSION['user_name'];
			$user_ip = $_SERVER['REMOTE_ADDR'];
			$parent_id = (I('cid') ? I('cid') : 0);

			if (!empty($user_id)) {
				$article_id = I('id');

				if (IS_POST) {
					$data['content'] = I('content');
					$data['user_id'] = $user_id;
					$data['user_name'] = $user_name;
					$data['id_value'] = $id;
					$data['comment_type'] = '1';
					$data['parent_id'] = $parent_id;
					$data['status'] = '1';
					$data['add_time'] = gmtime();
					$data['ip_address'] = $user_ip;

					if (!empty($data['content'])) {
						$res = $this->model->table('comment')->data($data)->add();

						if ($res == true) {
							echo json_encode(url('article/index/detail', array('id' => $id)));
						}
					}
				}
			}
			else {
				echo json_encode(url('user/login/index'));
			}
		}
	}

	public function actionWechat()
	{
		if (is_dir(APP_WECHAT_PATH)) {
			$news_id = I('get.id', 0, 'intval');
			$data = $this->db->table('wechat_media')->field('wechat_id,title,author,add_time,is_show, file, digest, content')->where(array('id' => $news_id))->find();

			if (empty($data)) {
				$this->redirect('/');
			}

			$data['author'] = !empty($data['author']) ? $data['author'] : $this->db->table('wechat')->where(array('id' => $data['wechat_id']))->getField('name');
			$data['add_time'] = local_date('Y-m-d H:i', $data['add_time']);
			$data['content'] = article_content_html_out($data['content']);
			$data['image'] = $data['is_show'] ? get_wechat_image_path($data['file']) : '';
			$this->assign('page_title', $data['title']);
			$this->assign('description', htmlspecialchars($data['digest']));
			$this->assign('page_img', $data['image']);
			$this->assign('article', $data);
		}

		$this->display();
	}

	private function parameter()
	{
		$page_size = C('shop.article_number');
		$this->parameter['size'] = 0 < $page_size ? $page_size : 10;
		$this->parameter['page'] = I('page') ? I('page', 'intval') : 1;
		$this->parameter['cat_id'] = I('id', 'intval');
		$this->parameter['keywords'] = I('keywords');
	}
}

?>
