function showhash() {
    var hash = window.location.hash; //取hash
    if (hash == "") {
        hash = "showshell";
    } else {
        hash = hash.replace(/#/, ''); //删除井号
    }
    if (hash.indexOf("?") <= 0) { //没有问号时
        $("#" + hash + "a").siblings().removeClass("active"); //同胞 取消激活
        $("#" + hash + "a").addClass("active"); //激活按钮
    }
    $.get(CONTROLLER + "/" + hash, function(data) { //ajax获取页面
        $("#right").html(data);
        //如果是统计，制作图表
        if (hash == "spidercount") {
            $.get(CONTROLLER + "/spidercount?method=count30", function(data) {
                var count = new Array();
                var date = new Array();
                $.each(data, function(index, val) {
                    count.push(val.count);
                    date.push(val.date);
                });
                var ctx = $("#spidercountchart").get(0).getContext("2d");
                var data = {
                    labels: date,
                    datasets: [{
                        fillColor: "rgba(220,220,220,0.5)",
                        strokeColor: "rgba(220,220,220,1)",
                        pointColor: "rgba(220,220,220,1)",
                        pointStrokeColor: "#fff",
                        data: count
                    }]
                }
                new Chart(ctx).Bar(data);
            });

        }
    });

}
showhash();
//1 首页索引 2二级目录 3 二级索引 4 二级文章页面
//动态加载页面 绑定on事件
$(document).ready(function() {

    $(document).on('click', '#staticpage', function() {
        $("#staticpagevalue").attr("value", $("#staticpage").val());
    });
    //选中 清空 反选 用prop 对于只有真假
    $(document).on('click', '#selectAll', function() {
        $("#tablelist :checkbox").prop("checked", "true");
    });
    $(document).on('click', '#unselectAll', function() {
        $("#tablelist :checkbox").removeAttr("checked");
    });
    $(document).on('click', '#reverse', function() {
        $("#tablelist :checkbox").each(function() {
            this.checked = !this.checked;
        })
    });
    //分页点击
    $(document).on('click', '#page a', function() {
        $.get($(this).attr('href'), function(data) { //ajax获取页面
            $("#right").html(data);
        });
    });
    //hash改变  右侧插入代码
    $(window).bind('hashchange', function() {
        showhash();
    });
    //添加shell
    $(document).on('click', '#addbtn', function() {
        var params = $('#addshellform').serialize();
        $.ajax({
            url: CONTROLLER + "/addshell",
            type: 'POST',
            dataType: 'json',
            data: params,
            success: function(result) {
                if (result['status'] == 1) {
                    alert(result['con']);
                    window.location.reload();
                } else {
                    alert(result['con']);
                }
            },
        });
    });
    //编辑shell
    $(document).on('click', '#editbtn', function() {
        var params = $('#editshellform').serialize();
        $.ajax({
            url: CONTROLLER + "/editshell",
            type: 'POST',
            dataType: 'json',
            data: params,
            success: function(result) {
                if (result['status'] == 1) {
                    alert(result['con']);
                    window.location.reload();
                } else {
                    alert(result['con']);
                }
            },
        });
    });
    //删除选中shell
    $(document).on('click', '#delbutton', function() {
        var id = new Array;
        $("#tablelist :checkbox").each(function() {
            if ($(this).prop("checked")) {
                id.push($(this).val());
            }
        });
        $.ajax({
            url: CONTROLLER + "/delshell",
            type: 'POST',
            dataType: 'json',
            data: "id=" + id.toString(),
            success: function(result) {
                if (result['status'] == 1) {
                    alert(result['con']);
                    $("#tablelist :checkbox").removeAttr("checked");
                    window.location.reload();
                } else {
                    alert(result['con']);
                }
            },
        });
    });
    //蜘蛛统计
    $(document).on('click', '#spibutton', function() {
        $("#tablelist :checkbox").each(function() {
            if ($(this).prop("checked")) {
                var id = $(this).val();
                $.get(CONTROLLER + "/getinfo?id=" + id, function(data) {
                    var url = data['url'].replace(/\/\w+\.php$/, "/");
                    $.get(url + "spider.php?show=log", function(data) {
                        if (data == "finished") {
                            //没有历史记录 获取今日 存储
                            $.get(url + "spider.php?show=now", function(data) {
                                $.post(CONTROLLER + "/spidercount?method=now&id=" + id, "data=" + data, function(data) {
                                    $("#info_now").prepend("<p>" + id + "无历史记录，今日记录已更新</p>");
                                });
                            });
                        } else {
                            //传送存储历史记录
                            $.post(CONTROLLER + "/spidercount?method=history&id=" + id, "data=" + data, function(data) {
                                $("#info_now").prepend("<p>" + id + "历史记录已存储</p>");
                            });
                            //获取今日 存储
                            $.get(url + "spider.php?show=now", function(data) {
                                $.post(CONTROLLER + "/spidercount?method=now&id=" + id, "data=" + data, function(data) {
                                    $("#info_now").prepend("<p>" + id + "今日记录已更新</p>");
                                });
                            });
                        }
                    });

                });
            }
        });
    });
    //初始化shell
    $(document).on('click', '#firbutton', function() {
        $("#tablelist :checkbox").each(function() {
            if ($(this).prop("checked")) {
                var id = $(this).val();
                $.get(CONTROLLER + '/build?method=dir&id=' + id, function(data) {
                    var url = data['url'];
                    var pw = data['pw'];
                    var dir = data['dir']; //arr
                    var baseurl = url.replace(/\/\w+\.php$/, "/");
                    //写入样式表在根目录
                    $.get(CONTROLLER + "/build?method=style&id=" + id, function(data) {
                        var style = "pw=" + pw + "&dir=./&page=style.css&content=" + data;
                        $.post(url, style, function(data) {
                            $("#info_now").prepend("<p>" + baseurl + "style.css 创建成功</p>");
                        });
                    });
                    //写入htaccess在根目录
                    $.get(CONTROLLER + "/build?method=htaccess&id=" + id, function(data) {
                        var style = "pw=" + pw + "&dir=./&page=.htaccess&content=" + data;
                        $.post(url, style, function(data) {
                            $("#info_now").prepend("<p>" + baseurl + ".htaccess 创建成功</p>");
                        });
                    });
                    //写入spider.php在根目录
                    $.get(CONTROLLER + "/build?method=spider", function(data) {
                        var style = "pw=" + pw + "&dir=./&page=spider.php&content=" + data;
                        $.post(url, style, function(data) {
                            $("#info_now").prepend("<p>" + baseurl + "spider.php 创建成功</p>");
                        });
                    });
                    //循环创建每个shell的二级目录，链接写入连接池
                    $.each(dir, function(index, val) {
                        var shell = "pw=" + pw + "&dir=" + val;
                        $.post(url, shell, function(data) {
                            $.get(CONTROLLER + '/links?url=' + baseurl + val + '/&sid=' + id + '&class=2&dir=' + val, function(data) {
                                $("#info_now").prepend("<p>" + baseurl + val + " 创建成功</p>");
                            });
                        });
                    });
                });
            }
        });
    });
    //每个子目录写入一个页面。
    $(document).on('click', '#crebutton', function() {
        $("#tablelist :checkbox").each(function() {
            if ($(this).prop("checked")) {
                var id = $(this).val();
                $.get(CONTROLLER + "/build?method=dir&id=" + id, function(data) {
                    var url = data['url'];
                    var pw = data['pw'];
                    var suffix = data['suffix'];
                    var dir = data['dir'];
                    var baseurl = url.replace(/\/\w+\.php$/, "/");
                    //循环每个shell的二级目录
                    $.each(dir, function(index, val) {
                        //写入子页面
                        $.get(CONTROLLER + "/build?method=artcle&id=" + id + "&dir=" + val, function(page) {
                            var shell = "pw=" + pw + "&dir=" + val + "&page=" + page['title'] + "." + suffix + "&content=" + page['template'];
                            $.post(url, shell, function(data) {
                                var link = url;
                                link = link.replace(/\/\w+\.php$/, "") + "/" + val + "/";
                                $.get(CONTROLLER + '/links?url=' + link + page['title'] + "." + suffix + '&sid=' + id + '&class=4&dir=' + val + '&title=' + page['title'], function(data) {
                                    $("#info_now").prepend("<p>" + link + page['title'] + "." + suffix + " 创建成功</p>");
                                });
                            });
                        });
                    });
                });
            }
        });
    });

    //写入子目录索引页面、写入首页索引
    $(document).on('click', '#updbutton', function() {
        //循环每个shell的二级目录
        $("#tablelist :checkbox").each(function() {
            if ($(this).prop("checked")) {
                var id = $(this).val();
                //取得二级目录
                $.get(CONTROLLER + "/build?method=dir&id=" + id, function(data) {
                    var url = data['url'];
                    var pw = data['pw'];
                    var dir = data['dir'];
                    var suffix = data['suffix'];
                    var baseurl = url;
                    baseurl = baseurl.replace(/\/\w+\.php$/, "/");
                    //循环每个shell的二级目录
                    $.each(dir, function(index, val) {
                        //写入子目录索引页面
                        $.get(CONTROLLER + "/build?method=keyindex&id=" + id + "&dir=" + val, function(page) {
                            var shell = "pw=" + pw + "&dir=./" + val + "&page=index." + suffix + "&content=" + page;
                            $.post(url, shell, function(data) {
                                $("#info_now").prepend("<p>" + baseurl + val + "/index." + suffix + " 创建成功</p>");
                            });
                        });
                    });
                    //写入首页录索引页面
                    $.get(CONTROLLER + "/build?method=index&id=" + id, function(page) {
                        var shell = "pw=" + pw + "&dir=./&page=index." + suffix + "&content=" + page;
                        $.post(url, shell, function(data) {
                            $("#info_now").prepend("<p>" + baseurl + "index." + suffix + " 创建成功</p>");
                        });
                    });
                });
            }
        });
    });





});
