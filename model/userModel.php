<?php namespace model;
/**
* extends Model
*/

use app\clases\gestionBD;
use model\Model;
use Exception;

class userModel 
{
	private $table;
	private $listDetalle;
	function __construct()
	{
		//Instanciamos la BD
		$this -> con = new gestionBD();
		$this -> table = "user";
	}
	public function listaUser()
	{
		try {
			$sql = "SELECT * FROM ".$this -> table;
			//Ejecución de consulta
			//$ejePagina = $con -> ejecutar($sql);	
			return  $lista = $this -> con -> ejecutararray($sql);	
		} catch (Exception $e) {
			throw $e;
		}
	}
	public function listaDetallesUser($id = ""){
		try {
			$sql = "SELECT user.name_User, user.mail_User, user.phrase_User, user.imgProfile_User, user.imgCover_User, user.web_User, user.id_TypeUser, typeuser.name_TypeUser, person.name_Person, person.lastName_Person, person.sex_Person, person.dateBirth_Person FROM user INNER JOIN typeuser ON user.id_TypeUser=typeuser.id_TypeUser INNER JOIN person ON user.id_User=person.id_User";
			$det = false; // 0
			if ($id != "") {
				$sql .= " WHERE user.id_User = ".$id; 
				$det = true; // 1

			}
			//Ejecución de consulta
			//$ejePagina = $con -> ejecutar($sql);	
			// return $sql;
			$lista = $this -> con -> ejecutararray($sql);
			$statusTable = $this -> statusTable();
			$listaTypeUser = $this -> listaTypeUser();
			$compilated = array('datos' => $lista, 'status' => $statusTable, 'typeuser' => $listaTypeUser,'det' => $det);
			return  $compilated;
		} catch (Exception $e) {
			throw $e;
		}
	}
	public function listaTypeUser(){
		try {
			$sql = "SELECT * FROM typeuser";
			return  $lista = $this -> con -> ejecutararray($sql);	
		} catch (Exception $e) {
			throw $e;
		}
	}
	public function registrarUser($datos){
		$data['error'] = 1;
		$data['msj']   = null;
		try {
			extract($datos);
			$name_User = trim($name_User);
			$sql = "INSERT INTO user (name_User, pass_User, mail_User, status_User) 
							VALUES ('{$name_User} ', '{$pass_User}', '', '1')";
			$sql = $this->con->ejecutar($sql);
			if ($sql != 1) {
				throw new Exception($sql);
			}
			$id_user = $this->con->lastId();
			$permisos = explode(',', $permisos);
			foreach ($permisos as $permiso) {
				$sql = "INSERT INTO tab_user (id_user, id_tab) 
							VALUES ('{$id_user} ', '{$permiso}')";
				$this->con->ejecutar($sql);
			}	
			$data['error'] = 0;
			$data['msj']   = 1;
		} catch (Exception $e) {
			$data['msj']  =  $e->getMessage();
		}
		return $data;
	}
	
	public function getPermisosbyIdUser($id_user) {
		$sql = "SELECT * 
				  FROM  tabs t,tab_user tu 
				  WHERE CASE WHEN (SELECT id_tab FROM tab_user WHERE id_user = {$id_user} LIMIT 1) = 0 
					         THEN 1 = 1
		                     ELSE t.id_tab=tu.id_tab 
		                END
				   AND id_user = {$id_user};";
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

	public function deleterUser($id_user){
		$data['error'] = 1;
		$data['msj']   = null;
		try {
			$sql = "DELETE FROM user WHERE id_User = {$id_user}";
			$sql = $this->con->ejecutar($sql);
			$sql = "DELETE FROM tab_user WHERE id_user = {$id_user}";
			$sql = $this->con->ejecutar($sql);
			if ($sql != 1) {
				throw new Exception($sql);
			}
			$data['error'] = 0;
			$data['msj']   = 1;
		} catch (Exception $e) {
			$data['msj']  =  $e->getMessage();
		}
		return $data;
	}

	public function actualizarUser($datos){
		try {
			$sql = 'UPDATE user u INNER JOIN person p ON u.id_User=p.id_User SET ';
			$sql .= ' u.mail_User="' . $datos['mail_User'] . '",';
			$sql .= ' u.phrase_User="'. $datos['phrase_User'] . '",'; 
			$sql .= ' u.web_User="'.$datos['web_User'] . '",';
			$sql .= ' p.name_person="'.$datos['name_person'] . '",';
			$sql .= ' p.lastName_Person="'.$datos['lastName_Person'] . '",';
			$sql .= ' p.sex_Person="'.$datos['sex_Person'] . '",';
			$sql .= ' p.dateBirth_Person="'.$datos['dateBirth_Person'] . '"';
			$sql .= ' WHERE u.id_User ='.$datos['id_User'];
			$sql = $this -> con -> ejecutar($sql);
			if ($sql) {
				$response = ['sql' => $sql,
							'upd' => true];
			} else {
				$response = ['sql' => $sql,
							'upd' => false];
			}
			return  $response;
		} catch (Exception $e) {
			throw $e;
		}
	}
	public function actualizarImgUser($datos){
		try {
			$act = $datos['action'];
			if ($act == "actProfile") {
				$upd = "imgProfile_User";
			} else if ($act == "actCover") {
				$upd = "imgCover_User";
			}
			$sql = 'UPDATE user SET ';
			$sql .= $upd . '="' . $datos[$upd] . '"';
			// Session::unsetValue($upd);
			Session::setValue(  $upd ,  $datos[$upd] );
			$sql = $this -> con -> ejecutar($sql);
			if ($sql) {
				$response = ['sql' => $sql,
							'upd' => true];
			} else {
				$response = ['sql' => $sql,
							'upd' => false];
			}
			return  $response;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function setAutoincrement($num){
		try {
			$sql = "ALTER TABLE {$this -> table} AUTO_INCREMENT =".$num;
			$rows = $this -> con -> ejecutar($sql);
			if ($rows) {
				$rows = true;
			} else {
				$rows = false;
			}
			$data = array('sql' => $rows);
			return $data;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function statusTable(){
		try {
			$sql = "SHOW TABLE STATUS LIKE '".$this -> table."'";
			return  $lista = $this -> con -> ejecutararray($sql);
		} catch (Exception $e) {
			throw $e;
		}
	}
}