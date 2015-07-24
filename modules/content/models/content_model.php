<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content_model extends MY_Model
{
	protected $ci;
	public $content;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_content( $content_id )
	{
		if( is_numeric($content_id) ){
			$where = " content.id = ".(int)$content_id;	
		}else{
			$where = " content.seo = '$content_id'";	
		}
		$query = "SELECT content.*,sys_users.name FROM content 
					LEFT JOIN sys_users ON(content.created_by=sys_users.id) 
				  	WHERE $where LIMIT 1";
		$result = $this->dbQueryCache($query,'row');
		$content = $this->parse_content( $result );	
		$this->content = $content;
		return $content;
	}
	
	protected function parse_content($array)
	{
		if( is_array($array) and !empty($array) )
		{
			
			if( !empty($array['seo']) ){
				$url = _URL."content/detail/$array[seo]";	
			}else{
				$url = _URL."content/detail/$array[id]/".url_title($array['title'],"-",true);	
			}
			
			$explode_title = explode(' ', $array['title']);

			$content = array();
			$content['id'] = $array['id'];
			$content['title'] = $array['title'];
			$content['subtitle'] = $array['subtitle'];
			$content['seo'] = $array['seo'];
			$content['created'] = mysql_date($array['created'],'d/m/y',true);
			$content['user_id'] = $array['created_by'];
			$content['name'] = $array['name'];
			$content['image'] = $array['image'];
			$content['image_url'] = image("files/content/".$array['image'],'alt="'.$array['title'].'"');
			$content['content']	 = $array['content'];
			$content['meta_keyword'] = $array['meta_keyword'];
			$content['meta_description'] = $array['meta_description'];
			$content['hits'] = $array['hits'];
			$content['url'] = $url;
			return $content;
		}
	}
	
	public function set_meta()
	{
		
		if( !empty($this->content) )
		{
			$meta['title'] = $this->content['title'];
			$meta['keyword'] = $this->content['meta_keyword'];
			
			if( empty( $this->content['meta_description'] ) ){
				$meta['description'] = 	substr(strip_tags($this->content['content']),0,200);
			}else{
				$meta['description'] = $this->content['meta_description'];
			}
			
			$meta['url'] = $this->content['url'];
			$meta['type'] = "article";
			$meta['image'] = $this->content['image_url'];
			return $meta;
		}
	}
	
	
}