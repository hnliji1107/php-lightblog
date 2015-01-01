<?php
require('./connect.php');
require('./libs/Smarty.class.php');
require('./class.common.php');

$smarty = new Smarty();
$common = new Common();
$now_user = $_SESSION['user_name'];//当前用户
$_SESSION['loginUrl'] = $common->getPageUrl(); //保存登录前页面url

if(!empty($_GET['artid'])){
	$lookid = $_GET['artid'];
	$now_time = date('Y-m-d H:i:s'); //当前时间
	
	//插入评论
	if (!empty($now_user) && !empty($_POST['replyid']) && !empty($_POST['comment_text'])) {
		$comment_text = htmlspecialchars($_POST['comment_text'],ENT_QUOTES);
		$replyid = $_POST['replyid'];
		$commenter_os = $common->getos(); //评论者的操作系统
		$commenter_browser = $common->getbrowser(); //评论者的浏览器
		$select = "SELECT comment_id FROM comments WHERE user_name='$now_user' AND comment_content='$comment_text' AND reply_id='$replyid'";
		$result = $common->selectSql($select);
		
		if (mysql_num_rows($result) === 0) {
			$insert = "INSERT INTO comments (user_name,comment_content,comment_time,reply_id,commenter_os,commenter_browser) VALUES
					('$now_user','$comment_text','$now_time','$replyid','$commenter_os','$commenter_browser')";
		
			if(!mysql_query($insert,$con)){
				die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
			}
			
			//利用当前插入评论的id，更新附件
			$tmpcommentid = mysql_insert_id();
			$update = "UPDATE comment_attachment SET assoc_commentid='$tmpcommentid' WHERE assoc_artid='$replyid' AND assoc_commentid=0"; //assoc_commentid=0,表示当前附件没有被提交

			if (!mysql_query($update,$con)) {
				die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
			}
			
			//更新未读评论数
			$update = "UPDATE articles SET newartcomment=newartcomment+1 WHERE topics_id='$replyid' LIMIT 1";
			if (!mysql_query($update,$con)) {
				die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
			}
		}
	}
	
	//读取所有保存到服务器的附件
	$select = "SELECT attachment_id,attachment_path,attachment_time FROM comment_attachment WHERE user_name='$now_user' AND 
	assoc_commentid=0 AND assoc_artid='$lookid' ORDER BY attachment_time";
	$filenames = array();
	$filenames = $common->getfileinfo($select);
	
	if (!empty($filenames)) {
		$smarty->assign('is_open_attachment',true);
		$smarty->assign('filenames',$filenames); //所有附件
	}
	
	//读取该篇文章所有附件
	$select = "SELECT attachment_id,attachment_path,downloads FROM article_attachment WHERE assoc_artid='$lookid' ORDER BY attachment_time";
	$article_filenames = array();
	$article_filenames = $common->getfileinfo($select);

	$smarty->assign('article_filenames',$article_filenames); //文章附带的所有附件
	
	//查找文章
	$select = "SELECT * FROM articles WHERE topics_id='$lookid' LIMIT 1";
	$result = $common->selectSql($select);
	$someart = $row = mysql_fetch_assoc($result);
	$title = $row['article_title']? $row['article_title'] : '抱歉，文章不存在！';
	$article_id = $row['topics_id']; //文章id
	$article_name = $row['user_name']; //文章作者
	$article_content = html_entity_decode($row['article_content'],ENT_QUOTES); //文章内容
	//添加图片懒加载功能
	$article_content = preg_replace("/<img src=/i",'<img src="images/lazyload.png" data-lazyload-src=',$article_content);
	$someart['article_content'] = $article_content;

	//看过评论后更新未读数据
	if($now_user == $article_name){
		$update = "UPDATE articles SET newartcomment=0 WHERE topics_id='$article_id' LIMIT 1";
		if(!mysql_query($update,$con)){
			die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
		}
	}
	
	//查找上一篇
	$select_pre = "SELECT topics_id,article_title FROM articles WHERE topics_id < '$lookid' ORDER BY topics_id DESC";
	$result_pre = $common->selectSql($select_pre);
	while ($row_pre = mysql_fetch_array($result_pre)) {
		$smarty->assign('prevArt',$row_pre);
		break;
	}
	
	//查找下一篇
	$select_next = "SELECT topics_id,article_title FROM articles WHERE topics_id > '$lookid' ORDER BY topics_id";
	$result_next = $common->selectSql($select_next);
	while ($row_next = mysql_fetch_array($result_next)) {
		$smarty->assign('nextArt',$row_next);
		break;
	}
	
	//更新未读回复
	if (!empty($now_user)) {
		//读取评论id
		$select = "SELECT comment_id FROM comments WHERE reply_id='$lookid'";
		$result = $common->selectSql($select);
		
		while ($row = mysql_fetch_assoc($result)) {
			$tmpcommentid = $row['comment_id'];
			$update = "UPDATE replys SET is_newreply=0 WHERE receiver='$now_user' AND associd='$tmpcommentid' AND is_newreply=1";
			
			if(!mysql_query($update,$con)){
				die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
			}
		}
		
	}
	
	$session_artid = $now_user.'firopen_'.$article_id;
	if($now_user !== $article_name && !empty($article_id) && !isset($_SESSION[$session_artid])){
		//访问量+1并存入数据库
		$update = "UPDATE articles set visit_num=visit_num+1 WHERE topics_id='$article_id'";
		if(!mysql_query($update,$con)){
			die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
		}
	}
	
	//判断是否第一次打开这个页面
	if($now_user !== $article_name && !empty($article_id) && !isset($_SESSION[$session_artid])){
		$_SESSION[$session_artid] = $now_user.$article_id.'_yes'; //如果第一次打开，为本文章设置session
	}
	
	//查找访问用户
	$select = "SELECT * FROM visiters WHERE assocart_id='$article_id'";
	$result = $common->selectSql($select);
	$visiters = array();
	$nowvisiter_arts = array();
	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$tmpname = $row['visiter_name'];
		$visiters[$i] = $row;
		$visiters[$i]['visiter_photo'] = $common->getphoto($tmpname,'50x50');
		$i++;
	}
	
	//保存访问用户
	$select = "SELECT * FROM visiters WHERE visiter_name='$now_user' AND
				assocart_id='$article_id'"; //读取当前访问表中用户
	$result = $common->selectSql($select);
	
	if($now_user !== $article_name && !empty($article_id)){ //访问者不包括作者
		if(mysql_num_rows($result)>0){ //如果当前用户已访问过该文章，则更新最新时间
			$update = "UPDATE visiters SET visiter_time='$now_time' WHERE
						visiter_name='$now_user' AND assocart_id='$article_id'";
			if(!mysql_query($update,$con)){
				die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
			}
		}
		else if(!empty($now_user)){ //如果有登陆用户且表中不存在该用户，则添加入表中
			$insert = "INSERT INTO visiters (visiter_name,visiter_time,assocart_id) VALUES
					('$now_user','$now_time','$article_id')";
			if(!mysql_query($insert,$con)){
				die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
			}
		}
	}
	
	//查找关联评论
	$select = "SELECT * FROM comments WHERE reply_id='$article_id' ORDER BY comment_time";
	$result = $common->selectSql($select);
	$comments = array();
	$nowuser_arts = array();
	$i = 0;

	while ($row = mysql_fetch_assoc($result)) {
		$tmpname = $row['user_name'];
		$tmpid = $row['comment_id'];
		$comments[$i] = $row;
		$comments[$i]['comment_content'] = html_entity_decode($row['comment_content'],ENT_QUOTES);
		$comments[$i]['userphoto'] = $common->getphoto($tmpname,'50x50'); //用户头像
		//读取用户已上传附件
		$select = "SELECT attachment_id,attachment_path,downloads FROM comment_attachment WHERE assoc_commentid='$tmpid' AND assoc_artid='$article_id'";
		$comments[$i]['attachment'] = $common->getfileinfo($select);
		//读取用户签名
		$select = "SELECT signature FROM users WHERE user_name='$tmpname' LIMIT 1";
		$subre = $common->selectSql($select);
		$row = mysql_fetch_assoc($subre);
		$comments[$i]['signature'] = $row['signature']; //用户签名
		$i++;
	}
	
	$replys = array();
	foreach ($comments as $value){//依次获取本文章中每条评论对应回复
		$replys[$value['comment_id']] = $common->getreplys('replys',$value['comment_id']);
	}
	
	//查找当前文章收藏数
	$select = "SELECT * FROM collects WHERE assocart_id='$article_id'";
	$result = $common->selectSql($select);
	$collect_num = mysql_num_rows($result);

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
	$smarty->assign('title',$title);
	$smarty->assign('separticle',true);
	$smarty->assign('someart',$someart); //文章
	$smarty->assign('nowuser_arts',$nowuser_arts); //每个用户文章组合
	$smarty->assign('nowvisiter_arts',$nowvisiter_arts); //访问者文章组合
	$smarty->assign('visiters',$visiters); //访问数
	$smarty->assign('comments',$comments); //评论数
	$smarty->assign('collect_num',$collect_num); //收藏数
	$smarty->assign('replys',$replys); //回复
	$smarty->assign('hotArts',$common->readHotArts()); //热门文章
	$smarty->assign('randomArts',$common->readRandArts()); //随机文章
	$smarty->display('separticle.tpl');
}

?>