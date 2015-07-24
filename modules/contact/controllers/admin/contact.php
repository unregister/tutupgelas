<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();	
	}
	
	public function index()
	{
		
		$this->crud->set_table("contact");
		$this->crud->set_subject('Data contact');
		$this->crud->columns("name","email","created","reply");
		$this->crud->field_type('reply','true_false',array('1' => 'Sudah', '0' => 'Belum'));
		$this->crud->add_action('Balas','<i class="glyphicon glyphicon-share-alt"></i>','contact/admin/contact/reply','');

		$this->crud->unset_edit();
		$this->crud->unset_add();
		
		$output = $this->crud->render();
		
		$data['output'] = $output;
		$data['title'] = "Data contact";
		$data['module'] = "contact";
		$data['page'] = "layout_contact_data";	
		$this->load->view($this->layout,$data);
	}
	
	public function settings()
	{
		$this->crud->set_table("contact_settings");
		$this->crud->set_subject('Settings contact');
	
		$this->crud->field_type('show_name','true_false',array('1' => 'Show', '0' => 'Hide'));
		$this->crud->field_type('show_email','true_false',array('1' => 'Show', '0' => 'Hide'));
		$this->crud->field_type('show_address','true_false',array('1' => 'Show', '0' => 'Hide'));
		$this->crud->field_type('show_phone','true_false',array('1' => 'Show', '0' => 'Hide'));
		$this->crud->field_type('show_subject','true_false',array('1' => 'Show', '0' => 'Hide'));
		$this->crud->field_type('show_message','true_false',array('1' => 'Show', '0' => 'Hide'));
		$this->crud->field_type('show_captcha','true_false',array('1' => 'Show', '0' => 'Hide'));
		$this->crud->field_type('address','text');
		$this->crud->field_type('work_hours','text');
		$this->crud->unset_texteditor('work_hours','summary');
		
		$this->crud->unset_list();
		$this->crud->unset_add();
		$this->crud->unset_back_to_list();
		
		try{
			$output = $this->crud->render();
		} catch(Exception $e) {
		 
			if($e->getCode() == 14) //The 14 is the code of the "You don't have permissions" error on grocery CRUD.
			{
				redirect(site_url('contact/admin/contact/settings/edit/1'));
			}
			else
			{
				show_error($e->getMessage());
			}
		}
		
		$data['output'] = $output;
		$data['title'] = "Data contact";
		$data['module'] = "contact";
		$data['page'] = "layout_contact_data";	
		$this->load->view($this->layout,$data);	
	}

	public function reply()
	{
		$id = $this->uri->segment(5);

		if( $id )
		{
			$this->db->where("id",$id);
			$query = $this->db->get("contact");

			if( $query->num_rows() <= 0 ){
				redirect('contact/admin/contact/');
				exit();
			}

			$data['id'] = $id;
			$data['contact'] = $query->row();
			$data['title'] = "Balas contact";
			$data['module'] = "contact";
			$data['page'] = "layout_contact_reply";	
			$this->load->view($this->layout,$data);

		}else{
			redirect('contact/admin/contact/');
			exit();
		}

	}

	public function reply_contact()
	{
		if( isset($_POST['submit']) )
		{
			if( trim($_POST['balasan']) != '' )
			{
				$this->load->model('contact/contact_model');
				$settings = $this->contact_model->get_setting_contact();


				$this->db->where("id",$_POST['id']);
				$this->db->set('reply','1');
				$this->db->update('contact');

				$this->load->library('email');
				$this->email->mailtype = 'html';
				$this->email->from($settings->email, $setings->name);
				$this->email->to( $_POST['email'] );

				$this->email->subject( "[REPLY] ".$_POST['subject'] );
				$this->email->message( $_POST['balasan'] );

				$send = $this->email->send();
				if( $send ){
					set_msg( success("Pesan terkirim") );
					redirect('contact/admin/contact/reply/'.$_POST['id']);
				}else{
					set_msg( success("Pesan gagal terkirim") );
					redirect('contact/admin/contact/reply/'.$_POST['id']);
				}
			}
		}
	}
}