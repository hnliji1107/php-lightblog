<?php
require('./globalheader.php');

//空间留言插入回复
if(!empty($_POST['type']) && $_POST['type'] == 'gub'){
	if(!empty($_POST['replytext']) && !empty($_POST['associd'])){
		$replytext = htmlspecialchars($_POST['replytext'],ENT_QUOTES);
		$replytext = preg_replace("/\n/",'<br />',$replytext);
		$commenter_os = $common->getos(); //评论者的操作系统
		$commenter_browser = $common->getbrowser(); //评论者的浏览器
		$associd = $_POST['associd'];
		$replytime = date('Y-m-d H:i:s');
		$insert = "INSERT INTO msgs_replys (replyname,replytext,replytime,associd,commenter_os,commenter_browser) VALUES
					('$now_user','$replytext','$replytime','$associd','$commenter_os','$commenter_browser')";
		
		if(!mysql_query($insert,$con)){
			die(json_encode(array('status'=>0,'msg'=>'回复失败！')));
		}
		
		$timearr = getdate(strtotime($replytime));
		$mon = $timearr['mon'] > 9? $timearr['mon'] : '0'.$timearr['mon'];
		$mday = $timearr['mday'] > 9? $timearr['mday'] : '0'.$timearr['mday'];
		$hours = $timearr['hours'] > 9? $timearr['hours'] : '0'.$timearr['hours'];
		$minutes = $timearr['minutes'] > 9? $timearr['minutes'] : '0'.$timearr['minutes'];
		$replytime = $mon.'-'.$mday.' '.$hours.':'.$minutes;
		$userphoto = $common->getphoto($now_user,'40x40');
		$msg = "<div class=\"replys wb-clr\"><div class=\"re_user_photo usertip-wrapper\"><input type=\"hidden\" value=\"{$now_user}\" class=\"ta-name\" /><img src=\"{$userphoto}\" alt=\"{$now_user}\" title=\"{$now_user}\" /></div><div class=\"re_user_info\"><p class=\"nametime\"><span style=\"color:#369;\">{$now_user}</span> {$replytime} / {$commenter_os} / {$commenter_browser}</p><p>{$replytext}</p></div></div>";
		echo json_encode(array('status'=>1,'msg'=>$msg));
	}
}

//插入文章评论回复
if(!empty($_POST['type']) && $_POST['type'] == 'art'){
	if(!empty($_POST['replytext']) && !empty($_POST['associd']) && !empty($_POST['receiver'])){
		$replytext = htmlspecialchars($_POST['replytext'],ENT_QUOTES);
		$replytext = preg_replace("/\n/",'<br />',$replytext);
		$commenter_os = $common->getos();
		$commenter_browser = $common->getbrowser();
		$associd = $_POST['associd'];
		$receiver = $_POST['receiver'];
		$replytime = date('Y-m-d H:i:s');
		$insert = "INSERT INTO replys (replyname,replytext,replytime,associd,receiver,is_newreply,commenter_os,commenter_browser) VALUES
					('$now_user','$replytext','$replytime','$associd','$receiver',1,'$commenter_os','$commenter_browser')";
		
		if(!mysql_query($insert,$con)){
			die(json_encode(array('status'=>0,'msg'=>'回复失败！')));
		}
		
		$timearr = getdate(strtotime($replytime));
		$mon = $timearr['mon'] > 9? $timearr['mon'] : '0'.$timearr['mon'];
		$mday = $timearr['mday'] > 9? $timearr['mday'] : '0'.$timearr['mday'];
		$hours = $timearr['hours'] > 9? $timearr['hours'] : '0'.$timearr['hours'];
		$minutes = $timearr['minutes'] > 9? $timearr['minutes'] : '0'.$timearr['minutes'];
		$replytime = $mon.'-'.$mday.' '.$hours.':'.$minutes;
		$userphoto = $common->getphoto($now_user,'40x40');
		$msg = "<div class=\"replys wb-clr\"><div class=\"re_user_photo usertip-wrapper\"><input type=\"hidden\" value=\"{$now_user}\" class=\"ta-name\" /><img src=\"{$userphoto}\" alt=\"{$now_user}\" title=\"{$now_user}\" /></div><div class=\"re_user_info\"><p class=\"uinfo\"><span class=\"uname\">{$now_user}</span><span class=\"pinfo\"> {$replytime} / {$commenter_os} / {$commenter_browser}</span></p><p>{$replytext}</p></div></div>";
		echo json_encode(array('status'=>1,'msg'=>$msg));
	}
}

