<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Location_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getZipCode($zip)
	{
		$rs = $this->db
			->from('zipcode')
			->where('zipcode', $zip)
			->get()->result();
		if (!empty($rs)) {
			
			return $rs[0]->zipcode;
		}
		return false;
	}

	public function getSetCity($city)
	{
		$rs = $this->db->where('code', $city->cod)->get('city')->result();
		$id_city = null;
		if (!empty($rs)) {
            $id_city = $rs[0]->id;
        } else {
			$rsLocal = $this->getCityState($city->name, $city->state);
			
			if(!empty($rsLocal)) {
				$this->update('city', $rsLocal[0]->id, ['code'=>$city->cod]);
                     $id_city = $rsLocal[0]->id;
            } else {
                $id_state = $this->getSetState($city->state);

                $dataCity = [
                    'id_state' => $id_state,
                    'name' => $city->name,
                    'code' => $city->cod,
                ];
                $id_city = $this->insert('city', $dataCity);
            }
		}
		return $id_city;
	}

	private function getSetState($state, $statename = null) {

		$rs = $this->db->where('abbr', $state)->get('state')->result();
        if (!empty($rs)) {
            $id_state = $rs[0]->id;
        } else {
            $dadosRegion = [
                'name' => $statename ?? $state,
                'abbr' => $state
            ];
            $id_state = $this->insert('state', $dadosRegion);
        }
        return $id_state;
    }

	public function getState($abbr)
	{
		$rs = $this->db->where('abbr', $abbr)->get('state')->result();
		if (!empty($rs)) {
			return $rs[0];
		}
		return false;
	}

	public function getCityByNameState($cityName, $stateName)
	{
		$rs = $this->db
			->select('city.id, city.name, city.id_state')
			->where([
				'city.name' => $cityName,
				'state.abbr' => $stateName
			])
			->join('state', 'state.id = city.id_state', 'left')
			->get('city')
			->result();
		if (!empty($rs)) {
			return $rs[0];
		}
		return false;
	}

	public function getCityState($cityName, $stateId)
	{
		$rs = $this->db
			->where(['id_state' => $stateId, 'name' => $cityName])
			->get('city')->result();
		if (!empty($rs)) {
			return $rs[0];
		}
		return false;
	}

	public function getLocationById($zipcode)
	{
		$rs = $this->db
			->select('
				zipcode.zipcode 
				,zipcode.street 
				,zipcode.district 
				,city.name as city 
				,city.id as id_city
				,state.name as state 
				,state.abbr as uf')
			->join('city', 'city.id = zipcode.id_city', 'LEFT')
			->join('state', 'state.id = city.id_state', 'LEFT')
			->where('zipcode.zipcode ', $zipcode)
			->get('zipcode')->result();
		if (!empty($rs)) {
			return $rs[0];
		}
		return false;
	}

	public function update($table, $id, $data)
	{
		if ($this->db->update($table, $data, array('id' => $id))) {
			return true;
		}
		return false;
	}

	public function insert($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}
}
