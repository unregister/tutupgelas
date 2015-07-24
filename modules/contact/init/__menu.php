<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$contact['uri_controller'] 	= $this->obj->uri->segment(1);
$contact['module_name'] 	= "contact";

$contact_status_class 		= "";
$contact_submenu_display	= "none";
$content_parent_class		= "";

if( $contact['uri_controller'] == $contact['module_name'] ){
	$contact_status_class 		= " active";
	$contact_submenu_display 	= "block";	
	$content_parent_class		= "treeview-active";
}


$contactmenu = '<li class="treeview'.$contact_status_class.'">
					<a href="#" class="'.$content_parent_class.'"><i class="fa fa-envelope"></i> <span>CONTACT MANAGER</span></a>
					<ul class="treeview-menu shaddow-active" style="display:'.$contact_submenu_display.'">
						<li>
							<a href="'.site_url("contact/admin/contact/index").'">
							<i class="fa fa-check"></i> Data contact
							</a>
						</li>
						<li>
							<a href="'.site_url("contact/admin/contact/settings").'">
							<i class="fa fa-check"></i> Settings contact
							</a>
						</li>                              
					</ul>
				</li>';

$admin_menu['contact'] = $contactmenu;

