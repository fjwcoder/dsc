<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\contracts\repository\article;

interface ArticleRepositoryInterface
{
	public function getList($cid);

	public function getDetail($id);

	public function getComment($id);

	public function getRelatedGoods($id);
}


?>
