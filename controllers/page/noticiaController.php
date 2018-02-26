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

class noticiaController extends Controller {
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
		} else { // Aquí en caso de que la vista sea pública 

			$content = 'Los dirigentes nativos piden el mejoramiento de la estructura del Oleoducto Norperuano, la compensación por los derrames de petróleo y la remediación por contaminación petrolera.';
			$content = strlen($content) > 84 ? substr($content, 0, 84).'...' : $content ; 
			$autor   = 'Ancaor';
			$fecha   = '18/02/2018';
			$title   = 'Loreto: Comunidades nativas desbloquearon cuencas tras acuerdo con el Gobierno';
			$title   = strlen($title) > 84 ? substr($title, 0, 84).'...' : $title ; 
			$datos['hidro'] = array(array('autor' => $autor,'fecha' =>  $fecha, 'title' => $title , 'content' => $content, 'enlace' => 'noticia'),
						            array('autor' => $autor,'fecha' =>  $fecha, 'title' => $title , 'content' => $content, 'enlace' => 'noticia'));
			View::renderPage('noticia',$this -> ctr -> ld,$datos);

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
