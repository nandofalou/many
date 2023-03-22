<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function index()
	{
		if($this->user) {
			redirect(base_url() . 'dashboard');
		} else {
			redirect(base_url() . 'auth/signin');
		}
	}
}
