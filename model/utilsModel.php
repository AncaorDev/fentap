<?php namespace model;
/**
* extends Model
*/
use app\clases\gestionBD;
use model\Model;

class utilsModel extends Model{ 
	public function __construct() {
		$this -> con = new gestionBD();
	}

	public function _getById($table , $campos = '*', $where = null){
		$data['error']  = 1;
		$data['data']	= null;
		$data['stdsql']	= 0;
		try{
			$sql = "SELECT {$campos} 
					  FROM {$table}";
			if ( $where != null) {
				if (is_array($where)) {
					$and = ' WHERE ';
					foreach ($where as $key => $value) {
						$sql .=	"$and {$key} = '{$value}'"; 
						$and = ' AND '; 
					}
				}
			}
			$eje = $this->con->ejecutar($sql);
			if (count($eje) == 0) {
				throw new Exception('Error al extraer información');
			}
			$data['error']  = 0;
			$data['data']   = $eje->num_rows;
			if ($eje->num_rows > 0) {
				$data['error']  = 0;
				$data['stdsql'] = 1;
				$data['data']  = $this->con->ejecutararray($sql);
				$this -> con -> liberar($eje);
			}
		} catch(Exception $e){
			$data['error'] = $e->getMessage();
		}
		return $data;
	}

	function updateTable($table, $datos, $where = null){
		$sql    = "UPDATE {$table} SET ";
		$concat = '';
		foreach ($datos as $key => $val) {
			$sql .= "{$concat} {$key} = '{$val}' ";
			$concat = ',';
		}
		if ($where != null) {
			$and = "WHERE ";
			foreach ($where as $key => $val) {
				$sql .= "{$and} {$key} = '{$val}' ";
				$and = " AND ";
			}
		}
		$sql = $this -> con -> ejecutar($sql);	
		$compilated = $arrayName = array('sql' => $sql, 'upd' => $sql);
		return $compilated;
	}

	public function _deleteRow($table , $where = null){
		$data['error']  = 1;
		$data['datos']	= null;
		$data['stdsql']	= 0;
		try{
			$sql = "DELETE FROM {$table}";
			if ( $where != null) {
				if (is_array($where)) {
					$and = ' WHERE ';
					foreach ($where as $key => $value) {
						$sql .=	"$and {$key} = '{$value}'"; 
						$and = ' AND '; 
					}
				}
						
			}	
			$eje = $this->con->ejecutar($sql);
			if ($eje == 0) {
				$data['error']  = 0;
				$data['stdsql'] = 1;
				$this -> con -> liberar($eje);	
			} 
		} catch(Exception $ex){
			$data['error'] = $e->getMessage();
		}
	}	
	public function logout(){
			Session::init();  
			Session::destroy();  
	}
	public function __destruct () {
	 	$this -> con -> cerrar();
	}
}