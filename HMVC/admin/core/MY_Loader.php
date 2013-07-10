<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package     CodeIgniter
 * @author      ExpressionEngine Dev Team
 * @copyright   Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license     http://codeigniter.com/user_guide/license.html
 * @link        http://codeigniter.com
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * 已扩展的 Loader 类库
 *
 * 此类库相对于原始 Loader 类库，主要是增加了对 HMVC 的支持
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @author      Hex
 * @category    HMVC
 * @link        http://codeigniter.org.cn/forums/thread-1319-1-2.html
 */
class MY_Loader extends CI_Loader {

    var $_ci_is_inside_module = false;  // 当前是否是 Module 里的 Loader
    var $_ci_module_path = '';  // 当前 Module 所在路径
    var $_ci_module_class = '';  // 当前 Module 的控制器名
    var $_ci_module_uri = '';   // 当前 Module 的调用 URI
    var $_ci_module_method = '';    // 当前 Module 执行的方法
    var $_ci_module_models = array();    // 用于通过模型类名反查属于哪个模块

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

    // Module 中的 Loader 类实例初始化时，自动调用此函数
    public function _ci_module_ready($class_path, $class_name)
    {
        $this->_ci_is_inside_module = true;
        $this->_ci_module_path = $class_path;
        $this->_ci_module_class = $class_name;

        $this->_ci_classes = array();
        $this->_ci_loaded_files = array();
        $this->_ci_models = array();
    }

    // --------------------------------------------------------------------

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

            // 注意：要操作模块里的 loader 类实例
            $CI->$class_name->load->_ci_module_path = $path.$module;
            $CI->$class_name->load->_ci_module_class = $class_name;

            $CI->$class_name->_ci_module_uri = $path.$module.'/'.$controller;
            $CI->$class_name->_ci_module_method = $method;
        }

        $module_load =& $CI->$class_name->load;

        if (strncmp($method, '_', 1) != 0 && in_array(strtolower($method), array_map('strtolower', get_class_methods($class_name))))
        {
            ob_start();

            log_message('debug', 'Module call: '.$class_name.'->'.$method);

            // Call the requested method.
            // Any URI segments present (besides the class/function) will be passed to the method for convenience
            $output = call_user_func_array(array($CI->$class_name, $method), $module_load->_ci_object_to_array($vars));

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

    // --------------------------------------------------------------------

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
                $CI->load->_ci_module_models[$module_model_name] = $module_class_name;

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

    // --------------------------------------------------------------------

    /**
     * Load View
     *
     * This function is used to load a "view" file.  It has three parameters:
     *
     * 1. The name of the "view" file to be included.
     * 2. An associative array of data to be extracted for use in the view.
     * 3. TRUE/FALSE - whether to return the data or load it.  In
     * some cases it's advantageous to be able to return data so that
     * a developer can process it in some way.
     *
     * @access  public
     * @param   string
     * @param   array
     * @param   bool
     * @return  void
     */
    function view($view, $vars = array(), $return = FALSE)
    {
        if ($this->_ci_is_inside_module)
        {
            $ext = pathinfo($view, PATHINFO_EXTENSION);
            $view = ($ext == '') ? $view.EXT : $view;
            $path = APPPATH.'modules/'.$this->_ci_module_path.'/views/'.$view;

            if (file_exists($path))
            {
                return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_path' => $path, '_ci_return' => $return));
            }
            else
            {
                return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
            }
        }
        else
        {
            return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
        }
    }

    // --------------------------------------------------------------------

    /**
     * 取当前 Module 某方法的 URL 地址
     *
     * @access  public
     * @param   string  方法名/参数1/.../参数n
     * @param   string  URL 中要替换的控制器名，为空使用当前控制器名
     * @return  string
     */
    function module_url($uri, $controller_name = '')
    {
        $CI =& get_instance();
        $class = $this->_ci_module_class;

        $module_uri = trim($CI->$class->_ci_module_uri, '/');

        if (!empty($controller_name))
        {
            $arr = explode('/', $module_uri);
            $arr[count($arr) - 1] = str_replace(array('/', '.'), '', $controller_name);
            $module_uri = implode('/', $arr);
        }

        return site_url('module/' . $module_uri . '/' . trim($uri, '/'));
    }

    // --------------------------------------------------------------------
    
    /**
     * Driver
     *
     * Loads a driver library
     *
     * @param	string	the name of the class
     * @param	mixed	the optional parameters
     * @param	string	an optional object name
     * @return	void
     */
    public function driver($library = '', $params = NULL, $object_name = NULL)
    {
    	$CI= & get_instance();
    	
    	if(isset($CI->$library))
    		return $CI->$library;
    	
    	if ( ! class_exists('CI_Driver_Library'))
    	{
    		// we aren't instantiating an object here, that'll be done by the Library itself
    		require BASEPATH.'libraries/Driver.php';
    	}
    
    	if ($library == '')
    	{
    		return FALSE;
    	}
    
    	// We can save the loader some time since Drivers will *always* be in a subfolder,
    	// and typically identically named to the library
    	if ( ! strpos($library, '/'))
    	{
    		$library = ucfirst($library).'/'.$library;
    	}
    
    	return $this->library($library, $params, $object_name);
    }

    // 获取 _base_classes 属性
    public function get_base_classes()
    {
        return $this->_base_classes;
    }
}

/* End of file MY_Loader.php */
/* Location: ./application/core/MY_Loader.php */
