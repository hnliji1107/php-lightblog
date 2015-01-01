<?php
require('./globalheader.php');

$select = "SELECT * FROM users WHERE user_name='$now_user' LIMIT 1";
$result = $common->selectSql($select);
$user_infomation = $row = mysql_fetch_assoc($result);
$photo_path = 'data/userphotos/';
$a_file = $row['user_photo'];
$is_exists = $photo_path.iconv('utf-8','gb2312',$a_file);
$photo = $photo_path.'130x130/'.$a_file;
//如果没有缩略图，则使用原图
if(!file_exists($is_exists)){
	$photo = $photo_path.$a_file;
}

//上传头像
if ((($_FILES['file']['type'] == 'image/gif') || ($_FILES['file']['type'] == 'image/jpeg') || ($_FILES['file']['type'] == 'image/pjpeg') || ($_FILES['file']['type'] == 'image/png')) && ($_POST['MAX_FILE_SIZE'] == 8388608) && ($_FILES['file']['size'] <= 8388608)) {
	
	if ($_FILES['file']['error'] > 0){
		switch ($_FILES['file']['error']) {
			case 1:{
				$err = "<script type='text/javascript'>alert('上传的图片大小超出8M限制，上传失败！');</script>";
			}
			break;
			
			case 2:{
				$err = "<script type='text/javascript'>alert('上传的图片大小超出8M限制，上传失败！');</script>";
			}
			break;
			
			case 3:{
				$err = "<script type='text/javascript'>alert('文件没有被完全上传，上传失败！');</script>";
			}
			break;
			
			case 4:{
				$err = "<script type='text/javascript'>alert('没有文件被上传，上传失败！');</script>";
			}
			break;
			
			case 6:{
				$err = "<script type='text/javascript'>alert('找不到临时文件目录，上传失败！');</script>";
			}
			break;
			
			case 7:{
				$err = "<script type='text/javascript'>alert('文件写入失败，上传失败！');</script>";
			}
			break;
		}
		echo $err;
	}
	else {
		//源头像
		$user_ph_folder = dirname(__FILE__).'\data\userphotos\\';
		//130x130规格的头像
		$user_ph_folder_130x130 = dirname(__FILE__).'\data\userphotos\130x130\\';
		//100x100规格的头像
		$user_ph_folder_100x100 = dirname(__FILE__).'\data\userphotos\100x100\\';
		//60x80规格的头像
		$user_ph_folder_60x80 = dirname(__FILE__).'\data\userphotos\60x80\\';
		//50x50规格的头像
		$user_ph_folder_50x50 = dirname(__FILE__).'\data\userphotos\50x50\\';
		//48x48规格的头像
		$user_ph_folder_48x48 = dirname(__FILE__).'\data\userphotos\48x48\\';
		//40x40规格的头像
		$user_ph_folder_40x40 = dirname(__FILE__).'\data\userphotos\40x40\\';
		//创建源头像文件夹
		if (!is_dir($user_ph_folder)) {
			mkdir($user_ph_folder);
		}
		//创建130x130规格的头像的文件夹
		if(!is_dir($user_ph_folder_130x130)){
			mkdir($user_ph_folder_130x130);
		}
		//创建100x100规格的头像的文件夹
		if(!is_dir($user_ph_folder_100x100)){
			mkdir($user_ph_folder_100x100);
		}
		//创建60x80规格的头像的文件夹
		if(!is_dir($user_ph_folder_60x80)){
			mkdir($user_ph_folder_60x80);
		}
		//创建50x50规格的头像的文件夹
		if(!is_dir($user_ph_folder_50x50)){
			mkdir($user_ph_folder_50x50);
		}
		//创建50x50规格的头像的文件夹
		if(!is_dir($user_ph_folder_48x48)){
			mkdir($user_ph_folder_48x48);
		}
		//创建40x40规格的头像的文件夹
		if(!is_dir($user_ph_folder_40x40)){
			mkdir($user_ph_folder_40x40);
		}
		
		//读取出当前用户旧头像
		$select = "SELECT user_photo FROM users WHERE user_name='$now_user' LIMIT 1";
		$result = $common->selectSql($select);
		$row = mysql_fetch_assoc($result);
		$oldphoto = $row['user_photo']; //保存旧头像，用于更换头像后删除
		$_FILES['file']['name'] = ($_FILES['file']['name'] == 'default.jpg')? $_FILES['file']['name'].mktime().'.jpg' : $_FILES['file']['name'];
		$file_name = iconv('utf-8','gb2312',$_FILES['file']['name']); //在上传文件时，转化utf-8格式为gb2312，解决中文乱码问题
		move_uploaded_file($_FILES['file']['tmp_name'],$photo_path.$file_name); //移动图片到指定文件夹
		//生成缩略图
		$common->imgcutout($photo_path.$file_name,$photo_path.'130x130/'.$file_name,130,130);
		$common->imgcutout($photo_path.$file_name,$photo_path.'100x100/'.$file_name,100,100);
		$common->imgcutout($photo_path.$file_name,$photo_path.'60x80/'.$file_name,60,80);
		$common->imgcutout($photo_path.$file_name,$photo_path.'50x50/'.$file_name,50,50);
		$common->imgcutout($photo_path.$file_name,$photo_path.'48x48/'.$file_name,48,48);
		$common->imgcutout($photo_path.$file_name,$photo_path.'40x40/'.$file_name,40,40);
		//在写入数据库时，再转换回utf-8格式
		$file_name = iconv('gb2312','utf-8',$file_name);
		$photo = $photo_path.'130x130/'.$file_name;
		//更新数据库
		$update = "UPDATE users SET user_photo='$file_name' WHERE user_name='$now_user' LIMIT 1";
		
		if(!mysql_query($update,$con)){
			echo "<script type='text/javascript'>alert('更新数据出错，上传失败！');</script>";
		}
		else { //删除旧头像
			if ($oldphoto != 'default.jpg' && $oldphoto != $file_name) { //默认头像除外
				$deldir = dirname(__FILE__).'\data\userphotos\\'.iconv('utf-8','gb2312',$oldphoto);
				$deldir_130x130 = dirname(__FILE__).'\data\userphotos\130x130\\'.iconv('utf-8','gb2312',$oldphoto);
				$deldir_100x100 = dirname(__FILE__).'\data\userphotos\100x100\\'.iconv('utf-8','gb2312',$oldphoto);
				$deldir_60x80 = dirname(__FILE__).'\data\userphotos\60x80\\'.iconv('utf-8','gb2312',$oldphoto);
				$deldir_50x50 = dirname(__FILE__).'\data\userphotos\50x50\\'.iconv('utf-8','gb2312',$oldphoto);
				$deldir_48x48 = dirname(__FILE__).'\data\userphotos\48x48\\'.iconv('utf-8','gb2312',$oldphoto);
				$deldir_40x40 = dirname(__FILE__).'\data\userphotos\40x40\\'.iconv('utf-8','gb2312',$oldphoto);
				if(file_exists($deldir)){
					unlink($deldir);
				}
				if(file_exists($deldir_130x130)){
					unlink($deldir_130x130);
				}
				if(file_exists($deldir_100x100)){
					unlink($deldir_100x100);
				}
				if(file_exists($deldir_60x80)){
					unlink($deldir_60x80);
				}
				if(file_exists($deldir_50x50)){
					unlink($deldir_50x50);
				}
				if(file_exists($deldir_48x48)){
					unlink($deldir_48x48);
				}
				if(file_exists($deldir_40x40)){
					unlink($deldir_40x40);
				}
			}
		}
	}
	$smarty->assign('modify_photo',true);
}

