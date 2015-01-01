{include file='header.tpl' title=$title}
{if $someart.topics_id}
	<div class="separtInner">
		<!--左侧部分start-->
		<div class="sepleftSide">
			{if $someart}
			<div class="userinfo">
				<h1>{$someart.article_title}</h1>
				<span><i>{$someart.user_name}</i>&nbsp;[{if $someart.article_modify}编辑{else}发表{/if}于] <em>{$someart.article_time}</em>&nbsp;&nbsp;阅读<i>({$someart.visit_num|default:0})</i>&nbsp;评论<i>({count($comments)})</i>&nbsp;收藏<i>({$collect_num|default:0})</i>&nbsp;[类别]<i>{$someart.article_type}</i></span>
			</div>
			{if $nextArt.topics_id || $prevArt.topics_id}
			<div class="childArts">
				{if $prevArt.topics_id}
				<p class="prevArt">
					<a href="separticle.php?artid={$prevArt.topics_id}">上一篇：{$prevArt.article_title}</a>
				</p>
				{/if}
				{if $nextArt.topics_id}
				<p class="nextArt">
					<a href="separticle.php?artid={$nextArt.topics_id}">下一篇：{$nextArt.article_title}</a>{/if}
				</p>
			</div>
			{/if}
			<div class="userart">{$someart.article_content}</div>
			{if $article_filenames}
			<!--附件下载start-->
			<div class="download">
				<h2>附件下载:</h2>
				{section name=somefile loop=$article_filenames}
					<p>
						<em style="background:url('images/file_logo/{$article_filenames[somefile].filetype}_logo.png') no-repeat;"></em>
						<i><a href="loading.php?attachment_flag=isarticle&attachment_id={$article_filenames[somefile].attachment_id}&loadfile_path={$article_filenames[somefile].filepath}" title="下载{$article_filenames[somefile].filename}">{$article_filenames[somefile].filename}</a></i>
						<i style="color:#666;">({$article_filenames[somefile].filesize},下载次数:{$article_filenames[somefile].downloads})</i>
					</p>
				{/section}
			</div>
			<!--附件下载end-->
			{/if}
			<div class="share sharebar">
				<!-- JiaThis Button BEGIN -->
				<div id="ckepop">
					<span class="jiathis_txt">分享到：</span>
					<a class="jiathis_button_qzone"></a>
					<a class="jiathis_button_tsina"></a>
					<a class="jiathis_button_tqq"></a>
					<a class="jiathis_button_weixin"></a>
					<a class="jiathis_button_renren"></a>
					<a class="jiathis_button_kaixin001"></a>
					<a class="jiathis_button_fb"></a>
					<a class="jiathis_button_twitter"></a>
					<a class="jiathis_button_googleplus"></a>
					<a href="http://www.jiathis.com/share?uid=1774333" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>
					<a class="jiathis_counter_style"></a>
				</div>
				<!-- JiaThis Button END -->
				<div class="collection">
					<a href="#" class="todoCollect collect-icon"></a>
					<a href="#" class="todoCollect">收藏本文</a>
					<input type="hidden" name="artid" value="{$someart.topics_id}" />
					<input type="hidden" name="autor" value="{$someart.user_name}" />
				</div>
			</div>
			{if $visiters}
			<!--谁看过...start-->
			<div class="visiterSide">
				<div class="irbox">
					<div class="irboxtop"><h3>谁看过该文章</h3></div>
					<div class="sepvisiters">
						<ul class="wb-clr">
						{section name=visiter loop=$visiters}
							<li class="wb-left">
								<div class="visiter_photo usertip-wrapper wb-left">
									<input type="hidden" value="{$visiters[visiter].visiter_name}" class="ta-name" />
									<a href="perspace.php?user={$visiters[visiter].visiter_name}"><img data-lazyload-src="{$visiters[visiter].visiter_photo}" src="images/lazyload.png" alt="{$visiters[visiter].visiter_name}" /></a>
								</div>
								<div class="visiternt wb-left">
									<span><a href="perspace.php?user={$visiters[visiter].visiter_name}">{$visiters[visiter].visiter_name|truncate:22:'...':true}</a></span>
									<span>{$visiters[visiter].visiter_time|date_format:'%m-%d %H:%M'}</span>
								</div>
							</li>
						{/section}
						</ul>
					</div>
				</div>
			</div>
			<!--谁看过...end-->
			{/if}
			{if $comments}
			<!--评论列表 start-->
			<div class="commentlist">
				<div class="commentlistTop"><h2>[<i>{$someart.article_title|truncate:15:'..':true}</i>]的评论</h2></div>
					<ul>
						{section name=info loop=$comments}
						<li class="wb-clr">
							<div class="user_photo usertip-wrapper">
								<input type="hidden" value="{$comments[info].user_name}" class="ta-name" />
								<a href="perspace.php?user={$comments[info].user_name}"><img data-lazyload-src="{$comments[info].userphoto}" src="images/lazyload.png" alt="{$comments[info].user_name}" /></a>
								<em class="quote_author">{$comments[info].user_name}</em>
							</div>
							<div class="user_text">
								<p class="uinfo">
									<span class="quote_time">{$comments[info].comment_time|date_format:'%m-%d %H:%M'}</span>
									{if $comments[info].commenter_os && $comments[info].commenter_browser}
									/ {$comments[info].commenter_os} / {$comments[info].commenter_browser}
									{/if}
								</p>
								<p class="quote_txt">{$comments[info].comment_content}</p>
								<input type="hidden" class="associd" value="{$comments[info].comment_id}" />
								<input type="hidden" class="receiver" value="{$comments[info].user_name}" />
								<div class="reply_to_user">
									<!--回复start-->
									{if $replys[{$comments[info].comment_id}]}
										{section name=some loop=$replys[{$comments[info].comment_id}]}
											<div class="replys wb-clr"{if $smarty.section.some.first}style="border-top:none;"{/if}>
												<div class="re_user_photo usertip-wrapper">
													<input type="hidden" value="{$replys[{$comments[info].comment_id}][some].replyname}" class="ta-name" />
													<img data-lazyload-src="{$replys[{$comments[info].comment_id}][some].replyphoto}" src="images/lazyload.png" alt="{$replys[{$comments[info].comment_id}][some].replyname}" title="{$replys[{$comments[info].comment_id}][some].replyname}" />
												</div>
												<div class="re_user_info">
													<p class="uinfo">
														<span class="uname">{$replys[{$comments[info].comment_id}][some].replyname}</span>
														<span class="pinfo">{$replys[{$comments[info].comment_id}][some].replytime|date_format:'%m-%d %H:%M'}
														{if $replys[{$comments[info].comment_id}][some].commenter_os && $replys[{$comments[info].comment_id}][some].commenter_browser}
														/ {$replys[{$comments[info].comment_id}][some].commenter_os} / {$replys[{$comments[info].comment_id}][some].commenter_browser}</span>
														{/if}
													</p>
													<p>{$replys[{$comments[info].comment_id}][some].replytext}</p>
												</div>
											</div>
										{/section}
									{/if}
									<!--回复end-->
								</div>
								{if $comments[info].attachment}
								<!--附件下载start-->
								<div class="download">
									<h2>附件下载:</h2>
									{section name=somefile loop=$comments[info].attachment}
										<p>
											<em style="background:url('images/file_logo/{$comments[info].attachment[somefile].filetype}_logo.png') no-repeat;"></em>
											<i><a href="loading.php?attachment_flag=iscomment&attachment_id={$comments[info].attachment[somefile].attachment_id}&loadfile_path={$comments[info].attachment[somefile].filepath}" title="下载{$comments[info].attachment[somefile].filename}">{$comments[info].attachment[somefile].filename}</a></i>
											<i style="color:#666;">({$comments[info].attachment[somefile].filesize},下载次数:{$comments[info].attachment[somefile].downloads})</i>
										</p>
									{/section}
								</div>
								<!--附件下载end-->
								{/if}
								{if $comments[info].signature}
								<p class="signp">ta的签名：{$comments[info].signature}</p>
								{/if}
							</div>
							<div class="user_reply speart_user_reply"><a href="#comment" class="quote">引用</a> | <a href="#" class="replay">回复</a><br /><i class="floor">#{$smarty.section.info.iteration}</i></div>
						</li>
						{/section}
					</ul>
			</div>
			<!--评论列表 end-->
			{/if}
			<div class="commentarea">
				<h2>评论[<i>{$someart.article_title|truncate:15:'..':true}</i>]</h2>
				<div class="station wb-hide">
					<!--上传附件start-->
					<div class="post_upload_file" style="{if $is_open_attachment}display:block;{/if}">
						<h3>上传附件</h3>
						<div class="successtip">
							<ul>
								{if $filenames}
									{section name=somefile loop=$filenames}
										<li>
											<em style="background:url('images/file_logo/{$filenames[somefile].filetype}_logo.png') no-repeat;"></em>
											<i class="fname" title="{$filenames[somefile].filename}">{$filenames[somefile].filename}</i> / 
											<i class="fsize">{$filenames[somefile].filesize}</i> / 
											<i class="ftime">{$filenames[somefile].attachment_time|date_format:'%m-%d %H:%M:%S'}上传</i>
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
								<input type="file" name="file" class="attachment_style" />
								<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
								<input type="hidden" name="assoc_artid" value="{$someart.topics_id}" />
								<input type="hidden" name="upload_attachment" value="yes" />
								<button type="submit" class="search_btn">上传</button>
							</form>
							<iframe name="hide_iframe" class="wb-hide"></iframe>
						</div>
					</div>
					<!--上传附件end-->
				</div>
				<div class="comment_txt wb-hide">
					<form action="separticle.php?artid={$someart.topics_id}#comment" method="post">
						<input type="hidden" name="replyid" value="{$someart.topics_id}" />
						<textarea id="comment_text" name="comment_text"></textarea>
						<button type="submit" class="persubmit_btn">提交评论</button>
					</form>
				</div>
			</div>
			{else}
				<p class="empty-content">抱歉哦，该文章不存在。</p>
			{/if}
		</div>
		<!--左侧部分end-->
	</div>
{else}
<p class="empty-content">抱歉，你所查找的文章不存在!</p>
{/if}
{include file='footer.tpl'}