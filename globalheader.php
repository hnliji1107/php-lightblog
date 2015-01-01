<?php
require('./connect.php');
require('./libs/Smarty.class.php');
require('./class.common.php');

//判断是否存在用户
if(empty($_SESSION['user_name'])){
	die(json_encode(array('status'=>0,'msg'=>'您尚未登录，请先登录！')));
}

$smarty = new Smarty();
$common = new Common();
$now_user = $_SESSION['user_name'];//当前用户

?>