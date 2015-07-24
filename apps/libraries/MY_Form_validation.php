<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
	public function __construct(){
		parent::__construct();
		$this->CI->load->database();
	}

	public function captcha_check($post)
	{
		$expiration = time()-300; // Two hour limit
		$this->CI->db->query("DELETE FROM captcha WHERE captcha_time < ".$expiration);

		$sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
		$binds = array($post, $this->CI->input->ip_address(), $expiration);
		$query = $this->CI->db->query($sql, $binds);
		$row = $query->row();

		if( $row->count == 0 ){
			$this->set_message('captcha_check', 'Invalid capthca code');
			return FALSE;
		}else{
			return TRUE;
		}
	}

	public function check_testimonial_image_extension( $str )
	{
		if( isset($_FILES['testimonial_images']) )
		{
			if( empty($_FILES['testimonial_images']['name']) ){
				$this->set_message('check_testimonial_image_extension', 'Form Photo is required');
				return FALSE;
			}
			else
			{
				$allowed_extension = array('jpg','jpeg','gif','png');
				$filename = $_FILES['testimonial_images']['name'];
				$ext = strtolower( end( explode(".",$filename) ) );

				if( !in_array($ext,$allowed_extension) ){
					$this->set_message('check_testimonial_image_extension', 'Ekstensi photo tidak diizinkan');
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	public function messages($rule,$str){
		$this->_error_messages[$rule] = $str;
		return $this;
	}
}