<?php
require('./connect.php');
require('./libs/Smarty.class.php');
require('./class.common.php');

$smarty = new Smarty();
$common = new Common();
$now_user = $_SESSION['user_name'];//当前用户
$_SESSION['loginUrl'] = $common->getPageUrl(); //保存登录前页面url

//保存附件
if (!empty($now_user) && !empty($_POST['upload_attachment']) && $_POST['upload_attachment'] == 'yes' && !empty($_POST['MAX_FILE_SIZE']) && $_POST['MAX_FILE_SIZE'] == 8388608 && $_FILES['file']['size'] <= 8388608){
	if ($_FILES['file']['error'] > 0){
	}
	else {
		$date = getdate(time());
		$year = $date['year'] > 9? $date['year'] : '0'.$date['year'];
		$mon = $date['mon'] > 9? $date['mon'] : '0'.$date['mon'];
		$mday = $date['mday'] > 9? $date['mday'] : '0'.$date['mday'];
		$hours = $date['hours'] > 9? $date['hours'] : '0'.$date['hours'];
		$minutes = $date['minutes'] > 9? $date['minutes'] : '0'.$date['minutes'];
		$seconds = $date['seconds'] > 9? $date['seconds'] : '0'.$date['seconds'];
		$file_folder = $year.$mon.$mday;
		//创建附件文件夹
		$attachment_folder = dirname(__FILE__).'\data\attachment\\';
		if (!is_dir($attachment_folder)) {
			mkdir($attachment_folder);
		}
		//创建当天文件夹
		$date_folder = $attachment_folder.$file_folder.'\\';
		if (!is_dir($date_folder)) {
			mkdir($date_folder);
		}
		//处理文件名为中文问题
		$file_name = iconv('utf-8','gb2312',$_FILES['file']['name']);
		$hms = $hours.$minutes.$seconds.'-';
		$file_name = $hms.$file_name;
		move_uploaded_file($_FILES['file']['tmp_name'],$date_folder.$file_name);//移动文件到指定文件夹
		$file_name = iconv('gb2312','utf-8',$file_name);
		//插入数据前，判断数据库中是否已存在
		$tmppath = $file_folder.'/'.$file_name; //文件名
		$tmpartid = $_POST['assoc_artid']; //文章id
		$select = "SELECT attachment_id FROM resource_attachment WHERE user_name='$now_user' AND attachment_path='$tmppath'";
		$result = $common->selectSql($select);
		if (mysql_num_rows($result) == 0) { //如果不存在，则插入数据
			$tmptime = date('Y-m-d H:i:s');
			$insert = "INSERT INTO resource_attachment (user_name,attachment_path,attachment_time) VALUES ('$now_user','$tmppath','$tmptime')";
			if(!mysql_query($insert,$con)){
				echo json_encode(array('status'=>0,'msg'=>'Access Error.'));
			}
		}
	}
	$smarty->assign('is_open_attachment',true); //控制上传附件后的显示
}

//显示资源
/*
$select = "SELECT attachment_id,user_name,attachment_path,attachment_time,downloads,attachment_flag FROM comment_attachment WHERE 1=1 UNION ALL 
			SELECT attachment_id,user_name,attachment_path,attachment_time,downloads,attachment_flag FROM article_attachment WHERE 1=1 UNION ALL
			SELECT attachment_id,user_name,attachment_path,attachment_time,downloads,attachment_flag FROM resource_attachment WHERE 1=1 ORDER BY attachment_time DESC";
*/
$select = "SELECT attachment_id,user_name,attachment_path,attachment_time,downloads,attachment_flag FROM resource_attachment WHERE 1=1 ORDER BY attachment_time DESC";
$all_resource = $common->getfileinfo($select);

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
$smarty->assign('resource',true);
$smarty->assign('all_resource',$all_resource); //输出所有资源
$smarty->assign('hotArts',$common->readHotArts()); //热门文章
$smarty->assign('randomArts',$common->readRandArts()); //随机文章
$smarty->display('resource.tpl');

?>