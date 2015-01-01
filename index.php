<?php
require('./connect.php');
require('./libs/Smarty.class.php');
require('./class.common.php');

$smarty = new Smarty();
$common = new Common();
$now_user = $_SESSION['user_name'];//当前用户
$_SESSION['loginUrl'] = $common->getPageUrl(); //保存登录前页面url

//获取网站外链
function getoutlinks($common) {
	$select = "SELECT * FROM outlinks WHERE 1=1 ORDER BY link_time ASC";
	$result = $common->selectSql($select);
	$arr = array();
	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$arr[$i++] = $row;
	}
	return $arr;
}

//获取指定类下文章
function getclsatrs($common,$cls){
	$select = "SELECT * FROM articles WHERE article_type='$cls' ORDER BY article_time DESC LIMIT 10";
	$result = $common->selectSql($select);
	$artlist = array();
	$i = 0;
	while($row = mysql_fetch_assoc($result)){
		$artlist[$i] = $row;
		$artlist[$i]['comment_c'] = $common->getCommentNum('comment_id','reply_id','comments',$row['topics_id']); //评论数
		$artlist[$i]['collect_c'] = $common->getCollectNum($row['topics_id']); //收藏数
		$artlist[$i]['arttype'] = $cls;
		$i++;
	}
	return $artlist;
}

//整合所有分类到一个数组里
$cls_array = array(
	array(
		'arttype' => 'html',
		'artinfo' => getclsatrs($common,'html')
	),
	array(
		'arttype' => 'css',
		'artinfo' => getclsatrs($common,'css')
	),
	array(
		'arttype' => 'js',
		'artinfo' => getclsatrs($common,'js')
	),
	array(
		'arttype' => 'jq',
		'artinfo' => getclsatrs($common,'jq')
	),
	array(
		'arttype' => 'php',
		'artinfo' => getclsatrs($common,'php')
	),
	array(
		'arttype' => 'nodejs',
		'artinfo' => getclsatrs($common,'nodejs')
	),
);
//资源分类
//显示资源
/*
$select = "SELECT attachment_id,user_name,attachment_path,attachment_time,downloads,attachment_flag FROM comment_attachment WHERE 1=1 UNION ALL 
			SELECT attachment_id,user_name,attachment_path,attachment_time,downloads,attachment_flag FROM article_attachment WHERE 1=1 UNION ALL
			SELECT attachment_id,user_name,attachment_path,attachment_time,downloads,attachment_flag FROM resource_attachment WHERE 1=1 ORDER BY attachment_time DESC LIMIT 5";
*/
$select = "SELECT attachment_id,user_name,attachment_path,attachment_time,downloads,attachment_flag FROM resource_attachment WHERE 1=1 ORDER BY attachment_time DESC LIMIT 10";
$cls_resource = $common->getfileinfo($select);

//根据时间排序文章
$select = "SELECT topics_id,user_name,article_title,article_content,article_time FROM articles ORDER BY article_time DESC LIMIT 9";
$recommendarts = $common->getArtsBy($select);

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
$smarty->assign('indexNavBk',true);
$smarty->assign('cls_array',$cls_array); //分类显示文章
$smarty->assign('cls_resource',$cls_resource); //分类中资源
$smarty->assign('recommendarts',$recommendarts); //推荐文章by时间
$smarty->assign('hotArts',$common->readHotArts()); //热门文章
$smarty->assign('randomArts',$common->readRandArts()); //随机文章
$smarty->assign('outlinks',getoutlinks($common)); //网站外链
$smarty->assign('isSuperAccount',$now_user=='58lou'); //是否超级账号
$smarty->display('index.tpl');

?>