/*
    file: boxy.js
    create: 2014-03-27
    author: lishijun
    功能: 网上扒的浮层jQuery插件
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
        $bdy = $(bdy);

    $.fn.boxy = function(options) {
        options = options || {};
        return this.each(function() {      
            var node = this.nodeName.toLowerCase(), self = this;
            if (node === 'a') {
                $(this).click(function() {
                    var 
                        active = Boxy.linkedTo(this),
                        href = this.getAttribute('href'),
                        localOptions = $.extend({actuator: this, title: this.title}, options);
                        
                    if (active) {
                        active.show();
                    } else if (href.indexOf('#') >= 0) {
                        var 
                            content = $(href.substr(href.indexOf('#'))),
                            newContent = content.clone(true);

                        content.remove();
                        localOptions.unloadOnHide = false;
                        new Boxy(newContent, localOptions);
                    } else { // fall back to AJAX; could do with a same-origin check
                        if (!localOptions.cache) localOptions.unloadOnHide = true;
                        Boxy.load(this.href, localOptions);
                    }
                    
                    return false;
                });
            } else if (node === 'form') {
                $(this).bind('submit.boxy', function() {
                    Boxy.confirm(options.message.content || '请确认：', function() {
                        $(self).unbind('submit.boxy').submit();
                    });
                    return false;
                });
            }
        });
    };

    // Boxy Class
    function Boxy(element, options) {
        this.boxy = $(Boxy.WRAPPER);
        $.data(this.boxy[0], 'boxy', this);
        
        this.visible = false;
        this.options = $.extend({}, Boxy.DEFAULTS, options || {});
        
        if (this.options.modal) {
            this.options = $.extend(this.options, {center: true, draggable: false});
        }
        
        // options.actuator === DOM element that opened this boxy
        // association will be automatically deleted when this boxy is remove()d
        if (this.options.actuator) {
            $.data(this.options.actuator, 'active.boxy', this);
        }
        
        this.setContent(element || "<div></div>");
        this._setupTitleBar();
        
        this.boxy.css('display', 'none').appendTo(bdy);
        this.toTop();

        if (this.options.fixed) {
            if (Boxy.isIE && (navigator.appVersion.match(/6./i)=='6.')) {
                this.options.fixed = false; // IE6 doesn't support fixed positioning
            } else {
                this.boxy.addClass('fixed');
            }
        }
        
        if (this.options.center && Boxy._u(this.options.x, this.options.y)) {
            this.center();
        } else {
            this.moveTo(
                Boxy._u(this.options.x) ? this.options.x : Boxy.DEFAULT_X,
                Boxy._u(this.options.y) ? this.options.y : Boxy.DEFAULT_Y
            );
        }
        
        if (this.options.show) this.show();
    }

    Boxy.EF = function() {};

    $.extend(Boxy, {
        WRAPPER:    "<div class='boxy-wrapper'><div class='boxy-inner'></div></div>",
        
        DEFAULTS: {
            title:                  null,           // titlebar text. titlebar will not be visible if not set.
            closeable:              true,           // display close link in titlebar?
            draggable:              true,           // can this dialog be dragged?
            clone:                  false,          // clone content prior to insertion into dialog?
            actuator:               null,           // element which opened this dialog
            center:                 true,           // center dialog in viewport?
            show:                   true,           // show dialog immediately?
            modal:                  false,          // make dialog modal?
            fixed:                  true,           // use fixed positioning, if supported? absolute positioning used otherwise
            closeText:              '[关闭]',      // text to use for default close link
            unloadOnHide:           false,          // should this dialog be removed from the DOM after being hidden?
            clickToFront:           false,          // bring dialog to foreground on any click (not just titlebar)?
            behaviours:             Boxy.EF,        // function used to apply behaviours to all content embedded in dialog.
            afterDrop:              Boxy.EF,        // callback fired after dialog is dropped. executes in context of Boxy instance.
            afterShow:              Boxy.EF,        // callback fired after dialog becomes visible. executes in context of Boxy instance.
            afterHide:              Boxy.EF,        // callback fired after dialog is hidden. executed in context of Boxy instance.
            beforeUnload:           Boxy.EF         // callback fired after dialog is unloaded. executed in context of Boxy instance.
        },
        
        DEFAULT_X:          50,
        DEFAULT_Y:          50,
        zIndex:             1337,
        dragConfigured:     false, // only set up one drag handler for all boxys
        resizeConfigured:   false,
        dragging:           null,

        isIE:               !!win.ActiveXObject,
        
        // load a URL and display in boxy
        // url - url to load
        // options keys (any not listed below are passed to boxy constructor)
        //   type: HTTP method, default: GET
        //   cache: cache retrieved content? default: false
        //   filter: jQuery selector used to filter remote content
        load: function(url, options) {
            options = options || {};
            
            var ajax = {
                url: url, type: 'GET', dataType: 'html', cache: false, success: function(html) {
                    html = $(html);
                    if (options.filter) html = $(options.filter, html);
                    new Boxy(html, options);
                }
            };
            
            $.each(['type', 'cache'], function() {
                if (this in options) {
                    ajax[this] = options[this];
                    delete options[this];
                }
            });
            
            $.ajax(ajax);
            
        },
        
        // allows you to get a handle to the containing boxy instance of any element
        // e.g. <a href='#' onclick='alert(Boxy.get(this));'>inspect!</a>.
        // this returns the actual instance of the boxy 'class', not just a DOM element.
        // Boxy.get(this).hide() would be valid, for instance.
        get: function(ele) {
            var p = $(ele).parents('.boxy-wrapper');
            return p.length ? $.data(p[0], 'boxy') : null;
        },
        
        // returns the boxy instance which has been linked to a given element via the
        // 'actuator' constructor option.
        linkedTo: function(ele) {
            return $.data(ele, 'active.boxy');
        },
        
        // displays an alert box with a given message, calling optional callback
        // after dismissal.
        alert: function(message, callback, options) {
            return Boxy.ask(message.content, message.buttonText, callback, options);
        },
        
        // displays an alert box with a given message, calling after callback iff
        // user selects OK.
        confirm: function(message, after, options) {
            return Boxy.ask(message.content, message.buttonText, function(response) {
                if (response === message.buttonText[0]) after();
            }, options);
        },
        
        // asks a question with multiple responses presented as buttons
        // selected item is returned to a callback method.
        // answers may be either an array or a hash. if it's an array, the
        // the callback will received the selected value. if it's a hash,
        // you'll get the corresponding key.
        ask: function(question, answers, callback, options) {
            options = $.extend({modal: true, closeable: false},
                                    options || {},
                                    {show: true, unloadOnHide: true});
            
            var by = $('<div></div>').append($('<div class="question"></div>').html(question));
            
            // ick
            var map = {}, answerStrings = [];
            if (answers instanceof Array) {
                for (var i = 0; i < answers.length; i++) {
                    map[answers[i]] = answers[i];
                    answerStrings.push(answers[i]);
                }
            } else {
                for (var k in answers) {
                    map[answers[k]] = k;
                    answerStrings.push(answers[k]);
                }
            }
            
            var buttons = $('<form class="answers"></form>');
            buttons.html($.map(answerStrings, function(v) {
                //add by zhangxinxu http://www.zhangxinxu.com 给确认对话框的确认取消按钮添加不同的class
                var btn_index;  
                if(v === answerStrings[0]){
                    btn_index = 1;
                }else if(v === answerStrings[1]){
                    btn_index = 2;
                }else{
                    btn_index = 3;  
                }
                //add end.  include the 'btn_index' below 
                return "<input class='boxy-btn"+btn_index+"' type='button' value='" + v + "' />";
            }).join(' '));
            
            $('input[type=button]', buttons).click(function() {
                var clicked = this;
                Boxy.get(this).hide(function() {
                    if (callback) callback(map[clicked.value]);
                });
            });
            
            by.append(buttons);
            
            new Boxy(by, options);
            
        },
        
        // returns true if a modal boxy is visible, false otherwise
        isModalVisible: function() {
            return $('.boxy-modal-blackout').length > 0;
        },
        
        _u: function() {
            for (var i = 0; i < arguments.length; i++)
                if (arguments[i] !== undefined) return false;
            return true;
        },
        
        _handleResize: function(evt) {
            var d = $doc;
            $('.boxy-modal-blackout').css('display', 'none').css({
                width: d.width(), height: d.height()
            }).css('display', 'block');
        },
        
        _handleDrag: function(evt) {
            if (Boxy.dragging) {
                var d = Boxy.dragging;
                d[0].boxy.css({left: evt.pageX - d[1], top: evt.pageY - d[2]});
            }
        },
        
        _nextZ: function() {
            return Boxy.zIndex++;
        },
        
        _viewport: function() {
            var d = doc.documentElement, b = bdy, w = win;
            return $.extend(
                Boxy.isIE ?
                    { left: b.scrollLeft || d.scrollLeft, top: b.scrollTop || d.scrollTop } :
                    { left: w.pageXOffset, top: w.pageYOffset },
                !Boxy._u(w.innerWidth) ?
                    { width: w.innerWidth, height: w.innerHeight } :
                    (!Boxy._u(d) && !Boxy._u(d.clientWidth) && d.clientWidth !== 0 ?
                        { width: d.clientWidth, height: d.clientHeight } :
                        { width: b.clientWidth, height: b.clientHeight }) );
        }

    });

    Boxy.prototype = {
        // Returns the size of this boxy instance without displaying it.
        // Do not use this method if boxy is already visible, use getSize() instead.
        estimateSize: function() {
            this.boxy.css({visibility: 'hidden', display: 'block'});
            var dims = this.getSize();
            this.boxy.css('display', 'none').css('visibility', 'visible');
            return dims;
        },
                    
        // Returns the dimensions of the entire boxy dialog as [width,height]
        getSize: function() {
            return [this.boxy.width(), this.boxy.height()];
        },
        
        // Returns the dimensions of the content region as [width,height]
        getContentSize: function() {
            var c = this.getContent();
            return [c.width(), c.height()];
        },
        
        // Returns the position of this dialog as [x,y]
        getPosition: function() {
            var b = this.boxy[0];
            return [b.offsetLeft, b.offsetTop];
        },
        
        // Returns the center point of this dialog as [x,y]
        getCenter: function() {
            var p = this.getPosition();
            var s = this.getSize();
            return [Math.floor(p[0] + s[0] / 2), Math.floor(p[1] + s[1] / 2)];
        },
                    
        // Returns a jQuery object wrapping the inner boxy region.
        // Not much reason to use this, you're probably more interested in getContent()
        getInner: function() {
            return $('.boxy-inner', this.boxy);
        },
        
        // Returns a jQuery object wrapping the boxy content region.
        // This is the user-editable content area (i.e. excludes titlebar)
        getContent: function() {
            return $('.boxy-content', this.boxy);
        },
        
        // Replace dialog content
        setContent: function(newContent) {
            newContent = $(newContent).css({display: 'block'}).addClass('boxy-content');
            if (this.options.clone) newContent = newContent.clone(true);
            this.getContent().remove();
            this.getInner().append(newContent);
            this._setupDefaultBehaviours(newContent);
            this.options.behaviours.call(this, newContent);
            return this;
        },
        
        // Move this dialog to some position, funnily enough
        moveTo: function(x, y) {
            this.moveToX(x).moveToY(y);
            return this;
        },
        
        // Move this dialog (x-coord only)
        moveToX: function(x) {
            if (typeof x === 'number') this.boxy.css({left: x});
            else this.centerX();
            return this;
        },
        
        // Move this dialog (y-coord only)
        moveToY: function(y) {
            if (typeof y === 'number') this.boxy.css({top: y});
            else this.centerY();
            return this;
        },
        
        // Move this dialog so that it is centered at (x,y)
        centerAt: function(x, y) {
            var s = this[this.visible ? 'getSize' : 'estimateSize']();
            if (typeof x === 'number') this.moveToX(x - s[0] / 2);
            if (typeof y === 'number') this.moveToY(y - s[1] / 2);
            return this;
        },
        
        centerAtX: function(x) {
            return this.centerAt(x, null);
        },
        
        centerAtY: function(y) {
            return this.centerAt(null, y);
        },
        
        // Center this dialog in the viewport
        // axis is optional, can be 'x', 'y'.
        center: function(axis) {
            var v = Boxy._viewport();
            var o = this.options.fixed ? [0, 0] : [v.left, v.top];
            if (!axis || axis === 'x') this.centerAt(o[0] + v.width / 2, null);
            if (!axis || axis === 'y') this.centerAt(null, o[1] + v.height / 2);
            return this;
        },
        
        // Center this dialog in the viewport (x-coord only)
        centerX: function() {
            return this.center('x');
        },
        
        // Center this dialog in the viewport (y-coord only)
        centerY: function() {
            return this.center('y');
        },
        
        // Resize the content region to a specific size
        resize: function(width, height, after) {
            if (!this.visible) return;
            var bounds = this._getBoundsForResize(width, height);
            this.boxy.css({left: bounds[0], top: bounds[1]});
            this.getContent().css({width: bounds[2], height: bounds[3]});
            if (after) after(this);
            return this;
        },
        
        // Tween the content region to a specific size
        tween: function(width, height, after) {
            if (!this.visible) return;
            var bounds = this._getBoundsForResize(width, height);
            var self = this;
            this.boxy.stop().animate({left: bounds[0], top: bounds[1]});
            this.getContent().stop().animate({width: bounds[2], height: bounds[3]}, function() {
                if (after) after(self);
            });
            return this;
        },
        
        // Returns true if this dialog is visible, false otherwise
        isVisible: function() {
            return this.visible;    
        },
        
        // Make this boxy instance visible
        show: function() {
            if (this.visible) return;
            if (this.options.modal) {
                var self = this;
                if (!Boxy.resizeConfigured) {
                    Boxy.resizeConfigured = true;
                    $win.resize(function() { Boxy._handleResize(); });
                }

                /*===========start: 修复bug by lishijun @2012-8-24===================*/
                $bdy.children('.boxy-modal-blackout').remove();

                if($bdy.children('.boxy-wrapper').size() > 1){
                    $bdy.children('.boxy-wrapper').not(':last').remove();
                }
                /*===========end: 修复bug by lishijun @2012-8-24===================*/

                this.modalBlackout = $('<div class="boxy-modal-blackout"></div>')
                    .css({zIndex: Boxy._nextZ(),
                          opacity: 0.5,
                          width: $doc.width(),
                          height: $doc.height()})
                    .appendTo(bdy);
                this.toTop();
                if (this.options.closeable) {
                    $bdy.bind('keypress.boxy', function(evt) {
                        var key = evt.which || evt.keyCode;
                        if (key === 27) {
                            self.hide();
                            $bdy.unbind('keypress.boxy');
                        }
                    });
                }
            }
            this.boxy.stop().css({opacity: 1}).show();
            this.visible = true;
            this._fire('afterShow');
            return this;
        },
        
        // Hide this boxy instance
        hide: function(after) {
            if (!this.visible) return;
            var self = this;
            if (this.options.modal) {
                $bdy.unbind('keypress.boxy');
                this.modalBlackout.animate({opacity: 0}, function() {
                    $(this).remove();
                });
            }
            this.boxy.stop().animate({opacity: 0}, 300, function() {
                self.boxy.css({display: 'none'});
                self.visible = false;
                self._fire('afterHide');
                if (after) after(self);
                if (self.options.unloadOnHide) self.unload();
            });
            return this;
        },
        
        toggle: function() {
            this[this.visible ? 'hide' : 'show']();
            return this;
        },
        
        hideAndUnload: function(after) {
            this.options.unloadOnHide = true;
            this.hide(after);
            return this;
        },
        
        unload: function() {
            this._fire('beforeUnload');
            this.boxy.remove();
            if (this.options.actuator) {
                $.data(this.options.actuator, 'active.boxy', false);
            }
        },
        
        // Move this dialog box above all other boxy instances
        toTop: function() {
            this.boxy.css({zIndex: Boxy._nextZ()});
            return this;
        },
        
        // Returns the title of this dialog
        getTitle: function() {
            return $('> .title-bar h2', this.getInner()).html();
        },
        
        // Sets the title of this dialog
        setTitle: function(t) {
            $('> .title-bar h2', this.getInner()).html(t);
            return this;
        },
        
        //
        // Don't touch these privates
        
        _getBoundsForResize: function(width, height) {
            var csize = this.getContentSize();
            var delta = [width - csize[0], height - csize[1]];
            var p = this.getPosition();
            return [Math.max(p[0] - delta[0] / 2, 0),
                    Math.max(p[1] - delta[1] / 2, 0), width, height];
        },
        
        _setupTitleBar: function() {
            if (this.options.title) {
                var self = this;
                var tb = $("<div class='title-bar'></div>").html("<h2>" + this.options.title + "</h2>");
                if (this.options.closeable) {
                    tb.append($("<a href='#' class='close'></a>").html(this.options.closeText));
                }
                if (this.options.draggable) {
                    tb[0].onselectstart = function() { return false; };
                    tb[0].unselectable = 'on';
                    tb[0].style.MozUserSelect = 'none';
                    if (!Boxy.dragConfigured) {
                        $doc.mousemove(Boxy._handleDrag);
                        Boxy.dragConfigured = true;
                    }
                    tb.mousedown(function(evt) {
                        self.toTop();
                        Boxy.dragging = [self, evt.pageX - self.boxy[0].offsetLeft, evt.pageY - self.boxy[0].offsetTop];
                        $(this).addClass('dragging');
                    }).mouseup(function() {
                        $(this).removeClass('dragging');
                        Boxy.dragging = null;
                        self._fire('afterDrop');
                    });
                }
                this.getInner().prepend(tb);
                this._setupDefaultBehaviours(tb);
            }
        },
        
        _setupDefaultBehaviours: function(root) {
            var self = this;
            if (this.options.clickToFront) {
                root.click(function() { self.toTop(); });
            }
            $('.close', root).click(function() {
                self.hide();
                return false;
            }).mousedown(function(evt) { evt.stopPropagation(); });
        },
        
        _fire: function(event) {
            this.options[event].call(this);
        }
    };

    win.Boxy = Boxy;
})(jQuery, window, document);