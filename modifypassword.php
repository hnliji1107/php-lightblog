<?php
require('./globalheader.php');

//修改个人资料
if(!empty($_POST['sex']) && !empty($_POST['email']) && !empty($_POST['modify_information']) && $_POST['modify_information']==true){
	$sex = $_POST['sex'];
	$email = $_POST['email'];
	$qq = $_POST['qq'];
	$phone = $_POST['phone'];
	$signature = $_POST['signature'];
	$update = "UPDATE users SET sex='$sex',user_email='$email',qq='$qq',phone='$phone',signature='$signature' WHERE
				user_name='$now_user' LIMIT 1";
	$modify = array();
	$modify['status'] = 1;
	$modify['msg'] = '恭喜，修改成功！';

	if(!mysql_query($update,$con)){
		$modify['status'] = 0;
		$modify['msg'] = '系统出错，请稍后再试！';	
	}

	echo json_encode($modify);
}

//修改密码
if(!empty($_POST['modify_name']) && $_POST['modify_name']==$now_user && !empty($_POST['modify_password']) && !empty($_POST['new_password'])){
	$modify_name = $_POST['modify_name'];
	$modify_password = md5($_POST['modify_password']);
	$modify = array();
	$modify['status'] = 1;
	$modify['msg'] = '恭喜，密码修改成功！';
	$new_password = md5($_POST['new_password']);
	$select = "SELECT * FROM users WHERE user_name='$modify_name' AND user_password='$modify_password' LIMIT 1";
	$result = mysql_query($select,$con);

	if(!$result){
		$modify['status'] = 0;
		$modify['msg'] = '系统出错，请稍后再试！';
		die(json_encode($modify));
	}
	
	$number = mysql_num_rows($result);
	
	if($number != 1){
		$modify['status'] = 0;
		$modify['msg'] = '密码错误，请重新输入！';
		die(json_encode($modify));
	}

	$select = "SELECT * FROM users WHERE user_name='$modify_name' AND user_password='$new_password' LIMIT 1";
	$result = mysql_query($select,$con);

	if(!$result){
		$modify['status'] = 0;
		$modify['msg'] = '系统出错，请稍后再试！';
		die(json_encode($modify));
	}
	
	$number = mysql_num_rows($result);
	
	if($number == 1){
		$modify['status'] = 0;
		$modify['msg'] = '所设密码不能与原有密码相同！';
		die(json_encode($modify));
	}
	
	$update = "UPDATE users SET user_password='$new_password' WHERE user_name='$modify_name' AND user_password='$modify_password'";
	
	if(!mysql_query($update,$con)){
		$modify['status'] = 0;
		$modify['msg'] = '系统出错，请稍后再试！';
		die(json_encode($modify));
	}
	
	echo json_encode($modify);
}

mysql_close($con);

?>