<?php
session_start();
require('./libs/Smarty.class.php');

if(!empty($_POST['host']) && !empty($_POST['db_user']) && !empty($_POST['db_name'])){
	$host = $_POST['host']; //主机地址
	$db_user = $_POST['db_user']; //数据库拥有者
	$db_password = $_POST['db_password']? $_POST['db_password'] : ''; //数据库密码
	$db_name = $_POST['db_name']; //要创建数据库的名称
	$tiparr = array(
		'status' => 1,
		'msg' => ''
	);
	/**连接数据库**/
	$con = @mysql_connect($host,$db_user,$db_password);
	if(!$con){
		$tiparr = array(
			'status' => 0,
			'msg' => '<li style="color:red;">数据库连接失败!</li>'
		);
		die(json_encode($tiparr));
	}
	/**创建数据库**/
	$database = "CREATE DATABASE if not exists $db_name";
	
	if(!mysql_query($database,$con)){
		$tiparr = array(
			'status' => 0,
			'msg' => '<li>创建数据库............<em>失败</em></li>'
		);
		die(json_encode($tiparr));
	}
	else{
		$tiparr['msg'] .= '<li>创建数据库............<i>成功</i></li>';
	}
	
	//保存数据库信息
	$_SESSION['host'] = $host;
	$_SESSION['db_user'] = $db_user;
	$_SESSION['db_password'] = $db_password;
	$_SESSION['db_name'] = $db_name;
	
	/**选择数据库**/
	mysql_select_db($db_name,$con);
	mysql_query("set names utf8");//设定字符集
	date_default_timezone_set(PRC);//更正8个小时时差
	
	/**创建数据库表**/ 
	$count = 0;
	
	//用户表 //为用户名添加唯一约束，不允许有重复值
	$table = "CREATE TABLE if not exists users (
		user_id mediumint(8) unsigned NOT NULL auto_increment, 
		user_email varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		user_name varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		sex varchar(10) character set utf8 collate utf8_unicode_ci NOT NULL DEFAULT '保密',
		user_password varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		QQ varchar(10) character set utf8 collate utf8_unicode_ci NULL DEFAULT NULL,
		phone varchar(11) character set utf8 collate utf8_unicode_ci NULL DEFAULT NULL,
		signature varchar(100) character set utf8 collate utf8_unicode_ci NULL DEFAULT NULL,
		user_photo varchar(200) character set utf8 collate utf8_unicode_ci NOT NULL default 'default.jpg',
		register_time datetime NOT NULL,
		login_time datetime NOT NULL,
		last_login_time datetime NULL,
		fans text character set utf8 collate utf8_unicode_ci NULL,
		attention text character set utf8 collate utf8_unicode_ci NULL,
		newfans MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
		msgnum MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
		PRIMARY KEY (user_id),UNIQUE KEY (user_name)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表users............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表users............<i>成功</i></li>';
		$count += 1;
	}
	
	//用户的留言表
	$table = "CREATE TABLE if not exists user_msgs (
		msg_id mediumint(8) unsigned NOT NULL auto_increment,
		user_name varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		msg_text text character set utf8 collate utf8_unicode_ci NOT NULL,
		msg_time datetime NOT NULL,
		user_id mediumint(8) unsigned NOT NULL default '0',
		commenter_os varchar(50) character set utf8 collate utf8_unicode_ci NULL,
		commenter_browser varchar(50) character set utf8 collate utf8_unicode_ci NULL,
		PRIMARY KEY (msg_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表user_msgs............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表user_msgs............<i>成功</i></li>';
		$count += 1;
	}
	
	//用户留言回复表
	$table = "CREATE TABLE if not exists msgs_replys (
		replyid mediumint(8) unsigned NOT NULL auto_increment,
		replyname varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		replytext text character set utf8 collate utf8_unicode_ci NOT NULL,
		replytime datetime NOT NULL,
		associd mediumint(8) unsigned NOT NULL default '0',
		commenter_os varchar(50) character set utf8 collate utf8_unicode_ci NULL,
		commenter_browser varchar(50) character set utf8 collate utf8_unicode_ci NULL,
		PRIMARY KEY (replyid)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表msgs_replys............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表msgs_replys............<i>成功</i></li>';
		$count += 1;
	}
	
	//用户的私信表
	$table = "CREATE TABLE if not exists user_sms (
		sms_id mediumint(8) unsigned NOT NULL auto_increment,
		sender varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		sms_text text character set utf8 collate utf8_unicode_ci NOT NULL,
		sms_time datetime NOT NULL,
		geter varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		unread tinyint(1) unsigned NOT NULL default '0',
		PRIMARY KEY (sms_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表user_sms............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表user_sms............<i>成功</i></li>';
		$count += 1;
	}
	
	//用户相册表
	$table = "CREATE TABLE if not exists user_album (
		album_id mediumint(8) unsigned NOT NULL auto_increment,
		album_name varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		album_description text character set utf8 collate utf8_unicode_ci NULL,
		album_photos text character set utf8 collate utf8_unicode_ci NULL,
		album_cover varchar(50) character set utf8 collate utf8_unicode_ci NULL,
		create_time datetime NOT NULL,
		assoc_userid mediumint(8) unsigned NOT NULL default '0',
		newalcomment mediumint(8) unsigned NOT NULL default '0',
		PRIMARY KEY (album_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表user_album............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表user_album............<i>成功</i></li>';
		$count += 1;
	}

	//相册评论表
	$table = "CREATE TABLE if not exists album_comment (
		alcomment_id mediumint(8) unsigned NOT NULL auto_increment,
		user_name varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		alcomment_text text character set utf8 collate utf8_unicode_ci NOT NULL,
		alcomment_time datetime NOT NULL,
		album_id mediumint(8) unsigned NOT NULL default '0',
		commenter_os varchar(50) character set utf8 collate utf8_unicode_ci NULL,
		commenter_browser varchar(50) character set utf8 collate utf8_unicode_ci NULL,
		PRIMARY KEY (alcomment_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表album_comment............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表album_comment............<i>成功</i></li>';
		$count += 1;
	}
	
	
	//相册评论回复表
	$table = "CREATE TABLE if not exists alcomment_replys (
		replyid mediumint(8) unsigned NOT NULL auto_increment,
		replyname varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		replytext text character set utf8 collate utf8_unicode_ci NOT NULL,
		replytime datetime NOT NULL,
		associd mediumint(8) unsigned NOT NULL default '0',
		commenter_os varchar(50) character set utf8 collate utf8_unicode_ci NULL,
		commenter_browser varchar(50) character set utf8 collate utf8_unicode_ci NULL,
		PRIMARY KEY (replyid)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表alcomment_replys............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表alcomment_replys............<i>成功</i></li>';
		$count += 1;
	}
	
	//访问空间用户表
	$table = "CREATE TABLE if not exists space_visiters (
		visiter_id mediumint(8) unsigned NOT NULL auto_increment,
		visiter_name varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		visiter_time datetime NOT NULL,
		assocart_id mediumint(8) unsigned NOT NULL default '0',
		PRIMARY KEY (visiter_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表space_visiters............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表space_visiters............<i>成功</i></li>';
		$count += 1;
	}
	
	//文章表
	$table = "CREATE TABLE if not exists articles (
	topics_id mediumint(8) unsigned NOT NULL auto_increment,
	user_name varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
	article_title varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL,
	article_content longtext character set utf8 collate utf8_unicode_ci NOT NULL,
	article_time datetime NOT NULL,
	article_type varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
	article_modify tinyint(1) unsigned NOT NULL default '0',
	visit_num mediumint(8) unsigned NOT NULL default '0',
	newartcomment mediumint(8) unsigned NOT NULL default '0',
	PRIMARY KEY (topics_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表articles创建............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表articles............<i>成功</i></li>';
		$count += 1;
	}
	
	//文章附件表
	$table = "CREATE TABLE if not exists article_attachment (
		attachment_id mediumint(8) unsigned NOT NULL auto_increment,
		user_name varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		attachment_path varchar(200) character set utf8 collate utf8_unicode_ci NOT NULL,
		attachment_time datetime NOT NULL,
		assoc_artid mediumint(8) unsigned NOT NULL default '0',
		downloads mediumint(8) unsigned NOT NULL default '0',
		attachment_flag varchar(20) character set utf8 collate utf8_unicode_ci NOT NULL default 'isarticle',
		PRIMARY KEY (attachment_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表article_attachment............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表article_attachment............<i>成功</i></li>';
		$count += 1;
	}
	
	
	//文章评论表
	$table = "CREATE TABLE if not exists comments (
		comment_id mediumint(8) unsigned NOT NULL auto_increment,
		user_name varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		comment_content text character set utf8 collate utf8_unicode_ci NOT NULL,
		comment_time datetime NOT NULL,
		reply_id mediumint(8) unsigned NOT NULL default '0',
		commenter_os varchar(50) character set utf8 collate utf8_unicode_ci NULL,
		commenter_browser varchar(50) character set utf8 collate utf8_unicode_ci NULL,
		PRIMARY KEY (comment_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表comments............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表comments............<i>成功</i></li>';
		$count += 1;
	}
	
	//文章评论附件表
	$table = "CREATE TABLE if not exists comment_attachment (
		attachment_id mediumint(8) unsigned NOT NULL auto_increment,
		user_name varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		attachment_path varchar(200) character set utf8 collate utf8_unicode_ci NOT NULL,
		attachment_time datetime NOT NULL,
		assoc_commentid mediumint(8) unsigned NOT NULL default '0',
		assoc_artid mediumint(8) unsigned NOT NULL default '0',
		downloads mediumint(8) unsigned NOT NULL default '0',
		attachment_flag varchar(20) character set utf8 collate utf8_unicode_ci NOT NULL default 'iscomment',
		PRIMARY KEY (attachment_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表comment_attachment............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表comment_attachment............<i>成功</i></li>';
		$count += 1;
	}
	
	
	//文章评论回复表
	$table = "CREATE TABLE if not exists replys (
		replyid mediumint(8) unsigned NOT NULL auto_increment,
		replyname varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		replytext text character set utf8 collate utf8_unicode_ci NOT NULL,
		replytime datetime NOT NULL,
		associd mediumint(8) unsigned NOT NULL default '0',
		receiver varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		is_newreply tinyint(1) unsigned NOT NULL default '0',
		commenter_os varchar(50) character set utf8 collate utf8_unicode_ci NULL,
		commenter_browser varchar(50) character set utf8 collate utf8_unicode_ci NULL,
		PRIMARY KEY (replyid)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表replys............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表replys............<i>成功</i></li>';
		$count += 1;
	}
	
	//访问文章用户表
	$table = "CREATE TABLE if not exists visiters (
		visiter_id mediumint(8) unsigned NOT NULL auto_increment,
		visiter_name varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		visiter_time datetime NOT NULL,
		assocart_id mediumint(8) unsigned NOT NULL default '0',
		PRIMARY KEY (visiter_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表visiters............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表visiters............<i>成功</i></li>';
		$count += 1;
	}
	
	
	//资源表
	$table = "CREATE TABLE if not exists resource_attachment (
		attachment_id mediumint(8) unsigned NOT NULL auto_increment,
		user_name varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		attachment_path varchar(200) character set utf8 collate utf8_unicode_ci NOT NULL,
		attachment_time datetime NOT NULL,
		downloads mediumint(8) unsigned NOT NULL default '0',
		attachment_flag varchar(20) character set utf8 collate utf8_unicode_ci NOT NULL default 'isresource',
		PRIMARY KEY (attachment_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表resource_attachment............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表resource_attachment............<i>成功</i></li>';
		$count += 1;
	}
	
	
	//用户收藏表
	$table = "CREATE TABLE if not exists collects (
		collect_id mediumint(8) unsigned NOT NULL auto_increment,
		collecter varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		collect_time datetime NOT NULL,
		assocart_id mediumint(8) unsigned NOT NULL default '0',
		PRIMARY KEY (collect_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表collects............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表collects............<i>成功</i></li>';
		$count += 1;
	}

	//网站外链表
	$table = "CREATE TABLE if not exists outlinks (
		link_id mediumint(8) unsigned NOT NULL auto_increment,
		link_title varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
		link_href varchar(150) character set utf8 collate utf8_unicode_ci NOT NULL,
		link_time datetime NOT NULL,
		PRIMARY KEY (link_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		
	if(!mysql_query($table,$con)){
		$tiparr['msg'] .= '<li>创建表outlinks............<em>失败</em></li>';
	}
	else{
		$tiparr['msg'] .= '<li>创建表outlinks............<i>成功</i></li>';
		$count += 1;
	}
	
	$tiparr['count'] = $count; //安装成功表个数
	echo json_encode($tiparr);
}
else{
	$smarty = new Smarty();
	$smarty->assign('is_install',true);
	$smarty->assign('isUser',$_SESSION['user_name']);
	$smarty->display('install.tpl');
}

?>