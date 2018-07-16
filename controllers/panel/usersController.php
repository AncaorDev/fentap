<?php namespace controllers\panel;
/* ======================================================================
$dp => Datos o Informacion desde la BD a la Página, si $bd esta descativada no enviara nada
$ctr => Instancia de Controller
$bd => Si necesita usar la Base de datos true, caso contrario false
$auth => autenticación (booleano)
====================================================================== */
use app\clases\View;
use app\clases\Controller;
use app\clases\Session as S;
use model\panelModel;
use model\utilsModel;
use model\userModel;

class usersController extends Controller {
private $dp;
private $ctr;
private $bd;
private $auth;
private $m_panel;
private $m_utils;
private $m_user;
private $url;

function __construct($url){
	$this -> auth  = true; // Si para el acceso necesita estar autenticado
	$this -> bd    = true; // Si se usara la conexión a la base de Datos
	$this -> ctr   = new Controller($bd = $this -> bd); // Ejecutamos una instancia hacia el controlador general
	$this->m_panel = new panelModel();
	$this->m_utils = new utilsModel();
	$this->m_user  = new userModel();
	$this->url     = $url;
}

function index() { //Función que se jecuta al recibir una variable del tipo controlador
	if (parent::authenticate($this -> auth)) { // Aquí la vista en caso de que el acceso necesite autenticación
		$permisos = $this->m_panel->getPermisosByIdUser(S::getValue('id_user'));
		$tabs = $this->m_utils->_getById('tabs');
		$data['permisos'] = $permisos['data'];
		$data['tabs']     = $tabs['data'];
		$data['metodo']   = isset($this->url['metodo']) ? null : $this->url['metodo'];
		$users = $this->m_utils->_getById('user');
		$data['users']    = $users['data'];
		$data['title']    = 'Usuarios';
		$data['count']    = count($users['data']);
		View::renderPage('panel/users',$this->ctr->ld,$data);
	} else {
		\redirect('panel'); // Redirección en caso de autorización
	}
}

function newUser() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		if($_POST) 	{
		    $keys_post = array_keys($_POST);
		    foreach ($keys_post as $key_post) {
		      	$$key_post = $_POST[$key_post];
		    }
		}
		$insert = array('name_User' => $name_User,
						'pass_User' => \encriptar($pass_User),
						'permisos'  => $permisos);
		$data = $this->m_user->registrarUser($insert);
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	echo json_encode(array_map('utf8_decode', $data));
}

function deleteUser() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		if($_POST) 	{
		    $keys_post = array_keys($_POST);
		    foreach ($keys_post as $key_post) {
		      	$$key_post = $_POST[$key_post];
		    }
		}
		$data = $this->m_user->deleterUser($id_user);
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	echo json_encode(array_map('utf8_decode', $data));
}

function getPermisosbyIdUser() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		if($_POST) 	{
	    	$keys_post = array_keys($_POST);
	    	foreach ($keys_post as $key_post) {
	      		$$key_post = $_POST[$key_post];
	    	}
		}
	$query = $this->m_user->getPermisosbyIdUser($id_user);
	$permisos = $query['data'];
	$html = '<table class="table table-striped table-hover ">
                          <thead>
                            <tr>
                              <th> # (ID) </th>
                              <th> Nombre del Permiso </th>
                            </tr>
                          </thead>
                          <tbody>';
      	foreach ($permisos as $permiso) {
      		$html .= ' <tr class="success" id="tdata">
                              <td> '.$permiso['id_tab'].' </td>
                              <td> '.$permiso['desc_tab'].'  </td>
						</tr>';
		}
        $html .= ' </tbody>
					</table> ';
		$data['html'] =  $html;
		$data['error'] =  0;
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	echo json_encode($data);
}
function resetAutoIncrement(){
	$data['msj']   = 'ERROR';
	$data['error'] = 1;
	try {
		if($_POST) 	{
		    $keys_post = array_keys($_POST);
		    foreach ($keys_post as $key_post) {
		      	$$key_post = $_POST[$key_post];
		    }
		}
		$this->m_user->setAutoincrement($num);
		$data['msj']   = 'RESET';
		$data['error'] = 0;
	} catch (Exception $e) {
		$file['msj'] = $e->getMessage();
	}
	echo json_encode($data);
}
// Fin class
}

