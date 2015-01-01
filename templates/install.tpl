{include file='header.tpl' title='安装程序'}
<div class="installWrapper">
	<h1>欢迎安装web资料站程序</h1>
	<div class="install">
		<h2>数据库设置</h2>
		<div>
			<table>
				<tr><td class="title">数据库主机：</td><td><input type="text" name="host" value="localhost" /> <span>*</span></td></tr>
				<tr><td class="title">用户名：</td><td><input type="text" name="db_user" /> <span>*</span></td></tr>
				<tr><td class="title">密码：</td><td><input type="password" name="db_password" /></td></tr>
				<tr><td class="title">数据库名：</td><td><input type="text" name="db_name" /> <span>*</span></td></tr>
			</table>
		</div>
		<div class="insbtn"><button class="persubmit_btn">确认安装</button></div>
		<!--提示模块-->
		<div class="tipblock wb-hide">
		    <h3>安装程序监视器</h3>
		    <div class="waiting">
		    	<img src="images/waiting.gif" alt="加载中..." height="13" width="300" />
		    </div>
		    <div class="dising"></div>
		    <a href="#" class="W_close_color"></a>
		</div>
	</div>
</div>
{include file='footer.tpl'}