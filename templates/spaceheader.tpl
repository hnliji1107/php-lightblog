<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="web资料站、web前端及后端资料、html、css、nodejs、javascript、jQuery、php等前沿技术研究总结" />
<meta name="keywords" content="html、css、nodejs、javascript、jQuery、php" />
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
<title>{$someUser.user_name}的空间|web资料站</title>
<link rel="stylesheet" href="css/compressor/common.min.css" type="text/css" />
<link type="image/x-icon" href="images/favicon.ico" rel="shortcut icon" />
<script src="js/compressor/pace.min.js"></script>
</head>
<body>
<div class="spaceWrapper">
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
					<li>{$newfans}位新粉丝，<a href="fans.php?act=lookfans&user={$now_user}&newnum={$newfans[newone]}">查看粉丝</a></li>
				{/if}
				{if $msgnum}
					<li>你的空间有(<a href="guestbook.php?user={$now_user}">{$msgnum}</a>)条新留言</li>
				{/if}
				{if $newalcomment[0].newalcomment>0}
					{section name=onecomment loop=$newalcomment}
						{if $newalcomment[onecomment].newalcomment > 0}
						<li>你的<a href="album.php?user={$now_user}&act=look&id={$newalcomment[onecomment].album_id}">{$newalcomment[onecomment].album_name|truncate:10:"..":true}</a>相册有(<a href="album.php?user={$now_user}&act=look&id={$newalcomment[onecomment].album_id}">{$newalcomment[onecomment].newalcomment}</a>)条新评论</li>
						{/if}
					{/section}
				{/if}
			</ul>
			<a href="#" class="W_close_color"></a>
		</div>
	{/if}
	<div class="spaceMain">
		<!--头部start-->
		<div class="spaceHeader">
			<div class="logo"><h1><a href="perspace.php?user={$someUser.user_name}">{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}的空间</a></h1></div>
			<div class="menu wb-clr">
				<div class="oprator">
					{if $now_user}
						<a target="_blank" class="utype_2" href="perspace.php?user={$now_user}"><strong>{$now_user}</strong></a>
						<a target="_blank" href="percenter.php?act=accout">个人中心</a>
						<a href="#" class="exitspace">退出</a>
					{else}
						<a href="register.php">注册</a>
						<a href="login.php">登陆</a>
					{/if}
				</div>
				<div class="links">
					<a href="perspace.php?user={$someUser.user_name}" {if $spacehome}class="snav_current"{/if}>主页</a>┊
					<a href="perspace.php?user={$someUser.user_name}&dynamic=yes" {if $dynamic}class="snav_current"{/if}>动态</a>┊
					<a href="perspace.php?user={$someUser.user_name}&morearts=yes" {if $morearts}class="snav_current"{/if}>文章</a>┊
					<a href="album.php?user={$someUser.user_name}" {if $albumPage}class="snav_current"{/if}>相册</a>┊
					<a href="guestbook.php?user={$someUser.user_name}" {if $gbpage}class="snav_current"{/if}>留言板</a>┊
					{if $someUser.user_name === $now_user}
					<a href="fans.php?act=lookfans&user={$someUser.user_name}">查看粉丝</a>┊
					{/if}
					<a target="_blank" href="/">网站首页</a>
				</div>
			</div>
		</div>
		<!--头部end-->
		<!--主要部分start-->
		<div class="spaceContent wb-clr">
			<!--左侧start-->
			<div class="sidebar">
				<!--个人资料start-->
				<div class="sidebar_box">
					<h2>个人资料</h2>
					<div class="spacephoto"><img data-lazyload-src="{$someUser.user_photo}" src="images/lazyload.png" alt="{$someUser.user_name}" /></div>
					<div class="spacerinfo">
						<div class="uname">
							<span class="title">用户名：</span>
							<span class="content"><a href="">{$someUser.user_name}</a></span>
						</div>
						<div class="usex">
							<span class="title">性别：</span>
							<span class="content">{$someUser.sex}</span>
						</div>
						<div class="utime">
						{if $firstLogin !== $now_user}
							<span class="title">上次：</span>
							{if $unlogin}
								{if $re_login_time}
								<span class="content">{$re_login_time}</span>
								{/if}
							{else}
								{if $last_login_time}
								<span class="content">{$last_login_time}</span>
								{/if}
							{/if}
						{else}
							{if $re_login_time}
							<span class="content">{$re_login_time}</span>
							{/if}
						{/if}
						</div>
						{if $someUser.user_name !== $now_user}
						<div class="uoprator">
							<input type="hidden" name="ta-name" value="{$someUser.user_name}" />
							<a class="sendmessage" href="#">发私信</a>
							<a class="perspace_addfriend" href="#">加关注</a>
						</div>
						{/if}
					</div>
					<div class="signature">签名：{$someUser.signature|default:'暂无'}</div>
				</div>
				<!--个人资料end-->
				<!--最新访客start-->
				<div class="sidebar_box">
					<h2>最新访客</h2>
					<div class="space_visiters wb-clr">
						{if $visiters}
						{section name=visiter loop=$visiters}
						<div class="someVisiter usertip-wrapper">
							<input type="hidden" value="{$visiters[visiter].visiter_name}" class="ta-name" />
							<div class="vsphoto"><a href="perspace.php?user={$visiters[visiter].visiter_name}"><img alt="{$visiters[visiter].visiter_name}" data-lazyload-src="{$visiters[visiter].visiter_photo}" src="images/lazyload.png" /></a></div>
							<div class="vsname">{$visiters[visiter].visiter_name}</div>
						</div>
						{/section}	
						{else}
							<p class="empty-content">暂无人访问</p>
						{/if}
					</div>
				</div>
				<!--最新访客end-->
				<!--粉丝 start-->
				<div class="sidebar_box">
					<h2>粉丝</h2>
					<div class="space_visiters wb-clr">
						{if $fansArr}
						{section name=fans loop=$fansArr}
						<div class="someVisiter usertip-wrapper">
							<input type="hidden" value="{$fansArr[fans].name}" class="ta-name" />
							<div class="vsphoto"><a href="perspace.php?user={$fansArr[fans].name}"><img alt="{$fansArr[fans].name}" data-lazyload-src="{$fansArr[fans].photo}" src="images/lazyload.png" /></a></div>
							<div class="vsname">{$fansArr[fans].name}</div>
						</div>
						{/section}	
						{else}
							<p class="empty-content">暂无粉丝</p>
						{/if}
					</div>
				</div>
				<!--粉丝 end-->
				<!--关注 start-->
				<div class="sidebar_box">
					<h2>关注</h2>
					<div class="space_visiters wb-clr">
						{if $attenArr}
						{section name=atten loop=$attenArr}
						<div class="someVisiter usertip-wrapper">
							<input type="hidden" value="{$attenArr[atten].name}" class="ta-name" />
							<div class="vsphoto"><a href="perspace.php?user={$attenArr[atten].name}"><img alt="{$attenArr[atten].name}" data-lazyload-src="{$attenArr[atten].photo}" src="images/lazyload.png" /></a></div>
							<div class="vsname">{$attenArr[atten].name}</div>
						</div>
						{/section}	
						{else}
							<p class="empty-content">暂无关注</p>
						{/if}
					</div>
				</div>
				<!--关注 end-->
			</div>
			<!--左侧end-->
