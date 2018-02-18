<?php namespace controllers\error;
/* Código  generado automáticamente por LidPanel*/
/* ======================================================================
$dp => Datos o Informacion desde la BD a la Página, si $bd esta descativada no enviara nada
$ctr => Instancia de Controller 
$bd => Si necesita usar la Base de datos true, caso contrario false
$auth => autenticación (booleano)
====================================================================== */
use app\clases\View;
use app\clases\Controller;

class error404Controller extends Controller {
private $dp;
private $ctr;
private $bd; 
private $auth;
function __construct(){
	$this -> auth = false;
	$this -> bd = false;
	$this -> ctr = new Controller($bd = $this -> bd);
}

function index() {
	if ($this -> authenticate($this -> auth)) {
		if (isset($_GET['subpage'])) {

		} else {
			/* Se requieren 4 datos : $p, $data y $id, $data tiene filtros : 
				$data => std , count
			*/
			View::renderPage('error.404');
		}
	} else {
		View::renderPage('error.unautorized');
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
function subAcceso($dato){

}
// Fin class
}
