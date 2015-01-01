;(function(wind, doc, undefined) {
	var mobileFeature = {
		init: function() {
			this.orientationchange();
		},
		//手机翻转检测
		orientationchange: function() {
			var wrap = doc.getElementsByTagName('p')[0];
			if (!isSupported('orientationchange')) {
				text = '该设备不支持orientationchange事件';
				wrap.innerHTML = text;
				return;
			}
			wrap.innerHTML =  'Current orientation is ' + getTextByOrientation(wind.orientation);
			wind.addEventListener('orientationchange', function() {
				wrap.innerHTML = 'Current orientation is ' + getTextByOrientation(wind.orientation);
			}, false);
		}
	};

	doc.addEventListener('DOMContentLoaded', function() {
		mobileFeature.init();
	}, false);

	//根据手机翻转角度输出对应提示语
	function getTextByOrientation(orientation) {
		var text = '';
		switch(orientation) {
			case 0: {
				text = '你的手机处于正常的竖屏状态（0度）！';
			}
			break;
			case -90: {
				text = '你的手机横屏了（顺时针90度）！';
			}
			break;
			case 90: {
				text = '你的手机横屏了（逆时针90度）！';
			}
			break;
			case 180: {
				text = '你的手机反着拿了（180度）！';
			}
			break;
			default: 
			break;
		}
		return text;
	}

	//检测设备是否支持某个属性
	function isSupported(property) {
		return isSupported = ('on'+property in wind) && (doc.documentMode === undefined || doc.documentMode > 7);
	}
})(this, document);