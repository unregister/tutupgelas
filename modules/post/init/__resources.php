<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$resources['post']['mode'] = 'single';
$resources['post']['parent'] = array('title'=>'Post','value'=>'post');
$resources['post']['child'][] = array('title'=>'Post data','value'=>'post.data');
$resources['post']['child'][] = array('title'=>'Post add','value'=>'post.add');
$resources['post']['child'][] = array('title'=>'Post edit','value'=>'post.edit');
$resources['post']['child'][] = array('title'=>'Post delete','value'=>'post.delete');
$resources['post']['child'][] = array('title'=>'Category','value'=>'post.category');
$resources['post']['child'][] = array('title'=>'Settings','value'=>'post.settings');

