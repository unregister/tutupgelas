<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH."third_party/MX/Controller.php";

class MY_Controller extends MX_Controller
{
	var $adodb;
	var $debug = FALSE;
	var $db_var = FALSE;
	#var $_config;
	var $system;
	var $css_link;
	var $js_link;
	var $crud;
	var $icon;
	var $breadcrumb_trail;
	
	public function __construct(){
		parent::__construct();	
		include_once(APPPATH."libraries/Grocery_CRUD.php");
		
		$this->load->library('DX_Auth');
		
		$this->load->database();
		$this->get_config();
		$this->set_icon();
		$this->output->enable_profiler(false);
		
		# INIT LICENSI
		//$this->license();
	}
	
	protected function license()
	{		
		if( !$this->config->item('license_key') )
		{ 
			die('Need license code!!!!') ;
		}
		else
		{	
			$hostname = str_replace("www.","",$_SERVER['HTTP_HOST']);
			$decrypt = $this->decrypt( $this->config->item('license_key') );
			if( $decrypt != $hostname )
			{
				die("You don't have license to use this code.");
			}
		}
	}
	
	protected function get_config()
	{
		$result = $this->db->query("SELECT * FROM sys_site_config WHERE 1") -> row();		
		foreach((array)$result as $key=>$val)
		{
			(object)$this->config->$key = $val;	
		}
		
		
		$public_config = $this->db->get("sys_config") -> result_array();
				
		foreach((array)$public_config as $config){
			(object)$this->config->$config['name'] = $config['value'];
		}
		
	}
	
	function encrypt($plain_text, $password = 'OuzsPh0dq8', $iv_len = 16)
	{
	   $plain_text .= "\2008A";
	   $n = strlen($plain_text);
	   if ($n % 16) $plain_text .= str_repeat("\0", 16 - ($n % 16));
	   $i = 0;
	   $enc_text = $this->getRandomCode($iv_len);
	   $iv = substr($password ^ $enc_text, 0, 512);
	   while ($i < $n) {
		   $block = substr($plain_text, $i, 16) ^ pack('H*', md5($iv));
		   $enc_text .= $block;
		   $iv = substr($block . $iv, 0, 512) ^ $password;
		   $i += 16;
	   }
	   return base64_encode($enc_text);
	}
	
	
	function decrypt($enc_text, $password = 'OuzsPh0dq8', $iv_len = 16)
	{
	   $enc_text = base64_decode($enc_text);
	   $n = strlen($enc_text);
	   $i = $iv_len;
	   $plain_text = '';
	   $iv = substr($password ^ substr($enc_text, 0, $iv_len), 0, 512);
	   while ($i < $n) {
		   $block = substr($enc_text, $i, 16);
		   $plain_text .= $block ^ pack('H*', md5($iv));
		   $iv = substr($block . $iv, 0, 512) ^ $password;
		   $i += 16;
	   }
	   return preg_replace('/\\2008A\\x00*$/', '', $plain_text);
	}
	
	function getRandomCode($iv_len)
	{
	   $iv = '';
	   while ($iv_len-- > 0) {
		   $iv .= chr(mt_rand() & 0xff);
	   }
	   return $iv;
	}
	
	function set_icon()
	{
		$this->load->config('glyphicon');
		$this->load->config('font_awesome');
		
		$glyphicon = $this->config->item('glyphicon');
		$fontawesome = $this->config->item('fa');
		
		foreach((array)$glyphicon as $key=>$val){
			$this->icon->glyphicon[$key] = "<i class=\"glyphicon $val\"></i>";	
		}
		
		foreach((array)$fontawesome as $k=>$v)
		{
			$this->icon->fa[$k] = "<i class=\"fa $v\"></i>";	
		}
	}
	
}

class Admin_Controller extends MY_Controller
{
	static $layout;
	public $user;
	public $crud;

	public $is_root;
	
