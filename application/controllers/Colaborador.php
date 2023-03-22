<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Colaborador extends MY_Controller
{

	private $typePerson = 'COLABORADOR';

	public function __construct()
	{
		parent::__construct();
		$this->isAllowed('colaborador');
		$this->load->model('user_model', 'model');
		$this->load->model('permission_model', 'permission');
		$this->load->library('location');
	}

	public function index()
	{
		$this->setTitle('Colaborador::');
		$this->addOnView('colaboradores', $this->model->getUser($this->typePerson));

		$this->load->view('layout/app/top', $this->getViewData());
		$this->load->view('colaborador/index', $this->getViewData());
		$this->load->view('layout/app/footer', $this->getViewData());
	}

	public function new()
	{

		$colaborador = (object) [
			'id' => null,
			'name' => null,
			'email' => null,
			'active' => 1,
			'type_person' => 'COLABORADOR'
		];

		$permissions = $this->permission->getAllPermissionByUser(0);
		$this->setTitle('Colaborador::');
		$this->addOnView('colaborador', $colaborador);
		$this->addOnView('permissions', $permissions);

		$this->load->view('layout/app/top', $this->getViewData());
		$this->load->view('colaborador/edit', $this->getViewData());
		$this->load->view('layout/app/footer', $this->getViewData());
	}

	public function view($id)
	{

		$colaborador = $this->model->getUser($this->typePerson, $id);

		if (empty($colaborador)) {
			$this->session->set_flashdata('error', ['Colaborador não cadastrado']);
			redirect(base_url() . 'colaborador');
		}

		$permissions = $this->permission->getAllPermissionByUser($colaborador[0]->id);
		$this->setTitle('Colaborador::');
		$this->addOnView('colaborador', $colaborador[0]);
		$this->addOnView('permissions', $permissions);

		$this->load->view('layout/app/top', $this->getViewData());
		$this->load->view('colaborador/edit', $this->getViewData());
		$this->load->view('layout/app/footer', $this->getViewData());
	}

	public function address($id)
	{

		$colaborador = $this->model->getUser($this->typePerson, $id);

		if (empty($colaborador)) {
			$this->session->set_flashdata('error', ['Colaborador não cadastrado']);
			redirect(base_url() . 'colaborador');
		}

		$this->setTitle('Colaborador::');
		$this->addOnView('colaborador', $colaborador[0]);

		$this->load->view('layout/app/top', $this->getViewData());
		$this->load->view('colaborador/address', $this->getViewData());
		$this->load->view('layout/app/footer', $this->getViewData());
	}

	public function alladdress($id)
	{
		$address = $this->model->getUserAddress($id);
		$this->response($address, 200, true, null);
	}

	public function removeaddress($idUser, $idAddress)
	{
		$this->model->removeAddress($idUser, $idAddress);
		$this->response('', 200, true, null);
	}

	public function addaddress()
	{

		$data = (object) [
			'id_user' =>$this->input->post('id'),
			'zipcode' =>$this->input->post('zipcode'),
			'number' =>$this->input->post('number'),
			'complement' =>$this->input->post('complement'),
		];

		$error = null;

		$colaborador = $this->model->getUser($this->typePerson, $data->id_user);

		if (empty($colaborador)) {
			$error = 'Colaborador não cadastrado';
		}

		if (empty($error) && empty($data->zipcode)) {
			$error = 'Informe o CEP';
		}

		if (empty($error) && empty($data->number)) {
			$error = 'Informe o Número';
		}
		
		if (empty($error) && empty($this->location->getZip($data->zipcode))) {
			$error = 'CEP Inválido';
		}

		if (empty($error)) {
			if(empty($this->model->insertAddress($data)) ){
				$error = 'Ocorreu um erro ao cadastrar CEP';
			}
		}


		if (empty($error)) {
			$this->response('', 200, true, null);
		} else {
			$this->response('', 404, false, $error);
		}

	}

	public function save($id)
	{

		$colaborador = $this->model->getUser($this->typePerson, $id);

		if (empty($colaborador)) {
			$this->session->set_flashdata('error', ['Colaborador não cadastrado']);
			redirect(base_url() . 'colaborador');
		}

		$data = [
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$this->model->update($id, $data);
		$this->permission->setPermissions($id, $this->input->post('permission'));

		$this->session->set_flashdata('success', ['Colaborador alterado com sucesso']);
		redirect(base_url() . 'colaborador');
	}

	public function add()
	{

		$existeEmail = $this->model->getUserByEmail($this->input->post('email'));

		if (!empty($existeEmail)) {
			$this->session->set_flashdata('error', ['Este email já está sendo utilizado por outro usuário']);
			redirect(base_url() . 'colaborador');
		}

		$data = [
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'active' => 1,
			'type_person' => 'COLABORADOR',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$colaboradorId = $this->model->insert($data);

		if (!empty($colaboradorId)) {
			$this->permission->setPermissions($colaboradorId, $this->input->post('permission'));
			$this->session->set_flashdata('success', ['Colaborador criado com sucesso']);
		} else {
			$this->session->set_flashdata('error', ['Ocorreu um problema ao inserir novo colaborador.']);
		}
		redirect(base_url() . 'colaborador');
	}

	public function changests()
	{

		$data=(object)[
			'id'=>$this->input->post('id'),
			'active'=>$this->input->post('active'),
		];

		$colaborador = $this->model->getUser($this->typePerson, $data->id);
		
		if (empty($colaborador)) {
			$this->response($data, 404, false, 'Colaborador inválido');
		} else {
			$this->model->update($data->id, ['active' =>$data->active]);
			$this->response($data, 200, true, $message = 'Status alterado');
		}

	}
}
