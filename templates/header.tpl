<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="web资料站、web前端及后端资料、html、css、nodejs、javascript、jQuery、php等前沿技术研究总结" />
<meta name="keywords" content="html、css、nodejs、javascript、jQuery、php" />
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
<title>{$title}|web资料站</title>
<link type="image/x-icon" href="images/favicon.ico" rel="shortcut icon" />
<link rel="stylesheet" href="css/compressor/common.min.css" type="text/css" />
<script src="js/compressor/pace.min.js"></script>
</head>
<body>
<div class="mainWrapper">
<!-- start: 网站横幅 -->
<!-- <p class="smbanner">
	迎接移动web时代的到来，本站点已从固定布局模式初步改造为响应式布局模式，支持各种主流终端，欢迎各种拍砖。
	{if !$isMobile}
	<span class="denglong denglong-left-1"></span>
	<span class="denglong denglong-left-2"></span>
	<span class="denglong denglong-right-1"></span>
	<span class="denglong denglong-right-2"></span>
	{/if}
</p> -->
<!-- end: 网站横幅 -->
<div class="mainHeader">
	<!--rss订阅-->
	<div class="feed">
		<!--logo-->
		<a href="/" class="correct">web资料站</a>
		<!--订阅-->
		<a href="feed.php" class="subscribe" target="_blank"><strong>订阅web资料站Feed</strong>www.58lou.com/feed.php</a>
	</div>
	{if $isUser}
	<div class="loginbar"><h1><a href="#" class="doexit">退出</a><em>欢迎您，{$isUser}</em></h1></div>
	{else}
	<div class="loginbar"><h1><a href="login.php" class="dologin">登录</a><a href="register.php" class="doregist">注册</a></h1></div>
	{/if}
	<!--导航-->
    <div class="topNav wb-clr">
    	<div class="search">
    		<form action="search.php" method="post">
    			<div class="search-wrapper wb-clr">
					<div class="search_btn modify_search range_btn">
						<i>{if $search_range}{$search_range}{else}文章内容{/if}</i>
						<div class="range_txt wb-hide">
							<span class="search_btn modify_search" title="文章名称">
								<input type="hidden" value="article_title" />文章名称
							</span>
							<span class="search_btn modify_search" title="文章内容">
								<input type="hidden" value="article_content" />文章内容
							</span>
							<span class="search_btn modify_search" title="文章类型">
								<input type="hidden" value="article_type" />文章类型
							</span>
							<span class="search_btn modify_search" title="文章作者">
								<input type="hidden" value="user_name" />文章作者
							</span>
						</div>
					</div>
					<input type="submit" value="搜索" class="search_btn to_search searchbar" />
					<input type="text" name="condition" placeholder="查询文章，请输入关键字。" value="{if $condition}{$condition}{/if}" class="search_type condition" />
					<input type="hidden" name="search_range" class="search_range" value="{$search_rang_value|default:'article_content'}" />
				</div>
			</form>
    	</div>
        <ul class="category wb-clr">
            <li><a href="/" {if $indexNavBk}class="inav_current"{/if}>首页</a></li>
            <li><a href="classart.php?act=html" {if $html_yes}class="inav_current"{/if}>HTML</a></li>
            <li><a href="classart.php?act=css" {if $css_yes}class="inav_current"{/if}>CSS</a></li>
            <li><a href="classart.php?act=js" {if $js_yes}class="inav_current"{/if}>JS</a></li>
            <li><a href="classart.php?act=jq" {if $jq_yes}class="inav_current"{/if}>JQ</a></li>
            <li><a href="classart.php?act=php" {if $php_yes}class="inav_current"{/if}>PHP</a></li>
            <li><a href="classart.php?act=nodejs" {if $nodejs_yes}class="inav_current"{/if}>NODEJS</a></li>
            <li><a href="classart.php?act=allarts" {if $allarts_yes}class="inav_current"{/if}>全部</a></li>
            <li><a href="resource.php" {if $resource}class="inav_current"{/if}>资源</a></li>
            <li><a href="news.php" {if $lastnews}class="inav_current"{/if}>新闻</a></li>
            {if $isUser}
            <li class="percenter"><a href="percenter.php?act=accout" {if $perNavBk}class="inav_current"{/if}>个人中心</a></li>
            <li class="perspace"><a href="perspace.php?user={$isUser}" target="_blank">我的空间</a></li>
            {/if}
        </ul>
    </div>
	{if $newartcomment || $newreply || $newsms || $newfans || $msgnum || $newalcomment[0].newalcomment>0}
		<div class="layer_message_box">
			<ul>
				{if $newartcomment}
					{section name=theartcomment loop=$newartcomment}
						<li>你的<a href="separticle.php?artid={$newartcomment[theartcomment].topics_id}">{$newartcomment[theartcomment].article_title}</a>文章有<a href="separticle.php?artid={$newartcomment[theartcomment].topics_id}&lookart=yes">({$newartcomment[theartcomment].newartcomment})</a>条新留言</li>
					{/section}
				{/if}
				{if $newreply}
				{section name=thereply loop=$newreply}
				<li><a href="separticle.php?artid={$newreply[thereply].artid}">{$newreply[thereply].arttitle}</a>文章中有<a href="separticle.php?artid={$newreply[thereply].artid}">({$newreply[thereply].number})</a>条回复未读。</li>
				{/section}
				{/if}
				{if $newsms}
					{section name=onesms loop=$newsms}
					<li>
						1条新私信，<a href="#" class="looksms">查看私信</a>
						<input type="hidden" name="sms_id" value="{$newsms[onesms].sms_id}" />
						<input type="hidden" name="sender" value="{$newsms[onesms].sender}" />
						<input type="hidden" name="sms_text" value="{$newsms[onesms].sms_text}" />
						<input type="hidden" name="sms_time" value="{$newsms[onesms].sms_time}" />
					</li>
					{/section}
				{/if}
				{if $newfans}
					<li>{$newfans}位新粉丝，<a href="fans.php?act=lookfans&user={$isUser}&newnum={$newfans[newone]}">查看粉丝</a></li>
				{/if}
				{if $msgnum}
					<li>你的空间有(<a href="guestbook.php?user={$isUser}">{$msgnum}</a>)条新留言</li>
				{/if}
				{if $newalcomment[0].newalcomment>0}
					{section name=onecomment loop=$newalcomment}
						{if $newalcomment[onecomment].newalcomment > 0}
						<li>你的<a href="album.php?user={$isUser}&act=look&id={$newalcomment[onecomment].album_id}">{$newalcomment[onecomment].album_name|truncate:10:"..":true}</a>相册有(<a href="album.php?user={$isUser}&act=look&id={$newalcomment[onecomment].album_id}">{$newalcomment[onecomment].newalcomment}</a>)条新评论</li>
						{/if}
					{/section}
				{/if}
			</ul>
			<a href="#" class="W_close_color"></a>
		</div>
	{/if}
</div>
<!--页面内容区start-->
<div class="mainContent">
{if !$is_login && !$is_register && !$is_percenter && !$is_updart && !$is_retrpwd && !$is_install && !$wrsuccess && !$upsuccess}
<!--热门文章 start-->
<div class="hotArts">
	<h2>热门文章</h2>
	<ul>
		{section name=theArt loop=$hotArts}
		<li><a href="separticle.php?artid={$hotArts[theArt].topics_id}" target="_blank">{$hotArts[theArt].article_title}</a></li>
		{/section}
	</ul>
	<a href="#" title="关闭热门文章" class="W_close_color"></a>
</div>
<!--热门文章 end-->
<!--随机文章 start-->
<div class="hotArts randomArts">
	<h2>随机文章</h2>
	<ul>
		{section name=theArt loop=$randomArts}
		<li><a href="separticle.php?artid={$randomArts[theArt].topics_id}">{$randomArts[theArt].article_title}</a></li>
		{/section}
	</ul>
	<a href="#" title="关闭随机文章" class="W_close_color"></a>
</div>
<!--随机文章 end-->
{/if}