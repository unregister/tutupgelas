<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Public_Controller
{
	public function __construct(){
		parent::__construct();
	}
	
	public function index()
	{	
		#pr($this->THEMEVARS);	
		#$this->THEMEVARS['slideshow']['image'] = $this->slideshow_model->get_slideshow();
		#$this->THEMEVARS['slideshow']['paging'] = $this->slideshow_model->get_paging();
		
		 // pr($this->THEMEVARS);
		 // die;
		//echo $this->layout;
		//die;

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
		$this->session->set_userdata('captcha_kode',$captcha['word']);

		$data = array(
		    'captcha_time' => $captcha['time'],
		    'ip_address' => $this->input->ip_address(),
		    'word' => $captcha['word']
		    );

		$query = $this->db->insert_string('captcha', $data);
		$this->db->query($query);

		$this->THEMEVARS['captcha_url'] = $captcha['image_url'];
		$this->THEMEVARS['page'] = 'templates/home.html';				
		$this->twig->display($this->layout, $this->THEMEVARS );
	}

	public function save_inquiry()
	{
		if( isset($_POST['inquiry_send']) )
		{
			$this->load->library('form_validation');

			$this->form_validation->set_rules('inquiry_name','Nama','trim|required|xss_clean');
			$this->form_validation->set_rules('inquiry_phone','No telepon','trim|required|xss_clean');
			$this->form_validation->set_rules('inquiry_location','Lokasi','trim|required|xss_clean');
			$this->form_validation->set_rules('inquiry_captcha','Security code','trim|required|xss_clean|captcha_check');

			if( $this->form_validation->run() === FALSE )
			{
				$msg = error( validation_errors() );
				set_msg( $msg );
				redirect( _URL );
				exit();
			}
			else
			{
				$this->db->set( 'inquiry_name', $this->input->post('inquiry_name') );
				$this->db->set( 'inquiry_phone', $this->input->post('inquiry_phone') );
				$this->db->set( 'inquiry_location', $this->input->post('inquiry_location') );
				$this->db->set( 'inquiry_services', ucfirst($this->input->post('inquiry_services') ) );
				$this->db->set( 'inquiry_message', $this->input->post('inquiry_message') );
				$this->db->set( 'inquiry_created', 'NOW()',FALSE );

				$insert = $this->db->insert('inquiry');
				if( $insert ){
					$msg = success( "Terimakasih. Inquiry berhasil disimpan" );
				}
				else
				{
					$msg = success( "Inquiry gagal disimpan. Silahkan ulangi" );
				}

				set_msg( $msg );
				redirect( _URL );
				exit();
			}
		}
	}
	
}