{include file='header.tpl' title='新闻'}
<div class="newsInner">
	<div class="main_title">
		<ul class="fx wb-clr">
			<li class="on" data-cachecontent='{section name=new loop=$news}
						<div class="onenew xzwidth{if $smarty.section.new.last} no-bottom-border{/if}">
							<h3><a href="{$news[new].link}" target="_blank">{$news[new].title}</a></h3>
							<p>{$news[new].description} <a href="{$news[new].link}" target="_blank">--- 详细</a></p>
							<p class="newtime">--- {$news[new].pubDate}</p>
						</div>
					{/section}'><a title="新闻频道">新闻频道</a></li>
			<li><a title="娱乐频道">娱乐频道</a></li>
			<li><a title="体育频道">体育频道</a></li>
			<li><a title="女性频道">女性频道</a></li>
			<li><a title="科技频道">科技频道</a></li>
			<li><a title="游戏频道">游戏频道</a></li>
			<li><a title="教育频道">教育频道</a></li>
			<li><a title="读书频道">读书频道</a></li>
			<li><a title="时尚频道">时尚频道</a></li>
			<li><a title="笑话频道">笑话频道</a></li>
		</ul>
	</div>
	<div class="newsdetail wb-clr">
		<!--新闻频道-->
		<div class="classlist">
			<table width="100%">
				<tbody>
					<tr><td><a href="http://news.qq.com/newsgn/rss_newsgn.xml" title="国内新闻" class="selected" data-cachecontent='{section name=new loop=$news}
						<div class="onenew xzwidth{if $smarty.section.new.last} no-bottom-border{/if}">
							<h3><a href="{$news[new].link}" target="_blank">{$news[new].title}</a></h3>
							<p>{$news[new].description} <a href="{$news[new].link}" target="_blank">--- 详细</a></p>
							<p class="newtime">--- {$news[new].pubDate}</p>
						</div>
					{/section}'>国内新闻</a></td></tr>
					<tr><td><a href="http://news.qq.com/newsgj/rss_newswj.xml" title="国际新闻">国际新闻</a></td></tr>
					<tr><td><a href="http://news.qq.com/newssh/rss_newssh.xml" tilte="社会新闻">社会新闻</a></td></tr>
					<tr><td><a href="http://news.qq.com/photon/rss_photo.xml" title="图片站">图片站</a></td></tr>
					<tr><td><a href="http://news.qq.com/newscomments/rss_comment.xml" title="评论站">评论站</a></td></tr>
					<tr><td><a href="http://news.qq.com/milite/rss_milit.xml" title="军事站">军事站</a></td></tr>
					<tr><td><a href="http://news.qq.com/histor/rss_history.xml" title="史海钩沉">史海钩沉</a></td></tr>
					<tr><td><a href="http://news.qq.com/xwyl/rss_xwyl.xml" title="新闻语录">新闻语录</a></td></tr>
					<tr><td><a href="http://news.qq.com/lianxian/rss_lx.xml" title="QQ连线">QQ连线</a></td></tr>
					<tr><td><a href="http://news.qq.com/szsh/rss_szsh.xml" title="数字说话">数字说话</a></td></tr>
					<tr><td><a href="http://news.qq.com/nehemu/rss_nmhm.xml" title="内幕黑幕">内幕黑幕</a></td></tr>
					<tr><td><a href="http://news.qq.com/person/rss_person.xml" title="人物站">人物站</a></td></tr>
					<tr><td><a href="http://news.qq.com/zmdnew/rss_now.xml" title="即时消息">即时消息</a></td></tr>
					<tr><td><a href="http://news.qq.com/bj/rss_bj.xml" title="地方站-北京">地方站-北京</a></td></tr>
					<tr><td><a href="http://news.qq.com/sh/rss_sh.xml" title="地方站-上海">地方站-上海</a></td></tr>
					<tr><td><a href="http://news.qq.com/gd/rss_gd.xml" title="地方站-广东">地方站-广东</a></td></tr>
					<tr><td><a href="http://news.qq.com/zj/rss_zj.xml" title="地方站-浙江">地方站-浙江</a></td></tr>
					<tr><td><a href="http://news.qq.com/js/rss_js.xml" title="地方站-江苏">地方站-江苏</a></td></tr>
					<tr><td><a href="http://news.qq.com/sd/rss_sd.xml" title="地方站-山东">地方站-山东</a></td></tr>
				</tbody>
			</table>
		</div>
		<!--娱乐频道-->
		<div class="classlist wb-hide">
			<table width="100%">
				<tbody>
					<tr><td><a href="http://ent.qq.com/movie/rss_movie.xml" title="电影">电影</a></td></tr>
					<tr><td><a href="http://ent.qq.com/tv/rss_tv.xml" title="电视">电视</a></td></tr>
					<tr><td><a href="http://ent.qq.com/newxw/rss_start.xml" title="明星">明星</a></td></tr>
					<tr><td><a href="http://ent.qq.com/m_news/rss_yinyue.xml" title="音乐">音乐</a></td></tr>
					<tr><td><a href="http://ent.qq.com/entpic/rss_entpic.xml" title="图片站">图片站</a></td></tr>
					<tr><td><a href="http://ent.qq.com/chatroom/chatnews/rss_starchat.xml" title="名人坊">名人坊</a></td></tr>
				</tbody>
			</table>
		</div>
		<!--体育频道-->
		<div class="classlist wb-hide">
			<table width="100%">
				<tbody>
					<tr><td><a href="http://sports.qq.com/basket/rss_basket.xml" title="篮球">篮球</a></td></tr>
					<tr><td><a href="http://sports.qq.com/basket/nba/nbarep/rss_nbarep.xml" title="NBA热点新闻">NBA热点新闻</a></td></tr>
					<tr><td><a href="http://sports.qq.com/basket/bskb/cba/rss_cba.xml" title="CBA热点新闻">CBA热点新闻</a></td></tr>
					<tr><td><a href="http://sports.qq.com/basket/nba/nbarep/yaoming0607/rss_rockets.xml" title="火箭新闻">火箭新闻</a></td></tr>
					<tr><td><a href="http://sports.qq.com/basket/nba/nbarep/yaoming0607/yaoming0809/rss_yaoming.xml" title="姚明新闻">姚明新闻</a></td></tr>
					<tr><td><a href="http://sports.qq.com/isocce/rss_isocce.xml" title="国际足球">国际足球</a></td></tr>
					<tr><td><a href="http://sports.qq.com/isocce/yingc/rss_pl.xml" title="英超联赛">英超联赛</a></td></tr>
					<tr><td><a href="http://sports.qq.com/isocce/yijia/rss_sereasa.xml" title="意甲联赛">意甲联赛</a></td></tr>
					<tr><td><a href="http://sports.qq.com/isocce/xijia/rss_laliga.xml" title="西甲联赛">西甲联赛</a></td></tr>
					<tr><td><a href="http://sports.qq.com/csocce/rss_csocce.xml" title="中国足球">中国足球</a></td></tr>
					<tr><td><a href="http://sports.qq.com/csocce/2011preview/rss_guozu.xml" title="国足精彩要闻">国足精彩要闻</a></td></tr>
					<tr><td><a href="http://sports.qq.com/csocce/jiaa/rss_zc.xml" title="中超体育要闻">中超体育要闻</a></td></tr>
					<tr><td><a href="http://sports.qq.com/others/rss_others.xml" title="综合体育">综合体育</a></td></tr>
					<tr><td><a href="http://sports.qq.com/tennis/rss_tennis.xml" title="腾讯网球">腾讯网球</a></td></tr>
					<tr><td><a href="http://sports.qq.com/f1/rss_f1.xml" title="腾讯赛车">腾讯赛车</a></td></tr>
					<tr><td><a href="http://sports.qq.com/others/tianj/tjnews/liuxiangnews/rss_liuxiang.xml" title="刘翔新闻">刘翔新闻</a></td></tr>
					<tr><td><a href="http://sports.qq.com/photo/rss_photo.xml" title="图片站">图片站</a></td></tr>
					<tr><td><a href="http://sports.qq.com/photo/meinvhuabian/rss_tp1.xml" title="美女花边_体育要闻">美女花边_体育要闻</a></td></tr>
					<tr><td><a href="http://sports.qq.com/photo/lace/rss_tp2.xml" title="八卦绯闻_体育要闻">八卦绯闻_体育要闻</a></td></tr>
					<tr><td><a href="http://sports.qq.com/photo/gaoqing/rss_tp3.xml" title="高清大图_体育要闻">高清大图_体育要闻</a></td></tr>
				</tbody>
			</table>
		</div>
		<!--女性频道-->
		<div class="classlist wb-hide">
			<table width="100%">
				<tbody>
					<tr><td><a href="http://lady.qq.com/qqstar/rss_qqstart.xml" title="星工厂">星工厂</a></td></tr>
					<tr><td><a href="http://lady.qq.com/video/rss_video.xml" title="视频直播">视频直播</a></td></tr>
					<tr><td><a href="http://lady.qq.com/diet/rss_diet.xml" title="瘦身减肥">瘦身减肥</a></td></tr>
					<tr><td><a href="http://lady.qq.com/baby/rss_baby.xml" title="育儿频道">育儿频道</a></td></tr>
					<tr><td><a href="http://lady.qq.com/vogue/rss_vogue.xml" title="流行风尚">流行风尚</a></td></tr>
					<tr><td><a href="http://lady.qq.com/style/rss_dress.xml" title="服饰潮流">服饰潮流</a></td></tr>
					<tr><td><a href="http://lady.qq.com/beauty/rss_beauty.xml" title="美容美体">美容美体</a></td></tr>
					<tr><td><a href="http://lady.qq.com/vision/rss_vision.xml" title="视觉写真">视觉写真</a></td></tr>
					<tr><td><a href="http://lady.qq.com/emo/rss_emo.xml" title="情感心绪">情感心绪</a></td></tr>
					<tr><td><a href="http://lady.qq.com/sex/rss_sex.xml" title="两性健康">两性健康</a></td></tr>
					<tr><td><a href="http://lady.qq.com/8g/rss_8g.xml" title="明星八卦">明星八卦</a></td></tr>
					<tr><td><a href="http://lady.qq.com/zhuant/rss_zhuanti.xml" title="专题">专题</a></td></tr>
					<tr><td><a href="http://lady.qq.com/liaotian/rss_liaotian.xml" title="访谈聊天">访谈聊天</a></td></tr>
				</tbody>
			</table>
		</div>
		<!--科技频道-->
		<div class="classlist wb-hide">
			<table width="100%">
				<tbody>
					<tr><td><a href="http://tech.qq.com/web/rss_web.xml" title="互联网">互联网</a></td></tr>
					<tr><td><a href="http://tech.qq.com/tele/rss_tele.xml" title="电信">电信</a></td></tr>
					<tr><td><a href="http://tech.qq.com/3Gworld/rss_3Gworld.xml" title="3G">3G</a></td></tr>
					<tr><td><a href="http://tech.qq.com/it/rss_it.xml" title="业界">业界</a></td></tr>
					<tr><td><a href="http://tech.qq.com/photo/rss_photo.xml" title="图吧">图吧</a></td></tr>
					<tr><td><a href="http://tech.qq.com/zt/rss_zt.xml" title="专题">专题</a></td></tr>
					<tr><td><a href="http://tech.qq.com/ITcw/rss_ITcw.xml" title="传闻">传闻</a></td></tr>
					<tr><td><a href="http://tech.qq.com/interview/rss_interview.xml" title="名人在线">名人在线</a></td></tr>
					<tr><td><a href="http://tech.qq.com/yuanshifangtan/rss_yuanshifangtan.xml" title="院士访谈">院士访谈</a></td></tr>
					<tr><td><a href="http://tech.qq.com/ceoclub/rss_ceoclub.xml" title="总裁俱乐部">总裁俱乐部</a></td></tr>
					<tr><td><a href="http://tech.qq.com/itlife/rss_itlife.xml" title="IT吧">IT吧</a></td></tr>
				</tbody>
			</table>
		</div>
		<!--游戏频道-->
		<div class="classlist wb-hide">
			<table width="100%">
				<tbody>
					<tr><td><a href="http://games.qq.com/ntgame/rss_ntgame.xml" title="网络游戏">网络游戏</a></td></tr>
					<tr><td><a href="http://games.qq.com/mini/rss_mini.xml" title="小游戏">小游戏</a></td></tr>
					<tr><td><a href="http://games.qq.com/mobile/rss_mobile.xml" title="手机游戏">手机游戏</a></td></tr>
					<tr><td><a href="http://games.qq.com/radio/rss_radio.xml" title="游戏电台">游戏电台</a></td></tr>
					<tr><td><a href="http://games.qq.com/pic/rss_pic.xml" title="游戏图吧">游戏图吧</a></td></tr>
					<tr><td><a href="http://games.qq.com/tencent/rss_tencent.xml" title="腾讯游戏">腾讯游戏</a></td></tr>
					<tr><td><a href="http://games.qq.com/pcgame/rss_pcgame.xml" title="电脑游戏">电脑游戏</a></td></tr>
					<tr><td><a href="http://games.qq.com/tvgame/rss_tvgame.xml" title="电视游戏">电视游戏</a></td></tr>
					<tr><td><a href="http://games.qq.com/cyfw/rss_cyfw.xml" title="产业服务">产业服务</a></td></tr>
					<tr><td><a href="http://games.qq.com/downlo/rss_download.xml" title="下载天地">下载天地</a></td></tr>
					<tr><td><a href="http://games.qq.com/bbs/rss_bbs.xml" title="游戏论坛">游戏论坛</a></td></tr>
					<tr><td><a href="http://games.qq.com/gamedatalib/rss_gamedatalib.xml" title="游戏产品库">游戏产品库</a></td></tr>
					<tr><td><a href="http://games.qq.com/gamezt/rss_gamezt.xml" title="游戏专题">游戏专题</a></td></tr>
					<tr><td><a href="http://games.qq.com/gamevideo/rss_gamevideo.xml" title="游戏视频">游戏视频</a></td></tr>
					<tr><td><a href="http://games.qq.com/battle/rss_battle.xml" title="战网平台">战网平台</a></td></tr>
					<tr><td><a href="http://games.qq.com/guide/rss_guide.xml" title="攻略秘籍">攻略秘籍</a></td></tr>
					<tr><td><a href="http://games.qq.com/gamehall/rss_gamehall.xml" title="QQ游戏">QQ游戏</a></td></tr>
				</tbody>
			</table>
		</div>
		<!--教育频道-->
		<div class="classlist wb-hide">
			<table width="100%">
				<tbody>
					<tr><td><a href="http://edu.qq.com/edunew/rss_edunew.xml" title="教育新闻">教育新闻</a></td></tr>
					<tr><td><a href="http://edu.qq.com/gaokao/rss_gaokao.xml" title="高考">高考</a></td></tr>
					<tr><td><a href="http://edu.qq.com/abroad/rss_abroad.xml" title="出国">出国</a></td></tr>
					<tr><td><a href="http://edu.qq.com/y/ynews/rss_kaoyan.xml" title="考研">考研</a></td></tr>
					<tr><td><a href="http://edu.qq.com/official/rss_official.xml" title="公务员">公务员</a></td></tr>
					<tr><td><a href="http://edu.qq.com/kszx/rss_zsks.xml" title="考试">考试</a></td></tr>
					<tr><td><a href="http://edu.qq.com/en/rss_en.xml" title="外语">外语</a></td></tr>
					<tr><td><a href="http://edu.qq.com/jjxy/rss_jjxy.xml" title="校园">校园</a></td></tr>
					<tr><td><a href="http://edu.qq.com/photo/rss_photo.xml" title="图片站">图片站</a></td></tr>
				</tbody>
			</table>
		</div>
		<!--读书频道-->
		<div class="classlist wb-hide">
			<table width="100%">
				<tbody>
					<tr><td><a href="http://book.qq.com/origin/rss_origin.xml" title="原创">原创</a></td></tr>
					<tr><td><a href="http://book.qq.com/y/rss_qcb.xml" title="青春版">青春版</a></td></tr>
					<tr><td><a href="http://book.qq.com/whzy/rss_whzy.xml" title="文化阵营">文化阵营</a></td></tr>
					<tr><td><a href="http://book.qq.com/bookpic/rss_bookpic.xml" title="视觉阅读">视觉阅读</a></td></tr>
				</tbody>
			</table>
		</div>
		<!--时尚频道-->
		<div class="classlist wb-hide">
			<table width="100%">
				<tbody>
					<tr><td><a href="http://luxury.qq.com/staff/rss_staff.xml" title="奢华前沿">奢华前沿</a></td></tr>
					<tr><td><a href="http://luxury.qq.com/life/rss_life.xml" title="名流生活">名流生活</a></td></tr>
					<tr><td><a href="http://luxury.qq.com/pinwei/rss_pinwei.xml" title="优品人生">优品人生</a></td></tr>
					<tr><td><a href="http://luxury.qq.com/pic/rss_pic.xml" title="图片站">图片站</a></td></tr>
				</tbody>
			</table>
		</div>
		<!--笑话频道-->
		<div class="classlist wb-hide">
			<table width="100%">
				<tbody>
					<tr><td><a href="http://joke.qq.com/jokevideo/rss_video.xml" title="搞笑视频">搞笑视频</a></td></tr>
					<tr><td><a href="http://joke.qq.com/story/rss_story.xml" title="故事长廊">故事长廊</a></td></tr>
					<tr><td><a href="http://joke.qq.com/qiwenyishi/rss_qwys.xml" title="奇闻轶事">奇闻轶事</a></td></tr>
					<tr><td><a href="http://joke.qq.com/pic/rss_pic.xml" title="搞笑图片">搞笑图片</a></td></tr>
					<tr><td><a href="http://joke.qq.com/net/rss_net.xml" title="无厘网文">无厘网文</a></td></tr>
					<tr><td><a href="http://joke.qq.com/fool/rss_fool.xml" title="愚人愚己">愚人愚己</a></td></tr>
					<tr><td><a href="http://joke.qq.com/adult/rss_adult.xml" title="成人专区">成人专区</a></td></tr>
				</tbody>
			</table>
		</div>
		<!--新闻模块start-->
		<div class="newstext">
			<div class="artList">
				<h2>新闻频道 - 国内新闻</h2>
				<div class="news_toggle">
					{section name=new loop=$news}
						<div class="onenew xzwidth{if $smarty.section.new.last} no-bottom-border{/if}">
							<h3><a href="{$news[new].link}" target="_blank">{$news[new].title}</a></h3>
							<p>{$news[new].description} <a href="{$news[new].link}" target="_blank">--- 详细</a></p>
							<p class="newtime">--- {$news[new].pubDate}</p>
						</div>
					{/section}
				</div>
			</div>
		</div>
		<!--新闻模块end-->
	</div>
</div>
{include file='footer.tpl'}