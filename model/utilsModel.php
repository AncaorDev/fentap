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
		try{

			$sql = "SELECT {$campos} 
					  FROM {$table}";
			if ( $where != null) {
				if (is_array($where)) {
					$and = ' WHERE ';
					foreach ($where as $key => $value) {
						$sql .=	"$and {$key} = {$value}"; 
						$and = ' AND '; 
					}
				}
						
			}		  	 
			$eje = $this->con->ejecutar($sql);
			if ($eje->num_rows > 0) {
				$error  = 0;
				$stdsql = 1;
				$datos  = $this->con->ejecutararray($sql);
				$this -> con -> liberar($eje);	
			} else {
				$datos	= null;
				$stdsql	= 0;
				$error  = 1;
			}
			return array('stdsql' => $stdsql, 'error' => $error , 'data' => $datos);
		}
		catch(Exception $ex){
			throw $ex;
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