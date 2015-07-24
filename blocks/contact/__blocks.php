<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$contact = $this->contact_model->get_setting_contact();
$contact['address'] = preg_replace('~[\r\n]+~','',$contact['address']);
$this->THEMEVARS['blocks']['contact'] = $contact;