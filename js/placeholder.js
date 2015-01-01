/*
    file: placeholder.js
    create: 2013-9-22
    author: lishijun (928990115@qq.com)
    功能: 模拟html5的placeholder功能
    参数: {
        shield: false, // Boolean类型，设置为true，表示所有浏览器都使用该组件模拟；默认为false，只在不支持placeholder属性的浏览器中使用
        color: "#A9A9A9", // String类型，用来设置中间层中的提示语字体颜色，默认为#A9A9A9
        //zIndex
        zIndex: 1, // Number类型，用来设置中间层垂直高度，默认为1
    },
    使用示例: 
    $.placeholder.init({
        shield: true,
        elemArr: [document.getElementsByTagName('placeholder'))] //需要作用的对象
    });
*/

;(function($, win, doc, undefined) {
    var 
        //body对象
        bdy = doc.body,
        //jQuery包装的window
        $win = $(win),
        //jQuery包装的document
        $doc = $(doc),
        //jQuery包装的body
        $bdy = $(bdy),
        //配置项
        placeholder = {
            options: {
                shield: false,
                color: "#A9A9A9",
                zIndex: 1
            },
            init: function(config) {
                var newConfig = $.extend({}, this.options, config);

                initSetPos(newConfig, false);
            },
            reset: function(config) {
                var newConfig = $.extend({}, this.options, config);

                initSetPos(newConfig, true);
            }
        };

    function initSetPos(config, reset) {
        if (!config.shield) {
            var input = doc.createElement("input");

            if ("placeholder" in input) return;
        }

        for (var i=0, count=config.elemArr.length; i<count; i++) {
            var 
                $e = $(config.elemArr[i]),
                nodeName = $e[0].nodeName.toLowerCase(),
                $tip;

            if($e.val() === "") {
                var 
                    borderTop = parseInt($e.css("border-top-width")),
                    borderRight = parseInt($e.css("border-right-width")),
                    borderBottom = parseInt($e.css("border-bottom-width")),
                    borderLeft = parseInt($e.css("border-left-width")),

                    offset = $e[0].getBoundingClientRect(),
                    top = offset.top+$win.scrollTop(),
                    left = offset.left+borderLeft+$win.scrollLeft(),

                    paddingTop = parseInt($e.css("padding-top")),
                    paddingRight = parseInt($e.css("padding-right")),
                    paddingBottom = parseInt($e.css("padding-bottom")),
                    paddingLeft = parseInt($e.css("padding-left")),

                    width = $e.outerWidth()-paddingLeft-paddingRight-borderLeft-borderRight,
                    height = $e.outerHeight()-paddingTop-paddingBottom,

                    lineHeight = $e.css("line-height"),
                    fontSize = $e.css("font-size"),
                    fontFamily = $e.css("font-family"),
                    overflowY = "hidden";

                if (nodeName === "textarea") {
                    overflowY = "auto";
                    top += borderTop;
                    height -= borderTop+borderBottom;
                    lineHeight = 1.2;
                }

                if (nodeName === "input") {
                    lineHeight = height+1+"px";
                }

                if (!reset) {
                    $tip = $("<div class=\"pld-tip\">"+$e.attr("placeholder")+"</div>");
                }
                else {
                    $tip = $(".pld-tip").eq(i);
                }

                $tip.css({
                    "position": "absolute",
                    "top": top+"px",
                    "left": left+"px",
                    "padding-top": paddingTop+"px",
                    "padding-right": paddingRight+"px",
                    "padding-bottom": paddingBottom+"px",
                    "padding-left": paddingLeft+"px",
                    "width": width + "px",
                    "height": height + "px",
                    "line-height": lineHeight,
                    "word-wrap": "break-word",
                    "overflow-x": "hidden",
                    "overflow-y": overflowY,
                    "cursor": "text",
                    "color": config.color,
                    "font-size": fontSize,
                    "font-family": fontFamily,
                    "z-index": config.zIndex
                }).attr("contenteditable", true);

                if (config.shield && !reset) {
                    $e[0].removeAttribute("placeholder");
                }

                if (!reset) {
                    bdy.appendChild($tip[0]);
                }

                initEvnt($tip, $e);
            }
            else {
                setCursor($e[0], $e.val().length);
            }
        }
    }

    function initEvnt($tip, $e) {
        $tip.on("focus", function() {
            if($e.val() === "") {
                setCursor($e[0], 0);
            }
        });

        $e.on("keyup", function(e) {
            if ($e.val() !== "") {
                $tip.hide();
            }
            
            if (e.keyCode === 8) {
                if($e.val() === "") {
                    $tip.show();
                }
            }
        }).on("input", function(e) {
            if ($e.val() !== "") {
                $tip.hide();
            }
            else {
                $tip.show();
            }
        }).on("propertychange", function(e) {
            if ($e.val() !== "") {
                $tip.hide();
            }
            else {
                $tip.show();
            }
        });
    }

    function setCursor(elem, pos) {
        elem.focus();

        if (elem.setSelectionRange) { //W3C
            elem.setSelectionRange(pos, pos);
        }
    　　else if (elem.createTextRange) { //IE
            var range = elem.createTextRange();

            range.moveStart("character", pos);
            range.collapse(true);
            range.select();
        }  
    }
    
    $.placeholder = placeholder;
})(jQuery, window, document);