<?php
class Common {
	//判断是否属手机
	public function is_mobile() {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
		$is_mobile = false;
		foreach ($mobile_agents as $device) {
			if (stristr($user_agent, $device)) {
				$is_mobile = true;
				break;
			}
		}
		return $is_mobile;
	}

	//读取数据
	public function selectSql($select) {
		if (!$result = mysql_query($select)) {
			die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
		}
		return $result;
	}

	//判断用户名是否存在
	public function check_user($user){
		$select = "SELECT user_name FROM users";
		$result = mysql_query($select);
		while($row = mysql_fetch_array($result)){
			if($user == $row['user_name']){
				return false;
			}
		}
		return true;
	}

	//根据用户名查找用户id
	public function get_user_id($user){
		$select = "SELECT user_id FROM users WHERE user_name='$user'LIMIT 1";
		$result = $this->selectSql($select);
		$row = mysql_fetch_assoc($result);
		return $row['user_id'];
	}

	//获取当前完整url
	public function getPageUrl(){
	    $pageURL = 'http';
	    if ($_SERVER["HTTPS"] == "on"){
	        $pageURL .= "s";
	    }
	    $pageURL .= "://";
	    if ($_SERVER["SERVER_PORT"] != "80"){
	        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	    }
	    else{
	        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	    }
	    return $pageURL;
	}

	//根据访问数排序文章（热门文章）
	public function readHotArts(){
		$select = "SELECT topics_id,article_title FROM articles ORDER BY visit_num DESC LIMIT 57";
		$result = $this->selectSql($select);
		$hotArts = array();
		while ($row = mysql_fetch_assoc($result)) {
			$hotArts[] = $row;
		}
		return $hotArts;
	}

	//随机文章20
	public function readRandArts(){
		$select = "SELECT topics_id,article_title FROM articles ORDER BY RAND() LIMIT 45";
		$result = $this->selectSql($select);
		$randomArts = array();
		while ($row = mysql_fetch_assoc($result)) {
			$randomArts[] = $row;
		}
		return $randomArts;
	}

	//文章未读评论提示
	public function commentMsg($now_user){
		if (!empty($now_user)) {
			$select = "SELECT topics_id,article_title,newartcomment FROM articles WHERE user_name='$now_user' AND newartcomment > 0";
			$result = mysql_query($select);
			$i = 0;
			$newartcomment = array();
			while ($row = mysql_fetch_assoc($result)) {
				$newartcomment[$i] = $row;
				$i++;
			}
			return $newartcomment;
		}
	}

	//未读回复
	public function replyMsg($now_user){
		$select = "SELECT comment_id,reply_id FROM comments WHERE user_name='$now_user'";
		$result = $this->selectSql($select);
		$i = 0;
		$newreply = array();
		while ($row = mysql_fetch_assoc($result)) {
			$tmpid = $row['comment_id'];
			$tmpartid = $row['reply_id'];
			$select = "SELECT replyid FROM replys WHERE associd='$tmpid' AND is_newreply=1";
			$subresult = $this->selectSql($select);
			if (mysql_num_rows($subresult) > 0) {
				$newreply[$i]['number'] = mysql_num_rows($subresult); //本文章未读回复数
				$select = "SELECT article_title FROM articles WHERE topics_id='$tmpartid'";
				$subresult = $this->selectSql($select);
				$subrow = mysql_fetch_assoc($subresult);
				$newreply[$i]['artid'] = $tmpartid; //文章id
				$newreply[$i]['arttitle'] = $subrow['article_title']; //文章名
				$i++;
			}
		}
		return $newreply;
	}

