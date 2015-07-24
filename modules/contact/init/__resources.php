<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$resources['contact']['parent'] = array('title'=>'Contact','value'=>'contact');
$resources['contact']['child'][] = array('title'=>'Contact data','value'=>'contact.data');
$resources['contact']['child'][] = array('title'=>'Contact edit','value'=>'contact.edit');
$resources['contact']['child'][] = array('title'=>'Contact delete','value'=>'contact.delete');
$resources['contact']['child'][] = array('title'=>'Contact settings','value'=>'contact.settings');

