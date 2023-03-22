<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Product_model extends CI_Model
{

	public $table = 'product';

	public function __construct()
	{
		parent::__construct();
	}

	public function get($where=null)
	{
		$rs = $this->db;
		if(!empty($where)) {
			$rs->where($where);
		}
		return $rs->get($this->table)->result();
	}

	public function getAll()
	{
		return $this->db->get($this->table)->result();
	}

	public function find($id)
	{
		$rs = $this->db->where('id', $id)->get($this->table)->result();
		if(!empty($rs)) {
			return $rs[0];
		}
		return false;
	}

	public function update($id, $data)
	{
		if ($this->db->update($this->table, $data, array('id' => $id))) {
			return true;
		}
		return false;
	}

	public function insert($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
}
