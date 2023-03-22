<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model', 'product');
		$this->load->model('user_model', 'usermodel');
		$this->load->model('order_model', 'order');
	}
	
	public function index($id=null)
	{
		$output = [];
		$orders = $this->order->getOrder($id);
		foreach($orders as $order) {
			$_order = (object) [
				'id' => $order->id,
				'created_at' => $order->created_at,
				'obs' => $order->obs,
				'status'=>null,
				'fornecedor'=>null,
				'colaborador'=>null,
				'items'=>null,
				'total_amount'=>null,
			];

			$status = $this->order->getStatus($order->id_status_order);
			if(!empty($status)){
				$_order->status = $status[0];
			}

			$_order->fornecedor = $this->usermodel->getUserById($order->id_user, 'FORNECEDOR');
			$_order->colaborador = $this->usermodel->getUserById($order->created_by, 'COLABORADOR');
			$_order->items = $this->order->getItems($order->id);

			foreach($_order->items as $item){
				$_order->total_amount += ($item->price * $item->quantity);
			}
			$output[]=$_order;
		}
		$this->response($output, 200, true, null);
	}

}
