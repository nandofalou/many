<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Location extends CI_Controller {

	private $CI;

    public function __construct() {
        $this->CI = &get_instance();
		$this->CI->load->model('location_model', 'zip');
    }

	public function getZip($zipCode)
	{
		$zipCode = preg_replace("/[^0-9]/im", "", $zipCode);
		$zip = $this->CI->zip->getZipCode($zipCode);
		
		if(empty($zip)) {
			$zip = $this->getRemoteZip($zipCode);
		}
		if ($zip) {
            return $this->CI->zip->getLocationById($zip);
        }
        return null;
	}

	private function getRemoteZip($zip) {
        $rs = $this->getJson($zip);
      
        if (!empty($rs) && !property_exists($rs, 'erro')) {
            $city = (object) [
                        'name' => $rs->localidade,
                        'state' => strtoupper($rs->uf),
                        'cod' => $rs->ibge,
            ];
            $idCity = $this->CI->zip->getSetCity($city);
			
            $data = (object) [
                        'street' => $rs->logradouro,
                        'district' => $rs->bairro,
                        'id_city' => $idCity,
                        'zipcode' => $zip,
                        'cod' => $rs->ibge,
            ];
            
            return $this->addZip($data);
        }
    }

	private function addZip($data) {
        $dataImport = array(
            'street' => !empty($data->street) ? $data->street : "Centro"
            , 'district' => !empty($data->district) ? $data->district : "Centro"
            , 'id_city' => $data->id_city
            , 'zipcode' => $data->zipcode
        );
        if($this->CI->zip->insert('zipcode', $dataImport)) {
			return $data->zipcode;
		}
		
		return null;
    }

	private function getJson($zipCode) {
        
        
        $header["Content-type"] = ['application/json; charset=utf-8'];

        $ch = curl_init();
        
        $url = "https://viacep.com.br/ws/{$zipCode}/json/";
        
        $options = array(
			CURLOPT_RETURNTRANSFER => true,   // return web page
			CURLOPT_HEADER         => false,  // don't return headers
			CURLOPT_FOLLOWLOCATION => true,   // follow redirects
			CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
			CURLOPT_ENCODING       => "",     // handle compressed
			CURLOPT_USERAGENT      => "test", // name of client
			CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
			CURLOPT_TIMEOUT        => 120,    // time-out on response
		); 
	
		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
	
		$content  = curl_exec($ch);
	
		curl_close($ch);
	
		return json_decode($content);
    }

}
