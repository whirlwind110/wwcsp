<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
	const RANDNUM = 100; //随机抽取数，在日后连接池、站点很多，适当改大
	const NUM = 100; //索引页面抽取最新数，根据页面留得mykeylink设置。
	const INDEXNUM = 6; //首页索引，每个区域链接数
	public function _empty($name) {
		$this->show($name . "功能正在开发。");
	}
	public function index() {
		$this->assign('time', date("H:i:s"));
		$this->display();
	}
	public function pool() {
		$this->display();
	}
	public function showshell() {
		$shell = M('shell');
		$count = $shell->order("id desc")->limit(10)->count();
		$Page = new \Think\Page($count, 10);
		$show = $Page->show();
		$list = $shell->order("id desc")->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('shelllist', $list);
		$this->assign('page', $show); // 赋值分页输出
		$this->assign('publictitle', $publictitle);
		$this->assign('corekeywords', implode(",", $corekeywords));
		$this->assign('longkeywords', implode(",", $longkeywords));
		$this->display();
	}
	public function addshell() {
		$shell = M('shell');
		if ($conf = $shell->create()) {
			//给长关键词剔除两边空格
			$conf['longkey'] = implode(",", $this->TrimArray(explode(",", $conf['longkey'])));
			$shell->create($conf);
			if (empty($shell->url) or $shell->where("url ='%s'", $shell->url)->find()) {
				$res['status'] = 0;
				$res['con'] = "错误，URL重复或为空。";
				$this->ajaxReturn($res);
			} elseif ($shell->add()) {
				$res['status'] = 1;
				$res['con'] = "添加成功";
				$this->ajaxReturn($res);
			} else {
				$res['status'] = 0;
				$res['con'] = "未知错误";
				$this->ajaxReturn($res);
			}
		}
		$temdir = "./Public/template/";
		if ($dh = opendir($temdir)) {
			while (($file = readdir($dh)) !== false) {
				if (($file != ".") && ($file != "..") && is_dir($temdir . $file)) {
					$template[] = $temdir . $file . "/";
				}
			}
			closedir($dh);
		}
		$this->assign("template", $template);
		$dfdir = "./Public/datafile/";
		if ($dh = opendir($dfdir)) {
			while (($file = readdir($dh)) !== false) {
				if (($file != ".") && ($file != "..") && (strtolower(substr(strrchr($file, "."), 1)) == "txt")) {
					$datafile[] = $dfdir . $file;
				}
			}
			closedir($dh);
		}
		$this->assign("datafile", $datafile);
		$this->display();
	}
	public function editshell() {
		$shell = M('shell');
		if ($conf = $shell->create()) {
			//给长关键词剔除两边空格
			$conf['longkey'] = implode(",", $this->TrimArray(explode(",", $conf['longkey'])));
			$shell->create($conf);
			if ($shell->save()) {
				$res['status'] = 1;
				$res['con'] = "更新成功";
				$this->ajaxReturn($res);
			} else {
				$res['status'] = 0;
				$res['con'] = "未知错误";
				$this->ajaxReturn($res);
			}

		}
		$data = $shell->where("id='%s'", I("get.sid"))->find();
		$this->assign("id", $data['id']);
		$this->assign("url", $data['url']);
		$this->assign("pw", $data['pw']);
		$this->assign("suffix", $data['suffix']);
		$this->assign("mytemplate", $data['template']);
		$this->assign("mydatafile", $data['datafile']);
		$this->assign("title", $data['title']);
		$this->assign("keywords", $data['keywords']);
		$this->assign("description", $data['description']);
		$this->assign("longkey", $data['longkey']);
		$temdir = "./Public/template/";
		if ($dh = opendir($temdir)) {
			while (($file = readdir($dh)) !== false) {
				if (($file != ".") && ($file != "..") && is_dir($temdir . $file)) {
					$template[] = $temdir . $file . "/";
				}
			}
			closedir($dh);
		}
		$this->assign("template", $template);
		$dfdir = "./Public/datafile/";
		if ($dh = opendir($dfdir)) {
			while (($file = readdir($dh)) !== false) {
				if (($file != ".") && ($file != "..") && (strtolower(substr(strrchr($file, "."), 1)) == "txt")) {
					$datafile[] = $dfdir . $file;
				}
			}
			closedir($dh);
		}
		$this->assign("datafile", $datafile);
		$this->display();
	}
	public function delshell() {
		$shell = M('shell');
		if ($shell->create()) {
			if ($shell->delete()) {
				$res['status'] = 1;
				$res['con'] = "删除成功";
				$this->ajaxReturn($res);
			} else {
				$res['status'] = 0;
				$res['con'] = "删除失败";
				$this->ajaxReturn($res);
			}
		} else {
			$res['status'] = 0;
			$res['con'] = "接收失败";
			$this->ajaxReturn($res);
		}
	}
	public function spidercount() {
		$sid = I("get.id");
		switch (I("get.method")) {
		case 'history':
			$history = M("spider_history");
			$data = base64_decode(I("post.data"));
			$data = explode("***", $data);
			foreach ($data as $value1) {
				$value1 = explode("\n", $value1);
				array_shift($value1);
				foreach ($value1 as $value2) {
					if (!empty($value2)) {
						$value2 = explode("|", $value2);
						$arr['sid'] = $sid;
						$arr['date'] = $value2[0];
						$arr['ip'] = $value2[1];
						$arr['name'] = $value2[2];
						$arr['page'] = $value2[3];
						if (empty($history->where($arr)->find())) {
							$history->add($arr);
							$now = M("spider_now");
							$now->where($arr)->delete();
						}

					}
				}
			}
			break;
		case 'now':
			$data = base64_decode(I("post.data"));
			$data = explode("\n", $data);
			array_shift($data);
			foreach ($data as $value) {
				if (!empty($value)) {
					$value = explode("|", $value);
					$arr['sid'] = $sid;
					$arr['date'] = $value[0];
					$arr['ip'] = $value[1];
					$arr['name'] = $value[2];
					$arr['page'] = $value[3];
					$now = M("spider_now");
					if (empty($now->where($arr)->find())) {
						$now->add($arr);
					}
				}
			}
			break;
		case 'count30':
			for ($i = 30, $p = 0; $i >= 1; $i--, $p++) {
				$history = M("spider_history");
				$date = date("Y-m-d", strtotime("-{$i} day"));
				$m = $i - 1;
				$date2 = date("Y-m-d", strtotime("-{$m} day"));
				$count[$p]['date'] = date("m-d", strtotime("-{$i} day"));
				$count[$p]['count'] = count($history->where("date >='%s' and date<'%s'", $date, $date2)->select());
			}
			$this->ajaxReturn($count);
			break;
		default:
			$history = M("spider_history");
			$now = M("spider_now");
			$today = date("Y-m-d");
			$day30 = date("Y-m-d", strtotime("-30 day"));
			$day7 = date("Y-m-d", strtotime("-7 day"));
			$spider_7day = $history->where("date >='%s' < '%s'", $day7, $today)->select();
			$spider_30day = $history->where("date >='%s' < '%s'", $day30, $today)->select();
			$spider_today = $now->where("date >='%s'", $today)->select();
			$this->assign('day7', count($spider_7day));
			$this->assign('day30', count($spider_30day));
			$this->assign('today', count($spider_today));
			$this->display();
			break;
		}

	}
	public function getinfo() {
		$shell = M("shell");
		$shell = $shell->where("id='%s'", I("get.id"))->getField("id,url,pw");
		$id = array_keys($shell)[0];
		$arr['url'] = $shell[$id]['url'];
		$arr['pw'] = $shell[$id]['pw'];
		$this->ajaxReturn($arr);
	}
	public function links() {
		if (IS_GET) {
			$links = M('links');
			$data['url'] = I("get.url");
			$data['sid'] = I("get.sid");
			$data['class'] = I("get.class");
			$data['dir'] = I("get.dir");
			$data['title'] = I("get.title");
			$links->add($data);
		}
	}
	public function build() {
		switch (I("get.method")) {
		case 'htaccess':
			//返回代码
			$this->ajaxReturn($this->buildhtaccess());
			break;
		case 'style':
			//返回代码
			$this->ajaxReturn($this->buildstyle());
			break;
		case 'dir':
			//返回url、pw、dir(array)
			$this->ajaxReturn($this->builddir(I("get.id")));
			break;
		case 'artcle':
			//返回代码
			$this->ajaxReturn($this->buildartcle(I("get.id"), I("get.dir")));
			break;
		case 'keyindex':
			//返回代码
			$this->ajaxReturn($this->buildkeyindex(I("get.id"), I("get.dir")));
			break;
		case 'index':
			//返回代码
			$this->ajaxReturn($this->buildindex(I("get.id")));
			break;
		case 'spider':
			//返回代码
			$this->ajaxReturn($this->ubcode(file_get_contents("./spider.php")));
			break;
		default:
			$this->ajaxReturn("error");
			break;
		}
	}
	private function builddir($id) {
		$shell = M("shell");
		$shell = $shell->where("id='%s'", $id)->getField("url,pw,keywords,suffix");
		$key = array_keys($shell)[0];
		$arr['url'] = $shell[$key]['url'];
		$arr['pw'] = $shell[$key]['pw'];
		$arr['suffix'] = $shell[$key]['suffix'];
		$arr['dir'] = explode(",", $shell[$key]['keywords']);
		return $arr;
	}
	private function buildartcle($id, $dir) {
		$mycorekey = $dir;
		$shell = M("shell");
		$shell = $shell->where("id='%s'", $id)->getField("suffix,keywords,description,datafile,title,longkey,template");
		$key = array_keys($shell)[0];
		$suffix = $shell[$key]['suffix'];
		$keywords = explode(",", $shell[$key]['keywords']);
		$description = $shell[$key]['description'];
		$datafile = $shell[$key]['datafile'];
		$title = $shell[$key]['title'];
		$template = $shell[$key]['template'];
		$longkey = explode(",", $shell[$key]['longkey']);
		//随机抽取关键词
		$mykey = $longkey[mt_rand(0, count($longkey) - 1)];
		//读取模板
		$template = file_get_contents($template . "/artcle.html");
		//设置样式表地址
		$style = "../style.css";
		$template = str_ireplace("{style}", $style, $template);
		//设置页面标题
		$template = str_ireplace("{title}", $title, $template);
		//设置页面关键词
		$template = str_ireplace("{keywords}", implode(",", $keywords), $template);
		//设置页面描述
		$template = str_ireplace("{description}", $description, $template);
		//设置日期
		$date = date("Ymd");
		$template = str_ireplace("{date}", $date, $template);
		//替换顶部导航 6个核心
		foreach ($keywords as $value) {
			$navli .= "<li><a href=\"../{$value}/index.{$suffix}\" title=\"{$value}\" target=\"_blank\">{$value}</a></li>";
		}
		$template = str_ireplace("{navli}", $navli, $template);
		//替换当前抽取的关键词
		$template = str_ireplace("{mykey}", $mykey, $template);
		//初始化链接表
		$links = M("links");
		//相关链接 取最新两百个本shell的链接，随机抽取.
		//替换当前站相关链接
		if ($linkarr = $links->where("sid='%s' and class=4", $id)->order("id DESC")->limit(RANDNUM)->select()) {
			while (stripos($template, "{mylink}") !== false) {
				$thislink = $linkarr[mt_rand(0, count($linkarr) - 1)];
				$mylink = "<a href=\"{$thislink['url']}\" title=\"{$thislink['title']}\">{$thislink['title']}</a>";
				$template = preg_replace("/{mylink}/", $mylink, $template, 1);
			}
		} else {
			$template = str_replace("{mylink}", "not found", $template);
		}
		//非本站文章链接 取最新两百个本shell的链接，随机抽取.
		//替换站外相关链接
		if ($linkarr = $links->where("sid != '%s' and class=4", $id)->order("id DESC")->limit(RANDNUM)->select()) {
			while (stripos($template, "{syslink}") !== false) {
				$thislink = $linkarr[mt_rand(0, count($linkarr) - 1)];
				$syslink = "<a href=\"{$thislink['url']}\" title=\"{$thislink['title']}\">{$thislink['title']}</a>";
				$template = preg_replace("/{syslink}/", $syslink, $template, 1);
			}
		} else {
			$template = str_replace("{syslink}", "not found", $template);
		}

		//创建内容
		//打开数据文件
		$contents = file_get_contents($datafile);
		$contents = explode("\n", $contents);
		//随机10-20条
		for ($i = 0; $i < mt_rand(10, 20); $i++) {
			//随机抽取一行
			$pcontents = $contents[mt_rand(0, count($contents) - 1)];
			//1/2概率在本行插入关键词
			if (mt_rand(0, 1)) {
				//分割本行为一个字一个字
				$pcontents = $this->str_split_unicode($pcontents);
				//随机选择当前词或核心词
				//随机在本行某位置插入关键词
				if (mt_rand(0, 1)) {
					//核心词
					$pcontents[mt_rand(0, count($pcontents) - 1)] .= "<b><em>{$keywords[mt_rand(0, count($keywords) - 1)]}</em></b>";
				} else {
					//当前词
					$pcontents[mt_rand(0, count($pcontents) - 1)] .= "<b><em>{$mykey}</em></b>";
				}
				$pcontents = implode($pcontents);
			}
			$section .= "<p>" . $pcontents . "</p>";
		}
		//替换段落内容
		$template = str_ireplace("{section}", $section, $template);
		//替换当前选中关键词
		$template = str_ireplace("{mycorekey}", $mycorekey, $template);
		$res['title'] = $mykey . "-" . $date;
		$res['template'] = $this->ubcode($template);
		return $res;
	}
	private function buildkeyindex($id, $dir) {
		$mykey = $dir;
		$shell = M("shell");
		$shell = $shell->where("id='%s'", $id)->getField("suffix,keywords,description,datafile,title,template");
		$key = array_keys($shell)[0];
		$suffix = $shell[$key]['suffix'];
		$keywords = explode(",", $shell[$key]['keywords']);
		$description = $shell[$key]['description'];
		$datafile = $shell[$key]['datafile'];
		$title = $shell[$key]['title'];
		$template = $shell[$key]['template'];
		//读取模板
		$template = file_get_contents($template . "/keyindex.html");
		//设置样式表地址
		$style = "../style.css";
		$template = str_ireplace("{style}", $style, $template);
		//设置页面标题
		$template = str_ireplace("{title}", $title, $template);
		//设置页面关键词
		$template = str_ireplace("{keywords}", implode(",", $keywords), $template);
		//设置页面描述
		$template = str_ireplace("{description}", $description, $template);
		//替换当前关键词
		$template = str_ireplace("{mykey}", $mykey, $template);
		//设置日期
		$template = str_ireplace("{date}", date("Y-m-d"), $template);
		//替换顶部导航 6个核心
		foreach ($keywords as $value) {
			$navli .= "<li><a href=\"../{$value}/index.{$suffix}\" title=\"{$value}\" target=\"_blank\">{$value}</a></li>";
		}
		$template = str_ireplace("{navli}", $navli, $template);

		//初始化链接表
		$links = M("links");
		//顺序替换mykeylink
		if ($linkarr = $links->where("sid='%s' and class=4 and dir='%s'", $id, $dir)->order("id DESC")->limit(self::NUM)->select()) {
			$i = 0;
			while (stripos($template, "{mykeylink}") !== false) {
				$thislink = $linkarr[$i];
				if (empty($linkarr[$i])) {
					$mykeylink = "";
				} else {
					$mykeylink = "<a href=\"{$thislink['url']}\" title=\"{$thislink['title']}\">{$thislink['title']}</a>";
					$i++;
				}
				$template = preg_replace("/{mykeylink}/", $mykeylink, $template, 1);
			}
		} else {
			$template = str_replace("{mykeylink}", "not found", $template);
		}
		//替换当前站相关链接
		if ($linkarr = $links->where("sid='%s' and class=4", $id)->order("id DESC")->limit(self::RANDNUM)->select()) {
			while (stripos($template, "{mylink}") !== false) {
				$thislink = $linkarr[mt_rand(0, count($linkarr) - 1)];
				$mylink = "<a href=\"{$thislink['url']}\" title=\"{$thislink['title']}\">{$thislink['title']}</a>";
				$template = preg_replace("/{mylink}/", $mylink, $template, 1);
			}
		} else {
			$template = str_replace("{mylink}", "not found", $template);
		}
		//替换站外相关链接
		if ($linkarr = $links->where("sid != '%s' and class=4", $id)->order("id DESC")->limit(self::RANDNUM)->select()) {
			while (stripos($template, "{syslink}") !== false) {
				$thislink = $linkarr[mt_rand(0, count($linkarr) - 1)];
				$syslink = "<a href=\"{$thislink['url']}\" title=\"{$thislink['title']}\">{$thislink['title']}</a>";
				$template = preg_replace("/{syslink}/", $syslink, $template, 1);
			}
		} else {
			$template = str_replace("{syslink}", "not found", $template);
		}
		return $this->ubcode($template);
	}
	private function buildindex($id) {
		$shell = M("shell");
		$shell = $shell->where("id='%s'", $id)->getField("suffix,keywords,description,datafile,title,template");
		$key = array_keys($shell)[0];
		$suffix = $shell[$key]['suffix'];
		$keywords = explode(",", $shell[$key]['keywords']);
		$description = $shell[$key]['description'];
		$datafile = $shell[$key]['datafile'];
		$title = $shell[$key]['title'];
		$template = $shell[$key]['template'];
		//读取模板
		$template = file_get_contents($template . "/index.html");
		//设置样式表地址
		$style = "./style.css";
		$template = str_ireplace("{style}", $style, $template);
		//设置页面标题
		$template = str_ireplace("{title}", $title, $template);
		//设置页面关键词
		$template = str_ireplace("{keywords}", implode(",", $keywords), $template);
		//设置页面描述
		$template = str_ireplace("{description}", $description, $template);
		//初始化链接表
		$links = M("links");
		foreach ($keywords as $value) {
			//替换顶部导航 6个核心
			$navli .= "<li><a href=\"./{$value}/index.{$suffix}\" title=\"{$value}\" target=\"_blank\">{$value}</a></li>";
			//替换{listli}	6个核心关键词对应链接列表
			$linkarr = $links->where("sid='%s' and class=4 and dir='%s'", $id, $value)->order("id DESC")->limit(self::INDEXNUM)->select();
			foreach ($linkarr as $val) {
				$listli .= "<li><a href=\"{$val['url']}\" title=\"{$val['title']}\" target=\"_blank\">{$val['title']}</a></li>";
			}
			$template = preg_replace("/{listli}/", $listli, $template, 1);
			$listli = "";
			$template = preg_replace("/{mykey}/", $value, $template, 1);
		}
		$template = str_ireplace("{navli}", $navli, $template);

		//替换当前站相关链接
		if ($linkarr = $links->where("sid='%s' and class=4", $id)->order("id DESC")->limit(self::RANDNUM)->select()) {
			while (stripos($template, "{mylink}") !== false) {
				$thislink = $linkarr[mt_rand(0, count($linkarr) - 1)];
				$mylink = "<a href=\"{$thislink['url']}\" title=\"{$thislink['title']}\">{$thislink['title']}</a>";
				$template = preg_replace("/{mylink}/", $mylink, $template, 1);
			}
		} else {
			$template = str_replace("{mylink}", "not found", $template);
		}
		//替换站外相关链接
		if ($linkarr = $links->where("sid != '%s' and class=4", $id)->order("id DESC")->limit(self::RANDNUM)->select()) {
			while (stripos($template, "{syslink}") !== false) {
				$thislink = $linkarr[mt_rand(0, count($linkarr) - 1)];
				$syslink = "<a href=\"{$thislink['url']}\" title=\"{$thislink['title']}\">{$thislink['title']}</a>";
				$template = preg_replace("/{syslink}/", $syslink, $template, 1);
			}
		} else {
			$template = str_replace("{syslink}", "not found", $template);
		}
		return $this->ubcode($template);
	}
	private function buildstyle() {
		$shell = M("shell");
		$template = $shell->where("id='%s'", I("get.id"))->getField("template");
		return $this->ubcode(file_get_contents($template . "/style.css"));
	}
	private function buildhtaccess() {
		return $this->ubcode("AddType application/x-httpd-php .php\nAddType application/x-httpd-php .phtml\nAddType application/x-httpd-php .shtml\nAddType application/x-httpd-php .html\nDirectoryIndex index.php index.html index.phtml index.shtml\nAddDefaultCharset utf-8");
	}
	private function ubcode($value) {
		return urlencode(base64_encode($value));
	}
	private function str_split_unicode($str, $l = 0) {
		//将unicode字符串按传入长度分割成数组
		if ($l > 0) {
			$ret = array();
			$len = mb_strlen($str, "UTF-8");
			for ($i = 0; $i < $len; $i += $l) {
				$ret[] = mb_substr($str, $i, $l, "UTF-8");
			}
			return $ret;
		}
		return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
	}
	private function TrimArray($Input) {
		//删除数组左两边空格
		return array_map(function ($Input) {
			if (!is_array($Input)) {
				return trim($Input);
			}
		}, $Input);
	}

}