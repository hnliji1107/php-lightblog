<?php
require('./connect.php');

function updownload($tmptable,$tmpid) {
	$update = "UPDATE $tmptable SET downloads=downloads+1 WHERE attachment_id='$tmpid' LIMIT 1";
	if (!mysql_query($update)) {
		echo json_encode(array('status'=>0,'msg'=>'Access Error.'));
	}
}

set_time_limit(0); //防止下载超时

//附件下载
if (!empty($_GET['attachment_id']) && !empty($_GET['loadfile_path'])) {
	$tmpid = $_GET['attachment_id']; //附件id
	$tmppath = $_GET['loadfile_path']; //下载路径
	$tmppath = iconv('utf-8','gb2312',$tmppath); //处理中文文件名
	
	//下载文件大小
	$namearr = explode('/',$tmppath);
	$namearr = array_reverse($namearr);
	$physicalpath = dirname(__FILE__).'\data\attachment\\'.$namearr[1].'\\'.$namearr[0]; //文件物理位置
	$filesize = filesize($physicalpath);
	
	//下载文件类型
	$pattern = "/.+\.([a-zA-Z]+)$/";
	preg_match_all($pattern,basename($tmppath),$matches);
	
	header("Content-Type: application/force-download/".$matches[1][0]); //强制弹出保存对话框
	header("Pragma: no-cache"); // 缓存
	header("Expires: 0");
	header("Content-Transfer-Encoding: binary"); //发送数据方式
	Header("Content-Length: ".$filesize); //文件大小
	header('Content-Disposition: attachment; filename="'.basename($tmppath).'"'); //文件名
	
	echo file_get_contents($tmppath); //下载
	
	//更新下载次数
	
	//评论附件
	if (!empty($_GET['attachment_flag']) && $_GET['attachment_flag'] == 'iscomment') {
		updownload('comment_attachment',$tmpid);
	}
	
	//文章附件
	if (!empty($_GET['attachment_flag']) && $_GET['attachment_flag'] == 'isarticle') {
		updownload('article_attachment',$tmpid);
	}
	
	//资源
	if (!empty($_GET['attachment_flag']) && $_GET['attachment_flag'] == 'isresource') {
		updownload('resource_attachment',$tmpid);
	}
	
	mysql_close($con);
}
	
?>