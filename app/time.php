<?php namespace app;
/**
* 
*/
class Time
{
	public static function Time($show = true)
	{
		if ($show) 
		{
			$tiempo_inicio = microtime(true);
			DEFINE('TIME_INICIO', $tiempo_inicio);		
		} else {
			DEFINE('TIME_INICIO', FALSE);
		}	
	}

	public static function endTime()
	{
		try {
			if (!defined('TIME_INICIO')) 
			{
				throw new \Exception('Function time no initialized');	
			}
			if (TIME_INICIO) 
			{
				$tiempo_fin = microtime(true);
				$total = bcsub($tiempo_fin, TIME_INICIO, 3);
				$html = "<div style='background: black;text-align:center;color:white;'><p style='margin:0;'>";
				$html .= "Esta página fue generada en {$total} segundos. </p></div>";
				print($html);
			} 
		} catch (\Exception $e) {
			echo  $e->getMessage();
		}
	}
}