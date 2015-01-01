{include file='header.tpl' title='个人中心'}
	<div class="percenterInner">
		<div class="sideBoxMid wb-clr">
			<!--left side-->
			<div class="features_list">
				<ul>
			    	<li><a href="percenter.php?act=accout" {if $accout}class="addlibk"{/if}>个人资料</a></li>
			        <li><a href="percenter.php?act=modify_password" {if $modify_password}class="addlibk"{/if}>修改密码</a></li>
			        <li><a href="percenter.php?act=modify_photo" {if $modify_photo}class="addlibk"{/if}>修改头像</a></li>
			        <li><a href="percenter.php?act=is_posttxt" {if $is_posttxt}class="addlibk"{/if}>发表文章</a></li>
			        {if $openmsg && $arttitle && $artcontent && $arttype}
			        <li><a href="updarticles.php?artid={$artid}" {if $openmsg && $arttitle && $artcontent && $arttype}class="addlibk"{/if}>修改文章</a></li>
			        {/if}
				    <li><a href="percenter.php?act=artmanage" {if $manageblock}class="addlibk"{/if}>管理文章</a></li>
				    <li style="border-bottom:none;"><a href="percenter.php?act=artcollect" {if $collectblock}class="addlibk"{/if}>我的收藏</a></li>
			    </ul>
			</div>
			<!--right side-->
			<div class="features">
				{if $accout}
			    <!--个人资料-->
			    <div class="toggle moreadd">
			    	<!--显示个人资料start-->
			    	<div id="display_information">
				        <table width="100%">
				            <tr>
				                <td class="title" width="20%">用户名：</td>
				                <td>&nbsp;{$user_infomation.user_name}</td>
				            </tr>
				            <tr height="10"></tr>
				            <tr>
				                <td class="title" width="20%">性别：</td>
				                <td>&nbsp;{$user_infomation.sex|default:"保密"}</td>
				            </tr>
				            <tr height="10"></tr>
				            <tr>
				                <td class="title" width="20%">Email：</td>
				                <td>&nbsp;{$user_infomation.user_email}</td>
				            </tr>
				            <tr height="10"></tr>
				            <tr>
				                <td class="title" width="20%">Q Q：</td>
				                <td>&nbsp;{$user_infomation.QQ|default:"暂无"}</td>
				            </tr>
				            <tr height="10"></tr>
				            <tr>
				                <td class="title" width="20%">手机：</td>
				                <td>&nbsp;{$user_infomation.phone|default:"暂无"}</td>
				            </tr>
				            <tr height="10"></tr>
				            <tr>
				                <td class="title" width="20%">注册时间：</td>
				                <td>&nbsp;{$user_infomation.register_time}</td>
				            </tr>
				            <tr height="10"></tr>
				        </table>
				        <div class="signing">
				        	<span class="signtitle">签名：</span>
				        	<span class="signtext">{$user_infomation.signature|default:"暂无"}</span>
				        </div>
				        <input type="hidden" value="{$user_infomation.sex}" class="info_sex" />
				        <input type="hidden" value="{$user_infomation.user_email}" class="info_email" />
				        <input type="hidden" value="{$user_infomation.QQ}" class="info_qq" />
				        <input type="hidden" value="{$user_infomation.phone}" class="info_phone" />
				        <input type="hidden" value="{$user_infomation.signature}" class="info_signature" />
						<button class="persubmit_btn">修改资料</button>
					</div>
					<!--显示个人资料start-->
					<!--修改资料start-->
					<div id="modify_information" class="wb-hide">
						<table width="100%">
							<tr>
								<td class="title" width="20%">性别：</td>
								<td class="sexSelect">
									<label>男 <input type="radio" name="sex" value="男" {if $user_infomation.sex=="男"}checked{/if} /></label>
									<label>女 <input type="radio" name="sex" value="女" {if $user_infomation.sex=="女"}checked{/if} /></label>
									<label>保密 <input type="radio" name="sex" value="保密" {if $user_infomation.sex=="保密"}checked{/if} /></label>
								</td>
							</tr>
							<tr height="10"></tr>
							<tr>
								<td class="title" width="20%">Email：</td>
								<td><input type="text" name="mod_email" value="{$user_infomation.user_email}" class="modify_input_type" /></td>
							</tr>
							<tr height="10"></tr>
							<tr>
								<td class="title" width="20%">Q Q：</td>
								<td><input type="text" name="mod_qq" value="{$user_infomation.QQ}" class="modify_input_type" /></td>
							</tr>
							<tr height="10"></tr>
							<tr>
								<td class="title" width="20%">手机：</td>
								<td><input type="text" name="mod_phone" value="{$user_infomation.phone}" class="modify_input_type" /></td>
							</tr>
							<tr height="10"></tr>
						</table>
						<div id="charNumTip"></div>
						<div class="signing modsigning">
							<span class="signtitle modtitle">签名：</span>
							<span class="signtext"><textarea rows="3" cols="92" onfocus="checkCharNum(this,100,'charNumTip')">{$user_infomation.signature}</textarea></span>
						</div>
						<div class="submitArea wb-clr">
							<button class="persubmit_btn" id="save_modify">保存修改</button>
							<a href="percenter.php?act=accout" class="returnAccout">返回个人资料</a>
						</div>
					</div>
					<!--修改资料end-->
			    </div>
			   	{/if}
			   	{if $modify_password}
			    <!--修改密码-->
			    <div class="toggle moreadd" id="modify_password">
			        <table width="100%">
			            <tr>
			                <td align="right" width="20%" class="title">用户名：</td>
			                <td>&nbsp;{$user_infomation.user_name}</td>
			            </tr>
			            <tr height="10"></tr>
			            <tr>
			                <td align="right" width="20%" class="title">原密码：</td>
			                <td><input type="password" name="modify_password" class="input_type" /></td>
			            </tr>
			            <tr height="10"></tr>
			            <tr>
			                <td align="right" width="20%" class="title">新密码：</td>
			                <td><input type="password" name="new_password" class="input_type" /></td>
			            </tr>
			            <tr height="10"></tr>
			        </table>
			        <div class="wb-clr">
			        	<input type="hidden" value="{$user_infomation.user_name}" class="username" />
			        	<button class="persubmit_btn">提交修改</button>
			        </div>
			    </div>
			    {/if}
			    {if $modify_photo}
			    <!--修改头像-->
			    <div class="toggle moreadd" id="waitpar">
			    	<div class="photoinfo">
			    		<span class="photo"><img data-lazyload-src="{$photoPath}" src="images/lazyload.png" alt="{$isUser}" title="{$isUser}" /></span>
			    		<span class="info"><i>图片格式：</i>jpg,png,gif<br /><i>图片大小：</i>8M以内</span>
			    		<div class="updateAvatar">
				    		<form action="percenter.php" method="post" enctype="multipart/form-data">
					    		<div class="uploadAvatar">
					    			<a href="#">使用本地图片更换头像</a>
					        		<input type="file" name="file" />
					    		</div>
					        	<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
					      	</form>
					    </div>
			    	</div>	   
			    </div>
			    {/if}
			    {if $is_posttxt}
			    <!--发表文章-->
			    <div class="toggle" id="publishArticle">
			    	<h2>发表文章</h2>
			    	<div class="station">
				    	<!--上传附件start-->
						<div class="post_upload_file percenter_upload_file" style="{if $is_open_attachment}display:block;{/if}">
							<h3>上传附件</h3>
							<div class="successtip">
								<ul>
									{if $filenames}
										{section name=somefile loop=$filenames}
											<li>
												<em style="background:url('images/file_logo/{$filenames[somefile].filetype}_logo.png') no-repeat;"></em>
												<i class="fname" title="{$filenames[somefile].filename}">{$filenames[somefile].filename}</i> / 
												<i class="fsize">{$filenames[somefile].filesize}</i> / 
												<i class="ftime">{$filenames[somefile].attachment_time|date_format:"%m-%d %H:%M:%S"}上传</i>
												<a href="#" title="删除附件" class="W_close_color"></a>
												<input type="hidden" value="{$filenames[somefile].attachment_id}" />
											</li>
										{/section}
									{else}
										<p>请注意：<br />1.文件大小不能超过8M<br />2.文件名长度尽量不要超过200个字符</p>
									{/if}
								</ul>
							</div>
							<div class="uping">
								<form action="upload.php" enctype="multipart/form-data" method="post" target="hide_iframe">
									<input type="file" name="attachment" class="attachment_style" />
									<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
									<input type="hidden" name="art_upload_attachment" value="yes" />
									<button type="submit" class="search_btn">上传</button>
								</form>
								<iframe name="hide_iframe" class="wb-hide"></iframe>
							</div>
						</div>
						<!--上传附件end-->
					</div>
			    	<form action="pulsarticles.php" method="post" id="postForm">
					<div class="articlepost">
						<p>
							<input type="text" name="arttitle" value="" class="subject" />
							<span class="gray">这里是标题</span>
						</p>
					</div>
			    	<textarea id="artcontent" name="artcontent" cols="100" rows="40"></textarea>
			    	<p>
						<label>所属分类</label>
						<span class="category">
							<span><input type="radio" value="html" name="tid">html</span>
							<span><input type="radio" value="css" name="tid">css</span>
							<span><input type="radio" value="js" name="tid">js</span>
							<span><input type="radio" value="jq" name="tid">jq</span>
							<span><input type="radio" value="php" name="tid">php</span>
							<span><input type="radio" value="nodejs" name="tid">nodejs</span>
						</span>
					</p>
					<p><button type="submit" class="persubmit_btn">提交发布</button></p>
					</form>
			    </div>
			    {/if}
			    {if $openmsg && $arttitle && $artcontent && $arttype}
			    <!--修改文章-->
			    <div class="toggle" id="modifyArticle">
			    	<h2>修改文章</h2>
			    	<div class="station">
			    		<!--上传附件start-->
						<div class="post_upload_file percenter_upload_file" style="{if $is_open_attachment}display:block;{/if}">
							<h3>上传附件</h3>
							<div class="successtip">
								<ul>
									{if $filenames}
										{section name=somefile loop=$filenames}
											<li>
												<em style="background:url('images/file_logo/{$filenames[somefile].filetype}_logo.png') no-repeat;"></em>
												<i class="fname" title="{$filenames[somefile].filename}">{$filenames[somefile].filename}</i> / 
												<i class="fsize">{$filenames[somefile].filesize}</i> / 
												<i class="ftime">{$filenames[somefile].attachment_time|date_format:"%m-%d %H:%M:%S"}上传</i>
												<a href="#" title="删除附件" class="W_close_color"></a>
												<input type="hidden" value="{$filenames[somefile].attachment_id}" />
											</li>
										{/section}
									{else}
										<p>请注意：<br />1.文件大小不能超过8M<br />2.文件名长度尽量不要超过200个字符</p>
									{/if}
								</ul>
							</div>
							<div class="uping">
								<form action="upload.php" enctype="multipart/form-data" method="post" target="hide_iframe">
									<input type="file" name="attachment" class="attachment_style" />
									<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
									<input type="hidden" name="art_upload_attachment" value="yes" />
									<input type="hidden" name="modify_tmpid" value="{$artid}" />
									<button type="submit" class="search_btn">上传</button>
								</form>
								<iframe name="hide_iframe" class="wb-hide"></iframe>
							</div>
						</div>
						<!--上传附件end-->
			    	</div>
			    	<form action="pulsarticles.php" method="post" id="postForm">
					<div class="articlepost">
						<p>
							<input type="text" name="arttitle2" value="{$arttitle}" class="subject" />
							<input type="hidden" name="artid" value="{$artid}" />
							<span class="gray">这里是标题</span>
						</p>
					</div>
			    	<textarea id="artcontent2" name="artcontent2" cols="100" rows="40">{$artcontent}</textarea>
			    	<p>
						<label>所属栏目</label>
						<span>
							<input type="radio" {if $arttype == "html"}checked="checked"{/if} value="html" name="tid2"> html
							<input type="radio" {if $arttype == "css"}checked="checked"{/if} value="css" name="tid2"> css
							<input type="radio" {if $arttype == "js"}checked="checked"{/if} value="js" name="tid2"> js
							<input type="radio" {if $arttype == "jq"}checked="checked"{/if} value="jq" name="tid2"> jq
							<input type="radio" {if $arttype == "php"}checked="checked"{/if} value="php" name="tid2"> php
							<input type="radio" {if $arttype == "nodejs"}checked="checked"{/if} value="nodejs" name="tid2"> nodejs
						</span>
					</p>
					<p><button type="submit" id="submit_article2" class="persubmit_btn">提交修改</button></p>
					</form>
			    </div>
			    {/if}
			    {if $manageblock}
			    <!--管理文章-->
			    <div class="oprblock manageblock">
			    	{if $artlist}
			    	<table width="100%">
						<thead>
							<tr>
								<th height="30">标题</th>
								<th>阅读/评论/收藏</th>
								<th>时间</th>
								<th>类型</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							{section name=artinfo loop=$artlist}
							<tr>
								<td width="20%"><a href="separticle.php?artid={$artlist[artinfo].topics_id}" target="_blank">{$artlist[artinfo].article_title|truncate:16:'..':true}</a></td>
								<td width="20%">{$artlist[artinfo].visit_num|default:0} / {$artlist[artinfo].comment_num|default:0} / {$artlist[artinfo].collect_num|default:0}</td>
								<td width="20%">{$artlist[artinfo].article_time}</td>
								<td width="20%">{$artlist[artinfo].article_type}</td>
								<td width="20%" class="oparator">
									<a href="updarticles.php?artid={$artlist[artinfo].topics_id}&act=up">修改</a>
									<a href="#" class="delart">删除</a>
									<input type="hidden" value="{$artlist[artinfo].topics_id}" />
									<a href="separticle.php?artid={$artlist[artinfo].topics_id}" target="_blank">查看</a>
								</td>
							</tr>
							{/section}
						</tbody>
					</table>
					{else}
						<p>你还没有发表任何文章哦，现在<span class="towrite"><a href="percenter.php?act=is_posttxt">发表>>></a></span></p>
					{/if}
			    </div>
			    {/if}
			    {if $collectblock}
			    <!--我的收藏-->
			    <div class="oprblock collectblock">
			    	{if $collects}
				    	<table width="100%">
							<thead>
								<tr>
									<th height="30">标题</th>
									<th>阅读/评论/收藏</th>
									<th>时间</th>
									<th>类型</th>
									<th>作者</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								{section name=collect loop=$collects}
								<tr>
									<td width="20%"><a href="separticle.php?artid={$collects[collect].topics_id}" target="_blank">{$collects[collect].article_title|truncate:16:'..':true}</a></td>
									<td width="20%">{$collects[collect].visit_num|default:0} / {$collects[collect].comment_num|default:0} / {$collects[collect].collect_num|default:0}</td>
									<td width="20%">{$collects[collect].article_time}</td>
									<td width="10%">{$collects[collect].article_type}</td>
									<td width="10%">{$collects[collect].user_name}</td>
									<td width="20%" class="oparator">
										<a href="#" class="delcollect">删除</a>
										<input type="hidden" value="{$collects[collect].topics_id}" />
										<a href="separticle.php?artid={$collects[collect].topics_id}" target="_blank">查看</a>
									</td>
								</tr>
								{/section}
							</tbody>
						</table>
					{else}
						<p>你还没有收藏任何文章哦!</p>
					{/if}
			    </div>
			    {/if}
			</div>
		</div>
	</div>
{include file='footer.tpl'}