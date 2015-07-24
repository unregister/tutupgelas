<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$post['uri_controller'] 	= $this->obj->uri->segment(1);
$post['module_name'] 	= "post";

$post_status_class 		= "";
$post_submenu_display	= "none";
$post_parent_class		= "";

if( $post['uri_controller'] == $post['module_name'] ){
	$post_status_class 		= " active";
	$post_submenu_display 	= "block";
	$post_parent_class		= "treeview-active";	
}


$post_menu = '<li class="treeview'.$post_status_class.'">
					<a href="#" class="'.$post_parent_class.'"><i class="fa fa-files-o"></i> <span>POST MANAGER</span></a>
					<ul class="treeview-menu shaddow-active" style="display:'.$post_submenu_display.'">
						<li>
							<a href="'.site_url("post/admin/post/index").'">
							<i class="fa fa-check"></i> Data post
							</a>
						</li>
						<li>
							<a href="'.site_url("post/admin/post/index/add").'">
							<i class="fa fa-check"></i> Tambah post
							</a>
						</li>
						<li>
							<a href="'.site_url("post/admin/post/category").'">
							<i class="fa fa-check"></i> Kategori
							</a>
						</li>
						                          
					</ul>
				</li>';

$admin_menu['post'] = $post_menu;

