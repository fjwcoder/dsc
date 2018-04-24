<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\contracts\transformer;

interface TransformerInterface
{
	public function setHidden(array $hidden);

	public function setMap(array $map);

	public function transformer(array $data);
}


?>
