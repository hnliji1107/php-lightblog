		</div>
		<!--主要部分end-->
	</div>
	<!--单独模块·发私信start-->
	<div id="sendsms">
		<h2>发私信</h2>
		<div class="smsdetail">
			<table>
				<tr><td>发给：</td><td><input type="text" class="sminput_type posttitle" value="{$someUser.user_name}" /></td></tr>
				<tr><td height="5"></td></tr>
				<tr><td>内容：</td><td><textarea class="sminput_type postcontent"></textarea></td></tr>
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
				<tr><td><button class="persubmit_btn lksending">发送</button><span class="tip wb-hide">正在提交，请稍后...</span></td></tr>
			</table>
		</div>
		<a href="#" class="W_close_color"></a>
	</div>
	<!--单独模块·查看私信end-->
	<div class="footer spaceFooter">
		<p class="copyright">
			<span class="text">收集、整合web开发中前端、后端技术资料<br />©版权所有，保留一切权利</span>
			<script type="text/javascript">
			var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
			document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F19070425cdfe4a00d06ba4ba44f80aa0' type='text/javascript'%3E%3C/script%3E"));
			</script>
		</p>
	</div>
	<input type="hidden" value="{$now_user}" class="isUser" />
</div>
<a class="W_gotop wb-hide" href="#"><span>返回顶部</span></a>
<!--[if lte IE 6]>
<script type="text/javascript" src="js/compressor/minmax.min.js"></script>
<![endif]-->
<script type="text/javascript" src="js/compressor/asynLoadJs.min.js"></script>
</body>
</html>