//插入相片评论回复
if(!empty($_POST['type']) && $_POST['type'] == 'alb'){
	if(!empty($_POST['album_replytext']) && !empty($_POST['album_associd'])){
		$replytext = htmlspecialchars($_POST['album_replytext'],ENT_QUOTES);
		$replytext = preg_replace("/\n/",'<br />',$replytext);
		$commenter_os = $common->getos();
		$commenter_browser = $common->getbrowser();
		$associd = $_POST['album_associd'];
		$replytime = date('Y-m-d H:i:s');
		$insert = "INSERT INTO alcomment_replys (replyname,replytext,replytime,associd,commenter_os,commenter_browser) VALUES
					('$now_user','$replytext','$replytime','$associd','$commenter_os','$commenter_browser')";
		
		if(!mysql_query($insert,$con)){
			die(json_encode(array('status'=>0,'msg'=>'回复失败！')));
		}
		
		$timearr = getdate(strtotime($replytime));
		$mon = $timearr['mon'] > 9? $timearr['mon'] : '0'.$timearr['mon'];
		$mday = $timearr['mday'] > 9? $timearr['mday'] : '0'.$timearr['mday'];
		$hours = $timearr['hours'] > 9? $timearr['hours'] : '0'.$timearr['hours'];
		$minutes = $timearr['minutes'] > 9? $timearr['minutes'] : '0'.$timearr['minutes'];
		$replytime = $mon.'-'.$mday.' '.$hours.':'.$minutes;
		$userphoto = $common->getphoto($now_user,'40x40');
		$msg = "<div class=\"replys wb-clr\"><div class=\"re_user_photo usertip-wrapper\"><input type=\"hidden\" value=\"{$now_user}\" class=\"ta-name\" /><img src=\"{$userphoto}\" alt=\"{$now_user}\" title=\"{$now_user}\" /></div><div class=\"re_user_info\"><p class=\"nametime\"><span style=\"color:#369;\">{$now_user}</span> {$replytime} / {$commenter_os} / {$commenter_browser}</p><p>{$replytext}</p></div></div>";
		echo json_encode(array('status'=>1,'msg'=>$msg));
	}
}

//收藏,作者不能收藏
if(!empty($_POST['artid']) && !empty($_POST['autor'])){
	$collect_time = date('Y-m-d H:i:s');
	$assocart_id = $_POST['artid'];
	$autor = $_POST['autor'];
	
	if($autor==$now_user){
		echo json_encode(array('status'=>0,'msg'=>'作者本人不能收藏！'));
	}
	else{
		$select = "SELECT assocart_id FROM collects WHERE collecter='$now_user' AND assocart_id='$assocart_id'";
		$result = $common->selectSql($select);
		if(mysql_num_rows($result)>0){ //如果已经收藏过,则不再收藏
			echo json_encode(array('status'=>0,'msg'=>'你已经收藏过该文章！'));
		}
		else{ //否则收藏文章
			$insert = "INSERT INTO collects (collecter,collect_time,assocart_id) VALUES
						('$now_user','$collect_time','$assocart_id')";
			if(!mysql_query($insert,$con)){
				die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
			}
			echo json_encode(array('status'=>1,'msg'=>'收藏成功！'));
		}
	}
}

//读取关注或粉丝
function getFansAtten($common,$user){
	$select = "SELECT fans,attention FROM users WHERE user_name='$user' LIMIT 1";
	$result = $common->selectSql($select);
	$row = mysql_fetch_assoc($result);
	return $row;
}

