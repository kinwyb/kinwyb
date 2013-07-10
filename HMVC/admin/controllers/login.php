<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model("login_model",'login');
	}
	
	/**
	 * 判断是否登入？跳转进首页：跳转登入页
	 */
	public function index()
	{
		if($this->session->userdata('username'))
			redirect('index/feedback');
		else
			$this->load->view('login');
	}
	
	/**
	 * 登入检测
	 */
	public function checkuser()
	{
		$vcode=$this->input->post('vcode',TRUE);
		if($vcode != $_SESSION['code'])
			$this->load->module('public/public_made/message',array("验证码错误！",'login'));
		$username=$this->input->post('username',TRUE);
		$password=$this->input->post('password',TRUE);
		$password=substr(md5($password), 5, 20);
		$result=$this->login->userlogin($username,$password);
		switch ($result)
		{
			case 1: $this->load->module('public/public_made/message',array("登入成功！",'index/feedback'));
				break;
			case 2: $this->load->module('public/public_made/message',array("你的帐号已被管理员冻结！",'login'));
				break;
			case 3: $this->load->module('public/public_made/message',array("帐号或密码错误！",'login'));
				break;
			default: $this->load->module('public/public_made/message',array("出现未知错误！",'login'));
		}
	}
	
	/**
	 * 验证码
	 */
	function vcode()
	{
		$this->load->library('imgcode');
	}
	
	/**
	 * 退出
	 */
	public function login_out()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}