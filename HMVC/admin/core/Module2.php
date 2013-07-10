<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_Module
{
	var $_ci_is_inside_module = TRUE;
	var $_ci_module_path = '';  // 当前 Module 所在路径
    var $_ci_module_class = '';  // 当前 Module 的控制器名
	var $_ci_module_uri = '';   // 当前 Module 的调用 URI
	var $_ci_module_method = '';    // 当前 Module 执行的方法
	var $_ci_module_models = array();    // 用于通过模型类名反查属于哪个模块
	/**
	 * Constructor
	 *
	 * @access public
	 */
	function __construct()
	{
		$CI =& get_instance();
		// 利用 PHP5 的反射机制，动态确定 Module 类名和路径
		$reflector = new ReflectionClass($this);
	
		$this->_ci_module_path = substr(dirname($reflector->getFileName()), strlen(realpath(APPPATH.'modules').DIRECTORY_SEPARATOR));
		//$class_path = implode('/', array_slice(explode(DIRECTORY_SEPARATOR, $path), 0, -1));
		$class_name = $reflector->getName();
		$this->_ci_module_class = $class_name;
		
		$CI->$class_name = $this;
	echo $class_name;
		log_message('debug', $this->_ci_module_class." Module Class Initialized");
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
}