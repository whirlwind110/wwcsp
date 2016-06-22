<?php if (!defined('THINK_PATH')) exit();?><form class="form-horizontal col-xs-11" id="editshellform" method="POST">
    <div class="form-group">
        <label for="shellurl" class="col-xs-2 control-label">地址</label>
        <div class="col-xs-10">
            <input type="text" class="form-control" id="shellurl" name="url" value="<?php echo ($url); ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="shellpw" class="col-xs-2 control-label">密码</label>
        <div class="col-xs-10">
            <input type="text" class="form-control" id="shellpw" name="pw" value="<?php echo ($pw); ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="shellsuf" class="col-xs-2 control-label">后缀类型</label>
        <div class="col-xs-6">
            <select class="form-control" id="shellsuf" name="suffix">
                <option>
                    <?php echo ($suffix); ?>
                </option>
                <option>-----------</option>
                <option>php</option>
                <option>phtml</option>
                <option>html</option>
                <option>php4</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="shelltem" class="col-xs-2 control-label">模板路径</label>
        <div class="col-xs-10">
            <select class="form-control" id="shelltem" name="template">
                <option>
                    <?php echo ($mytemplate); ?>
                </option>
                <option>-----------</option>
                <?php if(is_array($template)): $i = 0; $__LIST__ = $template;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sel): $mod = ($i % 2 );++$i;?><option>
                        <?php echo ($sel); ?>
                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="shelldf" class="col-xs-2 control-label">词库路径</label>
        <div class="col-xs-10">
            <select class="form-control" id="shelldf" name="datafile">
                <option>
                    <?php echo ($mydatafile); ?>
                </option>
                <option>-----------</option>
                <?php if(is_array($datafile)): $i = 0; $__LIST__ = $datafile;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$df): $mod = ($i % 2 );++$i;?><option>
                        <?php echo ($df); ?>
                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="shelltit" class="col-xs-2 control-label">标题</label>
        <div class="col-xs-10">
            <input type="text" class="form-control col-xs-8" id="shelltit" name="title" value="<?php echo ($title); ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="shelldes" class="col-xs-2 control-label">描述</label>
        <div class="col-xs-10">
            <input type="text" class="form-control col-xs-8" id="shelldes" name="description" value="<?php echo ($description); ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="shellkey" class="col-xs-2 control-label">关键词</label>
        <div class="col-xs-10">
            <input type="text" class="form-control col-xs-8" id="shellkey" name="keywords" value="<?php echo ($keywords); ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="shelllongkey" class="col-xs-2 control-label">长尾词库</label>
        <div class="col-xs-10">
            <textarea type="text" class="form-control col-xs-8" rows="3" id="shelllongkey" name="longkey"><?php echo ($longkey); ?></textarea>
        </div>
    </div>
    <input type="hidden" name="id" value="<?php echo ($id); ?>">
    <div class="form-group">
        <button type="button" class="btn btn-primary  center-block" id="editbtn">更新</button>
    </div>
</form>