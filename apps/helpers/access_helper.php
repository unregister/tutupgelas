<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

function check_user( $data, $redirect = true )
{
	$ci =& get_instance();
	if( empty($data) ){
		return FALSE;	
	}

	if( $ci->dx_auth->get_permission_value($data) != NULL && $ci->dx_auth->get_permission_value($data) ){
		return TRUE;	
	}else{
		if( $redirect ){
			set_msg( error("You do not have permission to access this page") );
			redirect('home/admin/dashboard');
			exit();	
		}else{
			return FALSE;	
		}
	}
		
}