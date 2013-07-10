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
<script type="text/javascript" src="<?php echo base_url('views/js/snoopy.js');?>"></script>
<script>
var i=9;
</script>
<style>
  #J_overlay {
    background: none repeat scroll 0 0 #000000;
    height: 100%;
    left: 0;
    opacity: 0.4;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 10000;
}
#J_overlay iframe {
    background: none repeat scroll 0 0 #000000;
    border: 0 none;
    height: 100%;
    left: 0;
    opacity: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: -1;
}
  </style>
</head>
<body>
<div id="body-wrapper">
  <div id="sidebar">
    <?php 
    $this->load->module('public/info_made/sys_left');
    ?>
  </div>
  <div id="main-content">
    <ul class="shortcut-buttons-set">
      <li><a class="shortcut-button" href="#" id="caiji"><span> <img src="<?php echo base_url('views/images/icons/pencil_48.png')?>" alt="icon" /><br />采集</span></a></li>
      <li><a class="shortcut-button" href="#" id="caijid"><span> <img src="<?php echo base_url('views/images/icons/pencil_48.png')?>" alt="icon" /><br />采集单页采集</span></a></li>
    </ul>
    <div class="clear"></div>
    <div class="content-box" style="display:none"  id="view">
      <div class="content-box-header">
        <h3>采集结果</h3>
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
                <th>文章名称</th>
                <th>作者</th>
                <th>时间</th>
                <th>采集查看</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="3">
                  <div class="bulk-actions align-left">
                   <select id="dropdown">
                      <option value="option1">请选择...</option>
                      <?php 
					  foreach($val as $v)
					  	 echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
					  ?>
                    </select>
                    <a class="button" href="#" id="editall">确定</a> </div>
                  <div class="pagination">
                  </div>
                  <div class="clear"></div>
                </td>
              </tr>
            </tfoot>
            <tbody  id="rowinfo">
              
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="content-box closed-box"  id="peiz">
      <div class="content-box-header">
        <h3>采集设置</h3>
        <div class="clear"></div>
      </div>
      <div class="content-box-content" >
        <div class="tab-content default-tab" id="tab1">
          <form action="#" method="post">
            <fieldset>
            <p>
              <label>采集首页地址</label>
              <input class="text-input small-input" type="text" id="url" value="http://www.mp189.com" />
              <input type="checkbox" name="jh" value="1" id="jh" />精华
           </p>
           <p>
              <label>时间</label>
              <input class="text-input small-input" type="text" id="time" value="<?php echo date("Y-m-d",time()-25*60*60) ?>" />
           </p>
           <p>
              <label>帖子页正则</label>
              <input class="text-input medium-input" type="text" id="forum" value="/^http:\/\/www.mp189.com\/forum-[0-9]*-1.html/i" />
           </p>
           <p>
              <label>浏览器agent</label>
              <input class="text-input large-input" type="text" id="agent" value="Mozilla/5.0 (Windows NT 6.1; rv:20.0) Gecko/20100101 Firefox/20.0" />
           </p>
            <p>
              <label>COOKIES</label>
              <textarea id="cookies" cols="79" rows="15"></textarea>
            </p>
            </fieldset>
            <div class="clear"></div>
          </form>
        </div>
      </div>
    </div>
    
    
     <div class="content-box closed-box"  id="peiz">
      <div class="content-box-header">
        <h3>采集设置--指定页采集</h3>
        <div class="clear"></div>
      </div>
      <div class="content-box-content" >
        <div class="tab-content default-tab" id="tab1">
          <form action="#" method="post">
            <fieldset>
            <p>
              <label>采集地址</label>
              <input class="text-input small-input" type="text" id="urld"/>
           </p>
           <p>
           <select id="typed">
                      <?php 
					  foreach($val as $v)
					  	 echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
					  ?>
                    </select>
           </p>
            <p>
              <label>COOKIES</label>
              <textarea id="cookiesd" cols="79" rows="15"></textarea>
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
