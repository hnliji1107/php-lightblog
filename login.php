<?php
require('./connect.php');
require('./libs/Smarty.class.php');
require('./class.common.php');

$smarty = new Smarty();
$common = new Common();

//更新登陆时间
function upLgnTm($user,$islast,$time){
	if($islast){ //更新上次登录时间
		$update = "UPDATE users SET last_login_time='$time' WHERE user_name='$user'";
	}
	else{ //更新本次登录时间
		$update = "UPDATE users SET login_time='$time' WHERE user_name='$user'";
	}
	if(!mysql_query($update)){
		$loginError = array(
			'status' => 0,
			'msg' => '系统出错！'
		);
		die(json_encode($loginError));
	}
}

if(empty($_POST['user_name']) || empty($_POST['user_password']) || empty($_POST['check_code'])){
	$smarty->assign('is_login',true);
	$smarty->assign('isUser',$_SESSION['user_name']);
	$smarty->display('login.tpl');
	return false;
}

//判断密码之前先判断验证码是否正确
$input_code = $_POST['check_code'];
$input_code = strtoupper($input_code);//把用户输入的验证码转化为大写 
$save_code = $_SESSION['check_code'];
$loginError = array();
$loginError['status'] = 0;

//如果验证码正确，则向下执行
if($input_code != $save_code){
	$loginError['etype'] = 1;
	$loginError['msg'] = '验证码错误，请重新输入！';
	die(json_encode($loginError));
}

$name = strtolower($common->make_semiangle(trim($_POST['user_name'])));
$password = md5($_POST['user_password']);
$sql = "SELECT user_id FROM users WHERE user_name = '$name' AND user_password = '$password'";

if(!$result = mysql_query($sql,$con)){
	$loginError['msg'] = '系统出错！';
	die(json_encode($loginError));
}

$row = mysql_fetch_array($result);

if($row){
	$_SESSION['user_name'] = $name; //登记用户

	if(isset($_SESSION['loginUrl'])){
		$loginUrl = $_SESSION['loginUrl']; //获取登录前url
	}
	else{
		$loginUrl = '/';
	}

	$tologin = array(
		'status' => 1,
		'msg' => $loginUrl
	);
	//更新上次登录时间
	$select = "SELECT login_time FROM users WHERE user_name='$name' LIMIT 1";
	$result = $common->selectSql($select);
	$row = mysql_fetch_assoc($result);
	upLgnTm($name,true,$row['login_time']);
	//记录本次登陆时间
	$login_time = date('Y-m-d H:i:s');
	upLgnTm($name,false,$login_time);
	//转化为json格式并传递到前端
	echo json_encode($tologin);
}
else{
	$loginError['etype'] = 2;
	$loginError['msg'] = '密码错误，请重新输入！';
	die(json_encode($loginError));
}

?>