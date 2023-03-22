<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class User_model extends CI_Model
{

	public $table = 'user';

	public function __construct()
	{
		parent::__construct();
	}

	public function login($email, $password, $remoteIp)
	{
		$rs = $this->db->where([
			'email' => $email,
			'active' => 1,
			'type_person' => 'COLABORADOR',
		])->get($this->table)->result();

		if (!empty($rs)) {

			if (password_verify($password, $rs[0]->pass)) {
				unset($rs[0]->pass);
				$this->logUser($rs[0]->id, $remoteIp, 1);
				return $rs[0];
			} else {
				$this->logUser($rs[0]->id, $remoteIp, 0);
			}
		}

		return false;
	}

	public function basicAuthorization($email, $password)
	{
		$rs = $this->db->where([
			'email' => $email,
			'active' => 1,
			'type_person' => 'COLABORADOR',
		])->get($this->table)->result();

		if (!empty($rs)) {

			if (password_verify($password, $rs[0]->pass)) {
				unset($rs[0]->pass);
				return $rs[0];
			}
		}

		return false;
	}

	public function getUserByEmail($email)
	{
		$rs = $this->db
			->where([
				'email' => $email,
				'active' => 1,
				'type_person' => 'COLABORADOR',
			])
			->select('id, name, email, type_person')
			->get($this->table)->result();

		if (!empty($rs)) {
			return $rs[0];
		}

		return false;
	}

	public function getUserByHash($hash)
	{
		$rs = $this->db
			->where('hash', $hash)
			->where('hash_validate >=', 'now()')
			->select('id, name, email, type_person')
			->get($this->table)->result();

		if (!empty($rs)) {
			return $rs[0];
		}

		return false;
	}

	public function getUserById($id, $typePerson = 'COLABORADOR')
	{
		$rs = $this->db
			->where([
				'id' => $id,
				'active' => 1,
				'type_person' => $typePerson,
			])
			->select('id, name, email, type_person')
			->get($this->table)->result();

		if (!empty($rs)) {
			return $rs[0];
		}
		return false;
	}

	public function getUser($typePerson, $id = null)
	{
		$rs = $this->db
			->where([
				'type_person' => $typePerson,
			])
			->select('id, name, email, active, created_at')
			->order_by('name', 'asc');
		if (!empty($id)) {
			$rs->where('id', $id);
		}

		return $rs->get($this->table)->result();
	}

	public function addUser($name, $email, $pass)
	{
		$data = [
			'name' => $name,
			'email' => $email,
			'pass' => password_hash($pass, PASSWORD_DEFAULT),
			'active' => 1,
			'type_person' => 'COLABORADOR',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function removeAddress($idUser, $idAddress)
	{
		return $this->db->update(
			'user_address',
			['deleted_at' => date('Y-m-d H:i:s')],
			['id' => $idAddress, 'id_user' => $idUser]
		);
	}

	public function removeAddressHard($idUser, $idAddress)
	{
		return $this->db->delete('user_address', [
			'id_user' => $idUser,
			'id' => $idAddress
		]);
	}

	public function insertAddress($data)
	{
		$data->zipcode = preg_replace("/[^0-9]/im", "", $data->zipcode);
		$data = [
			'id_user' => $data->id_user,
			'zipcode' => $data->zipcode,
			'number' => $data->number,
			'complement' => $data->complement,
			'is_default' => 0,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$this->db->insert('user_address', $data);
		return $this->db->insert_id();
	}

	public function getUserAddress($idUser)
	{
		return $this->db
			->select('
				user_address.id
				,zipcode.zipcode
				,user_address.number
				,user_address.complement
				,zipcode.street 
				,zipcode.district 
				,city.name as city 
				,city.id as id_city
				,state.name as state 
				,state.abbr as uf')
			->join('zipcode', 'zipcode.zipcode = user_address.zipcode', 'LEFT')
			->join('city', 'city.id = zipcode.id_city', 'LEFT')
			->join('state', 'state.id = city.id_state', 'LEFT')
			->where('user_address.id_user ', $idUser)
			->where('user_address.deleted_at is null')
			->get('user_address')->result();
	}

	public function update($id, $data)
	{
		if ($this->db->update('user', $data, array('id' => $id))) {
			return true;
		}
		return false;
	}

	public function insert($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function updatePass($id, $pass)
	{
		$data = [
			'pass' => password_hash($pass, PASSWORD_DEFAULT),
			'hash' => null,
			'hash_validate' => null,
			'updated_at' => date('Y-m-d H:i:s'),
		];

		if ($this->db->update('user', $data, array('id' => $id))) {
			return true;
		}
		return false;
	}

	public function verifyLoginAccess($remoteIp)
	{
		$rs = $this->db
			->from('log_auth')
			->where('ip', $remoteIp)
			->where('success', 0)
			->where('created_at >=', date('Y-m-d H:i:s', strtotime('-10 minutes')))
			->count_all_results();
		if (!empty($rs)) {
			return $rs;
		}

		return 0;
	}

	public function createHash($id)
	{
		$data = [
			'hash' => md5($id . time() . rand(0, 500)),
			'hash_validate' => date('Y-m-d H:i:s', strtotime('+ 1 hour'))
		];

		if ($this->db->update('user', $data, array('id' => $id))) {
			return $data;
		}
		return null;
	}

	private function logUser($id_user, $remoteIp, $success)
	{
		$data = [
			'id_user' => $id_user,
			'ip' => $remoteIp,
			'success' => (int) $success,
			'created_at' => date('Y-m-d H:i:s'),
		];

		$this->db->insert('log_auth', $data);
		return $this->db->insert_id();
	}
}
