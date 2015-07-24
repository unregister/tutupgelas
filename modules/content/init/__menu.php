<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$content['uri_controller'] 	= $this->obj->uri->segment(1);
$content['module_name'] 	= "content";

$content_status_class 		= "";
$content_submenu_display	= "none";
$content_parent_class		= "";

if( $content['uri_controller'] == $content['module_name'] ){
	$content_status_class 		= " active";
	$content_submenu_display 	= "block";
	$content_parent_class		= "treeview-active";	
}


$content_menu = '<li class="treeview'.$content_status_class.'">
					<a href="#" class="'.$content_parent_class.'"><i class="fa fa-file-text"></i> <span>CONTENT MANAGER</span></a>
					<ul class="treeview-menu shaddow-active" style="display:'.$content_submenu_display.'">
						<li>
							<a href="'.site_url("content/admin/content/index").'">
							<i class="fa fa-check"></i> Data content
							</a>
						</li>
						<li>
							<a href="'.site_url("content/admin/content/index/add").'">
							<i class="fa fa-check"></i> Tambah content
							</a>
						</li>
						                          
					</ul>
				</li>';

$admin_menu['content'] = $content_menu;

