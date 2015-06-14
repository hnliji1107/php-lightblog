/*
    file: module.js
    create: 2013-11-10
    author: lishijun (928990115@qq.com)
    update: 2014-05-08
    功能: 全站主要操作
*/

;(function($, win, doc, undefined) {
    //公共声明
    var 
        //body对象
        bdy = doc.body,
        //jQuery包装的window
        $win = $(win),
        //jQuery包装的document
        $doc = $(doc),
        //jQuery包装的body
        $bdy = $(bdy),
        //url正则
        urlReg = /^https?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/,
        //邮箱正则
        emailReg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
        //qq正则
        qqReg = /^[1-9]\d{4,10}$/,
        //手机正则
        phoneReg = /^13[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$/,
        //图片正则
        imgReg = /.+\.(jpg)|(jpeg)|(gif)|(png)$/,
        //邀请正则
        inviterReg = /\S+\?inviter=(\S+)$/,
        //附件上传正则
        attachmentReg = /^<(p)|(P)[^>]*?>.+<\/P>/i,
        //退出登录正则
        quitReg = /(percenter)|(search)\.php/,
        //当前的登录者
        loginer = $('.isUser').val(),
        //用户名片数据缓存容器
        cardmap = [],
        //地址附带的参数字符串
        search = location.search,
        //debug正则
        debugReg = /debug=1/gi,
        //检测是否debug模式
        isDebug = debugReg.test(search),
        //3d旋转开始X位置
        spin3dX = -10,
        //3d旋转开始Y位置
        spin3dY = 0,
        //定时器时间间隔（毫秒）
        timecell = 200,
        //提示文案接口
        tips = {
            notEmptyOfEmail: '邮箱不能为空！',
            notEmptyOfName: '用户名不能为空！',
            notEmptyOfPassword: '密码不能为空！',
            notEmptyOfCheckCode: '验证码不能为空！',
            notEmptyOfPostContent: '提交内容不能为空！',
            notEmptyOfPostReceiver: '接收对象不能为空！',
            notEmptyOfSearchKey: '搜索词不能为空！',
            notEmptyOfSearchType: '搜索类型不能为空！',
            notEmptyOfDBHost: '数据库主机地址不能为空！',
            notEmptyOfDBOwner: '数据库拥有者不能为空！',
            notEmptyOfDBName: '数据库名称不能为空！',
            notEmptyOfArticleTitle: '文章标题不能为空！',
            notEmptyOfArticleBody: '文章内容不能为空！',
            notEmptyOfArticleType: '文章类型不能为空！',
            notEmptyOfAlbumName: '相册名称不能为空！',
            notEmptyOfOutLinksTitle: '外链标题不能为空！',
            notEmptyOfOutLinksHref: '外链链接不能为空！',
            notConformOfOutLinksHref: '外链链接格式不正确！',
            notChangeOfOutLinks: '外链信息没有改动！',
            notConformRuleOfEmail: '邮箱格式不正确！',
            notConformRuleOfQQ: 'QQ格式不正确！',
            notConformRuleOfPhone: '手机格式不正确！',
            notConformRuleOfSign: '签名不能超过100字符！',
            notConformRuleOfImage: '图片格式不正确！',
            notChangeOfInformation: '资料没有改变！',
            notSupport3d: '你使用的浏览器不支持3d动画！',
            selectedOfDeletePhoto: '请选择要删除的照片！',
            selectedOfAlbumCover: '请勾选一张作为相册封面！',
            selectedOfMoreAsCover: '你勾选了多张，请勾选一张作为封面！',
            selectedOfMovePhoto: '请选择要移动的相片！',
            sameOfTwoPassword: '两次密码输入必须一致！',
            existedOfAlbumName: '该相册名已被占用！',
            needLoginedOfOprate: '请先登录！',
            notAttenteOfSelf: '不能关注自己！',
            notPostOfSelf: '不能给自己发私信！',
            failOfLogout: '退出登陆失败！',
            placeholderOfSearchArticle: '查询文章，请输入关键字。'
        },
        //通用业务功能接口
        business = {
            //网站登录接口
            loginApply: function() {
                //检测用户输入
                var 
                    $user_name = getObjByName('user_name:self'),
                    $user_password = getObjByName('user_password:self'),
                    $check_code = getObjByName('check_code:self'),
                    days = 7;

                //检测用户名
                if (!checkItem($user_name, tips.notEmptyOfName)) return;
                //检测密码
                if (!checkItem($user_password, tips.notEmptyOfPassword)) return;
                //检测验证码
                if (!checkItem($check_code, tips.notEmptyOfCheckCode)) return;
                
                var successFn = function(m) {
                    if (m.status === 1) {
                        //记住登录状态
                        if ($('.remember_status').find('input[type=checkbox]').is(':checked')) {
                            //是否记住
                            webUtil.setCookie('isRemember', 'yes', days);
                            //记住用户名
                            webUtil.setCookie('remember_user', $user_name.val().replace(/(^\s*)|(\s*$)/g,''), days);
                            //记住密码
                            webUtil.setCookie('remember_psw', $user_password.val(), days);
                        }
                        //记录当前登陆者
                        webUtil.setCookie('loginer', $user_name.val());
                        //移除异常刷新器
                        webUtil.setCookie('reloadCount', '');
                        //跳转到指定页面，如果是首页，则忽略文件名。
                        location.href = m.msg.replace('/index.php', '');
                    } else {
                        webUtil.boxalert({
                            msg: m.msg,
                            callback: function() {
                                $('img.codeimg').attr('src','checkcode.php?t='+Math.random());
                                if (m.etype === 1) {
                                    $check_code.focus();
                                }
                                if (m.etype === 2) {
                                    $user_password.focus();
                                }
                            }
                        });
                    }
                },
                data = {
                    user_name : $user_name.val(),
                    user_password : $user_password.val(),
                    check_code : $check_code.val()
                },
                ajaxObj = {
                    url: 'login.php',
                    data: data,
                    success: successFn
                };
                //发起请求
                webUtil.ajaxRequest(ajaxObj);
            },
            //内容上传前验证
            uploadBefore: function() {
                //检测是否登录
                if (!checkItem(!loginer, tips.needLoginedOfOprate)) return false;
                //检测上传内容
                if (!checkItem($(this).prevAll('[type=file]').val()==='', '上传'+tips.notEmptyOfPostContent)) return false;
                //加载缓冲icon
                webUtil.loading(true, 1);
            },
            //附件上传成功提示
            successTip: function(txt) {
                var $ul = $('.post_upload_file .successtip').find('ul');
                if (attachmentReg.test($ul.html())) {
                    $ul.html(txt);
                } else {
                    $ul.append(txt);
                }
                //关闭遮罩层
                webUtil.unloading();
            },
            //附件删除功能
            attachmentDelete: function(e) {
                e.preventDefault();
                var 
                    $this = $(this),
                    $liparent = $this.closest('ul'),
                    attachment_id = $this.next('input[type=hidden]').val(),
                    data = getObjByName('replyid:self').length ? 
                            {separticle: true, attachment_id: attachment_id} : 
                            {percenter: true, attachment_id: attachment_id},
                    addtxt = '<p style="height:auto;">请注意：<br />1.文件大小不能超过8M<br />2.文件名长度尽量不要超过200个字符</p>',
                    successFn = function(m) {
                        if (m.status === 1) {
                            elemsh($this.parent(), false);
                            if ($liparent.find('li:visible').length === 0) {
                                $liparent.html(addtxt);
                            }
                        } else {
                            webUtil.boxalert({msg: m.msg});
                        }
                    },
                    ajaxObj = {
                        url: 'rplarticle.php',
                        data: data,
                        success: successFn,
                        isloading: false
                    };

                webUtil.ajaxRequest(ajaxObj);
            },
            //加关注功能
            addAttention: function(e) {
                e.preventDefault();
                var
                    name = e.data.name,
                    successFn = function(m) {
                        webUtil.boxalert({msg: m.msg});
                    },
                    ajaxObj = {
                        url: 'rplarticle.php',
                        data: {spacer: name, fans: loginer},
                        success: successFn
                    };

                //检测是否登录
                if (!checkItem(!loginer, tips.needLoginedOfOprate)) return;
                //检测是否本人关注
                if (!checkItem(name === loginer, tips.notAttenteOfSelf)) return;
                //检测通过之后发起请求
                webUtil.ajaxRequest(ajaxObj);
            },
            //收藏文章功能
            artCollect: function(e) {
                e.preventDefault();
                var 
                    $this = $(this),
                    //文章id
                    artid = getObjByName('artid:next', $this, true),
                    //文章作者
                    autor = getObjByName('autor:next', $this, true),
                    successFn = function(m) {
                        webUtil.boxalert({msg: m.msg});
                    },
                    ajaxObj = {
                        url: 'rplarticle.php',
                        data: {artid: artid, autor: autor},
                        success: successFn,
                        isloading: false
                    };
                //判断是否登录
                if (!checkItem(!loginer, tips.needLoginedOfOprate)) return;
                //发起请求
                webUtil.ajaxRequest(ajaxObj);
            },
            //打开发私信弹窗
            openMessageBox: function(e) {
                var elem = e.data.elem;
                e.preventDefault();
                //检测是否登录
                if (!checkItem(!loginer, tips.needLoginedOfOprate)) return;
                //填入发送人
                elem.find('.posttitle').val(getObjByName('ta-name:prev', $(this), true));
                //打开私信窗口
                webUtil.dialogCenter(elem, 2);
                //绑定拖动事件
                webUtil.dialogMove(elem, elem.find('h2'));
            }
        };

    //通用区块
    ({
        init: function() {
            initialize(this);
            //对外开放的两个接口（字数控制接口、附件上传成功接口）
            win.checkCharNum = numberTip;
            win.addtip = business.successTip;
        },

        //针对页面缓存，js处理登录状态切换
        clearCache: function() {
            var reloadCount = webUtil.getCookie('reloadCount') || 0;
            //最多连续刷新3次
            if (reloadCount < 3) {
                if (webUtil.getCookie('loginer') !== loginer) {
                    //如果该方法没有规定参数，或者参数是 false，它就会用 HTTP 头 If-Modified-Since 来检测服务器上的文档是否已改变。
                    //如果文档已改变，reload() 会再次下载该文档。
                    //如果文档未改变，则该方法将从缓存中装载文档。这与用户单击浏览器的刷新按钮的效果是完全一样的。
                    //如果把该方法的参数设置为 true，那么无论文档的最后修改日期是什么，它都会绕过缓存，从服务器上重新下载该文档。
                    //这与用户在单击浏览器的刷新按钮时按住ctrl健的效果完全一样。
                    location.reload();
                    webUtil.setCookie('reloadCount', reloadCount+1);
                }
            } else {
                webUtil.setCookie('loginer', '');
            }
        },

        //图片预加载
        imagePreLoad: function() {
            var 
                originurl = location.origin+'/images/',
                //预加载图片队列
                imgArr = [
                    originurl+'loading.gif'
                    // ,originurl+'waiting.gif'
                ],
                count = imgArr.length,
                i = 0;

            for(; i<count; i++) {
                (new Image).src = imgArr[i];
            }
        },
        
        //图片懒加载
        imageLazyLoad: function() {
            var timeid;
            webUtil.imageLazyLoad();
            $win.on('scroll.lazyload, resize.lazyload', function() {
                if (timeid) clearTimeout(timeid);
                timeid = setTimeout(webUtil.imageLazyLoad, timecell);
            });
        },

        //返回顶部功能
        go2Top: function() {
            var 
                $oprabar = $bdy.find('.W_gotop'),
                sctop = $win.scrollTop(),
                timeid, distance = 200,
                scroll = function() {
                    if (timeid) clearTimeout(timeid);
                    timeid = setTimeout(function() {
                        sctop = $win.scrollTop();
                        if (sctop <= distance) {
                            elemsh($oprabar, false);
                        } else {
                            elemsh($oprabar, true);
                        }
                    }, timecell);
                },
                gotop = function(e) {
                    e.preventDefault();
                    $("html, body").animate({scrollTop: 0}, timecell);
                };

            //滚动条距离顶端的距离大于200，置顶按钮才显示
            if (sctop > distance) {
                elemsh($oprabar, true);
            }
            //滚动条滚动时，实时改变置顶按钮的状态
            $win.on('scroll.go2Top', scroll);
            //返回顶部
            $oprabar.on('click', gotop);
        },

        //网站退出登录功能
        siteQuit: function() {
            var 
                $loginbar = $('.loginbar'),
                $oprator = $('.oprator'),
                days = 7,
                //退出网站登录接口
                quitApply = function(arg) {
                    arg[0].preventDefault();
                    var 
                        callback = arg[1],
                        successFn = function(m) {
                            //清除登陆者的记录
                            webUtil.setCookie('loginer', loginer, -1);
                            //移除异常刷新器
                            webUtil.setCookie('reloadCount', '');
                            if (m.status === 1 && callback && typeof callback === 'function') {
                                callback.call(this);
                            } else {
                                webUtil.boxalert({msg: tips.failOfLogout});
                            }
                        },
                        ajaxObj = {
                            url: 'exit.php',
                            data: {loginOut: true},
                            success: successFn,
                            unloading: false
                        };
                    
                    webUtil.ajaxRequest(ajaxObj);
                },
                exitMain = function(e) {
                    quitApply([e, function() {
                        var href = location.href;
                        if (quitReg.test(href)) {
                            location.href = '/';
                        } else {
                            location.href = href;
                        }
                    }]);
                },
                exitFan = function(e) {
                    quitApply([e, function() {
                        location.href = 'perspace.php?user=' + loginer;
                    }]);
                },
                exitSapce = function(e) {
                    quitApply([e, function() {
                        location.href = location.href;
                    }]);
                };

            //网站前台退出登录
            $loginbar.on('click', '.doexit', exitMain);
            //我的粉丝退出登录
            $oprator.on('click', '.fansexit', exitFan)
            //我的空间退出登录
            .on('click', '.exitspace', exitSapce);
        },

        //查看私信功能
        lookLetter: function() {
            var 
                $lksms = $('#lksms'),
                $layer_message_box = $('.layer_message_box'),
                openMessage = function(e) {
                    e.preventDefault();
                    var 
                        $this = $(this),
                        $lksmsh2 = $lksms.find('h2'),
                        len = $layer_message_box.find('li').length,
                        sender = getObjByName('sender:next', $this, true),
                        sms_id = getObjByName('sms_id:next', $this, true),
                        sms_text = getObjByName('sms_text:next', $this, true),
                        sms_time = getObjByName('sms_time:next', $this, true);

                    //显示私信内容
                    $lksmsh2.html('来自 <i>'+sender+'</i> 的私信<span>---'+sms_time+'</span>');
                    $lksms.find('.talkdetail').val(sms_text);
                    //打开私信窗口
                    webUtil.dialogCenter($lksms, 2);
                    //绑定拖动事件
                    webUtil.dialogMove($lksms, $lksmsh2);
                    //更新未读提示
                    $.post('rplarticle.php', {sms_id: sms_id});
                    //更新私信提示
                    $this.parent('li').remove();
                    //如果消息数量小于2，移除消息框
                    if (len <= 1) {
                        $layer_message_box.remove();
                    }
                },
                closeList = function(e) {
                    e.preventDefault();
                    elemsh($(this).parent(), false);
                },
                reply = function() {
                    var 
                        $senddetail = $lksms.find('.senddetail'),
                        $lksending = $lksms.find('.lksending'),
                        $lksmsp = $lksms.find('.tip'),
                        sender = $(this).closest('.talks').prev('h2').find('i').text(),
                        successFn = function(m) {
                            if (m.status === 1) {
                                webUtil.boxalert({
                                    msg: m.msg,
                                    callback: function() {
                                        $senddetail.val('').focus();
                                    }
                                });
                                //清除等待提示
                                elemsh($lksmsp, false);
                                //发送内容恢复可写
                                $senddetail.attr('readonly', false);
                                //提交按钮恢复可用
                                $lksending.attr('disabled', false);
                            } else {
                                webUtil.boxalert({msg: m.msg});
                            }
                        },
                        ajaxObj = {
                            url: 'rplarticle.php',
                            data: {sendObject: sender, textObject: $senddetail.val()},
                            success: successFn,
                            isloading: false,
                            unloading: false
                        };

                    //检测发送内容
                    if(!checkItem($senddetail, tips.notEmptyOfPostContent)) return;
                    //等待提示
                    elemsh($lksmsp, true);
                    //发送内容不可写
                    $senddetail.attr('readonly', true);
                    //提交按钮不可用
                    $lksending.attr('disabled', true);
                    //保存私信
                    webUtil.ajaxRequest(ajaxObj);
                },
                closeReply = function(e) {
                    e.preventDefault();
                    webUtil.undialog($('#lksms'));
                };

            //打开每条消息
            $layer_message_box.on('click', '.looksms', openMessage)
            //关闭消息列表
            .on('click', '.W_close_color', closeList);
            //回复私信
            $lksms.on('click', '.lksending', reply)
            //关闭回复
            .on('click', '.W_close_color', closeReply);
        },

        //发送私信功能
        sendLetter: function() {
            var 
                $sendsms = $('#sendsms'),
                $sending = $sendsms.find('.sending'),
                submitSend = function() {
                    var 
                        $smsdetail = $sendsms.find('.smsdetail'),
                        $sendObject = $smsdetail.find('input.sminput_type'), //发送对象
                        $textObject = $smsdetail.find(' textarea.sminput_type'), //发送内容
                        $tip = $smsdetail.find('.tip'),
                        successFn = function(m) {
                            if (m.status === 1) {
                                webUtil.boxalert({
                                    msg: m.msg,
                                    callback: function() {
                                        $textObject.val('').focus();
                                    }
                                });
                            } else {
                                webUtil.boxalert({
                                    msg: m.msg,
                                    elem: $sendObject
                                });
                            }
                            //清除等待提示
                            elemsh($tip, false);
                            //发送人恢复可写
                            $sendObject.attr('readonly', false);
                            //发送内容恢复可写
                            $textObject.attr('readonly', false);
                            //提交按钮恢复可用
                            $sending.attr('disabled', false);
                        },
                        ajaxObj = {
                            url: 'rplarticle.php',
                            data: {sendObject: $sendObject.val(), textObject: $textObject.val()},
                            success: successFn,
                            isloading: false,
                            unloading: false
                        };

                    //检测发送对象
                    if (!checkItem($sendObject, tips.notEmptyOfPostReceiver)) return;
                    //检测是否要发私信给自己
                    if (!checkItem(loginer===$sendObject.val(), tips.notPostOfSelf, $sendObject)) return;
                    //检测发送内容
                    if (!checkItem($textObject, tips.notEmptyOfPostContent)) return;
                    //等待提示
                    elemsh($tip, true);
                    //发送人不可写
                    $sendObject.attr('readonly', true);
                    //发送内容不可写
                    $textObject.attr('readonly', true);
                    //提交按钮不可用
                    $sending.attr('disabled',true);
                    //保存私信
                    webUtil.ajaxRequest(ajaxObj);
                },
                closeSend = function(e) {
                    e.preventDefault();
                    webUtil.undialog($sendsms);
                };

            //提交发送
            $sendsms.on('click', '.sending', submitSend)
            //关闭弹窗
            .on('click', '.W_close_color', closeSend);
        },

        //网站登录功能
        siteLogin: function() {
            var 
                $loginInner = $('.loginInner'),
                $spaceWrapper = $('.spaceWrapper'),
                $registInner = $('.registInner'),
                loginBefore = function(e) {
                    if (e.data.type === 'click') {
                        e.preventDefault();
                        business.loginApply();
                    } else if (e.data.type === 'enter' && e.keyCode === 13) {
                        business.loginApply();
                    }
                },
                //网站注册接口
                registApply = function() {
                    var 
                        $registInner = $('.registInner'),
                        $email = getObjByName('email:parent', $registInner),
                        $name = getObjByName('name:parent', $registInner),
                        $password = getObjByName('password:parent', $registInner),
                        $repassword = getObjByName('repassword:parent', $registInner),
                        $check_code = getObjByName('check_code:parent', $registInner),
                        href = location.href,
                        inviter = '';

                    //检测邮箱是否为空
                    if (!checkItem($email, tips.notEmptyOfEmail)) return;
                    //检测邮箱格式
                    if (!checkItem(!emailReg.test($email.val()), tips.notConformRuleOfEmail, $email)) return;
                    //检测用户名
                    if (!checkItem($name, tips.notEmptyOfName)) return;
                    //检测密码是否为空
                    if (!checkItem($password, tips.notEmptyOfPassword)) return;
                    //检测两次密码是否一致
                    if (!checkItem($password.val()!==$repassword.val(), tips.sameOfTwoPassword, $repassword)) return;
                    //检测验证码
                    if (!checkItem($check_code, tips.notEmptyOfCheckCode)) return;
                    //获取邀请人
                    if (inviterReg.test(href)) {
                        inviter = inviterReg.exec(href)[1];
                    }

                    //判断用户名是否重复,验证码是否正确
                    var 
                        successFn = function(m) {
                            if (m.status === 1) {
                                //移除异常刷新器
                                webUtil.setCookie('reloadCount', '');
                                //跳转到指定页面，如果是首页，则忽略文件名。
                                location.href = m.msg.replace('index.php', '');
                            } else {
                                webUtil.boxalert({
                                    msg: m.msg,
                                    callback: function() {
                                        $registInner.find('img.codeimg').attr('src','checkcode.php?t='+Math.random());
                                        if (m.etype === 1) {
                                            $check_code.focus();
                                        }
                                        if (m.etype === 2) {
                                            $name.focus();
                                        }
                                    }
                                });
                            }
                        },
                        data = {
                            email : $email.val(),
                            name : $name.val(),
                            password : $password.val(),
                            check_code : $check_code.val(),
                            inviter : inviter
                        },
                        ajaxObj = {
                            url: 'register.php',
                            data: data,
                            success: successFn
                        };

                    webUtil.ajaxRequest(ajaxObj);
                },
                registBefore = function(e) {
                    e.preventDefault();
                    registApply();
                };

            //查看是否有记住密码
            if (webUtil.getCookie('isRemember') === 'yes') {
                getObjByName('user_name:parent', $loginInner).val(webUtil.getCookie('remember_user'));
                getObjByName('user_password:parent', $loginInner).val(webUtil.getCookie('remember_psw'));
            }
            //网站前台登录
            $loginInner.on('click', '.act-login .persubmit_btn', {type: 'click'}, loginBefore)
                       .on('keypress', '[name=check_code]', {type: 'enter'}, loginBefore);
            //用户注册
            $registInner.on('click', '.act-register .persubmit_btn', registBefore);
        },

        //用户回复功能
        userReply: function() {
            var 
                $commentlist = $('.commentlist'),
                $user_text = $('.user_text'),
                openReply = function(e) {
                    e.preventDefault();
                    var 
                        $this = $(this),
                        $parentObj = $this.parent().prevAll('.user_text'),
                        $to_reply = $parentObj.find('.to_reply'),
                        areaObj = '<p class="to_reply"><textarea cols="50" cols="20" class="areatype"></textarea><button class="persubmit_btn">提交回复</button></p>';

                    if (!$this.data('open') || $this.data('open') === 'off') {
                        $parentObj.append(areaObj);
                        $to_reply.find('textarea').focus();
                        $this.text('收起').data('open','on');
                    }
                    else if ($this.data('open') === 'on') {
                        $to_reply.remove();
                        $this.text('回复').data('open','off');
                    }
                },
                submitReply = function() {
                    var 
                        $theArea = $(this).prevAll('textarea'),
                        $nowreplyObj = $theArea.parent().prevAll('.reply_to_user'),
                        replytext = $.trim($theArea.val()),
                        associd = $nowreplyObj.prevAll('input.associd').val(),
                        receiver = $nowreplyObj.prevAll('input.receiver').val();

                    //检测是否登录
                    if (!checkItem(!loginer, tips.needLoginedOfOprate)) return;
                    //检测回复内容
                    if(!checkItem($theArea, tips.notEmptyOfPostContent)) return;
                    
                    var 
                        successFn = function(m) {
                            if (m.status === 1) {
                                //当前回复列表高度
                                var nowObjHeight = $nowreplyObj.height();
                                //设置当前回复列表溢出隐藏
                                $nowreplyObj.css({height:nowObjHeight+'px',overflow:'hidden'});
                                //紧接着从后台接收一条回复并插入到当前回复列表中
                                $nowreplyObj.append(m.msg);
                                //获得当前回复列表中最后一个回复(即本条插入的回复)高度
                                var lastheight = $nowreplyObj.find('.replys:last').height();
                                //滑动显示出来
                                $nowreplyObj.animate({
                                    height:nowObjHeight+lastheight+21+'px' //21为内补丁和边框
                                },'slow');
                                //回复完成清空文本域
                                $theArea.val('');
                            } else {
                                webUtil.boxalert({msg: m.msg});
                            }
                        },
                        data = $('input.artid').length ? {type: 'art', replytext: replytext, associd: associd, receiver: receiver} : $('input.aid').length ? {type: 'alb', album_replytext: replytext, album_associd: associd} : {type: 'gub', replytext: replytext, associd: associd},
                        ajaxObj = {
                            url: 'rplarticle.php',
                            data: data,
                            success: successFn,
                            isloading: false
                        };

                    webUtil.ajaxRequest(ajaxObj);
                };

            //打开回复
            $commentlist.on('click', '.replay', openReply);
            //提交回复
            $user_text.on('click', '.persubmit_btn', submitReply);
        }
    }).init();

    //前台区块
    ({
        init: function() {
            initialize(this);
        },
        
        //加载分类下的更多文章
        rollLoadMoreArts: function() {
            var 
                $classartInner = $('.classartInner'),
                $artlistWrap = $classartInner.find('ul'),
                $getMoreLine = $classartInner.find('.getMoreLine'),
                category = $('.topNav .category').find('.inav_current').text().toUpperCase(),
                winHeight, scrollTop, timeid, currenCount,
                //需要加载的总数量
                totalCount = $artlistWrap.data('totalcount'),
                //每次加载数量
                loadCount = 5,
                //是否开启请求，默认开启
                openRequest = true,
                updateTip = function(tip) {
                    $getMoreLine.text(tip);
                },
                successFn = function(m) {
                    //如果结果为空，视为加载失败
                    if (!m.msg) {
                        //程序出错时更新提示
                        updateTip('抱歉，数据加载失败，请重试。');
                        //标识本次加载失败
                        $getMoreLine.data('loadFail', true);
                    }
                    //更新数据
                    $artlistWrap.append(m.msg);
                    //标识可以开启下次请求
                    openRequest = true;
                },
                errorFn = function() {
                    //程序出错时更新提示
                    updateTip('抱歉，数据加载失败，请重试。');
                    //标识本次加载失败
                    $getMoreLine.data('loadFail', true);
                    //标识可以开启下次请求
                    openRequest = true;
                },
                ajaxObj = {
                    url: 'json.php',
                    data: {category: category},
                    success: successFn,
                    errcallback: errorFn,
                    isloading: false
                },
                sendRequestBefore = function() {
                    if ($getMoreLine[0] && $getMoreLine.offset().top < winHeight + scrollTop) {
                        //当前列表中文章的数量
                        currenCount = $artlistWrap.find('li').length;
                        //每次加载文章数量
                        ajaxObj.data.range = currenCount+', '+loadCount;
                        //如果已加载数量大于或等于总数量
                        if (currenCount >= totalCount) {
                            //更新提示
                            updateTip('报告，'+totalCount+'条数据已全部加载完毕。');
                        } else {
                            //在上次响应完成（成功或失败）之后才能发起请求
                            if (openRequest) {
                                webUtil.ajaxRequest(ajaxObj);
                            }
                            //标识当前处于响应未完成状态，不能开启请求
                            openRequest = false;
                        }
                    }
                },
                sendRequest = function() {
                    //不是分类页面不请求
                    if (!category) return;
                    //加载失败后滚动页面重新启动请求的同时更新提示
                    if ($getMoreLine.data('loadFail')) {
                        //更新提示
                        updateTip('正在加载更多数据，请稍后...');
                        //清除加载失败标识
                        $getMoreLine.data('loadFail', false);
                    }
                    //实时获取窗口高度和滚动条滚动距离
                    winHeight = $win.height();
                    scrollTop = $win.scrollTop();
                    //请求数据的时间间隔为0.2秒
                    if (timeid) clearTimeout(timeid);
                    timeid = setTimeout(sendRequestBefore, timecell);
                };

            //分类页面才绑定事件
            if (category) {
                sendRequest();
                $win.on('scroll.loadmore, resize.loadmore', sendRequest);
            }
        },

        //热门文章/随机文章浮层操作
        artLayer: function() {
            var 
                $wrapper = $('.mainContent'),
                $hotArts = $wrapper.find('.hotArts'),
                $hotArt_first = $hotArts.eq(0),
                $hotArt_second = $hotArts.eq(1),
                setLayer = function() {
                    var 
                        $this = $(this),
                        sibling_z = $this.siblings('.hotArts').css('z-index');

                    $this.css({
                        'z-index': Number(sibling_z)+1,
                        'background': '#fff'
                    });
                },
                closeLayer = function(e) {
                    e.preventDefault();
                    $(this).parent().css({
                        '-webkit-transition': 'all 1s ease',
                        '-moz-transition': 'all 1s ease',
                        '-o-transition': 'all 1s ease',
                        'transition': 'all 1s ease',
                        '-webkit-transform': 'scale(0) rotateY(720deg)',
                        '-moz-transform': 'scale(0) rotateY(720deg)',
                        '-o-transform': 'scale(0) rotateY(720deg)',
                        'transform': 'scale(0) rotateY(720deg)'
                    });
                    //不支持transform的浏览器直接隐藏
                    if (!Modernizr.csstransforms) {
                        elemsh($(this).parent(), false);
                    }
                };

            //绑定拖动事件
            webUtil.dialogMove($hotArt_first, $hotArt_first.children('h2'));
            webUtil.dialogMove($hotArt_second, $hotArt_second.children('h2'));

            //调整拖动浮层的垂直高度z-index
            $wrapper.on('mousedown', '.hotArts', setLayer);
            //关闭浮层
            $hotArts.on('click', '.W_close_color', closeLayer);
        },

        //网站搜索栏操作
        searchLine: function() {
            var 
                $mainWrapper = $('.mainWrapper'),
                $range_txt = $mainWrapper.find('.range_txt'),
                $condition = $mainWrapper.find('.condition'),
                $search_range = $mainWrapper.find('.search_range'),
                rangListShow = function() {
                    //增大父层的z-index
                    $mainWrapper.find('.topNav').css('z-index', 2);
                    //显示列表
                    elemsh($range_txt, true);
                },
                rangListHide = function() {
                    //还原父层的z-index
                    $mainWrapper.find('.topNav').css('z-index', 1);
                    //隐藏列表
                    elemsh($range_txt, false);
                },
                lineHight = function() {
                    $(this).addClass('selected');
                },
                noLineHight = function() {
                    $(this).removeClass('selected');
                },
                selectItem = function() {
                    var $this = $(this);
                    //添加提示
                    $range_txt.prevAll('i').text($this.attr('title'));
                    //保存用户选择的搜索范围
                    $search_range.val($('input[type=hidden]', $this).val());
                    elemsh($range_txt, false);
                    if ($condition.val() !== tips.placeholderOfSearchArticle && $condition.val() !== '') {
                        webUtil.loading(true, 2);
                        //选择后立即搜索
                        $this.closest('form').submit();
                    }
                },
                submitSearch = function() {
                    //检测搜索内容
                    if (!checkItem($condition, tips.notEmptyOfSearchKey)) return false;
                    if (!checkItem($condition.val()===tips.placeholderOfSearchArticle, tips.notEmptyOfSearchKey, $condition)) return false;
                    //检测搜索范围
                    if (!checkItem($search_range.val(), tips.notEmptyOfSearchType)) return false;
                    //加载缓冲icon
                    webUtil.loading(true, 2);
                };

            //展开搜索范围
            $mainWrapper.find('.range_btn').hover(rangListShow, rangListHide)
            //提交搜索
            .end().on('click', '.to_search', submitSearch);
            //高亮当前选项
            $range_txt.find('.modify_search').hover(lineHight, noLineHight)
            //选择搜索范围
            .on('click', selectItem);
        },

        //首页排序方式切换功能
        indexOrder: function() {
            var 
                //显示用户名片timeid
                cardtime = null,
                //移入用户名片timeid
                cardtime2 = null,
                $indexInner = $('.indexInner'),
                $disbyorder = $indexInner.find('.dis_by_order'),
                //首页切换成功回调方法
                toggleSuccess = function(arg) {
                    if ($.type(arg[0]) !== 'object' || $.type(arg[1]) !== 'object') {
                        throw new Error('arg[0] and arg[1] must object.');
                    }

                    var 
                        elem = arg[0],
                        that = arg[1],
                        isappend = arg[2],
                        type = that.attr('type'),
                        rdom = '';

                    return function(m) {
                        if (m.status === 1) {
                            $.each(m.arts, function(i,d) {
                                if (d.article_content.length > 100) {
                                    d.article_content = d.article_content.substr(0,100)+'..';
                                }

                                var rstr = ['<div class="sub-item wb-clr">',
                                            '<div class="pic usertip-wrapper">',
                                                '<input type="hidden" value="'+d.user_name+'" class="ta-name" />',
                                                '<a href="perspace.php?user='+d.user_name+'" class="face">',
                                                    '<img src="'+d.userphoto+'" alt="'+d.user_name+'" />',
                                                '</a>',
                                                '<a href="perspace.php?user='+d.user_name+'" class="back"><span><i>签名档:</i><br />'+(d.signature ? d.signature : '这家伙没有签名档')+'</span></a>',
                                            '</div>',
                                            '<div class="information">',
                                                '<div class="info_title">',
                                                    '<a href="separticle.php?artid='+d.topics_id+'" title="'+d.article_title+'" target="_blank">'+d.article_title+'</a>',
                                                '</div>',
                                                '<div class="info_text">'+d.article_content+'</div>',
                                                '<div class="info_time">'+d.arttime+'</div>',
                                            '</div>',
                                        '</div>'].join('');

                                rdom += rstr;
                            });
                            
                            if (m.arts.length >= 9) {
                                rdom += '<p class="gasket"></p><a href="#" class="openmore" type='+type+'>点击展开更多</a>';
                            }
                        } else {
                            rdom = '<p class="empty-content">暂无文章</p>';
                        }

                        if (isappend) {
                            //移除当前bar
                            that.remove();
                            //渲染dom片段
                            elem.append(rdom);
                        } else {
                            //渲染dom片段
                            elem.html(rdom);
                        }
                    };
                },
                dataToggle = function(e) {
                    e.preventDefault();
                    var 
                        $this = $(this),
                        i = $this.index(),
                        type = $this.attr('type'),
                        $the_disbyorder = $disbyorder.eq(i);

                    $this.addClass('selected').siblings().removeClass('selected');
                    elemsh($disbyorder.not(i), false);
                    elemsh($disbyorder.eq(i), true);
                    $the_disbyorder.find('.empty-content').text('正在努力加载···');

                    if ($the_disbyorder.children('.sub-item').length <= 0) {
                        var
                            ajaxObj = {
                                url: 'json.php',
                                data: {act: 'toggle', type: type},
                                success: toggleSuccess([$the_disbyorder, $this, false]),
                                isloading: false,
                                errcallback: function() {
                                    $the_disbyorder.find('.empty-content').text('加载失败，请重试。');
                                }
                            };

                        webUtil.ajaxRequest(ajaxObj);
                    }
                },
                appendMore = function(e) {
                    e.preventDefault();
                    var 
                        $this = $(this),
                        type = $this.attr('type'),
                        $the_disbyorder = $(this).parent('.dis_by_order'),
                        ajaxObj = {
                            url: 'json.php',
                            data: {act: 'toggle', type: type, oper: 'more', nowcount: $the_disbyorder.find('.sub-item').length},
                            success: toggleSuccess([$the_disbyorder, $this, true]),
                            isloading: false,
                            errcallback: function() {
                                $this.text('点击展开更多');
                            }
                        };

                    $this.text('正在努力加载···');
                    webUtil.ajaxRequest(ajaxObj);
                },
                //显示用户名片
                cardShow = function() {
                    var 
                        user = $(this).children('input.ta-name').val(),
                        userphoto = $(this).find('img').attr('src'),
                        pos = $(this)[0].getBoundingClientRect(),
                        sctop = $win.scrollTop(),
                        scleft = $win.scrollLeft(),
                        top = sctop + pos.top + pos.height/8,
                        left = scleft + pos.left + pos.width,
                        card_top = ['<div class="usertip wb-clr">',
                                        '<div class="tipphoto">',
                                            '<img src="'+userphoto+'" alt="'+user+'" height="50" width="50" />',
                                            '<a href="perspace.php?user='+user+'" target="_blank" class="perspace_enter">个人空间</a>',
                                            '<a href="#" class="addfriend">+加关注</a>',
                                        '</div>',
                                        '<div class="tipinfo">',
                                            '<span>笔名：<i>'+user+'</i></span>',
                                            '<span>最新发表文章：</span>',
                                            '<ol>'].join(''),
                        card_bottom =      ['</ol>',
                                        '</div>',
                                    '</div>'].join(''),
                        //获取名片中的文章列表
                        getMiddle = function(data) {
                            var strArr = [];
                            if (data.length > 0) {
                                $.each(data, function(i, d) {
                                    strArr.push('<li><a href="separticle.php?artid='+d.topics_id+'" target="_blank">'+d.article_title+'</a></li>');
                                });
                            } else {
                                strArr.push('<span><i>暂无文章</i></span>');
                            }
                            return strArr.join('');
                        },
                        //用户名片定位
                        setPos = function(obj, top, left) {
                            obj.css({
                                position: 'absolute',
                                top: top+'px',
                                left: left+'px'
                            });
                        },
                        successFn = function(m) {
                            if (m.status === 1) {
                                //记录该用户的数据
                                cardmap[user] = {threeArts: m.threeArts};
                                //插入dom元素
                                $bdy.append(card_top+getMiddle(m.threeArts)+card_bottom);
                                //名片定位
                                setPos($bdy.children('.usertip'), top, left);
                            }
                        },
                        ajaxObj = {
                            url: 'json.php',
                            data: {user: user, act: 'card'},
                            success: successFn,
                            isloading: false,
                            unloading: false,
                            showerror: false
                        };

                    cardtime = setTimeout(function() {
                        //第一次从服务器读
                        if (!cardmap[user]) {
                            webUtil.ajaxRequest(ajaxObj);
                        }
                        //从缓存读
                        else {
                            //插入dom元素
                            $bdy.append(card_top+getMiddle(cardmap[user].threeArts)+card_bottom);
                        }
                        //加关注
                        $bdy.on('click', '.addfriend', {name: user}, business.addAttention);
                        //名片定位
                        setPos($bdy.children('.usertip'), top, left);
                    }, timecell);
                },
                //隐藏用户名片
                cardHide = function() {
                    clearTimeout(cardtime);
                    cardtime2 = setTimeout(function() {
                        $bdy.children('.usertip').remove();
                    }, timecell);
                },
                cardOver = function() {
                    clearTimeout(cardtime2);
                },
                cardOut = function() {
                    cardHide();
                };

            //数据切换
            $indexInner.on('click', '.irboxtop a', dataToggle)
            //展开更多
            .on('click', '.openmore', appendMore);
            //展示用户名片
            $bdy.on('mouseenter', '.usertip-wrapper', cardShow)
            //隐藏用户名片
            .on('mouseleave', '.usertip-wrapper', cardHide)
            //移到名片上
            .on('mouseenter', '.usertip', cardOver)
            //从名片上移出
            .on('mouseleave', '.usertip', cardOut);
        },

        //网站外链编辑功能
        outLinksEdit: function() {
            var 
                $mainWrapper = $('.mainWrapper'),
                $fieldset = $mainWrapper.find('.footer fieldset'),
                $editOutLinksLayer = $('#editOutLinksLayer'),
                $addOutLinks = $fieldset.find('.addOutLinks'),
                submit = function(act) {
                    var 
                        linktitle = getObjByName('linktitle:self', $editOutLinksLayer),
                        linkhref = getObjByName('linkhref:self', $editOutLinksLayer),
                        linktitleVal = $.trim(linktitle.val()),
                        linkhrefVal = $.trim(linkhref.val()),
                        linkid = $editOutLinksLayer.data('editInfo') && $editOutLinksLayer.data('editInfo').linkid,
                        oldtitle = $editOutLinksLayer.data('editInfo') && $editOutLinksLayer.data('editInfo').linktitle_origin,
                        oldhref = $editOutLinksLayer.data('editInfo') && $editOutLinksLayer.data('editInfo').linkhref_origin,
                        $outLink = $editOutLinksLayer.data('editInfo') && $editOutLinksLayer.data('editInfo').outLink,
                        ajaxObj = {
                            url: 'rplarticle.php',
                            data: {
                                act: act,
                                linktitle: linktitleVal,
                                linkhref: linkhrefVal
                            },
                            success: function(m) {
                                webUtil.boxalert({msg: m.msg});
                                //添加成功后隐藏编辑框并更新列表
                                if (m.status === 1) {
                                    if (act === 'add') {
                                        var $addItem = $('<a href="'+linkhrefVal+'" title="'+linktitleVal+'" target="_blank" class="outLink" data-linkid="'+m.linkid+'"><span class="title">'+linktitleVal+'</span><span class="delOutLinks"></span><span class="editOutLinks"></span></a>');
                                        $addItem.insertBefore($addOutLinks);
                                    } else if (act === 'edit') {
                                        $outLink.attr({title: linktitleVal, href: linkhrefVal}).find('.title').text(linktitleVal);
                                    }
                                    //关闭编辑框
                                    webUtil.undialog($editOutLinksLayer);
                                }
                            },
                            unloading: false
                        };

                    //检测外链信息是否填写
                    if (!checkItem(linktitle, tips.notEmptyOfOutLinksTitle)) return;
                    if (!checkItem(linkhref, tips.notEmptyOfOutLinksHref)) return;
                    if (!checkItem(!urlReg.test(linkhref.val()), tips.notConformOfOutLinksHref)) return;

                    //保存外链
                    if (act === 'edit' && linkid) {
                        if (!checkItem(oldtitle===linktitleVal && oldhref===linkhrefVal, tips.notChangeOfOutLinks)) return;
                        $.extend(ajaxObj.data, {linkid: linkid});
                    }
                    webUtil.ajaxRequest(ajaxObj);
                };

            $fieldset.on('click', '.addOutLinks', function(ev) {
                ev.preventDefault();
                //清空原来的数据
                $editOutLinksLayer.data('editInfo') && ($editOutLinksLayer.data('editInfo').edit = false);
                getObjByName('linktitle:self', $editOutLinksLayer).val('');
                getObjByName('linkhref:self', $editOutLinksLayer).val('');

                //显示编辑框
                webUtil.dialogCenter($editOutLinksLayer, 1);
                webUtil.dialogMove($editOutLinksLayer, $editOutLinksLayer.find('h2'));
            }).on('click', '.delOutLinks', function(ev) {
                ev.preventDefault();
                ev.stopPropagation();
                var 
                    $outLink = $(this).closest('.outLink'),
                    linkid = $outLink.data('linkid'),
                    ajaxObj = {
                        url: 'rplarticle.php',
                        data: {
                            act: 'delete',
                            linkid: linkid
                        },
                        success: function(m) {
                            webUtil.boxalert({msg: m.msg});
                            //更新列表
                            $outLink.remove();
                        }
                    };

                Boxy.confirm({content:'确定要删除这个外链吗?', buttonText:['确定','取消']}, function() {
                    webUtil.ajaxRequest(ajaxObj);
                }, {title:'外链删除'});
            }).on('click', '.editOutLinks', function(ev) {
                ev.preventDefault();
                ev.stopPropagation();
                var 
                    $outLink = $(this).closest('.outLink'),
                    linkid = $outLink.data('linkid'),
                    linktitle_origin = $outLink.attr('title'),
                    linkhref_origin = $outLink.attr('href');

                //在编辑框中填入要编辑的数据
                $editOutLinksLayer.data('editInfo', {
                    edit: true,
                    linkid: linkid,
                    linktitle_origin: linktitle_origin,
                    linkhref_origin: linkhref_origin,
                    outLink: $outLink
                });
                $editOutLinksLayer.find('[name=linktitle]').val(linktitle_origin);
                $editOutLinksLayer.find('[name=linkhref]').val(linkhref_origin);

                //显示编辑框
                webUtil.dialogCenter($editOutLinksLayer, 1);
                webUtil.dialogMove($editOutLinksLayer, $editOutLinksLayer.find('h2'));
            });

            $editOutLinksLayer.on('click', '.submit', function() {
                if ($editOutLinksLayer.data('editInfo') && $editOutLinksLayer.data('editInfo').edit) {
                    submit('edit');
                } else {
                    submit('add');
                }
            }).on('click', '.cancel', function() {
                webUtil.undialog($editOutLinksLayer);
            });
        },

        //网站安装功能
        siteInstall: function() {
            var 
                $installWrapper = $('.installWrapper'),
                $tipblock = $installWrapper.find('.tipblock'),
                $waiting = $tipblock.find('.waiting'),
                $dising = $tipblock.find('.dising'),
                installSubmit = function(e) {
                    var 
                        $host = getObjByName('host:parent', $installWrapper),
                        $db_user = getObjByName('db_user:parent', $installWrapper),
                        $db_name = getObjByName('db_name:parent', $installWrapper),
                        $db_password = getObjByName('db_password:parent', $installWrapper),
                        successFn = function(m) {
                            if (m.status === 1) {
                                var tip = '<ul>'+m.msg+'</ul><p>成功安装 <span class="count">'+m.count+'</span> 个数据表。</p>';

                                if (m.count === 17) {
                                    tip += '<p>恭喜您，本程序 已经成功地安装完成。<br />基于安全的考虑，请手动删除 install 文件。<br /><br />前往 <a href="/">首页</a> 或 <a href="#">后台管理中心</a></p>';
                                } else {
                                    tip += '<p>抱歉，程序未安装成功。请检查后重新安装!</p>';
                                }

                                $dising.html(tip);
                            } else {
                                $dising.html('<ul>'+m.msg+'</ul>');
                            }
                            //隐藏loading...
                            elemsh($waiting, false);
                        },
                        data = {
                            host: $host.val(),
                            db_user: $db_user.val(),
                            db_password: $db_password.val(),
                            db_name: $db_name.val()
                        },
                        ajaxObj = {
                            url: 'install.php',
                            data: data,
                            success: successFn,
                            isloading: false,
                            unloading: false
                        };

                    //检测数据库主机
                    if (!checkItem($host, tips.notEmptyOfDBHost)) return;
                    //检测数据库拥有者
                    if (!checkItem($db_user, tips.notEmptyOfDBOwner)) return;
                    //检测数据库名称
                    if (!checkItem($db_name, tips.notEmptyOfDBName)) return;
                    //显示弹窗
                    elemsh([$tipblock, $waiting], true);
                    $dising.empty();
                    webUtil.ajaxRequest(ajaxObj);
                },
                closeLayer = function(e) {
                    e.preventDefault();
                    elemsh($tipblock, false);
                    webUtil.unloading();
                };

            //提交安装
            $installWrapper.on('click', '.insbtn .persubmit_btn', installSubmit);
            //关闭状态弹窗
            $tipblock.on('click', '.W_close_color', closeLayer);
        },

        //网站新闻展示功能
        newsShow: function() {
            var 
                $newsInner = $('.newsInner'),
                $main_title = $newsInner.find('.main_title'),
                $cllist = $newsInner.find('.classlist'),
                $artList = $newsInner.find('.artList'),
                $page = $artList.find('.page'),
                $news_toggle = $artList.find('.news_toggle'),
                cachecontent = '',
                bigLHSetting = function($this, index, bgclass, $ftd, rssurl) {
                    //大分类高亮
                    $this.addClass('on').siblings().removeClass('on');
                    //显示对应小分类
                    elemsh($cllist.not(index), false);
                    elemsh($cllist.eq(index), true);
                    //当前大类下其他个小类去除高亮,高亮第一个小类
                    $ftd.find('a').addClass('selected').closest('tr').siblings('tr').find('a').removeClass('selected');
                    //当前大分类提示
                    $artList.find('h2').text(bgclass + ' - ' + $ftd.text());
                    //保存当前小类rss源
                    $page.find('input[type=hidden]').val(rssurl);
                    //高亮第一个分页
                    $page.find('a').last().addClass('selected').siblings('a').removeClass('selected');
                },
                smallLHSetting = function($this, bgcls, smcls, rssurl) {
                    //当前大分类提示
                    $artList.find('h2').text(bgcls + ' - ' + smcls);
                    //高亮当前小分类
                    $this.addClass('selected').closest('tr').siblings('tr').find('a').removeClass('selected');
                    //保存当前小类rss源
                    $page.find('input[type=hidden]').val(rssurl);
                    //高亮第一个分页
                    $page.find('a').last().addClass('selected').siblings('a').removeClass('selected');
                },
                bigClsSwitch = function() {
                    var $this = $(this);
                    if($this.hasClass('on')) return;

                    var 
                        index = $this.index(),
                        bgclass = $this.text(),
                        $itd = $cllist.eq(index).find('td'),
                        $ftd = $itd.first(),
                        rssurl = $ftd.find('a').attr('href');

                    //优先读取缓存，没有缓存数据才发送请求
                    if (cachecontent = $this.data('cachecontent')) {
                        //对应区域切换、高亮
                        bigLHSetting($this, index, bgclass, $ftd, rssurl);
                        //更新数据
                        $news_toggle.html(cachecontent);
                        return;
                    }

                    var
                        successFn = function(m) {
                            if (m.status === 1) {
                                //对应区域切换、高亮
                                bigLHSetting($this, index, bgclass, $ftd, rssurl);
                                //更新数据
                                $news_toggle.html(m.msg);
                                //一级tab数据缓存到本地
                                $this.data('cachecontent', m.msg);
                                //每个一级对应的二级的第一个tab数据缓存到本地
                                $ftd.find('a').data('cachecontent', m.msg);
                            } else {
                                webUtil.boxalert({msg: m.msg});
                            }
                        },
                        ajaxObj = {
                            url: 'getnews.php',
                            data: {rssurl: rssurl, xl: true},
                            success: successFn
                        };

                    webUtil.ajaxRequest(ajaxObj);
                },
                smallClsSwitch = function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    if($this.hasClass('selected')) return;

                    var 
                        //当前小分类名字
                        smcls = $this.text(),
                        //当前大类名字
                        bgcls = $main_title.find('li.on').text(),
                        rssurl = $this.attr('href');

                    //优先读取缓存，没有缓存数据才发送请求
                    if (cachecontent = $this.data('cachecontent')) {
                        //对应区域切换、高亮
                        smallLHSetting($this, bgcls, smcls, rssurl);
                        //更新数据
                        $news_toggle.html(cachecontent);
                        return;
                    }

                    var 
                        successFn = function(m) {
                            if (m.status === 1) {
                                //对应区域切换、高亮
                                smallLHSetting($this, bgcls, smcls, rssurl);
                                //更新数据
                                $news_toggle.html(m.msg);
                                //缓存数据到本地
                                $this.data('cachecontent', m.msg);
                            } else {
                                webUtil.boxalert({msg: m.msg});
                            }
                        },
                        ajaxObj = {
                            url: 'getnews.php',
                            data: {rssurl: rssurl, xl: true},
                            success: successFn
                        };

                    webUtil.ajaxRequest(ajaxObj);
                };

            //新闻大类切换
            $main_title.on('click', 'li', bigClsSwitch);
            //新闻小类切换
            $cllist.on('click', 'a', smallClsSwitch);
        },

        //个人中心页面功能
        perCenter: function() {
            var 
                $percenterInner = $('.percenterInner'),
                $display_information = $('#display_information'),
                $modify_information = $('#modify_information'),
                $modify_password = $('#modify_password'),
                $manageblock = $percenterInner.find('.manageblock'),
                $collectblock = $percenterInner.find('.collectblock'),
                openChange = function() {
                    elemsh($display_information, false);
                    elemsh($modify_information, true);
                },
                saveChange = function() {
                    var 
                        $mod_sign = $modify_information.find('.signtext textarea'),
                        $email = getObjByName('mod_email:parent', $modify_information),
                        $qq = getObjByName('mod_qq:parent', $modify_information),
                        $phone = getObjByName('mod_phone:parent', $modify_information),
                        email = $email.val(),
                        qq = $qq.val(),
                        phone = $phone.val(),
                        sex = getObjByName('sex:parent:checked', $modify_information, true),
                        signature = $mod_sign.val(),
                        $oldsex = $display_information.find('input.info_sex'),
                        $oldemail = $display_information.find('input.info_email'),
                        $oldqq = $display_information.find('input.info_qq'),
                        $oldphone = $display_information.find('input.info_phone'),
                        $oldsignature = $display_information.find('input.info_signature'),
                        oldsex = $oldsex.val(),
                        oldemail = $oldemail.val(),
                        oldqq = $oldqq.val(),
                        oldphone = $oldphone.val(),
                        oldsignature = $oldsignature.val();

                    //检测邮箱
                    if (!checkItem($email, tips.notEmptyOfEmail)) return;
                    if (!checkItem(!emailReg.test(email), tips.notConformRuleOfEmail, $email)) return;
                    //检测qq
                    if (!checkItem(qq!==''&&!qqReg.test(qq), tips.notConformRuleOfQQ, $qq)) return;
                    //检测手机号
                    if (!checkItem(phone!==''&&!phoneReg.test(phone), tips.notConformRuleOfPhone, $phone)) return;
                    //检测签名
                    if (!checkItem(signature.length>100, tips.notConformRuleOfSign, $mod_sign)) return;
                    //检测资料是否改变
                    if (!checkItem(oldsex===sex&&oldemail===email&&oldqq===qq&&oldphone===phone&&oldsignature===signature, tips.notChangeOfInformation)) return;

                    var 
                        successFn = function(m) {
                            //更新资料
                            $oldsex.val(sex);
                            $oldemail.val(email);
                            $oldqq.val(qq);
                            $oldphone.val(phone);
                            $oldsignature.val(signature);
                            webUtil.boxalert({msg: m.msg});
                        },
                        data = {
                            sex : sex,
                            email : email,
                            qq : qq,
                            phone : phone,
                            signature : signature,
                            modify_information : true
                        },
                        ajaxObj = {
                            url: 'modifypassword.php',
                            data: data,
                            success: successFn
                        };

                    webUtil.ajaxRequest(ajaxObj);
                },
                changePassword = function() {
                    var 
                        $modify_password_input = getObjByName('modify_password:self'),
                        $new_password_input = getObjByName('new_password:self');

                    //检测旧密码
                    if (!checkItem($modify_password_input, tips.notEmptyOfPassword)) return;
                    //检测新密码
                    if (!checkItem($new_password_input, '新'+tips.notEmptyOfPassword)) return;

                    var 
                        successFn = function(m) {
                            webUtil.boxalert({
                                msg: m.msg,
                                elem: $modify_password_input
                            });
                        },
                        data = {
                            modify_name : loginer,
                            modify_password : $modify_password_input.val(),
                            new_password : $new_password_input.val()
                        },
                        ajaxObj = {
                            url: 'modifypassword.php',
                            data: data,
                            success: successFn
                        };

                    webUtil.ajaxRequest(ajaxObj);
                },
                imgUploadWait = function() {
                    var $this = $(this);
                    //检测上传内容
                    if (!checkItem($this, '上传'+tips.notEmptyOfPostContent)) return false;
                    //检测图片格式
                    if (!checkItem(!imgReg.test($this.val()), tips.notConformRuleOfImage)) return false;
                    //提交表单
                    $this.closest('form').submit();
                    webUtil.loading(true, 1);
                },
                changeArts = function(e) {
                    var 
                        wrapper = e.target.parentNode.parentNode.parentNode,
                        $tInput = $(wrapper).find('input.subject'),
                        arttype = $(wrapper).find('input[type=radio]:checked'),
                        neEditor;

                    //编辑器类型
                    if (wrapper && wrapper.id === 'publishArticle') {
                        neEditor = new_editor;
                    }
                    else if (wrapper && wrapper.id === 'modifyArticle') {
                        neEditor = edt_editor;
                    }
                    //检测文章标题
                    if (!checkItem($tInput, tips.notEmptyOfArticleTitle)) return false;
                    //检测文章内容
                    if (!checkItem(neEditor.isEmpty(), tips.notEmptyOfArticleBody, neEditor)) return false;
                    //检测文章类型
                    if (!checkItem(arttype.length<=0, tips.notEmptyOfArticleType)) return false;
                    //加载缓冲icon
                    webUtil.loading(true, 1);
                },
                deleteArts = function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    Boxy.confirm({content:'确定要删除文章吗?', buttonText:['确定','取消']}, function() {
                        var 
                            article_id = $this.next('input[type=hidden]').val(),
                            $tbody = $manageblock.find('tbody'),
                            successFn = function(m) {
                                if (m.status === 1) {
                                    $this.parent('td').parent('tr').remove();

                                    if ($tbody.children('tr').length === 0) {
                                        $manageblock.html('<p>你还没有发表任何文章，现在<span class="towrite"><a href="percenter.php?act=is_posttxt">发表>>></a></span></p>');
                                    }
                                }
                            },
                            ajaxObj = {
                                url: 'percenter.php',
                                data: {act: 'artmanage', artid: article_id},
                                success: successFn
                            };

                        webUtil.ajaxRequest(ajaxObj);
                    }, {title:'删除提示'});
                },
                deleteCollect = function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    Boxy.confirm({content:'确定要删除文章收藏吗?', buttonText:['确定','取消']}, function() {
                        var 
                            article_id = $this.next('input[type=hidden]').val(),
                            $tbody = $collectblock.find('tbody'),
                            successFn = function(m) {
                                if (m.status === 1) {
                                    $this.parent('td').parent('tr').remove();

                                    if ($tbody.children('tr').length === 0) {
                                        $collectblock.html('<p>你还没有收藏任何文章!</p>');
                                    }
                                }
                            },
                            ajaxObj = {
                                url: 'percenter.php',
                                data: {act: 'artcollect', 'delid': article_id},
                                success: successFn
                            };

                        webUtil.ajaxRequest(ajaxObj);
                    }, {title:'删除提示'});
                };

            //修改个人资料
            $display_information.on('click', '.persubmit_btn', openChange);
            //保存个人资料
            $modify_information.on('click', '.persubmit_btn', saveChange);
            //修改密码
            $modify_password.on('click', '.persubmit_btn', changePassword);
            //上传图片前等待
            $('#waitpar').on('change', 'input[type=file]', imgUploadWait);
            //发表文章/修改文章
            $('#publishArticle, #modifyArticle').on('click', '.persubmit_btn', changeArts);
            //删除文章
            $manageblock.on('click', '.delart', deleteArts);
            //删除我的收藏
            $collectblock.on('click', '.delcollect', deleteCollect);
            //附件删除
            $percenterInner.on('click', '.post_upload_file .W_close_color', business.attachmentDelete)
            //上传附件前登录验证
            .on('click', '.uping [type=submit]', business.uploadBefore);
        },

        //资源页面功能
        resource: function() {
            var 
                $resourceInner = $('.resourceInner'),
                $dialog = $resourceInner.find('.post_upload_file'),
                $station = $resourceInner.find('.station'),
                resourceUpload = function(e) {
                    e.preventDefault();
                    var 
                        $this = $(this),
                        isopen = $this.data("open"),
                        dialog_h = $dialog.height()+10;

                    if (!isopen || isopen === "off") {
                        $station.animate({height:dialog_h+'px'}, 1000, function() {
                            $station.css("height","auto");
                        });
                        $dialog.slideDown(1000);
                        $this.data("open", "on");
                    }
                    else if (isopen === 'on') {
                        $dialog.slideUp(1000);
                        $station.animate({height:0}, 1000);
                        $this.data("open", "off");
                    }
                };
            //资源文件上传
            $resourceInner.on('click', '.upload_ico a', resourceUpload)
            //附件删除
            .on('click', '.post_upload_file .W_close_color', business.attachmentDelete)
            //上传附件前登录验证
            .on('click', '.uping [type=submit]', business.uploadBefore);
        },

        //密码找回功能
        forgetPassword: function() {
            var 
                $retrpwdWrapper = $('.retrpwdWrapper'),
                changeSubmit = function() {
                    var 
                        $ckcode = getObjByName('checkCode:parent', $retrpwdWrapper),
                        $name = getObjByName('user_name:parent', $retrpwdWrapper),
                        $email = getObjByName('user_email:parent', $retrpwdWrapper),
                        date = new Date(),
                        nowtime = date.getTime(),
                        lasttime = webUtil.getCookie('lasttime');

                    if (!!lasttime) {
                        var 
                            maxtime = 600000,
                            diff_time = nowtime-lasttime,
                            diff_min = Math.ceil((maxtime-diff_time)/60000);

                        if (!checkItem(diff_time<maxtime, '发送邮件的时间间隔是10分钟，还剩下大约：'+diff_min+'分钟。')) return;
                    }

                    //判断用户名是否填写
                    if (!checkItem($name, tips.notEmptyOfName)) return;
                    //判断email是否填写
                    if (!checkItem($email, tips.notEmptyOfEmail)) return;
                    //判断email是否填写正确
                    if (!checkItem(!emailReg.test($email.val()), tips.notConformRuleOfEmail, $email)) return;
                    //判断验证码是否填写
                    if (!checkItem($ckcode, tips.notEmptyOfCheckCode)) return;
                
                    var 
                        successFn = function(m) {
                            webUtil.boxalert({
                                msg: m.msg,
                                callback: function() {
                                    $retrpwdWrapper.find('img.codeimg').attr('src','checkcode.php?t='+Math.random());
                                    if (m.status === 1) {
                                        var date = new Date();
                                        lasttime = date.getTime();
                                        webUtil.setCookie('lasttime',lasttime,1);
                                    }
                                    if (m.status === 0 && m.type === 1) {
                                        $name.focus();
                                    }
                                    if (m.status === 0 && m.type === 2) {
                                        $ckcode.focus();
                                    }
                                }
                            });
                        },
                        data = {
                            name : $name.val(),
                            email : $email.val(),
                            check_code : $ckcode.val()
                        },
                        ajaxObj = {
                            url: 'sendmail.php',
                            data: data,
                            success: successFn
                        };

                    webUtil.ajaxRequest(ajaxObj);
                };

            //提交修改
            $retrpwdWrapper.on('click', '.skin-blue .persubmit_btn', changeSubmit);
        },

        //文章detail页功能
        articleDetail: function() {
            var 
                $separtInner = $('.separtInner'),
                runCode = function() {
                    var winname = win.open('', '_blank', '');
                    winname.document.open('text/html','replace');
                    winname.opener = null;
                    winname.document.write($(this).parent().prev('textarea').val());
                    winname.document.close();
                },
                copyCode = function() {
                    var $textarea = $(this).parent().prev('textarea');
                    $textarea.select();
                    doc.execCommand('Copy');
                },
                quote = function(e) {
                    e.stopPropagation();
                    var 
                        $this = $(this),
                        $theli = $this.closest('li'),
                        $quoteTxt = $theli.find('.quote_txt'),
                        quote_author = $theli.find('.quote_author').html(),
                        quote_txt = $quoteTxt.html(),
                        quote_time = $theli.find('.quote_time').html(),
                        runcode = $quoteTxt.next('div').find('.runtextarea').html(),
                        precode = $quoteTxt.nextAll('pre').html(),
                        quotecode = $quoteTxt.next('blockquote').html();

                    //预置的内容
                    if (precode) {
                        quote_txt += precode;
                    }
                    //运行的内容
                    if (runcode) {
                        quote_txt += runcode;
                    }
                    //引用的内容
                    if (quotecode) {
                        quote_txt += quotecode;
                    }
                    //引用的字符串
                    quote_txt = "<blockquote style=\"overflow-x:hidden;width:50%;border:dashed 1px gray;margin:10px 0;padding:10px;word-wrap:break-word;\">引自：<cite>"+quote_author+" </cite>&nbsp;&nbsp;于 <ins>"+quote_time+"</ins> 发表的评论<br>引用内容：<br><br><q>"+quote_txt+"</q></blockquote><br/>";

                    //插入到编辑器光标处
                    editor.insertHtml(quote_txt);
                },
                check = function() {
                    //判断是否登录
                    if (!checkItem(!loginer, tips.needLoginedOfOprate)) return false;
                    //判断内容是否空
                    if (!checkItem(editor.isEmpty(), tips.notEmptyOfPostContent, editor)) return false;
                    //加载缓冲icon
                    webUtil.loading(true, 1);
                };

            //修复文章中历史遗留问题（内容不换行）
            $separtInner.find('.userart').find('[style*=nowrap]').removeAttr('style');
            //运行代码
            $separtInner.on('click', '.runing', runCode)
            //复制代码
            .on('click', '.copying', copyCode)
            //引用
            .on('click', '.quote', quote)
            //收藏文章
            .on('click', '.todoCollect', business.artCollect)
            //提交前验证
            .on('click', '#comment .persubmit_btn', check)
            //附件删除
            .on('click', '.post_upload_file .W_close_color', business.attachmentDelete)
            //上传附件前登录验证
            .on('click', '.uping [type=submit]', business.uploadBefore);
        },

        //代码高亮懒加载
        highlighterLazyLoad: function() {
            var 
                timeid, highlighterlink, pre = $('pre.js, pre.css, pre.html, pre.php, pre.xml'),
                exec = function() {
                    //已加载过资源取消滚动事件绑定
                    if ($win.data('highlighter')) {
                        $win.off('scroll.highlighter, resize.highlighter', exec);
                        return;
                    }

                    //一定频率的监控
                    if (timeid) clearTimeout(timeid);
                    timeid = setTimeout(function() {
                        if (pre[0] && pre.offset().top < $win.height() + $win.scrollTop()) {
                            //加载高亮组件的样式表
                            highlighterlink = document.createElement('link');
                            highlighterlink.rel = 'stylesheet';
                            highlighterlink.href = 'SyntaxHighlighter/Styles/SyntaxHighlighter.min.css';
                            document.getElementsByTagName('head')[0].appendChild(highlighterlink);
                            //加载高亮组件的脚本
                            asynLoadJs(["SyntaxHighlighter/Scripts/compressor/shx.min.js"], function() {
                                dp.SyntaxHighlighter.ClipboardSwf = "SyntaxHighlighter/Scripts/clipboard.swf";
                                dp.SyntaxHighlighter.HighlightAll();
                            });
                            //标识资源加载完毕
                            $win.data('highlighter', true);
                        }
                    }, timecell);
                };

            if (pre.length > 0) {
                //初始化时查找一次
                exec();
                //在页面滚动和窗口改变时监控
                $win.on('scroll.highlighter, resize.highlighter', exec);
            }
        },

        //分享栏懒加载
        sharelineLazyLoad: function() {
            var 
                timeid, sharebar = $('.sharebar'),
                exec = function() {
                    //已加载过资源取消滚动事件绑定
                    if ($win.data('shareline')) {
                        $win.off('scroll.shareline, resize.shareline', exec);
                        return;
                    }

                    //一定频率的监控
                    if (timeid) clearTimeout(timeid);
                    timeid = setTimeout(function() {
                        if (sharebar[0] && sharebar.offset().top < $win.height() + $win.scrollTop()) {
                            //加载分享组件脚本
                            asynLoadJs(["http://v3.jiathis.com/code/jia.js?uid=1358474197032104"]);
                            //标识资源加载完毕
                            $win.data('shareline', true);
                        }
                    }, timecell);
                };

            if (sharebar.length > 0) {
                //初始化时查找一次
                exec();
                //在页面滚动和窗口改变时监控
                $win.on('scroll.shareline, resize.shareline', exec);
            }
        },

        //编辑器懒加载
        editorLazyLoad: function() {
            var 
                timeid, commentarea = $('.commentarea'),
                exec = function() {
                    console.log(0)
                    //已加载过资源取消滚动事件绑定
                    if ($win.data('editor')) {
                        $win.off('scroll.editor, resize.editor', exec);
                        return;
                    }

                    //一定频率的监控
                    if (timeid) clearTimeout(timeid);
                    timeid = setTimeout(function() {
                        if (commentarea[0] && commentarea.offset().top < $win.height() + $win.scrollTop()) {
                            console.log(1)
                            //加载编辑器脚本
                            asynLoadJs(["data/editor/kindeditor-min.js"], function() {
                                asynLoadJs(["data/editor/lang/zh_CN.js"], function() {
                                    win.editor = KindEditor.create('#comment_text', {
                                        width : '99.7%',
                                        height: '200px',
                                        resizeType : 1,
                                        allowFileManager : true,
                                        newlineTag : 'br',
                                        urlType : 'absolute',
                                        items : ['fullscreen', 'preview', 'link', 'unlink', 'clearhtml', 'attachment', 'code', 'runcode', 'emoticons', 'image', 'forecolor', 'hilitecolor', 'bold',
                            'italic', 'strikethrough']
                                    });
                                    //显示编辑器
                                    commentarea.find('.station').removeClass('wb-hide');
                                    commentarea.find('.comment_txt').removeClass('wb-hide');
                                });
                            });
                            //标识资源加载完毕
                            $win.data('editor', true);
                        }
                    }, timecell);
                };

            if (commentarea.length > 0) {
                //初始化时查找一次
                exec();
                //在页面滚动和窗口改变时监控
                $win.on('scroll.editor, resize.editor', exec);
            }
        }
    }).init();

    //后台区块
    ({
        init: function() {
            initialize(this);
        },
        
        //我的空间主页功能
        spaceIndex: function() {
            var 
                $perspaceInner = $('.perspaceInner'),
                $sidebar = $('.sidebar'),
                $sendsms = $('#sendsms'),
                name = getObjByName('ta-name:prev', $sidebar.find('.perspace_addfriend'), true);
            //收藏文章
            $perspaceInner.on('click', '.todoCollect', business.artCollect);
            //打开发私信弹窗
            $sidebar.on('click', '.sendmessage', {elem: $sendsms}, business.openMessageBox)
            //非名片加关注
            .on('click', '.perspace_addfriend', {name: name}, business.addAttention);
        },

        //我的相册页面功能
        spaceAlbum: function() {
            var 
                $albumInner = $('.albumInner'),
                $disArts_box = $albumInner.find('.disArts_box'),
                $box_first = $disArts_box.eq(0),
                $box_second = $disArts_box.eq(1),
                $editObj = $('#edit_album'),
                $editTitleLine = $editObj.find('h3'),
                $uploadBlock = $('#uploadBlock'),
                $album_photos = $albumInner.find('.album_photos'),
                $album_photosUl = $album_photos.find('ul'),
                $manage = $disArts_box.find('.manage'),
                $checkboxs = $album_photosUl.find('input[type=checkbox]'),
                photoIndex = 0,
                $big_ph = $('#big_ph'),
                $opt_img = $big_ph.find('.opt_img'),
                $img = $big_ph.find('img'),
                openCreate = function(e) {
                    e.preventDefault();
                    elemsh($box_first, false);
                    elemsh($box_second, true);
                },
                look = function(e) {
                    e.preventDefault();
                    elemsh($box_first, true);
                    elemsh($box_second, false);
                },
                submitCreate = function() {
                    //检测相册名称
                    if (!checkItem(getObjByName('album_name:parent', $disArts_box), tips.notEmptyOfAlbumName)) return false;
                    //加载缓冲icon                      
                    webUtil.loading(true, 1);
                },
                edit = function(e) {
                    e.preventDefault();
                    var 
                        $setdetails = $(this).closest('.set-details'),
                        //原相册id
                        oldid = $setdetails.find('input.aid').val(),
                        //原相册名称
                        oldname = $setdetails.find('a.aname').text(),
                        //原相册描述
                        olddesc = $setdetails.find('p.adesc').text();

                    olddesc = olddesc.replace('描述:', '');
                    //显示原相册名称
                    $editObj.find('input.aname').val(oldname);
                    //显示原相册名称
                    $editObj.find('input.aid').val(oldid);
                    //显示原相册描述
                    $editObj.find('textarea.adesc').val(olddesc);
                    //展开弹窗
                    webUtil.dialogCenter($editObj, 1);
                    //绑定拖动事件
                    webUtil.dialogMove($editObj, $editTitleLine);
                },
                closeEdit = function(e) {
                    e.preventDefault();
                    webUtil.undialog($editObj);
                },
                submitEdit = function() {
                    var 
                        $newname_input = $editObj.find('input.aname'),
                        //显示原相册名称
                        newname = $newname_input.val(),
                        //显示原相册名称
                        newid = $editObj.find('input.aid').val(),
                        //显示原相册描述
                        newdesc = $editObj.find('textarea.adesc').val(),
                        $setdetails = $disArts_box.find('.set-details'),
                        theid,thename,thedesc;

                    //检测相册名称
                    if (!checkItem($newname_input, tips.notEmptyOfAlbumName)) return;
                    
                    for(var i=0, len=$setdetails.length; i<len; i++) {
                        var $setdetails_i = $setdetails.eq(i);
                        //当前循环相册id
                        theid = $setdetails_i.find('input.aid').val();
                        //当前循环相册名称
                        thename = $setdetails_i.find('a.aname').text();
                        //当前循环相册描述
                        thedesc = $setdetails_i.find('p.adesc').text();
                        //判断相册名是否存在
                        if (!checkItem(newname===thename&&newid!==theid, tips.existedOfAlbumName, $newname_input)) return;
                        if (newid === theid) {
                            newdesc = newdesc === '' ? '暂无' : newdesc;
                            newdesc = '描述:' + newdesc;
                            //判断相册信息是否改变
                            if (!checkItem(thename===newname&&thedesc===newdesc, tips.notChangeOfInformation, $newname_input)) return;
                        }
                    }

                    var 
                        successFn = function(m) {
                            if (m.status === 1) {
                                for(var i=0, len=$setdetails.length; i<len; i++) {
                                    var $setdetails_i = $setdetails.eq(i);
                                    //当前循环相册id
                                    theid = $setdetails_i.find('input.aid').val();
                                    //找到指定相册，并更新其名称和描述
                                    if (newid === theid) {
                                        //更新名称
                                        $setdetails_i.find('a.aname').text(newname);
                                        //更新描述
                                        $setdetails_i.find('p.adesc').text(newdesc);
                                        break;
                                    }
                                }
                            }
                            webUtil.boxalert({
                                msg: m.msg,
                                callback: function() {
                                    webUtil.undialog($editObj);
                                }
                            });
                        },
                        data = {
                            albumName : loginer,
                            newid : newid,
                            newname : newname,
                            newdesc : newdesc
                        },
                        ajaxObj = {
                            url: 'album.php',
                            data: data,
                            success: successFn
                        };

                    webUtil.ajaxRequest(ajaxObj);
                },
                deletation = function(e) {
                    e.preventDefault();
                    var 
                        $this = $(this),
                        $albumInner = $('.albumInner'),
                        $calbum = $albumInner.find('.calbum'),
                        aid = $this.prevAll('input.aid').val();

                    Boxy.confirm({content:'此操作将删除此相册及其内的所有相片，确定删除?', buttonText:['确定','取消']}, function() {
                        var successFn = function(m) {
                            if (m.status === 1) {
                                var $lis = $calbum.find('li');
                                if ($lis.length === 1) {
                                    $calbum.empty().html('<h2>我的相册</h2><p class="noneAlbum">你当前没有相册，马上<a class="createNew" href="#">创建新相册</a>?</p>');
                                } else {
                                    $this.closest('li').remove();
                                }
                            } else {
                                webUtil.boxalert({msg: m.msg});
                            }
                        },
                        ajaxObj = {
                            url: 'rplarticle.php',
                            data: {'del_albumid': aid},
                            success: successFn
                        };

                        webUtil.ajaxRequest(ajaxObj);
                    }, {title:'删除提示'});
                },
                openUpload = function() {
                    //显示弹窗
                    webUtil.dialogCenter($uploadBlock, 1);
                    //绑定拖动事件
                    webUtil.dialogMove($uploadBlock, $uploadBlock.find('h2'));
                },
                closeUpload = function(e) {
                    e.preventDefault();
                    webUtil.undialog($uploadBlock);
                },
                addPhoto = function() {
                    photoIndex++;
                    var 
                        photo = 'photo_'+photoIndex,
                        newphoto = ['<li>',
                                        '<span>',
                                            '<input type="file" name="'+photo+'" />',
                                            '<input type="hidden" name="MAX_FILE_SIZE" value="2048000" />',
                                        '</span>',
                                        '<span>',
                                            '<a href="#" class="delphoto">删除</a>',
                                        '</span>',
                                    '</li>'].join('');

                    $uploadBlock.find('.uploadList ul').append(newphoto);
                    return false;
                },
                deleteItem = function(e) {
                    e.preventDefault();
                    $(this).closest('li').remove();
                },
                submitUploadBefore = function() {
                    webUtil.loading(true, 1001);
                },
                openSomePhoto = function(e) {
                    e.preventDefault();
                    if (e.target.nodeName.toLowerCase() !== 'img') return;
                    
                    var 
                        $this = $(this),
                        src = $this.attr('origin_path'),
                        title = $this.attr('title'),
                        alt = $this.attr('alt');
                        
                    //加载缓冲icon
                    webUtil.loading(true, 1);
                    //原图请求
                    webUtil.geomerScale($img[0], src, function(pos) {
                        //去除window窗口滚动条
                        $('body, html').css('overflow','hidden');
                        //加载相片
                        $img.attr({title: title, alt: alt});
                        //移除加载icon
                        webUtil.unloading();
                        //显示弹窗
                        webUtil.dialogCenter($big_ph, 1, pos);
                        //绑定拖动事件
                        webUtil.dialogMove($big_ph, $img);
                    });
                },
                closePhoto = function() {
                    webUtil.undialog($big_ph);
                    //显示window窗口滚动条
                    $('body, html').css('overflow','auto');
                },
                photoResize = function() {
                    if($big_ph.is(':hidden')) return;
                    var 
                        distance = 40,
                        minSize = 200,
                        win_height = $win.height() - distance,
                        win_width = $win.width() - distance,
                        height = $img.attr('org_h'),
                        width = $img.attr('org_w'),
                        percent = 1;

                    //计算压缩比例
                    if (width > win_width && height < win_height) {
                        percent = win_width/width;
                    }
                    else if (width > win_width && height > win_height) {
                        percent = Math.min((win_height/height),(win_width/width));
                    }
                    else if (width < win_width && height > win_height) {
                        percent = win_height/height;
                    }
                    //检测边界值
                    if (height*percent > minSize || width*percent > minSize) {
                        //重置图片大小
                        var newWidth = width*percent, newHeight = height*percent, pos = {};
                        //窗口宽度不能小于400
                        if (win_width >= distance*10) {
                            //如果最后图片高度小于200，则图片不做压缩
                            if (newHeight < minSize && width > win_width) {
                                newWidth = width;
                                newHeight = height;
                                pos = {left: 0};
                            }
                            //如果最后图片宽度小于200，则图片不做压缩
                            if (newWidth < minSize && height > win_height) {
                                newWidth = width;
                                newHeight = height;
                                pos = {top: 0};
                            }
                        }
                        //赋值宽高
                        $img.attr('width', newWidth);
                        $img.attr('height', newHeight);
                        //重新定位容器位置
                        webUtil.dialogCenter($big_ph, 1, pos);
                    }
                },
                managePhoto = function(e) {
                    e.preventDefault();
                    var 
                        $this = $(this),
                        $checking = $disArts_box.find('.checking'),
                        thisact = $this.text();

                    //显示管理选择，添加checkbox
                    if (thisact === '管理') {
                        elemsh([$manage, $checking], true);
                        $this.text('退出管理');
                    }
                    //隐藏管理
                    if (thisact === '退出管理') {
                        elemsh([$manage, $checking], false);
                        $this.text('管理');
                    }
                },
                selectAll = function() {
                    if ($(this).is(':checked')) {
                        for(var i=0; i<$checkboxs.length; i++) {
                            $checkboxs[i].checked = 'checked';
                        }
                    } else {
                        $checkboxs.removeAttr('checked');
                    }
                },
                deletePhoto = function(e) {
                    e.preventDefault();
                    //如果没选中项
                    if (!checkItem(!$checkboxs.is(':checked'), tips.selectedOfDeletePhoto)) return;
                    //如果有选中
                    var 
                        //已选中照片
                        $checkeds = $album_photosUl.find('input[type=checkbox]:checked'),
                        names = [],
                        aid = $manage.find('input.aid').val();

                    for(var i=0, len=$checkeds.length; i<len; i++) {
                        //保存要删除的照片名称
                        names[i] = $checkeds.eq(i).val();
                    }

                    var 
                        successFn = function(m) {
                            if (m.status === 1) {
                                location.href = location.href;
                            } else {
                                webUtil.boxalert({msg: m.msg});
                            }
                        },
                        data = {
                            del_albumId : aid,
                            photo_names : names.toString(),
                            albumName : loginer
                        },
                        ajaxObj = {
                            url: 'album.php',
                            data: data,
                            success: successFn
                        };

                    Boxy.confirm({content:'确定要删除照片：'+names+'？', buttonText:['确定','取消']}, function() {
                        webUtil.ajaxRequest(ajaxObj);
                    }, {title:'提示'});
                },
                setCover = function(e) {
                    e.preventDefault();
                    var 
                        //已选中照片
                        $checkeds = $album_photosUl.find('input[type=checkbox]:checked'),
                        aid = $manage.find('input.aid').val();

                    //相册封面选择
                    if (!checkItem($checkeds.length===0, tips.selectedOfAlbumCover)) return;
                    if (!checkItem($checkeds.length>1, tips.selectedOfMoreAsCover)) return;
                    
                    var 
                        successFn = function(m) {
                            webUtil.boxalert({msg: m.msg});
                        },
                        data = {
                            set_albumId : aid,
                            albumName : loginer,
                            frontCover : $checkeds.val()
                        },
                        ajaxObj = {
                            url: 'album.php',
                            data: data,
                            success: successFn
                        };

                    webUtil.ajaxRequest(ajaxObj);
                },
                movePhoto = function() {
                    var 
                        $this = $(this),
                        //选择相册id
                        theValue = $this.val(),
                        //当前相册id
                        this_album_id = $this.prevAll('input.this_album_id').val(),
                        //已选中照片
                        $checkeds = $album_photosUl.find('input[type=checkbox]:checked'),
                        names = [];

                    if (theValue === '--选择相册--') {
                        return;
                    }
                    //选择要移动的相片
                    if (!checkItem($checkeds.length===0, tips.selectedOfMovePhoto)) return;
                    //保存要删除的照片名称
                    for(var i=0, len=$checkeds.length; i<len; i++) {
                        names[i] = $checkeds.eq(i).val();
                    }

                    var 
                        successFn = function(m) {
                            if (m.status === 1) {
                                location.href = location.href;
                            } else {
                                webUtil.boxalert({msg: m.msg});
                            }
                        },
                        data = {
                            fromAlbumId : this_album_id,
                            toAlbumId : theValue,
                            albumName : loginer,
                            //ajax不能传递数组，把数组拼接为字符串
                            photos : names.toString()
                        },
                        ajaxObj = {
                            url: 'album.php',
                            data: data,
                            success: successFn
                        };

                    Boxy.confirm({content:'确定要移动照片：'+names+'？', buttonText:['确定','取消']}, function() {
                        webUtil.ajaxRequest(ajaxObj);
                    }, {title:'提示'});
                },
                commitBefore = function() {
                    //判断是否登录
                    if (!checkItem(!loginer, tips.needLoginedOfOprate)) return false;
                    //检测评论内容
                    if (!checkItem($(this).prevAll('textarea.textarea_type'), tips.notEmptyOfPostContent)) return false;
                    //加载缓冲icon
                    webUtil.loading(true, 1);
                },
                dragSpin = function() {
                    //鼠标点击事件
                    this.on('mousedown.dragSpin', function(e) {
                        e.preventDefault();
                        //获取当前鼠标位置
                        var downX = e.clientX, downY = e.clientY, startX = spin3dX, startY = spin3dY;
                        //鼠标移动事件
                        $doc.on('mousemove.dragSpin', function(e) {
                            e.preventDefault();
                            spin3dY = startY + (e.clientX - downX) / 10;
                            spin3dX = startX - (e.clientY - downY) / 10;
                            //设置水平、垂直上的偏移效果
                            $album_photosUl.css({
                                '-webkit-transform': 'perspective(3000px) rotateX('+spin3dX+'deg) rotateY('+spin3dY+'deg)',
                                '-moz-transform': 'perspective(3000px) rotateX('+spin3dX+'deg) rotateY('+spin3dY+'deg)',
                                '-o-transform': 'perspective(3000px) rotateX('+spin3dX+'deg) rotateY('+spin3dY+'deg)',
                                '-ms-transform': 'perspective(3000px) rotateX('+spin3dX+'deg) rotateY('+spin3dY+'deg)',
                                'transform': 'perspective(3000px) rotateX('+spin3dX+'deg) rotateY('+spin3dY+'deg)'
                            });
                        });
                        //鼠标抬起事件
                        $doc.on('mouseup.dragSpin', function(e) {
                            e.preventDefault();
                            //解除doc上的绑定
                            $doc.off('mousemove.dragSpin').off('mouseup.dragSpin');
                            //展开单个相片
                            if (e.clientX-downX === 0 && e.clientY-downY === 0 && e.which === 1) {
                                openSomePhoto.call(e.target, e);
                            }
                        });
                    });
                },
                spin3d = function() {
                    var $lis = $album_photosUl.find('li');
                    //打开3d预览
                    if ($(this).is(':checked')) {
                        //缓存的3d旋转位置重置
                        spin3dX = -10;
                        spin3dY = 0;
                        //解除图片点击事件
                        $album_photosUl.off('click', 'img', openSomePhoto);

                        //浏览器是否支持css3 3d效果
                        var divstyle = doc.createElement('div').style;
                        if (!checkItem(!('-webkit-transform-style' in divstyle || '-moz-transform-style' in divstyle || 
                            '-o-transform-style' in divstyle || '-ms-transform-style' in divstyle || 
                            'transform-style' in divstyle), tips.notSupport3d)) return;

                        //添加3d效果
                        var 
                            liLen = $lis.length,
                            degStep = 360/liLen,
                            //25是为了拉开元素间的距离，为了美观
                            translateZ = 50/Math.tan(degStep/2/180*Math.PI)+25;

                        //打开3d旋转
                        $album_photosUl.addClass('animation');
                        //给所需元素添加效果
                        $.each($lis, function(i, e) {
                            (function(i, e) {
                                setTimeout(function() {
                                    $(e).css({
                                        '-webkit-transform': 'rotateY('+degStep*i+'deg) translateZ('+translateZ+'px)',
                                        '-moz-transform': 'rotateY('+degStep*i+'deg) translateZ('+translateZ+'px)',
                                        '-o-transform': 'rotateY('+degStep*i+'deg) translateZ('+translateZ+'px)',
                                        '-ms-transform': 'rotateY('+degStep*i+'deg) translateZ('+translateZ+'px)',
                                        'transform': 'rotateY('+degStep*i+'deg) translateZ('+translateZ+'px)'
                                    });
                                }, (liLen+3-i)*200);
                            })(i, e);
                        });
                        //添加命名空间
                        $album_photos.addClass('spin3d');
                        //拖拽旋转
                        dragSpin.call($lis);
                    }
                    // 关闭3d预览
                    else {
                        //绑定图片点击事件
                        $album_photosUl.on('click', 'img', openSomePhoto);
                        //解绑图片上的mousedown事件
                        $lis.off('mousedown.dragSpin');
                        //去除容器上的效果残留
                        $album_photos.removeClass('spin3d');
                        $album_photosUl.removeClass('animation').removeAttr('style');
                        //去除元素的3d效果
                        $.each($lis, function(i, e) {
                            $(e).removeAttr('style');
                        });
                    }
                };
                

            //新建相册
            $disArts_box.on('click','.createNew', openCreate)
            //查看相册
            .on('click', '.lookAlbums', look)
            //提交创建
            .on('click', '.creatAlbum', submitCreate)
            //编辑相册
            .on('click', '.toEditAlbum', edit)
            //删除相册
            .on('click', '.delAlbum', deletation)
            //上传弹窗
            .on('click', '.upload', openUpload)
            //管理照片
            .on('click', '.managPhotos', managePhoto)
            //全选
            .on('click', '.totalPhotos', selectAll)
            //删除
            .on('click', '.delPhoto', deletePhoto)
            //设封面
            .on('click', '.setPhoto', setCover)
            //移动
            .on('change', '.movePhoto', movePhoto)
            //相册评论前判断
            .on('click', '.photo_comment .persubmit_btn', commitBefore)
            //图片3d预览
            .on('click', '.is3d [type=checkbox]', spin3d);
            //关闭编辑弹窗
            $editTitleLine.on('click', '.edit_close', closeEdit);
            //相册编辑数据提交
            $editObj.on('click', '.persubmit_btn', submitEdit);
            //关闭弹窗
            $uploadBlock.on('click', '.W_close_color', closeUpload)
            //添加上传图片
            .on('click', '.addphoto', addPhoto)
            //提交上传
            .on('click', '.submit_photos', submitUploadBefore)
            //单张图片删除操作
            .on('click', '.delphoto', deleteItem);
            //展开单个相片
            $album_photosUl.on('click', 'img', openSomePhoto);
            //window窗口大小改变时
            $win.on('resize.photo', photoResize);
            //关闭弹窗
            $opt_img.on('click', '.close', closePhoto);
        },

        //我的留言页面功能
        spaceBook: function() {
            var 
                $guestbookInner = $('.guestbookInner'),
                sbumitBefore = function() {
                    var $commentobj = $guestbookInner.find('.gbcomment_text');
                    //判断是否登录
                    if (!checkItem(!loginer, tips.needLoginedOfOprate)) return false;
                    //判断内容是否空
                    if (!checkItem($commentobj, tips.notEmptyOfPostContent)) return false;
                    //加载缓冲icon
                    webUtil.loading(true, 1);
                };
            //提交前验证
            $guestbookInner.on('click', '.submit_msg', sbumitBefore);
        },

        //我的粉丝页面功能
        spaceFans: function() {
            var 
                $fansbody = $('.fansbody'),
                $sendsms = $('#sendsms'),
                hoverFirst = function() {
                    elemsh($(this).find('.fans_ico'), true);
                },
                hoverSecond = function() {
                    elemsh($(this).find('.fans_ico'), false);
                },
                fanMove = function(e) {
                    e.preventDefault();
                    var 
                        $this = $(this),
                        $li = $this.closest('li'),
                        act = $('input.remove_act').val(),
                        tip = $this.text(),
                        //粉丝/关注名字
                        fanAttener = $li.find('.fans_info h4').text();

                    Boxy.confirm({content:'确定'+tip+'吗?', buttonText:['确定','取消']}, function() {
                        var 
                            successFn = function(m) {
                                if (m.status === 1) {
                                    location.href = location.href;
                                } else {
                                    webUtil.boxalert({msg: m.msg});
                                }
                            },
                            data = {
                                act : act,
                                fanAttener : fanAttener,
                                loginer : loginer
                            },
                            ajaxObj = {
                                url: 'rplarticle.php',
                                data: data,
                                success: successFn,
                                isloading: false
                            };

                        webUtil.ajaxRequest(ajaxObj);
                    }, {title:'删除提示'});
                },
                //我的空间粉丝搜索功能
                getFans = function(arg) {
                    if (!arg[0]) return;
                    var 
                        $conditionInput = arg[0],
                        successFn = function(m) {
                            if (m.status === 1) {
                                //显示新结果前移除旧结果
                                $bdy.find('.sear_fans_list').remove();
                                //显示结果
                                $bdy.append(m.msg);
                                //弹窗内容
                                var $sear_fans_list = $bdy.find('.sear_fans_list');
                                //判断弹窗出现的位置
                                if (parseInt($sear_fans_list.height(),10) >= parseInt($win.height(),10)) {
                                    webUtil.dialogCenter($sear_fans_list, 1, {top:0});
                                } else {
                                    webUtil.dialogCenter($sear_fans_list, 2);
                                }
                                //使弹窗可移动
                                webUtil.dialogMove($sear_fans_list, $sear_fans_list.find('h3'));
                            } else {
                                webUtil.boxalert({
                                    msg: m.msg,
                                    elem: $conditionInput
                                });
                            }
                        },
                        ajaxObj = {
                            url: 'rplarticle.php',
                            data: arg[1],
                            success: successFn,
                            isloading: false,
                            unloading: false
                        };

                    //检测昵称
                    if (!checkItem($conditionInput, tips.notEmptyOfSearchKey)) return;
                    //发起请求
                    webUtil.ajaxRequest(ajaxObj);
                },
                fanSearch = function(e) {
                    e.preventDefault();
                    var 
                        $fansNameInput = $(this).prevAll('input.sminput_type'),
                        fansName = $fansNameInput.val(),
                        find_act = $('input.find_act').val();

                    getFans([$fansNameInput, {
                        user: loginer,
                        act: find_act,
                        key: fansName
                    }]);
                },
                fanFind = function(e) {
                    e.preventDefault();
                    var 
                        $fdconditionInput = $(this).prevAll('input.sminput_type'),
                        fdcondition = $fdconditionInput.val();

                    getFans([$fdconditionInput, {
                        act: 'find',
                        fdcondition: fdcondition
                    }]);
                },
                closeSearch = function(e) {
                    e.preventDefault();
                    var $fanlist = $(this).closest('.sear_fans_list');
                    //关闭弹窗，移除搜索列表
                    webUtil.undialog($fanlist);
                    $fanlist.remove();
                };

            //操作粉丝/关注
            $fansbody.find('.fans_list li').hover(hoverFirst, hoverSecond)
            //移除粉丝/取消关注
            .end().on('click', '.remove_fans', fanMove)
            //搜索粉丝
            .on('click', '.search_fans', fanSearch)
            //找人/邀请注册
            .on('click', '.findperson .search_btn', fanFind)
            //关闭搜索结果
            .on('click', '.sear_fans_list .close', closeSearch)
            //打开发私信弹窗
            .on('click', '.sendmessage', {elem: $sendsms}, business.openMessageBox);
        }
    }).init();
    
    //区块init方法
    function initialize(obj) {
        for (var i in obj) {
            if (typeof obj[i] === 'function' && i !== 'init') {
                if (!isDebug) {
                    try {
                        obj[i].call(obj);
                    } catch(e) {}
                }
                //调试模式
                else {
                    obj[i].call(obj);
                }
            }
        }
    }

    //元素显示/隐藏
    function elemsh(els, type) {
        $.each(els, function() {
            var el = $(this);
            if (type) {
                el.removeClass('wb-hide');
            } else {
                el.addClass('wb-hide');
            }
        });
    }

    //根据name查找元素
    function getObjByName(text, obj, isval) {
        var 
            match = text.split(':'),
            elem = null, name = match[0], type = match[1], flag = match[2],
            selector = 'input[name='+name+']';

        //如果有标志位，根据标志位查找
        if (flag) {
            selector = selector + ':' + flag;
        }

        //根据类型匹配返回值
        switch(type) {
            //直接根据name查找
            case 'self': {
                elem = $(selector);
            }
            break;
            //从祖辈元素查找
            case 'parent': {
                elem = obj.find(selector);
            }
            break;
            //从下个节点查找
            case 'next': {
                elem = obj.nextAll(selector);
            }
            break;
            //从上个节点查找
            case 'prev': {
                elem = obj.prevAll(selector);
            }
            break;
            default: 
            break;
        }
        //返回查找结果
        return isval ? elem.val() : elem;
    }

    //检测条件是否符合
    function checkItem(elem, text, obj) {
        //参数对象
        var param = {msg: text};
        //如果elem是对象
        if ($.type(elem) === "object") {
            $.extend(param, {elem: elem});
            //如果对象的值为空，返回false
            if (elem.val() === '') {
                webUtil.boxalert(param);
                return false;
            }
        }
        //如果elem是布尔值
        else if ($.type(elem) === 'boolean'){
            if ($.type(obj) === 'object') {
                $.extend(param, {elem: obj});
            }
            //如果布尔值为真，返回false
            if (elem) {
                webUtil.boxalert(param);
                return false;
            }
        }
        return true;
    }

    //用户输入文本字数提示
    function numberTip(obj, totalNum, tipId) {
        var tipObj = doc.getElementById(tipId);
        tipObj.style.display = 'block';

        setInterval(function() {
            var string = obj.value;

            if (string.length <= 0) {
                tipObj.innerHTML = '签名请保证100字以内，还可以输入<em>100</em>字';
            }
            //把单个中文处理成两个字符(因为这里是把每个汉字看成一个字符的)并求出字数
            else {
                var 
                    charNum = string.replace(/[^\x00-\xff]/g, "**").length,
                    difNum = totalNum - charNum;

                tipObj.innerHTML = '签名请保证100字以内，还可以输入<em>' + difNum + '</em>字';

                if (charNum <= totalNum) {
                    tipObj.getElementsByTagName('em')[0].innerHTML = difNum;
                    doc.getElementById('save_modify').removeAttribute('disabled');
                } else {
                    tipObj.innerHTML = '签名请保证100字以内，已经超出<em style="color:red;">' + (-difNum) + '</em>字';
                    doc.getElementById('save_modify').setAttribute('disabled','disabled');
                }
            }
        },100);
    }
})(jQuery, window, document);