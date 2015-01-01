<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">

	<channel>
		<title>web资料站</title>
		<link>{$domain}</link>
		<description>web资料站、web前端及后端资料、html、css、nodejs、javascript、jQuery、php等前沿技术研究总结</description>
		{if $items}
		{section name=some loop=$items}
		<item>
			<title>{$items[some].article_title}</title>
			<link>{$domain}separticle.php?artid={$items[some].topics_id}</link>
			<author>{$items[some].user_name}</author>
			<pubDate>{$items[some].article_time}</pubDate>
		</item>
		{/section}
		{/if}
	</channel>
</rss>