<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class KL_Upload extends CI_Upload
{
	private $ci,$date_folder;
	
	function __construct($props = array()){
		parent::__construct($props);
		$this->ci = &get_instance();
	}
	
	/**
	 *  附加上上传文件地址
	 * !CodeTemplates.overridecomment.nonjd!
	 * @see CI_Upload::data()
	 */
	function data()
	{
		$upload=parent::data();
		$upload['dir_file_name']=$this->date_folder.$upload['file_name'];
		$upload['dir_file_name']=ltrim($upload['dir_file_name'],".");
		return $upload;
	}
	
	/**
	 * 重写参数载入函数
	 * Initialize preferences
	 *
	 * @param	array
	 * @return	void
	 */
	public function initialize($config = array())
	{
		$this->date_folder=rtrim($config['upload_path'],"/")."/".date("Y-m-d")."/";
		if(!file_exists($this->date_folder))
			mkdir($this->date_folder,0777);
		$config['upload_path']=$this->date_folder;
		parent::initialize($config);
	}
}