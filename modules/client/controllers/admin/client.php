<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client extends Admin_Controller
{
	public function __construct(){
		parent::__construct();
		check_user('client.manager');
	}

	public function index()
	{
		check_user('client.data');
		$this->crud->set_table("client");
		$this->crud->set_subject('Data client');

		$this->crud->set_field_upload('client_image','files/client');
		$this->crud->field_type('client_active','true_false',array('1' => 'Yes', '0' => 'No'));

		$this->crud->display_as('client_name','Nama');
		$this->crud->display_as('client_website','Webiste');
		$this->crud->display_as('client_image','Gambar');
		$this->crud->display_as('client_description','Deskripsi');
		$this->crud->display_as('client_active','Aktif');

		$output = $this->crud->render();
		
		$data['output'] = $output;
		$data['title'] = "Data Klien";
		$data['module'] = "client";
		$data['page'] = "layout_crud";	
		$this->load->view($this->layout,$data);
	}
}