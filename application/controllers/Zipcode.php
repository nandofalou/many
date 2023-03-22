<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Zipcode extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('location');
	}

	public function get($zipCode)
	{
		$this->response($this->location->getZip($zipCode), 200, true, null);
	}

	
}