//加关注
if(!empty($_POST['spacer']) && !empty($_POST['fans'])){
	$spacer = $_POST['spacer'];
	$newFans = $_POST['fans']; //新粉丝
	$fansRow = getFansAtten($common,$spacer); //当前空间用户者粉丝
	$attenRow = getFansAtten($common,$now_user); //当前用户原有关注人
	//把名字分开
	$fansArr = explode('|',$fansRow['fans']); 
	$attenArr = explode('|',$attenRow['attention']);
	
	if (in_array($newFans,$fansArr,true)) {
		echo json_encode(array('status'=>0,'msg'=>'你已经关注过了！'));
	}
	else {
		//添加粉丝
		$fansStr = $fansRow['fans'];
		$fansStr .= $newFans.'|';
		//添加关注
		$attenStr = $attenRow['attention'];
		$attenStr .= $spacer.'|';
		//更新粉丝
		$update = "UPDATE users SET fans='$fansStr',newfans=newfans+1 WHERE user_name='$spacer' LIMIT 1";
		if(!mysql_query($update,$con)){
			die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
		}
		//更新关注
		$update = "UPDATE users SET attention='$attenStr' WHERE user_name='$now_user' LIMIT 1";
		if(!mysql_query($update,$con)){
			die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
		}
		echo json_encode(array('status'=>1,'msg'=>'关注成功！'));
	}
}

//根据昵称搜索
if (!empty($_POST['user'])) {
	$user = $_POST['user'];
	$searResult = array(); //搜索结果
	$searResult['status'] = 0;
	$row = $common->getUsersValue($user);
	$parent_fanArr = explode('|',$row['fans']); //当前用户粉丝数
	$parent_attenArr = explode('|',$row['attention']); //当前用户关注数
	$key = $_POST['key']; //搜索关键字
	
	//搜索粉丝
	if (!empty($_POST['act']) && $_POST['act'] == 'fans') {
		if(!in_array($key,$parent_fanArr)){
			$searResult['msg'] = '你要查找的用户不在粉丝列表中！';
		}
		else{
			//结果人的信息
			$select = "SELECT sex,fans,attention FROM users WHERE user_name='$key' LIMIT 1";
			$result = $common->selectSql($select);
			$row = mysql_fetch_assoc($result);
			$sex = $row['sex']; //结果人性别
			$photo = $common->getphoto($key,'50x50'); //结果人相片
			$fansnum = count(explode('|',$row['fans']))-1; //结果人粉丝数
			$attennum = count(explode('|',$row['attention']))-1; //结果人关注数
			$wraptp = "<div class=\"fans_list sear_fans_list\">
				<h3><a href=\"#\" class=\"close\"></a>搜索结果：</h3>
				<ul>
					<li class=\"wb-clr\">
						<div class=\"fans_photo usertip-wrapper\">
							<input type=\"hidden\" value=\"{$key}\" class=\"ta-name\" />
							<a href=\"perspace.php?user={$key}\"><img src=\"{$photo}\" /></a>
						</div>
						<div class=\"fans_info\">
							<h4>{$key}</h4>
							<span>{$sex}&nbsp;&nbsp;粉丝{$fansnum}人&nbsp;&nbsp;关注{$attennum}人</span>
						</div>
					</li>
				</ul>
			</div>";
			$searResult = array(
				'status' => 1,
				'msg' => $wraptp.$limd.$wrapbt
			);
		}
		echo json_encode($searResult);
	}
	
	//搜索关注
	if (!empty($_POST['act']) && $_POST['act'] == 'attention') {
		if(!in_array($key,$parent_attenArr)){
			$searResult['msg'] = '你要查找的用户不在关注列表中！';
		}
		else{
			//结果人的信息
			$select = "SELECT sex,fans,attention FROM users WHERE user_name='$key' LIMIT 1";
			$result = $common->selectSql($select);
			$row = mysql_fetch_assoc($result);
			$sex = $row['sex']; //结果人性别
			$photo = $common->getphoto($key,'50x50'); //结果人相片
			$fansnum = count(explode('|',$row['fans']))-1; //结果人粉丝数
			$attennum = count(explode('|',$row['attention']))-1; //结果人关注数
			$wraptp = "<div class=\"fans_list sear_fans_list\">
				<h3><a href=\"#\" class=\"close\"></a>搜索结果：</h3>
				<ul>
					<li class=\"wb-clr\">
						<div class=\"fans_photo usertip-wrapper\">
							<input type=\"hidden\" value=\"{$key}\" class=\"ta-name\" />
							<a href=\"perspace.php?user={$key}\"><img src=\"{$photo}\" /></a>
						</div>
						<div class=\"fans_info\">
							<h4>{$key}</h4>
							<span>{$sex}&nbsp;&nbsp;粉丝{$fansnum}人&nbsp;&nbsp;关注{$attennum}人</span>
						</div>
					</li>
				</ul>
			</div>";
			$searResult = array(
				'status' => 1,
				'msg' => $wraptp.$limd.$wrapbt
			);
		}
		echo json_encode($searResult);
	}
}

