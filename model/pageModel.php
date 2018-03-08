<?php namespace model;
/**
* extends Model
*/
use app\clases\gestionBD;
use app\clases\Log;
use model\Model;

class pageModel  extends Model
{
	private $table;
	function __construct() {
		parent::__construct();
		//Instanciamos la BD
		$this -> con = new gestionBD();
		$this -> table = "page";
	}
	public function listaPage()
	{
		try {
			$sql = "SELECT * FROM {$this -> table}";
			//Ejecución de consulta
			//$ejePagina = $con -> ejecutar($sql);	
			return  $lista = $this -> con -> ejecutararray($sql);	
		} catch (Exception $e) {
			throw $e;
		}
	}
	public function listaDetallesPage($id = "", $slug = ""){
		try {
			$sql = "SELECT page.id_Page, 
						   page.title_Page, 
						   page.state_Page,
						   page.html_Page,
						   templatepage.code_TemplatePage,
						   page.slug_Page, 
						   attributepage.id_AttributePage,
						   attributepage.name_AttributePage, 
						   user.id_User,
						   user.name_User, 
						   page.order_Page 
					FROM page 
					INNER JOIN attributepage 
						ON page.id_AttributePage=attributepage.id_attributepage 
					INNER JOIN user 
						ON page.id_User=user.id_User 
					INNER JOIN templatepage 
						ON page.id_TemplatePage=templatepage.id_TemplatePage";
			$det = false; // 0
			if ($id != "") {
				$sql .= " WHERE page.id_Page ='{$id}'"; 
				$det = true; // 1
			} 
			if ($slug != "") {
				$sql .= " WHERE page.slug_Page ='{$slug}'"; 
				$det = true; // 1
			} 
			Log::_log($sql);
			$lista = $this -> con -> ejecutararray($sql);
			$statusTable = $this -> statusTable();
			$listaAttributePage = $this -> listaAttributePage();
			$compilated = array('datos' => $lista, 'status' => $statusTable, 'attributepage' => $listaAttributePage, 'det' => $det);
			return  $compilated;
		} catch (Exception $e) {
			throw $e;
		}
	}
	public function registrarPage($datos){ 
		try {	
			extract($datos);
			$VerificarOrden = $this -> verificarOrden();
			$order_Page = $VerificarOrden['max']+1;
			$sql = "INSERT INTO page (title_Page, 
									  slug_Page, 
									  order_Page, 
									  html_Page,
									  state_Page, 
									  dateCreate_Page, 
									  dateModificate_Page, 
									  id_TemplatePage,
									  id_AttributePage,
									  id_User, 
									  id_UserModificate) 
						      VALUES ('{$title_Page}',
						  			  '{$slug_Page}' ,
						  			   {$order_Page},
						  			  '',
						  			  '{$state_page}',
						  			  '{$dateCreate_Page}',
						  			  '{$dateCreate_Page}',
						  			  1,
						  			  1,
						  			  {$id_User},
						  			  {$id_User})";
			$sql = $this -> con -> ejecutar($sql);	
			$compilated = $arrayName = array('sql' => $sql, 'upd' => $sql);
			return $compilated;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function verificarSlug($slug){
		try {
			$sql = "SELECT * FROM page WHERE slug_Page='{$slug}' LIMIT 1";
			$sql = $this -> con -> ejecutararray($sql);
			$exis = false;
			if (count($sql)>0) {
				$exis = true;
			} 			
			$response =  array('sql' => $sql , 'exis' => $exis);
			return $response;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function verificarOrden(){
		try {
			$sql = "SELECT order_Page FROM page";
			$sql = $this -> con -> ejecutararray($sql);
			$max = max($sql);
			$response =  array('sql' => $sql , 'max' => $max['order_Page']);
			return $response;
		} catch (Exception $e) {
			throw $e;
		}
	}
	public function CambiarOrden($idPage){

	}
	
	function updatePage($datos, $where ){
			extract($datos);
			$VerificarOrden = $this -> verificarOrden();
			$order_Page = $VerificarOrden['max']+1;
			$sql    = 'UPDATE page SET ';
			$concat = '';
			foreach ($datos as $key => $val) {
				$sql .= "{$concat} {$key} = '{$val}' ";
				$concat = ',';
			}
			$and = "WHERE ";
			foreach ($where as $key => $val) {
				$sql .= "{$and} {$key} = '{$val}' ";
				$and = " AND ";
			}
			\__log($sql);
			$sql = $this -> con -> ejecutar($sql);	
			$compilated = $arrayName = array('sql' => $sql, 'upd' => $sql);
			return $compilated;
	}
	public function listaAttributePage(){
		try {
			$sql = "SELECT * FROM attributepage";
			return  $lista = $this -> con -> ejecutararray($sql);	
		} catch (Exception $e) {
			throw $e;
		}
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