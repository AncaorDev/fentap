<?php namespace model;
/**
* extends Model
*/
use app\clases\gestionBD;
use model\Model;

class mapaModel extends Model{ 
	
	private $table;

	public function __construct() {
		parent::__construct();
		$this -> table = 'mapa';
		$this -> con = new gestionBD();
	}

	function listaDetallesMapa($val = null, $slug_boletin = null){
		try{
			$det = false;
			$sql = "SELECT  u.name_User,
							t.id_mapa,
							t.slug_mapa,
							t.date_create,
							t.date_modificate,
							t.html_mapa,
							d.id_departamento,
							d.departamento
					FROM {$this -> table} t, user u, ubdepartamento d
					WHERE t.id_User=u.id_User 
					AND   t.id_departamento=d.id_departamento";
			if ($val != null ) {
				$det = true;
				$sql .= " AND t.id_mapa={$val}";	
			}
			if ($slug_boletin != null ) {
				$det = true;
				$sql .= " AND t.slug_mapa='{$slug_boletin}'";	
			}	
			$lista = $this -> con -> ejecutararray($sql);
			\__log($lista);
			$statusTable = $this -> statusTable();
			$compilated = array('datos' => $lista, 'status' => $statusTable, 'det' => $det);
			return  $compilated;
		}
		catch(Exception $ex){
			throw $ex;
		}
	}

	function newMapa($datos) {
		try{
			extract($datos);
			$sql = "INSERT INTO mapa ( flg_publicado, 
									   html_mapa,
									   slug_mapa,
									   id_User) 
						      VALUES ( 1,
						  			  '{$html_mapa}',
						  			  '{$slug_mapa}',
						  			  '{$id_User}')";
			$sql = $this -> con -> ejecutar($sql);	
			\__log($sql);
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

