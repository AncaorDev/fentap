<?php namespace model;
/**
* extends Model
*/
use app\clases\gestionBD;
use model\Model;

class pageModel 
{
	private $table;
	function __construct()
	{
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
	public function listaDetallesPage($id = ""){
		try {
			$sql = "SELECT page.id_Page, 
						   page.title_Page, 
						   page.state_Page,
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
					INNER JOIN USER 
						ON page.id_User=user.id_User 
					INNER JOIN templatepage 
						ON page.id_TemplatePage=templatepage.id_TemplatePage";
			$det = false; // 0
			if ($id != "") {
				$sql .= " WHERE page.slug_Page ='{$id}'"; 
				$det = true; // 1
			} 
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
			foreach($datos as $nombre_campo => $valor){
			  	$asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
			   	eval($asignacion); 
			}
			$slug_Page = preg_replace("/[0-9]+/", "", $slug_Page);
			$title_Page = preg_replace("/\-/", " ", $title_Page);
			$verificarSlug = $this -> verificarSlug($slug_Page);
			if ($verificarSlug['exis']) {
				$sql = "No ejecutada , Ya existe slug";
			} else {
				$VerificarOrden = $this -> verificarOrden();
				$dateCreate_Page = getDateTime(false);
				$order_Page = $VerificarOrden['max']+1;
				$sql = "INSERT INTO page (title_Page, slug_Page, order_Page, html_Page, state_Page, dateCreate_Page, dateModificate_Page, id_TemplatePage, id_AttributePage, id_User, id_UserModificate) VALUES (";
				$sql .= "{$title_Page},"; // title_Page 
				$sql .= "{$slug_Page},"; // slug_Page
				$sql .= $order_Page . ', '; // order_Page
				$sql .= '" ", '; // html_Page
				$sql .= '"Borrador", '; // state_Page
				$sql .= '"' . $dateCreate_Page . '", '; // dateCreate_Page
				$sql .= '"' . $dateCreate_Page . '", '; // dateModificate_Page
				$sql .= ' 1 ,'; // id_TemplatePage
				$sql .= $id_AttributePage . ','; // id_AttributePage
				$sql .= $id_User . ','; // id_User
				$sql .= $id_User . ')'; // id_UserModificate
				$sql = $this -> con -> ejecutar($sql);	
			}
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
}