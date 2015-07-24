<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testimonial extends Public_Controller
{
	protected $settings;

	public function __construct(){
		parent::__construct();
		$this->settings = $this->testimonial_model->get_settings();
	}

	public function index(){
		$data = $this->testimonial_model->get_testimonial();
		pr($data['data']);
		echo "<br>";
		echo $data['nav'];
	}

	public function add()
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
		$this->session->set_userdata('captcha_kode',$captcha['word']);

		$data = array(
		    'captcha_time' => $captcha['time'],
		    'ip_address' => $this->input->ip_address(),
		    'word' => $captcha['word']
		    );

		$query = $this->db->insert_string('captcha', $data);
		$this->db->query($query);

		$this->THEMEVARS['captcha_url'] = $captcha['image_url'];
		$this->THEMEVARS['testimonial']['settings'] = (array)$this->settings;
		$this->THEMEVARS['page'] = 'templates/testimonial_add.html';				
		$this->twig->display($this->layout, $this->THEMEVARS );
	}

	public function save()
	{
		if( isset( $_POST['testimonial_save']) )
		{
			$this->load->library('form_validation');
			$this->load->helper('captcha');

			$this->form_validation->set_rules('testimonial_name','Nama', 'trim|required|xss_clean');
			$this->form_validation->set_rules('testimonial_email','Email', 'trim|required|valid_email|xss_clean');

			if( $this->settings->show_phone ){
				if( $this->settings->required_phone ){
					$this->form_validation->set_rules('testimonial_phone','Phone','trim|required|xss_clean');
				}
			}

			if( $this->settings->show_website ){
				if( $this->settings->required_website ){
					$this->form_validation->set_rules('testimonial_website','Website','trim|required|xss_clean');
				}
			}

			if( $this->settings->show_location ){
				if( $this->settings->required_location ){
					$this->form_validation->set_rules('testimonial_location','Lokasi','trim|required|xss_clean');
				}
			}

			if( isset($_FILES['testimonial_images']) ){
				$_POST['testimonial_images'] = $_FILES['testimonial_images']['name'];
			}

			if( $this->settings->show_images ){
				if( $this->settings->required_images ){					
					if( isset($_FILES['testimonial_images']) )
					{
						$_POST['testimonial_images'] = $_FILES['testimonial_images']['name'];
						$this->form_validation->messages('testimonial_images','The Testimonial images field is required');
					}
				}
			}

			$this->form_validation->set_rules('testimonial_captcha','Captcha','trim|required|xss_clean|captcha_check');
			
			

			if( $this->form_validation->run() == FALSE ){
				$msg = error( validation_errors() );
				set_msg( $msg );
				redirect( site_url('testimonial/add') );
				exit();
			}
			else
			{

				$file_images = '';
				if( $this->settings->show_images ){	
					if( isset($_FILES['testimonial_images']) && !empty($_FILES['testimonial_images']) )
					{
						$allowed_ext = array('jpeg','jpg','gif','png');
						$ext = strtolower( end( explode(".",$_FILES['testimonial_images']['name']) ) );

						if( !in_array($ext, $allowed_ext) ){
							$msg = error("Image extension is not allowed $ext");
							set_msg($msg);
							redirect( site_url('testimonial/add') );
							exit();
						}

						if( !is_dir( _ROOT."files/testimonial" ) ){
							@mkdir( _ROOT."files/testimonial",0777 );
						}

						$uploaded_name = date('YmdHis')."_".time().".".$ext;
						$upload = move_uploaded_file($_FILES['testimonial_images']['tmp_name'], _ROOT."files/testimonial/$uploaded_name");
						if( $upload && file_exists(_ROOT."files/testimonial/$uploaded_name") ){
							$this->load->library('image_moo');
							$this->image_moo->load(_ROOT."files/testimonial/$uploaded_name")->resize_crop(200,200)->save_pa(null,null,TRUE);
							$file_images = $uploaded_name;
						}
					}
				}

				$post = array();
				$post['testimonial_name'] = $this->input->post('testimonial_name',TRUE);
				$post['testimonial_email'] = $this->input->post('testimonial_email',TRUE);

				if( $this->settings->show_phone ){
					$post['testimonial_phone'] = $this->input->post('testimonial_phone',TRUE);
				}
				if( $this->settings->show_website ){
					$post['testimonial_website'] = $this->input->post('testimonial_website',TRUE);
				}
				if( $this->settings->show_location ){
					$post['testimonial_location'] = $this->input->post('testimonial_location',TRUE);
				}
				if( $this->settings->show_images ){					
					$post['testimonial_image'] = $file_images;
				}

				$save = $this->testimonial_model->save_testimonial( $post );
				if( $save ){
					$text = ($this->settings->success_messages)?$this->settings->success_messages:'Thank you. Your testimonial has been saved';
					$msg = success( $text );
				}
				else
				{
					$text = ($this->settings->error_messages)?$this->settings->error_messages:'Sorry. Your testimonial failed to saved';
					$msg = error( $text );
				}

				set_msg( $msg );
				redirect( site_url('testimonial/add') );
				exit();
			}
		}
	}

	public function captcha_check( $post ){
		return TRUE;
	}
}