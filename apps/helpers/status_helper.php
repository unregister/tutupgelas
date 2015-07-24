<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function error( $str, $return = true ){	
	if( is_array($str) ){ $str = implode('<br>',$str);}	
	$html = html_msg($str,'danger','glyphicon glyphicon-remove');	
	if( $return ){ return $html; }else{ echo $html; }	
}

function success( $str, $return = true ){	
	$html = html_msg($str,'success','glyphicon glyphicon-ok');	
	if( $return ){ return $html; }else{ echo $html;	}	
}

function info( $str, $return = true ){	
	if( is_array($str) ){ $str = implode('<br>',$str);}
	$html = html_msg($str,'info','glyphicon glyphicon-info-sign');	
	if( $return ){ return $html; }else{ echo $html;	}	
}

function html_msg( $str,$type = 'error', $icon = 'glyphicon glyphicon-ok' ){
	$icon = ($type=='error')?'glyphicon-remove':'glyphicon-ok';
	$prefix = ($type=='danger')?'error':$type;
	
	$html = "<div class=\"alert alert-$type fade in\">
			<i data-dismiss=\"alert\" class=\"icon-remove close\"></i>
			<strong>".ucfirst($prefix)."!</strong> $str</div>";
	return $html;
}

function set_msg($str){
	$ci =& get_instance();
	return $ci->session->set_flashdata('_msg',$str);
}

function get_msg( $return = false ){
	$ci =& get_instance();
	if( $ci->session->flashdata('_msg') ){
		if( $return ){
			return 	$ci->session->flashdata('_msg');
		}else{
			echo $ci->session->flashdata('_msg');	
		}
	}
}