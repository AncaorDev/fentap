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
use model\publishModel;
use model\utilsModel;
use Carbon\Carbon;
use app\utils\Files;
use app\utils\upload;
use Exception;

class sliderController extends Controller {
private $dp;
private $ctr;
private $bd; 
private $auth;
private $m_panel;
private $m_publish;
private $m_utils;
private $url;

function __construct($url){
	$this -> auth    = true; // Si para el acceso necesita estar autenticado
	$this -> bd      = true; // Si se usara la conexión a la base de Datos
	$this -> ctr     = new Controller($bd = $this -> bd); // Ejecutamos una instancia hacia el controlador general
	$this->m_panel   = new panelModel();
	$this->m_publish = new publishModel();
	$this->m_utils   = new utilsModel();
	$this->url       = $url;
}

function index() { //Función que se jecuta al recibir una variable del tipo controlador
	if (parent::authenticate($this -> auth)) { // Aquí la vista en caso de que el acceso necesite autenticación
		$data['accion'] = 'listar';
		if ($this->url['metodo'] != null && $this->url['atributo'] != null) {
			if ($this->url['metodo'] == 'edit') {
				$publish         = $this->m_publish->listaDetallesPublish($this->url['atributo']);
				$data['publish'] = $publish['datos'][0];
				$data['publish']['html_publish'] = \decode_HTML($data['publish']['html_publish']);
				\__log($data['publish']);
			}
		} 
		$permisos = $this->m_panel->getPermisosByIdUser(S::getValue('id_user'));
		$tabs = $this->m_utils->_getById('tabs');

		$data['permisos'] = $permisos['data'];
		$data['tabs']     = $tabs['data'];
		$data['title'] 	  = 'Publicaciones';
		$publishes		  = $this->m_publish->listaDetallesPublish();
		$data['count']    = count($publishes['datos']);
		$data = array_merge($data,$publishes);
		View::renderPage('panel.publishes',$this->ctr->ld,$data);
	} else {
		// View::renderPage("error.unautorized");
		\redirect('login'); // Redirección en caso de autorización
	}
}


function newPublish() {
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

		$slug_publish = \limpiarURL($title_publish);
		$slug_publish = substr($slug_publish, 0, 40);
		$html_publish = \encode_HTML($html_publish);

		$insert = array('title_publish'	  => $title_publish,
						'descrip_publish'  => $descrip_publish,
						'flg_publicado'   => 1,
						'html_publish'     => trim($html_publish),
						'slug_publish'     => trim($slug_publish),
						'id_User'         => S::getValue('id_user')
					   );

		if ($image != '') {
			$insert['img_portada']   = $image['name'];
		}

		$publish = $this->m_publish->newPublish($insert);
		$data['error'] = 0;
		$data['msj']   = 'SUCCESS';
	} catch (Exception $e) {
		$data['msj']   =  $e->getMessage();
	}
	echo json_encode(array_map('utf8_decode', $data));
}

function savePublish() {
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
		$html_publish = \encode_HTML($html_publish);

		$update = array('title_publish'	  => $title_publish,
						'descrip_publish'  => $descrip_publish,
						'flg_publicado'   => 1,
						'html_publish'     => trim($html_publish),
						'id_User'         => S::getValue('id_user')
					   );

		if ($image != '') {
			$update['img_portada']   = $image['name'];
		}
		$where = array('id_publish' => $id_publish);
		$publish = $this->m_utils->updateTable('publish',$update, $where);
		$data['error'] = 0;
		$data['msj']   = 'SUCCESS';
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	echo json_encode($data);
}

function deletePublish() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		if($_POST) 	{ 
		    $keys_post = array_keys($_POST); 
		    foreach ($keys_post as $key_post) { 
		      	$$key_post = $_POST[$key_post]; 
		    } 
		}
		$where = array('id_publish' => $id_publish);
		$data  = $this->m_utils->_deleteRow('publish', $where);
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
		$this->m_publish->setAutoincrement($num);
		$data['msj']   = 'RESET';
		$data['error'] = 0;
	} catch (Exception $e) {
		$file['msj'] = $e->getMessage();
	}
	echo json_encode($data);
}
// Fin class
}

