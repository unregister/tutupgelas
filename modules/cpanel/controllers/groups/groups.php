<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groups extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		check_user('group.manager');	
	}
	
	public function index()
	{
		$this->crud->set_table('sys_roles');
		$this->crud->set_subject('Data groups');
		$this->crud->required_fields('name');
		$this->crud->columns('name');
		$this->crud->display_as('name','Group Name');
		$this->crud->add_action('PERMISSION', $this->icon->fa['key'], _URL.'cpanel/groups/permission/');
		$this->crud->unset_read();
		$this->crud->unset_add_fields('id','parent_id');
		$this->crud->unset_edit_fields('id','parent_id');
		
		
		$output = $this->crud->render();
		
		$data['output'] = $output;
		$data['page'] = "layout_crud";
		$data['module'] = "cpanel";
		$data['title'] = "User groups";

		$this->load->view($this->layout,$data);
	}
	
	public function permission()
	{
		
		$this->load->model('cpanel/groups_model');
		
		$group_id = (int)$this->uri->segment(4);
		$arr_resources = $this->groups_model->get_array_resources();
		
		$data['group_id'] = $group_id;
		$data['tree_resources'] = $this->groups_model->get_tree_resources($arr_resources,$group_id);
		$data['page'] = 'layout_permission';
		$data['module'] = "cpanel";
		$data['title'] = "Group permissions";
		$this->load->view($this->layout,$data);	
	}
	
	public function save_permission()
	{
		$response = array();
		$this->load->model('cpanel/groups_model');	
		$this->load->model('dx_auth/permissions');	
		
		if( $this->input->post('group_id',true) )
		{
			if( $this->input->post('resources',true) )
			{
				$group_id = (int)$this->input->post('group_id',true);
				$resources = $this->groups_model->get_all_resources();
				$access = $this->input->post('resources',true);
				
				if( count($access) > 0 )
				{
				
					$r_resources = array();
					foreach((array)$resources as $val)
					{
						$allow = ( in_array($val['id'],$access) ) ? true : false;
						$r_resources[$val['name']] = $allow;
					}
					
					$save = $this->permissions->set_permission_data($group_id,$r_resources);
					if($save)
					{
						$response['status'] = 1;
						$response['msg'] = success("Data permission berhasil disimpan");
					}
					else
					{
						$response['status'] = 0;
						$response['msg'] = error("Data permission gagal disimpan");	
					}
				}
				
			}
			else
			{
				$response['status'] = 0;
				$response['msg'] = "Silahkan pilih resources";	
			}
		}
		else
		{
			$response['status'] = 0;
			$response['msg'] = "Silahkan pilih resources";	
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
		
	}
	
	public function _getArrayPermission( $parent_id = 0 )
	{
		$return = array();
		$query = $this->db->query("SELECT * FROM sys_resources WHERE parent_id = $parent_id");
		if( $query->num_rows() > 0 )
		{
			foreach((array)$query->result_array() as $row)
			{
				$item['id'] = $row['id'];
				$item['key'] = $row['id'];
				$item['title'] = $row['title'];
				if( $row['id'] == 1 ){
					$item['select'] = true;
					$item['hideCheckbox'] = true;
					$item['expand'] = true;	
				}
				
				
				$isFolder = false;
				$run = $this->db->query("SELECT * FROM sys_resources WHERE parent_id = $row[id]");
				if( $run->num_rows() > 0 )
				{
					$isFolder = true;
					$item['children'] = $this->_getArrayPermission($row['id']);
				}
				$item['isFolder'] = $isFolder;
				$return[] = $item;
			}
		}
		return $return;
	}
	
	public function _getHtmlPermission( $array )
	{
		$out = "<ul>";
		if( is_array($array) && count($array) > 0 )
		{
			foreach((array)$array as $r)
			{
				$cls = (isset($r['children']) && count($r['children']) > 0)?' class="folder"':'';
				$out .= "<li".$cls.">";
				$out .= $r['title'];
				if( isset($r['children']) && count($r['children']) > 0 )
				{
					$out .= $this->_getHtmlPermission($r['children']);	
				}
				$out .= "</li>";
			}
		}
		$out .= "</ul>";
		return $out;
	}
	
}