//移除粉丝/取消关注
if(!empty($_POST['fanAttener']) && !empty($_POST['loginer'])){
	$loginer = $_POST['loginer'];
	$fanAttener = $_POST['fanAttener'];
	$row = $common->getUsersValue($loginer);
	$parent_fanArr = explode('|',$row['fans']); //当前用户粉丝数
	$parent_attenArr = explode('|',$row['attention']); //当前用户关注数
	$remove = array();
	$remove['status'] = 1;

	//移除粉丝
	if(!empty($_POST['act']) && $_POST['act'] == 'removeFans'){
		if(!in_array($fanAttener,$parent_fanArr)){
			$remove['status'] = 0;
			$remove['msg'] = '不在粉丝列表中，不能删除！';
		}
		else{
			$newfanstr = ''; //新粉丝字符串

			foreach ($parent_fanArr as $value){ //在粉丝中查找
				if(!empty($value)){ //不空时
					if($value == $fanAttener){ //如果在粉丝中查找到
						continue; //跳出本次循环
					}
					$newfanstr .= $value.'|';
				}
			}
			
			//更新粉丝列表
			$update = "UPDATE users SET fans='$newfanstr' WHERE user_name='$loginer' LIMIT 1";
			if(!mysql_query($update,$con)){
				$remove['status'] = 0;
				$remove['msg'] = '移除粉丝失败！';
			}
			
			//更新对应粉丝的关注列表
			$row = $common->getUsersValue($fanAttener);
			$assoc_attenArr = explode('|',$row['attention']); //当前用户关注数
			$assoc_newattenstr = ''; //对应新关注字符串

			foreach ($assoc_attenArr as $value){ //在关注中查找
				if(!empty($value)){ //不空时
					if($value == $loginer){ //如果在关注中查找到
						continue; //跳出本次循环
					}
					$assoc_newattenstr .= $value.'|';
				}
			}
			
			//更新对应关注列表
			$update = "UPDATE users SET attention='$assoc_newattenstr' WHERE user_name='$fanAttener' LIMIT 1";
			if(!mysql_query($update,$con)){
				$remove['status'] = 0;
				$remove['msg'] = '移除粉丝失败！';
			}
			$remove['msg'] = '粉丝移除成功！'; //标示为一处粉丝
		}
	}
	
	//取消关注
	if(!empty($_POST['act']) && $_POST['act'] == 'removeAtten'){
		if(!in_array($fanAttener,$parent_attenArr)){
			$remove['status'] = 0;
			$remove['msg'] = '不在关注列表中，不能删除！';
		}
		else{
			$newAttenStr = ''; //新关注字符串

			foreach ($parent_attenArr as $value){ //在关注中查找
				if(!empty($value)){ //不空时
					if($value == $fanAttener){ //如果在粉丝中查找到
						continue; //跳出本次循环
					}
					$newAttenStr .= $value.'|';
				}
			}
			
			//更新关注列表
			$update = "UPDATE users SET attention='$newAttenStr' WHERE user_name='$loginer' LIMIT 1";
			if(!mysql_query($update,$con)){
				$remove['status'] = 0;
				$remove['msg'] = '取消关注失败！';
			}
			
			//更新对应关注的粉丝列表
			$select = "SELECT fans FROM users WHERE user_name='$fanAttener' LIMIT 1";
			$result = $common->selectSql($select);
			$row = mysql_fetch_assoc($result);
			$assoc_fansArr = explode('|',$row['fans']); //当前用户粉丝数
			$assoc_newfanstr = ''; //对应新关注字符串

			foreach ($assoc_fansArr as $value){ //在关注中查找
				if(!empty($value)){ //不空时
					if($value == $loginer){ //如果在关注中查找到
						continue; //跳出本次循环
					}
					$assoc_newfanstr .= $value.'|';
				}
			}
			
			//更新对应关注列表
			$update = "UPDATE users SET fans='$assoc_newfanstr' WHERE user_name='$fanAttener' LIMIT 1";
			if(!mysql_query($update,$con)){
				$remove['status'] = 0;
				$remove['msg'] = '取消关注失败！';
			}
			$remove['msg'] = '成功取消关注！'; //标示为取消关注
		}
	}
	echo json_encode($remove);
}

