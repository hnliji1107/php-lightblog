	</div>
	<!--页面内容区end-->
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
	<!-- 添加网站外链编辑框start -->
	<div id="editOutLinksLayer">
		<div class="header">
			<h2>添加网站外链</h2>
		</div>
		<div class="content">
			<div class="title">
				<lable>外链名称：<input type="text" name="linktitle" class="input_type" /></lable>
			</div>
			<div class="href">
				<lable>外链链接：<input type="text" name="linkhref" class="input_type" /></lable>
			</div>
		</div>
		<div class="buttons">
			<button class="persubmit_btn cancel">取消</button>
			<button class="persubmit_btn submit">提交</button>
		</div>
	</div>
	<!-- 添加网站外链编辑框end -->
	<div class="footer mainFooter">
		{if $indexNavBk}
		<fieldset>
			<legend><a href="tencent://message/?uin=928990115&Service=0&sigT=c91aed75f6ce89f7fc95efcb07ae5b66d396e37bb891ba96bf7a544943a82b887bf5289cfc75d7b8406d9" title="联系我"></a>友情链接 QQ:928990115</legend>
			{if $outlinks}
				{section name=somelink loop=$outlinks}
					<a href="{$outlinks[somelink].link_href}" title="{$outlinks[somelink].link_title}" target="_blank" class="outLink" data-linkid="{$outlinks[somelink].link_id}"><span class="title">{$outlinks[somelink].link_title}</span>{if $isSuperAccount}<span class="delOutLinks"></span><span class="editOutLinks"></span>{/if}</a>
				{/section}
			{/if}
			{if $isSuperAccount}
			<a href="#" class="addOutLinks" title="添加外链">+</a>
			{/if}
		</fieldset>
		{/if}
		<p class="copyright">
			<span class="text">收集、整合web开发中前端、后端技术资料<br />©版权所有，保留一切权利</span>
			<script type="text/javascript">
			var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
			document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F19070425cdfe4a00d06ba4ba44f80aa0' type='text/javascript'%3E%3C/script%3E"));
			</script>
		</p>
	</div>
	<input type="hidden" value="{$isUser}" class="isUser" />
</div>
{if !$is_login && !$is_register && !$is_percenter && !$is_updart && !$is_retrpwd && !$is_install && !$wrsuccess && !$upsuccess}
<a class="W_gotop wb-hide" href="#"><span>返回顶部</span></a>
{/if}
<!--[if lte IE 6]>
<script type="text/javascript" src="js/compressor/minmax.min.js"></script>
<![endif]-->
<script type="text/javascript" src="js/compressor/asynLoadJs.min.js"></script>
{if $separticle || ($openmsg && $arttitle && $artcontent && $arttype) || $is_posttxt}
<script type="text/javascript">
{if ($openmsg && $arttitle && $artcontent && $arttype) || $is_posttxt}
//个人中心资源加载
//加载编辑器脚本
asynLoadJs(["data/editor/kindeditor-min.js"], function() {
	asynLoadJs(["data/editor/lang/zh_CN.js"], function() {
	    KindEditor.ready(function(K) {
	    	{if $is_posttxt}
	    	try {
		        window.new_editor = K.create('#artcontent', {
					width : '100%',
					height: '350px',
					resizeType : 1,
					allowFileManager : true,
					newlineTag : 'br',
					urlType : 'absolute',
					items : ['fullscreen', 'preview', 'link', 'unlink', 'clearhtml', 'attachment', 'code', 'runcode', 'emoticons', 'image', 'forecolor', 'hilitecolor', 'bold',
		'italic', 'strikethrough']
				});
		    } catch(e) {}
			{/if}

			{if $openmsg && $arttitle && $artcontent && $arttype}
			try {
		        window.edt_editor = K.create('#artcontent2', {
					width : '100%',
					height: '350px',
					resizeType : 1,
					allowFileManager : true,
					newlineTag : 'br',
					urlType : 'absolute',
					items : ['fullscreen', 'preview', 'link', 'unlink', 'clearhtml', 'attachment', 'code', 'runcode', 'emoticons', 'image', 'forecolor', 'hilitecolor', 'bold',
		'italic', 'strikethrough']
				});
		    } catch(e) {}
			{/if}
			
			document.getElementById('postForm').style.display = 'block';
	    });
	});
});
{/if}
</script>
{/if}
</body>
</html>