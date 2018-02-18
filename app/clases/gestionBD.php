<?php namespace app\clases;
// Mejorar esta *#$%&$
class gestionBD extends \mysqli {
  private $conexion;
  private $server=HOST;
  private $usuario=USER;
  private $password=PASS;
  private $base_datos=DBNAME;
  private $debug=DEBUG;
  public $state;

  /*La función construct para ejecutar automaticamente la conexion al instanciarla*/
  public function __construct(){
    $this->conectar();
  }
  /*Evitamos el clonaje del objeto. Patrón Singleton*/
  private function __clone(){ }
  /*La funcion destruc destruye la clase cuando no se utiliza */
  //private function __destruct(){ }

  /*La funcion conextar es privada*/
  private function conectar(){
    
      $this->conexion=new \mysqli($this->server, $this->usuario, $this->password, $this->base_datos);
      // Works as of PHP 5.2.9 and 5.3.0.
      if ($this->conexion->connect_error) {
        if ($this->debug) {
          die('Error de conexión: ' . $this->conexion->connect_error);
        }
          die('Ha ocurrido un error');
      }
      mysqli_set_charset($this->conexion,"utf8");

  }
  // Función para ejecutar una sentencia sql
  public function ejecutar($sql){
      try {
        $this->stmn=mysqli_query($this->conexion,$sql);
        if (!$this->stmn) {
          return $this->conexion->error;
        } else {
          return $this->stmn;
        }  
      } catch (Exception $e) {
        throw $e;
      }
  }
  /*Funcion que nos permite extraer un array*/
  public function recorrer($sql){
    try {
      return mysqli_fetch_array($sql); 
    } catch (Exception $e) {
      throw $e;
    }
  }
  // Función para obtener la cantidad de datos
  public function filas($sql){
    try {
      return mysqli_num_rows($sql);    
    } catch (Exception $e) {
      throw $e;
    }
  }
  // Función para obtener la cantidad de datos de frente
  public function filasrow($sql){
    try {
      return mysqli_num_rows(mysqli_query($this->conexion,$sql));    
    } catch (Exception $e) {
      throw $e;
    }
  }
  // Función para liberar sql
  public function liberar($sql){
    try {
      return mysqli_free_result($sql);
    } catch (Exception $e) {
      throw $e;
    }
  }
  // Función para cerrar la conexion
  public function cerrar(){
    try {
      mysqli_close($this -> conexion);
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function ejecutararray($sql){
    try {   
      $lista=array();
      $rs=mysqli_query($this -> conexion,$sql);
      if (!$rs) {
        // En caso que falle la consulta retornamos el error
        return $lista = ['std' => 'false' , 'error' => $this -> conexion->error];
      } else {
        // 
        while($fila=mysqli_fetch_array($rs)){
        $lista[]=$fila; //El array lista es la coleccion de filas
        }
        return $lista;
      }
    }
    catch(Exception $ex){
      // En caso de un error interno
      throw $ex; 
    }
  }
  public function lastId(){
    try {
       return mysqli_insert_id($this -> conexion);
    } catch (Exception $e) {
      throw $e;
    }
  }
}
