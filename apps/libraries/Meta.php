<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Meta
{
	var $obj;
	var $meta_seo	= array();
	var $js_link 	= array('all'=>array(), 'meta'=>array());
	var $css_link	= array('all'=>array(), 'meta'=>array());
	var $def_css;
	var $def_js;
	var $is_admin;
	var $module;
	var $breadcrumb_trail = array();
	var $sys_config;
	var $current_theme;
	var $arr_meta = array();
	var $is_custom_meta;
	
	public function __construct(){
		$this->obj =& get_instance();
		$this->init();			
	}
	
	public function init()
	{		
		#Get Site Config
		$result = $this->obj->db->query("SELECT * FROM sys_site_config WHERE 1") -> row();		
		unset($result->id);
		foreach((array)$result as $key=>$val)
		{
			(object)@$this->sys_config->$key = $val;	
		}
		
		$public_config = $this->obj->db->get("sys_config") -> result_array();				
		foreach((array)$public_config as $config)
		{
			(object)@$this->sys_config->$config['name'] = $config['value'];
		}
			
		
		#Init other config
		$this->current_theme = ( !empty($this->sys_config->theme) )?$this->sys_config->theme:'default';
		$this->is_custom_meta = false;

	}
	
	public function set_meta( $arr_meta )
	{	
		#Seo Standar
		$meta = array();		
		$site_name = ( isset($arr_meta['site_name']) && !empty($arr_meta['site_name']) )?$arr_meta['site_name']:$this->sys_config->site_name;
		$meta['site']['site_name']		= strip_tags( $site_name );
		$meta['site']['title'] 			= strip_tags( $arr_meta['title'] );
		$meta['site']['keyword'] 		= strip_tags( $arr_meta['keyword'] );
		$meta['site']['description'] 	= strip_tags( $arr_meta['description'] );
		
		#Facebook Og
		$meta['og']['title'] 			= strip_tags( $arr_meta['title'] );
		$meta['og']['url'] 				= _URL . $this->obj->uri->uri_string();
		$meta['og']['site_name'] 		= strip_tags( $site_name );
		$meta['og']['image'] 			= ( !isset($arr_meta['image']) or empty($arr_meta['image']) ) ? $this->logo() : $arr_meta['image'];
		$meta['og']['type'] 			= ( isset($arr_meta['type']) && !empty($arr_meta['type']) )?$arr_meta['type']:"article";
		$meta['og']['description'] 		= strip_tags( $arr_meta['description'] );
		return $meta;
	}
	
	public function get_meta()
	{			
		
		$url = $this->obj->uri->uri_string();
		$meta = $this->get_meta_menu($url);
		
		$title = ( $meta['title'] )?$meta['title']:strip_tags($this->sys_config->meta_title);
		$key = ( $meta['meta_keyword'] )?$meta['meta_keyword']:strip_tags($this->sys_config->meta_keyword);
		$desc = ( $meta['meta_description'] )?$meta['meta_description']:strip_tags($this->sys_config->meta_description);
		
		
		$meta = array();
		$meta['site_name']		= strip_tags($this->sys_config->site_name);
		$meta['title'] 			= $title;
		$meta['keyword'] 		= $key;
		$meta['description'] 	= $desc;
		$meta['url'] 			= _URL . $this->obj->uri->uri_string();
		$meta['image'] 			= $this->logo();
		$meta['type'] 			= ($this->sys_config->site_type)?$this->sys_config->site_type:"article";
		return $this->set_meta($meta);
	}
	
	public function get_meta_menu( $url )
	{
		if( $url )
		{
			$this->obj->db->select('title,meta_keyword,meta_description');
			$this->obj->db->where('url',$url);
			$this->obj->db->where('is_admin',0);
			$this->obj->db->where('is_content',0);
			$run = $this->obj->db->get('sys_menu');
			if( $run->num_rows() > 0 ){
				return $run->row_array();	
			}
		}
	}
	
	public function current_theme()
	{
		return $this->current_theme;	
	}
	
	public function link_repair($file)
	{
		$file = str_replace(_ROOT, '', $file);
		$file = str_replace(_URL, '', $file);
		$real_file = preg_replace('~(\?.*?)?$~is', '', $file);
		
		if( is_url($file) ) { 
			return $file;
		}elseif(is_file(_ROOT.$real_file)){
			return _URL.$file;
		}else{
			return false;
		}
	}
	
	public function get_modules()
	{
		$this->obj->db->where("is_installed",1);
		$this->obj->db->order_by("name","asc");
		$result = $this->obj->db->get("sys_modules");
		return $result->result_array();
	}
	
	public function get_admin_menu()
	{
		$allmodules = $this->get_modules();
		foreach((array)$allmodules as $module)
		{			
			if( is_file(_ROOT."modules/$module[name]/init/__menu.php") )
			{
				include_once _ROOT."modules/$module[name]/init/__menu.php";				
				
				if( !empty($admin_menu) )
				{
					echo $admin_menu[$module['name']];
				}
			}
		}
	}
	
	public function add_breadcrumb( $name, $link = "" )
	{
		if($name == NULL){
			return false;
		}		
		$this->breadcrumb_trail[$name] = $link;
	}
	
	public function breadcrumb( $separator = " &raquo; " )
	{
		$output = '';
		$i = 1;
		foreach ( $this->breadcrumb_trail as $name => $link )
		{
			if ( $i == count($this->breadcrumb_trail) )
			{
				$output .= "<li class=\"breadcrumb-current\"><a>$name</a></li>";
			}
			else
			{
				$output .= "<li>".anchor($link, $name)."<li>";
				//$output .= $separator;
			}
			$i++;
		}
		
		return $output;
	}
	
	public function css( $css, $is_meta = TRUE )
	{
		$css = $this->link_repair($css);
		if($css)
		{
			if(!in_array($css, $this->css_link['all']))
			{
				$this->css_link['all'][] = $css;
				if($is_meta) {
					$this->css_link['meta'][] = $css;
				}else{
					echo '<link href="'.$css.'" rel="stylesheet" type="text/css" />', "\n";
				}
			}
		}
	}
	
	public function js($js, $is_meta = TRUE )
	{
		$js = $this->link_repair($js);
		if($js)
		{
			if(!in_array($js, $this->js_link['all']))
			{
				$this->js_link['all'][] = $js;
				if($is_meta) {
					$this->js_link['meta'][] = $js;
				}else{
					echo '<script src="'.$js. '" type="text/javascript"></script>', "\n";
				}
			}
		}
	}
	
	public function logo()
	{
		$path = "assets/logo/";
		if( $this->sys_config->logo && !empty($this->sys_config->logo) ){
			if( file_exists(_ROOT.$path.$this->sys_config->logo) ){
				return _URL . $path.$this->sys_config->logo;	
			}
		}
	}
	
	public function favicon()
	{
		$path = "assets/favicon/";
		if( $this->sys_config->favicon && !empty($this->sys_config->favicon) ){
			if( file_exists(_ROOT.$path.$this->sys_config->favicon) ){
				return _URL . $path.$this->sys_config->favicon;	
			}
		}
	}
	
	
	public function display()
	{
		
		$output = "";
		$title = $this->sys_config->site_name." - Content Management System";
		$output .= "<title>".$title."</title>\n";
		$output .= "<meta http-equiv=\"Pragma\" content=\"no-cache\">\n";
		$output .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
		$output .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
		
		$arrcss = array_unique(array_merge(array($this->def_css), $this->css_link['meta']));
		foreach((array)$arrcss AS $css){
			if(!empty($css)){
				$output .= "<link href=\"".$css."\" rel=\"stylesheet\" type=\"text/css\" />\n";
			}
		}
		
		$arrjs = array_unique(array_merge(array($this->def_js), $this->js_link['meta']));
		foreach((array)$arrjs AS $js){
			if(!empty($js)){
				$output .= "<script src=\"".$js."\" type=\"text/javascript\"></script>\n";
			}
		}
		
		return $output;
	}
	
	public function display_old( $meta = true )
	{		
		$output  = "";
		$meta = $this->
		
		$title = $this->seo_meta['site']['site_name'];
		if($this->is_admin){
			$title = $this->seo_meta['site']['site_name']." - Content Management System";	
		}
		
		$output .= "<title>".$title."</title>\n";
		$output .= "<meta http-equiv=\"Pragma\" content=\"no-cache\">\n";
		$output .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
		$output .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
		$output .= "<link href=\"".$this->favicon()."\" rel=\"shortcut icon\" type=\"image/x-icon\" />\n";
		
		if( $meta )
		{			
			$output .= meta("verify-v1", session_id());
			$output .= meta("copyright",COPYRIGHT_OWNER);
			$output .= meta("author",_URL);
			$output .= meta("description",$this->seo_meta['site']['description']);
			$output .= meta("keyword",$this->seo_meta['site']['keyword']);
			$output .= meta("revisit-after","2 days");
			$output .= meta("robots","all,index,follow");		
			
			$og_title 		= $this->seo_meta['og']['title'];
			$og_site_name 	= $this->seo_meta['og']['site_name'];
			$og_url 		= $this->seo_meta['og']['url'];
			$og_type 		= $this->seo_meta['og']['type'];
			$og_description = $this->seo_meta['og']['description'];
			$og_image 		= $this->seo_meta['og']['image'];		
			
			# META OG
			$output .= "<meta property=\"og:title\" content=\"$og_title\"/>\n";
			$output .= "<meta property=\"og:site_name\" content=\"$og_site_name\"/>\n";
			$output .= "<meta property=\"og:url\" content=\"$og_url\"/>\n";
			$output .= "<meta property=\"og:type\" content=\"$og_type\"/>\n";
			$output .= "<meta property=\"og:image\" content=\"$og_image\"/>\n";
			$output .= "<meta property=\"og:description\" content=\"$og_description\"/>\n";
		
		}
		$title = $this->seo_meta['site']['site_name'];
		$output .= "<link href=\"".$this->favicon()."\" rel=\"shortcut icon\" type=\"image/x-icon\" />\n";
		#$output .= "<link href=\"".site_url('content/rss')."\" rel=\"alternate\" type=\"application/rss+xml\" title=\"".$title."\" />\n";
		
		
		
		$arrcss = array_unique(array_merge(array($this->def_css), $this->css_link['meta']));
		foreach((array)$arrcss AS $css){
			if(!empty($css)){
				$output .= "<link href=\"".$css."\" rel=\"stylesheet\" type=\"text/css\" />\n";
			}
		}
		
		$arrjs = array_unique(array_merge(array($this->def_js), $this->js_link['meta']));
		foreach((array)$arrjs AS $js){
			if(!empty($js)){
				$output .= "<script src=\"".$js."\" type=\"text/javascript\"></script>\n";
			}
		}
		
		return $output;
	}
	
	
	
	public function array_menu_admin( $parent_id = 0 )
	{
		$return = array();
		$query = "	SELECT id,parent_id,title,url FROM sys_menu 
					WHERE parent_id = $parent_id 
					AND is_admin = 1 
					AND active = 1";
		$run = $this->obj->db->query($query);
		if( $run->num_rows() > 0 )
		{
			foreach((array)$run->result_array() as $row)
			{
				$menu['id'] 		= $row['id'];
				$menu['parent_id'] 	= $row['parent_id'];
				$menu['title'] 		= $row['title'];
				$menu['url'] 		= $row['url'];
				$menu['child'] 		= $this->array_menu_admin($row['id']);
				$return[] = $menu;
			}
		}
		return $return;
	}
	
	public function array_menu( $id = 0, $position = 'top-middle' )
	{
		$return = array();
		$query = "	SELECT id,parent_id,title,url,seo, is_content, content_id FROM sys_menu 
					WHERE parent_id = $id 
					AND is_admin = 0 
					AND position = '$position'
					AND active = 1 ORDER BY orderby ASC";
		$query = $this->obj->db->query($query);
		if( $query->num_rows() > 0 )
		{
			foreach($query->result_array() as $row)
			{
				if($row['is_content']==1)
				{
					$content = $this->getContent($row['content_id']);
					$row['url'] = (!empty($content['seo']))?_URL."content/detail/".$content['seo']:_URL."content/detail/$row[content_id]/".url_title(@$content['title']);	
				}else{
					if( !preg_match("#http\:\/\/#is",$row['url']) )
					{
						$row['url'] = _URL . $row['url'];
					}
				}
				
				$item['id'] 		= $row['id'];
				$item['title'] 		= $row['title'];
				$item['parent_id'] 	= $row['parent_id'];
				$item['url'] 		= $row['url'];
				$item['seo'] 		= $row['seo'];
				$item['child'] 		= $this->array_menu($row['id']);
				$return[] = $item;
			}
		}
		return $return;
	}
	
	public function getContent($id)
	{
		return $this->obj->db->query("SELECT * FROM content WHERE type_id = 1 AND id = $id")->row_array();	
	}
	
	
	# Planing buat ngecek dihalaman publik apakah module ybs sudah diisntal atau belum, kalo belum diredirect
	public function allowed_access_module()
	{
		// if( $this->obj->uri->segment(1) ){
		// 	$module = strtolower( $this->obj->uri->segment(1) );
		// }else{
		// 	$module = 'home';
		// }

		// $this->obj->db->select('is_installed');
		// $this->obj->db->where('name',$module);
		// $query = $this->obj->db->get('sys_modules') -> row();

		// if( $query->is_installed == 0 ){
		// 	show_404();
		// 	exit();
		// }
	}
}