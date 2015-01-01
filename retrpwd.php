<?php
require('./connect.php');
require('./libs/Smarty.class.php');

$smarty = new Smarty();
$smarty->assign('is_retrpwd',true);
$smarty->assign('isUser',$_SESSION['user_name']);
$smarty->display('retrpwd.tpl');

?>