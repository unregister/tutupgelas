<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends Public_Controller
{
	protected $settings;
	protected $captcha;
	
	public function __construct()
	{
		parent::__construct();	
		$this->settings = (object)$this->contact_model->get_setting_contact();
	}
	

	public function index()
	{
		$this->load->helper('captcha');

		$options = array(
					'img_path' => _ROOT.'captcha/images/',
					'img_url' => _URL.'captcha/images/',
					'font_path' => _ROOT.'assets/fonts/texb.ttf',
					'img_width' => '150',
					'img_height' => '50',
					'expiration' => 300
				);

		$captcha = create_captcha($options);

		$data = array(
		    'captcha_time' => $captcha['time'],
		    'ip_address' => $this->input->ip_address(),
		    'word' => $captcha['word']
		    );

		$query = $this->db->insert_string('captcha', $data);
		$this->db->query($query);

		$this->THEMEVARS['breadcrumb']['Contact us'] = '';	
		$this->THEMEVARS['captcha_url'] = $captcha['image_url'];
		$this->THEMEVARS['contact']['settings'] = $this->settings;		
		$this->THEMEVARS['page_title'] = "Contact Us";		
		$this->THEMEVARS['page'] = 'templates/contact.html';		
		$this->twig->display($this->layout, $this->THEMEVARS );	
	}
	
	public function save()
	{
		$this->load->library('form_validation');
		if( isset($_POST['contact_save']) )
		{

			if( $this->settings->show_name ){
				$this->form_validation->set_rules('contact_name','Nama','trim|required|xss_clean');
			}
			
			if( $this->settings->show_email ){
				$this->form_validation->set_rules('contact_email','Email','trim|valid_email|required|xss_clean');
			}
			
			if( $this->settings->show_address ){
				$this->form_validation->set_rules('contact_address','Alamat','trim|required|xss_clean');
			}
			
			if( $this->settings->show_phone ){
				$this->form_validation->set_rules('contact_phone','Telepon','trim|required|xss_clean');
			}
			
			if( $this->settings->show_subject ){
				$this->form_validation->set_rules('contact_subject','Subjek','trim|required|xss_clean');
			}
			
			if( $this->settings->show_message ){
				$this->form_validation->set_rules('contact_message','Pesan','trim|required|xss_clean');
			}
			
			#if( $this->settings->show_captcha ){
				$this->form_validation->set_rules('contact_captcha','Security code','trim|required|xss_clean|captcha_check');
			#}
			
			if( $this->form_validation->run() == false )
			{
				$error = validation_errors();
				$msg = error($error);
				set_msg( $msg );
				redirect("contact");
				exit();	
			}
			else
			{

				$arr['created'] = date('Y-m-d H:i:s');
				if( $this->settings->show_name ){
					$arr['name'] = $this->input->post('contact_name',true);
				}
				
				if( $this->settings->show_email ){
					$arr['email'] = $this->input->post('contact_email',true);
				}
				
				if( $this->settings->show_address ){
					$arr['address'] = $this->input->post('contact_address',true);
				}
				
				if( $this->settings->show_phone ){
					$arr['phone'] = $this->input->post('contact_phone',true);
				}
				
				if( $this->settings->show_subject ){
					$arr['subject'] = $this->input->post('contact_subject',true);
				}
				
				if( $this->settings->show_message ){
					$arr['message'] = $this->input->post('contact_message',true);
				}
				
				if( $this->contact_model->save_contact($arr) ){
					$msg = success('Your message has been saved');	
				}else{
					$msg = error('Message failed to send. Please try again');		
				}
				
				set_msg( $msg );
				redirect('contact');
				exit();
			}
		}else{
			show_404();	
		}
	}
	
}	