	/*
	 * 将一个字串中含有全角的数字字符、字母字符转换为相应半角字符
	 * @access public
	 * @param string $str 待转换字串
	 * @return string $str 处理后字串
	*/
	public function make_semiangle($str){
		$arr = array(
			'０' => '0', 
			'１' => '1', 
			'２' => '2', 
			'３' => '3', 
			'４' => '4',
			'５' => '5', 
			'６' => '6', 
			'７' => '7', 
			'８' => '8', 
			'９' => '9', 
			'Ａ' => 'A', 
			'Ｂ' => 'B', 
			'Ｃ' => 'C', 
			'Ｄ' => 'D', 
			'Ｅ' => 'E',
			'Ｆ' => 'F', 
			'Ｇ' => 'G', 
			'Ｈ' => 'H', 
			'Ｉ' => 'I', 
			'Ｊ' => 'J', 
			'Ｋ' => 'K', 
			'Ｌ' => 'L', 
			'Ｍ' => 'M', 
			'Ｎ' => 'N', 
			'Ｏ' => 'O',
			'Ｐ' => 'P', 
			'Ｑ' => 'Q', 
			'Ｒ' => 'R', 
			'Ｓ' => 'S', 
			'Ｔ' => 'T',
			'Ｕ' => 'U', 
			'Ｖ' => 'V', 
			'Ｗ' => 'W', 
			'Ｘ' => 'X', 
			'Ｙ' => 'Y',
			'Ｚ' => 'Z', 
			'ａ' => 'a', 
			'ｂ' => 'b', 
			'ｃ' => 'c', 
			'ｄ' => 'd',
			'ｅ' => 'e', 
			'ｆ' => 'f', 
			'ｇ' => 'g', 
			'ｈ' => 'h', 
			'ｉ' => 'i',
			'ｊ' => 'j', 
			'ｋ' => 'k', 
			'ｌ' => 'l', 
			'ｍ' => 'm', 
			'ｎ' => 'n',
			'ｏ' => 'o', 
			'ｐ' => 'p', 
			'ｑ' => 'q', 
			'ｒ' => 'r', 
			'ｓ' => 's', 
			'ｔ' => 't', 
			'ｕ' => 'u', 
			'ｖ' => 'v', 
			'ｗ' => 'w', 
			'ｘ' => 'x', 
			'ｙ' => 'y', 
			'ｚ' => 'z'
		);
		return strtr($str, $arr);
	}

