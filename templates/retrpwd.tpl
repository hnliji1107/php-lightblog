{include file='header.tpl' title='密码找回'}
<div class="retrpwdWrapper">
	<h1 class="retrpwd_title">密码找回</h1>
	<div class="cntbox">
		<h3>请填写你的用户名和注册邮箱</h3>
	    <div class="fpout"> 
		    <div class="fpbox">
		        <ul class="formitem choose">
		        	<li class="first current">
			        	<label class="lb lb_un">用户名：</label>
			        	<input type="text" name="user_name" value="" class="text" />
		        	</li>
		        	<li class="first current">
			        	<label class="lb lb_un">邮箱：</label>
			        	<input type="text" name="user_email" value="" class="text" />
		        	</li>
		        </ul>
		    </div>
		</div>
	    <h3>请输入图片中的验证码</h3>
	    <div class="fpout"> 
	    	<div class="fpbox">
		        <ul class="formitem">
		        	<li class="last wb-clr">
		        		<div class="checkcode_img wb-clr">
			                <div class="img">
			                	<img title="点击刷新验证码" alt="验证码" class="codeimg" id="codeimg" src="checkcode.php" onclick="this.src='checkcode.php?t='+Math.random()" />
			                </div>
			                <div class="J_checkcode_trigger">
			                	<a onclick="document.getElementById('codeimg').src='checkcode.php?t='+Math.random()">验证码看不清？</a>
			                </div>
			            </div>
						<div class="checkcode_input">
			                <label for="ck_code_input" class="lb lb_co">验证码：</label>
			                <input type="text" class="text code" maxlength="4" name="checkCode" />
			            </div>
		            </li>
		        </ul>
	    	</div> 
		</div>
		<p class="tip">发送邮件的时间间隔是10分钟，请慎重操作！</p>
	    <div class="skin-blue">
	        <button class="persubmit_btn">提交申请</button>
	    </div>
	</div>
</div>
{include file='footer.tpl'}