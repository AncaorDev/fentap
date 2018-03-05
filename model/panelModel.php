<?php namespace model;
/**
* extends Model
*/
use app\clases\gestionBD;
use model\Model;

class panelModel extends Model{ 
	public function __construct() {
		$this -> con = new gestionBD();
	}

	public function getPermisosByIdUser($id_user){
		try{
			$sql = "SELECT  t.id_tab,
							t.desc_tab, 
							at.id_acciones_tab, 
							at.desc_accion, 
							at.url_accion 
					FROM tabs t,acciones_tab at 
					WHERE t.id_tab = at.id_tab 
					AND at.flg_activo = 1  
					AND CASE WHEN (SELECT id_tab 
									 FROM tab_user 
									WHERE id_user = {$id_user} LIMIT 1) = 0 
							 THEN 1 =1
                             ELSE t.id_tab IN (SELECT id_tab FROM tab_user WHERE id_user = {$id_user})
						END";
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

	public function getTabsByIdUser($id_user) {
		try{
			$sql = "SELECT  * 
					FROM tabs t
					WHERE CASE WHEN (SELECT id_tab 
									 FROM tab_user 
									WHERE id_user = {$id_user} 
									LIMIT 1) = 0 
							   THEN 1 = 1
							   ELSE t.id_tab IN (SELECT id_tab FROM tab_user WHERE id_user = {$id_user})
						  END";
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