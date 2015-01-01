;(function($, wind, undefined) {
	//缓存全局变量
	var jwind = $(wind);
	var carouselEffect = {
		init: function() {
			//操作容器
			this.carousel = $('.carousel');
			this.ul = this.carousel.find('ul');
			//列表项内容宽度自适应
			this.styleInit();
			//事件绑定集
			this.event();
		},
		//列表项内容宽度自适应
		styleInit: function() {
			this.ul.each(function(i, el) {
				var ul = $(el), item = ul.find('li'), itemCount = item.length, itemWidth = jwind.width();
				//设置列表项父层容器的宽度
				ul.css('width', itemCount*itemWidth);
				//设置列表项内容的宽度
				item.css('width', itemWidth);
			});
		},
		//事件绑定集
		event: function() {
			var that = this;
			//窗口大小改变时列表项内容宽度自适应
			jwind.on('resize.th', function() {
				if (wind.timeid) clearTimeout(wind.timeid);
				wind.timeid = setTimeout($.proxy(that.styleInit, that), 1000/60);
			});
			//绑定触摸事件
			this.carousel.on('touchstart', 'li', touchstart);
			this.carousel.on('touchmove', 'li', touchmove);
			this.carousel.on('touchend', 'li', touchend);
			//绑定滚动条插件
			window.scroller = new iScroll('inner', {vScrollbar: true, onScrollEnd: scrollend});
		}
	};

	$(function() {
		carouselEffect.init();
	})

	//预定义常用的变量
	var touch, startx, cachex, cacheulx, ul, ulx, endx, diffx;

	//触摸开始
	function touchstart(ev) {
		touch = ev.originalEvent.touches[0];
		cachex = startx = touch.clientX;
		cacheulx = parseInt($(this).parent('ul').css('margin-left'));
	}

	//触摸移动
	function touchmove(ev) {
		//阻止浏览器的默认拖动行为
		ev.preventDefault();
		//计算移动距离
		touch = ev.originalEvent.touches[0];
		endx = touch.clientX;
		diffx = endx - startx;
		ulx = parseInt((ul=$(this).parent('ul')).css('margin-left'));
		startx = endx;
		//移动对象
		ul.css('margin-left', ulx+diffx);
	}

	//触摸结束
	function touchend(ev) {
		var resultx, boundvalue = 50, itemCount = (ul=$(this).parent('ul')).find('li').length, itemWidth = jwind.width();
		//手指抬起时横坐标
		endx = ev.originalEvent.changedTouches[0].clientX;
		//向左滑动
		if (cachex > endx) {
			//计算下一项的位置
			resultx = cacheulx % itemWidth === 0 ? cacheulx-itemWidth : itemWidth;
			resultx = resultx < -(itemCount-1)*itemWidth ? -(itemCount-1)*itemWidth : resultx;
			//滑动一定距离则切换到下一项
			if (cachex - endx > boundvalue) {
				ul.animate({'margin-left': resultx}, 'fast', function() {
					//滑动完毕读取的距离才可信
					cacheulx = parseInt(ul.css('margin-left'));
				});
			}
			//未滑动一定距离则返回到原来位置
			else {
				ul.animate({'margin-left': cacheulx}, 'fast', function() {
					//滑动完毕读取的距离才可信
					cacheulx = parseInt(ul.css('margin-left'));
				});
			}
		}
		//向右滑动
		else {
			//计算下一项的位置
			resultx = cacheulx % itemWidth === 0 ? cacheulx+itemWidth : itemWidth;
			resultx = resultx > 0  ? 0 : resultx;
			//滑动一定距离则切换到下一项
			if (endx - cachex > boundvalue) {
				ul.animate({'margin-left': resultx}, 'fast', function() {
					//滑动完毕读取的距离才可信
					cacheulx = parseInt(ul.css('margin-left'));
				});
			}
			//未滑动一定距离则返回到原来位置
			else {
				ul.animate({'margin-left': cacheulx}, 'fast', function() {
					//滑动完毕读取的距离才可信
					cacheulx = parseInt(ul.css('margin-left'));
				});
			}
		}
	}

	//滚动条滚动结束
	function scrollend() {
		var jwrapper = $(this.wrapper), viewHeight = this.wrapperH, pageHeight = this.scrollerH, scrollbarTop = this.y;
		//滚动条滚到底部时加载更多数据
		if (viewHeight-scrollbarTop >= pageHeight) {
			//保证服务端数据变化时提示语正确
			jwrapper.find('.loading').text('正在加载，请稍后···');
			//用于插入的html片段
			var liFragment = '<li>\
						<div class="img mb-left">\
							<a href="#" title=""><img src="d.png" alt="" /></a>\
						</div>\
						<div class="info mb-left">\
							<h2>大武林</h2>\
							<p class="author">四时风雨 著</p>\
							<p class="desc">逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派</p>\
						</div>\
					</li>\
					<li>\
						<div class="img mb-left">\
							<a href="#" title=""><img src="d.png" alt="" /></a>\
						</div>\
						<div class="info mb-left">\
							<h2>大武林</h2>\
							<p class="author">四时风雨 著</p>\
							<p class="desc">逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派</p>\
						</div>\
					</li>\
					<li>\
						<div class="img mb-left">\
							<a href="#" title=""><img src="d.png" alt="" /></a>\
						</div>\
						<div class="info mb-left">\
							<h2>大武林</h2>\
							<p class="author">四时风雨 著</p>\
							<p class="desc">逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派</p>\
						</div>\
					</li>\
					<li>\
						<div class="img mb-left">\
							<a href="#" title=""><img src="d.png" alt="" /></a>\
						</div>\
						<div class="info mb-left">\
							<h2>大武林</h2>\
							<p class="author">四时风雨 著</p>\
							<p class="desc">逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派逍遥派</p>\
						</div>\
					</li>';
			//插入加载内容
			jwrapper.find('ul').append(liFragment);
			//内容改变后重新渲染滚动区
			scroller.refresh();
		}
	}
})(jQuery, window);