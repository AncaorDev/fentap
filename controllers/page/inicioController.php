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
use model\utilsModel;

class inicioController extends Controller {
private $dp;
private $ctr;
private $bd; 
private $auth;
private $url;
private $m_utils;

function __construct($url){
    parent::__construct();
    $this->auth    = false; // Si para el acceso necesita estar autenticado
    $this->bd      = true; // Si se usara la conexión a la base de Datos
    $this->ctr     = new Controller($bd = $this -> bd); // Ejecutamos una instancia hacia el controlador general
    $this->m_utils = new utilsModel();
    $this->url     = $url;
}

function index() { //Función que se jecuta al recibir una variable del tipo controlador
  if (parent::authenticate($this -> auth)) { // Aquí la vista en caso de que el acceso necesite autenticación
    $datos['pages']     = $this->listaPaginas();
    $datos['this_page'] = $this->listaPaginasbySlug($this->url['controller']);
    $querynotice        = $this -> ctr -> extractData('notices|count'); // asignación de datos a la variable array    
    $datos['notices']   = $querynotice['notices']['datos'];

    $queryboletin        = $this -> ctr -> extractData('boletin|count'); // asignación de datos a la variable array
    $datos['boletines']  = $queryboletin['boletin']['datos'];

    $querymapa           = $this -> ctr -> extractData('mapa'); // asignación de datos a la variable array
    $mapas               = $querymapa['mapa']['datos'];
    $regiones            = array('d200' => '1',
                                  'd26' => '2');
    foreach ($mapas as $mapa) {
        $index = 'd'.$mapa['id_departamento'];
        $regiones[$index] = '3';
        $projects[$mapa['id_departamento']] = 'Ver Información';
    }
    $datos['regiones']   = json_encode($regiones);
    $datos['projects']   = json_encode($projects);
    View::renderPage('inicio',$this -> ctr -> ld,$datos);
  } else {
    // View::renderPage("error.unautorized");
    F::redirect('login'); // Redirección en caso de autorización
  }
}

function goToDepartamento() {
    $code = $_POST['code'];
    $conservar = '0-9'; // juego de caracteres a conservar
    $regex = sprintf('~[^%s]++~i', $conservar); // case insensitive
    \__log($regex);
    $id_departamento = preg_replace($regex, '', $code);
    \__log($id_departamento);
    $query_departamento = $this->m_utils->_getById('mapa','*',array('id_departamento' => $id_departamento));
    \__log($query_departamento);
}
// Fin class
}
