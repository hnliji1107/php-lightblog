{include file='header.tpl' title='发布成功'}
<div class="wrupWrapper">
	<div class="content">
		<h2>恭喜你，{if $wrsuccess}发表{/if}{if $upsuccess}修改{/if}文章成功！</h2>
		<div class="doarticlesuccess">
			<span class="title">你可以进行如下操作：</span>
			<a href="percenter.php?act=artmanage">管理我的文章</a>
			<a href="updarticles.php?artid={$artid}&act=up">重新修改</a>
			<a href="percenter.php?act=is_posttxt">继续发布新文章</a>
			<a target="_blank" href="separticle.php?artid={$artid}">浏览该文章</a>
		</div>
	</div>
</div>
{include file='footer.tpl'}

