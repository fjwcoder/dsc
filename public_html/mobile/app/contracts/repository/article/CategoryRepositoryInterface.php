<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\contracts\repository\article;

interface CategoryRepositoryInterface
{
	public function getList($cid, $type);

	public function getDetail($cid);

	public function getArticle($cid);
}


?>
