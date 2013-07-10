<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * 用户登入检测
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function userlogin($username,$password)
	{
		$row=$this->db->where('username',$username)->limit(1)->get("admin")->row();
		if(!isset($row->password) || $password !=$row->password)
			return 3;
		elseif($row->start == '2')
			return 2;
		else
		{
			$loginip=$this->get_ip();
			$this->db->where("uid",$row->uid)->update('admin',array("ip"=>$loginip));
			$this->session->set_userdata(array(
					'username'=>$row->username,
					'userrole'=>$row->role,
					'power'=>$row->power,
					'wpower'=>$row->wpower,
			));
			return 1;
		}
	}
	
	/**
	 * 获取客户端的IP
	 * @return string
	 */
	private function get_ip()
	{
		if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if(getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if(getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");
		else $ip = "Unknow";
		return $ip;
	}
	
}