//读取所有保存到服务器的附件
$select = "SELECT attachment_id,attachment_path,attachment_time FROM article_attachment WHERE user_name='$now_user' AND assoc_artid=0 ORDER BY attachment_time";
$filenames = array();
$filenames = $common->getfileinfo($select);

if (!empty($filenames)) {
	$smarty->assign('is_open_attachment',true);
	$smarty->assign('filenames',$filenames); //所有附件
}

$smarty->assign('user_infomation',$user_infomation);
$smarty->assign('photoPath',$photo);
$smarty->assign('isUser',$now_user);
$smarty->assign('perNavBk',true);

//个人资料
if(!empty($_GET['act']) && $_GET['act']=='accout'){
	$smarty->assign('accout',true);
}
//修改密码
if(!empty($_GET['act']) && $_GET['act']=='modify_password'){
	$smarty->assign('modify_password',true);
}
//修改头像
if(!empty($_GET['act']) && $_GET['act']=='modify_photo'){
	$smarty->assign('modify_photo',true);
}
//发表文章
if(!empty($_GET['act']) && $_GET['act']=='is_posttxt'){
	$smarty->assign('is_posttxt',true);
}

/**
 * 删除实体附件
 *
 * @param string $table
 * @param string $attachment_id
 */
function delattachment($common,$table,$attachment_id) {
	//读取附件路径
	$select = "SELECT attachment_path FROM $table WHERE attachment_id='$attachment_id' LIMIT 1";
	$result = $common->selectSql($select);
	$row = mysql_fetch_assoc($result);
	$file_name = $row['attachment_path'];
	$filearr = array();
	$filearr = explode('/',$file_name);
	//删除实体文件
	$file_phys_path = dirname(__FILE__).'\data\attachment\\'.$filearr[0].'\\'.$filearr[1];
	$file_phys_path = iconv('utf-8','gb2312',$file_phys_path);
	if(file_exists($file_phys_path)){
		unlink($file_phys_path);
	}
}

