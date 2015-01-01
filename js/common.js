/*
    file: common.js
    create: 2012-08-23
    author: lishijun
    update: 2014-05-08
    功能: 扩展jQuery的原型方法（全站公用）
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
        //工具集
        webUtil = {};

    $.extend(win.webUtil = webUtil, {
        /*
            功能: Boxy.alert方法的包装
            参数: { 
                msg: String类型，提示语，默认为空字符串
                textArr: Array类型，操作按钮
                callback: Function类型，回调方法
                elem: Object类型，获取焦点的元素对象
                title: String类型，标题
            }
        */
        boxalert: function(obj) {
            Boxy.alert({
                content: obj.msg || '', 
                buttonText: obj.textArr || ['确定']
            },
            function() {
                if (obj.callback && typeof obj.callback === 'function') {
                    obj.callback.call(this);
                }
                if (obj.elem) {
                    obj.elem.focus();
                }
            },
            {
                title: obj.title || '提示'
            });
        },

        /*
            功能: 创建遮罩层以及等待图标
            参数: { 
                hasIcon: Boolean类型, false时，加载图标不显示
                zIndex: Number类型, 垂直方向层级
            }
        */
        loading: function(hasIcon, zIndex) {
            var 
                winW = parseInt($win.width()),
                winH = parseInt($win.height()),
                docH = parseInt($doc.height()),
                scrollLeft = parseInt($win.scrollLeft()),
                scrollTop = parseInt($win.scrollTop()),
                loadingSize = 80,
                maxW = winW,
                maxH = Math.max(winH, docH),
                left = scrollLeft+(winW-loadingSize)/2,
                top = scrollTop+(winH-loadingSize)/2,
                background = '#000 url(../images/loading.gif) no-repeat '+left+'px '+top+'px';

            //不使用遮罩
            if (!hasIcon) {
                background = '#000';
            }

            //创建遮罩层
            var createMask = function() {
                //缓存遮罩对象
                var mask;
                return function() {
                    return mask || (mask = $('<div/>', {
                        class: 'mask',
                        css: {
                            'position': 'absolute',
                            'top': 0,
                            'left': 0,
                            'background': background,
                            'opacity': 0.5,
                            'filter': 'alpha(opacity=50)',
                            'width': maxW+'px',
                            'height': maxH+'px',
                            'z-index': zIndex
                        }
                    }));
                };
            }();

            //追加遮罩层到body中
            if (!$bdy.children('.mask').length) {
                $bdy.append(createMask());
            }
        },

        /*
            功能: 关闭遮罩层
            参数: 无

        */
        unloading: function() {
            $bdy.children('.mask').remove();
        },

        /*
            功能: 弹窗居中
            参数: { 
                obj: Object类型, 弹窗对象
                zIndex: number类型, 垂直方向层级
                pos: Object类型, 弹窗开始位置
            }
        */
        dialogCenter: function(obj, zIndex, pos) {
            var 
                winW = parseInt($win.width()),
                winH = parseInt($win.height()),
                scrollTop = parseInt($win.scrollTop()),
                scrollLeft = parseInt($win.scrollLeft()),
                objW = obj.outerWidth(true),
                objH = obj.outerHeight(true),
                objL = scrollLeft + (winW-objW)/2,
                objT = scrollTop + (winH-objH)/2,
                top = (pos && pos.top !== undefined) ? pos.top+scrollTop : objT,
                left = (pos && pos.left !== undefined) ? pos.left+scrollLeft : objL;

            this.loading(false, zIndex);
            obj.css({
                'position': 'absolute',
                'top': top+'px',
                'left': left+'px',
                'z-index': zIndex+1
            }).show();
        },

        /*
            功能: 关闭弹窗
            参数: {
                obj: Object类型, 弹窗对象
            }
        */
        undialog: function(obj) {
            obj.hide();
            this.unloading();
        },

        /*
            功能: 弹窗拖动
            参数: {
                obj: Object类型, 移动的对象
                dragObj: Object类型, 拖动的对象
            }
        */
        dialogMove: function(obj, dragObj) {
            var
                theX, theY, ofLt, ofTp, moveKey = false,
                down = function(e) {
                    e.preventDefault();
                    theX = e.clientX;
                    theY = e.clientY;
                    ofLt = parseInt(obj.css('left'));
                    ofTp = parseInt(obj.css('top'));
                    moveKey = true;
                    dragObj.css({'cursor': 'move'});
                },
                move = function(e) {
                    if (moveKey) {
                        e.preventDefault();
                        var 
                            nowX = e.clientX,
                            nowY = e.clientY,
                            left = ofLt + nowX - theX,
                            top = ofTp + nowY - theY;
                            
                        obj.css({left: left+'px',top: top+'px'});
                    }
                },
                up = function() {
                    moveKey = false;
                    dragObj.css({'cursor': 'default'});
                };

            //拖动对象
            dragObj = (dragObj === undefined)? obj: dragObj;
            //事件监控
            dragObj.on('mousedown.move', down);
            $doc.on('mousemove.move', move).on('mouseup.move', up);
        },

        /*
            功能: ajax请求
            参数: {
                obj: Object类型, 包括传递数据方式,后台处理文件路径,传递的数据,接受数据的类型,成功后回调函数等
            }
        */
        ajaxRequest: function(obj) {
            var that = this;
            $.ajax({
                type: obj.type || 'post',
                url: obj.url,
                data: obj.data,
                dataType: obj.dataType || 'json',
                timeout: obj.timeout || 5000,
                beforeSend: function(xhr) {
                    if (typeof obj.isloading === 'boolean' && obj.isloading === false) {
                    }
                    else {
                        that.loading(true, 5);
                    }
                },
                complete: function(xhr) {
                    if (typeof obj.unloading === 'boolean' && obj.unloading === false) {
                    }
                    else {
                        that.unloading();
                    }
                },
                success: obj.success,
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    if (typeof obj.showerror === 'boolean' && obj.showerror === false) {
                    }
                    else {
                        if (textStatus === 'timeout') {
                            alert('请求超时');
                        }
                        else if (textStatus === 'error') {
                            alert('无网络连接');
                        }
                        else if (textStatus === 'parsererror') {
                            alert('服务器解析出错');
                        }
                        else {
                            alert('莫名错误');
                        }

                        if (obj.errcallback && typeof obj.errcallback === 'function') {
                            obj.errcallback.call(this);
                        }
                    }
                }
            });
        },

        /*
            功能: 设置cookie值
            参数: {
                setCookie: String类型，记录状态
                c_name: String类型，为记录变量名
                value: String类型，为变量值
                expiredays: String类型，为记录时间，单位为天
            }
        */
        setCookie: function(c_name, value, expiredays) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + expiredays);
            doc.cookie = encodeURIComponent(c_name) + '=' +encodeURIComponent(value) + ((expiredays === undefined) ? '': ';expires=' + exdate.toGMTString());
        },

        /*
            功能: 读取cookie值
            参数: {
                getCookie: String类型，读取记录状态
                c_name: String类型，为记录变量名
            }
        */
        getCookie: function(c_name) {
            if (doc.cookie.length > 0) {
                c_name = encodeURIComponent(c_name);

                var 
                    c_start = doc.cookie.indexOf(c_name+'='),
                    c_end = doc.cookie.indexOf(';', c_start);

                if (c_start !== -1) { 
                    c_start = c_start + c_name.length + 1;

                    if (c_end === -1) {
                        c_end = doc.cookie.length;
                    }

                    return decodeURIComponent(doc.cookie.substring(c_start,c_end));
                } 
            }

            return '';
        },

        /*
            功能: 根据window窗口高度等比例压缩图片
            参数: {
                img: Object类型，图片对象
                path: String类型，图片路径
            }
            注意: onload 应该写在 img.src 之前，这样可以避免一部分情况(ie6、7、8)下，onload事件不触发。
        */
        geomerScale: function(img, path, callback) {
            var 
                image = new Image(),
                win_height = $win.height() - 40,
                win_width = $win.width() - 40;

            image.onload = function() {
                var 
                    height = this.height,
                    width = this.width,
                    percent = 1;

                //保存原图大小到img节点上
                img.setAttribute('org_w', width);
                img.setAttribute('org_h', height);
                //计算压缩比例
                if (width > win_width && height < win_height) {
                    percent = win_width/width;
                }
                else if (width > win_width && height > win_height) {
                    percent = Math.min((win_height/height), (win_width/width));
                }
                else if (width < win_width && height > win_height) {
                    percent = win_height/height;
                }
                //检测边界值
                if (height*percent < 200 && width*percent < 200) {
                    percent = 0.2;
                }
                //重置图片大小
                var newWidth = width*percent, newHeight = height*percent, pos = {};
                //窗口宽度不能小于400
                if (win_width >= 400) {
                    //如果最后图片高度小于200，则图片不做压缩
                    if (newHeight < 200 && width > win_width) {
                        newWidth = width;
                        newHeight = height;
                        pos = {left: 0};
                    }
                    //如果最后图片宽度小于200，则图片不做压缩
                    if (newWidth < 200 && height > win_height) {
                        newWidth = width;
                        newHeight = height;
                        pos = {top: 0};
                    }
                }
                //赋值宽高
                img.height = newHeight;
                img.width = newWidth;
                img.src = path;
                //回调
                if (callback && typeof callback === 'function') {
                    callback.call(this, pos);
                }
            };
            
            image.src = path;
        },
        
        /*
            功能: 图片懒加载
            参数: {
                img: Object类型，图片对象
                path: String类型，图片路径
            }
        */
        imageLazyLoad: function() {
            var
                lazyImages = $('img[data-lazyload-src]'),
                scrollTop = $win.scrollTop(),
                winHeight = $win.height(),
                el, lazyloadsrc;
                
            $.each(lazyImages, function(i, e) {
                //图片进入可视区并且请求地址不为空
                if ((el=$(e)).offset().top < winHeight + scrollTop &&
                    (lazyloadsrc=el.data('lazyload-src'))) {
                    el.removeAttr('data-lazyload-src').attr('src', lazyloadsrc);
                }
            });
        }
    });
})(jQuery, window, document);