<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$rooms = $this->rooms_model->getRooms();
$this->THEMEVARS['blocks']['rooms'] = $rooms;