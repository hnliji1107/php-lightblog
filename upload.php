<?php
require('./globalheader.php');

//保存评论的附件
if (!empty($now_user) && !empty($_POST['upload_attachment']) && $_POST['upload_attachment'] == 'yes' && !empty($_POST['assoc_artid']) && !empty($_POST['MAX_FILE_SIZE']) && $_POST['MAX_FILE_SIZE'] == 8388608 && $_FILES['file']['size'] <= 8388608){
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
		$select = "SELECT attachment_id FROM comment_attachment WHERE user_name='$now_user' AND attachment_path='$tmppath' AND 
		assoc_artid='$tmpartid' AND assoc_commentid=0";
		$result = $common->selectSql($select);

		if (mysql_num_rows($result) == 0) { //如果不存在，则插入数据
			$tmptime = date('Y-m-d H:i:s');
			$insert = "INSERT INTO comment_attachment (user_name,attachment_path,attachment_time,assoc_artid) VALUES ('$now_user','$tmppath','$tmptime','$tmpartid')";

			if(!mysql_query($insert,$con)){
				echo json_encode(array('status'=>0,'访问出错'));
			}
			
			$backid = mysql_insert_id();
			$select = "SELECT attachment_path,attachment_time FROM comment_attachment WHERE user_name='$now_user' AND attachment_id='$backid'";
			$fileinfo = $common->getfileinfo($select);
			$str = "<li><em style=\"background:url(images/file_logo/{$fileinfo[0]['filetype']}_logo.png) no-repeat;\"></em><i class=\"fname\" title=\"{$fileinfo[0]['filename']}\">{$fileinfo[0]['filename']}</i> / <i class=\"fsize\">{$fileinfo[0]['filesize']}</i> / <i class=\"ftime\">{$tmptime}上传</i><a href=\"#\" title=\"删除附件\" class=\"W_close_color\"></a><input type=\"hidden\" value=\"{$backid}\" /></li>";
			echo "<script type='text/javascript'>window.parent.addtip('".$str."');</script>";
		}
	}
}

//保存文章发表或者修改的附件
if (!empty($now_user) && !empty($_POST['art_upload_attachment']) && $_POST['art_upload_attachment'] == 'yes' && !empty($_POST['MAX_FILE_SIZE']) && $_POST['MAX_FILE_SIZE'] == 8388608 && $_FILES['attachment']['size'] <= 8388608){
	if ($_FILES['attachment']['error'] > 0){
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
		$file_name = iconv('utf-8','gb2312',$_FILES['attachment']['name']);
		$hms = $hours.$minutes.$seconds.'-';
		$file_name = $hms.$file_name;
		move_uploaded_file($_FILES['attachment']['tmp_name'],$date_folder.$file_name);//移动文件到指定文件夹
		$file_name = iconv('gb2312','utf-8',$file_name);
		//插入数据前，判断数据库中是否已存在
		$tmppath = $file_folder.'/'.$file_name; //文件名
		$tmpartid = $_POST['assoc_artid']; //文章id
		$select = "SELECT attachment_id FROM article_attachment WHERE user_name='$now_user' AND attachment_path='$tmppath' AND assoc_artid=0";
		$result = $common->selectSql($select);
		
		if (mysql_num_rows($result) == 0) { //如果不存在，则插入数据
			$tmptime = date('Y-m-d H:i:s');
			$insert = "INSERT INTO article_attachment (user_name,attachment_path,attachment_time) VALUES ('$now_user','$tmppath','$tmptime')";
			
			if(!mysql_query($insert,$con)){
				echo json_encode(array('status'=>0,'Access Error.'));
			}
			
			$backid = mysql_insert_id();
			$select = "SELECT attachment_path,attachment_time FROM article_attachment WHERE user_name='$now_user' AND attachment_id='$backid'";
			$fileinfo = $common->getfileinfo($select);
			$str = "<li><em style=\"background:url(images/file_logo/{$fileinfo[0]['filetype']}_logo.png) no-repeat;\"></em><i class=\"fname\" title=\"{$fileinfo[0]['filename']}\">{$fileinfo[0]['filename']}</i> / <i class=\"fsize\">{$fileinfo[0]['filesize']}</i> / <i class=\"ftime\">{$tmptime}上传</i><a href=\"#\" title=\"删除附件\" class=\"W_close_color\"></a><input type=\"hidden\" value=\"{$backid}\" /></li>";
			echo "<script type='text/javascript'>window.parent.addtip('".$str."');</script>";
		}
	}
}

?>