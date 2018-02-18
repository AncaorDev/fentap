<?php
/** Archivo que controla toda la web. 
* @ Index
**/ 
require_once(realpath(__DIR__ . '/../app/time.php'));
app\Time::Time(true); 
/* ---------------------------------------------------
Archivo Gestor de dependencias 
--------------------------------------------------- */
require_once(realpath(__DIR__  . '/../vendor/autoload.php'));
/* ---------------------------------------------------
  Archivo de registro y lectura de información
--------------------------------------------------- */
require_once(realpath(__DIR__ . '/../app/core.php')); 

use app\Core;
use app\manager\Request;

class Index 
{  
  	static function run() {   
	    // Ejecutamos core para obtener toda información necesaria para el uso de la APP
	    new Core();
	    // Ejecutamos Request para mostrar resultados según la URL
	    new Request(); 
	    
  	}
}
Index::run();
app\Time::endTime();