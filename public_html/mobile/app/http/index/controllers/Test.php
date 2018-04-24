<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\http\index\controllers;

class Test extends \app\http\base\controllers\Frontend
{
	private $article;

	public function __construct(\app\repositories\Article $articles)
	{
		parent::__construct();
		$this->article = $articles;
	}

	public function actionIndex()
	{
		$res = $this->article->find(58);
		dump($res);
	}
}

?>
