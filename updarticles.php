<?php
require('./globalheader.php');

//修改入口
if(!empty($_GET['artid']) && !empty($_GET['act']) && $_GET['act'] == 'up'){
	$artid = $_GET['artid'];
	$select = "SELECT * FROM articles WHERE topics_id='$artid' LIMIT 1";
	$result = $common->selectSql($select);
	$row = mysql_fetch_assoc($result);

	$smarty->assign('openmsg',true);
	$smarty->assign('artid',$artid);
	$smarty->assign('arttitle',$row['article_title']);
	$smarty->assign('artcontent',$row['article_content']);
	$smarty->assign('arttype',$row['article_type']);
	
	//读取所有保存到服务器的附件
	$select = "SELECT attachment_id,attachment_path,attachment_time FROM article_attachment WHERE user_name='$now_user' AND assoc_artid=0 ORDER BY attachment_time";
	$filenames = array();
	$filenames = $common->getfileinfo($select);
	
	if (!empty($filenames)) {
		$smarty->assign('is_open_attachment',true);
		$smarty->assign('filenames',$filenames); //所有附件
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
	
	$smarty->assign('isUser',$now_user);
	$smarty->assign('perNavBk',true);
	$smarty->assign('is_updart',true);
	$smarty->display('percenter.tpl');
}
else{
	die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
}