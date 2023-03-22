<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Version extends MY_Controller {
	
	public function index()
	{
		$this->response(['version' => '1.0.0', 'on'=>date('Y-m-d H:i:s')], 200, true, null);
	}

}
