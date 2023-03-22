<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Permission_model extends CI_Model
{

	public $table = 'permission';

	public function __construct()
	{
		parent::__construct();
	}

	public function getAll()
	{
		return $this->db->get($this->table)->result();
	}

	public function getPermissionByUser($idUser)
	{
		return $this->db->from('permission')
			->join('permission_user', 'permission_user.id_permission  = permission.id', 'left')
			->where('permission.active', 1)
			->where('permission_user.id_user', $idUser)
			->get()->result();
	}

	public function getAllPermissionByUser($idUser)
	{
		return $this->db->from('permission')
			->select('id, name, description, IF(ISNULL(permission_user.id_user),0,1) as activated')
			->join(
				'permission_user',
				"permission_user.id_permission  = permission.id and permission_user.id_user = {$idUser}",
				'left'
			)
			->where('permission.active', 1)
			->get()->result();
	}

	public function setPermissions($idUser, $permission=[])
	{
		$this->db->trans_start();
		$this->db->where('id_user',$idUser)->delete('permission_user');
		foreach($permission as $idPermission) {
			$this->db->insert('permission_user', [
				'id_user' => $idUser,
				'id_permission' => $idPermission
			]);
		}
		$this->db->trans_complete();
	}

	public function setAllPermission($idUser)
	{
		$permission = [];
		foreach($this->getAll() as $r) {
			$permission[]=$r->id;
		}
		$this->setPermissions($idUser, $permission);
	}
}
