<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Loader2 extends CI_Loader
{
    
	/**
	 * Constructor
	 *
	 * Sets the path to the view files and gets the initial output buffering level
	 */
	public function __construct()
	{
		parent::__construct();
	
		log_message('debug', "MY_Loader Class Initialized");
	}
	
	/**
	 * Module Loader
	 *
	 * This function lets users load and instantiate module.
	 *
	 * @access  public
	 * @param   string  the module uri of the module
	 * @return  void
	 */
	function module($module_uri, $vars = array(), $return = FALSE)
	{
		if ($module_uri == '')
		{
			return;
		}
	
		$module_uri = trim($module_uri, '/');
	
		$CI =& get_instance();
	
		$default_controller = $CI->router->default_controller;
	
		if (strpos($module_uri, '/') === FALSE)
		{
			$path = '';
			// 只有模块名，使用默认控制器和默认方法
			$module = $module_uri;
			$controller = $default_controller;
			$method = 'index';
			$segments = array();
		}
		else
		{
			$segments = explode('/', $module_uri);
	
			if (file_exists(APPPATH.'modules/'.$segments[0].'/controllers/'.$segments[1].EXT))
			{
				$path = '';
				$module = $segments[0];
				$controller = $segments[1];
				$method = isset($segments[2]) ? $segments[2] : 'index';
			}
			// 子目录下有模块？
			elseif (is_dir(APPPATH.'modules/'.$segments[0].'/'.$segments[1].'/controllers'))
			{
				// Set the directory and remove it from the segment array
				$path = $segments[0];
				$segments = array_slice($segments, 1);
	
				if (count($segments) > 0)
				{
					// 子目录下有模块？
					if (is_dir(APPPATH.'modules/'.$path.'/'.$segments[0].'/controllers'))
					{
						$module = $segments[0];
						$controller = isset($segments[1]) ? $segments[1] : $default_controller;
						$method = isset($segments[2]) ? $segments[2] : 'index';
					}
				}
				else
				{
					show_error('Unable to locate the module you have specified: '.$path);
				}
			}
			else
			{
				show_error('Unable to locate the module you have specified: '.$module_uri);
			}
	
			if ($path != '')
			{
				$path = rtrim($path, '/') . '/';
			}
		}
	
		// 模块名全部小写
		$module = strtolower($module);
	
		// 必须是类似这样的模块类名：目录_模块名_控制器名_module (如：Account_Message_Home_module)
		$c = str_replace(' ', '_', ucwords(str_replace('_', ' ', $controller)));
		$class_name = str_replace(' ', '_', ucwords(str_replace('/', ' ', $path.$module.' '.$c))) . '_module';
	
		// Module 的控制器文件的路径
		$controller_path = APPPATH.'modules/'.$path.$module.'/controllers/'.$controller.EXT;
	
		if ( ! file_exists($controller_path))
		{
			show_error('Unable to locate the module you have specified: '.$path.$module.'/controllers/'.$controller.EXT);
		}
	
		if ( ! class_exists('CI_Module'))
		{
			require_once(APPPATH.'core/Module'.EXT);
		}
	
		if (!isset($CI->$class_name))
		{
			// 装载 Module 控制器文件
			require_once($controller_path);
	
			// 实例化 Module 控制器
			$CI->$class_name = new $class_name();

		}
	
		if (strncmp($method, '_', 1) != 0 && in_array(strtolower($method), array_map('strtolower', get_class_methods($class_name))))
		{
			ob_start();
	
			log_message('debug', 'Module call: '.$class_name.'->'.$method);
	
			// Call the requested method.
			// Any URI segments present (besides the class/function) will be passed to the method for convenience
			$output = call_user_func_array(array($CI->$class_name, $method), $CI->load->_ci_object_to_array($vars));
			if ($return === TRUE)
			{
				$buffer = ob_get_contents();
				@ob_end_clean();
	
				$result = ($output) ? $output : $buffer;
	
				return $result;
			}
			else
			{
				if (ob_get_level() > $this->_ci_ob_level + 1)
				{
					ob_end_flush();
				}
				else
				{
					$buffer = ob_get_contents();
					$result = ($output) ? $output : $buffer;
					$CI->output->append_output($result);
					@ob_end_clean();
				}
			}
		}
		else
		{
			show_error('Unable to locate the '.$method.' method you have specified: '.$class_name);
		}
	}
	
	/**
	 * Model Loader
	 *
	 * This function lets users load and instantiate models.
	 *
	 * @access  public
	 * @param   string  the name of the class
	 * @param   string  name for the model
	 * @param   bool    database connection
	 * @return  void
	 */
	function model($model, $name = '', $db_conn = FALSE)
	{
		if (is_array($model))
		{
			foreach ($model as $babe)
			{
				$this->model($babe);
			}
			return;
		}
	
		if ($model == '')
		{
			return;
		}
	
		$path = '';
	
		// Is the model in a sub-folder? If so, parse out the filename and path.
		if (($last_slash = strrpos($model, '/')) !== FALSE)
		{
			// The path is in front of the last slash
			$path = substr($model, 0, $last_slash + 1);
	
			// And the model name behind it
			$model = substr($model, $last_slash + 1);
		}
	
		if ($name == '')
		{
			$name = $model;
		}
	
		if (in_array($name, $this->_ci_models, TRUE))
		{
			return;
		}
	
		$CI =& get_instance();
	
		$model_paths = $this->_ci_model_paths;
		//echo $this->_ci_module_class;
		exit();
		if ($this->_ci_is_inside_module)
		{
			$module_class_name = $this->_ci_module_class;
			array_unshift($model_paths, APPPATH.'modules/'.$this->_ci_module_path.'/');
			$module_model_name = str_replace(' ', '_', ucwords(str_replace('/', ' ', $this->_ci_module_path.' '.$model)));
			if (isset($CI->$module_class_name->$name))
			{
				show_error('The model name you are loading is the name of a resource that is already being used: '.$module_class_name.'.'.$module_model_name);
			}
		}
		else
		{
			if (isset($CI->$name))
			{
				show_error('The model name you are loading is the name of a resource that is already being used: '.$name);
			}
		}
	
		$model = strtolower($model);
	
		foreach ($model_paths as $key=>$mod_path)
		{
			if ( ! file_exists($mod_path.'models/'.$path.$model.'.php'))
			{
				continue;
			}
	
			if ($db_conn !== FALSE AND ! class_exists('CI_DB'))
			{
				if ($db_conn === TRUE)
				{
					$db_conn = '';
				}
	
				$CI->load->database($db_conn, FALSE, TRUE);
			}
	
			if ( ! class_exists('CI_Model'))
			{
				load_class('Model', 'core');
			}
	
			require_once($mod_path.'models/'.$path.$model.'.php');
	
			$model = ucfirst($model);
	
			if ($this->_ci_is_inside_module)
			{
				// 一定要放到全局 loader 实例中，否则还是无法查询模型属于哪个模块
				$CI->_ci_module_models[$module_model_name] = $module_class_name;
	
				if ($key == 0)
				{
					$CI->$module_class_name->$name = new $module_model_name();
				}
				else
				{
					$CI->$module_class_name->$name = new $model();
				}
			}
			else
			{
				$CI->$name = new $model();
			}
	
			$this->_ci_models[] = $name;
			return;
		}
	
		// couldn't find the model
		show_error('Unable to locate the model you have specified: '.$model);
	}
	
	
	
	/**
	 * Initialize the Loader
	 *
	 * This method is called once in CI_Controller.
	 *
	 * @param 	array
	 * @return 	object
	 */
	public function initialize()
	{
		$this->_ci_classes = array();
		$this->_ci_loaded_files = array();
		$this->_ci_models = array();
		$this->_base_classes =& is_loaded();
	
		$this->_ci_autoloader();
	
		return $this;
	}
	
	/**
	 * Autoloader
	 *
	 * The config/autoload.php file contains an array that permits sub-systems,
	 * libraries, and helpers to be loaded automatically.
	 *
	 * @access  private
	 * @param   array
	 * @return  void
	 */
	private function _ci_autoloader()
	{
		if (defined('ENVIRONMENT') AND file_exists(APPPATH.'config/'.ENVIRONMENT.'/autoload.php'))
		{
			include_once(APPPATH.'config/'.ENVIRONMENT.'/autoload.php');
		}
		else
		{
			include_once(APPPATH.'config/autoload.php');
		}
	
	
		if ( ! isset($autoload))
		{
			return FALSE;
		}
	
		// Autoload packages
		if (isset($autoload['packages']))
		{
			foreach ($autoload['packages'] as $package_path)
			{
				$this->add_package_path($package_path);
			}
		}
	
		// Load any custom config file
		if (count($autoload['config']) > 0)
		{
			$CI = &get_instance();
			foreach ($autoload['config'] as $key => $val)
			{
				$CI->config->load($val);
			}
		}
	
		// Autoload helpers and languages
		foreach (array('helper', 'language') as $type)
		{
			if (isset($autoload[$type]) AND count($autoload[$type]) > 0)
			{
				$this->$type($autoload[$type]);
			}
		}
	
		// A little tweak to remain backward compatible
		// The $autoload['core'] item was deprecated
		if ( ! isset($autoload['libraries']) AND isset($autoload['core']))
		{
			$autoload['libraries'] = $autoload['core'];
		}
	
		// Load libraries
		if (isset($autoload['libraries']) AND count($autoload['libraries']) > 0)
		{
			// Load the database driver.
			if (in_array('database', $autoload['libraries']))
			{
				$this->database();
				$autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
			}
	
			// Load all other libraries
			foreach ($autoload['libraries'] as $item)
			{
				$this->library($item);
			}
	
			$this->_ci_autoload_libraries = $autoload['libraries'];
		}
	
		// Autoload models
		if (isset($autoload['model']))
		{
			$this->model($autoload['model']);
	
			$this->_ci_autoload_models = $autoload['model'];
		}
	}
	
}