	/*
	 * 获得浏览器名称和版本
	 * @access public
	 * @return string
	*/
	public function getbrowser() {
		global $_SERVER;
	    $agent         = $_SERVER['HTTP_USER_AGENT'];
	    $browser       = '';
	    $browser_ver   = '';
	    if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
	      $browser       = 'OmniWeb';
	      $browser_ver   = $regs[2];
	    }
	    if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) {
	      $browser       = 'Netscape';
	      $browser_ver   = $regs[2];
	    }
	    if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
	      $browser       = 'Safari';
	      $browser_ver   = $regs[1];
	    }
	    if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
	      $browser       = 'Internet Explorer';
	      $browser_ver   = $regs[1];
	    }
	    if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
	      $browser       = 'Opera';
	      $browser_ver   = $regs[1];
	    }
	    if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
	      $browser       = '(Internet Explorer ' .$browser_ver. ') NetCaptor';
	      $browser_ver   = $regs[1];
	    }
	    if (preg_match('/Maxthon/i', $agent, $regs)) {
	      $browser       = '(Internet Explorer ' .$browser_ver. ') Maxthon';
	      $browser_ver   = '';
	    }
	    if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
	      $browser       = 'FireFox';
	      $browser_ver   = $regs[1];
	    }
	    if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) {
	      $browser       = 'Lynx';
	      $browser_ver   = $regs[1];
	    }
	    if ($browser != '') {
	       return $browser.' '.$browser_ver;
	    }
	    else {
	      return 'Unknow browser';
	    }
	}

	/*
	 * 获得客户端的操作系统
	 * @access private
	 * @return void
	*/
	public function getos() {
	    $agent = $_SERVER['HTTP_USER_AGENT'];
	    $os = false;
	    if (eregi('win', $agent) && strpos($agent, '95')) {
	      $os = 'Windows 95';
	    }
	    else if (eregi('win 9x', $agent) && strpos($agent, '4.90')) {
	      $os = 'Windows ME';
	    }
	    else if (eregi('win', $agent) && ereg('98', $agent)) {
	      $os = 'Windows 98';
	    }
	    else if (eregi('win', $agent) && eregi('nt 5.1', $agent)) {
	      $os = 'Windows XP';
	    }
	    else if (eregi('win', $agent) && eregi('nt 5', $agent)) {
	      $os = 'Windows 2000';
	    }
	    else if (eregi('win', $agent) && eregi('nt', $agent)) {
	      $os = 'Windows NT';
	    }
	    else if (eregi('win', $agent) && ereg('32', $agent)) {
	      $os = 'Windows 32';
	    }
	    else if (eregi('linux', $agent)) {
	      $os = 'Linux';
	    }
	    else if (eregi('unix', $agent)) {
	      $os = 'Unix';
	    }
	    else if (eregi('sun', $agent) && eregi('os', $agent)) {
	      $os = 'SunOS';
	    }
	    else if (eregi('ibm', $agent) && eregi('os', $agent)) {
	      $os = 'IBM OS/2';
	    }
	    else if (eregi('Mac', $agent) && eregi('PC', $agent)) {
	      $os = 'Macintosh';
	    }
	    else if (eregi('PowerPC', $agent)) {
	      $os = 'PowerPC';
	    }
	    else if (eregi('AIX', $agent)) {
	      $os = 'AIX';
	    }
	    else if (eregi('HPUX', $agent)) {
	      $os = 'HPUX';
	    }
	    else if (eregi('NetBSD', $agent)) {
	      $os = 'NetBSD';
	    }
	    else if (eregi('BSD', $agent)) {
	      $os = 'BSD';
	    }
	    else if (ereg('OSF1', $agent)) {
	      $os = 'OSF1';
	    }
	    else if (ereg('IRIX', $agent)) {
	      $os = 'IRIX';
	    }
	    else if (eregi('FreeBSD', $agent)) {
	      $os = 'FreeBSD';
	    }
	    else if (eregi('teleport', $agent)) {
	      $os = 'teleport';
	    }
	    else if (eregi('flashget', $agent)) {
	      $os = 'flashget';
	    }
	    else if (eregi('webzip', $agent)) {
	      $os = 'webzip';
	    }
	    else if (eregi('offline', $agent)) {
	      $os = 'offline';
	    }
	    else{
	      $os = 'Unknown';
	    }
	    return $os;
	}

	//未读私信
	public function newsms($user){
		$select = "SELECT * FROM user_sms WHERE geter='$user' AND unread=1";
		$result = $this->selectSql($select);
		$i = 0;
		$newsms = array();
		while ($row = mysql_fetch_assoc($result)) {
			$newsms[$i] = $row;
			$i++;
		}
		return $newsms;
	}

	//读取新空间留言数
	public function msgnum($user_id){
		$select ="SELECT msgnum FROM users WHERE user_id='$user_id' LIMIT 1";
		$result = $this->selectSql($select);
		$row = mysql_fetch_assoc($result);
		return $row['msgnum'];
	}

	//读取用户相册评论数
	public function album_comments_num($user_id){
		$select = "SELECT album_id,album_name,newalcomment FROM user_album WHERE assoc_userid='$user_id' AND newalcomment>0";
		$result = $this->selectSql($select);
		$i = 0;
		$newalcomment = array();
		while ($row = mysql_fetch_assoc($result)) {
			$newalcomment[$i] = $row;
			$i++;
		}
		return $newalcomment;
	}

	//新粉丝
	public function newfans($user){
		$row = $this->getUsersValue($user);
		$newfans = $row['newfans'];
		return $newfans;
	}

	//当前用户粉丝
	public function tafans($user){
		$row = $this->getUsersValue($user);
		$fans = explode('|',$row['fans']);
		$fansArr = array();
		foreach ($fans as $key => $value){
			if(!empty($value)){ //不为空
				$fansArr[$key]['name'] = $value; //粉丝名
				$fansArr[$key]['photo'] = $this->getphoto($value,'48x48'); //粉丝照片
				$fansArr[$key][$value] = $this->getThreeArts($value); //粉丝的文章
			}
		}
		return $fansArr;
	}

	//读取用户信息
	public function getUsersValue($user){
		$select = "SELECT fans,attention,newfans,signature,sex FROM users WHERE user_name='$user' LIMIT 1";
		$result = $this->selectSql($select);
		$row = mysql_fetch_assoc($result);
		return $row;
	}

	//读取附件后缀名
	public function getfiletype($filename) {
		$pattern = "/.+\.([a-zA-Z]+)$/";
		preg_match_all($pattern,$filename,$matches);
		//预设图标
		$icon_arr = array();
		$icon_arr = array('chm','doc','docx','ppt','pptx','xlsx','html','jpeg','jpg','bmp','png','gif','rar','zip','tpl','txt','xml','js','php','css','pdf','psd');
		if (in_array(strtolower($matches[1][0]),$icon_arr)) { //如果文件格式在预设图标内
			return $matches[1][0]; //设置相应图标
		}
		else { //否则
			return 'unknow'; //设置默认图标
		}
	}

	//读取附件信息
	public function getfileinfo($select) {
		$result = $this->selectSql($select);
		$i = 0;
		$filenames = array();
		while ($row = mysql_fetch_assoc($result)) {
			$filename = array();
			$filename = explode('/',$row['attachment_path']);
			$filenames[$i] = $row;
			$filenames[$i]['filename'] = preg_replace("/^\d+\-/",'',$filename[1],1); //文件名
			$filenames[$i]['filetype'] = strtolower($this->getfiletype($filename[1])); //文件类型
			//处理文件名中的特殊字符
			$attachment_path = preg_replace("/\+/",'%2B',$row['attachment_path']);
			$attachment_path = preg_replace("/\#/",'%23',$attachment_path);
			$attachment_path = preg_replace("/\&/",'%26',$attachment_path);
			$filenames[$i]['filepath'] = './data/attachment/'.$attachment_path; //文件路径
			$physicalpath = dirname(__FILE__).'\data\attachment\\'.$filename[0].'\\'.$filename[1]; //文件物理位置
			$physicalpath = iconv('utf-8','gb2312',$physicalpath); //处理中文文件名
			$filesize = @filesize($physicalpath);
			$filesize_str = '';
			if ($filesize > 1024) { //KB
				if ($filesize/1024 > 1024) { //MB
					$filesize_str = round($filesize/1024/1024,2).'MB';
				}
				else {
					$filesize_str = round($filesize/1024,2).'KB';
				}
			}
			else {
				$filesize_str = $filesize.'字节';
			}
			$filenames[$i]['filesize'] = $filesize_str; //文件大小
			$i++;
		}
		return $filenames;
	}

	//获取用户头像	
	public function getphoto($user,$size){
		$select = "SELECT user_photo FROM users WHERE user_name='$user' LIMIT 1";
		if(!$result = mysql_query($select)){
			die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
		}
		$row = mysql_fetch_assoc($result);
		$path = './data/userphotos/';
		$file = $row['user_photo'];
		$is_exists = $path.$size.'/'.iconv('utf-8','gb2312',$file);
		$avatar = $path.$size.'/'.$file;
		//如果没有缩略图，则使用原图
		if(!file_exists($is_exists)){
			$avatar = $path.$file;
		}
		return $avatar;
	}

	//获取指定文章的评论个数
	public function getCommentNum($id,$replyId,$table,$artid){
		$select = "SELECT $id FROM $table WHERE $replyId='$artid'";
		$result = $this->selectSql($select);
		return mysql_num_rows($result);
	}

	//获取指定文章的收藏数
	public function getCollectNum($artid){
		$select = "SELECT collect_id FROM collects WHERE assocart_id='$artid'";
		$result = $this->selectSql($select);
		return mysql_num_rows($result);
	}

	//读取用户最新三篇文章
	public function getThreeArts($tmpname){
		$select = "SELECT topics_id,article_title FROM articles WHERE user_name='$tmpname' ORDER BY article_time DESC LIMIT 3";
		$result = $this->selectSql($select);
		$arts = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$arts[$i] = $row;
			$i++;
		}
		return $arts;
	}

	//查找对应留言的关联回复
	public function getreplys($table,$associd){
		$select = "SELECT replyname,replytext,replytime,commenter_os,commenter_browser FROM $table WHERE associd='$associd' ORDER BY replytime";
		$result = $this->selectSql($select);
		$replys = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$replys[$i] = $row;
			$replys[$i]['replyphoto'] = $this->getphoto($row['replyname'],'40x40');
			$i++;
		}
		return $replys;
	}

	//换算时间
	public function getFormatTime($article_time){
		$tmptime = time() - strtotime($article_time); //时间戳差
		if ($tmptime >= 60) {
			if ($tmptime >= 3600) {
				if ($tmptime >= 3600*24) {
					if ($tmptime > 3600*24*30) { //如果时间差超过1个月
						$tmptime = date('Y-m-d',strtotime($article_time)); //输出年-月-天
					}
					else { //如果大于1天小于1个月
						if (intval(($tmptime%(3600*24))/3600) > 0) {
							$tmptime = intval($tmptime/3600/24).'天'.intval(($tmptime%(3600*24))/3600).'小时前'; //输出 约xx天xx小时前
						}
						else { //如果没有多余小时
							$tmptime = intval($tmptime/3600/24).'天前'; //输入 约xx天前
						}
					}
				}
				else { //如果大于1小时小于1天
					if (intval(($tmptime%3600)/60) > 0) {
						$tmptime = intval($tmptime/3600).'小时'.intval(($tmptime%3600)/60).'分钟前'; //输出 约xx小时xx分钟前
					}
					else { //如果没有多余分钟
						$tmptime = intval($tmptime/3600).'小时前'; //输入 约xx小时前
					}
				}
			}
			else { //如果大于1分钟小于1小时
				$tmptime = intval($tmptime/60).'分钟前'; //输出 约xx分钟前
			}
		}
		else { //如果小于1分钟
			$tmptime = $tmptime.'秒前'; //输出 约xx秒前
		}
		return $tmptime;
	}

	//不同方式获取文章
	public function getArtsBy($select){
		$result = $this->selectSql($select);
		$arr = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$arr[$i] = $row;
			$urow = $this->getUsersValue($row['user_name']);
			$arr[$i]['arttime'] = $this->getFormatTime($row['article_time']); //用于显示的时间
			$arr[$i]['article_content'] = preg_replace("/&nbsp;/",'',strip_tags(html_entity_decode($row['article_content'],ENT_QUOTES)));
			$arr[$i]['userphoto'] = $this->getphoto($row['user_name'],'60x80');
			$arr[$i]['signature'] = $urow['signature'];
			$i++;
		}
		return $arr;
	}

	//生成缩略图
	public function thumbnails($srcFile,$dstFile,$maxWidth,$maxHeight){
		//没有最大内存限制
		ini_set('memory_limit', '-1');
		//设置无超时
		set_time_limit(0);

		//读取图片类型
		$pattern = "/(.+).(jpg|JPG|jpeg|JPEG|png|PNG|gif|GIF)$/";
		preg_match($pattern,$srcFile,$matches);
		$type = strtolower($matches[2]);
		$type = ($type=='jpg')? 'jpeg' : $type;

		//读取原图宽高
		list($srcPicW, $srcPicH) = @getimagesize($srcFile);

		//压缩算法
		if(!empty($maxHeight)){
			// 缩略图尺寸(按固定大小)
			if($srcPicW > $maxWidth){
				$dstPicW = $maxWidth;
			}
			else{
				$dstPicW = $srcPicW;
			}
			if($srcPicH > $maxHeight){
				$dstPicH = $maxHeight;
			}
			else{
				$dstPicH = $srcPicH;
			}
		}
		else{
			// 缩略图尺寸(按比例)
			if($srcPicW > $maxWidth){
				$percent = $maxWidth / $srcPicW;
				$dstPicW = $srcPicW * $percent;
				$dstPicH = $srcPicH * $percent;
			}
			else{
				$dstPicW = $srcPicW;
				$dstPicH = $srcPicH;
			}
		}
		
		//缩略图在画布中显示的坐标  
		$dstPicX = 0;
		$dstPicY = 0;

		//原图裁切的坐标  
		$srcPicX = 0;
		$srcPicY = 0;
		
		// 加载图像
		switch($type){
			case 'jpeg': {
				$srcIm = @imagecreatefromjpeg($srcFile);
			}
			break;
			case 'gif': {
				$srcIm = @imagecreatefromgif($srcFile);
			}
			break;
			case 'png': {
				$srcIm = @imagecreatefrompng($srcFile);
			}
		}
		  
		$dstIm = @imagecreatetruecolor($dstPicW, $dstPicH);
		  
		// 调整大小  
		@imagecopyresized($dstIm,$srcIm,$dstPicX,$dstPicY,$srcPicX,$srcPicY,$dstPicW,$dstPicH,$srcPicW,$srcPicH);
		  
		//另存为自定义的文件
		switch($type){
			case 'jpeg': {
				@imagejpeg($dstIm,$dstFile,100);
			}
			break;
			case 'gif': {
				@imagegif($dstIm,$dstFile,100);
			}
			break;
			case 'png': {
				@imagepng($dstIm,$dstFile,9);
			}
		}
		  
		//释放内存  
		@imagedestroy($dstIm);
		@imagedestroy($srcIm);
	}

	//生成裁剪图
	public function imgcutout($srcFile,$dstFile,$maxWidth,$maxHeight) {
		//没有最大内存限制
		ini_set('memory_limit', '-1');
		//设置无超时
		set_time_limit(0);

		//获取图片类型
		$pattern = "/(.+).(jpg|JPG|jpeg|JPEG|png|PNG|gif|GIF)$/";
		preg_match($pattern,$srcFile,$matches);
		$type = strtolower($matches[2]);
		$type = ($type=='jpg')? 'jpeg' : $type;

		//用于存放裁切图片的画布宽
		$dstPicW = $maxWidth;
		$dstPicH = $maxHeight;

		//裁切的宽高
		$srcPicW = $maxWidth;
		$srcPicH = $maxHeight;

		//裁切的坐标点
		$srcPicX = 0;
		$srcPicY = 0;

		//读取原图宽高
		list($sW, $sH) = @getimagesize($srcFile);

		//从图片中心裁切
		$diffW = ($maxWidth-$sW)/2;
		$diffH = ($maxHeight-$sH)/2;

		if($diffW <= 0) {
			$srcPicX = -$diffW;
		}
		if($diffH <= 0) {
			$srcPicY = -$diffH;
		}
		//裁切的图片在画布中显示的x坐标
		$dstPicX = ($dstPicW-$srcPicW)/2;
		$dstPicY = ($dstPicH-$srcPicH)/2;
		
		// 加载图像
		switch($type){
			case 'jpeg': {
				$srcIm = @imagecreatefromjpeg($srcFile);
			}
			break;
			case 'gif': {
				$srcIm = @imagecreatefromgif($srcFile);
			}
			break;
			case 'png': {
				$srcIm = @imagecreatefrompng($srcFile);
			}
		}

		$dstIm = @imagecreatetruecolor($dstPicW, $dstPicH);
		$dstImBg = @imagecolorallocate($dstIm,255,255,255);

		//创建背景为白色的图片  
		imagefill($dstIm,0,0,$dstImBg);
		imagecopy ($dstIm,$srcIm,$dstPicX,$dstPicY,$srcPicX,$srcPicY,$srcPicW,$srcPicH);

		//另存为自定义的文件
		switch($type){
			case 'jpeg': {
				@imagejpeg($dstIm,$dstFile,100);
			}
			break;
			case 'gif': {
				@imagegif($dstIm,$dstFile,100);
			}
			break;
			case 'png': {
				@imagepng($dstIm,$dstFile,9);
			}
		}

		//释放内存  
		@imagedestroy($dstIm);
		@imagedestroy($srcIm);
	}

	//获取某类文章列表
	public function getCategoryList($category,$range="0, 5",$extra="",$keyword="",$search_range="article_content"){
		$condition = "article_type='$category'";

		if ($category == '全部') {
			$condition = "1=1";
		}

		if ($range) {
			$limit = " LIMIT ".$range;
		}

		if ($extra) {
			$condition .= ' AND '.$extra;
		}

		//统计每个分类下的总数量
		$countSelect = "SELECT topics_id FROM articles WHERE ".$condition;
		$countResult = $this->selectSql($countSelect);
		$totalCount = mysql_num_rows($countResult);

		$select = "SELECT topics_id,user_name,article_title,left(article_content, 1000) AS article_content,article_time,article_type,visit_num FROM articles WHERE ".$condition." ORDER BY article_time DESC".$limit;
		$result = $this->selectSql($select);
		$someCategoryArt = array();
		$i = 0;

		while ($row = mysql_fetch_assoc($result)) {
			$topics_id = $row['topics_id'];
			$article_title = $row['article_title'];
			$article_type = $row['article_type'];
			$user_name = $row['user_name'];
			$article_content = preg_replace("/&nbsp;/",'',strip_tags(html_entity_decode($row['article_content'],ENT_QUOTES),'<img>'));
			//图片懒加载
			$article_content = preg_replace("/<img src=/i",'<img src="images/lazyload.png" data-lazyload-src=',$article_content);

			//搜索关键字高亮
			if ($keyword) {
				//搜索文章内容
				if ($search_range === 'article_content') {
					$article_content = preg_replace("/{$keyword}/","<span class=\"keyword\">{$keyword}</span>",$article_content);
				}
				//搜索文章标题
				if ($search_range === 'article_title') {
					$article_title = preg_replace("/{$keyword}/","<span class=\"keyword\">{$keyword}</span>",$article_title);
				}
				//搜索文章类型
				if ($search_range === 'article_type') {
					$article_type = preg_replace("/{$keyword}/","<span class=\"keyword\">{$keyword}</span>",$article_type);
				}
				//搜索文章作者
				if ($search_range === 'user_name') {
					$user_name = preg_replace("/{$keyword}/","<span class=\"keyword\">{$keyword}</span>",$user_name);
				}
			}

			$someCategoryArt[$i] = $row;
			$someCategoryArt[$i]['article_title'] = $article_title;
			$someCategoryArt[$i]['article_type'] = $article_type;
			$someCategoryArt[$i]['article_content'] = $article_content;
			$someCategoryArt[$i]['user_name'] = $user_name;
			$someCategoryArt[$i]['commentnum'] = $this->getCommentNum('comment_id','reply_id','comments',$topics_id); //读取每篇文章评论数
			$someCategoryArt[$i]['collectnum'] = $this->getCollectNum($topics_id); //读取每篇文章收藏数
			$someCategoryArt[$i]['totalCount'] = $totalCount;
			$i++;
		}
		return $someCategoryArt;
	}

}

?>