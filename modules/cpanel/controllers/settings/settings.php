<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();	
		check_user('advance.settings');
	}
	
	public function dashboard()
	{		
		$data['title'] = "Advance settings";
		$data['page'] = "layout_settings_dashboard";
		$data['module'] = "cpanel";
		$this->load->view($this->layout,$data);	
	}
	
	public function data()
	{
		
		$this->load->config('general');
		
		$this->crud->set_table("sys_site_config");
		$this->crud->set_subject('Pengaturan umum');
		
		$this->crud->required_fields('site_name','meta_title','site_status');
		
		$unset_edit[] = 'id';
		if( !$this->is_root ){
			$unset_edit[] = 'site_status';
		}
		
		$this->crud->unset_edit_fields( $unset_edit );
		$this->crud->set_field_upload('logo','assets/logo');
		$this->crud->set_field_upload('favicon','assets/favicon');
		
		$this->crud->field_type('site_type','dropdown', $this->config->item('site_type') );
		$this->crud->field_type('site_status','dropdown', $this->config->item('site_status') );
		
		$this->crud->field_type('meta_description','text');
		$this->crud->unset_texteditor('meta_description');
		
		$this->crud->unset_list();
		$this->crud->unset_add();
		$this->crud->unset_back_to_list();
		
		try{
			$output = $this->crud->render();
		} catch(Exception $e) {
		 
			if($e->getCode() == 14) //The 14 is the code of the "You don't have permissions" error on grocery CRUD.
			{
				redirect(site_url('cpanel/settings/data/edit/1'));
			}
			else
			{
				show_error($e->getMessage());
			}
		}
		
		
		
		$data['output'] = $output;		
		$data['title'] = "Pengaturan website";
		$data['page'] = "layout_crud";
		$data['module'] = "cpanel";
		$this->load->view($this->layout,$data);
	}
}