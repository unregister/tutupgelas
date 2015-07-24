<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$slideshow['uri_controller'] 	= $this->obj->uri->segment(1);
$slideshow['module_name'] 	= "slideshow";

$slideshow_status_class 		= "";
$slideshow_submenu_display	= "none";
$slideshow_parent_class		= "";

if( $slideshow['uri_controller'] == $slideshow['module_name'] ){
	$slideshow_status_class 		= " active";
	$slideshow_submenu_display 	= "block";
	$slideshow_parent_class		= "treeview-active";	
}


$slideshow_menu = '<li class="treeview'.$slideshow_status_class.'">
					<a href="#" class="'.$slideshow_parent_class.'"><i class="fa fa-leaf"></i> <span>SLIDESHOW MANAGER</span></a>
					<ul class="treeview-menu shaddow-active" style="display:'.$slideshow_submenu_display.'">
						<li>
							<a href="'.site_url("slideshow/admin/slideshow/index").'">
							<i class="fa fa-check"></i> Data slideshow
							</a>
						</li>
												                          
					</ul>
				</li>';

$admin_menu['slideshow'] = $slideshow_menu;