//找人
if(!empty($_POST['act']) && $_POST['act'] == 'find' && !empty($_POST['fdcondition'])){
	$fdcondition = $_POST['fdcondition']; //查找条件
	//取出所有用户
	$select = "SELECT user_id,user_name,sex,fans,attention FROM users WHERE user_name LIKE '%$fdcondition%'";
	$result = $common->selectSql($select);
	$findres = mysql_num_rows($result); //是否查找到
	$finds = array();
	$finds['status'] = 1; //默认有找到
	
	if($findres <= 0){
		$finds = array(
			'status' => 0,
			'msg' => '未找到该用户！'
		);
		echo json_encode($finds);
		exit;
	}
	
	$userArr = array();
	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$name = $row['user_name']; //用户名字
		$sex = $row['sex']; //用户性别
		$fans = $row['fans']; //用户粉丝
		$attention = $row['attention']; //用户关注
		$userArr[$i++] = array(
			'name' => $name, 
			'sex' => $sex, 
			'photo' => $common->getphoto($name,'50x50'), 
			'fansnum' => count(explode('|',$fans))-1, 
			'attennum' => count(explode('|',$attention))-1
		);
	}
	
	//输出div上部分
	$wraptp = "<div class=\"fans_list sear_fans_list\"><h3><a href=\"#\" class=\"close\"></a>搜索结果：</h3><ul>";
	//输出div中间部分
	$wrapmd = ''; 
	foreach ($userArr as $item){
		//li上部分
		$litp = "<li class=\"wb-clr\">
					<div class=\"fans_photo ajax_photo usertip-wrapper\">
						<input type=\"hidden\" value=\"{$item['name']}\" class=\"ta-name\" />
						<a href=\"perspace.php?user={$item['name']}\"><img src=\"{$item['photo']}\" /></a>
					</div>
					<div class=\"fans_info\">
						<h4>{$item['name']}</h4>
						<span>{$item['sex']}&nbsp;&nbsp;粉丝{$item['fansnum']}人&nbsp;&nbsp;关注{$item['attennum']}人</span>
					</div>
				</li>";
			$li = $litp.$limd.$libt;
			$wrapmd .= $li;
	}
	
	//输出div下部分
	$wrapbt = "</ul></div>";
	$wrap = $wraptp.$wrapmd.$wrapbt;
	$finds['msg'] = $wrap;
	echo json_encode($finds);
}

//发私信
if (!empty($_POST['sendObject']) && !empty($_POST['textObject'])) {
	$sender = $now_user; //发送者
	$sms_time = date('Y-m-d H:i:s'); //发送时间
	$sms_text = $_POST['textObject']; //发送内容
	$geter = $_POST['sendObject']; //接受者
	$errtip = array();
	$errtip['status'] = 1;

	if ($geter == $sender) {
		$errtip = array(
			'status' => 0,
			'msg' => '不能给自己发私信！'
		);
		die(json_encode($errtip));
	}

	//首先搜索接收者是否存在
	if ($common->check_user($geter)) {
		$errtip = array(
			'status' => 0,
			'msg' => '接收者不存在！'
		);
		die(json_encode($errtip));
	}

	//保存私信
	$insert = "INSERT INTO user_sms (sender,sms_text,sms_time,geter,unread) VALUES ('$sender','$sms_text','$sms_time','$geter','1')";
	
	if(!mysql_query($insert,$con)){
		$errtip = array(
			'status' => 0,
			'msg' => '抱歉，发送私信失败！'
		);
		die(json_encode($errtip));
	}

	$errtip['msg'] = '恭喜，发送私信成功！';
	echo json_encode($errtip);
}

//更新未读私信
if (!empty($_POST['sms_id'])) {
	$sms_id = $_POST['sms_id'];
	$update = "UPDATE user_sms SET unread=0 WHERE sms_id='$sms_id' LIMIT 1";
	if (!mysql_query($update,$con)) {
		die(json_encode(array('status'=>0,'msg'=>'私信更新失败！')));
	}
	echo json_encode(array('status'=>1,'msg'=>'私信更新成功！'));
}

