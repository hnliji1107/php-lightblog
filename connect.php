<?php
@session_start();

//301永久重定向
if($_SERVER['HTTP_HOST'] == 'lishijun.w28.mc-test.com'){
	@header('HTTP/1.1 301 Moved Permanently');
	@header('Location:'.'http://www.58lou.com'.$_SERVER['REQUEST_URI']);
}

//设置如下响应头，才能支持缓存。
@header('Pragma: cache');
@header('Cache-Control: max-age=86400');
@header('Expires: '.gmdate('r', time()+86400));

//防止F5刷新浏览器请求而不走缓存。
//php会无视请求header中的If-Modified-Since，而是每次返回200，故模拟之。
//首先服务器发送Last-Modified头信息，客户端才能根据它的值发送If-Modified-Since值。
// @header('Last-Modified: ' .gmdate('r', time()));
// if (php_sapi_name() == 'apache2handler' || php_sapi_name() == 'apache') {
//     $headers = apache_request_headers();
//     if (isset($headers['If-Modified-Since']) && !empty($headers['If-Modified-Since'])) {
//         header('HTTP/1.1 304 Not Modified');
//         exit;
//     }
// }

//设置数据库信息
$host = '';
$db_user = '';
$db_password = '';
$db_name = '';

/**连接数据库**/
$con = mysql_connect($host,$db_user,$db_password);
if(!$con){
	die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
}

/**选择数据库**/
mysql_select_db($db_name,$con);
mysql_query('set names utf8');//设定字符集
date_default_timezone_set(PRC);//更正8个小时时差

?>