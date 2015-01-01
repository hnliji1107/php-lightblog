<?php
require('./connect.php');
require('./libs/Smarty.class.php');
require('./class.common.php');

$smarty = new Smarty();
$common = new Common();

// cutstr;
// $string 是要处理的字符串 
// $length 为截取的长度(即字数) 
function cutstr($string, $length) {
	preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $info);  
	for($i=0; $i<count($info[0]); $i++) {
		$wordscut .= $info[0][$i];
		$j = ord($info[0][$i]) > 127 ? $j + 2 : $j + 1;
		if($j > $length - 3){
		    return $wordscut." ...";
		}
	}
	return join('', $info[0]);
}

// 根据文章id读取它的评论
function getcomments($common,$artid) {
	$select = "SELECT comment_content FROM comments WHERE reply_id='$artid' ORDER BY comment_time DESC LIMIT 1";
	$result = $common->selectSql($select);
	$row = mysql_fetch_assoc($result);
	$tempcomment = strip_tags($row['comment_content'],'<img>,<embed>');
	$tempcomment = preg_replace("/&nbsp;/",'',$tempcomment);
	return $tempcomment? $tempcomment : '暂无评论';
}

$select = "SELECT topics_id,user_name,article_title,article_content,article_time FROM articles ORDER BY article_time DESC ";
$result = $common->selectSql($select);
$items = array();
$i = 0;

while ($row = mysql_fetch_assoc($result)) {
	// $artcontent = $row['article_content'];
	// $artcontent = html_entity_decode($artcontent, ENT_QUOTES);
	// $artcontent = cutstr($artcontent,300);
	$items[$i]['topics_id'] = $row['topics_id'];
	$items[$i]['article_title'] = $row['article_title'];
	// $items[$i]['article_content'] = $artcontent;
	$items[$i]['user_name'] = $row['user_name'];
	$items[$i]['article_time'] = $row['article_time'];
	// $items[$i]['article_comment'] = getcomments($common,$row['topics_id']);
	$i++;
}

$smarty->assign('domain', 'http://www.58lou.com/');
$smarty->assign('items',$items);
$smarty->display('feed.tpl');

?>