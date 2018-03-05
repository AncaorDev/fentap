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
use app\clases\Functions as F;
use model\loginModel;
use model\panelModel;
use model\utilsModel;
use Exception;


class panelController extends Controller {
private $dp;
private $ctr;
private $bd; 
private $auth;
private $m_login;
private $m_panel;
private $m_utils;
private $url;
function __construct($url){
	parent::__construct();
	$this-> auth   = true; // Si para el acceso necesita estar autenticado
	$this->bd      = true; // Si se usara la conexión a la base de Datos
	$this->ctr     = new Controller($bd = $this->bd); // Ejecutamos una instancia hacia el controlador general
	$this->m_login = new loginModel();
	$this->m_panel = new panelModel();
	$this->m_utils = new utilsModel();;
	$this->url     = $url;
}

function index() { //Función que se jecuta al recibir una variable del tipo controlador
	if (parent::authenticate($this -> auth)) { // Aquí la vista en caso de que el acceso necesite autenticación
		// ---- En esta parte el programador es libre de manejarlo a su manera //
		$permisos = $this->m_panel->getPermisosByIdUser(S::getValue('id_user'));
		$tabs = $this->m_panel->getTabsByIdUser(S::getValue('id_user'));
		$data['permisos'] = $permisos['data'];
		$data['tabs']     = $tabs['data'];

		View::renderPage('panel/panel',$this->ctr->ld,$data);
	} else {
		View::renderPage('login');
	}
}

function login() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		if($_POST) 	{ 
		    $keys_post = array_keys($_POST); 
		    foreach ($keys_post as $key_post) 
		     { 
		      $$key_post = $_POST[$key_post]; 
		     } 
		} 
		$error = '<div class="alert alert-dismissible alert-danger">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>ERROR, Datos no V&aacute;lidos</strong>
				  </div>';

		$success = '<div class="alert alert-dismissible alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong> <i class="fa fa-spinner fa-spin"></i> REDIRECCIONANDO 
						<span class="display:none;" id="redirectl"></span></strong>
					</div>';
		$login = $this->m_login->login($user,$pass,$sesion);
		if ($login['data'] == '') {
			throw new Exception($error);
		}
		$user =  $login['data'];
		if ($sesion == 1) {
       		$time_session = (60*60*24);
    	} else {
        	$time_session = "300";
    	}
    	
    	ini_set('session.upload_progress.enabled', 1);
		ini_set("session.cookie_lifetime",$time_session);
		session_cache_expire($time_session);
		session_set_cookie_params($time_session);			    
		S::init();  
		S::setData(array('session'     => 'yes',
						'id_user'      => $user['id_User'],
						'time_session' => $time_session ));	
		$data['error'] = 0;
		$data['url']   = 'login';
		$data['msj']   = $success;
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	echo json_encode(array_map('utf8_decode', $data));
}

function logout() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		S::init();  
		S::destroy();  
		$data['msj']   = null;
		$data['error'] = 0;
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	echo json_encode(array_map('utf8_decode', $data));
}
// Fin class
}
