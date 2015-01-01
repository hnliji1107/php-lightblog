<?php
require('./connect.php');
require('./libs/Smarty.class.php');
require('./class.common.php');

$smarty = new Smarty();
$common = new Common();
$now_user = $_SESSION['user_name'];//当前用户
$_SESSION['loginUrl'] = $common->getPageUrl(); //保存登录前页面url

if(!empty($_GET['user']) || !empty($_POST['albumName'])){ //相册
	$user = $_GET['user']? $_GET['user'] : $_POST['albumName']; //相册拥有者;
	if(!$common->check_user($user)){ //如果该用户存在
		//上次登录时间
		$select = "SELECT last_login_time,login_time FROM users WHERE user_name='$user' LIMIT 1";
		$result = $common->selectSql($select);
		$row = mysql_fetch_assoc($result);

		$smarty->assign('last_login_time',$row['last_login_time']);
		
		//处理上次登录时间
		if($_SESSION['user_name'] != $user){
			$smarty->assign('unlogin',true);
			$smarty->assign('re_login_time',$row['login_time']);
		}
		
		//读取用户信息
		$select = "SELECT user_id,user_name,sex,signature,user_photo FROM users WHERE user_name='$user' LIMIT 1";
		$result = $common->selectSql($select);
		$row = mysql_fetch_assoc($result);
		$origin_ph = $row['user_photo'];
		$is_exists = './data/userphotos/100x100/'.iconv('utf-8','gb2312',$origin_ph);
		$row['user_photo'] = './data/userphotos/100x100/'.$origin_ph;
		//如果没有缩略图，则使用原图
		if(!file_exists($is_exists)){
			$row['user_photo'] = './data/userphotos/'.$origin_ph;
		}
		
		$smarty->assign('isMobile',$common->is_mobile());
		$smarty->assign('now_user',$now_user);
		$smarty->assign('someUser',$row);
		$who_login = $user.'LastLogin';
		$smarty->assign('login_time',$_SESSION[$who_login]);
		
		//查找访问用户
		$user_id = $row['user_id']; //用户id
		$user_name = $row['user_name']; //用户名
		$select = "SELECT * FROM space_visiters WHERE assocart_id='$user_id'";
		$result = $common->selectSql($select);
		$visiters = array();
		$nowvisiter_arts = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$tmpname = $row['visiter_name'];
			$visiters[$i] = $row;
			$visiters[$i]['visiter_photo'] = $common->getphoto($tmpname,'48x48');
			$i++;
		}
		
		$smarty->assign('visiters',$visiters);
		$smarty->assign('nowvisiter_arts',$nowvisiter_arts);
		
		//保存访问用户
		$select = "SELECT * FROM space_visiters WHERE visiter_name='$now_user' AND
					assocart_id='$user_id'"; //读取当前空间访问表中用户
		$result = $common->selectSql($select);
		if($now_user !== $user_name && !empty($user_id)){ //访问者不包括作者
			$now_time = date('Y-m-d H:i:s');
			if(mysql_num_rows($result)>0){ //如果当前用户已访问过该文章，则更新最新时间
				$update = "UPDATE space_visiters SET visiter_time='$now_time' WHERE
							visiter_name='$now_user' AND assocart_id='$user_id'";
				if(!mysql_query($update,$con)){
					die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
				}
			}
			else if(!empty($now_user)){ //如果有登陆用户且表中不存在该用户，则添加入表中
				$insert = "INSERT INTO space_visiters (visiter_name,visiter_time,assocart_id) VALUES
						('$now_user','$now_time','$user_id')";
				if(!mysql_query($insert,$con)){
					die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
				}
			}
		}
		
		//用户关注
		$row = $common->getUsersValue($user_name);
		$attentions = explode('|',$row['attention']);
		$attenArr = array();
		
		foreach ($attentions as $key => $value){
			if(!empty($value)){ //不为空
				$attenArr[$key]['name'] = $value; //关注者名名称
				$attenArr[$key]['photo'] = $common->getphoto($value,'48x48'); //关注者照片
			}
		}
		
		$smarty->assign('attenArr',$attenArr); //关注者
		
		//更新未读相册评论
		if ($now_user == $user) {
			if(!empty($_GET['act']) && $_GET['act'] == 'look' && !empty($_GET['id'])){
				$album_id = $_GET['id'];
				$update = "UPDATE user_album SET newalcomment=0 WHERE album_id='$album_id' AND newalcomment>0";
				if(!mysql_query($update,$con)){
					die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
				}
			}
		}
		
		if(!empty($_POST['album_name']) && !empty($_POST['assoc_userid'])){ //创建相册
			$album_name = $_POST['album_name']; //相册名称
			$repeat = false; //不重复
			$select = "SELECT * FROM user_album WHERE assoc_userid='$user_id'";
			$result = $common->selectSql($select);
			while ($row = mysql_fetch_assoc($result)) {
				if($row['album_name'] == $album_name){ //如果相册名重复，则不创建
					$repeat = true;
					break;
				}
			}
			
			if (!$repeat) { //相册名不重复，才会创建
				$time = date('Y-m-d H:i:s'); //创建时间
				$album_description = $_POST['album_description']? $_POST['album_description'] : '暂无'; //相册描述
				$assoc_userid = $_POST['assoc_userid']; //相册拥有者id
				$insert = "INSERT INTO user_album (album_name,album_description,create_time,assoc_userid) VALUES
							('$album_name','$album_description','$time','$assoc_userid')";

				if(!mysql_query($insert,$con)){
					die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
				}
				
				//读取插入数据的id,即相册id
				$select = "SELECT album_id FROM user_album WHERE album_name='$album_name' AND assoc_userid='$assoc_userid' LIMIT 1";
				$result = $common->selectSql($select);
				$row = mysql_fetch_assoc($result);
				$album_id = $row['album_id'];
				//创建相册文件夹
				$album_path = dirname(__FILE__).'\data\albums';

				if(!is_dir($album_path)){
					mkdir($album_path);
				}

				$album_path = dirname(__FILE__).'\data\albums\album_'.$album_id.'\\';

				if(!is_dir($album_path)){
					mkdir($album_path);
				}
				
				$smarty->assign('successTip',$album_name); //创建成功提示
			}
		}
		
		if(!empty($_POST['newid']) && !empty($_POST['newname']) && !empty($_POST['newdesc'])){ //编辑相册
			$newid = $_POST['newid'];
			$newname = $_POST['newname'];
			$newdesc = $_POST['newdesc']? str_replace('描述:','',$_POST['newdesc']) : '暂无';
			$update = "UPDATE user_album SET album_name='$newname',album_description='$newdesc' WHERE album_id='$newid'";

			if(!mysql_query($update,$con)){
				die(json_encode(array('status'=>0,'msg'=>'修改失败！')));
			}

			echo json_encode(array('status'=>1,'msg'=>'修改成功！'));
			exit;
		}
		
		//设置相册封面
		if(!empty($_POST['set_albumId']) && !empty($_POST['frontCover'])){
			$album_id = $_POST['set_albumId']; //相册id
			$coverphoto = $_POST['frontCover']; //封面相片 
			//更新数据库
			$update = "UPDATE user_album SET album_cover='$coverphoto' WHERE album_id='$album_id'";

			if(!mysql_query($update,$con)){
				die(json_encode(array('status'=>0,'msg'=>'封面设置失败！')));
			}

			echo json_encode(array('status'=>1,'msg'=>'封面设置成功！'));
			exit;
		}
		
		//输出个人相册
		$select = "SELECT * FROM user_album WHERE assoc_userid='$user_id'";
		$result = $common->selectSql($select);
		$albums = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$albums[$i] = $row;
			$album_path = 'data/albums/album_'.$row['album_id'].'/75x75/'; //相册路径
			$p_arr = explode('|',$row['album_photos']); //相片数组
			$albums[$i]['photo_count'] = count($p_arr); //相片个数
			$albums[$i]['comment_num'] = $common->getCommentNum('alcomment_id','album_id','album_comment',$row['album_id']);
			if(!empty($p_arr[0])){
				$frontCover = $row['album_cover']? $album_path.$row['album_cover'] : $album_path.$p_arr[0];
				$is_exists = iconv('utf-8','gb2312',$frontCover);
				//如果没有缩略图，则使用原图
				if(!file_exists($is_exists)){
					$album_path = 'data/albums/album_'.$row['album_id'].'/'; //相册路径
					$frontCover = $row['album_cover']? $album_path.$row['album_cover'] : $album_path.$p_arr[0];
				}
				$albums[$i]['album_cover'] = $frontCover;
			}
			$i++;
		}
		
		$smarty->assign('albums',$albums);

		//提交相片评论
		if(!empty($_POST['phcomment']) && !empty($_POST['album_id']) && !empty($now_user)){
			$album_id = $_POST['album_id'];
			$alcomment_text = htmlspecialchars($_POST['phcomment'],ENT_QUOTES); //html标签转化为实体字符
			$commenter_os = $common->getos(); //评论者的操作系统
			$commenter_browser = $common->getbrowser(); //评论者的浏览器
			//处理回车键
			$alcomment_text = preg_replace("/\n/",'<br />',$alcomment_text);
			$select = "SELECT alcomment_id FROM album_comment WHERE user_name='$now_user' AND alcomment_text='$alcomment_text' AND album_id='$album_id'";
			$result = $common->selectSql($select);
			
			if(mysql_num_rows($result) === 0){ //防止重复提交
				$alcomment_time = date('Y-m-d H:i:s');
				$insert = "INSERT INTO album_comment (user_name,alcomment_text,alcomment_time,album_id,commenter_os,commenter_browser) VALUES 
				('$now_user','$alcomment_text','$alcomment_time','$album_id','$commenter_os','$commenter_browser')";
				if(!mysql_query($insert,$con)){
					die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
				}
				
				//更新未查看照片评论
				$update = "UPDATE user_album SET newalcomment=newalcomment+1 WHERE album_id='$album_id' LIMIT 1";
				if(!mysql_query($update,$con)){
					die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
				}
			}
		}
		
		//上传相片
		if(!empty($_POST['albumId']) && !empty($_POST['albumName'])){
			$album_id = $_POST['albumId']; //相册id
			$photos = ''; //保存照片名
			$i = 0;
			//创建相册文件夹
			$album_path = dirname(__FILE__).'\data\albums';
			if(!is_dir($album_path)){
				mkdir($album_path);
			}
			//原图片
			$album_path = dirname(__FILE__).'\data\albums\album_'.$album_id.'\\';
			//100x100规格的图片
			$album_path_100x100 = dirname(__FILE__).'\data\albums\album_'.$album_id.'\100x100\\';
			//75x75规格的图片
			$album_path_75x75 = dirname(__FILE__).'\data\albums\album_'.$album_id.'\75x75\\';
			//50x50规格的图片
			$album_path_50x50 = dirname(__FILE__).'\data\albums\album_'.$album_id.'\50x50\\';

			//创建源图片文件夹
			if(!is_dir($album_path)){
				mkdir($album_path);
			}
			//创建100x100规格的头像的文件夹
			if(!is_dir($album_path_100x100)){
				mkdir($album_path_100x100);
			}
			//创建75x75规格的头像的文件夹
			if(!is_dir($album_path_75x75)){
				mkdir($album_path_75x75);
			}
			//创建50x50规格的头像的文件夹
			if(!is_dir($album_path_50x50)){
				mkdir($album_path_50x50);
			}

			//添加新相片前，先取出原来相片
			$select = "SELECT album_photos FROM user_album WHERE album_id='$album_id' LIMIT 1";
			$result = $common->selectSql($select);
			$row = mysql_fetch_assoc($result);
			$photos = $row['album_photos'];

			if(!empty($_FILES)){
				foreach ($_FILES as $key => $value){
					if ((($_FILES[$key]['type'] == 'image/gif')
						|| ($_FILES[$key]['type'] == 'image/jpeg')
						|| ($_FILES[$key]['type'] == 'image/pjpeg')
						|| ($_FILES[$key]['type'] == 'image/bmp')
						|| ($_FILES[$key]['type'] == 'image/png'))
						&& ($_FILES[$key]['size'] < 2048000)){ //符合要求的相片才被允许上传
						
						if ($_FILES[$key]['error'] > 0){ //错误提示
							switch ($_FILES[$key]['error']) {
								case 1:{
									$err = "<script type='text/javascript'>
												alert('上传的图片大小超出2M限制，上传失败！');
												location.href = './album.php?user={$now_user}&act=look&id={$album_id}';
											</script>";
								}
								break;
								
								case 2:{
									$err = "<script type='text/javascript'>
												alert('上传的图片大小超出2M限制，上传失败！');
												location.href = './album.php?user={$now_user}&act=look&id={$album_id}';
											</script>";
								}
								break;
								
								case 3:{
									$err = "<script type='text/javascript'>
												alert('文件没有被完全上传，上传失败！');
												location.href = './album.php?user={$now_user}&act=look&id={$album_id}';
											</script>";
								}
								break;
								
								case 4:{
									$err = "<script type='text/javascript'>
												alert('没有文件被上传，上传失败！');
												location.href = './album.php?user={$now_user}&act=look&id={$album_id}';
											</script>";
								}
								break;
								
								case 6:{
									$err = "<script type='text/javascript'>
												alert('找不到临时文件目录，上传失败！');
												location.href = './album.php?user={$now_user}&act=look&id={$album_id}';
											</script>";
								}
								break;
								
								case 7:{
									$err = "<script type='text/javascript'>
												alert('文件写入失败，上传失败！');
												location.href = './album.php?user={$now_user}&act=look&id={$album_id}';
											</script>";
								}
								break;
							}
							echo $err;
						}
						
						if(!empty($_FILES[$key]['name'])){ //如果相片名不空
							//判断当前相册是否包含此相片
							$select = "SELECT album_photos FROM user_album WHERE album_id='$album_id' LIMIT 1";
							$result = $common->selectSql($select);
							$row = mysql_fetch_assoc($result); //根据相册id读取此相册中的所有相片
							$ph_arr = explode('|',$row['album_photos']); //所有相片名称
							if (in_array($_FILES[$key]['name'],$ph_arr)) { //如果文件名重复，则不做响应，并提示
								$err = "<script type='text/javascript'>
									alert('文件名重复，上传失败！');
									location.href = './album.php?user={$now_user}&act=look&id={$album_id}';
								</script>";
								echo $err;
							}
							else { //如果文件名没有重复的
								$photo = iconv('utf-8','gb2312',$_FILES[$key]['name']); //在上传文件时，转化utf-8格式为gb2312，解决中文乱码问题
								$move = move_uploaded_file($_FILES[$key]['tmp_name'],$album_path.$photo);//移动相片到指定文件夹
								//生成缩略图
								$common->imgcutout($album_path.$photo,$album_path.'\\100x100\\'.$photo,100,100);
								$common->imgcutout($album_path.$photo,$album_path.'\\75x75\\'.$photo,75,75);
								$common->imgcutout($album_path.$photo,$album_path.'\\50x50\\'.$photo,50,50);
								if(!$move){
									$err = "<script type='text/javascript'>
										alert('有图片移动出错，上传失败！');
										location.href = './album.php?user={$now_user}&act=look&id={$album_id}';
									</script>";
									echo $err;
								}
								else {
									$photo = iconv('gb2312','utf-8',$photo); //在写入数据库时，再转换回utf-8格式
									$photos .= $photo.'|'; //保存所有相片名
									//更新数据库
									$update = "UPDATE user_album SET album_photos='$photos' WHERE album_id='$album_id' LIMIT 1";
									if(!mysql_query($update,$con)){
										die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
									}
								}
								
							}
							
						}
						
					}
					else {
						$err = "<script type='text/javascript'>
							alert('有图片格式不正确，上传失败！');
							location.href = './album.php?user={$now_user}&act=look&id={$album_id}';
						</script>";
						echo $err;
					}
				}

			}
			else {
				$err = "<script type='text/javascript'>
							alert('请选择要上传的图片！');
							location.href = './album.php?user={$now_user}&act=look&id={$album_id}';
						</script>";
						echo $err;
			}
		}
		
		//查看相片
		if(!empty($_GET['act']) && $_GET['act']=='look' && !empty($_GET['id'])){
			$album_id = $_GET['id'];
			$select = "SELECT * FROM user_album WHERE album_id='$album_id' LIMIT 1";
			$result = $common->selectSql($select);
			$photos = $row = mysql_fetch_assoc($result); //根据相册id读取此相册信息
			$photoArray = array();
			$photoArray = explode('|',$photos['album_photos']); //获取此相册中所有相片名称
			$photo_path_name = array();

			foreach ($photoArray as $key => $value){ //为每张相片加上路径
				$album_path = './data/albums/album_'.$album_id.'/100x100/'; //相册路径
				if(!empty($value)){
					//如果没有缩略图，则使用原图
					$is_exists = $album_path.iconv('utf-8','gb2312',$value);
					if(!file_exists($is_exists)){
						$album_path = './data/albums/album_'.$album_id.'/'; //相册路径
					}
					$photo_path_name[$key]['path'] = $album_path.$value; //完整路径
					$photo_path_name[$key]['origin_path'] = './data/albums/album_'.$album_id.'/'.$value; //原图路径
					$photo_path_name[$key]['name'] = $value; //相片名
				}
			}

			//显示照片评论
			$select ="SELECT * FROM album_comment WHERE album_id='$album_id' ORDER BY alcomment_time";
			$result = $common->selectSql($select);
			$i = 0;
			$alcomment = array();
			$alcomment_replys = array(); //保存相片评论对应回复
			$nowuser_arts = array(); //保存评论者最新三篇文章
			
			while ($row = mysql_fetch_assoc($result)) {
				$alcomment[$i] = $row; //评论信息
				$alcomment[$i]['user_photo'] = $common->getphoto($row['user_name'],'50x50'); //评论者头像
				$userinfo = $common->getUsersValue($row['user_name']); //用户新粉丝数，粉丝，关注，签名
				$alcomment[$i]['signature'] = $userinfo['signature']; //评论者签名
				$alcomment_replys[$row['alcomment_id']] = $common->getreplys('alcomment_replys',$row['alcomment_id']); //该评论对应回复
				$i++; 
			}
			
			$smarty->assign('album_id',$album_id); //相册id
			$smarty->assign('lookKey',true);
			$smarty->assign('photos',$photos); //照片数组
			$smarty->assign('alcomment',$alcomment); //照片评论数组
			$smarty->assign('alcomment_replys',$alcomment_replys); //评论对应回复
			$smarty->assign('nowuser_arts',$nowuser_arts); //评论者最新三篇文章
			$smarty->assign('photo_path_name',$photo_path_name); //相册中相片
		}
		
		//删除相片
		if(!empty($_POST['photo_names']) && !empty($_POST['del_albumId'])){
			$album_id = $_POST['del_albumId'];
			$names = $_POST['photo_names'];
			$delarr = explode(',',$names); //要删除相片
			$delcover = false; //封面是否要删除
			//读取出当前所有相片
			$select = "SELECT album_photos,album_cover FROM user_album WHERE album_id='$album_id' LIMIT 1";
			$result = $common->selectSql($select);
			$row = mysql_fetch_assoc($result); //根据相册id读取此相册信息
			$totalarr = explode('|',$row['album_photos']); //取出当前所有相片
			$album_cover = $row['album_cover']; //取出相册封面
			
			if(in_array($album_cover,$delarr)){ //如果相册封面在删除中
				$delcover = true;
			}
			
			//取得删除后的照片
			$newarr = array_diff($totalarr,$delarr);
			$newstr = '';

			foreach ($newarr as $value){
				if($value){ //过滤值为空情况
					$newstr .= $value.'|';
				}
			}
			
			//更新数据库
			if($delcover){ //如果相册封面在删除队列中
				$update = "UPDATE user_album SET album_photos='$newstr',album_cover='' WHERE album_id='$album_id'";
			}
			else{
				$update = "UPDATE user_album SET album_photos='$newstr' WHERE album_id='$album_id'";
			}

			if(!mysql_query($update,$con)){
				die(json_encode(array('status'=>0,'msg'=>'照片删除失败！')));
			}
			
			//最后到文件夹下删除相片
			$album_path = dirname(__FILE__).'\data\albums\album_'.$album_id.'\\'; //相册文件夹路径
			foreach ($delarr as $value){ //到具体文件夹下删除相片
				if(!empty($value)){ //过滤空值
					$value = iconv('utf-8','gb2312',$value); //处理中文名字问题
					$aph = $album_path.$value;
					$aph_100x100 = $album_path.'100x100\\'.$value;
					$aph_75x75 = $album_path.'75x75\\'.$value;
					$aph_50x50 = $album_path.'50x50\\'.$value;
					if(file_exists($aph)){
						unlink($aph);
					}
					if(file_exists($aph_100x100)){
						unlink($aph_100x100);
					}
					if(file_exists($aph_75x75)){
						unlink($aph_75x75);
					}
					if(file_exists($aph_50x50)){
						unlink($aph_50x50);
					}
				}
			}

			echo json_encode(array('status'=>1,'msg'=>'照片删除成功！'));
			exit;
		}
		
		//移动照片
		if(!empty($_POST['fromAlbumId']) && !empty($_POST['toAlbumId']) && !empty($_POST['photos'])){
			$fromAlbum_id = $_POST['fromAlbumId'];
			$toAlbum_id = $_POST['toAlbumId'];
			$names = $_POST['photos'];
			$delarr = explode(',',$names); //要删除相片
			$delcover = false; //封面是否要删除
			//处理要移动到的相册
			//读取出当前所有相片
			$select = "SELECT album_photos,album_cover FROM user_album WHERE album_id='$toAlbum_id' LIMIT 1";
			$result = $common->selectSql($select);
			$row = mysql_fetch_assoc($result); //根据相册id读取此相册信息
			$addphotos = $row['album_photos']; //原相册中相片数据
			foreach ($delarr as $value){ //移动相片连接到目的地
				$addphotos .= $value.'|';
			}
			$update = "UPDATE user_album SET album_photos='$addphotos' WHERE album_id='$toAlbum_id'";

			if(!mysql_query($update,$con)){
				die(json_encode(array('status'=>0,'msg'=>'照片移动失败!')));
			}

			$fromAlbum_path = './data/albums/album_'.$fromAlbum_id.'/'; //相册文件夹路径
			$toAlbum_path = './data/albums/album_'.$toAlbum_id.'/'; //相册文件夹路径
			$toAlbum_path_100x100 = dirname(__FILE__).'\data\albums\album_'.$toAlbum_id.'\100x100\\';
			$toAlbum_path_75x75 = dirname(__FILE__).'\data\albums\album_'.$toAlbum_id.'\75x75\\';
			$toAlbum_path_50x50 = dirname(__FILE__).'\data\albums\album_'.$toAlbum_id.'\50x50\\';

			foreach ($delarr as $value){ //移动实体相片
				$value = iconv('utf-8','gb2312',$value); //处理中文名字问题
				copy($fromAlbum_path.$value,$toAlbum_path.$value);
				//创建100x100规格的头像的文件夹
				if(!is_dir($toAlbum_path_100x100)){
					mkdir($toAlbum_path_100x100);
				}
				copy($fromAlbum_path.'100x100/'.$value,$toAlbum_path.'100x100/'.$value);
				//创建75x75规格的头像的文件夹
				if(!is_dir($toAlbum_path_75x75)){
					mkdir($toAlbum_path_75x75);
				}
				copy($fromAlbum_path.'75x75/'.$value,$toAlbum_path.'75x75/'.$value);
				//创建50x50规格的头像的文件夹
				if(!is_dir($toAlbum_path_50x50)){
					mkdir($toAlbum_path_50x50);
				}
				copy($fromAlbum_path.'50x50/'.$value,$toAlbum_path.'50x50/'.$value);
			}
			
			//处理原相册
			//读取出当前所有相片
			$select = "SELECT album_photos,album_cover FROM user_album WHERE album_id='$fromAlbum_id' LIMIT 1";
			$result = $common->selectSql($select);
			$row = mysql_fetch_assoc($result); //根据相册id读取此相册信息
			$totalarr = explode('|',$row['album_photos']); //取出当前所有相片
			$album_cover = $row['album_cover']; //取出相册封面
			
			if(in_array($album_cover,$delarr)){ //如果相册封面在删除中
				$delcover = true;
			}
			
			//取得删除后的照片
			$newarr = array_diff($totalarr,$delarr);
			$newstr = '';

			foreach ($newarr as $value){
				if($value){ //过滤值为空情况
					$newstr .= $value.'|';
				}
			}
			
			//更新数据库
			if($delcover){ //如果相册封面在删除队列中
				$update = "UPDATE user_album SET album_photos='$newstr',album_cover='' WHERE album_id='$fromAlbum_id'";
			}
			else{
				$update = "UPDATE user_album SET album_photos='$newstr' WHERE album_id='$fromAlbum_id'";
			}

			if(!mysql_query($update,$con)){
				die(json_encode(array('status'=>0,'msg'=>'照片移动失败！')));
			}
			
			//最后到文件夹下删除相片
			$album_path = dirname(__FILE__).'\data\albums\album_'.$fromAlbum_id.'\\'; //相册文件夹路径
			foreach ($delarr as $value){ //到具体文件夹下删除相片
				if(!empty($value)){ //过滤空值
					$value = iconv('utf-8','gb2312',$value); //处理中文名字问题
					$aph = $album_path.$value;
					$aph_100x100 = $album_path.'100x100\\'.$value;
					$aph_75x75 = $album_path.'75x75\\'.$value;
					$aph_50x50 = $album_path.'50x50\\'.$value;
					if(file_exists($aph)){
						unlink($aph);
					}
					if(file_exists($aph_100x100)){
						unlink($aph_100x100);
					}
					if(file_exists($aph_75x75)){
						unlink($aph_75x75);
					}
					if(file_exists($aph_50x50)){
						unlink($aph_50x50);
					}
				}
			}

			echo json_encode(array('status'=>1,'msg'=>'照片移动成功!'));
			exit;
		}
		

		if (!empty($now_user)) {
			$user_id = $common->get_user_id($now_user);
			//文章未读评论提示
			$smarty->assign('newartcomment',$common->commentMsg($now_user));
			//未读回复
			$smarty->assign('newreply',$common->replyMsg($now_user));
			//未读私信，做出提示
			$smarty->assign('newsms',$common->newsms($now_user));
			//读取新空间留言数，做出提示
			$smarty->assign('msgnum',$common->msgnum($user_id));
			//读取用户相册评论数，做出提示
			$smarty->assign('newalcomment',$common->album_comments_num($user_id));
			//新粉丝提示
			$smarty->assign('newfans',$common->newfans($now_user));
		}
		
		//当前用户的粉丝
		$smarty->assign('fansArr',$common->tafans($user));
		//注册那次登录
		$smarty->assign('firstLogin',empty($_SESSION['firstLogin'])? '' : $_SESSION['firstLogin']);
		//标识相册页面
		$smarty->assign('albumPage',true);
		$smarty->display('album.tpl');
	}
	else{
		die(json_encode(array('status'=>0,'msg'=>'该用户不存在，Access Error.')));
	}
}