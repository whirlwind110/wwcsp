<?php
header("content-type:text/html;charset=utf-8");
$file = "./txt/01.txt";
$ini = ini_set("soap.wsdl_cache_enabled", "0");

//调用 奶盘(naipan.com) WebService的URL
$wsdl = 'http://www.naipan.com/NaipanPort?WSDL';

//通过您的PHP文件格式选择，实现中文乱码处理
//$content = iconv('gbk','utf-8',"文章内容");//格式为ANSI
//$content = "文章内容";//格式为UTF-8
$content = file_get_contents($file);
//参数值【文章内容  奶盘网注册用户名test@163.com(未注册用默认) 注册序列号,免费用户用ICQl3kdebh7zns97XVT9dLDBASR7pBrM2AAKbI7HpMw=】
$param = array(
	"arg0" => $content,
	"arg1" => "test@163.com",
	"arg2" => "ICQl3kdebh7zns97XVT9dLDBASR7pBrM2AAKbI7HpMw=",
);

$client = new SoapClient($wsdl);
$result = $client->getContent($param, 1);

file_put_contents("txt/01_ok.txt", $result->return)

?>