	public function __construct(){
		parent::__construct();
		
		$libraries = array('DX_Auth','Grocery_CRUD','Meta');
		$this->load->library( $libraries );
		$this->load->helper('access');
		
		if( !$this->dx_auth->is_logged_in() ){
			$msg = error("Silahkan login terlebih dahulu.");

			$redirect = null;
			$uristring = $this->uri->uri_string();
			if( $uristring ){
				$redirect = '?redirect='.$uristring;
			}
			set_msg( $msg );
			redirect( base_url() . "admin".$redirect );
			exit();
		}
		
		if( !check_user('root',false) ){
			redirect( base_url() );
			exit();
		}
		
		if( $this->session->userdata['DX_role_id'] == 1 ){
			$this->is_root = TRUE;
		}

		$this->theme_name = "admin";
		
		$this->layout 			= "../../themes/{$this->theme_name}/layout_main.php";
		$this->layout_content   = "../../themes/{$this->theme_name}/layout_content.php";
		$this->layout_crud   	= "../../themes/{$this->theme_name}/layout_form.php";
		$this->theme_url 		= _URL . "themes/{$this->theme_name}/";
		$this->theme_path 		= _ROOT . "themes/{$this->theme_name}/";
		$this->asset_url 		= _URL ."assets/";
		
		$this->get_session();
		
		$this->meta->add_breadcrumb('Home', 'home/admin/dashboard' );
		$this->meta->is_admin = true;
		
		$this->crud = new grocery_CRUD();
		$this->crud->unset_jquery();
		$this->crud->unset_print();
		$this->crud->unset_export();
		$this->crud->set_theme('twitter-bootstrap');
	}
	
	protected function get_session()
	{
		$arr = array();
		if( $this->session->userdata )
		{		
			
			$arr['user_id'] 	= $this->session->userdata['DX_user_id'];
			$arr['username'] 	= $this->session->userdata['DX_username'];
			$arr['group_id'] 	= $this->session->userdata['DX_role_id'];
			$arr['group_name'] 	= $this->session->userdata['DX_role_name'];
			
			$run = $this->db->query("SELECT `name`,gender,email,banned,image FROM sys_users WHERE id = ".$arr['user_id']) -> row();
			foreach($run as $key=>$val)
			{
				$arr[$key] = $val;	
			}
						
			foreach($arr as $key=>$val){
				@$this->user->$key = $val;
			}
		}
	}
	
	function dtreeArrayMenu( $parent_id = 0 )
	{
		$data = array();
		$query = "SELECT id,title,url,parent_id FROM sys_menu WHERE parent_id = $parent_id AND is_admin = 1 AND active = 1 ORDER BY orderby ASC";
		$result = $this->adodb->GetAll($query);
		if( count($result) > 0 )
		{
			foreach($result as $row)
			{
				$r['id'] 		= $row['id'];
				$r['parent_id'] = $row['parent_id'];
				$r['title'] 	= $row['title'];
				$r['url'] 		= $row['url'];
				$data[] = $r;
			}
		}
		return $data;
	}
	
	function arr_menu( $parent_id = 0 )
	{
		$data = array();
		$query = "SELECT id,title,url,parent_id FROM sys_menu WHERE parent_id = $parent_id AND is_admin = 1 AND active = 1 ORDER BY orderby ASC";
		$result = $this->adodb->GetAll($query);
		if( count($result) > 0 )
		{
			foreach($result as $row)
			{
				$r['id'] 		= $row['id'];
				$r['parent_id'] = $row['parent_id'];
				$r['title'] 	= $row['title'];
				$r['url'] 		= $row['url'];
				$r['child'] 	= $this->arr_menu($row['id']);
				$data[] = $r;
			}
		}
		return $data;
	}
	
	function html_menu( $arr, $deep = 0 )
	{
		if( $arr )
		{
			$out  = "";
			$out .=	"<ul id=\"tree\">\n";
			
			foreach((array)$arr as $row)
			{
				$out .= "<li>\n";
				$out .= "<a href=\"$row[url]\">$row[title]</a>\n";
				if( isset($row['child']) and count($row['child']) > 0 )
				{
					$out .= $this->html_menu($row['child'],$deep+1);	
				}
				$out .= "</li>\n";	
			}
			
			$out .= "</ul>\n";
			return $out;
		}
	}
	
	function get_menu()
	{
		$arr = $this->arr_menu(0);
		return $this->html_menu($arr);	
	}
	
	function view( $arr )
	{
		return $this->load->view($this->layout_content, $arr);	
	}
	
}


class Public_Controller extends MY_Controller
{
	var $is_home;
	var $theme_name;
	var $layout;
	var $layout_content;
	var $layout_blocks;
	var $theme_url;
	var $theme_admin_url;
	var $theme_path;
	var $breadcrumb_trail;
	
