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
				$page         = $this->m_notice->listaDetallesNotices($this->url['atributo']);
				$data['notice'] = $page['datos'][0];
			}
		} 
		$permisos = $this->m_panel->getPermisosByIdUser(S::getValue('id_user'));
		$tabs = $this->m_utils->_getById('tabs');

		$data['permisos'] = $permisos['data'];
		$data['tabs']     = $tabs['data'];
		$data['title'] 	  = 'Noticias';
		
		$notice           = $this->m_notice->listaDetallesNotices($this->url['atributo']);
		$data['count']    = count($notice['datos']);
		$data = array_merge($data,$notice);
		View::renderPage('panel.notices',$this->ctr->ld,$data);
	} else {
		// View::renderPage("error.unautorized");
		F::redirect('panel'); // Redirección en caso de autorización
	}
}

function newNotice() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		\__log($_FILES['file']);
		\__log($_POST);

		$image = $this->subirImagen($_FILES['file']);
		\__log($image);
		// url
		// name
		if($_POST) 	{ 
		    $keys_post = array_keys($_POST); 
		    foreach ($keys_post as $key_post) { 
		      	$$key_post = $_POST[$key_post]; 
		    } 
		}
		// title_notice
		// descrip_notice
		// flg_publicado
		// html_notice
		// img_portada
		$html_notice = \encode_HTML($html_notice);
		$insert = array('title_notice'	  => $title_notice,
						'descrip_notice'  => $descrip_notice,
						'flg_publicado'   => 1,
						'html_notice'     => $html_notice,
						'img_portada'     => $image['name'],
						'id_User'         => S::getValue('id_user')
					   );
		$notice = $this->m_notice->newNoticia($insert);
		\__log($notice);
		$data['error'] = 0;
		$data['msj']   = 'ERROR';
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	echo json_encode(array_map('utf8_decode', $data));
}

function savePage() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		\__log(print_r($_POST,true));
		if($_POST) 	{ 
		    $keys_post = array_keys($_POST); 
		    foreach ($keys_post as $key_post) { 
		      	$$key_post = $_POST[$key_post]; 
		    } 
		} 
		$bool  = false;
		$where = array('id_Page' => $id_Page);
		$query = $this->m_utils->_getById('page','slug_Page,title_Page',$where);
		$page  = $query['data'][0];
		$name		= $this->generateNameCtr($slug_Page);
		$cont_ctr	= $this->editDataCtr($page['slug_Page'], $slug_Page);
		$cont_view	= $this->editDataView($page['title_Page'], $title_Page , $page['slug_Page']);
		if ($page['slug_Page'] == $slug_Page) {
			$bool = true;
		} else {
			Files::deleteFile("controllers/page/{$page['slug_Page']}Controller.php");
			Files::deleteFile("resources/views/{$page['slug_Page']}.twig");
		}
		$file_ctr	= Files::createFile('controllers/page/', $name['ctr'] , $cont_ctr['data'] , $bool);
		$file_view	= Files::createFile('resources/views/', $name['view'] , $cont_view['data'] , $bool);

		$update = array('slug_Page'		      => $slug_Page,
						'title_Page' 	      => $title_Page,
						'id_AttributePage'    => $id_AttributePage,
						'dateModificate_Page' => Carbon::now(),
						'id_UserModificate'	  => S::getValue('id_user')
					   );
		
		$page = $this->m_page->updatePage($update, $where);
		$data['error'] = 0;
		$data['msj']   = 'Modificación Correcta';
		\__log(print_r($page,true));
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	\__log(print_r($data,true));
	echo json_encode($data);
}
function guardarHtml() {
	$data['error'] = 1;
	$data['msj']   = 'ERROR';
	try {
		\__log(print_r($_POST,true));
		if($_POST) 	{ 
		    $keys_post = array_keys($_POST); 
		    foreach ($keys_post as $key_post) { 
		      	$$key_post = $_POST[$key_post]; 
		    } 
		} 
		// html_Page
		// id_Page
		$where  = array('id_Page' => $id_Page);
		$update = array('html_Page'		      => $html_Page,
						'dateModificate_Page' => Carbon::now(),
						'id_UserModificate'	  => S::getValue('id_user')
					   );
		$page = $this->m_page->updatePage($update, $where);
		$data['error'] = 0;
		$data['msj']   = 'Modificación Correcta';
		\__log(print_r($page,true));
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	\__log(print_r($data,true));
	echo json_encode($data);
}

function subirImagen($file){
	$data['error'] = 1;
	$data['msj']   = '';
	$data['url']   = null;
	$data['name']  = null;
	$handle = new upload($file);
    if ($handle->uploaded) {
    	\__log('upload?');
        // yes, the file is on the server
        // now, we start the upload 'process'. That is, to copy the uploaded file
        // from its temporary location to the wanted location
        // It could be something like $handle->Process('/home/www/my_uploads/');
		$handle->image_convert		= 'png';
		$handle->png_compression	= 9;
        $handle->Process(__DIR__.'/../../public/images/upload');
        \__log($handle->processed);
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
// Fin class
}

// function resetAI(){
// 	$data['msj']   = 'ERROR';
// 	$data['error'] = 1;
// 	try {
// 		$data['msj']   = 'UPLOAD';
// 		$data['error'] = 0;
// 	} catch (Exception $e) {
// 		$file['msj'] = $e->getMessage();
// 	}
// 	echo json_encode($data);
// }

