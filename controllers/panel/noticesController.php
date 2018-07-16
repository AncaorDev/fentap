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
use model\noticesModel;
use model\utilsModel;
use Carbon\Carbon;
use app\utils\Files;
use app\utils\upload;
use Exception;

class noticesController extends Controller {
private $dp;
private $ctr;
private $bd;
private $auth;
private $m_panel;
private $m_page;
private $m_utils;
private $url;

function __construct($url){
	$this -> auth  = true; // Si para el acceso necesita estar autenticado
	$this -> bd    = true; // Si se usara la conexión a la base de Datos
	$this -> ctr   = new Controller($bd = $this -> bd); // Ejecutamos una instancia hacia el controlador general
	$this->m_panel = new panelModel();
	$this->m_notice = new noticesModel();
	$this->m_utils = new utilsModel();
	$this->url     = $url;
}

function index() { //Función que se jecuta al recibir una variable del tipo controlador
	if (parent::authenticate($this -> auth)) { // Aquí la vista en caso de que el acceso necesite autenticación
		$data['accion'] = 'listar';
		if ($this->url['metodo'] != null && $this->url['atributo'] != null) {
			if ($this->url['metodo'] == 'edit') {
				__log('idddddd');
				__log($this->url['atributo']);
				$notice         = $this->m_notice->listaDetallesNotices($this->url['atributo']);
				__log($notice);
				$data['notice'] = $notice['datos'][0];
				$data['notice']['html_notice'] = \decode_HTML($data['notice']['html_notice']);
			}
		}
		$permisos = $this->m_panel->getPermisosByIdUser(S::getValue('id_user'));
		$tabs = $this->m_utils->_getById('tabs');

		$data['permisos'] = $permisos['data'];
		$data['tabs']     = $tabs['data'];
		$data['title'] 	  = 'Noticias';
		$notices           = $this->m_notice->listaDetallesNotices();
		$data['count']    = count($notices['datos']);
		$data = array_merge($data,$notices);
		View::renderPage('panel.notices',$this->ctr->ld,$data);
	} else {
		// View::renderPage("error.unautorized");
		\redirect('panel'); // Redirección en caso de autorización
	}
}

function newNotice() {
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
		$slug_notice = \limpiarURL($title_notice);
		$slug_notice = substr($slug_notice, 0, 40);
		$html_notice = \encode_HTML($html_notice);

		$insert = array('title_notice'	  => $title_notice,
						'descrip_notice'  => $descrip_notice,
						'flg_publicado'   => 1,
						'html_notice'     => trim($html_notice),
						'slug_notice'     => trim($slug_notice),
						'id_User'         => S::getValue('id_user')
					   );

		if ($image != '') {
			$insert['img_portada']   = $image['name'];
		}

		if ($flg_destacado == 1) {
			$insert['flg_destacado'] = $flg_destacado;
			$m_utils->updateTable('notice', array('flg_destacado' => 0));
		}
		$notice = $this->m_notice->newNoticia($insert);
		$data['error'] = 0;
		$data['msj']   = 'SUCCESS';
	} catch (Exception $e) {
		$data['msj']   =  $e->getMessage();
	}
	echo json_encode($data);
}

function saveNotice() {
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
		$html_notice = \encode_HTML($html_notice);

		$update = array('title_notice'	  => $title_notice,
						'descrip_notice'  => $descrip_notice,
						'flg_publicado'   => 1,
						'html_notice'     => trim($html_notice),
						'id_User'         => S::getValue('id_user')
					   );

		if ($image != '') {
			$update['img_portada']   = $image['name'];
		}

		if ($flg_destacado == 1) {
			$update['flg_destacado'] = $flg_destacado;
			$this->m_utils->updateTable('notice', array('flg_destacado' => 0));
		}
		$where = array('id_notice' => $id_notice);
		$notice = $this->m_utils->updateTable('notice',$update, $where);
		$data['error'] = 0;
		$data['msj']   = 'SUCCESS';
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	echo json_encode($data);
}

function deletenotice() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		if($_POST) 	{ 
		    $keys_post = array_keys($_POST); 
		    foreach ($keys_post as $key_post) { 
		      	$$key_post = $_POST[$key_post]; 
		    } 
		}
		$where = array('id_notice' => $id_notice);
		$data  = $this->m_utils->_deleteRow('notice', $where);
		$data['error'] = 0;
		$data['msj']   = 'Eliminado';
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	echo json_encode($data);
}

function guardarHtml() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		if($_POST) 	{ 
		    $keys_post = array_keys($_POST); 
		    foreach ($keys_post as $key_post) { 
		      	$$key_post = $_POST[$key_post]; 
		    } 
		} 
		$where  = array('id_Page' => $id_Page);
		$update = array('html_Page'		      => $html_Page,
						'dateModificate_Page' => Carbon::now(),
						'id_UserModificate'	  => S::getValue('id_user')
					   );
		$page = $this->m_page->updatePage($update, $where);
		$data['error'] = 0;
		$data['msj']   = 'Modificación Correcta';
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
		$this->m_notice->setAutoincrement($num);
		$data['msj']   = 'RESET';
		$data['error'] = 0;
	} catch (Exception $e) {
		$file['msj'] = $e->getMessage();
	}
	echo json_encode($data);
}
// Fin class
}