//管理文章
if((!empty($_GET['act']) && $_GET['act']=='artmanage') || (!empty($_POST['act']) && $_POST['act']=='artmanage')){
	function deledata($table,$delid,$idval,$one=false){ //根据主键id删除某条数据
		if($one){
			$delete = "DELETE FROM $table WHERE $delid='$idval' LIMIT 1";
		}
		else{
			$delete = "DELETE FROM $table WHERE $delid='$idval'";
		}
		
		if(!mysql_query($delete)){
			die(json_encode(array('status'=>0,'msg'=>'系统出错，请稍后再试！')));
		}
	}
	
	//删除文章部分
	if(!empty($_POST['artid'])){
		$topics_id = $_POST['artid']; //获取该文章主键topics_id
		//删除文章
		deledata('articles','topics_id',$topics_id,true);
		$select = "SELECT * FROM comments WHERE reply_id='$topics_id'";
		$result = $common->selectSql($select);
		
		while ($row = mysql_fetch_assoc($result)) { //删除回复
			deledata('replys','associd',$row['comment_id'],true);
		}
		
		//删除评论
		deledata('comments','reply_id',$topics_id);
		//删除评论者的实体附件
		$select = "SELECT attachment_id FROM comment_attachment WHERE assoc_artid='$topics_id'";
		$result = $common->selectSql($select);
		
		while ($row = mysql_fetch_assoc($result)) { //删除回复
			delattachment($common,'comment_attachment',$row['attachment_id']);
		}
		
		//删除数据库中评论者附件的信息
		deledata('comment_attachment','assoc_artid',$topics_id);
		//删除访问记录
		deledata('visiters','assocart_id',$topics_id);
		//删除收藏记录
		deledata('collects','assocart_id',$topics_id);
		
		//删除发表者的实体附件
		$select = "SELECT attachment_id FROM article_attachment WHERE assoc_artid='$topics_id'";
		$result = $common->selectSql($select);
		
		while ($row = mysql_fetch_assoc($result)) { //删除回复
			delattachment($common,'article_attachment',$row['attachment_id']);
		}
		
		//删除数据库中发表者附件的信息
		deledata('article_attachment','assoc_artid',$topics_id);
		echo json_encode(array('status'=>1,'msg'=>'文章删除成功！'));
		exit;
	}
		
	//显示文章列表
	$select = "SELECT * FROM articles WHERE user_name='$now_user' ORDER BY article_time DESC";
	$result = $common->selectSql($select);
	$articles = array();
	$i = 0;
	
	while ($row = mysql_fetch_assoc($result)) {//读取文章列表
		$articles[$i] = $row;
		$articles[$i]['comment_num'] = $common->getCommentNum('comment_id','reply_id','comments',$row['topics_id']);
		$articles[$i]['collect_num'] = $common->getCollectNum($row['topics_id']);
		$i++;
	}
	
	$smarty->assign('artlist',$articles);
	$smarty->assign('manageblock',true);
}

//收藏处理
if((!empty($_GET['act']) && $_GET['act']=='artcollect') || (!empty($_POST['act']) && $_POST['act']=='artcollect')){
	if(!empty($_POST['delid'])){ //删除收藏
		$tmpid = $_POST['delid'];
		$delete = "DELETE FROM collects WHERE collecter='$now_user' AND assocart_id='$tmpid' LIMIT 1";
		
		if(!mysql_query($delete,$con)){
			die(json_encode(array('status'=>0,'msg'=>'系统出错，请稍后再试！')));
		}

		echo json_encode(array('status'=>1,'msg'=>'删除收藏成功！'));
		exit;
	}
	
	//显示收藏
	$select = "SELECT assocart_id FROM collects WHERE collecter='$now_user'";
	$result = $common->selectSql($select); //根据用户名找出所有收藏的文章id
	$collects = array();
	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$tmpid = $row['assocart_id'];
		$select = "SELECT * FROM articles WHERE topics_id='$tmpid' LIMIT 1";
		$result_o = $common->selectSql($select); //根据提供的关联id找出文章
		$row = mysql_fetch_assoc($result_o);
		$collects[$i] = $row;
		$collects[$i]['comment_num'] = $common->getCommentNum('comment_id','reply_id','comments',$tmpid);
		$collects[$i]['collect_num'] = $common->getCollectNum($tmpid);
		$i++;
	}
	
	$smarty->assign('collects',$collects);
	$smarty->assign('collectblock',true);
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

$smarty->assign('is_percenter',true);
$smarty->display('percenter.tpl');

?>

