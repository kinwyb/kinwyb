<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->library("ucapi");
	}
	
	/**
	 * 用户登入
	 */
	function userlogin()
	{
		$username=$this->input->post("username",TRUE);
		$password=$this->input->post("password",TRUE);
		$this->_uc_user_login($username, $password);
	}
	
	/**
	 * UCenter 登入检测
	 * @param unknown $username
	 * @param unknown $password
	 */
	private function _uc_user_login($username,$password)
	{
		$row['timestamp'] = time();
		list($row['uid'], $row['username'], $row['password'], $row['email']) = $this->ucapi->login($username, $password);
		if($row['uid'] > 0)
		{
			//生成同步登录的代码
			$ucsynlogin = $this->ucapi->synlogin($row['uid']);
			echo $ucsynlogin;
		}else
			echo false;
	}
	
	/**
	 * AJAX登入检测
	 */
	function checklogin()
	{
		$row=$this->ucapi->check_login();
		$row=$this->ucapi->user_info($row['uid']);
		if($row['username'])
			echo '<strong>'.$row['username'].'</strong>&nbsp;&nbsp;|&nbsp;&nbsp;<div class="am" id="am" style="display:inline;"><a href="'.$row['home'].'">个人中心</a></div>&nbsp;&nbsp;|&nbsp;&nbsp;<span id="msg_n"><a href="'.$row['pm_url'].'" target="_parent">短消息('.$row['newpm'].')</a></span>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/login/logout" target="_self">退出</a>';
		else
			echo '<a id="zLogin" rel="nofollow" href="/login/user_login'.'" target="_self">登录</a> <a rel="nofollow" href="/login/register" target="_blank">注册</a>';
	}
	
	/**
	 *	留言登入检测
	 */
	function check_feedback()
	{
		$row=$this->ucapi->check_login();
		if($row['uid'])
		{
			$row['back']=false;
			$row['user']=$this->ucapi->user_info($row['uid']);
			echo json_encode($row);
		}
		else
			echo json_encode(array("back"=>true));
	}
	
	/**
	 * 用户退出
	 */
	function logout()
	{
		$timestamp = time();
		//生成同步退出的代码
		$ucsynlogout = $this->ucapi->synlogout();
		echo $ucsynlogout;
		echo '<script>history.go(-1);</script>';
	}
	
	/**
	 * 登入页面
	 */
	function user_login()
	{
		$row=$this->ucapi->check_login();
		if($row['uid'])
			echo '<script>history.go(-1);</script>';
		else
			$this->load->view("login");
	}
	
	/**
	 * 注册页面
	 */
	function register()
	{
		$row=$this->ucapi->check_login();
		if($row['uid'])
			$this->load->view("sys_message",array("msg"=>"你已经登陆！","locat"=>'http://'.$_SERVER["HTTP_HOST"]));
		else
			$this->load->view("register");
	}
	
	/**
	 * 注册检查
	 */
	function check_user_register()
	{
		$type=$this->input->post("type",TRUE);
		if($type=='username')
		{
			$username=$this->input->post("username",TRUE);
			echo $this->ucapi->checkname($username);
			exit();
		}
		if($type=='email')
		{
			$username=$this->input->post("username",TRUE);
			echo $this->ucapi->checkemail($username);
			exit();
		}
	}
	
	/**
	 * 提交注册
	 */
	function register_post()
	{
		if($_SESSION['code'] != $_POST['vcode'])
			$this->load->view("sys_message",array("msg"=>"验证码错误！"));
		$this->load->model("login_model");
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', '用户名', 'required|max_length[16]|min_length[6]|xss_clean');
		$this->form_validation->set_rules('password', '密码', 'required|max_length[25]|min_length[4]|xss_clean');
		$this->form_validation->set_rules('password2', '重复密码', 'required|max_length[25]|min_length[4]|xss_clean');
		$this->form_validation->set_rules('email', '邮箱', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$data['error']=1;
			$this->load->view("register",$data);
		}
		else
		{
			$this->input->post(NULL,TRUE);
			if($_POST['password2'] != $_POST['password'])
				$this->load->view("sys_message",array("msg"=>"两次密码不一致！"));
			elseif($_SESSION['vcode'] != $_POST['vcode'])
			$this->load->view("sys_message",array("msg"=>"验证码错误！"));
			elseif(($this->ucapi->checkname($_POST['username']) != 1) OR ($this->ucapi->checkemail($_POST['email']) != 1))
			$this->load->view("sys_message",array("msg"=>"用户名或邮箱已被注册！"));
			else
			{
				$v=$this->ucapi->register($_POST['username'], $_POST['password'], $_POST['email']);
				if($v > 0)
				{
					echo $this->ucapi->synlogin($v);
					$this->load->view("sys_message",array("msg"=>"注册成功！","locat"=>'http://'.$_SERVER["HTTP_HOST"]));
				}
				else
					$this->load->view("sys_message",array("msg"=>"注册失败！"));
			}
		}
	}
	
	function vcode($f="120_36_6")
	{
		list($data['width'],$data['height'],$data['codelength'])=explode("_", $f);
		$this->load->library('imgcode',$data);
	}
	
	/**
	 * 找回密码
	 * @param string $type
	 */
	function get_pwd()
	{
		$data['info']='<h2>找回密码-操作说明</h2>
      <p>1) 填写你的用户名与你注册时候的邮箱地址！<br />
      	 2) 提交你填写的内容后，系统会发送一封邮件至你的邮箱，点击邮箱限定时间内点击你的邮件连接即可重置你的密码！</p>
				<div class="utilities">
        <form method="post" action="/login/get_pwd_post">
          <div class="input-container">
				<input class="left" name="username" id="search" placeholder="用户名" type="text"><br />&nbsp;<br />
              <input class="left" name="email" id="search" placeholder="注册邮箱" type="text">
          </div>
           <INPUT class="button right" type="submit" value="提交" />
        </form>
        <div class="clear"></div>
      </div>';
		$this->load->view("get_pwd",$data);
	}
	
	/**
	 * 找回密码提交
	 * @param string $type
	 */
	function get_pwd_post()
	{
		$this->input->post(NULL,TRUE);
		$id=$this->ucapi->uc_get_user($_POST['username']);
		if(empty($id) || $_POST['email'] != $id[2])
			$data['info']='<h2>找回密码-错误说明</h2>
      <p>你所提交的邮箱与用户名对应有误！</p>
					<div class="utilities">
					<a class="button right" href="#" onclick="history.go(-1);return true;">返回...</a><a class="button right" href="/">首页</a>
        <div class="clear"></div>
      </div>';
		else
		{
			$this->load->database();
			$sq=md5($_POST['email'].$_SERVER['REQUEST_TIME'].$id[0].$id[1]);
			$this->db->insert("getpwd",array("sesid"=>$sq,"time"=>$_SERVER['REQUEST_TIME'],"username"=>$_POST['username']));
			$this->load->library("email");
			$this->email->from('kinwyb@163.com','名品渔具');
			$this->email->to($_POST['email']);
			$this->email->subject("名品渔具-密码找回");
			$this->email->message("亲爱的用户：\r\n您好！\r\n您在".date("Y年m月d日 H:i:s",$_SERVER['REQUEST_TIME'])."提交了邮箱找回密码请求，请点击下面的链接修改密码。\r\n http://localhost/login/pwd_reg/email/".$sq."
(如果您无法点击此链接，请将它复制到浏览器地址栏后访问) \r\n 为了保证您帐号的安全，该链接有效期为2小时，并且点击一次后失效！\r\n ".date("Y年m月d日",$_SERVER['REQUEST_TIME']));
			if($this->email->send())
				$data['info']='<h2>找回密码-操作成功</h2>
      <p>系统已向你的邮箱发送了一份找回密码的邮件！请你在2小时内点击邮件内的连接修改你的密码。超时后连接将失效</p>
					<div class="utilities">
					<a class="button right" href="/">首页</a>
        <div class="clear"></div>
      </div>';
			else
				$data['info']='<h2>找回密码-邮件发送失败</h2>
      <p>系统发送邮件失败，请联系管理员！</p>
					<div class="utilities">
					<a class="button right" href="/">首页</a>
        <div class="clear"></div>
      </div>';
		}
		$this->load->view("get_pwd",$data);
	}
	
	/**
	 * 重置密码
	 * @param unknown $type
	 * @param unknown $sid
	 */
	function pwd_reg($type,$sid)
	{
		$this->load->database();
		if($type=='email' && !empty($sid))
		{
			$row=$this->db->where("sesid",$sid)->get("getpwd")->row_array();
			if(($_SERVER['REQUEST_TIME']-$row['time']) > 7200000)
			{
				$data['info']='<h2>找回密码-连接超时</h2>
						<p>该连接已经失效！</p>
						<div class="utilities">
					<a class="button right" href="/login/get_pwd">重新申请找回密码</a><a class="button right" href="/">首页</a>
        <div class="clear"></div>
      </div>';
				$this->db->delete("getpwd",array("sesid"=>$sid));
			}
			else
				$data['info']='<h2>找回密码-修改密码</h2>
      <p>填写你的新密码</p>
				<div class="utilities">
        <form method="post" action="/login/pwd_reg/cheg/'.$sid.'">
          <div class="input-container">
				<input class="left" name="password1" id="search" placeholder="新密码" type="text"><br />&nbsp;<br />
              <input class="left" name="password2" id="search" placeholder="重复新密码" type="text">
          </div>
           <INPUT class="button right" type="submit" value="提交" />
        </form>
        <div class="clear"></div>
      </div>';
		}elseif(!empty($sid) AND $type == 'cheg')
		{
			$this->input->post(NULL,TRUE);
			if($_POST['password1'] != $_POST['password2'])
				$data['info']='<h2>找回密码-修改密码</h2>
      <p>两次密码不一致请重新填写！</p>
				<div class="utilities">
        <form method="post" action="/login/pwd_reg/cheg/'.$sid.'">
          <div class="input-container">
				<input class="left" name="password1" id="search" placeholder="新密码" type="text"><br />&nbsp;<br />
              <input class="left" name="password2" id="search" placeholder="重复新密码" type="text">
          </div>
           <INPUT class="button right" type="submit" value="提交" />
        </form>
        <div class="clear"></div>
      </div>';
			else
			{
				$row=$this->db->where("sesid",$sid)->get("getpwd")->row_array();
				$row=$this->ucapi->uc_user_edit($row['username'],$_POST['password1']);
				if($row == 1)
				{
					$data['info']='<h2>找回密码-密码修改成功</h2>
						<p>密码已经成功修改！</p>
						<div class="utilities">
					<a class="button right" href="/">首页</a>
        <div class="clear"></div>
      </div>';
					$this->db->delete("getpwd",array("sesid"=>$sid));
				}
				else
					$data['info']='<h2>找回密码-密码修改失败</h2>
						<p>密码修改失败！或者没有修改任何信息</p>
						<div class="utilities">
					<a class="button right" href="/">首页</a>
        <div class="clear"></div>
      </div>';
			}
		}
		$this->load->view("get_pwd",$data);
	}
	
	/**
	 * 检测权限
	 */
	function role()
	{
		$this->load->library("session");
		$role=$this->session->userdata("userrole");
		if(!empty($role))
			echo true;
		else
			echo false;
	}
}