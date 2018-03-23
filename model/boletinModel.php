<?php namespace model;
/**
* extends Model
*/
use app\clases\gestionBD;
use model\Model;

class boletinModel extends Model{ 
	
	private $table;

	public function __construct() {
		parent::__construct();
		$this -> table = 'boletin';
		$this -> con = new gestionBD();
	}

	function listaDetallesBoletin($val = null, $slug_boletin = null){
		try{
			$det = false;
			$sql = "SELECT  u.name_User,
							n.id_boletin,
							n.title_boletin,
							n.slug_boletin,
							n.descrip_boletin,
							n.date_create,
							n.date_modificate,
							n.html_boletin,
							n.img_portada,
							n.flg_destacado
					FROM {$this -> table} n, user u
					WHERE n.id_User=u.id_User";
			if ($val != null ) {
				$det = true;
				$sql .= " AND n.id_boletin={$val}";	
			}
			if ($slug_boletin != null ) {
				$det = true;
				$sql .= " AND n.slug_boletin='{$slug_boletin}'";	
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

	function newBoletin($datos) {
		try{
			extract($datos);
			$flg_destacado = isset($flg_destacado) ? $flg_destacado : ''; 
			$img_portada   = isset($img_portada)   ? $img_portada : ''; 
			$sql = "INSERT INTO boletin (title_boletin, 
									   descrip_boletin, 
									   flg_publicado, 
									   html_boletin,
									   img_portada,
									   slug_boletin,
									   flg_destacado, 
									   id_User) 
						      VALUES ('{$title_boletin}',
						  			  '{$descrip_boletin}' ,
						  			   {$flg_publicado},
						  			  '{$html_boletin}',
						  			  '{$img_portada}',
						  			  '{$slug_boletin}',
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

