{include file='header.tpl' title='欢迎注册'}
    <div class="lgrgInner registInner">
        <div class="header">注册新用户</div>
        <div class="content">
            <div class="email">
                <span class="title">Email</span>
                <span class="area"><input name="email" type="text" class="input_type" /></span>
                <span class="tip">请填写有效的Emal地址确保以后我们能够联系到您。</span>
            </div>
            <div class="user">
                <span class="title">用户名</span>
                <span class="area"><input name="name" type="text" class="input_type" /></span>
                <span class="tip">尽量以字母或下划线开头</span>
            </div>
            <div class="password">
                <span class="title">设定密码</span>
                <span class="area"><input name="password" type="password" class="input_type" /></span>
                <span class="tip">密码请设为6-16位字母或数字</span>
            </div>
            <div class="repassword">
                <span class="title">再次输入密码</span>
                <span class="area"><input name="repassword" type="password" class="input_type" /></span>
            </div>
            <div class="check_code">
                <span class="title">验证码</span>
                <span class="area"><input type="text" name="check_code" maxlength="4" class="input_type" /></span>
                <span class="code">
                    <img src="checkcode.php" alt="验证码" class="codeimg" onclick="this.src='checkcode.php?t='+Math.random()"/>
                </span>
                <span class="tip">不区分大小写</span>
            </div>
            <div class="act-register">
                <span><button class="persubmit_btn">注册</button></span>
                <span class="to_login">已有账号? <a href="login.php">请登录</a></span>
            </div>
        </div>

    </div>
{include file='footer.tpl'}
