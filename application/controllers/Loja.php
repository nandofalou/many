<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loja extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();
		$this->isAllowed('produto');
		$this->load->model('product_model', 'product');
		$this->load->model('user_model', 'fornecedor');
		$this->load->model('order_model', 'order');
	}

	public function index()
	{
		$this->setTitle('Loja::');
		$this->addOnView('produtos', $this->product->get(['active' => 1, 'stock >=' => 1]));
		$this->addOnView('fornecedores', $this->fornecedor->getUser('FORNECEDOR'));

		$this->load->view('layout/app/top', $this->getViewData());
		$this->load->view('loja/index', $this->getViewData());
		$this->load->view('layout/app/footer', $this->getViewData());
	}

	public function order($id)
	{

		$order = $this->order->getOrder($id);

		if (empty($order)) {
			$this->session->set_flashdata('error', ['Pedido inválido']);
			redirect(base_url() . 'dashboard');
		}
		$orderItems = $this->order->getItems($id);

		$this->setTitle('Loja::');
		$this->addOnView('order', $order[0]);
		$this->addOnView('orderItems', $orderItems);

		$this->load->view('layout/app/top', $this->getViewData());
		$this->load->view('loja/order', $this->getViewData());
		$this->load->view('layout/app/footer', $this->getViewData());
	}

	public function createorder()
	{

		$error = null;

		$fornecedor = $this->fornecedor->getUserById($this->input->post('fornecedorId'), 'FORNECEDOR');
		if (empty($fornecedor)) {
			$error = 'Fornecedor inválido';
		}

		$items = $this->input->post('item');
		if (empty($error) && is_array($items) && count($items) > 0) {
			$this->order->db->trans_start();

			$newOrder = [
				'id_user' => $fornecedor->id,
				'id_status_order' => 1,
				'created_by' => $this->user->id,
				'obs' => $this->input->post('obs'),
				'created_at' => date('Y-m-d H:i:s'),
				'created_at' => date('Y-m-d H:i:s'),
			];

			$orderId = $this->order->insert($newOrder);

			if ($orderId) {
				foreach ($items as $item) {
					$orderItem = $this->order->addItem($orderId, (int) $item['productId'], (int) $item['quantity']);
				}
			}
			$this->db->trans_complete();
			$this->response(['order' => $orderId], 200, true, null);
		} else {
			$error = 'Carrinho inválido.';
			$this->response('', 404, false, $error);
		}
	}
}
