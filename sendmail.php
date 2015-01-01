<?php
require('./connect.php');

function sendmail_sunchis_com($mailTo,$subject,$body,$AddAttachment){
    //$mailTo：是一个数组，表示收件人地址 和收件人姓名，格式为array('邮箱地址','姓名')
	//$subject 表示邮件标题
	//$body  ：表示邮件正文
	//$AddAttachment 附件地址
    //error_reporting(E_ALL);

	if(count($mailTo)==0){
		die(json_encode(array('status'=>0,'msg'=>'系统出错，请重试！')));
	}

    error_reporting(E_STRICT);
    date_default_timezone_set("Asia/Shanghai");	//设定时区东八区

    require_once("./class.phpmailer.php");
    include("./class.smtp.php"); 

    $mail             = new PHPMailer(); 		//new一个PHPMailer对象出来
    $body             = eregi_replace("[\]",'',$body); //对邮件内容进行必要的过滤
    $mail->CharSet 	  = "UTF-8";				//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP();	 						// 设定使用SMTP服务
    $mail->SMTPAuth   = true;                  	// 启用 SMTP 验证功能
    $mail->Host       = "smtp.qq.com";      	// SMTP 服务器
    $mail->Port       = 25;                   	// SMTP服务器的端口号
    $mail->Username   = "928990115@qq.com";  			// SMTP服务器用户名
    $mail->Password   = "hngmjiying103lsj";        // SMTP服务器密码
    $mail->SetFrom('928990115@qq.com','admin'); //发送方
    $mail->Subject    = $subject;
    $mail->MsgHTML($body);
	
	foreach($mailTo as $k => $v){
		$mail->AddAddress($mailTo[$k][0], $mailTo[$k][1]);
	}
	
	if(count($AddAttachment) > 0){
		foreach($AddAttachment as $k => $AttachmentAddress){
			$mail->AddAttachment($AttachmentAddress);
		}
	}
	
    if(!$mail->Send()) {
    	echo json_encode(array('status'=>0,'msg'=>'抱歉，邮件发送失败！'));
    } 
	else {
        echo json_encode(array('status'=>1,'msg'=>'邮件已发送，请注意查收！'));
    }
}

if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['check_code'])) {
	$return = array('status'=>1);
	//判断验证码是否正确
	$input_code = $_POST['check_code'];
	$input_code = strtoupper($input_code);//把用户输入的验证码转化为大写 
	$save_code = $_SESSION['check_code'];
	
	if ($save_code != $input_code) {
		$return['status'] = 0;
		$return['msg'] = '验证码错误，请重新输入！';
		$return['type'] = 2;
		die(json_encode($return));
	}
	
	//用户名
	$name = $_POST['name'];
	//用户email
	$email = $_POST['email'];
	//根据用户填写的用户名，email查询是否存在该用户
	$select = "SELECT user_id FROM users WHERE user_name='$name' AND user_email='$email'";
	if (!$result = mysql_query($select,$con)) {
		$return['status'] = 0;
		$return['msg'] = '系统出错，请重试！';
		die(json_encode($return));
	}
	
	if (mysql_num_rows($result) == 1) {
		$mailTo = array();
		$AddAttachment = array();
		array_push($mailTo, array("$email","$name")); //接收方
		array_push($AddAttachment,""); //发附件
		//生成随即密码
		srand((double)microtime()*1000000);//随机种子
		$string = '0,1,2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,I,G,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z';
		$arr = explode(',',$string);//把字符串以摸个间隔转换为数组
		$user_password = '';
		
		for($i=0; $i<8; $i++){//4位验证码
			$randnum = rand(0,35);
			$user_password .= $arr[$randnum];
		}
		
		$save_password = $user_password; //暂时保存，发送给用户
		//加密密码
		$user_password = md5($user_password);
		//当前用户id
		$row = mysql_fetch_assoc($result);
		$user_id = $row['user_id'];
		//更新该用户密码
		$update = "UPDATE users SET user_password='$user_password' WHERE user_id='$user_id' LIMIT 1";
		
		if (!mysql_query($update,$con)) {
			$return['status'] = 0;
			$return['msg'] = '系统出错，请重试！';
			die(json_encode($return));
		}
		
		//发送邮件
		$subject = "您好，这是您找回的会员密码!";
		$body = '<!DOCTYPE html>
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<title>密码找回</title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				</head>
				<body>
					<p>您好，您已经通过我们的网站取回了您的会员密码：</p>
					<p>用户名称：'.$name.'</p>
					<p>会员密码：'.$save_password.'</p>
					<p>请及时到我们的网站更改您的密码，以保证您的数据安全。</p>
					<p>谢谢您对我们一如既往的支持，如有问题，欢迎与我们及时联系。</p>
					<p><a href="http://www.58lou.com" target="_blank">http://www.58lou.com</a></p>
				</body>
				</html>';
		sendmail_sunchis_com($mailTo,$subject,$body,$AddAttachment);
	}
	else {
		$return['status'] = 0;
		$return['msg'] = '用户名和邮箱不匹配！';
		$return['type'] = 1;
		die(json_encode($return));
	}
}

?>