<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>数据备份</title>
<script src="<?php echo base_url('views/js/jquery.js');?>"></script>
</head>
<body>
<div style="margin:100px auto; width:500px;">
<table width="400px" cellspacing="0" cellpadding="5" class="border_table_org" align="center" >
        <tbody>
            <tr>
                <td>
                   <div align="center"><br />
								<div style="color:red;font-weight:bold"><?php echo $title;?></div>
                                <?php echo $msg; ?>
                            </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
<?php exit();?>
 			