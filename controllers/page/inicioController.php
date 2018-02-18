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

class inicioController extends Controller {
private $dp;
private $ctr;
private $bd; 
private $auth;
function __construct(){
	$this -> auth = false; // Si para el acceso necesita estar autenticado
	$this -> bd = true; // Si se usara la conexión a la base de Datos
	$this -> ctr = new Controller($bd = $this -> bd); // Ejecutamos una instancia hacia el controlador general
}

function index() { //Función que se jecuta al recibir una variable del tipo controlador
	if (parent::authenticate($this -> auth)) { // Aquí la vista en caso de que el acceso necesite autenticación
		if (isset($metodo)) { 
			if ($metodo != "") {
				$this -> subAcceso($metodo);
			} else {
				F::redirect('login'); // Redirección en caso que no exista un metodo
			}
		} else { // Aquí en caso de que la vista sea publica 
			// ---- En esta parte el programador es libre de manejarlo a su manera //

			$datos = []; //creación de una variable array
			/* Usamos la instancia del controlador para extraer lo que deseamos 
				en {extractData} enviamos en nombre del modelo y lo que necesitamos
				std = estado actual de la tabla en la BD, información general, ejem extractData('phrase|std')
				count = nos mostrara el numero de datos encontrados
			*/
			$info = $this -> ctr -> extractData('phrase|count'); // asignación de datos a la variable array	
			$num = mt_rand(0,$info['count']-1); // genero un número aleatorio 
			$datos['frase'] = $info['phrase'][$num]['content_phrase']; // extraigo una frase aleatoria
			// invoco al metodo estatico de la vista y muestro la vista
			View::renderPage('inicio',$this -> ctr -> ld,$datos);

			// ---------------------------------------------------------------- //
		}
	} else {
		// View::renderPage("error.unautorized");
		F::redirect('login'); // Redirección en caso de autorización
	}
}

function subAcceso($dato){
	/* ******************************************************** 
	********************** Code for user **********************
	******************************************************** */

}

function obtenerFrase(){
	$datos = $this -> ctr -> extractData('phrase|count'); // asignación de datos a la variable array
	$num = mt_rand(0,$datos['count']-1); // genero un número aleatorio 
	return $datos['frase'] = $datos['phrase'][$num]['content_phrase']; // extraigo una frase aleatoria
}
// Fin class
}