//删除实体附件
function delattachment($common,$table,$attachment_id) {
	//读取附件路径
	$select = "SELECT attachment_path FROM $table WHERE attachment_id='$attachment_id' LIMIT 1";
	$result = $common->selectSql($select);
	$row = mysql_fetch_assoc($result);
	$file_name = $row['attachment_path'];
	$filearr = array();
	$filearr = explode('/',$file_name);
	//删除实体文件
	$file_phys_path = dirname(__FILE__).'\data\attachment\\'.$filearr[0].'\\'.$filearr[1];
	$file_phys_path = iconv('utf-8','gb2312',$file_phys_path);
	if(file_exists($file_phys_path)){
		if(file_exists($file_phys_path)){
			unlink($file_phys_path);
		}
	}
}

//删除附件
if (!empty($_POST['attachment_id'])) {
	$attachment_id = $_POST['attachment_id'];
	if (!empty($_POST['percenter']) && $_POST['percenter'] == true) { //删除文章附件
		//删除实体附件
		delattachment($common,'article_attachment',$attachment_id);
		//删除数据库信息
		$delete = "DELETE FROM article_attachment WHERE attachment_id='$attachment_id' LIMIT 1";
		if (!mysql_query($delete,$con)) {
			die(json_encode(array('status'=>0,'msg'=>'数据删除失败！')));
		}
		echo json_encode(array('status'=>1,'msg'=>'附件删除成功！'));
	}
	else if (!empty($_POST['separticle']) && $_POST['separticle'] == true) { //删除评论附件
		//删除实体附件
		delattachment($common,'comment_attachment',$attachment_id);
		//删除数据库信息
		$delete = "DELETE FROM comment_attachment WHERE attachment_id='$attachment_id' LIMIT 1";
		if (!mysql_query($delete,$con)) {
			die(json_encode(array('status'=>0,'msg'=>'数据删除失败！')));
		}
		echo json_encode(array('status'=>1,'msg'=>'附件删除成功！'));
	}
}

//删除相册
if(!empty($now_user) && !empty($_POST['del_albumid'])){
	$album_id = $_POST['del_albumid']; //相册id
	//删除前，读取出所有相片并到文件夹下删除
	$select = "SELECT album_photos FROM user_album WHERE album_id='$album_id' LIMIT 1";
	$result = $common->selectSql($select);
	$row = mysql_fetch_assoc($result);
	$photoarr = array();
	$photoarr = explode('|',$row['album_photos']); //所有相片名称字符串转化数组
	$album_path = dirname(__FILE__).'\data\albums\album_'.$album_id.'\\'; //相册文件夹路径
	$album_path_100x100 = dirname(__FILE__).'\data\albums\album_'.$album_id.'\100x100\\'; //100x100缩略图文件夹
	$album_path_75x75 = dirname(__FILE__).'\data\albums\album_'.$album_id.'\75x75\\'; //75x75缩略图文件夹
	$album_path_50x50 = dirname(__FILE__).'\data\albums\album_'.$album_id.'\50x50\\'; //50x50缩略图文件夹

	foreach ($photoarr as $value){ //到具体文件夹下删除相片
		if(!empty($value)){ //过滤空值
			$value = iconv('utf-8','gb2312',$value); //处理中文名字问题
			if(file_exists($album_path_100x100.$value)){
				unlink($album_path_100x100.$value);
			}
			if(file_exists($album_path_75x75.$value)){
				unlink($album_path_75x75.$value);
			}
			if(file_exists($album_path_50x50.$value)){
				unlink($album_path_50x50.$value);
			}
			if(file_exists($album_path.$value)){
				unlink($album_path.$value);
			}
		}
	}

	$album_path = dirname(__FILE__).'\data\albums\album_'.$album_id; //相册文件夹路径
	if(is_dir($album_path_100x100)){
		rmdir($album_path_100x100); //删除规格100x100缩略图文件夹
	}
	if(is_dir($album_path_75x75)){
		rmdir($album_path_75x75); //删除规格75x75缩略图文件夹
	}
	if(is_dir($album_path_50x50)){
		rmdir($album_path_50x50); //删除规格50x50缩略图文件夹
	}
	if(is_dir($album_path)){
		rmdir($album_path); //删除相册文件夹
	}
	
	//删除数据库中数据
	$delete = "DELETE FROM user_album WHERE album_id='$album_id' LIMIT 1";
	if(!mysql_query($delete,$con)){
		die(json_encode(array('status'=>0,'msg'=>'抱歉，删除失败！')));
	}
	
	//删除相册的评论
	$delete = "DELETE FROM album_comment WHERE user_name='$now_user' AND album_id='$album_id'";
	if(!mysql_query($delete,$con)){
		die(json_encode(array('status'=>0,'msg'=>'抱歉，删除失败！')));
	}

	echo json_encode(array('status'=>1,'msg'=>'恭喜，删除成功！'));
	exit;
}

