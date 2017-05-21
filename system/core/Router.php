<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Router Class
 *
 * Parses URIs and determines routing
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/routing.html
 */
class CI_Router {

	/**
	 * CI_Config class object
	 *
	 * @var	object
	 */
	public $config;

	/**
	 * List of routes
	 *
	 * @var	array
	 */
	public $routes =	array();

	/**
	 * Current class name
	 *
	 * @var	string
	 */
	public $class =		'';

	/**
	 * Current method name
	 *
	 * @var	string
	 */
	public $method =	'index';

	/**
	 * Sub-directory that contains the requested controller class
	 *
	 * @var	string
	 */
	public $directory;

	/**
	 * Default controller (and method if specific)
	 *
	 * @var	string
	 */
	public $default_controller;

	/**
	 * Translate URI dashes
	 *
	 * Determines whether dashes in controller & method segments
	 * should be automatically replaced by underscores.
	 *
	 * @var	bool
	 */
	public $translate_uri_dashes = FALSE;

	/**
	 * Enable query strings flag
	 *
	 * Determines whether to use GET parameters or segment URIs
	 *
	 * @var	bool
	 */
	public $enable_query_strings = FALSE;

	// --------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * Runs the route mapping function.
	 *
	 * @param	array	$routing
	 * @return	void
	 */
	public function __construct($routing = NULL)
	{
		//加载内部的类
		$this->config =& load_class('Config', 'core');    //
		$this->uri =& load_class('URI', 'core');          //����URI��
		 //确认是否开启querystirng模式，如果这个模式开启,那就用index.php?c=mall&a=list这样去访问控制器和方法了 
		$this->enable_query_strings = ( ! is_cli() && $this->config->item('enable_query_strings') === TRUE);    //

		// If a directory override is configured, it has to be set before any dynamic routing logic
		//如果在index.php里指定控制器目录，那么在动态路由之前都将这个设置作为控制器的目录  
	    //通俗的说就是路由器在找控制器和方法时，会在“contrlloer/设置的目录/”下找  
	    //而且这个设置会覆盖URI(三段)的目录  
		is_array($routing) && isset($routing['directory']) && $this->set_directory($routing['directory']);
		$this->_set_routing();    //核心：解析URI到$this->directory、$this->class、$this->method  
		// Set any routing overrides that may exist in the main index file
		//如果在index.php中设置了控制器和方法，则覆盖  
	    //比如服务器维护时，设置一个方法用来显示“维护中”的静态页面，就可以让任何URI的请求都进入到该个方法中显示静态页面  
	    //我在想：应该把上面的$this->_set_routing();放到这个else块中就完美了  
		if (is_array($routing))
		{
			empty($routing['controller']) OR $this->set_class($routing['controller']);
			empty($routing['function'])   OR $this->set_method($routing['function']);
		}

		log_message('info', 'Router Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Set route mapping
	 *
	 * Determines what should be served based on the URI request,
	 * as well as any "routes" that have been set in the routing config file.
	 *
	 * @return	void
	 */
	protected function _set_routing()
	{
		// Load the routes.php file. It would be great if we could
		// skip this for enable_query_strings = TRUE, but then
		// default_controller would be empty ...
		//加载路由配置文件routes.php 
		if (file_exists(APPPATH.'config/routes.php'))
		{
			include(APPPATH.'config/routes.php');    
		}
		//如果有环境对应的配置文件，则加载并覆盖原配置文件routes.php  
		if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/routes.php'))  
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/routes.php');
		}

		// Validate & get reserved routes
		// Validate & get reserved routes  
	    //读取默认控制器设置$route['default_controller']  
	    //读取$route['translate_uri_dashes']。如果设置为TRUE，则可将URI中的破折号-转换成类名的下划线_  
	    //如my-controller/index    -> my_controller/index  
	    //读取所有自定义路由策略赋值给$this->routes  
		if (isset($route) && is_array($route))     
		{

			isset($route['default_controller']) && $this->default_controller = $route['default_controller'];
			isset($route['translate_uri_dashes']) && $this->translate_uri_dashes = $route['translate_uri_dashes'];
			unset($route['default_controller'], $route['translate_uri_dashes']);
			$this->routes = $route;       
		}

		// Are query strings enabled in the config file? Normally CI doesn't utilize query strings
		// since URI segments are more search-engine friendly, but they can optionally be used.
		// If this feature is enabled, we will gather the directory/class/method a little differently
		//在querystring模式下获取directory/class/method  
	    //index.php?d=admin&c=mall&m=list  
	    //$config['controller_trigger'] = 'c';//控制器变量  
	    //$config['function_trigger'] = 'm';//方法变量  
	    //$config['directory_trigger'] = 'd';//目录变量  
		if ($this->enable_query_strings)
		{
			// If the directory is set at this time, it means an override exists, so skip the checks
			//获取$this->directory。配置文件中的'directory_trigger'代表在$_GET中用什么变量名作为传递directory的键值  
        	//同样的还有设置控制器的传递参数键名controller_trigger，方法的传递参数键名function_trigger 
			if ( ! isset($this->directory))
			{
				$_d = $this->config->item('directory_trigger');
				$_d = isset($_GET[$_d]) ? trim($_GET[$_d], " \t\n\r\0\x0B/") : '';

				if ($_d !== '')
				{
					//filter_uri是验证uri的组成字符是否在白名单(配置文件中permitted_uri_chars设置)中
					$this->uri->filter_uri($_d);
					$this->set_directory($_d);
				}
			}
			//获取控制器和方法,并设置$this->uri->rsegments  
			$_c = trim($this->config->item('controller_trigger'));
			if ( ! empty($_GET[$_c]))
			{
				//filter_uri是验证uri的组成字符是否在白名单(配置文件中permitted_uri_chars设置)中
				$this->uri->filter_uri($_GET[$_c]);
				$this->set_class($_GET[$_c]);

				$_f = trim($this->config->item('function_trigger'));
				if ( ! empty($_GET[$_f]))
				{
					$this->uri->filter_uri($_GET[$_f]);
					$this->set_method($_GET[$_f]);
				}

				$this->uri->rsegments = array(
					1 => $this->class,
					2 => $this->method
				);
			}
			else
			{
				//方法没有可以允许，如果控制器都没有，就调用默认控制器和方法代替了 
				$this->_set_default_controller();
			}

			// Routing rules don't apply to query strings and we don't need to detect
			// directories, so we're done here
			return;
		}

		// Is there anything to parse?
		// 非querystring模式的程序可以走到这里  
		if ($this->uri->uri_string !== '')
		{
			//解析自定义路由规则，并调用_set_request函数设置目录、控制器、方法
			$this->_parse_routes();
		}
		else
		{
			//uri_string为空，一般情况下就是域名后面没有任何字符，调用默认控制器  
			$this->_set_default_controller();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Set request route
	 *
	 * Takes an array of URI segments as input and sets the class/method
	 * to be called.
	 *
	 * @used-by	CI_Router::_parse_routes()
	 * @param	array	$segments	URI segments
	 * @return	void
	 */
	protected function _set_request($segments = array())
	{
		//从$segments中提取Directory信息，设置$this->directory  
		$segments = $this->_validate_request($segments);
		// If we don't have any segments left - try the default controller;
		// WARNING: Directories get shifted out of the segments array!
		//如果$segments在目录被提取走后，没有剩下任何东西，那么就用默认路由
		if (empty($segments))
		{
			$this->_set_default_controller();
			return;
		}
		 //如果允许路径中破折号存在，也就是路径中破折号'-'映到至类名的下划线 '_'  
		if ($this->translate_uri_dashes === TRUE)
		{
			$segments[0] = str_replace('-', '_', $segments[0]);
			if (isset($segments[1]))
			{
				$segments[1] = str_replace('-', '_', $segments[1]);
			}
		}
		//设置控制器类 
		$this->set_class($segments[0]);
		if (isset($segments[1]))
		{
			//设置控制器类方法  
			$this->set_method($segments[1]);
		}
		else
		{
			//如果不存在方法片段，则默认方法名index
			$segments[1] = 'index';
		}
		  //将整个数组元素往后推一格，保持和没有shift掉目录时的数组原素存放序列一致，  
    //如array ( 0 => 'news', 1 => 'view', 2 => 'crm', )经过这两行后变成array ( 1 => 'news', 2 => 'view', 3 => 'crm', )  
    //不过要是多级目录的话，这样推有什么用呢？
		array_unshift($segments, NULL);
		unset($segments[0]);
		 //RTR->uri->rsegments用来存放路由转换后的片段，不含目录 
		$this->uri->rsegments = $segments;
	}

	// --------------------------------------------------------------------

	/**
	 * Set default controller
	 *
	 * @return	void
	 */
	protected function _set_default_controller()
	{
		if (empty($this->default_controller))
		{
			show_error('Unable to determine what should be displayed. A default route has not been specified in the routing file.');
		}

		// Is the method being specified?
		if (sscanf($this->default_controller, '%[^/]/%s', $class, $method) !== 2)
		{
			$method = 'index';
		}

		if ( ! file_exists(APPPATH.'controllers/'.$this->directory.ucfirst($class).'.php'))
		{
			// This will trigger 404 later
			return;
		}

		$this->set_class($class);
		$this->set_method($method);

		// Assign routed segments, index starting from 1
		$this->uri->rsegments = array(
			1 => $class,
			2 => $method
		);

		log_message('debug', 'No URI present. Default controller set.');
	}

	// --------------------------------------------------------------------

	/**
	 * Validate request
	 *
	 * Attempts validate the URI request and determine the controller path.
	 *
	 * @used-by	CI_Router::_set_request()
	 * @param	array	$segments	URI segments
	 * @return	mixed	URI segments
	 */
	protected function _validate_request($segments)
	{
		$c = count($segments);
		$directory_override = isset($this->directory);

		// Loop through our segments and return as soon as a controller
		// is found or when such a directory doesn't exist
		while ($c-- > 0)
		{
			$test = $this->directory
				.ucfirst($this->translate_uri_dashes === TRUE ? str_replace('-', '_', $segments[0]) : $segments[0]);

			if ( ! file_exists(APPPATH.'controllers/'.$test.'.php')
				&& $directory_override === FALSE
				&& is_dir(APPPATH.'controllers/'.$this->directory.$segments[0])
			)
			{
				$this->set_directory(array_shift($segments), TRUE);
				continue;
			}

			return $segments;
		}

		// This means that all segments were actually directories
		return $segments;
	}

	// --------------------------------------------------------------------

	/**
	 * Parse Routes
	 *
	 * Matches any routes that may exist in the config/routes.php file
	 * against the URI to determine if the class/method need to be remapped.
	 *
	 * @return	void
	 */
	protected function _parse_routes()
	{
		// Turn the segment array into a URI string
		//先将uri对像中的segments数组还原成uri路径。
		$uri = implode('/', $this->uri->segments);

		// Get HTTP verb
		//获取http请求动作
		$http_verb = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';

		// Loop through the route array looking for wildcards
		 //循环自定义路由规则，看是否能命中当前uri地址
		foreach ($this->routes as $key => $val)
		{
			// Check if route format is using HTTP verbs
			// Check if route format is using HTTP verbs  
        //(功能3、支持使用 HTTP 动词) 处理HTTP 动词  
  
        //可以在你的路由规则中使用 HTTP 动词（请求方法），就是在路由数组后面再加一个键，键名为 HTTP 动词。  
        //标准的 HTTP 动词（GET、PUT、POST、DELETE、PATCH）  
        //比如定义了：$route['admin/pages']['get'] = 'admin/pages/view/about';  
        //那么当命中这条时：key:'admin/pages', val:array ('get' => 'admin/pages/view/about', )  
			if (is_array($val))
			{
				$val = array_change_key_case($val, CASE_LOWER);
				if (isset($val[$http_verb]))
				{
					$val = $val[$http_verb];
				}
				else
				{
					continue;
				}
			}

			// Convert wildcards to RegEx
			 // Convert wildcards to RegEx  
        //把:any和:num转成正则  
			$key = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $key);
			//匹配路径信息 
			// Does the RegEx match?
			if (preg_match('#^'.$key.'$#', $uri, $matches))
			{
				// Are we using callbacks to process back-references?
				//(功能2、支持回调函数) 处理回调函数这种使用方法
				if ( ! is_string($val) && is_callable($val))
				{
					// Remove the original string from the matches array.
					//matches数组的第一个元素是能匹配上的完整字符串，所以先要把这个去掉，剩下的就是匹配上的括号中间表达式  
					array_shift($matches);

					// Execute the callback using the values in matches as its parameters.
					$val = call_user_func_array($val, $matches);
				}
				// Are we using the default routing method for back-references?
				// Execute the callback using the values in matches as its parameters.  
				 //(功能1、自定义路由规则) 处理通常的自定义路由 
				elseif (strpos($val, '$') !== FALSE && strpos($key, '(') !== FALSE)
				{
					//最核心的就是preg_replace这里了，不得不佩服正则函数的强大
					$val = preg_replace('#^'.$key.'$#', $val, $uri);
				}
				 //调用_set_request设置$this->directory、$this->class、$this->method  
            //参数是地址经过路由解析后再用'/'分割的数组
				$this->_set_request(explode('/', $val));
				return;
			}
		}

		// If we got this far it means we didn't encounter a
		// matching route so we'll set the site default route
	//如果程序执行到这里，说明没有匹配任何路由规则  
    //调用_set_request设置$this->directory、$this->class、$this->method  
		$this->_set_request(array_values($this->uri->segments));
	}

	// --------------------------------------------------------------------

	/**
	 * Set class name
	 *
	 * @param	string	$class	Class name
	 * @return	void
	 */
	public function set_class($class)
	{
		$this->class = str_replace(array('/', '.'), '', $class);
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch the current class
	 *
	 * @deprecated	3.0.0	Read the 'class' property instead
	 * @return	string
	 */
	public function fetch_class()
	{
		return $this->class;
	}

	// --------------------------------------------------------------------

	/**
	 * Set method name
	 *
	 * @param	string	$method	Method name
	 * @return	void
	 */
	public function set_method($method)
	{
		$this->method = $method;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch the current method
	 *
	 * @deprecated	3.0.0	Read the 'method' property instead
	 * @return	string
	 */
	public function fetch_method()
	{
		return $this->method;
	}

	// --------------------------------------------------------------------

	/**
	 * Set directory name
	 *
	 * @param	string	$dir	Directory name
	 * @param	bool	$append	Whether we're appending rather than setting the full value
	 * @return	void
	 */
	public function set_directory($dir, $append = FALSE)
	{
		if ($append !== TRUE OR empty($this->directory))
		{
			$this->directory = str_replace('.', '', trim($dir, '/')).'/';
		}
		else
		{
			$this->directory .= str_replace('.', '', trim($dir, '/')).'/';
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch directory
	 *
	 * Feches the sub-directory (if any) that contains the requested
	 * controller class.
	 *
	 * @deprecated	3.0.0	Read the 'directory' property instead
	 * @return	string
	 */
	public function fetch_directory()
	{
		return $this->directory;
	}

}
