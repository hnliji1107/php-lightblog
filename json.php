<?php
require('./connect.php');
require('./class.common.php');
$common = new Common();

//用户名片展示
if(!empty($_POST['user']) && !empty($_POST['act']) && $_POST['act'] == 'card'){
	$user = $_POST['user'];
	$userid = $common->get_user_id($user);
	$arr = array(
		'status' => 1,
		'threeArts' => $common->getThreeArts($user)
	);
	echo json_encode($arr);
}

//根据具体分类切换文章列表
if(!empty($_POST['act']) && $_POST['act'] == 'toggle'){
	//根据时间排序文章
	if(!empty($_POST['type']) && $_POST['type'] == 'tm'){
		$select = "SELECT topics_id,user_name,article_title,article_content,article_time FROM articles ORDER BY article_time DESC LIMIT 9";
		//展开更多
		if(!empty($_POST['oper']) && $_POST['oper'] == 'more' && !empty($_POST['nowcount'])) {
			$nowcount = $_POST['nowcount'];
			$select = "SELECT topics_id,user_name,article_title,article_content,article_time FROM articles ORDER BY article_time DESC LIMIT $nowcount, 9";
		}
	}
	//根据访问数排序文章
	if(!empty($_POST['type']) && $_POST['type'] == 'vs'){
		$select = "SELECT topics_id,user_name,article_title,article_content,article_time FROM articles ORDER BY visit_num DESC LIMIT 9";
		//展开更多
		if(!empty($_POST['oper']) && $_POST['oper'] == 'more' && !empty($_POST['nowcount'])) {
			$nowcount = $_POST['nowcount'];
			$select = "SELECT topics_id,user_name,article_title,article_content,article_time FROM articles ORDER BY visit_num DESC LIMIT $nowcount, 9";
		}
	}
	//根据评论数排序文章
	if(!empty($_POST['type']) && $_POST['type'] == 'cm'){
		$select = "SELECT *, COUNT(*) AS rlynum FROM (SELECT art.topics_id, art.user_name, art.article_title, art.article_content, art.article_time, cts.reply_id FROM articles AS art, comments AS cts WHERE art.topics_id=cts.reply_id) AS T GROUP BY reply_id ORDER BY rlynum DESC LIMIT 9";
		//展开更多
		if(!empty($_POST['oper']) && $_POST['oper'] == 'more' && !empty($_POST['nowcount'])) {
			$nowcount = $_POST['nowcount'];
			$select = "SELECT *, COUNT(*) AS rlynum FROM (SELECT art.topics_id, art.user_name, art.article_title, art.article_content, art.article_time, cts.reply_id FROM articles AS art, comments AS cts WHERE art.topics_id=cts.reply_id) AS T GROUP BY reply_id ORDER BY rlynum DESC LIMIT $nowcount, 9";
		}
	}
	echo json_encode(array('status'=>1,'arts'=>$common->getArtsBy($select)));
}

//滚动加载更多分类文章
if (!empty($_POST['category']) && !empty($_POST['range'])) {
	$data = $common->getCategoryList($_POST['category'], $_POST['range']);
	$len = count($data);
	$msg = '';
	foreach($data as $item) {
		$msg .= "<li>
					<div class=\"art_info\">
						<div class=\"arttitle\"><a href=\"separticle.php?artid={$item['topics_id']}\" target=\"_blank\">{$item['article_title']}</a></div>
						<div class=\"theArtInfo\">{$item['article_time']} / 阅读({$item['visit_num']})，评论({$item['commentnum']})，收藏({$item['collectnum']}) / 所属栏目：{$item['article_type']} / 作者：{$item['user_name']}</div>
						<div class=\"description\">{$item['article_content']}</div>
						<div class=\"readmore\"><a target=\"_blank\" href=\"separticle.php?artid={$item['topics_id']}\">Read More »</a></div>
					</div>
				</li>";
	}
	echo json_encode(array('status'=>1, 'msg'=>$msg));
}

?>