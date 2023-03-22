<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Order_model extends CI_Model
{

	public $table = 'order';

	public function __construct()
	{
		parent::__construct();
	}

	public function get($where = null)
	{
		$rs = $this->db;
		if (!empty($where)) {
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
		if (!empty($rs)) {
			return $rs[0];
		}
		return false;
	}

	public function getOrder($id = null)
	{
		$rs = $this->db
			->select('
				order.id,
				order.obs,
				order.created_at,
				order_status.name AS `status`,
				fornecedor.name AS fornecedor,
				user.name AS username,
				order.id_user,
				order.created_by,
				order.id_status_order
			')
			->join('user', 'ON user.id = order.created_by', 'left')
			->join('user as fornecedor', 'ON fornecedor.id = order.id_user', 'left')
			->join('order_status', 'ON order_status.id = order.id_status_order', 'left');

		if (!empty($id)) {
			$rs->where('order.id', $id);
		}
		return $rs->get($this->table)->result();
	}

	public function getItems($idOrder)
	{
		$rs = $this->db
			->select('
				product.id as product_id,
				product.name as product_name,
				product.price,
				order_item.quantity
			')
			->join('product', 'ON product.id = order_item.id_product', 'left')
			->where('order_item.id_order', $idOrder);
		return $rs->get('order_item')->result();
	}

	public function getStatus($id=null)
	{
		$rs = $this->db
			->select('
				id,
				name
			');

		if (!empty($id)) {
			$rs->where('id', $id);
		}
		return $rs->get('order_status')->result();
	}

	public function addItem($orderId, $productId, $quantity)
	{
		$this->db->insert('order_item', [
			'id_order' => $orderId,
			'id_product' => $productId,
			'quantity' => $quantity,
		]);
		return $this->db->insert_id();
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
