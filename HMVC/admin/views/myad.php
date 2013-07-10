<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>
<link rel="stylesheet" href="<?php echo base_url('views/css/reset.css');?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url('views/css/style.css');?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url('views/css/invalid.css');?>" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url('views/js/jquery.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('views/js/simpla.jquery.configuration.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('views/js/timer/WdatePicker.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('views/js/myad.js');?>"></script>
<script>var i=8;</script>
</head>
<body>
<div id="body-wrapper">
  <div id="sidebar">
    <?php 
    $this->load->module('public/info_made/sys_left');
    ?>
  </div>
  <div id="main-content">
    <div class="clear"></div>
    <div class="content-box">
      <div class="content-box-header">
        <h3>广告管理</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">列表</a></li>
          <li><a href="#tab2">新增</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>描述</th>
                <th>是否限时</th>
                <th>结束时间</th>
                <th></th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="6">
                  <div class="bulk-actions align-left">
                   <select id="dropdown">
                      <option value="option1">请选择...</option>
                      <option value="delete">删除</option>
                    </select>
                    <a class="button" href="#" id="editall">确定</a> </div>
                  <div class="pagination">
                  </div>
                  <div class="clear"></div>
                </td>
              </tr>
            </tfoot>
            <tbody  id="rowinfo">
              <?php 
			  if(!empty($row) AND is_array($row))
			  	foreach($row as $value)
				{
					if($value['timeset'] == 0 )
						$value['timeset']="否";
					else
						$value['timeset']="是";
					$value['downtime']=date("Y-m-d H:i:s",$value['downtime']);
					echo "<tr><td><input type='checkbox' value='".$value['id']."' id='check'/></td><td>".$value['adname']."</td><td>".$value['timeset']."</td><td>".$value['downtime']."</td><td><a href='/admin/myad/edit/".$value['id']."' id='edit'><img src='/admin/views/images/icons/pencil.png' alt='Edit' /></a> <a href='#' title='Delete' id='del'><img src='/admin/views/images/icons/cross.png' alt='Delete' /></a></td></tr>";
				}
			  ?>
            </tbody>
          </table>
        </div>
        <!-- End #tab1 -->
        <div class="tab-content" id="tab2">
          <form action="<?php echo site_url('myad/add_ad');?>" method="post">
            <fieldset>
            <input type="hidden" name="idname" value="<?php echo $adtype;?>" />
            <p>
              <label>名称描述</label>
              <input class="text-input small-input" type="text" name="adname" /><input type="checkbox" name="timeset"  value="1"/>限时
			</p>
            <p>
              <label>开始时间 <span style="margin-left:150px;">结束时间</span></label>
              <input class="small3-input Wdate" type="text" name="uptime" value="<?php echo date('Y-m-d H:i:s');?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',skin:'twoer'})"/>
              <input class="small3-input Wdate" type="text" name="downtime" value="<?php echo date('Y-m-d H:i:s');?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',skin:'twoer'})"/>
			</p>
            <p>
              <label>广告内容</label>
              <textarea name="body"></textarea>
			</p>
            <p>
              <label>过期内容</label>
              <textarea name="nobody"></textarea>
            </p>
            <p>
              <input class="button" type="submit" value="添加" />
            </p>
            </fieldset>
            <div class="clear"></div>
          </form>
        </div>
      </div>
    </div>
    <div class="clear"></div>
    <div id="footer"> <small>
      &#169; Copyright 2010 Your Company | Powered by <a href="#">admin templates</a> | <a href="#">Top</a>  消耗内存：{memory_usage} 消耗时间:{elapsed_time}</small> </div>
    </div>
</div>
</body>
</html>
