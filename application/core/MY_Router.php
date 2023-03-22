<?php if ( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class MY_Router extends CI_Router {

	public function __construct( ) {
		parent::__construct( );
	}

	/**
	 * Adiciona o REQUEST_METHOD no início do método da classe.
	 * get_, post_, put_, delete_ ...
	 */
	public function set_method($method)
	{
		$_verb = strtolower($_SERVER['REQUEST_METHOD']);
		if(strpos($_verb, 'api/')===0) {
			$this->method = $_verb.'_'.$method;
		} else {
			$this->method = $method;
		}
		// die($this->method);
	}

}

?>
