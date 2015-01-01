<?php
require('./connect.php');
require('./libs/Smarty.class.php');
require('./class.common.php');

$smarty = new Smarty();
$common = new Common();
$now_user = $_SESSION['user_name'];//当前用户
$_SESSION['loginUrl'] = $common->getPageUrl(); //保存登录前页面url

if(!empty($_GET['user'])){ //空间主页
	$user = $_GET['user'];
	if(!$common->check_user($user)){ //如果该用户存在
		//上次登录时间
		$select = "SELECT last_login_time,login_time FROM users WHERE user_name='$user' LIMIT 1";
		$result = $common->selectSql($select);
		$row = mysql_fetch_assoc($result);
		$smarty->assign('last_login_time',$row['last_login_time']);
		
		//处理上次登录时间
		if($_SESSION['user_name'] != $user){
			$smarty->assign('unlogin',true);
			$smarty->assign('re_login_time',$row['login_time']);
		}
		
		//读取用户信息
		$select = "SELECT user_id,user_name,sex,signature,user_photo FROM users WHERE user_name='$user' LIMIT 1";
		$result = $common->selectSql($select);
		$row = mysql_fetch_assoc($result);
		$origin_ph = $row['user_photo'];
		$is_exists = 'data/userphotos/100x100/'.iconv('utf-8','gb2312',$origin_ph);
		$row['user_photo'] = 'data/userphotos/100x100/'.$origin_ph;
		//如果没有缩略图，则使用原图
		if(!file_exists($is_exists)){
			$row['user_photo'] = 'data/userphotos/'.$origin_ph;
		}
		
		$smarty->assign('isMobile',$common->is_mobile());
		$smarty->assign('now_user',$now_user);
		$smarty->assign('someUser',$row);
		
		//查找访问用户
		$user_id = $row['user_id']; //用户id
		$user_name = $row['user_name']; //用户名
		$select = "SELECT * FROM space_visiters WHERE assocart_id='$user_id'";
		$result = $common->selectSql($select);
		$visiters = array();
		$nowvisiter_arts = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$tmpname = $row['visiter_name'];
			$visiters[$i] = $row;
			$visiters[$i]['visiter_photo'] = $common->getphoto($tmpname,'48x48');
			$i++;
		}
		
		$smarty->assign('visiters',$visiters);
		$smarty->assign('nowvisiter_arts',$nowvisiter_arts);
		
		//保存访问用户
		$select = "SELECT * FROM space_visiters WHERE visiter_name='$now_user' AND
					assocart_id='$user_id'"; //读取当前空间访问表中用户
		$result = $common->selectSql($select);
		
		if($now_user !== $user_name && !empty($user_id)){ //访问者不包括作者
			$now_time = date('Y-m-d H:i:s');
			if(mysql_num_rows($result)>0){ //如果当前用户已访问过该文章，则更新最新时间
				$update = "UPDATE space_visiters SET visiter_time='$now_time' WHERE
							visiter_name='$now_user' AND assocart_id='$user_id'";
				if(!mysql_query($update,$con)){
					die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
				}
			}
			else if(!empty($now_user)){ //如果有登陆用户且表中不存在该用户，则添加入表中
				$insert = "INSERT INTO space_visiters (visiter_name,visiter_time,assocart_id) VALUES
						('$now_user','$now_time','$user_id')";
				if(!mysql_query($insert,$con)){
					die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
				}
			}
		}

		//用户关注
		$row = $common->getUsersValue($user_name);
		$attentions = explode('|',$row['attention']);
		$attenArr = array();
		
		foreach ($attentions as $key => $value){
			if(!empty($value)){ //不为空
				$attenArr[$key]['name'] = $value; //关注者名名称
				$attenArr[$key]['photo'] = $common->getphoto($value,'48x48'); //关注者照片
			}
		}
		
		$smarty->assign('attenArr',$attenArr); //关注者
		
		if(!empty($now_user) && !empty($_POST['userid']) && !empty($_POST['gbcomment_text'])){ //插入留言
			$msg = htmlspecialchars(preg_replace("/\n/",'<br />',$_POST['gbcomment_text']),ENT_QUOTES); //html标签转化为实体字符
			$userid = $_POST['userid'];
			$commenter_os = $common->getos(); //评论者的操作系统
			$commenter_browser = $common->getbrowser(); //评论者的浏览器
			$select = "SELECT msg_id FROM user_msgs WHERE user_name='$now_user' AND msg_text='$msg' AND user_id='$userid'";
			
			if(!$result = mysql_query($select,$con)){
				die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
			}

			$select2 = "SELECT user_name FROM users WHERE user_id='$userid'";

			if(!$result2 = mysql_query($select2,$con)){
				die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
			}

			$row2 = mysql_fetch_assoc($result2);
			
			if(mysql_num_rows($result) === 0 && $row2['user_name'] !== ''){
				$msg_time = date('Y-m-d H:i:s');
				//插入留言
				$insert = "INSERT INTO user_msgs (user_name,msg_text,msg_time,user_id,commenter_os,commenter_browser) VALUES
							('$now_user','$msg','$msg_time','$userid','$commenter_os','$commenter_browser')";
				if(!mysql_query($insert,$con)){
					die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
				}

				//更新未查看留言数
				$update = "UPDATE users SET msgnum=msgnum+1 WHERE user_id='$userid' LIMIT 1";
				if(!mysql_query($update,$con)){
					die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
				}
			}
		}

		if ($now_user == $user) { //查看新空间留言，并更新新留言数
			$update = "UPDATE users SET msgnum=0 WHERE user_name='$user' AND msgnum>0 LIMIT 1";
			if(!mysql_query($update,$con)){
				die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
			}
		}

		//读取用户空间留言
		$select = "SELECT * FROM user_msgs WHERE user_id='$user_id' ORDER BY msg_time DESC";
		$result = $common->selectSql($select);
		$msgs = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$tmpname = $row['user_name'];
			$msgs[$i] = $row;
			$msgs[$i]['msg_text'] = html_entity_decode($row['msg_text'],ENT_QUOTES); //转化实体字符为html标签
			$msgs[$i]['userphoto'] = $common->getphoto($tmpname,'50x50');
			//读取每条留言对应回复
			$msgs_replys[$row['msg_id']] = $common->getreplys('msgs_replys',$row['msg_id']);
			//查找指定用户签名
			$select = "SELECT signature FROM users WHERE user_name='$tmpname' LIMIT 1";
			$subre = $common->selectSql($select);
			$row = mysql_fetch_assoc($subre);
			$msgs[$i]['signature'] = $row['signature']; //用户签名
			$i++;
		}

		if (!empty($now_user)) {
			$user_id = $common->get_user_id($now_user);
			//文章未读评论提示
			$smarty->assign('newartcomment',$common->commentMsg($now_user));
			//未读回复
			$smarty->assign('newreply',$common->replyMsg($now_user));
			//未读私信提示
			$smarty->assign('newsms',$common->newsms($now_user));
			//读取新空间留言数提示
			$smarty->assign('msgnum',$common->msgnum($user_id));
			//读取用户相册评论数提示
			$smarty->assign('newalcomment',$common->album_comments_num($user_id));
			//新粉丝提示
			$smarty->assign('newfans',$common->newfans($now_user));
		}
		
		//当前用户的粉丝
		$smarty->assign('fansArr',$common->tafans($user));
		$smarty->assign('msgs',$msgs);
		$smarty->assign('msgs_replys',$msgs_replys);
		$smarty->assign('nowuser_arts',$nowuser_arts);
		//注册那次登录
		$smarty->assign('firstLogin',empty($_SESSION['firstLogin'])? '' : $_SESSION['firstLogin']);
		//标识留言页面
		$smarty->assign('gbpage',true);
		$smarty->display('guestbook.tpl');
	}
	else{
		die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
	}
}

?>