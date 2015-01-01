<?php
//退出登录
if(!empty($_POST['loginOut']) && $_POST['loginOut']==true){
	session_start();
	unset($_SESSION['user_name']);//注销用户
	unset($_SESSION['loginUrl']); //清空当前url记录
	unset($_SESSION['firstLogin']); //清除注册那次登录痕迹

	echo json_encode(array('status'=>1,'msg'=>'成功退出登录！'));
}

?>