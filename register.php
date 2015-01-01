<?php
require('./connect.php');
require('./libs/Smarty.class.php');
require('./class.common.php');

$smarty = new Smarty();
$common = new Common();

if(!empty($_POST['email']) && !empty($_POST['name']) && !empty($_POST['password']) && !empty($_POST['check_code'])){
	$name = strtolower($common->make_semiangle(trim($_POST['name'])));
	$input_code = $_POST['check_code'];//注册之前先判断验证码是否正确
	$input_code = strtoupper($input_code);//把用户输入的验证码转化为大写
	$save_code = $_SESSION['check_code'];
	$regError = array();
	$regError['status'] = 0;

	//如果验证码正确，则向下执行
	if($input_code != $save_code){
		$regError['etype'] = 1;
		$regError['msg'] = '验证码错误，请重新输入！';
		die(json_encode($regError));
	}
	
	//再判断用户输入的用户名是否存在
	if(!$common->check_user($name)){
		$regError['etype'] = 2;
		$regError['msg'] = '用户名已经存在，请重新输入！';
		die(json_encode($regError));
	}
	
	$email = trim($_POST['email']);
	$password = md5($_POST['password']);
	$time = date('Y-m-d H:i:s');
	$insert = "INSERT INTO users (user_email,user_name,user_password,register_time,login_time,last_login_time) VALUES 
				('$email','$name','$password','$time','$time','$time')";
	
	if(!mysql_query($insert,$con)){
		$regError['msg'] = '系统出错！';
		die(json_encode($regError));
	}
	
	//邀请注册
	if(!empty($_POST['inviter'])){
		$inviter = $_POST['inviter']; //邀请人
		//给邀请人加粉丝
		$select = "SELECT fans FROM users WHERE user_name='$inviter' LIMIT 1";

		if(!$result = mysql_query($select,$con)){
			$regError['msg'] = '系统出错！';
			die(json_encode($regError));
		}
		
		$row = mysql_fetch_assoc($result);
		$fansStr = $row['fans'].$name.'|';
		$update = "UPDATE users SET fans='$fansStr',newfans=newfans+1 WHERE user_name='$inviter' LIMIT 1";

		if(!mysql_query($update,$con)){
			$regError['msg'] = '系统出错！';
			die(json_encode($regError));
		}
		
		//给注册人加关注
		$attenStr = $inviter.'|';
		$update = "UPDATE users SET attention='$attenStr' WHERE user_name='$name' LIMIT 1";

		if(!mysql_query($update,$con)){
			$regError['msg'] = '系统出错！';
			die(json_encode($regError));
		}
	}
	
	$_SESSION['user_name'] = $name;//指定当前用户为注册成功的人
	$_SESSION['firstLogin'] = $name;
	
	if(isset($_SESSION['loginUrl'])){
		$regUrl = $_SESSION['loginUrl']; //获取登录前url
	}
	else{
		$regUrl = '/';
	}
	
	$toreg = array('status' => 1,'msg' => $regUrl);
	echo json_encode($toreg); //转化为json格式并传递到前端
	mysql_close($con);
	return false;
}

$smarty->assign('is_register',true);
$smarty->assign('isUser',$_SESSION['user_name']);
$smarty->display('register.tpl');

?>