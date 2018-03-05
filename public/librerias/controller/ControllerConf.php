<?php


class Controller {

private $std; //  Estado actual de la clase.
private $stdController; //  Estado del controlador a crear.
private $stdPage; // Estado de la página  a crear.
private $stdinfoPage; // Estado de los datos página  a crear
private $nompageCtr; // Nombre del controlador 
private $nompage;  // Nombre del página y datos de esta misma 
private $controller; // Nombre que se usara , ejem "fotosController.php".	
private $proyecto; // Nombre de la ruta.

public function __construct(){
	// Incializamos todo en "" , esto se modifica según su uso.
	include 'coreController.php';
	$this -> proyecto = $nombreproyecto;
	$this -> std = false;
	$this -> stdController = "";
	$this -> stdPage = "";
	$this -> stdinfoPage = "";	
	$this -> nompageCtr = "";	
	$this -> nompage = "";
	$this -> controller = "";
	
}
// Función Crear Controlador 
public function crearController($dato){
	/* Para el nombre del controlador, pondremos el dato que recibimos en minúscula, 
	lo hace posible strtolower */
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

public function modificarController($dato1, $dato2){
	/* Para el nombre del controlador, pondremos el dato que recibimos en minúscula, 
	lo hace posible strtolower, en este caso el anterior y el nuevo */
	// Nombre anterior.
	$oldname = strtolower($dato1);
	// Nombre actual.
	$newname  = strtolower($dato2);
	/* Para el nombre del controlador, pondremos el dato que recibimos la primera letra
	en mayúscula y el resto en minúscula, lo hace posible ucfirst, usamos en nuevo nombre */
	$this -> nompage = ucfirst($newname);
	$controllerold = $oldname.'Controller.php';
	$controllernew = $newname.'Controller.php';
	$pathold = 'C:\\xampp\\htdocs\\'.$this -> proyecto.'\controllers\\page\\'.$controllerold ;
	$pathnew = 'C:\\xampp\\htdocs\\'.$this -> proyecto.'\controllers\\page\\'.$controllernew ;
		if (!file_exists($pathold)){
			return  $this ->  crearController($this -> nompage);
		} else {
			$renameCtr = rename($pathold, $pathnew);
			$tplCtr = fopen($pathnew, "w+");
			include 'controller.tpl';
			if (fwrite($tplCtr, $templateController)) {
					$this -> stdController = "Controlador Creado";
					$pathviewold = 'C:\\xampp\\htdocs\\'.$this -> proyecto.'\\views\\page\\'.$oldname.'.phtml';
					$pathviewnew = 'C:\\xampp\\htdocs\\'.$this -> proyecto.'\\views\\page\\'.$newname.'.phtml';
					$this -> stdPage = rename($pathviewold,$pathviewnew);
					if ($this -> stdPage) {
						$this -> stdPage = "Pagina Renombrada";
						$pathdataold = 'C:\\xampp\\htdocs\\'.$this -> proyecto.'\views\\page\\data\\data-'.$oldname.'.phtml';
						$pathdatanew = 'C:\\xampp\\htdocs\\'.$this -> proyecto.'\views\\page\\data\\data-'.$newname.'.phtml';
						$tplinfo = rename($pathdataold,$pathdatanew);               
						if ($tplinfo) {
							$this -> stdinfoPage = "Datos Modificados";
							$std = true;
						} else {
							$this -> stdinfoPage = "Error al modificar data";
						}

					} else {
						$this -> stdPage = "Error al renombrar pagina";
					}
				} else {
					$this -> stdController = "Error al renombrar controlador";
				}
				fclose($tplCtr);
				$message = array('estado' => $std, 'controlador' => $this -> stdController , 'page' => $this -> stdPage, 'datapage' => $this -> stdinfoPage);	
				return $message;
			}
		
		}

		public function eliminarController($dato1){
			$std = false;
			$this -> stdController = "";
			$this -> stdPage = "";
			$this -> stdinfoPage = "";
			$nompage = strtolower($dato1);
			$controller = $nompage.'Controller.php';
			$path = 'C:\\xampp\\htdocs\\'.$this -> proyecto.'\controllers\\page\\'.$controller;
			if (!file_exists($path)){
				$this -> stdController = false;
			}else {
				$deleteCtr = unlink($path);
				if ($deleteCtr) {
					$this -> stdController = "Controlador Eliminado";
					$pathview = 'C:\\xampp\\htdocs\\'.$this -> proyecto.'\\views\\page\\'.$nompage.'.phtml';
					$this -> stdPage = unlink($pathview);
					if ($this -> stdPage) {
						$this -> stdPage = "Pagina Eliminada";
						$pathdata = 'C:\\xampp\\htdocs\\'.$this -> proyecto.'\views\\page\\data\\data-'.$nompage.'.phtml';
						$tplinfo = unlink($pathdata);               
						if ($tplinfo) {
							$this -> stdinfoPage = "Datos Eliminada";
							$std = true;
						} else {
							$this -> stdinfoPage = "Error al eliminar data";
						}

					} else {
						$this -> stdPage = "Error al eliminar pagina";
					}
				} else {
					$this -> stdController = "Error al eliminar controlador";
				}
			}
		$message = array('estado' => $std, 'controlador' => $this -> stdController , 'page' => $this -> stdPage, 'datapage' => $this -> stdinfoPage);	
		return $message;
		}

	}

/* 
(1) fopen documentación en http://php.net/manual/es/function.fopen.php
'r'	Apertura para sólo lectura; coloca el puntero al fichero al principio del fichero.

'r+'	Apertura para lectura y escritura; coloca el puntero al fichero al principio del fichero.

'w'	Apertura para sólo escritura; coloca el puntero al fichero al principio del fichero y trunca el fichero a longitud cero. Si el fichero no existe se intenta crear.

'w+'	Apertura para lectura y escritura; coloca el puntero al fichero al principio del fichero y trunca el fichero a longitud cero. Si el fichero no existe se intenta crear.

'a'	Apertura para sólo escritura; coloca el puntero del fichero al final del mismo. Si el fichero no existe, se intenta crear. En este modo, fseek() solamente afecta a la posición de lectura; las lecturas siempre son pospuestas.

'a+'	Apertura para lectura y escritura; coloca el puntero del fichero al final del mismo. Si el fichero no existe, se intenta crear. En este modo, fseek() no tiene efecto, las escrituras siempre son pospuestas.
'x'	Creación y apertura para sólo escritura; coloca el puntero del fichero al principio del mismo. Si el fichero ya existe, la llamada a fopen() fallará devolviendo FALSE y generando un error de nivel E_WARNING. Si el fichero no exite se intenta crear. Esto es equivalente a especificar las banderas O_EXCL|O_CREAT para la llamada al sistema de open(2) subyacente.

'x+'	Creación y apertura para lectura y escritura; de otro modo tiene el mismo comportamiento que 'x'.

'c'	Abrir el fichero para sólo escritura. Si el fichero no existe, se crea. Si existe no es truncado (a diferencia de 'w'), ni la llamada a esta función falla (como en el caso con 'x'). El puntero al fichero se posiciona en el principio del fichero. Esto puede ser útil si se desea obtener un bloqueo asistido (véase flock()) antes de intentar modificar el fichero, ya que al usar 'w' se podría truncar el fichero antes de haber obtenido el bloqueo (si se desea truncar el fichero, se puede usar ftruncate() después de solicitar el bloqueo).

'c+'	Abrir el fichero para lectura y escritura; de otro modo tiene el mismo comportamiento que 'c'.

*/	
 ?>