{include file='header.tpl' title='首页'}
<div class="indexInner">
	<!--最新发表start-->
	<div class="newarts">
		<div class="irbox">
			<div class="irboxtop wb-clr">
				<h3><a href="#" type="tm" class="tm selected">时间排序</a><a href="#" type="vs" class="vs">访问量排序</a><a href="#" type="cm" class="cm">评论数排序</a></h3>
			</div>
			<div class="dis_by_order wb-clr">
			<!--默认按时间排序 start-->
			{if $recommendarts}
				{section name=someart loop=$recommendarts}
					<div class="sub-item">
						<div class="pic usertip-wrapper">
							<input type="hidden" value="{$recommendarts[someart].user_name}" class="ta-name" />
							<a href="perspace.php?user={$recommendarts[someart].user_name}" class="face"><img data-lazyload-src="{$recommendarts[someart].userphoto}" src="images/lazyload.png" alt="{$recommendarts[someart].user_name}" /></a>
							<a href="perspace.php?user={$recommendarts[someart].user_name}" class="back"><span><i>签名档:</i><br />{if $recommendarts[someart].signature}{$recommendarts[someart].signature}{else}这家伙没有签名档{/if}</span></a>
						</div>
						<div class="information">
							<div class="info_title"><a href="separticle.php?artid={$recommendarts[someart].topics_id}" title="{$recommendarts[someart].article_title}" target="_blank">{$recommendarts[someart].article_title}</a></div>
							<div class="info_text">{$recommendarts[someart].article_content|truncate:100:'..':true}</div>
							<div class="info_time">{$recommendarts[someart].arttime}</div>
						</div>
					</div>
				{/section}
				{if count($recommendarts) >= 9}
				<p class="gasket"></p>
				<a href="#" class="openmore" type="tm">点击展开更多</a>
				{/if}
			{else}
				<p class="empty-content">暂无文章</p>
			{/if}
			<!--默认按时间排序 end-->
			</div>
			<div class="dis_by_order wb-clr wb-hide"><p class="empty-content">正在努力加载中···</p></div>
			<div class="dis_by_order wb-clr wb-hide"><p class="empty-content">正在努力加载中···</p></div>
		</div>
	</div>
	<!--最新发表end-->
	<!--文章分类展示start-->
	<div class="categorys wb-clr">
		{section name=onecls loop=$cls_array}
			{if $cls_array[onecls].artinfo}
			<div class="somecls {if $smarty.section.onecls.iteration % 2 === 0}evencls{/if}">
				<a href="classart.php?act={$cls_array[onecls].arttype}" title="查看该分类下全部文章" class="clstip">{$cls_array[onecls].arttype}</a>
				<div class="arts">
					{section name=theart loop=$cls_array[onecls].artinfo}
						<p><i title="阅读{$cls_array[onecls].artinfo[theart].visit_num}/评论{$cls_array[onecls].artinfo[theart].comment_c}/收藏{$cls_array[onecls].artinfo[theart].collect_c}">{$cls_array[onecls].artinfo[theart].visit_num}/{$cls_array[onecls].artinfo[theart].comment_c}/{$cls_array[onecls].artinfo[theart].collect_c}</i><span><a href="separticle.php?artid={$cls_array[onecls].artinfo[theart].topics_id}" title="{$cls_array[onecls].artinfo[theart].article_title}" target="_blank">{$cls_array[onecls].artinfo[theart].article_title}</a></span></p>
					{/section}
				</div>
			</div>
			{/if}
		{/section}
		<!--资源分类start-->
		{if $cls_resource}
		<div class="somecls evencls">
			<a href="resource.php" title="查看全部资源" class="clstip">资源</a>
			<div class="arts">
				{section name=resource loop=$cls_resource}
					<p><i title="{$cls_resource[resource].filesize}/下载({$cls_resource[resource].downloads})">{$cls_resource[resource].filesize} / {$cls_resource[resource].downloads}</i><span><a href="loading.php?attachment_flag={$cls_resource[resource].attachment_flag}&attachment_id={$cls_resource[resource].attachment_id}&loadfile_path={$cls_resource[resource].filepath}" title="{$cls_resource[resource].filename}">{$cls_resource[resource].filename}</a></span></p>
				{/section}
			</div>
		</div>
		{/if}
		<!--资源分类end-->
	</div>
	<!--文章分类展示end-->
</div>
{include file='footer.tpl'}