{include file='spaceheader.tpl' title=$title}
			<div class="perspaceInner">
				{if $spacehome}
				<!--右侧start-->
				<div class="disArts">
					<!--最新动作start-->
					<div class="disArts_box newact">
						<div class="boxtop"><h2>最新动作</h2></div>
						{if $spacerArts || $myCollects || $myComments || $myspComments || $attenerArr ||$albums || $myalcomment}
						<ul>
							{section name=spacerArt loop=$spacerArts}
								{if $smarty.section.spacerArt.iteration <= 2}
									<li>
										<div class="someart_info">
											<span class="title">{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}</span> 发表文章
											<a href="separticle.php?artid={$spacerArts[spacerArt].topics_id}">{$spacerArts[spacerArt].article_title}</a>
											<span class="feed-dateline">{$spacerArts[spacerArt].article_time|date_format:'%Y-%m-%d %H:%M'}</span>
										</div>
									</li>
								{/if}
							{/section}
							{section name=somecollect loop=$myCollects}
								{if $smarty.section.somecollect.iteration <= 2}
									<li>
										<div class="someart_info">
											<span class="title">{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}</span> 收藏了
											<a href="perspace.php?user={$myCollects[somecollect].user_name}">{$myCollects[somecollect].user_name}</a> 的文章-->
											<a href="separticle.php?artid={$myCollects[somecollect].topics_id}">{$myCollects[somecollect].article_title}</a>
											<span class="feed-dateline">{$myCollects[somecollect].collectTime|date_format:'%Y-%m-%d %H:%M'}</span>
										</div>
									</li>
								{/if}
							{/section}
							{section name=somecomment loop=$myComments}
								{if $smarty.section.somecomment.iteration <= 2}
									<li>
										<div class="someart_info">
											<span class="title">{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}</span> 评论了
											{if $someUser.user_name === $myComments[somecomment].user_name}
												<i class="title">自己</i>
											{else}
												<a href="perspace.php?user={$myComments[somecomment].user_name}">{$myComments[somecomment].user_name}</a>
											{/if}
											的文章-->
											<a href="separticle.php?artid={$myComments[somecomment].topics_id}">{$myComments[somecomment].article_title}</a>
											<span class="feed-dateline">{$myComments[somecomment].commentTime|date_format:'%Y-%m-%d %H:%M'}</span>
										</div>
										{if $myComments[somecomment].commentContent}
										<div class="someart_text">{$myComments[somecomment].commentContent|truncate:100:'..':true}</div>
										{/if}
									</li>
								{/if}
							{/section}
							{section name=somesp loop=$myspComments}
								{if $smarty.section.somesp.iteration <= 2}
									<li>
										<div class="someart_info">
											<span class="title">{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}</span> 评论了
											{if $someUser.user_name === $myspComments[somesp].user_name}
												<i class="title">自己</i>
											{else}
												<a href="perspace.php?user={$myspComments[somesp].user_name}">{$myspComments[somesp].user_name}</a>
											{/if}
												的空间
											<span class="feed-dateline">{$myspComments[somesp].msgTime|date_format:'%Y-%m-%d %H:%M'}</span>
										</div>
										{if $myspComments[somesp].msgContent}
										<div class="someart_text">{$myspComments[somesp].msgContent|strip_tags|truncate:100:'..':true}</div>
										{/if}
									</li>
								{/if}
							{/section}
							{if $attenerArr}
							<li>
								<div class="someart_info">
									<span class="title">{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}</span> 关注了
									{section name=attener loop=$attenerArr}
										<a href="perspace.php?user={$attenerArr[attener]}">{$attenerArr[attener]}</a>
										{if !$smarty.section.attener.last}、{/if}
									{/section}
								</div>
							</li>
							{/if}
							{if $albums}
							<li>
								<div class="someart_info wb-clr">
									{section name=album loop=$albums}
										{if $albums[album].album_photos}
											<div class="dsside">
												<span class="title"><i>{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}</i> 在 <a href="album.php?user={$someUser.user_name}&act=look&id={$albums[album].album_id}">{$albums[album].album_name|truncate:10:'..':true}</a> 相册上传了</span>
												<span class="imgs">
												{section name=ph loop=$albums[album].album_photos}
													{if $smarty.section.ph.iteration <= 5}
														<a href="album.php?user={$someUser.user_name}&act=look&id={$albums[album].album_id}"><img data-lazyload-src="{$albums[album].album_photos[ph]}" src="images/lazyload.png" alt="图片" width="50" height="50" /></a>
													{/if}
												{/section}
												</span>
											</div>
										{/if}
									{/section}
								</div>
							</li>
							{/if}
							{if $myalcomment}
								<li>
									<div class="someart_info wb-clr">
										{section name=onealcomment loop=$myalcomment}
											<div class="dsside alcommentinfo">
												<span class="title"><i>{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}</i> 评论了 {if $someUser.user_name === $myalcomment[onealcomment].album_owner}<i>自己</i>{else}<a href="perspace.php?user={$myalcomment[onealcomment].album_owner}">{$myalcomment[onealcomment].album_owner}</a>{/if} 的 <a href="album.php?user={$someUser.user_name}&act=look&id={$myalcomment[onealcomment].album_id}">{$myalcomment[onealcomment].album_name|truncate:10:'..':true}</a> 相册</span>
												<span class="imgs">
												{if $myalcomment[onealcomment]}
													{section name=somecomment loop=$myalcomment[onealcomment]}
														{if $myalcomment[onealcomment][somecomment].alcomment_text}
															{if $smarty.section.somecomment.iteration === 1}
																<p>{$myalcomment[onealcomment][somecomment].alcomment_text}</p>
																<p>---{$myalcomment[onealcomment][somecomment].alcomment_time|date_format:'%Y-%m-%d %H:%M'}</p>
															{/if}
														{/if}
													{/section}
												{/if}
												</span>
											</div>
										{/section}
									</div>
								</li>
							{/if}
						</ul>
						{else}
						<p class="empty-content">抱歉，暂无最新哦!</p>
						{/if}
					</div>
					<!--最新动作end-->
					<!--最新文章列表start-->
					<div class="disArts_box">
						<div class="boxtop"><h2>最新文章列表</h2></div>
						{if $spacerArts}
						<ul>
						{section name=spacerArt loop=$spacerArts}
							{if $smarty.section.spacerArt.iteration <= 3}
								<li {if $smarty.section.spacerArt.last}style="border-bottom:none;"{/if}>
									<div class="artdata_title"><a href="separticle.php?artid={$spacerArts[spacerArt].topics_id}">{$spacerArts[spacerArt].article_title}</a></div>
									<div class="artdata_description">{$spacerArts[spacerArt].article_content|truncate:500:'..':true}</div>
									<div class="artdata_info">
										<span class="fr"><a href="separticle.php?artid={$spacerArts[spacerArt].topics_id}">阅读全文</a></span>
										<i>{$spacerArts[spacerArt].article_time|date_format:'%Y-%m-%d %H:%M'}</i>
										<a href="separticle.php?artid={$spacerArts[spacerArt].topics_id}">阅读</a>({$spacerArts[spacerArt].visit_num}) ┊
										<a href="separticle.php?artid={$spacerArts[spacerArt].topics_id}#comment">评论</a>({$spacerArts[spacerArt].comments}) ┊
										<a class="todoCollect" href="#">收藏</a>({$spacerArts[spacerArt].collects})
										<input type="hidden" name="artid" value="{$spacerArts[spacerArt].topics_id}" />
										<input type="hidden" name="autor" value="{$someUser.user_name}" />
									</div>
								</li>
							{/if}
						{/section}
						</ul>
						{else}
						<p class="empty-content">抱歉，暂无文章哦!</p>
						{/if}
					</div>
					<!--最新文章列表end-->
				</div>
				<!--右侧end-->
				{/if}
				<!--详细动态-->
				{if $dynamic}
				<!--右侧start-->
				<div class="disArts">
					<!--最新动作start-->
					<div class="disArts_box newact">
						<div class="boxtop"><h2>最新动作</h2></div>
						{if $spacerArts || $myCollects || $myComments || $myspComments || $attenerArr ||$albums || $myalcomment}
						<ul>
							{section name=spacerArt loop=$spacerArts}
								<li>
									<div class="someart_info">
										<span class="title">{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}</span> 发表文章
										<a href="separticle.php?artid={$spacerArts[spacerArt].topics_id}">{$spacerArts[spacerArt].article_title}</a>
										<span class="feed-dateline">{$spacerArts[spacerArt].article_time|date_format:'%Y-%m-%d %H:%M'}</span>
									</div>
								</li>
							{/section}
							{section name=somecollect loop=$myCollects}
								<li>
									<div class="someart_info">
										<span class="title">{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}</span> 收藏了
										<a href="perspace.php?user={$myCollects[somecollect].user_name}">{$myCollects[somecollect].user_name}</a> 的文章-->
										<a href="separticle.php?artid={$myCollects[somecollect].topics_id}">{$myCollects[somecollect].article_title}</a>
										<span class="feed-dateline">{$myCollects[somecollect].collectTime|date_format:'%Y-%m-%d %H:%M'}</span>
									</div>
								</li>
							{/section}
							{section name=somecomment loop=$myComments}
								<li>
									<div class="someart_info">
										<span class="title">{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}</span> 评论了
										{if $someUser.user_name === $myComments[somecomment].user_name}
											<i class="title">自己</i>
										{else}
											<a href="perspace.php?user={$myComments[somecomment].user_name}">{$myComments[somecomment].user_name}</a>
										{/if}
										的文章-->
										<a href="separticle.php?artid={$myComments[somecomment].topics_id}">{$myComments[somecomment].article_title}</a>
										<span class="feed-dateline">{$myComments[somecomment].commentTime|date_format:'%Y-%m-%d %H:%M'}</span>
									</div>
									{if $myComments[somecomment].commentContent}
									<div class="someart_text">{$myComments[somecomment].commentContent|truncate:100:'..':true}</div>
									{/if}
								</li>
							{/section}
							{section name=somesp loop=$myspComments}
								<li>
									<div class="someart_info">
										<span class="title">{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}</span> 评论了
										{if $someUser.user_name === $myspComments[somesp].user_name}
											<i class="title">自己</i>
										{else}
											<a href="perspace.php?user={$myspComments[somesp].user_name}">{$myspComments[somesp].user_name}</a>
										{/if}
											的空间
										<span class="feed-dateline">{$myspComments[somesp].msgTime|date_format:'%Y-%m-%d %H:%M'}</span>
									</div>
									{if $myspComments[somesp].msgContent}
									<div class="someart_text">{$myspComments[somesp].msgContent|strip_tags|truncate:100:'..':true}</div>
									{/if}
								</li>
							{/section}
							{if $attenerArr}
							<li>
								<div class="someart_info">
									<span class="title">{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}</span> 关注了
									{section name=attener loop=$attenerArr}
										<a href="perspace.php?user={$attenerArr[attener]}">{$attenerArr[attener]}</a>
										{if !$smarty.section.attener.last}、{/if}
									{/section}
								</div>
							</li>
							{/if}
							{if $albums}
							<li>
								<div class="someart_info wb-clr">
									{section name=album loop=$albums}
										{if $albums[album].album_photos}
											<div class="dsside">
												<span class="title"><i>{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}</i> 在 <a href="album.php?user={$someUser.user_name}&act=look&id={$albums[album].album_id}">{$albums[album].album_name|truncate:10:'..':true}</a> 相册上传了</span>
												<span class="imgs">
												{section name=ph loop=$albums[album].album_photos}
													<a href="album.php?user={$someUser.user_name}&act=look&id={$albums[album].album_id}"><img data-lazyload-src="{$albums[album].album_photos[ph]}" src="images/lazyload.png" alt="图片" height="50" width="50" /></a>
												{/section}
												</span>
											</div>
										{/if}
									{/section}
								</div>
							</li>
							{/if}
							{if $myalcomment}
								<li>
									<div class="someart_info wb-clr">
										{section name=onealcomment loop=$myalcomment}
											<div class="dsside alcommentinfo">
												<span class="title"><i>{if $someUser.user_name === $now_user}我{else}{$someUser.user_name}{/if}</i> 评论了 {if $someUser.user_name === $myalcomment[onealcomment].album_owner}<i>自己</i>{else}<a href="perspace.php?user={$myalcomment[onealcomment].album_owner}">{$myalcomment[onealcomment].album_owner}</a>{/if} 的 <a href="album.php?user={$someUser.user_name}&act=look&id={$myalcomment[onealcomment].album_id}">{$myalcomment[onealcomment].album_name|truncate:10:'..':true}</a> 相册</span>
												<span class="imgs">
												{if $myalcomment[onealcomment]}
													{section name=somecomment loop=$myalcomment[onealcomment]}
														{if $myalcomment[onealcomment][somecomment].alcomment_text}
															<p>{$myalcomment[onealcomment][somecomment].alcomment_text}</p>
															<p>---{$myalcomment[onealcomment][somecomment].alcomment_time|date_format:'%Y-%m-%d %H:%M'}</p>
														{/if}
													{/section}
												{/if}
												</span>
											</div>
										{/section}
									</div>
								</li>
							{/if}
						</ul>
						{else}
						<p class="empty-content">抱歉，暂无最新哦!</p>
						{/if}
					</div>
					<!--最新动作end-->
				</div>
				{/if}
				<!--详细文章-->
				{if $morearts}
				<!--右侧start-->
				<div class="disArts">
					<!--最新文章列表start-->
					<div class="disArts_box">
						<div class="boxtop"><h2>最新文章列表</h2></div>
						{if $spacerArts}
						<ul>
						{section name=spacerArt loop=$spacerArts}
							<li {if $smarty.section.spacerArt.last}style="border-bottom:none;"{/if}>
								<div class="artdata_title"><a href="separticle.php?artid={$spacerArts[spacerArt].topics_id}">{$spacerArts[spacerArt].article_title}</a></div>
								<div class="artdata_description">{$spacerArts[spacerArt].article_content|truncate:500:'..':true}</div>
								<div class="artdata_info">
									<span class="fr"><a href="separticle.php?artid={$spacerArts[spacerArt].topics_id}">阅读全文</a></span>
									<i>{$spacerArts[spacerArt].article_time|date_format:'%Y-%m-%d %H:%M'}</i>
									<a href="separticle.php?artid={$spacerArts[spacerArt].topics_id}">阅读</a>({$spacerArts[spacerArt].visit_num}) ┊
									<a href="separticle.php?artid={$spacerArts[spacerArt].topics_id}#comment">评论</a>({$spacerArts[spacerArt].comments}) ┊
									<a class="todoCollect" href="#">收藏</a>({$spacerArts[spacerArt].collects})
									<input type="hidden" name="artid" value="{$spacerArts[spacerArt].topics_id}" />
									<input type="hidden" name="autor" value="{$someUser.user_name}" />
								</div>
							</li>
						{/section}
						</ul>
						{else}
						<p class="empty-content">抱歉，暂无文章哦!</p>
						{/if}
					</div>
					<!--最新文章列表end-->
				</div>
				{/if}
			</div>
{include file='spacefooter.tpl'}