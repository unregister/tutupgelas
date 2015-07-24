<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$testimonial['uri_controller'] 	= $this->obj->uri->segment(1);
$testimonial['module_name'] 	= "testimonial";

$testimonial_status_class 		= "";
$testimonial_submenu_display	= "none";
$testimonial_parent_class		= "";

if( $testimonial['uri_controller'] == $testimonial['module_name'] ){
	$testimonial_status_class 		= " active";
	$testimonial_submenu_display 	= "block";
	$testimonial_parent_class		= "treeview-active";	
}


$testimonial_menu = '<li class="treeview'.$testimonial_status_class.'">
					<a href="#" class="'.$testimonial_parent_class.'"><i class="fa fa-files-o"></i> <span>TESTIMONIAL</span></a>
					<ul class="treeview-menu shaddow-active" style="display:'.$testimonial_submenu_display.'">
						<li>
							<a href="'.site_url("testimonial/admin/testimonial/index").'">
							<i class="fa fa-check"></i> Data testimonial
							</a>
						</li>
						<li>
							<a href="'.site_url("testimonial/admin/testimonial/settings").'">
							<i class="fa fa-check"></i> Settings testimonial
							</a>
						</li>						                          
					</ul>
				</li>';

$admin_menu['testimonial'] = $testimonial_menu;

