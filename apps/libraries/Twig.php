<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Twig
{
	private $CI;
	private $_twig;
	private $_template_dir;
	private $_cache_dir;

	/**
	* Constructor
	*
	*/
	function __construct($debug = false)
	{
		$this->CI =& get_instance();
		$this->CI->config->load('twig');
		
		$config = $this->CI->db->query("SELECT value FROM sys_config WHERE `name` = 'theme' LIMIT 1") -> row();
		$config->value = (empty($config->value))?'default':$config->value;

		$sitestatus = $this->CI->meta->sys_config->site_status;
		switch($sitestatus)
		{
			case 'online':
				$config->value = $this->CI->meta->current_theme();				
			break;
			
			case 'offline':
				$config->value = "default";
			break;
			
			case 'maintenance':
				$config->value = "default";
			break;
			
			case 'suspend':
				$config->value = "default";
			break;
			
			default:
				$config->value = $this->CI->meta->current_theme();
			break;
		}

		ini_set('include_path',
		ini_get('include_path') . PATH_SEPARATOR . APPPATH . 'libraries/Twig');
		require_once (string) "Autoloader" . EXT;
	
		log_message('debug', "Twig Autoloader Loaded");
	
		Twig_Autoloader::register();
	
		$this->_template_dir = $this->CI->config->item('template_dir') . $config->value . "/";
		$this->_cache_dir = $this->CI->config->item('cache_dir');

		$loader = new Twig_Loader_Filesystem($this->_template_dir);
	
		$this->_twig = new Twig_Environment($loader, array(
				'cache' => $this->_cache_dir,
				'auto_reload' => true,
				'strict_variables'=>false,
				'debug' => true,
				'autoescape' => false
				//'debug' => $debug,
		));

		$this->_twig->addExtension(new Twig_Extension_Debug());
		$this->_twig->addExtension(new Twig_Extension_Escaper('html'));
		$this->_twig->addExtension(new Twig_Extension_Optimizer());
		$this->_twig->addFilter( new Twig_SimpleFilter('asset_url', 'asset_url') );
		$this->_twig->addFilter( new Twig_SimpleFilter('theme_url', 'theme_url') );
		// enable all php function on twig
		foreach(get_defined_functions() as $functions) {
		  foreach($functions as $function) {
			  $this->_twig->addFunction($function, new Twig_Function_Function($function));
		  }
	 	}
		
		$arr_ci_function = array('base_url','site_url','url_title','asset_url');
		foreach($arr_ci_function as $ci_functions)
		{
			$this->_twig->addFunction($ci_functions, new Twig_Function_Function($ci_functions));
		}
		
		//$filter = new Twig_SimpleFilter('base_url', 'base_url');
		//$this->_twig->addFilter($filter);
		
	}

	public function add_function($name) 
	{
		$this->_twig->addFunction($name, new Twig_Function_Function($name));
	}

	public function render($template, $data = array()) 
	{
		$template = $this->_twig->loadTemplate($template);
		return $template->render($data);
	}

	public function display($template, $data = array()) 
	{
		$template = $this->_twig->loadTemplate($template);
		/* elapsed_time and memory_usage */
		$data['elapsed_time'] = $this->CI->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end');
		$memory = (!function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2) . 'MB';
		$data['memory_usage'] = $memory;
		$template->display($data);
	}
}