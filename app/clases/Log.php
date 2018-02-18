<?php namespace app\clases;
/**
* Mejorar
*/
class Log 
{
	static function _log($var) {
		$dbgt = debug_backtrace();
        if(isset($dbgt[1]['class'])) {
            $class = $dbgt[1]['class'];
        } else {
            $ruta = explode(DIRECTORY_SEPARATOR, $dbgt[0]['file']);
            $class = end($ruta);
        }
        self::error('( '.$class.' -> '.$dbgt[1]['function'].') (linea: '.$dbgt[0]['line'].') >> '.$var, 'error');
	}

	static function error($texto,$numero = 'Error :: ') { 
		try {
			$path = __DIR__.'/../log/error-'.date('Y-m-d').'.log'; 
			$ddf  = fopen($path,'a+'); 
			$data = '['.date('Y-m-d H:m:s').']'."$numero - $texto\r\n";
			if (!fwrite($ddf,$data)) {
				throw new \Exception("No se creo archivo Log");	
			} 
			fclose($ddf); 
		} catch (\Exception $e) {
			echo  $e->getMessage();
		}
	} 
}