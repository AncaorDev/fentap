<?php namespace model;
/**
* extends Model
*/
use app\clases\gestionBD;
use model\Model;

class noticesModel extends Model{ 
	
	private $table;

	public function __construct() {
		parent::__construct();
		$this -> table = "page";
		$this -> con = new gestionBD();
	}

	public function getNoticias($val){
		try{
			
		}
		catch(Exception $ex){
			throw $ex;
		}
	}

	public function __destruct () {
	 	$this -> con -> cerrar();
	}
}