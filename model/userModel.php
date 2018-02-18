<?php namespace model;
/**
* extends Model
*/

use app\clases\gestionBD;
use model\Model;

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
		try {
			$sql = "INSERT INTO proyectos (descripProy,idDepa) VALUES ("
			."'".$datos['descripProy']."',"
			."'".$datos['idDepa']."')";
			$sql = $this -> con -> ejecutar($sql);
			if ($sql) {
				$id = $this -> con -> lastId();
				$html = $this -> crudHTML -> crearHtml($id);
				$response = ['sql' => $sql,
							'msg-html' => $html['msg'],
							'std-html' => $html['std'],
							'idProy' => $id];
			} else {
				$response = ['sql' => $sql,
							'msg-html' => 'No se ejecuto',
							'std-html' => false];
			}		
			return  $response;	
		} catch (Exception $e) {
			throw $e;
		}
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

	public function statusTable(){
		try {
			$sql = "SHOW TABLE STATUS LIKE '".$this -> table."'";
			return  $lista = $this -> con -> ejecutararray($sql);	
		} catch (Exception $e) {
			throw $e;
		}
	}
}