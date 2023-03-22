<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

	public $user = null;
	public $remoteIp = null;
	protected $viewData;

	private $withoutValidation = [
		'',
		'/',
		'auth',
		'busca',
		'auth/signin',
		'auth/signup',
		'auth/api',
		'auth/passwordrecovery',
		'auth/resetpassword'
	];

	public function __construct()
	{
		parent::__construct();
		$this->load->library('authuser');
		$this->loadUser();
		$this->middle();
		$this->remoteIp = getRealIPAddr();
		$this->initViewData();
	}

	function middle()
	{
		$class = $this->router->uri->uri_string;
		if (!in_array($class, $this->withoutValidation)) {
			if (strpos($class, 'api/') === 0) {
				$this->validateResource();
			} else {
				$this->validateSession();
			}
		}
	}

	function validateSession()
	{
		if (empty($this->user)) {
			$this->session->set_flashdata('error', ['Sessão inválida ou expirada.']);
			redirect(base_url() . 'auth/signin');
		} else {
			$this->user->permission = $this->authuser->getPermissions($this->user->id);
		}
	}

	public function isAllowed($permissioName)
	{
		$permissionElement = array_column($this->user->permission, null, 'name')[$permissioName] ?? false;
		if (empty($permissionElement)) {
			$this->session->set_flashdata('error', ["usuário sem acesso à {$permissioName}"]);
			redirect(base_url() . 'dashboard');
		}
	}

	function validateResource()
	{

		$basicAuthorization = $this->getHeaderKey('Authorization');
		if(!empty($basicAuthorization)) {
			$basic = base64_decode(strstr($basicAuthorization, ' '));
			list($user, $pass) = explode(":", $basic);
			$user = $this->authuser->validaBasicAuth($user, $pass);
			if($user) {
				$this->setUser($user);
				return true;
			}
		}
		
		redirect(base_url() . 'auth/api');
	}

	function loadUser()
	{
		$this->decoderUser();
	}

	function setUser($user)
	{
		$this->user = $this->authuser->dataUser($user);

		$this->session->set_userdata('user', json_encode($this->user));
	}

	function toLogout()
	{
		$this->session->unset_userdata('user');
	}

	private function decoderUser()
	{
		$user = $this->session->userdata('user');
		if (!empty($user)) {
			$this->user = json_decode($user);
		}
	}

	public function setTitle($title)
	{
		$this->viewData['title'] = $title;
	}
	public function addOnView($name, $data)
	{
		if (is_array($name)) {
			foreach ($name as $key => $value) {
				$this->viewData[$key] = $value;
			}
		} else {
			$this->viewData[$name] = $data;
		}
	}

	public function getViewData()
	{
		return $this->viewData;
	}

	private function initViewData()
	{

		$this->viewData = [
			'user' => $this->user,
			'title' => '',
		];
	}

	public function getHeaderKey($key) {
        $saida = null;
		$headers = $this->input->request_headers();
        foreach ($headers  as $k => $v) {
            if (strcmp(strtolower($key), strtolower($k)) == 0) {
                $saida = $v;
            }
        }

        if (!empty($saida)) {
            return $saida;
        } else {
            return null;
        }
    }

	public function response($data, $code=200, $status = true, $message = null)
	{
		$data = (object) [
			'status' => $status,
			'message' => $message,
			'data' => $data
		];

		$this->output
			->set_content_type('application/json')
			->set_status_header($code)
			->set_output(json_encode($data));
	}
}
