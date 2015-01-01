{include file='spaceheader.tpl' title=$title}
		<div class="albumInner">
			<!--右侧start-->
			<div class="disArts">
			{if !$lookKey}
				<!--已建相册start-->
				<div class="disArts_box calbum">
					<h2>{if {$someUser.user_name}==={$now_user}}我{else}主人{/if}的相册</h2>
					{if $successTip}
					<div class="create_success">相册<i>{$successTip}</i>创建成功!</div>
					{/if}
					{if $albums}
					<ul class="wb-clr">
						{section name=theAlbum loop=$albums}
						<li>
							<div class="set">
								<div class="set-case">
									<a href="album.php?user={$someUser.user_name}&act=look&id={$albums[theAlbum].album_id}">
										<img data-lazyload-src="{if $albums[theAlbum].album_cover}{$albums[theAlbum].album_cover}{else}images/default_set_photo.gif{/if}" src="images/lazyload.png" alt="{$albums[theAlbum].album_name}" title="{$albums[theAlbum].album_name}" />
									</a>
								</div>
								<div class="set-details">
									<h4><a href="album.php?user={$someUser.user_name}&act=look&id={$albums[theAlbum].album_id}" class="aname">{$albums[theAlbum].album_name}</a></h4>
									<h4>共({$albums[theAlbum].photo_count-1})张</h4>
									<h4>评论({$albums[theAlbum].comment_num})</h4>
									<p class="adesc">描述:{$albums[theAlbum].album_description}</p>
									{if {$someUser.user_name}==={$now_user}}
									<input type="hidden" value="{$albums[theAlbum].album_id}" class="aid" />
									<a title="编辑相册" href="#" class="toEditAlbum">编辑</a> |
									<a title="删除相册" href="#" class="delAlbum">删除</a>
									{/if}
								</div>
							</div>
						</li>
						{/section}
					</ul>
					{if {$someUser.user_name}==={$now_user}}
					<a href="#" class="createNew">创建新相册</a>
					{/if}
					{else}
					{if {$someUser.user_name}==={$now_user}}
					<p class="noneAlbum">你当前没有相册{if {$now_user}}，马上<a href="#" class="createNew">创建新相册</a>?{/if}</p>
					{else}
					<p class="noneAlbum">抱歉，该用户尚未创建相册</p>
					{/if}
					{/if}
				</div>
				<!--已建相册end-->	
				{if {$someUser.user_name}==={$now_user}}
				<!--创建相册start-->	
				<div class="disArts_box wb-hide">
	                <div class="createArea">
	                	<h2>创建新相册</h2>
	                	<form action="album.php?user={$someUser.user_name}" method="post">
		                	<div class="album_name wb-clr">
		                		<span class="title">相册名称：</span>
		                		<span class="content">
		                			<input type="text" name="album_name" class="input_type" />
		                			<input type="hidden" name="assoc_userid" value="{$someUser.user_id}" />
		                		</span>
		                		{if $albums}
		                		<a href="#" class="lookAlbums">查看已有相册</a>
		                		{/if}
		                	</div>
		                	<div class="album_desc wb-clr">
		                		<span class="title">相册描述：</span>
		                		<span class="content"><textarea name="album_description" cols="50" rows="5"></textarea></span>
		                	</div>
		                	<div class="calbtn">
								<button type="submit" class="persubmit_btn creatAlbum">创建相册</button>
								<span class="error_tip"></span>
							</div>
						</form>
	                </div>
				</div>
				<!--创建相册end-->
				{/if}
				<!--独立模块·编辑相册start-->
				<div id="edit_album">
					<h3><a href="#" class="edit_close">关闭</a><span>相册编辑</span></h3>
					<div class="detail">
						<p class="nameTitle">名称：</p>
						<p class="nameContent"><input type="text" class="input_type aname" value="" /></p>
						<p class="descTitle">描述：</p>
						<p class="descContent"><textarea cols="40" rows="5" class="adesc"></textarea></p>
					</div>
					<input type="hidden" value="" class="aid" />
					<button class="persubmit_btn">提交修改</button>
				</div>
				<!--独立模块·编辑相册end-->
			{else}
				<div class="disArts_box album_photos">
					<h2><a href="album.php?user={$someUser.user_name}">{if {$someUser.user_name}==={$now_user}}我的{else}主人{/if}相册</a> > {$photos.album_name}{if count($photo_path_name) >= 3}<label class="is3d"><input type="checkbox"> 3d预览</label>{/if}</h2>
					{if {$someUser.user_name}==={$now_user}}
					<div class="upload_manage_key">
						<div class="wb-left"><button class="persubmit_btn upload" style="margin:10px;">上传</button></div>
						{if $photo_path_name}
						<div class="manage_key wb-left"><a href="#" class="managPhotos">管理</a></div>
						{/if}
					</div>
					<div class="manage wb-hide">
						<label><input type="checkbox" class="totalPhotos" /> 全选</label>
						<input type="hidden" value="{$photos.album_id}" class="aid" />
						<a href="#" class="setPhoto" title="将选中的照片设为封面">设为封面</a>
						<a href="#" class="delPhoto" title="删除选中的照片">删除</a>
						{if count($albums) >= 2}
						<span class="movearea">
							<a href="#" title="将选中相片移到其他相册">移动到</a>
							<input type="hidden" value="{$photos.album_id}" class="this_album_id" />
							<select class="movePhoto">
								<option>--选择相册--</option>
								{section name=somename loop=$albums}
									{if $albums[somename].album_name !== {$photos.album_name}}
										<option value="{$albums[somename].album_id}">{$albums[somename].album_name|truncate:10:"..":true}</option>
									{/if}
								{/section}
							</select>
						</span>
						{/if}
					</div>
					{/if}
					{if $photo_path_name}
					<ul class="wb-clr">
						{section name=somephoto loop=$photo_path_name}
						<li>
							<a><img data-lazyload-src="{$photo_path_name[somephoto].path}" src="images/lazyload.png" origin_path="{$photo_path_name[somephoto].origin_path}" alt="{$photo_path_name[somephoto].name}" title="{$photo_path_name[somephoto].name}" /></a>
							<p title="{$photo_path_name[somephoto].name}" class="thephoto">{$photo_path_name[somephoto].name}</p>
							<div class="checking wb-hide"><input type="checkbox" value="{$photo_path_name[somephoto].name}" /></div>
						</li>
						{/section}
					</ul>
					{if $alcomment}
					<!--评论start-->
					<div class="commentlist alcomment">
						<h2>相片评论</h2>
						{section name=onecomment loop=$alcomment}
						<div class="item wb-clr">
							<div class="user_photo usertip-wrapper">
								<input type="hidden" value="{$alcomment[onecomment].user_name}" class="ta-name" />
								<a href="perspace.php?user={$alcomment[onecomment].user_name}"><img data-lazyload-src="{$alcomment[onecomment].user_photo}" src="images/lazyload.png" alt="{$alcomment[onecomment].user_name}" /></a>
								<em>{$alcomment[onecomment].user_name|truncate:9:"..":true}</em>
							</div>
							<div class="user_text">
								<p class="uinfo">
									{$alcomment[onecomment].alcomment_time|date_format:'%m-%d %H:%M'}
									{if $alcomment[onecomment].commenter_os && $alcomment[onecomment].commenter_browser}
									/ {$alcomment[onecomment].commenter_os} / {$alcomment[onecomment].commenter_browser}
									{/if}
								</p>
								<p class="quote_txt">{$alcomment[onecomment].alcomment_text}</p>
								<input type="hidden" value="{$alcomment[onecomment].alcomment_id}" class="associd" />
								<div class="reply_to_user">
									<!--回复start-->
									{if $alcomment_replys[{$alcomment[onecomment].alcomment_id}]}
										{section name=some loop=$alcomment_replys[{$alcomment[onecomment].alcomment_id}]}
											<div class="replys wb-clr"{if $smarty.section.some.first}style="border-top:none;"{/if}>
												<div class="re_user_photo usertip-wrapper">
													<input type="hidden" value="{$alcomment_replys[{$alcomment[onecomment].alcomment_id}][some].replyname}" class="ta-name" />
													<img data-lazyload-src="{$alcomment_replys[{$alcomment[onecomment].alcomment_id}][some].replyphoto}" src="images/lazyload.png" alt="{$alcomment_replys[{$alcomment[onecomment].alcomment_id}][some].replyname}" title="{$alcomment_replys[{$alcomment[onecomment].alcomment_id}][some].replyname}" />
												</div>
												<div class="re_user_info">
													<p class="nametime">
														<span style="color:#369;">{$alcomment_replys[{$alcomment[onecomment].alcomment_id}][some].replyname}</span> {$alcomment_replys[{$alcomment[onecomment].alcomment_id}][some].replytime|date_format:"%m-%d %H:%M"}{if $alcomment_replys[{$alcomment[onecomment].alcomment_id}][some].commenter_os && $alcomment_replys[{$alcomment[onecomment].alcomment_id}][some].commenter_browser} / {$alcomment_replys[{$alcomment[onecomment].alcomment_id}][some].commenter_os} / {$alcomment_replys[{$alcomment[onecomment].alcomment_id}][some].commenter_browser}{/if}
													</p>
													<p>{$alcomment_replys[{$alcomment[onecomment].alcomment_id}][some].replytext}</p>
												</div>
											</div>
										{/section}
									{/if}
									<!--回复end-->
								</div>
								{if $alcomment[onecomment].signature}
								<p class="signp">ta的签名：{$alcomment[onecomment].signature}</p>
								{/if}
							</div>
							<div class="user_reply"><a href="#" class="replay">回复</a></div>
						</div>
						{/section}
					</div>
					<!--评论end-->
					{/if}
					<div class="photo_comment">
						<h2>评论相册/相片</h2>
						<form action="album.php?user={$someUser.user_name}&act=look&id={$album_id}#photo_comment" method="post">
						<input type="hidden" name="album_id" value="{$album_id}" />
						<textarea name="phcomment" cols="82" rows="5" class="textarea_type"></textarea>
						<input type="submit" class="persubmit_btn" value="提交评论" />
						</form>
					</div>
					{else}
					<p class="empty-content">暂无照片</p>
					{/if}
				</div>
				<!--独立模块·上传图片start-->
				<div id="uploadBlock">
					<h2>
						<span class="mpos">上传相片</span>
						<a href="#" class="W_close_color"></a>
					</h2>
					<form action="album.php?user={$someUser.user_name}&act=look&id={$album_id}" method="post" enctype="multipart/form-data">
						<div class="uploadHead wb-clr">
							<button class="persubmit_btn addphoto">添加相片</button>
							<button type="submit" class="persubmit_btn submit_photos">开始上传</button>
							<input type="hidden" name="albumId" value="{$photos.album_id}" />
							<input type="hidden" name="albumName" value="{$someUser.user_name}" />
						</div>
						<div class="uploadList">
							<ul></ul>
						</div>
					</form>
				</div>
				<!--独立模块·上传图片end-->
				<!--独立模块·图片展开start-->
				<div id="big_ph">
					<img src="images/lazyload.png" title="大图" alt="大图" class="theImg" />
					<div class="opt_img"><span class="close">关闭</span></div>
				</div>
				<!--独立模块·图片展开end-->
			{/if}
			</div>
			<!--右侧end-->
		</div>
{include file='spacefooter.tpl'}