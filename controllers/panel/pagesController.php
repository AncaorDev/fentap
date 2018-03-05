<?php namespace controllers\panel;
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
use model\panelModel;
use model\utilsModel;

class pagesController extends Controller {
private $dp;
private $ctr;
private $bd; 
private $auth;
private $m_panel;
private $m_utils;
private $url;

function __construct($url){
	$this -> auth  = true; // Si para el acceso necesita estar autenticado
	$this -> bd    = true; // Si se usara la conexión a la base de Datos
	$this -> ctr   = new Controller($bd = $this -> bd); // Ejecutamos una instancia hacia el controlador general
	$this->m_panel = new panelModel();
	$this->m_utils = new utilsModel();
	$this->url     = $url;
}

function index() { //Función que se jecuta al recibir una variable del tipo controlador
	if (parent::authenticate($this -> auth)) { // Aquí la vista en caso de que el acceso necesite autenticación
		$permisos = $this->m_panel->getPermisosByIdUser(S::getValue('id_user'));
		$tabs = $this->m_utils->_getById('tabs');
		$data['permisos'] = $permisos['data'];
		$data['tabs']     = $tabs['data'];
		View::renderPage('panel/pages',$this->ctr->ld,$data);
	} else {
		// View::renderPage("error.unautorized");
		F::redirect('panel'); // Redirección en caso de autorización
	}
}

// Fin class
}
