<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$resources['client']['parent'] = array('title'=>'Client Manager','value'=>'client.manager');
$resources['client']['child'][] = array('title'=>'Client data','value'=>'client.data');
$resources['client']['child'][] = array('title'=>'Client edit','value'=>'client.edit');
$resources['client']['child'][] = array('title'=>'Client delete','value'=>'client.delete');
