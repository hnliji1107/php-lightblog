{include file='spaceheader.tpl' title=$title}
			<div class="guestbookInner">
				<!--右侧start-->
				<div class="disArts">
					<div class="disArts_box">
						<div class="boxtop"><h2>我的留言板</h2></div>
						<div class="bookcomment" id="comment">
							<form action="guestbook.php?user={$someUser.user_name}" method="post">
							<input type="hidden" name="userid" value="{$someUser.user_id}" />
							<textarea cols="82" rows="5" class="textarea_type gbcomment_text" name="gbcomment_text"></textarea>
							<div class="submitLine wb-clr">
								<button type="submit" class="persubmit_btn submit_msg">提交留言</button>
							</div>
							</form>
						</div>
						<!--评论start-->
						<div class="commentlist gsbCommentList">
							<ul>
								{if $msgs}
								{section name=info loop=$msgs}
								<li class="item wb-clr">
									<div class="user_photo usertip-wrapper">
										<input type="hidden" value="{$msgs[info].user_name}" class="ta-name" />
										<a href="perspace.php?user={$msgs[info].user_name}"><img data-lazyload-src="{$msgs[info].userphoto}" src="images/lazyload.png" alt="{$msgs[info].user_name}" /></a>
										<em>{$msgs[info].user_name|truncate:9:'..':true}</em>
									</div>
									<div class="user_text">
										<p class="uinfo">
											{$msgs[info].msg_time|date_format:'%m-%d %H:%M'}
											{if $msgs[info].commenter_os && $msgs[info].commenter_browser}
											/ {$msgs[info].commenter_os} / {$msgs[info].commenter_browser}
											{/if}
										</p>
										<p class="quote_txt">{$msgs[info].msg_text}</p>
										<input type="hidden" value="{$msgs[info].msg_id}" class="associd" />
										<div class="reply_to_user">
											<!--回复start-->
											{if $msgs_replys[{$msgs[info].msg_id}]}
												{section name=some loop=$msgs_replys[{$msgs[info].msg_id}]}
													<div class="replys wb-clr"{if $smarty.section.some.first}style="border-top:none;"{/if}>
														<div class="re_user_photo usertip-wrapper">
															<input type="hidden" value="{$msgs_replys[{$msgs[info].msg_id}][some].replyname}" class="ta-name" />
															<img data-lazyload-src="{$msgs_replys[{$msgs[info].msg_id}][some].replyphoto}" src="images/lazyload.png" alt="{$msgs_replys[{$msgs[info].msg_id}][some].replyname}" title="{$msgs_replys[{$msgs[info].msg_id}][some].replyname}" />
														</div>
														<div class="re_user_info">
															<p class="nametime">
																<span style="color:#369;">{$msgs_replys[{$msgs[info].msg_id}][some].replyname}</span> {$msgs_replys[{$msgs[info].msg_id}][some].replytime|date_format:'%m-%d %H:%M'}{if $msgs_replys[{$msgs[info].msg_id}][some].commenter_os && $msgs_replys[{$msgs[info].msg_id}][some].commenter_browser} / {$msgs_replys[{$msgs[info].msg_id}][some].commenter_os} / {$msgs_replys[{$msgs[info].msg_id}][some].commenter_browser}{/if}
															</p>
															<p>{$msgs_replys[{$msgs[info].msg_id}][some].replytext}</p>
														</div>
													</div>
												{/section}
											{/if}
											<!--回复end-->
										</div>
										{if $msgs[info].signature}
										<p class="signp">ta的签名：{$msgs[info].signature}</p>
										{/if}
									</div>
									<div class="user_reply"><a href="#" class="replay">回复</a></div>
								</li>
								{/section}
								{else}
									<p class="empty-content">暂无留言</p>
								{/if}
							</ul>
						</div>
						<!--评论end-->
					</div>
				</div>
				<!--右侧end-->
			</div>
{include file='spacefooter.tpl'}