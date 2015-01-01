<?php
header('Content-type:image/png');
session_start();//打开session

$check_code = '';//初始化验证码，读取这次之前，把上一次的清空
srand((double)microtime()*1000000);//随机种子

$string = '0,1,2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,I,G,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z';
$arr = explode(',',$string);//把字符串以摸个间隔转换为数组

for($i=0; $i<4; $i++){//4位验证码
	$randnum = rand(0,35);
	$check_code .= $arr[$randnum];//这个字符串用于保存在session中
	$authnum .= $arr[$randnum].' ';//这个字符串用于显示在图片上
}

$image = imagecreate(90,27);//创建图片框架
imagecolorallocate($image,0,0,255);//切记，第一次设定的颜色，默认为背景色

/**
/* imagestring(int im,ing font,int x,int y,string str,int col)
/* im 背景图片
/* 如果font为1-5,则使用内置字体
/* x,y 距离图片左侧和顶部的距离
/* str 图片里的文字(即验证码)
/* col 为字体颜色
*/

$blue = imagecolorallocate($image,255,255,255);//设置字体颜色为白色
imagestring($image,5,12,6,$authnum,$blue);

//加入干扰素
/*
for($i=0; $i<500; $i++){
	
	//随机干扰素颜色
	$randcolor = imagecolorallocate($image,rand(0,255),rand(0,255),rand(0,255));
	imagesetpixel($image,rand()%90,rand()%40,$randcolor);//在图片随机位置输出干扰素
	
}
*/

//输出图像
imagepng($image);
imagedestroy($image);

//把验证码保存到session中
$_SESSION['check_code'] = $check_code;

?>