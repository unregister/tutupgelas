<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$content = $this->content_model->get_content(1);
$this->THEMEVARS['blocks']['welcome_message'] = $content;