<?php
/**
 * 读取rss
 *
 * @param string $rssurl ---rss源地址
 * @param string $scode ---rss源字符集,默认为 UTF-8
 * @param string $dcode ---网站字符集,默认为 UTF-8
 */

function getrss($rssurl) { 
	$xml = @file_get_contents($rssurl);

	//读取字符集
	$pattern = "/encoding=\"([a-zA-Z0-9\-]+)\"/";
	preg_match_all($pattern,$xml,$matches);
	$scode = $matches[1][0]; //rss源字符集
	$scode = strtoupper($scode);
	$dcode = 'UTF-8';
	$pattern = "/(\<\!\[CDATA\[)?(\]\]\>)?/"; //去除特殊字符
	$xml = preg_replace($pattern,'',$xml);
	preg_match_all( "/\<item(?:\s+id=\"\d+\")?\>(.*?)\<\/item\>/s", $xml, $items ); //匹配最外层标签里面的内容
	$newstr = '';
	$i = 0;
	$lastArtCls = '';
	$arts = $items[1];
	$artsCount = count($arts);
	
	foreach( $arts as $item ){
		$i++;
		if ($i == $artsCount) $lastArtCls = ' no-bottom-border';

		preg_match_all( "/\<title\>(.*?)\<\/title\>/", $item, $title ); //匹配出文章名
		preg_match_all( "/\<link\>(.*?)\<\/link\>/", $item, $link ); //匹配出文章链接
		preg_match_all( "/\<description\>(.*?)\<\/description\>/", $item, $description ); //匹配出文章描述
		preg_match_all( "/\<pubDate\>(.*?)\<\/pubDate\>/", $item, $pubDate ); //匹配出文章更新时间
		
		$title = iconv($scode,$dcode,$title[1][0]);
		$link = $link[1][0];
		$description = preg_replace("/[a-zA-Z]+/",'',$description[1][0]); //处理特殊字符
		$description = empty($description)? '该新闻无描述信息' : iconv($scode,$dcode,$description);
		$pubDate = $pubDate[1][0];
		$newstr .= "<div class=\"onenew{$lastArtCls}\">
						<h3><a href=\"{$link}\" target=\"_blank\">$title</a></h3>
						<p>{$description} <a href=\"{$link}\" target=\"_blank\">--- 详细</a></p>
						<p class=\"newtime\">--- {$pubDate}</p>
					</div>";
	}
	return $newstr;
}

//点击新闻小分类
if (!empty($_POST['rssurl']) && !empty($_POST['xl']) && $_POST['xl'] == true) {
	$rssurl = $_POST['rssurl']; //rss源
	$news = getrss($rssurl);
	echo json_encode(array('status'=>1,'msg'=>$news));
}

?>