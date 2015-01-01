{include file='header.tpl' title=$title}
<div class="classartInner">
		<!--左侧部分start-->
		<div class="categorylist">
			{if $search}
				<div class="artListTitle">搜索结果(<i class="dighighline">{$someCategory[0].totalCount|default:'0'}</i>)</div>
			{else}
				<div class="artListTitle"><i>{$title}</i> 文章列表(<i>{$someCategory[0].totalCount|default:'0'}</i>)</div>
			{/if}
			<ul data-totalcount="{$someCategory[0].totalCount|default:'0'}">
				{if $someCategory}
					{section name=somecate loop=$someCategory}
						<li {if $search && $smarty.section.somecate.last}class="no-bottom-border"{/if}>
							<div class="art_info">
								<div class="arttitle"><a href="separticle.php?artid={$someCategory[somecate].topics_id}" target="_blank">{$someCategory[somecate].article_title}</a></div>
								<div class="theArtInfo">{$someCategory[somecate].article_time} / 阅读({$someCategory[somecate].visit_num})，评论({$someCategory[somecate].commentnum|default:0})，收藏({$someCategory[somecate].collectnum|default:0}) / 所属栏目：{$someCategory[somecate].article_type} / 作者：{$someCategory[somecate].user_name}</div>
								<div class="description">{$someCategory[somecate].article_content}</div>
								<div class="readmore"><a target="_blank" href="separticle.php?artid={$someCategory[somecate].topics_id}">Read More »</a></div>
							</div>
						</li>
					{/section}
				{else}
					{if $search}
						<p class="empty-content">抱歉，没有符合条件的结果。</p>
					{/if}
				{/if}
			</ul>
			{if !$search}
			<div class="getMoreLine">正在加载更多数据，请稍后...</div>
			{/if}
		</div>
		<!--左侧部分end-->
	</div>
{include file='footer.tpl'}