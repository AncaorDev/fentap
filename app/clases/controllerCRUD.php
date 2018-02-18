<?php 
/**
* 
*/
class controllerCRUD {
// Inicio de la Clase	
private $std; //  Estado actual de la clase.
private $stdController; //  Estado del controlador a crear.
private $stdPage; // Estado de la página  a crear.
private $stdinfoPage; // Estado de los datos página  a crear
private $nompageCtr; // Nombre del controlador 
private $nompage;  // Nombre del página y datos de esta misma 
private $controller; // Nombre que se usara , ejem "fotosController.php".	
// private $proyecto; // Nombre de la ruta.
	
function __construct(){
	// Incializamos todo en "" , esto se modifica según su uso.
	$this -> std = false;
	$this -> stdController = "";
	$this -> stdPage = "";
	$this -> stdinfoPage = "";	
	$this -> nompageCtr = "";	
	$this -> nompage = "";
	$this -> controller = "";
}


// Función Crear Controlador 
public function controllerCreate($dato){
	/* Para el nombre del controlador, pondremos el dato que recibimos en minúscula,lo hace posible strtolower */
	$this -> nompageCtr = strtolower($dato);
	/* Para el nombre del controlador, pondremos el dato que recibimos la primera letra
	en mayúscula y el resto en minúscula, lo hace posible ucfirst */	
	$this -> nompage = ucfirst($this -> nompageCtr);
	/* Creamos en nombre completo del controlador, formato establecido :
	   nombrecontrolador + Controller.php "respetando las mayúsculas" ejemplo:
	   datosController.php  */
	$this -> controller = $this -> nompageCtr.'Controller.php';

	// Ruta en la cual se coloca el controlador.
	$pathCtr = 'C:\\xampp\\htdocs\\'.$this -> proyecto.'\controllers\\page\\'.$this -> controller ;
	// Abrimos esa ruta del Controlador, si no existe se crea (Mas información sobre w+ (1) al final).
	$stdpathCtr = fopen($pathCtr, "w+");

	// Incluimos la plantilla del controlador (Nota: Buscando otra forma de incluirlo..)
	include 'controller.tpl';

	/* con frwite agregaremos la plantilla al archivo creado anteriormente con fopen.
		fwrite recibe dos datos ($dato1, dato2)
		$dato1 = ruta del archivo
		$dato2 = información que se escribirá en dicho archivo
		esta función devuelve un valor booleano, si se realixo correntamente sera true,
		caso contrario false.	
	*/
	// Si se realizo correctamente haremos lo siguiente: 
	if (fwrite($stdpathCtr, $templateController)) {
		// Decimos que el controlador fue creado.
		$stdController = "Controlador Creado";
		// Ruta en la cual se coloca la página.
		$pathView = 'C:\\xampp\\htdocs\\'.$this -> proyecto.'\\views\\page\\'.$this -> nompageCtr.'.phtml';
		// Abrimos esa ruta de la Vista, si no existe se crea, mismo proceso del Controlador.
		$stdpathView = fopen($pathView, "w+");

		// Incluimos la plantilla de la página (Nota: Buscando otra forma de incluirlo..)
		include 'page.tpl';
		if (fwrite($stdpathView,$page)) {
			// Decimos que la página fue creada.
			$stdPage = "Página Creada";
			// Ruta en la cual se coloca la información de la página.
			$pathInfo = 'C:\\xampp\\htdocs\\'.$this -> proyecto.'\views\\page\\data\\data-'.$this -> nompageCtr.'.phtml';
			// Abrimos esa ruta de la Vista, si no existe se crea, mismo proceso del Controlador.
			$stdpathInfo = fopen($pathInfo,"w+");

			// Incluimos la información de la página (Nota: Buscando otra forma de incluirlo..)
			include 'datapage.tpl';                 
			if (fwrite($stdpathInfo,$datapage)) {
				// Decimos que la información fue agregada.
				$stdinfoPage = "Información agregada";
					// Proceso total Completado.
					$this -> std = true;
				} else {
					// Si el proceso falla  decimos que la información no fue agregada
					$stdinfoPage = "Error al agregar información";
				}
			// Cerramos el archivo de la información de la página.
			fclose($stdpathInfo);	
			} else {
				// Si el proceso falla  decimos que la página no fue creada.
				$stdPage = "Error al crear pagina";
			}
		// Cerramos el archivo de la página.
		fclose($stdpathView);	
		} else {
			// Si el proceso falla  decimos que el controlador no fue creado.
			$stdController = "Error al crear controlador";
		}
		// Cerramos el archivo del controlador.
		fclose($stdpathCtr);
	
	$message = array(
		// Devolvemos el estado de la clase, estado del controlador, página y dato de página 
		'estado' => $this -> std, 
		'controlador' => $this -> stdController , 
		'page' => $this -> stdPage, 
		'datapage' => $this -> stdinfoPage);
	return $message;
}

//Final de la Clase
}