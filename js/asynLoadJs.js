/*
    file: asynLoadJs.js
    create: 2012-11-10
    author: lishijun
    功能: 异步加载JS
*/

;(function(win, doc, undefined) {
    //异步并行加载、串行执行js
    function asynLoadJs(srcArr, callback) {
        if (Object.prototype.toString.call(srcArr) !== '[object Array]') return;
        var script, i = 0, completeCount = 0, len = srcArr.length, isCallback = callback && typeof callback === "function";

        //遍历请求队列
        for (; i<len; i++) {
            (function(i) {
                script = doc.createElement("script");
                script.type = "text/javascript";
                //IE
                if (script.readyState) {
                    script.onreadystatechange = function() {
                        if (script.readyState === "loaded" || script.readyState === "complete") {
                            completeCount++;
                            if (completeCount === len && isCallback) {
                                callback();
                            }
                        }
                    };
                }
                //W3C
                else {
                    script.onload = function() {
                        completeCount++;
                        if (completeCount === len && isCallback) {
                            callback();
                        }
                    };
                }

                script.src = srcArr[i];
                doc.getElementsByTagName("head")[0].appendChild(script);
            })(i);
        }
    }

    //对外接口
    return {
        init: function() {
            //对外开放的接口
            win.asynLoadJs = asynLoadJs;
            //模拟ie8及以下媒体查询、min、max功能
            asynLoadJs(["js/compressor/modernizr.min.js"], function() {
                Modernizr.load({
                    test: Modernizr.mq("only all"),
                    nope: ["js/compressor/respond.min.js"]
                });
            });
            //主要功能JS
            asynLoadJs(["js/compressor/jquery.min.js"], function() {
                asynLoadJs(["js/compressor/main.min.js"]);
            });
        }
    };
})(window, document).init();