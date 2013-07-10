<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sql extends MY_Controller
{
	private $sqlEnd=';';
	private	$ds="\n";
	private $message="";
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * 数据库操作
	 */
	public function sql_show()
	{
		$data['num']=strtoupper(substr(md5(rand()),0,17));
		$this->session->set_userdata("sql",$data['num']);
		if (!!$handle = opendir('../backupdata/'))
		{
			while (false !== ($filename = readdir($handle)))
			{
				if ($filename{0} == '.' || $filename=="restore.lock") continue;
				$data['file'][]=$filename;
			}
			closedir($handle);
		}
		$this->load->view("sql",$data);
	}
	
	/**
	 * 运行sql语句
	 */
	public function sql_run()
	{
		$sql=$this->db->query($_POST['sql'])->result_array();;
		echo json_encode(array("sql"=>$sql));
	}
	
	/**
	 * 备份数据
	 */
	public function backup()
	{
		if($_GET['sd']!=$this->session->userdata("sql"))
			$this->load->module('public/public_made/message',array('参数错误!','sql/sql_show'));
		$this->load->helper('file');
 		empty($_POST['var'])?$r=1:$r=$_POST['var'];
 		empty($_POST['nd'])?$nd=0:$nd=$_POST['nd'];
 		if(empty($_POST['filename']))
 		{
	 		$_POST['filename']=date('Ymd_his',$_SERVER['REQUEST_TIME']);
	 		mkdir('../backupdata/'.$_POST['filename']);
 		}
		if($r == 1)
			$GLOBALS['table']=$this->db->list_tables();
		if($nd == count($GLOBALS['table']))
		{
			$this->unlock();
			unset($GLOBALS['strlen'],$GLOBALS['table']);
			$this->message['title']="数据备份完成";
			$this->message['msg']='<p><a href="javascript:window.close();">关闭本页面</a></p>';
			$this->load->view("backmessage",$this->message);
		}
		$str="";
		if($GLOBALS['table'][$nd]=='minpin_sessions')
		{
			$this->message['title']='成功备份第'.$nd.'个表数据'.$GLOBALS['table'][$nd];
			$this->message['msg']="<form method=\"post\" action=\"/admin/sql/backup?sd=".$_GET['sd']."\" id=\"loadingform\"><input type=\"hidden\" name=\"nd\" value=\"".($nd+1)."\"><input type=\"hidden\" name=\"val\" value=\"".$r."\"><input type=\"hidden\" name=\"filename\" value=\"".$_POST['filename']."\"><img src=\"/admin/views/images/ajax_loader.gif\" class=\"marginbot\" /><br />".
					'<p class="marginbot"><a href="###" onclick="$(\'#loadingform\').submit();" class="lightlink">如果浏览器不自动跳转！请点击这里.</a></p></form><br /><script type="text/JavaScript">setTimeout("$(\'#loadingform\').submit();", 2000);</script>';
			$this->load->view("backmessage",$this->message);
		}
		$row=$this->table_info($GLOBALS['table'][$nd]);
		$str.=$row['create'];
		if(!empty($GLOBALS['strlen']) AND $GLOBALS['strlen']>0)
		{
			$strlen=$GLOBALS['strlen'];
			$r--;
		}
		else
			$strlen=2000000;
		foreach($row['info'] as $v)
			if (strlen ( $str ) >= $strlen)
			{
					write_file('../backupdata/'.$_POST['filename'].'/'.$r.'.sql', $str,"a+");
					$str=$this->insert_show($GLOBALS['table'][$nd],$v);
					$r++;
			}
			else
				$str.=$this->insert_show($GLOBALS['table'][$nd],$v);
		$GLOBALS['strlen']=$strlen-strlen ( $str );
		write_file('../backupdata/'.$_POST['filename'].'/'.$r.'.sql', $str,"a+");
		$this->message['title']='成功备份第'.$nd.'个表数据';
		$this->message['msg']="<form method=\"post\" action=\"/admin/sql/backup?sd=".$_GET['sd']."\" id=\"loadingform\"><input type=\"hidden\" name=\"nd\" value=\"".($nd+1)."\"><input type=\"hidden\" name=\"val\" value=\"".($r+1)."\"><input type=\"hidden\" name=\"filename\" value=\"".$_POST['filename']."\"><br /><img src=\"/admin/views/images/ajax_loader.gif\" class=\"marginbot\" /><br />".
				'<p class="marginbot"><a href="###" onclick="$(\'#loadingform\').submit();" class="lightlink">如果浏览器不自动跳转！请点击这里.</a></p></form><br /><script type="text/JavaScript">setTimeout("$(\'#loadingform\').submit();", 2000);</script>';
		$this->load->view("backmessage",$this->message);
	}
	
	/**
	 * 优化
	 */
	public function optimize()
	{
		if($_POST['sd']!=$this->session->userdata("sql"))
			$this->load->module('public/public_made/message',array('参数错误!','sql/sql_show'));
		$this->load->dbutil();
		if(!!$result=$this->dbutil->optimize_database())
		{
			echo '优化完成!<br />';
			print_r($result);
		}else
			echo '优化失败!';
	}
	
	/**
	 * 获取表结构急内容
	 * @param unknown $table
	 * @return multitype:string NULL
	 */
	private function table_info($table)
	{
		$this->lock($table);
		$sql=array();
		$sql['create'] = "";
		// 如果存在则删除表
		$sql['create'] .= $this->ds."DROP TABLE IF EXISTS `" . $table. '`' . $this->sqlEnd . $this->ds;
		$row=$this->db->query("SHOW CREATE TABLE `$table`")->result_array();
		$sql['create'] .= $row[0]['Create Table'].$this->sqlEnd.$this->ds.$this->ds;
		$sql['info']=$this->db->get($table)->result_array();
		return $sql;
	}
	
	/**
	 * 生成插入语句
	 * @param unknown $table
	 * @param unknown $v
	 * @return string
	 */
	private function insert_show($table,$v)
	{
		$comma="";
		$insert = "INSERT INTO `" . $table . "` VALUES(";
		// 循环每个子段下面的内容
		foreach($v as $k)
		{
			$insert .= ($comma . "'" . mysql_escape_string ( $k ) . "'");
			$comma = ",";
		}
		$insert .= ")". $this->sqlEnd . $this->ds;
		return $insert;
	}
	
	/**
	 * 删除备份
	 */
	function del()
	{
		$dir='../backupdata/'.$_GET['file'];
		echo $this->deldir($dir);
	}
	
	/**
	 * 删除文件夹
	 * @param unknown $dir
	 * @return boolean
	 */
	private function deldir($dir)
	{
		$dh=opendir($dir);
		while (!!$file=readdir($dh)) {
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file;
				if(!is_dir($fullpath)) {
					unlink($fullpath);
				} else {
					$this->deldir($fullpath);
				}
			}
		}
			
		closedir($dh);
		//删除当前文件夹：
		if(rmdir($dir)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 还原备份
	 */
	function restore()
	{
		if(file_exists ('../backupdata/restore.lock'))
			exit('数据还原已经被锁定，请删除 /backupdata/restore.lock');
		if (! file_exists ('../backupdata/'.$_GET['file'] )) {
			exit ( "备份文件不存在！请检查" );
		}
		empty($_POST['volume'])?$volume=1:$volume=$_POST['volume'];
		$file='../backupdata/'.$_GET['file'].'/'.$volume.'.sql';
		if(! file_exists ( $file ))
			exit("备份文件结构不正确，请检测备份文件是否完整！");
		else
		{
			if($this->_import($file))
				$this->message['msg']='<p>第'.$volume.'个备份文件导入成功！</p>';
			else
				$this->message['msg']='<p>第'.$volume.'个备份文件导入失败！</p>';
			$volume++;
			$file='../backupdata/'.$_GET['file'].'/'.$volume.'.sql';
			if(! file_exists ( $file ))
			{
				@fopen("../backupdata/restore.lock","a+");
				$this->message['title']="数据还原完成";
				$this->message['msg'].='<p><a href="javascript:window.close();">关闭本页面</a></p>';
			}
			else
			{
				$this->message['title']="数据还原中请勿中断，以免造数据库结构受损";
				$this->message['msg'].="<form method=\"post\" action=\"/admin/sql/restore?file=".$_GET['file']."\" id=\"loadingform\"><input type=\"hidden\" name=\"volume\" value=\"".$volume."\"><br /><img src=\"/admin/views/images/ajax_loader.gif\" class=\"marginbot\" /><br />".
						'<p class="marginbot"><a href="###" onclick="$(\'#loadingform\').submit();" class="lightlink">如果浏览器不自动跳转！请点击这里.</a></p></form><br /><script type="text/JavaScript">setTimeout("$(\'#loadingform\').submit();", 2000);</script>';
			}
		}
		$this->load->view("backmessage",$this->message);
	}
	
	/**
	 * 将sql导入到数据库（普通导入）
	 *
	 * @param string $sqlfile
	 * @return boolean
	 */
	private function _import($sqlfile) {
		// sql文件包含的sql语句数组
		$sqls = array ();
		$f = fopen ( $sqlfile, "rb" );
		// 创建表缓冲变量
		$create_table = '';
		while ( ! feof ( $f ) ) {
			// 读取每一行sql
			$line = fgets ( $f );
			// 这一步为了将创建表合成完整的sql语句
			// 如果结尾没有包含';'(即为一个完整的sql语句，这里是插入语句)，并且不包含'ENGINE='(即创建表的最后一句)
			if (! preg_match ( '/;/', $line ) || preg_match ( '/ENGINE=/', $line )) {
				// 将本次sql语句与创建表sql连接存起来
				$create_table .= $line;
				// 如果包含了创建表的最后一句
				if (preg_match ( '/ENGINE=/', $create_table)) {
					//执行sql语句创建表
					$this->db->query(trim ($create_table));
					// 清空当前，准备下一个表的创建
					$create_table = '';
				}
				// 跳过本次
				continue;
			}
			//执行sql语句
			$this->db->query( trim ( $line ) );
		}
		fclose ( $f );
		return true;
	}
	
	// 锁定数据库，以免备份或导入时出错
	private function lock($table,$op = "WRITE") {
		if ($this->db->query( "lock tables ".$table. " " . $op ))
			return true;
		else
			return false;
	}
	
	// 解锁
	private function unlock() {
		if ($this->db->query( "unlock tables" ))
			return true;
		else
			return false;
	}
	
	/**
	 * 用户列表管理
	 */
	function user()
	{
		$this->load->model("index_model","index");
		$data['row']=$this->index->user_list();
		$data['arcrole']=$this->index->arcrole();
		$data['adminrole']=$this->index->adminrole();
		$this->load->view("user",$data);
	}
	
	/**
	 * 用户修改提交
	 */
	function user_post($tp="ka")
	{
		$this->input->post(NULL,TRUE);
		if($tp== 'ka')
			switch ($_POST['type'])
			{
				case 'tz': $this->db->where("uid",$_POST['id'])->update("admin",array("start"=>2));
				break;
				case 'ks': $this->db->where("uid",$_POST['id'])->update("admin",array("start"=>1));
				break;
				case 'del':  $this->db->where("uid",$_POST['id'])->delete("admin");
				break;
			}
			elseif($tp == 'add')
			{
				$this->input->post(NULL,TRUE);
				$this->load->model("index_model","index");
				if($_POST['password'] != $_POST['password2'])
					$this->load->module('public/public_made/message',array("两次密码不一致!"));
				unset($_POST['password2']);
				$_POST['password']=substr(md5($_POST['password']), 5, 20);
				$_POST['start']=2;
				$_POST['ip']=$_SERVER['REMOTE_ADDR'];
				$arcrole=$this->index->arcrole();
				$_POST['wpower']="";
				foreach($arcrole as $value)
				{
					if(empty($_POST['arcrole'.$value['id']]))
						continue;
					$tr="";
					empty($_POST['arcrole'.$value['id']][0])?$tr.="0":$tr.="1";
					empty($_POST['arcrole'.$value['id']][1])?$tr.="0":$tr.="1";
					empty($_POST['arcrole'.$value['id']][2])?$tr.="0":$tr.="1";
					unset($_POST['arcrole'.$value['id']]);
					if($tr)
						$_POST['wpower'].=$value['id'].':'.bindec($tr)." ";
				}
				$arcrole=$this->index->adminrole();
				$_POST['power']="";
				foreach($arcrole as $value)
				{
					if(empty($_POST['role'.$value['menu_id']]))
						continue;
					$tr="";
					empty($_POST['role'.$value['menu_id']][0])?$tr.="0":$tr.="1";
					empty($_POST['role'.$value['menu_id']][1])?$tr.="0":$tr.="1";
					empty($_POST['role'.$value['menu_id']][2])?$tr.="0":$tr.="1";
					unset($_POST['role'.$value['menu_id']]);
					if($tr)
						$_POST['power'].=$value['menu_id'].':'.bindec($tr)." ";
				}
				$_POST['power']=trim($_POST['power']);
				$_POST['wpower']=trim($_POST['wpower']);
				$this->db->insert("admin",$_POST);
				$this->load->module('public/public_made/message',array("用户添加成功！"));
			}
	}
	
	/**
	 * 修改用户
	 * @param unknown $id
	 */
	function edit_user($id)
	{
		if(!is_numeric($id))
			$this->load->module('public/public_made/message',array("参数错误！"));
		$this->load->model("index_model","index");
		$data['arcrole']=$this->index->arcrole();
		$data['adminrole']=$this->index->adminrole();
		$data['info']=$this->index->user_info($id);
		if(!empty($data['info']['power']))
			$data['power']=explode(' ', $data['info']['power']);
		$power=array();
		if(!empty($data['power']))
		foreach ($data['power'] as $key => $value)
		{
			$value=explode(':', $value);
			$power[$value[0]]=$value[1];
		}
		$data['power']=$power;
		if(!empty($data['info']['wpower']))
			$data['wpower']=explode(' ', $data['info']['wpower']);
		if(!empty($data['wpower']))
		foreach ($data['wpower'] as $key => $value)
		{
			$value=explode(':', $value);
			$power[$value[0]]=$value[1];
		}
		$data['wpower']=$power;
		foreach ($data['arcrole'] as $value)
		{
			if(empty($data['wpower'][$value['id']]))
				$data['wpower'][$value['id']]='111';
			else
				$data['wpower'][$value['id']]=decbin(7-$data['wpower'][$value['id']]);
			$data['wpower'][$value['id']]=str_pad($data['wpower'][$value['id']],3,0,STR_PAD_LEFT);
		}
		foreach ($data['adminrole'] as $value)
		{
			if(empty($data['power'][$value['menu_id']]))
				$data['power'][$value['menu_id']]='111';
			else
				$data['power'][$value['menu_id']]=decbin(7-$data['power'][$value['menu_id']]);
			$data['power'][$value['menu_id']]=str_pad($data['power'][$value['menu_id']],3,0,STR_PAD_LEFT);
		}
		$this->load->view("useredit",$data);
	}
	
	/**
	 * 用户修改提交
	 */
	function user_edit_post($id)
	{
		if(!is_numeric($id))
			$this->load->module('public/public_made/message',array("参数错误！"));
		$this->input->post(NULL,TRUE);
		$this->load->model("index_model","index");
		if(!empty($_POST['password']))
		{
			if($_POST['password'] != $_POST['password2'])
				$this->load->module('public/public_made/message',array("两次密码不一致！"));
			unset($_POST['password2']);
			$_POST['password']=substr(md5($_POST['password']), 5, 20);
		}
		else
			unset($_POST['password'],$_POST['password2']);
		$_POST['ip']=$_SERVER['REMOTE_ADDR'];
		$arcrole=$this->index->arcrole();
		$_POST['wpower']="";
		foreach($arcrole as $value)
		{
			if(empty($_POST['arcrole'.$value['id']]))
				continue;
			$tr="";
			empty($_POST['arcrole'.$value['id']][0])?$tr.="0":$tr.="1";
			empty($_POST['arcrole'.$value['id']][1])?$tr.="0":$tr.="1";
			empty($_POST['arcrole'.$value['id']][2])?$tr.="0":$tr.="1";
			unset($_POST['arcrole'.$value['id']]);
			if($tr)
				$_POST['wpower'].=$value['id'].':'.bindec($tr)." ";
		}
		$arcrole=$this->index->adminrole();
		$_POST['power']="";
		foreach($arcrole as $value)
		{
			if(empty($_POST['role'.$value['menu_id']]))
				continue;
			$tr="";
			empty($_POST['role'.$value['menu_id']][0])?$tr.="0":$tr.="1";
			empty($_POST['role'.$value['menu_id']][1])?$tr.="0":$tr.="1";
			empty($_POST['role'.$value['menu_id']][2])?$tr.="0":$tr.="1";
			unset($_POST['role'.$value['menu_id']]);
			if($tr)
				$_POST['power'].=$value['menu_id'].':'.bindec($tr)." ";
		}
		$_POST['power']=trim($_POST['power']);
		$_POST['wpower']=trim($_POST['wpower']);
		$this->db->where("uid",$id)->update("admin",$_POST);
		$this->load->module('public/public_made/message',array("用户修改成功！"));
	}
}