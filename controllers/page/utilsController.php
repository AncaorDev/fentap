<?php namespace controllers\page;
/* ======================================================================
$dp => Datos o Informacion desde la BD a la Página, si $bd esta descativada no enviara nada
$ctr => Instancia de Controller
$bd => Si necesita usar la Base de datos true, caso contrario false
$auth => autenticación (booleano)
====================================================================== */
use app\clases\Controller;
use app\clases\Session as S;
use model\utilsModel;

class utilsController extends Controller {
    private $dp;
    private $ctr;
    private $bd;
    private $auth;
    private $m_utils;
    private $url;

    function __construct($url){
        parent::__construct();
        $this->auth    = false; // Si para el acceso necesita estar autenticado
        $this->bd      = true; // Si se usara la conexión a la base de Datos
        $this->ctr     = new Controller($bd = $this -> bd); // Ejecutamos una instancia hacia el controlador general
        $this->m_utils = new utilsModel();
        $this->url     = $url;
    }

    function datosVista() {
        $datos['pages']        = $this->listaPaginas();
        $datos['this_page']    = $this->listaPaginasbySlug($this->url['controller']);
        $datos['this_baseURL'] = $this->url['controller'];

        $querynotice        = $this -> ctr -> extractData('notices|count'); // asignación de datos a la variable array
        $datos['notices']   = $querynotice['notices']['datos'];

        $queryboletin        = $this -> ctr -> extractData('boletin|count'); // asignación de datos a la variable array
        $datos['boletines']  = $queryboletin['boletin']['datos'];

        $querypublish        = $this -> ctr -> extractData('publish|count'); // asignación de datos a la variable array
        $datos['publishes']  = $querypublish['publish']['datos'];

        $general = $this->m_utils->_getById('general');
        $datos['general']  = $general['data'];

        return $datos;
    }
// Fin class
}
