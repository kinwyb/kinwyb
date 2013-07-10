<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Public_Public_Made_module extends CI_Module
{
	/**
	 * 构造函数
	 *
	 * @return void
	 * @author
	 **/
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 信息提示
	 *
	 * @access  public
	 * @param   string
	 * @param   string
	 * @param   bool
	 * @param   string
	 * @return  void
	 */
	public function message($msg, $goto = '', $auto = TRUE, $fix = '')
	{
		if($goto == '')
		{
			$goto = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url();
		}
		else
		{
			$goto = strpos($goto, 'http') !== false ? $goto : $this->_backend_url($goto);
		}
		$goto .= $fix;
		$this->load->view('message', array('msg' => $msg, 'goto' => $goto, 'auto' => $auto));
		echo $this->output->get_output();
		exit();
	}
	
	/**
	 * URL跳转
	 * @param string $uri
	 * @param string $qs
	 * @return string
	 */
	private function _backend_url($uri = '', $qs = '')
	{
		return site_url('/' . $uri) . ($qs == '' ? '' : '?' . $qs);
	}
	
	/**
	 * 检测权限
	 * @param string $type
	 * @param string $role
	 * @param string $power
	 * @return void;
	 */
	public function power($type,$role,$power)
	{
		if(!$this->_ck_power($type,$role,$power))
			$this->message('你无权操作此栏目！');
		return ;
	}
	
	/**
	 * 检测权限
	 * @param string $type
	 * @param string $role
	 * @param string $power
	 * @return void;
	 */
	public function bopower($type,$role,$power)
	{
		return $this->_ck_power($type,$role,$power);
	}
	
	/**
	 * 检测权限
	 * @param string $type 操作类型(添删修)
	 * @param string $role 操作的栏目ID
	 * @param string $power 栏目类型
	 * @return boolean
	 */
	private function _ck_power($type,$role,$power)
	{
		$table=$this->session->userdata("userrole");
		if($table==15)
			return 1;
		$power=$this->session->userdata($power);
		if($power=='all')
			return 1;
		$table=explode(" ", $power);
		$power="";
		foreach($table as $value)
		{
			$value=explode(":",$value);
			if($value[0] == $role)
			{
				$power=$value[1];
				break;
			}
		}
		return $type&$power;
	}
	
	/**
	 * 创建缩略图
	 * @param unknown $img
	 */
	public function thumb($img,$type)
	{
		! $this->ts_key('image_lib') AND $this->load->library("image_lib");
		$config['image_library'] = 'gd2';
		$config['source_image'] = '..'.$img;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = 280;
		$config['height'] = 210;
		$image_size=getimagesize('..'.$img);
		if($image_size[0]>280 OR $image_size[1]>210)
		{
			empty($type) AND $config['create_thumb'] = TRUE;
			$this->image_lib->initialize($config); 
			if($this->image_lib->resize())
			{
				$img=$this->image_lib->ret_name();
				$img=preg_replace('/.*\/upload/', '/upload', $img);
				$img=preg_replace('/.*\/images/', '/images', $img);
				return $img;
			}
			else
				return $img;
		}
		else
			return $img;
	}
	
}