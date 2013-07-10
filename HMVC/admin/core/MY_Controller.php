<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->_check_login();
	}
	
	/**
	 * 检查登入
	 */
	protected function _check_login()
	{
		if(!$this->session->userdata('username'))
			redirect('login');
	}
	
}