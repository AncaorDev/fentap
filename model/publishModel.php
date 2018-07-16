<?php namespace model;
/**
* extends Model
*/
use app\clases\gestionBD;
use model\Model;

class publishModel extends Model{ 
	
	private $table;

	public function __construct() {
		parent::__construct();
		$this -> table = 'publish';
		$this -> con = new gestionBD();
	}

	function listaDetallesPublish($val = null, $slug_publish = null){
		try{
			$det = false;
			$sql = "SELECT  u.name_User,
							n.id_publish,
							n.title_publish,
							n.slug_publish,
							n.descrip_publish,
							n.date_create,
							n.date_modificate,
							n.html_publish,
							n.img_portada
					FROM {$this -> table} n, user u
					WHERE n.id_User=u.id_User";
			if ($val != null ) {
				$det = true;
				$sql .= " AND n.id_publish={$val}";	
			}
			if ($slug_publish != null ) {
				$det = true;
				$sql .= " AND n.slug_publish='{$slug_publish}'";	
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

	function newPublish($datos) {
		try{
			extract($datos);
			$img_portada   = isset($img_portada)   ? $img_portada : '';
			$sql = "INSERT INTO publish (title_publish,
									   descrip_publish,
									   flg_publicado,
									   html_publish,
									   img_portada,
									   slug_publish,
									   id_User)
						      VALUES ('{$title_publish}',
						  			  '{$descrip_publish}' ,
						  			   {$flg_publicado},
						  			  '{$html_publish}',
						  			  '{$img_portada}',
						  			  '{$slug_publish}',
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

