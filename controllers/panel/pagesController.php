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
use model\pageModel;
use model\utilsModel;
use Carbon\Carbon;
use app\utils\Files;
use app\utils\upload;
use Exception;

class pagesController extends Controller {
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
	$this->m_page  = new pageModel();
	$this->m_utils = new utilsModel();
	$this->url     = $url;
}

function index() { //Función que se jecuta al recibir una variable del tipo controlador
	if (parent::authenticate($this -> auth)) { // Aquí la vista en caso de que el acceso necesite autenticación
		$data['accion'] = 'listar';
		if ($this->url['metodo'] != null && $this->url['atributo'] != null) {
			if ($this->url['metodo'] == 'edit') {
				$page         = $this->m_page->listaDetallesPage($this->url['atributo']);
				$data['page'] = $page['datos'][0];
			}
		} 
		$permisos = $this->m_panel->getPermisosByIdUser(S::getValue('id_user'));
		$tabs = $this->m_utils->_getById('tabs');
		$page = $this->m_page->listaDetallesPage();
		$data['permisos'] = $permisos['data'];
		$data['tabs']     = $tabs['data'];
		$data['title'] 	  = 'Páginas';
		$data['count']    = count($page['datos']);
		$data = array_merge($data,$page);
		View::renderPage('panel/pages',$this->ctr->ld,$data);
	} else {
		// View::renderPage("error.unautorized");
		F::redirect('panel'); // Redirección en caso de autorización
	}
}

function newPage() {
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
		$slug_Page = preg_replace("/[0-9]+/", "", trim($slug_Page));
		$title_Page = preg_replace("/\-/", " ", $title_Page);
		
		$slug = $this->m_utils->_getById('page', 'slug_Page' , array('slug_Page' => $slug_Page));
		if (count($slug) == 0) {
			throw new Exception('Ya existe Slug');
		}
		$name		= $this->generateNameCtr($slug_Page);
		$cont_ctr	= $this->generateDataCtr($slug_Page);
		$cont_view	= $this->generateDataView($title_Page);
		$file_ctr	= Files::createFile('controllers/page/', $name['ctr'] , $cont_ctr['data'] );
		$file_view	= Files::createFile('resources/views/', $name['view'] , $cont_view['data'] );

		if ($file_ctr['error'] != 0) {
			throw new Exception('Error desconocido');
		}
		if ($file_view['error'] != 0) {
			throw new Exception('Error desconocido');
		}
		$insert = array('slug_Page'		  => $slug_Page,
						'title_Page' 	  => $title_Page,
						'dateCreate_Page' => Carbon::now(),
						'state_page'      => 'publicado',
						'id_User'         => S::getValue('id_user')
					   );
		$page = $this->m_page->registrarPage($insert);
		\__log(print_r($page,true));
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

function deletePage() {
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
		$where = array('id_Page' => $id_Page);
		$query = $this->m_utils->_getById('page','slug_Page,title_Page',$where);
		$page  = $query['data'][0];
		$data  = $this->m_utils->_deleteRow('page', $where);
		Files::deleteFile("controllers/page/{$page['slug_Page']}Controller.php");
		Files::deleteFile("resources/views/{$page['slug_Page']}.twig");
		$data['error'] = 0;
		$data['msj']   = 'Eliminado';
	} catch (Exception $e) {
		$data['msj']  =  $e->getMessage();
	}
	echo json_encode($data);
}
function generateNameCtr($slug) {
	$names['ctr']  = strtolower($slug).'Controller.php';
	$names['view'] = strtolower($slug).'.twig';
	return $names;
}

function generateDataCtr($slug) {
	$dir_default = __DIR__.'/../page/defaultController.php';
	$data_file   = file_get_contents($dir_default);
	$buscar      = array('{{name_ctr}}' , '{{page_render}}');
	$reemplazar  = array( $slug , $slug);
	$data_file   = str_replace($buscar , $reemplazar, $data_file);
	\__log($data_file);
	$file['data'] = $data_file;
	return $file;
}

function generateDataView($title_Page) {
	$dir_default = __DIR__.'/../../resources/views/template.twig';
	$data_file   = file_get_contents($dir_default);
	$buscar      = array('{{title}}');
	$reemplazar  = array($title_Page);
	$data_file   = str_replace($buscar , $reemplazar, $data_file);
	\__log($data_file);
	$file['data'] = $data_file;
	return $file;
}

function editDataCtr($slug , $slug_new) {
	$file['data']  = null;
	$file['msj']   = 'ERROR';
	$file['error'] = 1;
	try {
		$class     = "{$slug}Controller";
		$class_new = "{$slug_new}Controller";
		$dir_default = __DIR__."/../page/{$class}.php";
		if (!file_exists($dir_default)) {
			throw new Exception('No existe el archivo');
		} 
		$data_file   = file_get_contents($dir_default);
		$buscar      = array($class,$slug);
		$reemplazar  = array($class_new,$slug_new);
		$data_file   = str_replace($buscar , $reemplazar, $data_file);
		\__log($data_file);
		$file['data']  = $data_file;
		$file['error'] = 0;	
		$file['msj']   = 'Modificación Correcta';
	} catch (Exception $e) {
		$file['msj'] = $e->getMessage();
	}	
	return $file;
}

function editDataView($title_Page , $title_Page_new ,$slug) {
	$file['data']  = null;
	$file['msj']   = 'ERROR';
	$file['error'] = 1;
	try {
		$dir_default = __DIR__."/../../resources/views/{$slug}.twig";
		\__log($dir_default);
		if (!file_exists($dir_default)) {
			throw new Exception('No existe el archivo');
		} 
		$data_file   = file_get_contents($dir_default);
		$buscar      = array($title_Page);
		$reemplazar  = array($title_Page_new);
		$data_file   = str_replace($buscar , $reemplazar, $data_file);
		\__log($data_file);
		$file['data']  = $data_file;
		$file['error'] = 0;	
		$file['msj']   = 'Modificación Correcta';
	} catch (Exception $e) {
		$file['msj'] = $e->getMessage();
	}	
	return $file;
}

function subirImagen(){
	$data['msj']   = 'ERROR';
	$data['error'] = 1;
	try {
		\__log($_FILES['file']);
		$handle = new upload($_FILES['file']);
	    // then we check if the file has been uploaded properly
	    // in its *temporary* location in the server (often, it is /tmp)
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
	        } else {
	           $handle->error;
	        }
	        // we delete the temporary files
	        $handle-> Clean();
		}	
		$data['msj']   = 'UPLOAD';
		$data['error'] = 0;
	} catch (Exception $e) {
		$file['msj'] = $e->getMessage();
	}
	echo json_encode($data);
}

function resetAutoIncrement(){
	$data['msj']   = 'ERROR';
	$data['error'] = 1;
	try {
		\__log(print_r($_POST,true));
		if($_POST) 	{ 
		    $keys_post = array_keys($_POST); 
		    foreach ($keys_post as $key_post) { 
		      	$$key_post = $_POST[$key_post]; 
		    } 
		}
		$this->m_page->setAutoincrement($num);
		$data['msj']   = 'RESET';
		$data['error'] = 0;
	} catch (Exception $e) {
		$file['msj'] = $e->getMessage();
	}
	echo json_encode($data);
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

