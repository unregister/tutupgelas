<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testimonial extends Admin_Controller
{
	protected $module = 'testimonial';

	public function __construct(){
		parent::__construct();
		check_user('testimonial');
	}

	public function index(){
		$this->crud->set_table('testimonial');
		$this->crud->set_primary_key('testimonial_id');
		$this->crud->set_subject('Data Testimonial');

		$this->crud->columns('testimonial_name','testimonial_email','testimonial_created','testimonial_active');
		$this->crud->display_as('testimonial_name','Nama');
		$this->crud->display_as('testimonial_email','Email');
		$this->crud->display_as('testimonial_created','Create');
		$this->crud->display_as('testimonial_active','Approve');
		$this->crud->field_type('testimonial_active','true_false',array('1' => 'Sudah', '0' => 'Belum'));

		$this->crud->add_action('Approve / UnApprove', $this->icon->fa['tags'], 'testimonial/admin/testimonial/action','ui-icon-image',array($this,'_link_approve'));

		$this->crud->unset_add();
		$this->crud->unset_edit();
		$this->crud->unset_delete();

		$output = $this->crud->render();
		
		$data['output'] = $output;
		$data['page'] 	= "layout_crud";
		$data['module'] = $this->module;
		$data['title'] = "Testimonial manager";

		$this->load->view($this->layout,$data);
	}

	public function settings()
	{
		check_user('testimonial.settings');

		$this->crud->set_table('testimonial_settings');
		$this->crud->set_primary_key('id');
		$this->crud->set_subject('Settings Testimonial');

		$this->crud->edit_fields('success_messages','error_messages','show_phone','show_website','show_location','show_images','show_captcha','required_phone','required_website','required_location','required_images');

		$this->crud->display_as('success_messages','Pesan sukses');
		$this->crud->display_as('error_messages','Pesan error');

		$this->crud->field_type('show_phone','true_false',array('1' => 'Ya', '0' => 'Tidak'));
		$this->crud->field_type('show_website','true_false',array('1' => 'Ya', '0' => 'Tidak'));
		$this->crud->field_type('show_location','true_false',array('1' => 'Ya', '0' => 'Tidak'));
		$this->crud->field_type('show_images','true_false',array('1' => 'Ya', '0' => 'Tidak'));
		$this->crud->field_type('show_captcha','true_false',array('1' => 'Ya', '0' => 'Tidak'));

		$this->crud->display_as('show_phone','Tampilkan form phone');
		$this->crud->display_as('show_website','Tampilkan form website');
		$this->crud->display_as('show_location','Tampilkan form lokasi');
		$this->crud->display_as('show_images','Tampikan form photo');
		$this->crud->display_as('show_captcha','Tampilkan form captcha');

		$this->crud->field_type('required_name','true_false',array('1' => 'Ya', '0' => 'Tidak'));
		$this->crud->field_type('required_email','true_false',array('1' => 'Ya', '0' => 'Tidak'));
		$this->crud->field_type('required_phone','true_false',array('1' => 'Ya', '0' => 'Tidak'));
		$this->crud->field_type('required_website','true_false',array('1' => 'Ya', '0' => 'Tidak'));
		$this->crud->field_type('required_location','true_false',array('1' => 'Ya', '0' => 'Tidak'));
		$this->crud->field_type('required_images','true_false',array('1' => 'Ya', '0' => 'Tidak'));

		$this->crud->display_as('required_name','Form Nama wajib di isi?');
		$this->crud->display_as('required_email','Form Email wajib di isi?');
		$this->crud->display_as('required_phone','Form Phone wajib di isi?');
		$this->crud->display_as('required_website','Form Website wajib di isi?');
		$this->crud->display_as('required_location','Form Lokasi wajib di isi?');
		$this->crud->display_as('required_images','Form Photo wajib di isi?');

		$this->crud->unset_list();
		$this->crud->unset_add();
		$this->crud->unset_delete();
		$this->crud->unset_back_to_list();

		try{
		    $output = $this->crud->render();
		} catch(Exception $e) {
			if($e->getCode() == 14) //The 14 is the code of the "You don't have permissions" error on grocery CRUD.
			{
				redirect('testimonial/admin/testimonial/settings/edit/1');
				exit();
			}
			else
			{
				show_error($e->getMessage());
			}
		}

		$data['output'] = $output;
		$data['page'] 	= "layout_crud";
		$data['module'] = $this->module;
		$data['title'] = "Testimonial manager";

		$this->load->view($this->layout,$data);

	}

	public function _link_approve($value, $row)
	{
		$is_install = $this->db->query("SELECT testimonial_active FROM testimonial WHERE testimonial_id = {$row->testimonial_id}")->row_array();
		if($is_install['testimonial_active'] == 1){
			$mode = 'unapprove';
			$name = 'UnApprove';
			$label = 'label-edit';  
		}else{
			$mode = 'approve';
			$name = 'Approve'; 
			$label = 'label-custom'; 
		}
		
		$html  = site_url('testimonial/admin/testimonial/'.$mode.'/'.$row->testimonial_id);
		return $html;
	}

	public function approve( $id ){
		if( !$id ){
			redirect('testimonial/admin/testimonial');
			exit();
		}

		$this->db->set('testimonial_active',1);
		$this->db->where('testimonial_id', (int)$id);
		$update = $this->db->update('testimonial');
		if( $update ){
			redirect('testimonial/admin/testimonial');
			exit();
		}
	}

	public function unapprove( $id ){
		if( !$id ){
			redirect('testimonial/admin/testimonial');
			exit();
		}

		$this->db->set('testimonial_active',0);
		$this->db->where('testimonial_id', (int)$id);
		$update = $this->db->update('testimonial');
		if( $update ){
			redirect('testimonial/admin/testimonial');
			exit();
		}
	}
}