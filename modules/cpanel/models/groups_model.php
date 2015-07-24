<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Groups_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_all_resources()
	{
		$query = $this->db->query("SELECT * FROM sys_resources") -> result_array();
		return $query;
	}
	
	public function get_array_resources($parent_id = 0)
	{
		$return = array();
		$query = $this->db->query("SELECT * FROM sys_resources WHERE parent_id = $parent_id");
		if( $query->num_rows() > 0 )
		{
			foreach((array)$query->result_array() as $row)
			{
				
				$item['id'] = $row['id'];
				$item['parent_id'] = $row['parent_id'];
				$item['text'] = $row['title'];
				$item['name'] = $row['name'];
				$item['url'] = site_url("user/resources/form/edit/$row[id]");
				$item['children'] = $this->get_array_resources($row['id']);
				$return[] = $item;
			}
		}
		return $return;	
	}
	
	public function get_tree_resources($data,$group_id = 0,$deep=1)
	{
		$this->load->model('dx_auth/permissions');
		$ulid = ($deep==1)?' id="resourcestree"':'';
		$html = "<ul$ulid>\n";
		if( !empty($data) )
		{
			foreach((array)$data as $row)
			{
				
				$val = $this->permissions->get_permission_value($group_id,$row['name']);
				$checked = ($val)?' checked':'';
				
				$html .= "<li>";
				$html .= "<input type=\"checkbox\" name=\"resources[]\" id=\"resources_$row[id]\" value=\"$row[id]\"$checked />&nbsp;&nbsp;";
				$html .= "<label class=\"label-name\" for=\"resources_$row[id]\">$row[text]</label>";
				
				if( count($row['children']) > 0 )
				{
					$html .= $this->get_tree_resources($row['children'],$group_id,$deep+1);	
				}
				
				$html .= "</li>";	
			}
		}
		$html .= "</ul>\n";
		return $html;
	}
}