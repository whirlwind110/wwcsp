<?php
return array(
	/* 数据库设置  */
	'DB_TYPE' => 'mysql', // 数据库类型
	'DB_HOST' => '127.0.0.1', // 服务器地址
	'DB_NAME' => 'jsc2', // 数据库名
	'DB_USER' => 'root', // 用户名
	'DB_PWD' => 'admin888', // 密码
	'DB_PORT' => '3306', // 端口
	'DB_PREFIX' => '', // 数据库表前缀
	'DB_PARAMS' => array(), // 数据库连接参数
	'DB_DEBUG' => false, // 数据库调试模式 开启后可以记录SQL日志
	'DB_CHARSET' => 'utf8', // 数据库编码默认采用utf8
	/* URL设置 */
	'URL_CASE_INSENSITIVE' => true, // 默认false 表示URL区分大小写 true则表示不区分大小写
	'URL_MODEL' => 2, // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
	// 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
	/*调试*/
	'SHOW_PAGE_TRACE' => 0,
	/* 模板引擎*/
	'TMPL_L_DELIM' => '<{', // 模板引擎普通标签开始标记
	'TMPL_R_DELIM' => '}>', // 模板引擎普通标签结束标记
	/*安全配置*/
	'COOKIE_HTTPONLY' => '1', // Cookie httponly设置
	'DEFAULT_FILTER' => 'htmlspecialchars,trim', // 默认参数过滤方法 用于I函数...

);