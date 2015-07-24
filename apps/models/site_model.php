<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();	
	}
	
	public function menu( $parent_id = 0, $position = 'top-middle' )
	{
		$result = array();
		$sql = "SELECT id,parent_id,title,url,seo,is_content,content_id FROM sys_menu 
				WHERE 
					parent_id = $parent_id 
				AND is_admin = 0 
				AND active = 1 
				AND position = '$position'
				ORDER BY orderby ASC";
		$query = $this->db->query( $sql );
		if( $query->num_rows() > 0 )
		{
			foreach((array)$query->result_array() as $row)
			{
				$menu['id'] = $row['id'];
				$menu['parent_id'] = $row['parent_id'];
				$menu['title'] = $row['title'];
				$menu['url'] = $row['url'];
				$menu['seo'] = $row['seo'];
				$menu['is_content'] = $row['is_content'];
				$menu['content_id'] = $row['content_id'];
				$menu['children'] = $this->menu($menu['id'],$position);
				$result[$menu['id']] = $menu;
			}
		}
		return $result;
		
	}
}