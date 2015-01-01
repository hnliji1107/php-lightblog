/*
    file: zoomAndMove.js
    create: 2013-12-22
    author: lishijun (928990115@qq.com)
    功能: 拖拽移动，方向键移动，拖拽缩放。
    参数: {
        isMove: false, // 可选，Boolean类型，拖动功能开关，默认false
        dragbar: "", // 可选，String类型，被拖动元素，如"h2",".class"等，默认为整个对象
        isZoom: false, // 可选，Boolean类型，缩放功能开关，默认false
        dirArr: [], // 可选，Array类型，缩放方向设置，默认["lt","ct","rt","lc","rc","lb","cb","rb"],
        moveStart: function(info){}, // 可选，Function类型，移动开始时的回调，默认会返回一个信息对象，包括当前对象obj，当前对象的序列号idx，当前对象的宽度width、当前对象的高度height、当前对象的左偏移left、当前对象的上偏移top。
        moving: function(info){}, // 可选，Function类型，移动过程中的回调，返回信息同上
        moveEnd: function(info){}, // 可选，Function类型，移动结束时的回调，返回信息同上
        zoomStart: function(info){}, // 可选，Function类型，缩放开始时的回调，返回信息同上
        zooming: function(info){}, // 可选，Function类型，缩放过程中的回调，返回信息同上
        zoomEnd: function(info){} // 可选，Function类型，缩放结束时的回调，返回信息同上
        container: "", // 可选，Object类型，容器，用于确定操作范围。
    },
    使用示例: 
    $objs.zoomAndMove({
        isMove: true, 
        dragbar: "h2",
        isZoom: true,
        dirArr: ["lt","ct"],
        moveStart: function(info) {},
        moving: function(info) {},
        moveEnd: function(info) {},
        zoomStart: function(info) {},
        zooming: function(info) {},
        zoomEnd: function(info) {},
        container: ""
    });
    注意事项：
    1. 拖拽对象必须为绝对定位元素，并且显式声明top、left值，单位px。
    2. 拖拽对象不能设置外边距。
    3. 如果设置了容器，必须保证容器为相对定位元素，即为拖拽对象的父元素。
*/

