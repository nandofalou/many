<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fornecedor extends MY_Controller
{

	private $typePerson = 'FORNECEDOR';

	public function __construct()
	{
		parent::__construct();
		$this->isAllowed('fornecedor');
		$this->load->model('user_model', 'model');
	}

	public function index()
	{
		$this->setTitle('Fornecedor::');
		$this->addOnView('fornecedores', $this->model->getUser($this->typePerson));

		$this->load->view('layout/app/top', $this->getViewData());
		$this->load->view('fornecedor/index', $this->getViewData());
		$this->load->view('layout/app/footer', $this->getViewData());
	}

	public function new()
	{

		$fornecedor = (object) [
			'id' => null,
			'name' => null,
			'email' => null,
			'active' => 1,
			'type_person' => 'FORNECEDOR'
		];

		$this->setTitle('Fornecedor::');
		$this->addOnView('fornecedor', $fornecedor);

		$this->load->view('layout/app/top', $this->getViewData());
		$this->load->view('fornecedor/edit', $this->getViewData());
		$this->load->view('layout/app/footer', $this->getViewData());
	}

	public function view($id)
	{

		$fornecedor = $this->model->getUser($this->typePerson, $id);

		if (empty($fornecedor)) {
			$this->session->set_flashdata('error', ['Fornecedor não cadastrado']);
			redirect(base_url() . 'fornecedor');
		}

		$this->setTitle('Fornecedor::');
		$this->addOnView('fornecedor', $fornecedor[0]);

		$this->load->view('layout/app/top', $this->getViewData());
		$this->load->view('fornecedor/edit', $this->getViewData());
		$this->load->view('layout/app/footer', $this->getViewData());
	}

	public function save($id)
	{

		$fornecedor = $this->model->getUser($this->typePerson, $id);

		if (empty($fornecedor)) {
			$this->session->set_flashdata('error', ['Fornecedor não cadastrado']);
			redirect(base_url() . 'fornecedor');
		}

		$data = [
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$this->model->update($id, $data);

		$this->session->set_flashdata('success', ['Fornecedor alterado com sucesso']);
		redirect(base_url() . 'fornecedor');
	}

	public function add()
	{

		$existeEmail = $this->model->getUserByEmail($this->input->post('email'));

		if (!empty($existeEmail)) {
			$this->session->set_flashdata('error', ['Este email já está sendo utilizado por outro usuário']);
			redirect(base_url() . 'fornecedor');
		}

		$data = [
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'active' => 1,
			'type_person' => 'FORNECEDOR',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$fornecedorId = $this->model->insert($data);

		if (!empty($fornecedorId)) {
			$this->session->set_flashdata('success', ['Fornecedor criado com sucesso']);
		} else {
			$this->session->set_flashdata('error', ['Ocorreu um problema ao inserir novo fornecedor.']);
		}
		redirect(base_url() . 'fornecedor');
	}
}