	var $meta_data;
	var $THEMEVARS; 

	
	function __construct()
	{
		parent::__construct();
		
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));		
		
		$this->load->library('Meta');
		$this->load->library('Twig');
		$this->load->model('site_model');
					
		$this->meta->add_breadcrumb('Home', base_url() );
		
		$this->init_module();
		$this->init();
		$this->init_model();
		$this->init_variable();
		
		$this->init_menu();
		
	}
	
	protected function init()
	{
		$this->is_home = FALSE;		
		if( !$this->uri->segment(1) )
		{
			$this->is_home = TRUE;	
		}
		
		if( $this->uri->segment(1) == 'home' )
		{
			if( !$this->uri->segment(2) || 
				$this->uri->segment(2) == 'index' || 
				$this->uri->segment(2) == 'main' )
			{
				$this->is_home = TRUE;	
			}
		}
		
		
		
		switch($this->meta->sys_config->site_status)
		{
			case 'online':
				$themename = $this->meta->current_theme();				
				$layout = "layout.html";
			break;
			
			case 'offline':
				$themename = "default";
				$layout = "offline.html";
			break;
			
			case 'maintenance':
				$themename = "default";
				$layout = "maintenance.html";
			break;
			
			case 'suspend':
				$themename = "default";
				$layout = "suspend.html";
			break;
			
			default:
				$themename = $this->meta->current_theme();
				$layout = "layout.html";
			break;
		}
		
		
		
		$this->theme_url 		= _URL . "themes/$themename/";
		$this->theme_path 		= _ROOT . "themes/$themename/";
		$this->theme_admin_url 	= _URL . "themes/admin/";
		
		$this->layout			= $layout;
		$this->layout_content	= "templates/";
		$this->layout_blocks	= "blocks/";
		
		# Enabling db cache
		$this->db->cache_on();
	}
	
	protected function init_variable()
	{
		$this->THEMEVARS = array();
		$this->THEMEVARS['is_home'] 	= $this->is_home;
		$this->THEMEVARS['base_url'] 	= _URL;
		$this->THEMEVARS['theme_url'] 	= $this->theme_url;
		$this->THEMEVARS['logo_url'] 	= $this->meta->logo();
		$this->THEMEVARS['config'] 		= (array)$this->meta->sys_config;
		$this->THEMEVARS['meta'] 		= $this->meta->get_meta();
		$this->THEMEVARS['breadcrumb'] 	= $this->meta->breadcrumb_trail;
		$this->THEMEVARS['copyright'] 	= str_replace("[year]",date('Y'),$this->meta->sys_config->copyright);
		$this->THEMEVARS['notification'] 	= get_msg( true );
		
		$this->init_blocks();

		if( isset($_GET['_d_x_']) ){
			pr( $this->THEMEVARS ) ;
				die;
		}
			
	}
	
	protected function init_menu()
	{
		$position = array('top'			=>'top',
						  'topmiddle'	=>'top-middle',
						  'bottom'		=>'bottom',
						  'bottommiddle'=>'bottom-middle',
						  'left'		=>'left',
						  'right'		=>'right');
		foreach((array)$position as $pos=>$value)
		{
			$this->THEMEVARS['menu'][$pos] = $this->meta->array_menu(0,$value);	
		}
	}
	
	protected function init_model()
	{
		$all_modules = $this->meta->get_modules();
		foreach((array)$all_modules as $module)
		{
			$module_name = $module['name'];
			$model_name = $module_name . "_model";
			
			if( $module_name && file_exists(_ROOT."modules/$module_name/models/$model_name.php") )
			{
				$this->load->model($module_name."/".$model_name);	
			}
		}
	}
	
	protected function init_blocks()
	{
		if( is_dir(_ROOT."blocks") )
		{
			$blockpath = _ROOT."blocks";
			$scandir = scandir($blockpath);
			$not = array('.','..');
			
			if( $scandir )
			{
				foreach((array)$scandir as $index=>$blockname)
				{
					if( file_exists(_ROOT."blocks/$blockname/__blocks.php") ){
						include_once _ROOT."blocks/$blockname/__blocks.php";	
					}
				}
			}
		}
	}
	
	public function add_breadcrumb($name, $link='')
	{
		$this->meta->add_breadcrumb($name,$link);	
	}

	protected function init_module()
	{
		$this->meta->allowed_access_module();
	}

}