<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_Module
{
	/**
	 * Constructor
	 *
	 * @access public
	 */
	function __construct()
	{
		$CI =& get_instance();
		$this->load=clone $CI->load;
		// 利用 PHP5 的反射机制，动态确定 Module 类名和路径
		$reflector = new ReflectionClass($this);
	
		$path = substr(dirname($reflector->getFileName()), strlen(realpath(APPPATH.'modules').DIRECTORY_SEPARATOR));
		$class_path = implode('/', array_slice(explode(DIRECTORY_SEPARATOR, $path), 0, -1));
		$class_name = $reflector->getName();
		// 通知 Loader 类，Module 就绪
		$this->load->_ci_module_ready($class_path, $class_name);
		// 把自己放到全局超级对象中

		$CI->$class_name = $this;
		
		log_message('debug', "$class_name Module Class Initialized");
	}
	
	/**
	 * 直接应用CI里的东西
	 * @param unknown $key
	 */
	function __get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}
	
	/**
	 * 检测是否在CI里初始化
	 * @param unknown $key
	 */
	function ts_key($key)
	{
		$CI= & get_instance();
		return isset($CI->$key);
	}
}