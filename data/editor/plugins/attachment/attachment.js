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

KindEditor.plugin('attachment', function(K) {
	var self = this, name = 'attachment';
	self.clickToolbar(name, function() {
		var $this = $("[data-name="+name+"]"),
			$dialog = $(".post_upload_file"),
			$station = $(".station"),
			dialog_h = $dialog.height()+10,
			isopen = $this.data("open");

		if (!$station.height() || isopen == "off") {
			$station.animate({height:dialog_h+'px'},1000,function(){
				$station.css("height","auto");
			});
			$dialog.slideDown(1000);
			$this.data("open", "on");
		}
		else if(!isopen || isopen == 'on'){
			$dialog.slideUp(1000);
			$station.animate({height:0},1000);
			$this.data("open", "off");
		}
	});
});
