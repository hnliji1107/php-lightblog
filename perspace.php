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
		$smarty->assign('someUser',$row);
		$smarty->assign('now_user',$now_user);
		
		//查找访问用户
		$user_id = $row['user_id']; //用户id
		$user_name = $row['user_name']; //用户名
		$select = "SELECT * FROM space_visiters WHERE assocart_id='$user_id' ORDER BY visiter_time";
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
		$select = "SELECT * FROM space_visiters WHERE visiter_name='$now_user' AND assocart_id='$user_id'"; //读取当前空间访问表中用户
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
		
		//读取用户文章
		$select = "SELECT * FROM articles WHERE user_name='$user' ORDER BY article_time DESC";
		$result = $common->selectSql($select);
		$spacerArts = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$spacerArts[$i] = $row;
			$spacerArts[$i]['article_content'] = preg_replace("/&nbsp;/",'',strip_tags(html_entity_decode($row['article_content'],ENT_QUOTES)));
			$spacerArts[$i]['comments'] = $common->getCommentNum('comment_id','reply_id','comments',$row['topics_id']);
			$spacerArts[$i]['collects'] = $common->getCollectNum($row['topics_id']);
			$i++;	
		}
		
		$smarty->assign('spacerArts',$spacerArts);
		
		//本人最新动态
		
		//本人收藏
		function getart($common,$artid){
			$select = "SELECT topics_id,user_name,article_title FROM articles WHERE topics_id='$artid' LIMIT 1";
			$result = $common->selectSql($select);
			$row = mysql_fetch_assoc($result);
			return $row;
		}
		
		$select = "SELECT * FROM collects WHERE collecter='$user' ORDER BY collect_time DESC";
		$result = $common->selectSql($select);
		$myCollects = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$myCollects[$i] = getart($common,$row['assocart_id']);
			$myCollects[$i]['collectTime'] = $row['collect_time'];
			$i++;
		}

		$smarty->assign('myCollects',$myCollects);
		
		//本人评论的文章
		$select = "SELECT * FROM comments WHERE user_name='$user' ORDER BY comment_time DESC";
		$result = $common->selectSql($select);
		$myComments = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$myComments[$i] = getart($common,$row['reply_id']);
			$myComments[$i]['commentTime'] = $row['comment_time'];
			$myComments[$i]['commentContent'] = preg_replace("/&nbsp;/",'',strip_tags(html_entity_decode($row['comment_content'],ENT_QUOTES)));
			$i++;
		}
		
		$smarty->assign('myComments',$myComments);
		
		//根据用户id读取用户信息
		function getVisitor($common,$useid){
			$select = "SELECT user_name FROM users WHERE user_id='$useid' LIMIT 1";
			$result = $common->selectSql($select);
			$row = mysql_fetch_assoc($result);
			return $row;
		}
		
		//根据相册id读取相册信息
		function getAlbumInfo($album_id){
			$select = "SELECT album_name,assoc_userid FROM user_album WHERE album_id='$album_id' LIMIT 1";
			if(!$result = mysql_query($select)){
				die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
			}
			return $row = mysql_fetch_assoc($result);
		}
		
		//本人评论的空间
		$select = "SELECT * FROM user_msgs WHERE user_name='$user'";
		$result = $common->selectSql($select);
		$myspComments = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$myspComments[$i] = getVisitor($common,$row['user_id']);
			$myspComments[$i]['msgTime'] = $row['msg_time'];
			$myspComments[$i]['msgContent'] = $row['msg_text'];
		}
		
		$smarty->assign('myspComments',$myspComments);
		
		//本人对他人相册的评论
		$select = "SELECT distinct(album_id) FROM album_comment WHERE user_name='$user'"; //查找当前用户留言相册的id
		$result = $common->selectSql($select);
		$myalcomment = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$album_info = getAlbumInfo($row['album_id']); //根据相册id找出用户id和相册名
			$user_info = getVisitor($common,$album_info['assoc_userid']); //根据用户id找出用户名
			$myalcomment[$i]['album_id'] = $row['album_id']; //相册id
			$myalcomment[$i]['album_name'] = $album_info['album_name']; //相册名
			$myalcomment[$i]['album_owner'] = $user_info['user_name']; //相册拥有者
			//根据相册id读取相册最新留言
			$tmpid = $row['album_id'];
			$select = "SELECT alcomment_text,alcomment_time FROM album_comment WHERE album_id='$tmpid' ORDER BY alcomment_time DESC";
			$subresult = $common->selectSql($select);
			$j = 0;
			while ($row = mysql_fetch_assoc($subresult)) {
				$myalcomment[$i][$j] = $row;
				$myalcomment[$i][$j]['alcomment_text'] = preg_replace("/&nbsp;/",'',strip_tags(html_entity_decode($row['alcomment_text'],ENT_QUOTES)));
				$j++;
			}
			$i++;
		}
		
		$smarty->assign('myalcomment',$myalcomment);
		
		//本人上传的相片
		//读取用户id
		$uid = $common->get_user_id($user);
		
		//读取相册
		$select = "SELECT album_id,album_name,album_photos FROM user_album WHERE assoc_userid='$uid'";
		$result = $common->selectSql($select);
		$albums = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$albums[$i]['album_name'] = $row['album_name'];
			$albums[$i]['album_id'] = $row['album_id'];
			$phs = explode('|',$row['album_photos']);
			$album_path = 'data/albums/album_'.$row['album_id'].'/'; //相册路径
			//含路径的照片数组
			$path_phs = array();
			foreach ($phs as $key => $value) {
				if (!empty($value)){
					$is_exists = $album_path.'50x50/'.iconv('utf-8','gb2312',$value);
					$path_phs[$key] = $album_path.'50x50/'.$value;
					if(!file_exists($is_exists)){
						$path_phs[$key] = $album_path.$value;
					}
				}
			}
			$albums[$i]['album_photos'] = array_reverse($path_phs);
			$i++;
		}
		
		$smarty->assign('albums',$albums);
		
		//本人关注
		$attenRow = $common->getUsersValue($user);
		$attenerArr = explode('|',$attenRow['attention']);
		array_pop($attenerArr); //去除最后一个空值
		$smarty->assign('attenerArr',$attenerArr);
		
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
		//当前用户的粉丝
		$smarty->assign('fansArr',$common->tafans($user));
		//注册那次登录
		$smarty->assign('firstLogin',empty($_SESSION['firstLogin'])? '' : $_SESSION['firstLogin']);
		$smarty->assign('spacehome',true);
		
		//空间--动态页面
		if(!empty($_GET['dynamic']) && $_GET['dynamic'] == 'yes'){
			$smarty->assign('spacehome',false);
			$smarty->assign('dynamic',true);
		}
		
		//空间--文章页面
		if(!empty($_GET['morearts']) && $_GET['morearts'] == 'yes'){
			$smarty->assign('spacehome',false);
			$smarty->assign('morearts',true);
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

		$smarty->display('perspace.tpl');
	}
	else{
		die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
	}
}

?>