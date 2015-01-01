<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="web资料站、web前端及后端资料、html、css、nodejs、javascript、jQuery、php等前沿技术研究总结" />
<meta name="keywords" content="html、css、nodejs、javascript、jQuery、php" />
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
<link rel="stylesheet" href="css/compressor/common.min.css" type="text/css" />
<link type="image/x-icon" href="images/favicon.ico" rel="shortcut icon" />
<title>关注我的人|web资料站</title>
</head>
<body class="fansbody">
<div class="fansWrapper">
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
	<div class="fansMain">
		<!--头部start-->
		<div class="fansHeader">
			<div class="logo"><h1><a href="fans.php?act=lookfans&user={$now_user}&newnum={$newfans[newone]}">我的粉丝</a></h1></div>
			<div class="menu wb-clr">
				<div class="oprator wb-right">
					<div class="logined">
						{if $now_user}
							<a target="_blank" class="utype_2" href="perspace.php?user={$now_user}"><strong>{$now_user}</strong></a>
							<a target="_blank" href="percenter.php?act=accout">个人中心</a>
							<a href="#" class="fansexit">退出</a>
						{else}
							<a href="register.php">注册</a>
							<a href="login.php">登陆</a>
						{/if}
					</div>
				</div>
				<div class="links wb-left">
					<a href="perspace.php?user={$now_user}">主页</a>┊
					<a href="perspace.php?user={$now_user}&dynamic=yes">动态</a>┊
					<a href="perspace.php?user={$now_user}&morearts=yes">文章</a>┊
					<a href="album.php?user={$now_user}">相册</a>┊
					<a href="guestbook.php?user={$now_user}">留言板</a>┊
					{if $now_user}
					<a href="fans.php?act=lookfans&user={$now_user}" {if $fanspage}class="snav_current"{/if}>查看粉丝</a>┊
					{/if}
					<a target="_blank" href="/">网站首页</a>
				</div>
			</div>
		</div>
		<!--头部end-->
		<!--主要部分start-->
		<div class="fansContent wb-clr">
			<div class="fans_left">
				<ul>
					<li class="title"><a href="#">关注/粉丝</a></li>
					<li><a href="fans.php?act=attention&user={$now_user}" {if $attention}class="selected"{/if}>关注(<i>{$attenCount}</i>)</a></li>
					<li><a href="fans.php?act=lookfans&user={$now_user}" {if $lookfans}class="selected"{/if}>粉丝(<i>{$fansCount}</i>)</a></li>
					<li><a href="fans.php?act=invite&user={$now_user}" {if $invite}class="selected"{/if}>邀请站外好友</a></li>
					<li><a href="fans.php?act=find&user={$now_user}" {if $find}class="selected"{/if}>找人</a></li>
				</ul>
			</div>
			<div class="fans_middle">
				<div class="searching wb-clr">
					<div class="lf">
						{if $lookfans}
							{if $fansCount>0}
							已有<i>{$fansCount}</i>人关注
							{else}
							暂无人关注你哦!
							{/if}
						{else if $attention}
							{if $attenCount>0}
								我关注了<i>{$attenCount}</i>人
							{else}
								你还没有关注的人哦!
							{/if}
						{else if $invite}
							<strong>邀请好友关注我</strong>
							<p>选择常用的邀请方式，邀请好友加入。收到邀请的人注册后，就会自动关注你哦。</p>
						{else}
							<strong>找人</strong>
							<p>你只需敲敲键盘，就能找到你想找的人</p>
						{/if}
					</div>
					{if $lookfans || $attention}
	                    <div class="rt">
	                    	<input type="text" value="" maxlength="20" placeholder="输入昵称" class="sminput_type" />
	                        <a href="#" class="search_btn search_fans">搜索</a>
	                    </div>
                    {/if}
				</div>
				{if $fans}
				<div class="fans_list">
					<ul>
						{section name=one loop=$fans}
						<li class="wb-clr" {if $smarty.section.one.last}style="border-bottom:none;"{/if}>
							<div class="fans_photo usertip-wrapper">
								<input type="hidden" value="{$fans[one].name}" class="ta-name" />
								<a href="perspace.php?user={$fans[one].name}"><img data-lazyload-src="{$fans[one].photo}" src="images/lazyload.png" alt="{$fans[one].name}" /></a>
							</div>
							<div class="fans_info">
								<h4>{$fans[one].name}</h4>
								<span>{$fans[one].sex}&nbsp;&nbsp;粉丝{$fans[one].fanscount}人&nbsp;&nbsp;关注{$fans[one].attencount}人</span>
								{if $fans[one].artcontent}
									<div class="arttxt"><a href="separticle.php?artid={$fans[one].artid}">{$fans[one].artcontent|truncate:100:'..':true}</a></div>
								{/if}
							</div>
							<div class="fans_ico wb-hide">
								<p><a href="#" class="remove_fans">{if $lookfans}移除粉丝{/if}{if $attention}取消关注{/if}</a></p>
								<p>
									<input type="hidden" name="ta-name" value="{$fans[one].name}" />
									<a class="sendmessage" href="#">发私信</a>
								</p>
							</div>
						</li>
						{/section}
					</ul>
				</div>
				{else if $find}
					<div class="fdblock">
						<h3>按昵称查找</h3>
						<div class="fdcondition">
							<p>这里输入朋友的姓名或昵称，如：小新</p>
							<p class="findperson">
								<input type="text" class="sminput_type" value="" placeholder="输入昵称" style="width:40%;" />
								<a href="#" class="search_btn">查找</a>
							</p>
						</div>
					</div>
				{else if $invite}
					<div class="fdblock">
						<h3>邀请链接</h3>
						<div class="fdcondition">
							<p>通过QQ、MSN、电子邮件发送邀请链接给你的朋友注册成功后他们会自动成为你的粉丝。</p>
							<p>
								<input type="text" readonly="readonly" value="{$httphost}/register.php?inviter={$now_user}" class="sminput_type" style="width:100%;" onclick="this.select()" />
							</p>
						</div>
					</div>
				{else}
				<p class="empty-content">暂无信息哦!</p>
				{/if}
			</div>
		</div>
		<!--主要部分end-->
	</div>
	<!--单独模块·发私信start-->
	<div id="sendsms">
		<h2>发私信</h2>
		<div class="smsdetail">
			<table>
				<tr><td>发给：</td><td><input type="text" class="sminput_type posttitle" value="" /></td></tr>
				<tr><td height="5"></td></tr>
				<tr><td>内容：</td><td><textarea class="sminput_type postcontent"></textarea></td></tr>
				<tr><td height="5"></td></tr>
				<tr><td></td><td><button class="persubmit_btn sending">发送</button><span class="tip wb-hide">正在提交，请稍后...</span></td></tr>
			</table>
		</div>
		<a href="#" class="W_close_color"></a>
	</div>
	<!--单独模块·发私信end-->
	<!--单独模块·查看私信start-->
	<div id="lksms">
		<h2></h2>
		<div class="talks">
			<table>
				<tr><td><textarea readonly class="sminput_type receive talkdetail"></textarea></td></tr>
				<tr><td height="5"></td></tr>
				<tr><td><textarea class="sminput_type postcontent senddetail"></textarea></td></tr>
				<tr><td height="5"></td></tr>
				<tr><td><button class="persubmit_btn lksending">发送</button><span class="tip wb-hide">正在提交，请稍后...</span></td></tr>
			</table>
		</div>
		<a href="#" class="W_close_color"></a>
	</div>
	<!--单独模块·查看私信end-->
	<div class="footer fansFooter">
		<p>
			<span>收集、整合web开发中前端、后端技术资料<br />©版权所有，保留一切权利</span>
			<script type="text/javascript">
			var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
			document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F19070425cdfe4a00d06ba4ba44f80aa0' type='text/javascript'%3E%3C/script%3E"));
			</script>
		</p>
	</div>
<input type="hidden" value="{$now_user}" class="isUser" />
<input type="hidden" value="{if $lookfans}removeFans{/if}{if $invite}invite{/if}{if $attention}removeAtten{/if}" class="remove_act" />
<input type="hidden" value="{if $lookfans}fans{/if}{if $attention}attention{/if}" class="find_act" />
</div>
{if !$isMobile}
<a class="W_gotop wb-hide" href="#"><span><em class="sj">♦</em><em class="fk">▐</em>返回顶部</span></a>
{/if}
<!--[if lte IE 6]>
<script type="text/javascript" src="js/compressor/minmax.min.js"></script>
<![endif]-->
<script type="text/javascript" src="js/compressor/asynLoadJs.min.js"></script>
</body>
</html>