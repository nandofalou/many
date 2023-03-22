<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produto extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();
		$this->isAllowed('produto');
		$this->load->model('product_model', 'model');
	}

	public function index()
	{
		$this->setTitle('Produto::');
		$this->addOnView('produtos', $this->model->getAll());

		$this->load->view('layout/app/top', $this->getViewData());
		$this->load->view('produto/index', $this->getViewData());
		$this->load->view('layout/app/footer', $this->getViewData());
	}

	public function new()
	{

		$produto = (object) [
			'id' => null,
			'name' => null,
			'price' => null,
			'stock' => null,
			'active' => 1,
		];

		$this->setTitle('Produto::');
		$this->addOnView('produto', $produto);

		$this->load->view('layout/app/top', $this->getViewData());
		$this->load->view('produto/edit', $this->getViewData());
		$this->load->view('layout/app/footer', $this->getViewData());
	}

	public function view($id)
	{

		$produto = $this->model->find($id);

		if (empty($produto)) {
			$this->session->set_flashdata('error', ['Produto não cadastrado']);
			redirect(base_url() . 'produto');
		}

		$this->setTitle('Produto::');
		$this->addOnView('produto', $produto);

		$this->load->view('layout/app/top', $this->getViewData());
		$this->load->view('produto/edit', $this->getViewData());
		$this->load->view('layout/app/footer', $this->getViewData());
	}

	public function save($id)
	{

		$produto = $this->model->find($id);

		if (empty($produto)) {
			$this->session->set_flashdata('error', ['Produto não cadastrado']);
			redirect(base_url() . 'produto');
		}

		$data = [
			'name' => $this->input->post('name'),
			'price' => (float) $this->input->post('price'),
			'stock' => (int) $this->input->post('stock'),
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$this->model->update($id, $data);

		$this->session->set_flashdata('success', ['Produto alterado com sucesso']);
		redirect(base_url() . 'produto');
	}

	public function add()
	{

		$data = [
			'name' => $this->input->post('name'),
			'price' => (float) $this->input->post('price'),
			'stock' => (int) $this->input->post('stock'),
			'active' => 1,
			'created_by' => $this->user->id,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$produtoId = $this->model->insert($data);

		if (!empty($produtoId)) {
			$this->session->set_flashdata('success', ['Produto criado com sucesso']);
		} else {
			$this->session->set_flashdata('error', ['Ocorreu um problema ao inserir novo produto.']);
		}
		redirect(base_url() . 'produto');
	}

	public function changests()
	{

		$data=(object)[
			'id'=>$this->input->post('id'),
			'active'=>$this->input->post('active'),
		];

		$produto = $this->model->find($data->id);
		
		if (empty($produto)) {
			$this->response($data, $code=404, $status = false, $message = 'Produto inválido');
		} else {
			$this->model->update($data->id, ['active' =>$data->active]);
			$this->response($data, $code=200, $status = true, $message = 'Status alterado');
		}

	}
}
