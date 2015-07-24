<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['menu'] = array();
$config['menu'][] = array('title' => 'Home','url' => 'admin/dashboard','icon' => 'glyphicon glyphicon-home','nolink' => FALSE);
$config['menu'][] = array( 
							'title'  => 'Advance Settings',
							'url'    => '_cpanel/admin/advance',
							'icon'   => 'glyphicon glyphicon-cog',
							'nolink' => TRUE,
							'child'  => array(
												array(
														'title'  => 'Menu manager',
														'url'    => '_cpanel/admin/menu/index',
														'icon'   => 'glyphicon glyphicon-list',
														'nolink' => TRUE,
														'child'  =>array( 
																			array(
																					'title'  => 'Data menu admin',
																					'url'    => '_cpanel/admin/menu/admin',
																					'icon'   => 'glyphicon glyphicon-th',
																					'nolink' => FALSE
																				),
																			array(
																					'title'  => 'Data menu publik',
																					'url'    => '_cpanel/admin/menu/publics',
																					'icon'   => 'glyphicon glyphicon-th',
																					'nolink' => FALSE
																				)
																	)
												),
												array(
														'title'  => 'User manager',
														'url'    => '_cpanel/admin/user/index',
														'icon'   => 'glyphicon glyphicon-user',
														'nolink' => TRUE,
														'child'  => array(
																			array(
																				'title'  => 'Data user',
																				'url'    => '_cpanel/admin/user/data',
																				'icon'   => 'glyphicon glyphicon-list',
																				'nolink' => FALSE
																			),
																			array(
																					'title'  => 'Data groups',
																					'url'    => '_cpanel/admin/user/groups',
																					'icon'   => 'glyphicon glyphicon-tower',
																					'nolink' => FALSE
																				)
																	)
													)
											)
						  );
$config['menu'][] = array(
							'title' => 'My Account',
							'url'   => '_cpanel/admin/user/index',
							'icon'  => 'glyphicon glyphicon-user',
							'nolink' => TRUE,
							'child' => array(
												array(
														'title'  => 'Profil',
														'url'    => '_cpanel/admin/user/account',
														'icon'   => 'glyphicon glyphicon-user',
														'nolink' => FALSE
												),
												array(
														'title'  => 'Ubah password',
														'url'    => '_cpanel/admin/user/password',
														'icon'   => 'glyphicon glyphicon-lock',
														'nolink' => FALSE
												),
												array(
														'title'  => 'Logout',
														'url'    => 'admin/logout',
														'icon'   => 'glyphicon glyphicon-log-out',
														'nolink' => FALSE
												)
											)
						 );