;(function($, win, doc, undefined) {
    var body = doc.body, jbody = $(body), jdoc = $(doc);

    function ZoomAndMove($el, i, opts) {
        this.$el = $el;
        this.idx = i;
        this.opts = opts;

        this.init();
    }

    ZoomAndMove.prototype.init = function() {
        if (this.opts && this.opts.isZoom===true) {
            var bars = [], dirArr = ["lt","ct","rt","lc","rc","lb","cb","rb"], barSize = 5, barZindex = 2;

            if (typeof this.opts.dirArr=="object" && this.opts.dirArr.constructor==Array) {
                dirArr = this.opts.dirArr;
            }

            for (var i=0, count=dirArr.length; i<count; i++) {
                switch(dirArr[i]) {
                    case "lt": {
                        bars = bars + '<span class="lt" style="position:absolute;top:0;left:0;cursor:nw-resize;z-index:'+barZindex+';width:'+barSize+'px;height:'+barSize+'px;background:#fff;border:1px solid #fff;"></span>';
                    }
                    break;
                    case "ct": {
                        bars = bars + '<span class="ct" style="position:absolute;top:0;left:0;cursor:n-resize;width:100%;height:'+barSize+'px;border-top:1px dashed #fff;"></span>';
                    }
                    break;
                    case "rt": {
                        bars = bars + '<span class="rt" style="position:absolute;top:0;right:0;left:auto;cursor:ne-resize;z-index:'+barZindex+';width:'+barSize+'px;height:'+barSize+'px;background:#fff;border:1px solid #fff;"></span>';
                    }
                    break;
                    case "lc": {
                        bars = bars + '<span class="lc" style="position:absolute;top:0;left:0;cursor:w-resize;height:100%;width:'+barSize+'px;border-left:1px dashed #fff;"></span>';
                    }
                    break;
                    case "rc": {
                        bars = bars + '<span class="rc" style="position:absolute;top:0;left:auto;right:0;cursor:w-resize;height:100%;width:'+barSize+'px;border-right:1px dashed #fff;"></span>';
                    }
                    break;
                    case "lb": {
                        bars = bars + '<span class="lb" style="position:absolute;top:auto;left:0;bottom:0;cursor:ne-resize;z-index:'+barZindex+';width:'+barSize+'px;width:'+barSize+'px;height:'+barSize+'px;background:#fff;border:1px solid #fff;"></span>';
                    }
                    break;
                    case "cb": {
                        bars = bars + '<span class="cb" style="position:absolute;top:auto;left:0;bottom:0;cursor:n-resize;width:100%;height:'+barSize+'px;border-bottom:1px dashed #fff;"></span>';
                    }
                    break;
                    case "rb": {
                        bars = bars + '<span class="rb" style="position:absolute;top:auto;left:auto;right:0;bottom:0;cursor:nw-resize;z-index:'+barZindex+';width:'+barSize+'px;height:'+barSize+'px;background:#fff;border:1px solid #fff;"></span>';
                    }
                    break;
                }
                
                _dragZoom(this.$el, this.idx, dirArr[i], this.opts.zoomStart, this.opts.zooming, this.opts.zoomEnd, $(this.opts.container));
            }

            if (!this.$el.data("fill")) {
                this.$el.append(bars).data("fill", true);
            }
        }

        if (this.opts && this.opts.isMove===true) {
            _dragMove(this.$el, this.idx, this.opts.dragbar, this.opts.moveStart, this.opts.moving, this.opts.moveEnd, $(this.opts.container));
        }
    }

    function _dragZoom($el, idx, direc, zoomStart, zooming, zoomEnd, $container) {
        var point = {x:0, y:0}, isZoom = false;

        $el.on("mousedown", "."+direc, function(e) {
            e.preventDefault();
            var 
                baseWidth = parseInt($el.css("width")),
                baseHeight = parseInt($el.css("height")),
                container_top = jbody.offset().top,
                container_left = jbody.offset().left,
                container_border_top = 0,
                container_border_right = 0,
                container_border_bottom = 0,
                container_border_left = 0,
                container_border_height = 0,
                container_border_width = 0,
                el_top = $el.offset().top,
                el_left = $el.offset().left,
                el_border_top = parseInt($el.css("border-top-width")),
                el_border_right = parseInt($el.css("border-right-width")),
                el_border_bottom = parseInt($el.css("border-bottom-width")),
                el_border_left = parseInt($el.css("border-left-width")),
                el_border_width = el_border_left+el_border_right,
                el_border_height = el_border_top+el_border_bottom;

            if ($container.length) {
                container_top = $container.offset().top;
                container_left = $container.offset().left;
                container_border_top = parseInt($container.css("border-top-width"));
                container_border_right = parseInt($container.css("border-right-width"));
                container_border_bottom = parseInt($container.css("border-bottom-width"));
                container_border_left = parseInt($container.css("border-left-width"));
                container_border_height = container_border_top+container_border_bottom;
                container_border_width = container_border_left+container_border_right;
            }

            var 
                diff_top =  el_top-container_top,
                diff_left = el_left-container_left,
                baseTop = isNaN(parseInt($el.css("top"))) ? diff_top : parseInt($el.css("top")),
                baseLeft = isNaN(parseInt($el.css("left"))) ? diff_left : parseInt($el.css("left"));

            point.x = e.clientX;
            point.y = e.clientY;
            isZoom = true;

            if (typeof zoomStart == 'function') {
                zoomStart({
                    obj: $el,
                    idx: idx,
                    width: baseWidth,
                    height: baseHeight,
                    top: baseTop,
                    left: baseLeft
                });
            }

            jdoc.off("mousemove").on("mousemove", function(e) {
                e.preventDefault();

                if (isZoom) {
                    switch(direc) {
                        case "lt": {
                            var 
                                width = baseWidth+point.x-e.clientX,
                                height = baseHeight+point.y-e.clientY,
                                top = baseTop+e.clientY-point.y,
                                left = baseLeft+e.clientX-point.x;

                            if ($container.length) {
                                if (top < 0 || left < 0 || top > baseTop+baseHeight || left > baseLeft+baseWidth) return;
                            }

                            $el.css({width: width+"px", height: height+"px", top: top+"px", left: left+"px"});

                            if (typeof zooming == 'function' && (width-baseWidth != 0 || height-baseHeight != 0)) {
                                zooming({
                                    obj: $el,
                                    idx: idx,
                                    width: width,
                                    height: height,
                                    top: top,
                                    left: left
                                });
                            }
                        }
                        break;
                        case "ct": {
                            var height = baseHeight+point.y-e.clientY, top = baseTop+e.clientY-point.y;

                            if ($container.length) {
                                if (top < 0 || top > baseTop+baseHeight) return;
                            }

                            $el.css({width: baseWidth+"px", height: height+"px", top: top+"px"});

                            if (typeof zooming == 'function' && height-baseHeight != 0) {
                                zooming({
                                    obj: $el,
                                    idx: idx,
                                    width: baseWidth,
                                    height: height,
                                    top: top,
                                    left: baseLeft
                                });
                            }
                        }
                        break;
                        case "rt": {
                            var width = baseWidth+e.clientX-point.x, height = baseHeight+point.y-e.clientY, top = baseTop+e.clientY-point.y;

                            if ($container.length) {
                                if (width < 0 || height < 0 || top < 0 || baseLeft+width+container_border_width+el_border_width > $container.outerWidth()) return;
                            }

                            $el.css({width: width+"px", height: height+"px", top: top+"px"});

                            if (typeof zooming == 'function' && (width-baseWidth != 0 || height-baseHeight != 0)) {
                                zooming({
                                    obj: $el,
                                    idx: idx,
                                    width: width,
                                    height: height,
                                    top: top,
                                    left: baseLeft
                                });
                            }
                        }
                        break;
                        case "lc": {
                            var width = baseWidth+point.x-e.clientX, left = baseLeft+e.clientX-point.x;

                            if ($container.length) {
                                if (left < 0 || left > baseLeft+baseWidth) return;
                            }

                            $el.css({width: width+"px", height: baseHeight+"px", left: left+"px"});

                            if (typeof zooming == 'function' && width-baseWidth != 0) {
                                zooming({
                                    obj: $el,
                                    idx: idx,
                                    width: width,
                                    height: baseHeight,
                                    top: baseTop,
                                    left: left
                                });
                            }
                        }
                        break;
                        case "rc": {
                            var width = baseWidth+e.clientX-point.x;

                            if ($container.length) {
                                if (width < 0 || diff_left+width+container_border_width+el_border_width > $container.outerWidth()) return;
                            }

                            $el.css({width: width+"px", height: baseHeight+"px"});
                        
                            if (typeof zooming == 'function' && width-baseWidth != 0) {
                                zooming({
                                    obj: $el,
                                    idx: idx,
                                    width: width,
                                    height: baseHeight,
                                    top: baseTop,
                                    left: baseLeft
                                });
                            }
                        }
                        break;
                        case "lb": {
                            var width = baseWidth+point.x-e.clientX, height = baseHeight+e.clientY-point.y, left = baseLeft+e.clientX-point.x;

                            if ($container.length) {
                                if (width < 0 || height < 0 || left < 0 || baseTop+height+container_border_height+el_border_height > $container.outerHeight()) return;
                            }

                            $el.css({width: width+"px", height: height+"px", left: left+"px"});

                            if (typeof zooming == 'function' && (width-baseWidth !=0 || height-baseHeight != 0)) {
                                zooming({
                                    obj: $el,
                                    idx: idx,
                                    width: width,
                                    height: height,
                                    top: baseTop,
                                    left: left
                                });
                            }
                        }
                        break;
                        case "cb": {
                            var height = baseHeight+e.clientY-point.y;

                            if ($container.length) {
                                if (height < 0 || diff_top+height+container_border_height+el_border_height > $container.outerHeight()) return;
                            }

                            $el.css({width: baseWidth+"px", height: height+"px"});

                            if (typeof zooming == 'function' && height-baseHeight != 0) {
                                zooming({
                                    obj: $el,
                                    idx: idx,
                                    width: baseWidth,
                                    height: height,
                                    top: baseTop,
                                    left: baseLeft
                                });
                            }
                            
                        }
                        break;
                        case "rb": {
                            var width = baseWidth+e.clientX-point.x, height = baseHeight+e.clientY-point.y;

                            if ($container.length) {
                                if (width < 0 || height < 0 || baseTop+height+container_border_height+el_border_height > $container.outerHeight() || baseLeft+width+container_border_width+el_border_width > $container.outerWidth()) return;
                            }

                            $el.css({width: width+"px", height: height+"px"});

                            if (typeof zooming == 'function' && (width-baseWidth !=0 || height-baseHeight != 0)) {
                                zooming({
                                    obj: $el,
                                    idx: idx,
                                    width: width,
                                    height: height,
                                    top: baseTop,
                                    left: baseLeft
                                });
                            }
                        }
                        break;
                    }
                }
            }).off("mouseup").on("mouseup", function(e) {
                if (isZoom && typeof zoomEnd == 'function') {
                    zoomEnd({
                        obj: $el,
                        idx: idx,
                        width: parseInt($el.css("width")),
                        height: parseInt($el.css("height")),
                        top: isNaN(parseInt($el.css("top"))) ? diff_top : parseInt($el.css("top")),
                        left: isNaN(parseInt($el.css("top"))) ? diff_left : parseInt($el.css("left"))
                    });
                }

                isZoom = false;
            });
        });
    }

    function _dragMove($el, idx, dragbar, moveStart, moving, moveEnd, $container) {
        var 
            point = {x:0, y:0},
            isMove = false,
            $dragBar = dragbar==undefined ? $el : $el.find(dragbar),
            container_top = jbody.offset().top,
            container_left = jbody.offset().left,
            container_border_top = 0,
            container_border_right = 0,
            container_border_bottom = 0,
            container_border_left = 0,
            container_border_height = 0,
            container_border_width = 0,
            el_top = $el.offset().top,
            el_left = $el.offset().left,
            el_border_top = parseInt($el.css("border-top-width")),
            el_border_right = parseInt($el.css("border-right-width")),
            el_border_bottom = parseInt($el.css("border-bottom-width")),
            el_border_left = parseInt($el.css("border-left-width")),
            el_border_width = el_border_left+el_border_right,
            el_border_height = el_border_top+el_border_bottom;

        if ($container.length) {
            container_top = $container.offset().top;
            container_left = $container.offset().left;
            container_border_top = parseInt($container.css("border-top-width"));
            container_border_right = parseInt($container.css("border-right-width"));
            container_border_bottom = parseInt($container.css("border-bottom-width"));
            container_border_left = parseInt($container.css("border-left-width"));
            container_border_height = container_border_top+container_border_bottom;
            container_border_width = container_border_left+container_border_right;
        }

        var diff_top =  el_top-container_top, diff_left = el_left-container_left;

        $dragBar.css("cursor", "move");
            
        $dragBar.on("mousedown", function(e) {
            e.preventDefault();

            var targetCls = e.target.className;

            //在缩放bar上不能使用移动功能
            if (targetCls=="lt" || targetCls=="ct" || targetCls=="rt" || 
                targetCls=="lc" || targetCls=="rc" || targetCls=="lb" || 
                targetCls=="cb" || targetCls=="rb") {
                return;
            }

            $el.css("z-index", 999);

            var 
                baseTop = isNaN(parseInt($el.css("top"))) ? diff_top : parseInt($el.css("top")),
                baseLeft = isNaN(parseInt($el.css("left"))) ? diff_left : parseInt($el.css("left")),
                baseWidth = parseInt($el.css("width")),
                baseHeight = parseInt($el.css("height"));

            point.x = e.clientX;
            point.y = e.clientY;
            isMove = true;

            if (typeof moveStart == 'function') {
                moveStart({
                    obj: $el,
                    idx: idx,
                    width: baseWidth,
                    height: baseHeight,
                    top: baseTop,
                    left: baseLeft
                });
            }

            //按键方式移动
            jdoc.off("keydown").on("keydown", function(e) {
                e.stopPropagation();
                e.preventDefault();

                var 
                    keyCode = e.keyCode,
                    top = isNaN(parseInt($el.css("top"))) ? diff_top : parseInt($el.css("top")),
                    left = isNaN(parseInt($el.css("left"))) ? diff_left : parseInt($el.css("left")),
                    originTop = top,
                    originLeft = left;

                switch(keyCode) {
                    case 40:
                        top++;
                    break;
                    case 38:
                        top--;
                    break;
                    case 39:
                        left++;
                    break;
                    case 37:
                        left--;
                    break;
                }

                if ($container.length) {
                    top = top < 0 ? 0 : top;
                    top = top+baseHeight+el_border_height+container_border_height > $container.outerHeight() ? $container.outerHeight()-container_border_height-baseHeight-el_border_height : top;

                    left = left < 0 ? 0 : left;
                    left = left+baseWidth+el_border_width+container_border_width > $container.outerWidth() ? $container.outerWidth()-container_border_width-baseWidth-el_border_width : left;
                }

                $el.css({top: top+"px", left: left+'px'});

                if (typeof moving == 'function' && (top-originTop !=0 || left-originLeft != 0)) {
                    moving({
                        obj: $el,
                        idx: idx,
                        width: baseWidth,
                        height: baseHeight,
                        top: top,
                        left: left
                    });
                }
            });

            //拖拽方式移动
            jdoc.off("mousemove").on("mousemove", function(e) {
                e.preventDefault();

                var left = baseLeft+e.clientX-point.x, top = baseTop+e.clientY-point.y;

                if (isMove) {
                    if ($container.length) {
                        top = top < 0 ? 0 : top;
                        top = top+baseHeight+el_border_height+container_border_height > $container.outerHeight() ? $container.outerHeight()-container_border_height-baseHeight-el_border_height : top;

                        left = left < 0 ? 0 : left;
                        left = left+baseWidth+el_border_width+container_border_width > $container.outerWidth() ? $container.outerWidth()-container_border_width-baseWidth-el_border_width : left;
                    }

                    $el.css({left: left+"px", top: top+"px"});

                    if (moving && typeof moving == 'function' && (top-baseTop !=0 || left-baseLeft !=0)) {
                        moving({
                            obj: $el,
                            idx: idx,
                            width: baseWidth,
                            height: baseHeight,
                            top: top,
                            left: left
                        });
                    }
                }
            }).off("mouseup").on("mouseup", function() {
                $el.css("z-index", 1);

                if (isMove && typeof moveEnd == 'function') {
                    moveEnd({
                        obj: $el,
                        idx: idx,
                        width: baseWidth,
                        height: baseHeight,
                        top: isNaN(parseInt($el.css("top"))) ? diff_top : parseInt($el.css("top")),
                        left: isNaN(parseInt($el.css("left"))) ? diff_left : parseInt($el.css("left"))
                    });
                }

                isMove = false;
            });
        });
    }

    $.fn.zoomAndMove = function(opts) {
        return this.each(function(i) {
            if (!$(this).data("new")) {
                new ZoomAndMove($(this), i, opts);
                $(this).data("new", true);
            }
        });
    }
})(jQuery, window, document);