//编辑网站外链

//添加外链
if (!empty($_POST['act']) && $_POST['act'] == 'add' && !empty($_POST['linktitle']) && !empty($_POST['linkhref'])) {
	if ($_SESSION['user_name'] == '58lou') {
		$linktitle = $_POST['linktitle'];
		$linkhref = $_POST['linkhref'];

		$select = "SELECT link_id FROM outlinks WHERE link_title='$linktitle'";
		if(!$result = mysql_query($select,$con)){
			die(json_encode(array('status'=>0,'msg'=>mysql_error())));
		}
		
		if(mysql_num_rows($result) === 0){ //没有重复
			$linktime = date("Y-m-d H:i:s");
			$insert = "INSERT INTO outlinks (link_title,link_href,link_time) VALUES ('$linktitle','$linkhref','$linktime')";

			if(!mysql_query($insert,$con)){
				die(json_encode(array('status'=>0,'msg'=>mysql_error())));
			}

			echo json_encode(array('status'=>1,'msg'=>'恭喜，外链添加成功！','linkid'=>mysql_insert_id()));
		} else {
			echo json_encode(array('status'=>0,'msg'=>'外链已存在，不能重复添加！'));
		}
	} else {
		echo json_encode(array('status'=>0,'msg'=>'抱歉，你没有操作权限！'));
	}
}

//删除外链
if (!empty($_POST['act']) && $_POST['act'] == 'delete' && !empty($_POST['linkid'])) {
	if ($_SESSION['user_name'] == '58lou') {
		$linkid = $_POST['linkid'];
		
		//删除数据库中数据
		$delete = "DELETE FROM outlinks WHERE link_id='$linkid' LIMIT 1";
		if(!mysql_query($delete,$con)){
			die(json_encode(array('status'=>0,'msg'=>'抱歉，外链删除失败！')));
		}

		echo json_encode(array('status'=>1,'msg'=>'恭喜，外链删除成功！'));
	} else {
		echo json_encode(array('status'=>0,'msg'=>'抱歉，你没有操作权限！'));
	}
}

//编辑外链
if (!empty($_POST['act']) && $_POST['act'] == 'edit' && !empty($_POST['linkid'])) {
	if ($_SESSION['user_name'] == '58lou') {
		$linkid = $_POST['linkid'];
		$linktitle = $_POST['linktitle'];
		$linkhref = $_POST['linkhref'];

		if (empty($linktitle) && empty($linkhref)) {
			die(json_encode(array('status'=>0,'msg'=>'数据没有改变！')));
		}

		$select = "SELECT link_id FROM outlinks WHERE link_title='$linktitle' AND link_href='$linkhref'";
		if(!$result = mysql_query($select,$con)){
			die(json_encode(array('status'=>0,'msg'=>mysql_error())));
		}
		
		if(mysql_num_rows($result) === 0){ //没有重复
			if (!empty($linktitle) && !empty($linkhref)) {
				$setstr = "link_title='$linktitle',link_href='$linkhref'";
			}
			
			//更新数据
			$update = "UPDATE outlinks SET ".$setstr." WHERE link_id='$linkid' LIMIT 1";
			if(!mysql_query($update,$con)){
				die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
			}

			echo json_encode(array('status'=>1,'msg'=>'恭喜，外链修改成功！'));
		} else {
			echo json_encode(array('status'=>0,'msg'=>'外链已存在，请更改外链名称！'));
		}
	} else {
		echo json_encode(array('status'=>0,'msg'=>'抱歉，你没有操作权限！'));
	}
}

?>