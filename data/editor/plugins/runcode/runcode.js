/*******************************************************************************
* KindEditor - WYSIWYG HTML Editor for Internet
* Copyright (C) 2006-2011 kindsoft.net
*
* @author Roddy <luolonghao@gmail.com>
* @site http://www.kindsoft.net/
* @licence http://www.kindsoft.net/license.php
*******************************************************************************/

// google code prettify: http://google-code-prettify.googlecode.com/
// http://google-code-prettify.googlecode.com/

KindEditor.plugin('runcode', function(K) {
	var self = this, name = 'runcode';
	self.clickToolbar(name, function() {
		var lang = self.lang(name + '.'),
			html = ['<div style="padding:20px;">',
			'<textarea class="ke-textarea" style="width:408px;height:260px;"></textarea>',
			'</div>'].join(''),
			dialog = self.createDialog({
				name : name,
				width : 450,
				title : self.lang(name),
				body : html,
				yesBtn : {
					name : self.lang('yes'),
					click : function(e) {
							runcode = textarea.val(),
							html = '<div><textarea cols="50" rows="10" class="runtextarea" style="height:221px;overflow-y:auto;word-wrap:break-word;margin:1px;padding:3px;">' + K.escape(runcode) + '</textarea><div class="runbtn" style="margin-top:5px;"><button class="pn runing">运行代码</button><button class="pn copying">复制代码</button> <span>提示：您可以先修改部分代码再运行</span></div></div>';
						self.insertHtml(html).hideDialog().focus();
					}
				}
			}),
			textarea = K('textarea', dialog.div);
		textarea[0].focus();
	});
});
