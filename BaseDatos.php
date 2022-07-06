<?php
/*este script permite que la app se conecte al motor de bd*/
class BaseDatos {
    private $HOSTNAME; //ip donde se encuentra el motor de bd//
    private $BASEDATOS; //qué bd de todas las que hay//
    private $USUARIO; //datos de autenticacion//
    private $CLAVE;
    private $CONEXION;
    private $QUERY; //consulta
    private $RESULT; //donde se almacena el resultado de la consulta
    private $ERROR;
    
    
    /**
     * Establece el valor de HOSTNAME
     */ 
    public function setHOSTNAME($HOSTNAME){
        $this->HOSTNAME = $HOSTNAME;
    }

    /**
     * Establece el valor de BASEDATOS
     */ 
    public function setBASEDATOS($BASEDATOS){
        $this->BASEDATOS = $BASEDATOS;
    }

    /**
     * Establece el valor de USUARIO
     */ 
    public function setUSUARIO($USUARIO){
        $this->USUARIO = $USUARIO;
    }

    /**
     * Establece el valor de CLAVE
     */ 
    public function setCLAVE($CLAVE){
        $this->CLAVE = $CLAVE;
    }

    /**
     * Establece el valor de QUERY
     */ 
    public function setQUERY($QUERY){
        $this->QUERY = $QUERY;
    }

    /**
     * Establece el valor de CONEXION
     */ 
    public function setCONEXION($CONEXION){
        $this->CONEXION = $CONEXION;
    }

    /**
     * Establece el valor de RESULT
     */ 
    public function setRESULT($RESULT){
        $this->RESULT = $RESULT;
    }

    /**
     * Establece el valor de ERROR
     */ 
    public function setERROR($ERROR){
        $this->ERROR = $ERROR;
    }

    
    /**************************************/
    /**************** GET *****************/
    /**************************************/
    
    /**
     * Obtiene el valor de HOSTNAME
     */ 
    public function getHOSTNAME(){
        return $this->HOSTNAME;
    }

    /**
     * Obtiene el valor de BASEDATOS
     */ 
    public function getBASEDATOS(){
        return $this->BASEDATOS;
    }

    /**
     * Obtiene el valor de USUARIO
     */ 
    public function getUSUARIO(){
        return $this->USUARIO;
    }

    /**
     * Obtiene el valor de CLAVE
     */ 
    public function getCLAVE(){
        return $this->CLAVE;
    }

    /**
     * Obtiene el valor de CONEXION
     */ 
    public function getCONEXION(){
        return $this->CONEXION;
    }

    /**
     * Obtiene el valor de QUERY
     */ 
    public function getQUERY(){
        return $this->QUERY;
    }

    /**
     * Obtiene el valor de RESULT
     */ 
    public function getRESULT(){
        return $this->RESULT;
    }

    /**
     * Obtiene el valor de ERROR
     */ 
    public function getERROR(){
        return "\n".$this->ERROR;
    }

    /*
    * Constructor de la clase que inicia las variables instancias de la clase
    * vinculadas a la coneccion con el Servidor de BD
    */
    public function __construct(){ //el constructor no recibe parámetros pq son datos fijos
        $this->HOSTNAME = "127.0.0.1"; // o podemos poner "localhost"//
        $this->BASEDATOS = "bdviajes"; // nombre de la bd
        $this->USUARIO = "root";
        $this->CLAVE = "";
        $this->CONEXION = "";
        $this->QUERY = " "; //consulta
        $this->RESULT = 0; //resultado de la consulta
        $this->ERROR = " ";
    }
    
    /**
     * Inicia la conexion con el servidor y la BD Mysql
     * retorna true si se pudo establecer la conexion o false
     * en caso contrario
     */
    public function iniciar(){
        $verificacion = false;
        $conexion = mysqli_connect($this->getHOSTNAME(), $this->getUSUARIO(), $this->getCLAVE(), $this->getBASEDATOS());
        if($conexion){
            if(mysqli_select_db($conexion, $this->getBASEDATOS())){
                $this->setCONEXION($conexion);
                unset($this->QUERY);
                unset($this->ERROR);
                $verificacion = true;
            }else{
                $this->setERROR(mysqli_errno($conexion).":".mysqli_error($conexion));
            }
        }else{
            $this->setERROR(mysqli_errno($conexion).":".mysqli_error($conexion));
        }
        return $verificacion;
    }

    /**
     * ejecuta una consulta sql que se quiera ejecutar en la BD.
     * recibe la consulta en una cadena enviada por parametro y devuelve
     * un booleano. 
     * @param string $consulta
     * @return boolean
     */
    public function ejecutar($consulta){
        $verificacion = false;
        unset($this->ERROR);
        $this->setQUERY($consulta);
        $this->setRESULT(mysqli_query($this->getCONEXION(), $consulta));
        if($this->getRESULT()){
            $verificacion = true;
        }else{
            $this->setERROR(mysqli_errno($this->getCONEXION()).":".mysqli_error($this->getCONEXION()));
        }
        return $verificacion;
    }
    /**
     * El motor de BD ejecuta la consulta y devuelve los datos del registro retornado por
     * la ejecucion de una consulta y el cursor hace referencia al siguiente registro
     * Este método nos va a servir para crear una colección a muchos objetos, podemos ir,
     * buscar el registro y  por cada uno de los registros encontrados, los vamos guardando
     * en la colección     
     * @return boolean
     */
    public function registro(){
        $verificacion = null;
        if($this->getRESULT()){
            unset($this->ERROR);
            if($temp = mysqli_fetch_assoc($this->getRESULT())){ //a la variable temp le 
                $verificacion = $temp;                          //le asigna lo que marca el cursor
            }else{
                mysqli_free_result($this->getRESULT()); //cuando no hay registro libera
            }                                           //el cursor
        }else{
            $this->setERROR(mysqli_errno($this->getCONEXION()).":".mysqli_error($this->getCONEXION()));
        }
        return $verificacion;
    }


    /**
     * Este módulo devuelve el id de un campo autoincrement utilizado como clave de una tabla
     * Retorna el id numerico del registro insertado, devuelve null en caso que la ejecucion de la consulta falle
     * @param string $consulta
     * @return int id de la tupla insertada
     */
    
     public function devuelveIDInsercion($consulta){
        $verificacion = null;
        unset($this->ERROR);
        if($this->setRESULT(mysqli_query($this->getCONEXION(), $consulta))){
            $id = mysqli_insert_id();
            $verificacion = $id;
        }else{
            $this->setERROR(mysqli_errno($this->getCONEXION()).":".mysqli_error($this->getCONEXION()));
        }
        return $verificacion;
    }
}
?>