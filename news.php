<?php
require('./connect.php');
require('./libs/Smarty.class.php');
require('./class.common.php');

$smarty = new Smarty();
$common = new Common();
$now_user = $_SESSION['user_name'];//当前用户
$_SESSION['loginUrl'] = $common->getPageUrl(); //保存登录前页面url

/*
 * 读取rss
 * @param string $rssurl ---rss源地址
 * @param string $scode ---rss源字符集,默认为 UTF-8
 * @param string $dcode ---网站字符集,默认为 UTF-8
 */
function getrss($rssurl) { 
	$xml = @file_get_contents($rssurl);
	//读取字符集
	$pattern = "/encoding=\"([a-zA-Z0-9\-]+)\"/";
	preg_match_all($pattern,$xml,$matches);
	$scode = $matches[1][0]; //rss源字符集
	$scode = strtoupper($scode);
	$dcode = 'UTF-8';
	$pattern = "/(\<\!\[CDATA\[)?(\]\]\>)?/"; //去除特殊字符
	$xml = preg_replace($pattern,'',$xml);
	preg_match_all( "/\<item(?:\s+id=\"\d+\")?\>(.*?)\<\/item\>/s", $xml, $items ); //匹配最外层标签里面的内容
	$news = array();
	$i = 0;
	foreach ($items[1] as $item) {
		preg_match_all( "/\<title\>(.*?)\<\/title\>/", $item, $title ); //匹配出文章名
		preg_match_all( "/\<link\>(.*?)\<\/link\>/", $item, $link ); //匹配出文章链接
		preg_match_all( "/\<description\>(.*?)\<\/description\>/", $item, $description ); //匹配出文章描述
		preg_match_all( "/\<pubDate\>(.*?)\<\/pubDate\>/", $item, $pubDate ); //匹配出文章更新时间
		$news[$i++] = array(
			'title' => iconv($scode,$dcode,$title[1][0]),
			'link' => $link[1][0],
			'description' => iconv($scode,$dcode,$description[1][0]),
			'pubDate' => $pubDate[1][0]
		);
	}
	return array('news' => $news);
}

//获取前5条新闻以及翻页页码
$rssurl = "http://news.qq.com/newsgn/rss_newsgn.xml";
$newsinfo = array();
$newsinfo = getrss($rssurl);

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
$smarty->assign('lastnews',true); //高亮新闻导航块
$smarty->assign('news',$newsinfo['news']); //rss读取的新闻
$smarty->assign('hotArts',$common->readHotArts()); //热门文章
$smarty->assign('randomArts',$common->readRandArts()); //随机文章
$smarty->display('news.tpl');

?>