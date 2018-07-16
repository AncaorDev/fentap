<?php namespace controllers\page;
/* ======================================================================
$dp => Datos o Informacion desde la BD a la Página, si $bd esta descativada no enviara nada
$ctr => Instancia de Controller 
$bd => Si necesita usar la Base de datos true, caso contrario false
$auth => autenticación (booleano)
====================================================================== */
use app\clases\View;
use app\clases\Controller;
use app\clases\Session as S;
use model\utilsModel;
use model\mapaModel;

class mapaController extends Controller {
private $dp;
private $ctr;
private $bd; 
private $auth;
private $m_utils;
private $m_mapa;
private $url;

function __construct($url){
    parent::__construct();
    $this->auth    = false; // Si para el acceso necesita estar autenticado
    $this->bd      = true; // Si se usara la conexión a la base de Datos
    $this->ctr     = new Controller($bd = $this -> bd); // Ejecutamos una instancia hacia el controlador general
    $this->m_utils = new utilsModel();
    $this->m_mapa  = new mapaModel();
    $this->url     = $url;
}

function index() { //Función que se jecuta al recibir una variable del tipo controlador
  if (parent::authenticate($this -> auth)) { // Aquí la vista en caso de que el acceso necesite autenticación
  	// ---- En esta parte el programador es libre de manejarlo a su manera //
	$datos['pages']     = $this->listaPaginas();
    // $datos['this_page'] = $this->listaPaginasbySlug($this->url['controller']);
	if ($this->url['metodo'] != null && $this->url['atributo'] != null) {
		if ($this->url['metodo'] == 'read') {
            $mapa         = $this->m_mapa->listaDetallesMapa(null,$this->url['atributo']);
            $datos['mapa'] = $mapa['datos'][0];
            $datos['mapa']['html_mapa'] = \decode_HTML($datos['mapa']['html_mapa']);
            // $querynotice    = $this -> ctr -> extractData('notices',null, $this->url['atributo']);
            // $datos['notice'] = $querynotice['notices']['datos'][0]; // asignación de datos a la variable array
            // $datos['notice']['html_notice'] = \decode_HTML($datos['notice']['html_notice']);
		}
	} else {
		\redirect('inicio');
	}

	View::renderPage('mapa',$this -> ctr -> ld,$datos);
  	} else {
		\redirect('inicio');
        // View::renderPage("error.unautorized");
         // Redirección en caso de autorización
  	}
}
// Fin class
}
