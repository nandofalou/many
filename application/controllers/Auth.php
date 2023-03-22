<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model', 'model');
		$this->load->model('Permission_model', 'permission');
	}

	public function signinview()
	{
		$this->setTitle('Login::');
		$this->load->view('layout/auth/top', $this->getViewData());
		$this->load->view('auth/signin', $this->getViewData());
		$this->load->view('layout/auth/footer', $this->getViewData());
	}

	public function signin()
	{
		$verify = $this->model->verifyLoginAccess($this->remoteIp);
		//dd($verify);
		if ($verify >= 3) {
			// mensagem após 3 tentativas de acesso com senha errada, no intervalo de 10min
			$this->session->set_flashdata('error', ['Acesso bloqueado por excesso de tentativas']);
			redirect(base_url() . 'auth/signin');
		}

		$user = $this->model->login(
			$this->input->post('email'),
			$this->input->post('pass'),
			$this->remoteIp
		);

		if (!empty($user)) {
			$this->setUser($user);
			redirect(base_url() . 'dashboard');
		} else {
			$this->session->set_flashdata('error', ['Login/senha inválida']);
		}

		redirect(base_url() . 'auth/signin');
	}

	public function signupview()
	{
		$this->setTitle('Nova Conta::');
		$this->load->view('layout/auth/top', $this->getViewData());
		$this->load->view('auth/signup', $this->getViewData());
		$this->load->view('layout/auth/footer', $this->getViewData());
	}

	public function api() {
		$this->response('', 401, false, 'Usuário inválido');
	}

	public function signup()
	{
		$data = (object)[
			'email' => trim($this->input->post('email')),
			'name' => trim($this->input->post('name')),
			'pass' => trim($this->input->post('pass')),
		];

		if ($this->model->getUserByEmail($data->email)) {
			$this->session->set_flashdata('error', ['Este email já está cadastrado. Tente com um outro email.']);
		} else {

			$userId = $this->model->addUser(
				$data->name,
				$data->email,
				$data->pass
			);

			if ($userId) {
				$users = $this->model->getUserById($userId);
				$this->permission->setAllPermission($userId);
				$this->session->set_flashdata('success', ['Conta criara com sucesso']);
				
				$user = $this->setUser($this->model->getUserById($userId));
				redirect(base_url() . 'dashboard');
			} else {
				$this->session->set_flashdata('error', ['Ocorreu um erro ao criar conta. Tente novamente mais tarde']);
			}
		}

		redirect(base_url() . 'auth/signup');
	}

	public function passwordrecoveryview()
	{
		$this->setTitle('Esqueci a senha::');
		$this->load->view('layout/auth/top', $this->getViewData());
		$this->load->view('auth/passwordrecovery', $this->getViewData());
		$this->load->view('layout/auth/footer', $this->getViewData());
	}

	public function passwordrecovery()
	{
		$email = trim($this->input->post('email'));

		$user = $this->model->getUserByEmail($email);
		if (empty($user)) {
			$this->session->set_flashdata('error', ['e-mail não cadastrado!']);
			redirect(base_url() . 'auth/passwordrecovery');
		} else {
			$hashData = (object) $this->model->createHash($user->id);
			$this->setTitle('Recuperar a senha::');
			$this->addOnView('user', $user);
			$this->addOnView('hash', $hashData);
			$this->load->view('layout/auth/top', $this->getViewData());
			$this->load->view('auth/hash', $this->getViewData());
			$this->load->view('layout/auth/footer', $this->getViewData());
		}
	}

	public function resetpassword()
	{
		$hash = trim($this->input->get('hash'));

		if (empty($hash)) {
			$this->session->set_flashdata('error', ['Link inválido!']);
			redirect(base_url() . 'auth/signin');
		}

		$user = $this->model->getUserByHash($hash);
		if (empty($user)) {
			$this->session->set_flashdata('error', ['Link inválido!']);
			redirect(base_url() . 'auth/signin');
		} else {
			$this->setTitle('Informe a nova senha::');
			$this->addOnView('user', $user);
			$this->addOnView('hash', $hash);
			$this->load->view('layout/auth/top', $this->getViewData());
			$this->load->view('auth/changepass', $this->getViewData());
			$this->load->view('layout/auth/footer', $this->getViewData());
		}
	}

	public function changepass()
	{
		$hash = trim($this->input->post('hash'));
		$pass = trim($this->input->post('pass'));

		if (empty($hash)) {
			$this->session->set_flashdata('error', ['hash inválido!']);
			redirect(base_url() . 'auth/signin');
		}

		$user = $this->model->getUserByHash($hash);
		if (empty($user)) {
            $this->session->set_flashdata('error', ['hash inválido!']);
			redirect(base_url() . 'auth/signin');
        }

        
        if ($this->model->updatePass($user->id, $pass)) {
			$this->session->set_flashdata('success', ['Senha alterada com sucesso']);
			redirect(base_url() . 'auth/signin');
        } else {
            $this->session->set_flashdata('error', ['Ocorreu um erro ao alterar senha!']);
			redirect(base_url() . 'auth/signin');
        }
	}

	public function logout()
	{
		$this->toLogout();
		redirect(base_url() . 'auth/signin');
	}
}
