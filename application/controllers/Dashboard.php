<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('order_model', 'order');

	}
	
	public function index()
	{
		$order = $this->order->getOrder();

		$this->setTitle('Dashboard::');
		$this->addOnView('orders', $order);
		$this->load->view('layout/app/top', $this->getViewData());
		$this->load->view('dashboard/index', $this->getViewData());
		$this->load->view('layout/app/footer', $this->getViewData());
	}

}
