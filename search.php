<?php
require('./connect.php');
require('./libs/Smarty.class.php');
require('./class.common.php');

$smarty = new Smarty();
$common = new Common();
$now_user = $_SESSION['user_name'];//当前用户

$condition = $_POST['condition']; //搜索关键字
$search_range = $_POST['search_range']; //搜索范围

$extra = "$search_range LIKE '%$condition%'";
$someCategory = $common->getCategoryList('全部','',$extra,$condition,$search_range);

if (empty($condition)) {
	$smarty->assign('condition','查询文章，请输入关键字。'); //返回搜索关键字
}
else {
	$smarty->assign('condition',$condition); //返回搜索关键字
}

//返回搜索范围
switch ($search_range) {
	case 'article_title':{
		$smarty->assign('search_range','文章标题'); //文章标题,赋给显示部分
		$smarty->assign('search_rang_value','article_title'); //赋给值部分
	}
	break;
	case 'article_content':{
		$smarty->assign('search_range','文章内容'); //文章内容
		$smarty->assign('search_rang_value','article_content');
	}
	break;
	case 'article_type':{
		$smarty->assign('search_range','文章类型'); //文章类型
		$smarty->assign('search_rang_value','article_type');
	}
	break;
	case 'user_name':{
		$smarty->assign('search_range','文章作者'); //作者名称
		$smarty->assign('search_rang_value','user_name');
	}
	break;
	default:{
		$smarty->assign('search_range','文章内容'); //作者名称
		$smarty->assign('search_rang_value','article_content');
	}
	break;
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
$smarty->assign('title','搜索结果');
$smarty->assign('search',true);
$smarty->assign('someCategory',$someCategory);
$smarty->assign('nowuser_arts',$nowuser_arts); //每个用户文章组合
$smarty->assign('hotArts',$common->readHotArts()); //热门文章
$smarty->assign('randomArts',$common->readRandArts()); //随机文章
$smarty->display('classart.tpl');

?>