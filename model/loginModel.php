<?php namespace model;
/**
* extends Model
*/
use app\clases\gestionBD;
use model\Model;

class loginModel extends Model{
	private $con;
	private $user;
	private $pass;
	private $sesion;
	public function __construct() {
		$this -> con = new gestionBD();
	}

	public function login($user,$pass,$sesion){
		try{
			$this -> user = $user;
			$this -> pass = $pass;
			$this -> sesion = $sesion;
			$pass = encriptar($this -> pass);
			$sql = $this -> con -> ejecutar("SELECT * FROM user
													  WHERE (name_User='{$user}' OR mail_User='{$user}')
													  AND pass_User='$pass' LIMIT 1;");
			if ($sql) {
				$stdsql = true;
				$datos = $this -> con -> recorrer($sql);
				if ($this -> con -> filas($sql) > 0) {
					$msg = 1;
				} else {
					$msg = 0;
					$duracion_sesion = "none";
				}
				$this -> con -> liberar($sql);
			} else {
				$datos           = null;
				$duracion_sesion = "sin definir";
				$stdsql          = false;
				$msg 		     = "error en la ejecución";
			}
			$response = array('stdsql' => $stdsql, 'msg' => $msg , 'data' => $datos);
			return $response;
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

