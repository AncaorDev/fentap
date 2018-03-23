<?php namespace controllers\page;
/* ======================================================================
$dp => Datos o Informacion desde la BD a la Página, si $bd esta descativada no enviara nada
$ctr => Instancia de Controller 
$bd => Si necesita usar la Base de datos true, caso contrario false
$auth => autenticación (booleano)
====================================================================== */
use app\clases\View;
use app\clases\Controller;
use app\clases\Functions as F;
use app\clases\Session as S;
use model\utilsModel;
use controllers\page\utilsController as utils;

class hidroboletinController extends Controller {
private $dp;
private $ctr;
private $bd; 
private $auth;
private $m_utils;
private $url;
private $c_utils;

function __construct($url){
    parent::__construct();
    $this->auth    = false; // Si para el acceso necesita estar autenticado
    $this->bd      = true; // Si se usara la conexión a la base de Datos
    $this->ctr     = new Controller($bd = $this -> bd); // Ejecutamos una instancia hacia el controlador general
    $this->m_utils = new utilsModel();
    $this->url     = $url;
    $this->c_utils = new utils($url);
}

function index() { //Función que se jecuta al recibir una variable del tipo controlador
  if (parent::authenticate($this -> auth)) { // Aquí la vista en caso de que el acceso necesite autenticación
    $datos = $this->c_utils->datosVista(); 
    // $datos['this_page'] = $this->listaPaginasbySlug($this->url['controller']);
	if ($this->url['metodo'] != null && $this->url['atributo'] != null) {
		if ($this->url['metodo'] == 'read') {
			$querynotice	= $this -> ctr -> extractData('boletin',null, $this->url['atributo']); 
			$datos['boletin'] = $querynotice['boletin']['datos'][0]; // asignación de datos a la variable array    
			$datos['boletin']['html_boletin'] = \decode_HTML($datos['boletin']['html_boletin']);
		}
	} else {
		\redirect('inicio');
	}

	View::renderPage('boletin',$this -> ctr -> ld,$datos);
  } else {
    // View::renderPage("error.unautorized");
    F::redirect('login'); // Redirección en caso de autorización
  }
}
// Fin class
}
