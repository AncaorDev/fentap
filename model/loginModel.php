<?php 
class loginModel { 
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
			$sql = $this -> con -> ejecutar("SELECT * FROM user WHERE (name_User='$user' OR mail_User='$user') AND pass_User='$pass' LIMIT 1;");
			if ($sql) {
				$stdsql = true;
				$datos = $this -> con -> recorrer($sql);
				if ($this -> con -> filas($sql) > 0) {
				$msg = 1;
				   	if ($this -> sesion) {
				       $duracion_sesion = (60*60*24);
				    } else {
				        $duracion_sesion = "300";
				    }

				ini_set('session.upload_progress.enabled', 1);
				ini_set("session.cookie_lifetime",$duracion_sesion);
				session_cache_expire($duracion_sesion);
				session_set_cookie_params($duracion_sesion);			    
				// Session::init();    
				Session::setValue("session" , "yes");
				Session::setValue("id_User" , $datos['id_User']);
				Session::setValue("name_User" , $datos['name_User']);
				Session::setValue("imgProfile_User" , $datos['imgProfile_User']);
				Session::setValue("duracion" , $duracion_sesion);
				} else {
					$msg = 0;
					$duracion_sesion = "none";	
				}
				$this -> con -> liberar($sql);	
			} else {
				$duracion_sesion = "sin definir";
				$stdsql = false;
				$msg = "error en la ejecución";	
			}
			$response = array("stdsql" => $stdsql, "msg" => $msg ,"duracion" => $duracion_sesion);
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

