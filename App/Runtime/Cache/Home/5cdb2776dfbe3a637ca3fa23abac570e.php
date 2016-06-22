<?php if (!defined('THINK_PATH')) exit();?><form class="form-horizontal col-xs-11" id="addpoolform" method="POST">
    <div class="form-group">
        <label for="shellurl" class="col-xs-2 control-label">地址</label>
        <div class="col-xs-10">
            <input type="text" class="form-control" id="url" name="url" />
        </div>
    </div>
    <div class="form-group">
        <label for="shelltit" class="col-xs-2 control-label">锚文本</label>
        <div class="col-xs-10">
            <input type="text" class="form-control" id="title" name="title" />
        </div>
    </div>
    <div class="form-group">
        <label for="shelldes" class="col-xs-2 control-label">静态页面数</label>
        <div class="col-xs-10">
            <div class="col-xs-11 staticpage">
                <input type="range" min="10" max="200" step="10" id="staticpage" Value="100" class="form-control" />
            </div>
            <div class="col-xs-1 staticpage">
                <input type="text" id="staticpagevalue" class="form-control" value="100" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-primary  center-block" id="pooladdbtn">添加</button>
    </div>
</form>