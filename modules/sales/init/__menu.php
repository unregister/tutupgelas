<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$sales['uri_controller'] 	= $this->obj->uri->segment(1);
$sales['module_name'] 	= "sales";

$sales_status_class 		= "";
$sales_submenu_display	= "none";
$sales_parent_class		= "";

if( $sales['uri_controller'] == $sales['module_name'] ){
	$sales_status_class 		= " active";
	$sales_submenu_display 	= "block";
	$sales_parent_class		= "treeview-active";	
}


$sales_menu = '<li class="treeview'.$sales_status_class.'">
					<a href="#" class="'.$sales_parent_class.'"><i class="fa fa-files-o"></i> <span>SUPORT ONLINE</span></a>
					<ul class="treeview-menu shaddow-active" style="display:'.$sales_submenu_display.'">
						<li>
							<a href="'.site_url("sales/admin/support_online/index").'">
							<i class="fa fa-check"></i> Data yahoo messenger
							</a>
						</li>
						<li>
							<a href="'.site_url("sales/admin/support_online/index/add").'">
							<i class="fa fa-check"></i> Tambah yahoo messenger
							</a>
						</li>						                          
					</ul>
				</li>';

$admin_menu['sales'] = $sales_menu;

