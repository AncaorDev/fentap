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
use model\mapaModel;
use model\utilsModel;
use Carbon\Carbon;
use app\utils\Files;
use app\utils\upload;
use Exception;

class mapaController extends Controller {
private $dp;
private $ctr;
private $bd; 
private $auth;
private $m_panel;
private $m_mapa;
private $m_utils;
private $url;

function __construct($url){
	$this -> auth    = true; // Si para el acceso necesita estar autenticado
	$this -> bd      = true; // Si se usara la conexión a la base de Datos
	$this -> ctr     = new Controller($bd = $this -> bd); // Ejecutamos una instancia hacia el controlador general
	$this->m_panel   = new panelModel();
	$this->m_mapa    = new mapaModel();
	$this->m_utils   = new utilsModel();
	$this->url       = $url;
}

function index() { //Función que se jecuta al recibir una variable del tipo controlador
	if (parent::authenticate($this -> auth)) { // Aquí la vista en caso de que el acceso necesite autenticación
		$data['accion'] = 'listar';
		if ($this->url['metodo'] != null && $this->url['atributo'] != null) {
			if ($this->url['metodo'] == 'edit') {	
				$mapa         = $this->m_mapa->listaDetallesMapa($this->url['atributo']);
				$data['mapa'] = $mapa['datos'][0];
				$data['mapa']['html_mapa'] = \decode_HTML($data['mapa']['html_mapa']);
				\__log($data['mapa']);
			}
		} 
		$permisos = $this->m_panel->getPermisosByIdUser(S::getValue('id_user'));
		$tabs = $this->m_utils->_getById('tabs');

		$data['permisos'] = $permisos['data'];
		$data['tabs']     = $tabs['data'];
		$data['title'] 	  = 'Mapa ';
		$mapas   		  = $this->m_mapa->listaDetallesMapa();
		$data['count']    = count($mapas['datos']);
		$data = array_merge($data,$mapas);
		$sql_departamentos = $this->m_utils->_getById('ubdepartamento');
		$data['departamentos'] = $sql_departamentos['data'];
		View::renderPage('panel.mapas',$this->ctr->ld,$data);
	} else {
		// View::renderPage("error.unautorized");
		F::redirect('panel'); // Redirección en caso de autorización
	}
}

function newMapa() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		if($_POST) 	{ 
		    $keys_post = array_keys($_POST); 
		    foreach ($keys_post as $key_post) { 
		      	$$key_post = $_POST[$key_post]; 
		    } 
		}
		$departamento = $this->m_utils->_getById('ubdepartamento' , '*' , array(' ' => $id_departamento));
		$slug_mapa = strtolower($departamento['data'][0]['departamento']);
		$html_mapa = \encode_HTML($html_mapa);
		$insert = array('id_departamento'	=> $id_departamento,
						'html_mapa'     	=> trim($html_mapa),
						'slug_mapa'     	=> trim($slug_mapa),
						'id_User'			=> S::getValue('id_user')
					   );
		$mapa = $this->m_mapa->newMapa($insert);
		$data['error'] = 0;
		$data['msj']   = 'SUCCESS';
	} catch (Exception $e) {
		$data['msj']   =  $e->getMessage();
	}
	echo json_encode(array_map('utf8_decode', $data));
}

function verificarDepartamento() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		$data['exists'] = 0;
		$data['url']    = null;
		if($_POST) 	{ 
		    $keys_post = array_keys($_POST); 
		    foreach ($keys_post as $key_post) { 
		      	$$key_post = $_POST[$key_post]; 
		    } 
		}
		$departamento = $this->m_utils->_getById('mapa' , '*' , array('id_departamento' => $id_departamento));
		if ($departamento['data'] != 0) {
			$data['exists'] = 1;
			$data['url']    =  "panel/mapa/edit/{$departamento['data'][0]['id_mapa']}";
		}
		$data['error'] = 0;
		$data['msj']   = 'SUCCESS';
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	echo json_encode($data);
}

function saveBoletin() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		$image = '';
		if (count($_FILES) > 0) {
			$image = $this->subirImagen($_FILES['file']);
		}
		if($_POST) 	{ 
		    $keys_post = array_keys($_POST); 
		    foreach ($keys_post as $key_post) { 
		      	$$key_post = $_POST[$key_post]; 
		    } 
		} 
		\__log($_POST);
		$html_boletin = \encode_HTML($html_boletin);

		$update = array('title_boletin'	  => $title_boletin,
						'descrip_boletin'  => $descrip_boletin,
						'flg_publicado'   => 1,
						'html_boletin'     => trim($html_boletin),
						'id_User'         => S::getValue('id_user')
					   );

		if ($image != '') {
			$update['img_portada']   = $image['name'];
		}

		if ($flg_destacado == 1) {
			$update['flg_destacado'] = $flg_destacado;
			$this->m_utils->updateTable('boletin', array('flg_destacado' => 0));
		}
		$where = array('id_boletin' => $id_boletin);
		$boletin = $this->m_utils->updateTable('boletin',$update, $where);
		$data['error'] = 0;
		$data['msj']   = 'SUCCESS';
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	echo json_encode($data);
}

function deleteBoletin() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		if($_POST) 	{ 
		    $keys_post = array_keys($_POST); 
		    foreach ($keys_post as $key_post) { 
		      	$$key_post = $_POST[$key_post]; 
		    } 
		}
		$where = array('id_boletin' => $id_boletin);
		$data  = $this->m_utils->_deleteRow('boletin', $where);
		$data['error'] = 0;
		$data['msj']   = 'Eliminado';
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	echo json_encode($data);
}

function subirImagen($file){
	$data['error'] = 1;
	$data['msj']   = '';
	$data['url']   = null;
	$data['name']  = null;
	$handle = new upload($file);
    if ($handle->uploaded) {
        // yes, the file is on the server
        // now, we start the upload 'process'. That is, to copy the uploaded file
        // from its temporary location to the wanted location
        // It could be something like $handle->Process('/home/www/my_uploads/');
		$handle->image_convert		= 'png';
		$handle->png_compression	= 9;
        $handle->Process(__DIR__.'/../../public/images/upload');
        // we check if everything went OK
        if ($handle->processed) {
            // everything was fine !
        	$data['url']  = 'images/upload/'.$handle->file_dst_name;
            $data['name'] = $handle->file_dst_name;
            round(filesize($handle->file_dst_pathname)/256)/4 . 'KB';
			$data['error'] = 0;
			$data['msj']   = 'success';
        } else {
           $handle->error;
           \__log($handle->error);
        }
        // we delete the temporary files
        
        $handle-> Clean();
	}
	return $data;
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
		$this->m_boletin->setAutoincrement($num);
		$data['msj']   = 'RESET';
		$data['error'] = 0;
	} catch (Exception $e) {
		$file['msj'] = $e->getMessage();
	}
	echo json_encode($data);
}
// Fin class
}

