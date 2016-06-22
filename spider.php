<?php
//保存昨天、前天数据，今天检测，如果前天存在，合并到昨天。
//如果今天不存在，创建今天。
header('Access-Control-Allow-Origin: *');
error_reporting(0);
set_time_limit(0);
date_default_timezone_set('PRC');
function spider($path = "./") {
	$today = $path . "." . date("Y-m-d") . ".txt";
	$yesterday = $path . "." . date("Y-m-d", strtotime("-1 day")) . ".txt";
	$anteayer = $path . "." . date("Y-m-d", strtotime("-2 day")) . ".txt";
	if (file_exists($anteayer)) {
		$log = file_get_contents($anteayer) . "***\n" . @file_get_contents($yesterday);
		file_put_contents($yesterday, $log);
		unlink($anteayer);
	}
	if (!file_exists($today)) {
		file_put_contents($today, date("Y-m-d") . "\n");
	}
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if (strpos($useragent, 'googlebot') !== false) {$bot = 'Google';} elseif (strpos($useragent, 'mediapartners-google') !== false) {$bot = 'Google Adsense';} elseif (strpos($useragent, 'baiduspider') !== false) {$bot = 'Baidu';} elseif (strpos($useragent, 'sogou spider') !== false) {$bot = 'Sogou';} elseif (strpos($useragent, 'sogou web') !== false) {$bot = 'Sogou web';} elseif (strpos($useragent, 'sosospider') !== false) {$bot = 'SOSO';} elseif (strpos($useragent, '360spider') !== false) {$bot = '360Spider';} elseif (strpos($useragent, 'yahoo') !== false) {$bot = 'Yahoo';} elseif (strpos($useragent, 'msn') !== false) {$bot = 'MSN';} elseif (strpos($useragent, 'msnbot') !== false) {$bot = 'msnbot';} elseif (strpos($useragent, 'sohu') !== false) {$bot = 'Sohu';} elseif (strpos($useragent, 'yodaoBot') !== false) {$bot = 'Yodao';} elseif (strpos($useragent, 'twiceler') !== false) {$bot = 'Twiceler';} elseif (strpos($useragent, 'ia_archiver') !== false) {$bot = 'Alexa_';} elseif (strpos($useragent, 'iaarchiver') !== false) {$bot = 'Alexa';} elseif (strpos($useragent, 'slurp') !== false) {$bot = '雅虎';} elseif (strpos($useragent, 'bing') !== false) {$bot = 'bing';} elseif (strpos($useragent, 'bot') !== false) {$bot = '其它蜘蛛' . $_SERVER['HTTP_USER_AGENT'];}
	if (isset($bot)) {
		$fp = @fopen($today, 'a');
		fwrite($fp, date('Y-m-d H:i:s') . "|" . $_SERVER["REMOTE_ADDR"] . "|" . $bot . "|" . 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"] . "\n");
		fclose($fp);
	}
	if (isset($_GET['show'])) {
		if ($_GET['show'] == "log") {
			if (file_exists($yesterday)) {
				echo urlencode(base64_encode(file_get_contents($yesterday)));
				unlink($yesterday);
			} else {
				die("finished");
			}
		} else {
			echo urlencode(base64_encode(file_get_contents($today)));
		}
	}
}
spider();
?>
