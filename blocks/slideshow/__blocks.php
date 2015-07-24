<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$block_slideshow = $this->slideshow_model->get_slideshow();
$this->THEMEVARS['blocks']['slideshow'] = $block_slideshow;