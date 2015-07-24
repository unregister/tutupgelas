<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$client['uri_controller'] 	= $this->obj->uri->segment(1);
$client['module_name'] 	= "client";

$client_status_class 		= "";
$client_submenu_display	= "none";
$content_parent_class		= "";

if( $client['uri_controller'] == $client['module_name'] ){
	$client_status_class 		= " active";
	$client_submenu_display 	= "block";	
	$content_parent_class		= "treeview-active";
}


$clientmenu = '<li class="treeview'.$client_status_class.'">
					<a href="#" class="'.$content_parent_class.'"><i class="fa fa-envelope"></i> <span>KLIEN MANAGER</span></a>
					<ul class="treeview-menu shaddow-active" style="display:'.$client_submenu_display.'">
						<li>
							<a href="'.site_url("client/admin/client/index").'">
							<i class="fa fa-check"></i> Data client
							</a>
						</li>
						<li>
							<a href="'.site_url("client/admin/client/index/add").'">
							<i class="fa fa-check"></i> Tambah client
							</a>
						</li>                              
					</ul>
				</li>';

$admin_menu['client'] = $clientmenu;

