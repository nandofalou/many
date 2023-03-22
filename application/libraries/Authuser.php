<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authuser extends CI_Controller {

	private $CI;

    public function __construct() {
        $this->CI = &get_instance();
		$this->CI->load->model('permission_model', 'permission');
		$this->CI->load->model('user_model', 'usermodel');
    }

	public function dataUser($user)
	{
		return (object)[
			'id'=> property_exists($user, 'id')?$user->id:null,
			'name'=> property_exists($user, 'name')?$user->name:null,
			'email'=> property_exists($user, 'email')?$user->email:null,
			'type_person'=> property_exists($user, 'type_person')?$user->type_person:null,
			'permission'=>[]
		];
	}

	public function getPermissions($idUser) {
		return $this->CI->permission->getPermissionByUser($idUser);
	}
	public function validaBasicAuth($user, $pass) {
		return $this->CI->usermodel->basicAuthorization($user, $pass);
	}

}
