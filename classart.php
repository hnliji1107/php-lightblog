<?php
require('./connect.php');
require('./libs/Smarty.class.php');
require('./class.common.php');

$smarty = new Smarty();
$common = new Common();
$now_user = $_SESSION['user_name'];//当前用户
$_SESSION['loginUrl'] = $common->getPageUrl(); //保存登录前页面url

if(!empty($_GET['act'])){
	if($_GET['act']==='html'){
		$category = 'HTML';
		$someCategory = $common->getCategoryList($category);
		$smarty->assign('title',$category);
		$smarty->assign('html_yes',true);
		$smarty->assign('someCategory',$someCategory);
	}
	if($_GET['act']==='css'){
		$category = 'CSS';
		$someCategory = $common->getCategoryList($category);
		$smarty->assign('title',$category);
		$smarty->assign('css_yes',true);
		$smarty->assign('someCategory',$someCategory);
	}
	if($_GET['act']==='js'){
		$category = 'JS';
		$someCategory = $common->getCategoryList($category);
		$smarty->assign('title',$category);
		$smarty->assign('js_yes',true);
		$smarty->assign('someCategory',$someCategory);
	}
	if($_GET['act']==='jq'){
		$category = 'JQ';
		$someCategory = $common->getCategoryList($category);
		$smarty->assign('title',$category);
		$smarty->assign('jq_yes',true);
		$smarty->assign('someCategory',$someCategory);
	}
	if($_GET['act']==='php'){
		$category = 'PHP';
		$someCategory = $common->getCategoryList($category);
		$smarty->assign('title',$category);
		$smarty->assign('php_yes',true);
		$smarty->assign('someCategory',$someCategory);
	}
	if($_GET['act']==='nodejs'){
		$category = 'NODEJS';
		$someCategory = $common->getCategoryList($category);
		$smarty->assign('title',$category);
		$smarty->assign('nodejs_yes',true);
		$smarty->assign('someCategory',$someCategory);
	}
	if($_GET['act']==='allarts'){
		$category = '全部';
		$someCategory = $common->getCategoryList($category);
		$smarty->assign('title',$category);
		$smarty->assign('allarts_yes',true);
		$smarty->assign('someCategory',$someCategory);
	}
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
$smarty->assign('isUser',$now_user);
$smarty->assign('hotArts',$common->readHotArts()); //热门文章
$smarty->assign('randomArts',$common->readRandArts()); //随机文章
$smarty->display('classart.tpl');

?>