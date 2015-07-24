<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resources extends Admin_Controller
{
	function __contruct(){
		parent::__construct();	
		
	}
	
	function index(){
		$this->data();	
	}
	
	function data()
	{
		$this->system->add_breadcrumb('Data resources');
		
		$data['resources'] = array_resources();
		$data['module'] = "_cpanel";
		$data['page'] = "layout_resources";
		$this->load->view($this->layout_content,$data);
	}
	
	function add()
	{
		$this->system->add_breadcrumb('Data resources','_cpanel/admin/resources/data');
		$this->system->add_breadcrumb('Tambah resources');
		
		if( isset( $_POST['save_resources']) )
		{
			$name = trim($_POST['name']);
			if( $name == '' )
			{
				$data['msg'] = error("Resources name is required");	
			}else{
				$is_available = $this->adodb->GetOne("SELECT COUNT(*) FROM sys_resources WHERE `name` = '$name'");
				if( $is_available > 0 ){
					$data['msg'] = error("Resources for \"$name\" is available on DB. Please use another name");	
				}else{
					$parent_id = (int)$_POST['parent_id'];
					$insert = $this->adodb->Execute("INSERT INTO sys_resources SET parent_id = '$parent_id',`name` = '$name'");
					if( $insert ){
						$data['msg'] = success("New resources has been saved successfully");	
					}
				}
			}
		}
		
		$data['resources'] = array_resources();
		$data['module'] = "_cpanel";
		$data['page'] = "layout_add_resources";
		$this->load->view($this->layout_content,$data);
	}
	
	function edit()
	{
		$this->system->add_breadcrumb('Data resources','_cpanel/admin/resources/data');
		$this->system->add_breadcrumb('Edit resources');
		
		$id = (int)$_GET['id'];
		$edit = $this->adodb->GetROw("SELECT * FROM sys_resources WHERE id = $id LIMIT 1");
		
		$data['edit'] = $edit;
		$data['resources'] = array_resources();
		$data['module'] = "_cpanel";
		$data['page'] = "layout_edit_resources";
		$this->load->view($this->layout_content,$data);	
	}

}