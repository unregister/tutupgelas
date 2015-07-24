<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$content = $this->content_model->get_content(13);
$this->THEMEVARS['blocks']['content'] = $content;
