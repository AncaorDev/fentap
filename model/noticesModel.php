<?php namespace model;
/**
* extends Model
*/
use app\clases\gestionBD;
use app\clases\Log;
use model\Model;

class noticesModel extends Model{ 
	
	private $table;

	public function __construct() {
		parent::__construct();
		$this -> table = 'notice';
		$this -> con = new gestionBD();
	}

	function listaDetallesNotices($val = null, $slug_notice = null){
		try{
			$det = false;
			$sql = "SELECT  u.name_User,
							n.id_notice,
							n.title_notice,
							n.slug_notice,
							n.descrip_notice,
							n.date_create,
							n.date_modificate,
							n.html_notice,
							n.img_portada,
							n.flg_destacado
					FROM notice n, user u
					WHERE n.id_User=u.id_User";
			if ($val != null ) {
				$det = true;
				$sql .= " AND n.id_notice={$val}";	
			}
			if ($slug_notice != null ) {
				$det = true;
				$sql .= " AND n.slug_notice='{$slug_notice}'";	
			}		
			$lista = $this -> con -> ejecutararray($sql);
			$statusTable = $this -> statusTable();
			$compilated = array('datos' => $lista, 'status' => $statusTable, 'det' => $det);
			return  $compilated;
		}
		catch(Exception $ex){
			throw $ex;
		}
	}

	function newNoticia($datos) {
		try{
			extract($datos);
			$flg_destacado = isset($flg_destacado) ? $flg_destacado : ''; 
			$sql = "INSERT INTO notice (title_notice, 
									   descrip_notice, 
									   flg_publicado, 
									   html_notice,
									   img_portada,
									   slug_notice,
									   flg_destacado, 
									   id_User) 
						      VALUES ('{$title_notice}',
						  			  '{$descrip_notice}' ,
						  			   {$flg_publicado},
						  			  '{$html_notice}',
						  			  '{$img_portada}',
						  			  '{$slug_notice}',
						  			  '{$flg_destacado}',
						  			  '{$id_User}')";
			$sql = $this -> con -> ejecutar($sql);	
			$compilated = $arrayName = array('sql' => $sql, 'upd' => $sql);
		}
		catch(Exception $ex){
			throw $ex;
		}
		return $compilated;
	}

	public function statusTable(){
		try {
			$sql = "SHOW TABLE STATUS LIKE '{$this -> table}'";
			return  $lista = $this -> con -> ejecutararray($sql);	
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

	public function __destruct () {
		$this -> con -> cerrar();
	}
}

