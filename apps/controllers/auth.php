<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Form_validation');	
	}
	
	public function login()
	{
		if( isset($_POST['username']) )
		{
			$val = $this->form_validation;  

            $val->set_rules('username', 'Username', 'trim|required|xss_clean');  
            $val->set_rules('password', 'Password', 'trim|required|xss_clean');  
            $val->set_rules('remember', 'Remember me', 'integer');
			
			if( $val->run() == false )
			{
				$msg = error(validation_errors());
				
				if( IS_AJAX == 1 )
				{
					$response['status'] = 0;
					$response['msg'] = $msg;
				}
				else
				{					
					set_msg( $msg );
					redirect("admin");
					exit();
				}
			}else{			
			
				if( $this->dx_auth->is_banned() )
				{
					$msg = error("Username Anda telah dibanned. Silahkan kontak administrator");
					if( IS_AJAX == 1 )
					{
						$response['status'] = 0;
						$response['msg'] = $msg;
					}
					else
					{
						set_msg( $msg );
						redirect("admin");
						exit();
					}
						
				}
				else
				{
					$username = $this->input->post('username',true);
					$password = $this->input->post('password',true);
					$remember = $this->input->post('remember');
					
					$login = $this->dx_auth->login($username, $password, $remember);
					if( $login )
					{
						if( IS_AJAX == 1 )
						{
							$response['status'] = 1;
							$response['msg'] = success("Login berhasil. Mohon tunggu");
							$response['redirect'] = site_url('main');
						}
						else
						{
							$url_redirect = 'home/admin/dashboard';
							if( $this->input->get('redirect') ){
								$url_redirect = trim( $this->input->get('redirect') );
							}
							redirect($url_redirect);
							exit();	
						}
					}
					else
					{
						$msg = error("Username atau password Anda tidak benar");
						if( IS_AJAX == 1 )
						{
							$response['status'] = 0;
							$response['msg'] = $msg;
						}
						else
						{
							
							set_msg( $msg );
							redirect("admin");
							exit();	
						}
					}
				}
			}
			  	
		}
	}
	
	public function logout()
	{
		if( $this->dx_auth->is_logged_in() )
		{			
			$this->dx_auth->logout();
		}
		redirect( _URL."admin" );
		exit();
	}
}