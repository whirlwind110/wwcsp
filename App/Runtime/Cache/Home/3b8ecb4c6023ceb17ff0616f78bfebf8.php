<?php if (!defined('THINK_PATH')) exit();?><table class="table table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>URL</th>
            <th>Password</th>
            <th>Select</th>
        </tr>
    </thead>
    <tbody id="tablelist">
        <?php if(is_array($shelllist)): $i = 0; $__LIST__ = $shelllist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$shelllist): $mod = ($i % 2 );++$i;?><tr>
                <td>
                    <?php echo ($shelllist["id"]); ?>
                </td>
                <td>
                    <?php echo ($shelllist["url"]); ?>
                </td>
                <td>
                    <?php echo ($shelllist["pw"]); ?>
                </td>
                <td>
                    <input type="checkbox" name="check" value="<?php echo ($shelllist["id"]); ?>" />
                    <a href="#editshell?sid=<?php echo ($shelllist["id"]); ?>" class="editshella">编辑</a>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>
<div class="col-sm-4 btn-group-sm" role="group" aria-label="...">
    <button class="btn btn-defaul" id="selectAll" type="button">全选</button>
    <button class="btn btn-defaul" id="reverse" type="button">反选</button>
    <button class="btn btn-defaul" id="unselectAll" type="button">清空</button>
    <button class="btn btn-primary" id="spibutton" type="button">刷新统计</button>
</div>
<div class="col-sm-8" id="page">
    <?php echo ($page); ?>
</div>
<div class="col-sm-4 btn-group-sm" role="group" aria-label="...">
    <button class="btn btn-defaul" id="delbutton" type="button">删除</button>
    <button class="btn btn-defaul" id="firbutton" type="button">初始</button>
    <button class="btn btn-defaul" id="crebutton" type="button">新增</button>
    <button class="btn btn-primary" id="updbutton" type="button">更新索引</button>
</div>