<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
$backUrl = $ecs->url() . ADMIN_PATH . '/receive.php';
header('location:https://www.99bill.com');
exit();

?>
