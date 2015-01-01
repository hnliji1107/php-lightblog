<?php
require('./globalheader.php');

//发表文章
if(!empty($_POST['arttitle']) && !empty($_POST['artcontent']) && !empty($_POST['tid'])){
	$arttitle = $_POST['arttitle'];
	$artcontent = htmlspecialchars($_POST['artcontent'],ENT_QUOTES);
	$arttype = $_POST['tid'];
	$select = "SELECT topics_id FROM articles WHERE user_name='$now_user' AND article_title='$arttitle' AND
				article_content='$artcontent' AND article_type='$arttype'";
	if(!$result = mysql_query($select,$con)){
		die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
	}
	
	if(mysql_num_rows($result) === 0){ //没有重复
		$arttime = date("Y-m-d H:i:s");
		$insert = "INSERT INTO articles (user_name,article_title,article_content,article_time,article_type) VALUES
					('$now_user','$arttitle','$artcontent','$arttime','$arttype')";
		if(!mysql_query($insert,$con)){
			die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
		}
		
		//更新文章附件
		$tmpid = mysql_insert_id();
		$smarty->assign('artid',$tmpid);
		
		$update = "UPDATE article_attachment SET assoc_artid='$tmpid' WHERE user_name='$now_user' AND assoc_artid=0";
		if (!mysql_query($update,$con)) {
			die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
		}
	}
	else{
		$row = mysql_fetch_assoc($result);
		$smarty->assign('artid',$row['topics_id']);
	}
	
	$smarty->assign('wrsuccess',true);
	$smarty->assign('isUser',$_SESSION['user_name']);
	$smarty->display('wrup.tpl');
}

//修改文章
if(!empty($_POST['arttitle2']) && !empty($_POST['artcontent2']) && !empty($_POST['tid2']) && !empty($_POST['artid'])){
	$new_title = $_POST['arttitle2'];
	$new_content = htmlspecialchars($_POST['artcontent2'],ENT_QUOTES);
	$new_type = $_POST['tid2'];
	$artid = $_POST['artid'];
	$select = "SELECT topics_id FROM articles WHERE user_name='$now_user' AND article_title='$new_title' AND
				article_content='$new_content' AND article_type='$new_type'";
	if(!$result = mysql_query($select,$con)){
		die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
	}
	
	if(mysql_num_rows($result) === 0){ //没有重复
		$new_time = date("Y-m-d H:i:s");
		$update = "UPDATE articles SET article_title='$new_title',article_content='$new_content',article_type='$new_type',
			article_time='$new_time',article_modify='1' WHERE topics_id='$artid'";
	
		if(!mysql_query($update,$con)){
			die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
		}
	}
	else{
		$row = mysql_fetch_assoc($result);
		$smarty->assign('artid',$row['topics_id']);
	}
	
	//更新文章附件
	$update = "UPDATE article_attachment SET assoc_artid='$artid' WHERE user_name='$now_user' AND assoc_artid=0";
	if (!mysql_query($update,$con)) {
		die(json_encode(array('status'=>0,'msg'=>'Access Error.')));
	}
	
	$smarty->assign('upsuccess',true);
	$smarty->assign('artid',$artid);
	$smarty->assign('isUser',$_SESSION['user_name']);
	$smarty->display('wrup.tpl');
}

mysql_close($con);

?>