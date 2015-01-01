<?php
require('./globalheader.php');

$_SESSION['loginUrl'] = $common->getPageUrl(); //保存登录前页面url

//读取用户最新文章
function getLastArt($common,$user){
	$select = "SELECT topics_id,article_content FROM articles WHERE user_name='$user' ORDER BY article_time DESC LIMIT 1";
	$result = $common->selectSql($select);
	$row = mysql_fetch_assoc($result);
	return $row;
}

if(!empty($_GET['user']) && $_GET['user'] == $now_user){
	$user = $now_user;
	//取出粉丝
	$row = $common->getUsersValue($user);
	$user_id = $row['user_id'];
	$fansArr = explode('|',$row['fans']);
	$attenArr = explode('|',$row['attention']);
	$fansCount = count($fansArr)-1;
	$attenCount = count($attenArr)-1;
	
	//查看粉丝
	if(!empty($_GET['act']) && $_GET['act'] == 'lookfans'){
		$fans = array();
		foreach ($fansArr as $key => $value){
			if(!empty($value)){
				$oneuser = $common->getUsersValue($value); //当前用户信息
				$oneart = getLastArt($common,$value); //当前用户最新文章信息
				$fans[$key]['name'] = $value; //粉丝名
				$fans[$key]['photo'] = $common->getphoto($value,'50x50'); //粉丝照片
				$fans[$key]['sex'] = $oneuser['sex']; //粉丝性别
				$fans[$key]['fanscount'] = count(explode('|',$oneuser['fans']))-1; //粉丝数
				$fans[$key]['attencount'] = count(explode('|',$oneuser['attention']))-1; //粉丝数
				$fans[$key]['artid'] = $oneart['topics_id']; //最新文章id
				$fans[$key]['artcontent'] = preg_replace("/&nbsp;/",'',strip_tags(html_entity_decode($oneart['article_content'],ENT_QUOTES))); //最新文章内容
			}
		}
		
		$smarty->assign('fans',array_reverse($fans));
		$smarty->assign('lookfans',true);
		
		//查看过粉丝
		$update = "UPDATE users SET newfans=0 WHERE user_name='$user' AND newfans>0";
		if(!mysql_query($update,$con)){
			die(json_encode(array('status'=>0,'系统出错！')));
		}
	}
	
	//查看关注
	if(!empty($_GET['act']) && $_GET['act'] == 'attention'){
		$fans = array();
		foreach ($attenArr as $key => $value){
			if(!empty($value)){
				$oneuser = $common->getUsersValue($value); //当前用户信息
				$oneart = getLastArt($common,$value); //当前用户最新文章信息
				$fans[$key]['name'] = $value; //粉丝名
				$fans[$key]['photo'] = $common->getphoto($value,'50x50'); //粉丝照片
				$fans[$key]['sex'] = $oneuser['sex']; //粉丝性别
				$fans[$key]['fanscount'] = count(explode('|',$oneuser['fans']))-1; //粉丝数
				$fans[$key]['attencount'] = count(explode('|',$oneuser['attention']))-1; //粉丝数
				$fans[$key]['artid'] = $oneart['topics_id']; //最新文章id
				$fans[$key]['artcontent'] = preg_replace("/&nbsp;/",'',strip_tags(html_entity_decode($oneart['article_content'],ENT_QUOTES))); //文章内容
			}
		}
		
		$smarty->assign('fans',array_reverse($fans));
		$smarty->assign('attention',true);
	}
	
	$smarty->assign('fansCount',$fansCount); //粉丝数
	$smarty->assign('attenCount',$attenCount); //关注数
	
	//找人
	if(!empty($_GET['act']) && $_GET['act'] == 'find'){
		$smarty->assign('find',true);
	}
	
	//邀请人注册
	if(!empty($_GET['act']) && $_GET['act'] == 'invite'){
		$smarty->assign('invite',true);
		$smarty->assign('httphost',$_SERVER['HTTP_HOST']);
	}

	if (!empty($now_user)) {
		$user_id = $common->get_user_id($now_user);
		//文章未读评论提示
		$smarty->assign('newartcomment',$common->commentMsg($now_user));
		//未读回复
		$smarty->assign('newreply',$common->replyMsg($now_user));
		//未读私信，做出提示
		$smarty->assign('newsms',$common->newsms($now_user));
		//读取新空间留言数，做出提示
		$smarty->assign('msgnum',$common->msgnum($user_id));
		//读取用户相册评论数，做出提示
		$smarty->assign('newalcomment',$common->album_comments_num($user_id));
		//新粉丝提示
		$smarty->assign('newfans',$common->newfans($now_user));
	}

	$smarty->assign('isMobile',$common->is_mobile());
	//读取用户信息
	$someUser['user_name'] = $user;
	$smarty->assign('someUser',$someUser);
	$smarty->assign('now_user',$now_user); //当前用户
	//标识查看粉丝页面
	$smarty->assign('fanspage',true);
	$smarty->display('fans.tpl');
}
else{
	die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
}

?>