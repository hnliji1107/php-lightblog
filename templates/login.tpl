{include file='header.tpl' title='欢迎登陆'}
    <div class="lgrgInner loginInner">
        <div class="header">用户登录</div>
        <div class="content">
            <div class="user">
                <span class="title">用户名</span>
                <span class="area"><input type="text" name="user_name" class="input_type" /></span>
            </div>
            <div class="password">
                <span class="title">密码</span>
                <span class="area"><input type="password" name="user_password" class="input_type" /></span>
                <span class="tip">没有密码？<a href="register.php">注册</a></span>
            </div>
            <div class="check_code">
            	<span class="title">验证码</span>
                <span class="area"><input type="text" name="check_code" maxlength="4" class="input_type" /></span>
            	<span class="code">
            		<img src="checkcode.php" alt="验证码" class="codeimg" onclick="this.src='checkcode.php?t='+Math.random()" />
            	</span>
            	<span class="tip">不区分大小写</span>
            </div>
            <div class="rempsw wb-clr">
            	<span class="remember_status"><input type="checkbox" /></span>
            	<span>记住登录状态</span>
            	<span class="retrpwd"><a href="retrpwd.php" tabindex="-1" target="_blank">忘记密码？</a></span>
            </div>
            <div class="act-login">
                <button class="persubmit_btn">登录</button>
            </div>
        </div>
    </div>
{include file='footer.tpl'}
