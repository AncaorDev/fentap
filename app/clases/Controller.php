<?php namespace app\clases;
/*===========================================
             Controller
===========================================*/
/* 
Clase que se encarga de recibir los datos según eso mostrar sus vistas correspondientes 
con la información adecuada.
*/
use Carbon\Carbon;
use model\pageModel;

class Controller {
/*  
	Leyenda de las variables
	$mp => Modelo pordefecto.
	$rsp => Respuesta de los Funciones Modelo.
	$lp => Lista del modelo por defecto
*/
private   $md;
private   $det;
private   $bd;
protected $ld;
private   $m_page;

// Método constructor, la funcion se ejecutara al instanciarla.
public function __construct($bd = true,$data = "page",$id="") {
	$this -> bd = $bd;
	if ($this -> bd) {
		$this->m_page  = new pageModel();
		$data = self::verificarData($data);
		$this -> ld = self::obtnerLista($data,$id);
	} 
	$this -> load('functions');
	// $this->ajaxPost();
} 
/* Función Mostrar Página con la lista general
 	Se requieren 4 datos : $p, $data y $id
*/
public function extractData($data = "", $id = "") {
	try {		
		if ($this -> bd) {
			$data = self::verificarData($data);
			if ($data === "error") {
				$this -> v -> render("error" , "500" , "modelo no definido");
			} else {
				return $lista = self::obtnerLista($data,$id);
			}			
		}	
	} catch (Exception $e) {
		throw $e;
	}
}

static public function verificarData($data){
	try {
		if ($data == "") {
			return "error";
		} else {
			$arrayData = explode("|", $data);
			$arrayCant = count($arrayData);
			$newArrayData = [];
			if (is_array($arrayData)) {			
				$newArrayData['model'] = $arrayData[0];
			} else {
				$newArrayData['model'] = $arrayData;
			}
			if ($arrayCant>1) {
				for ($i=0; $i < $arrayCant; $i++) { 
					if ($arrayData[$i] == "std") {
						$newArrayData['std'] = true;
					} if ($arrayData[$i] == "count") {
						$newArrayData['count'] = true;
					}
				}
			} 			
		}
		// $newArrayData = array('model' => $arrayData[0],'num' => $arrayCant);
		return $newArrayData;
	} catch (Exception $e) {
		throw $e;	
	}
}

static public function obtnerLista($data,$id){
	try {
		$model = 'model\\'.strtolower($data['model'])."Model";
		$compilated = [];
		if (class_exists($model)) {
			$datamodel = new $model();
			$actlistadetalles = "listaDetalles".ucfirst($data['model']);
			$compilated[ strtolower($data['model']) ] = $datamodel -> $actlistadetalles($id);
			isset($data['std']) ? $compilated['std'] = $datamodel -> statusTable() : '';
			isset($data['count']) ? $compilated['count'] = count($compilated[strtolower($data['model'])]) : '';
			// $compilated = ['lista' => $listadetallesdata, 'std' => ];	
			return $compilated;	
		} else {
			echo "Not defined name class in the model </br>";
		}		
	} catch (Exception $e) {
		throw $e;
	}
}

function authenticate($acceso){
	if ($acceso) {
		if(SESSION)  {
			if (isset($_SESSION['session'])) {
				if (($_SESSION['session'] == "yes")) {
					return true;
				} 
			}
		} 
	} else {
		return true;
	}
}

function load($load){
	include_once(realpath(__DIR__.'/../load/'.$load.'_load.php'));
}

function listaPaginas() {
	$sql = $this->m_page->listaDetallesPage();
	return $sql['datos'];
}

function listaPaginasbySlug($slug) {
	$sql = $this->m_page->listaDetallesPage('', $slug);
	return $sql['datos'][0];
}
//Fin clase
}
