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
			\__log($sql);	  	 
			$eje = $this->con->ejecutar($sql);
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
			\__log($sql);
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