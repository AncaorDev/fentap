<?php namespace model;
/**
* 
*/
use app\clases\gestionBD;
use model\Model;

class phraseModel extends Model{
	private $con;
	private $table;
	private $sql;
	function __construct()
	{
		//Instanciamos la BD
		$this -> con = new gestionBD();
		//Tabla
		$this -> table = "phrase";
	}

	function listaDetallesPhrase($id="") {
		try {
			if (isset($this -> con -> state)) {
				
			} else {
				//Consulta
				$this -> sql = "SELECT * FROM {$this -> table}";
				if ($id != "") { $this -> sql .= " WHERE  ='{$id}'"; }
				//Ejecución de consulta
				$lista = $this -> con -> ejecutararray($this -> sql);	
				return  $lista;	
			}
		}
			catch(Exception $ex){
			throw $ex;
		}
	}

	public function statusTable() {
		try {
			$this -> sql = "SHOW TABLE STATUS LIKE '{$this -> table}'";	
			return  $lista = $this -> con -> ejecutararray($this -> sql);
			$this->_destruct();	
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function _destruct()
	{
		$this -> con -> liberar($this -> sql);
		$this -> con -> cerrar();
	}
}