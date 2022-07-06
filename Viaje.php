<?php
class Viaje{
    private $idViaje;
    private $vDestino;
    private $vCantidadMax;
    private $colObjPasajero; //los voy a setear con la función que creé
    private $objEmpresa;
    private $objResponsable;
    private $vImporte;    
    private $tipoAsiento;
    private $idaYVuelta;    
    private $mensajeError;


    /**
     * Obtiene el valor de idViaje
     */ 
    public function getIdViaje(){
        return $this->idViaje;
    }

    /**
     * Obtiene el valor de vDestino
     */ 
    public function getVDestino(){
        return $this->vDestino;
    }

    /**
     * Obtiene el valor de vCantidadMax
     */ 
    public function getVCantidadMax(){
        return $this->vCantidadMax;
    }

    /**
     * Obtiene el valor de colObjPasajero
     */ 
    public function getColObjPasajero(){
        return $this->colObjPasajero;
    }

    /**
     * Obtiene el valor de objEmpresa
     */ 
    public function getObjEmpresa(){
        return $this->objEmpresa;
    }

    /**
     * Obtiene el valor de objResponsable
     */ 
    public function getObjResponsable(){
        return $this->objResponsable;
    }

    /**
     * Obtiene el valor de vImporte
     */ 
    public function getVImporte(){
        return $this->vImporte;
    }

    /**
     * Obtiene el valor de tipoAsiento
     */ 
    public function getTipoAsiento(){
        return $this->tipoAsiento;
    }

    /**
     * Obtiene el valor de idaYVuelta
     */ 
    public function getIdaYVuelta(){
        return $this->idaYVuelta;
    }

    /**
     * Obtiene el valor de mensajeError
     */ 
    public function getMensajeError(){
        return $this->mensajeError;
    }

    /**
     * Establece el valor de idViaje
     */ 
    public function setIdViaje($idViaje){
        $this->idViaje = $idViaje;
    }

    /**
     * Establece el valor de vDestino
     */ 
    public function setVDestino($vDestino){
        $this->vDestino = $vDestino;
    }

    /**
     * Establece el valor de vCantidadMax
     */ 
    public function setVCantidadMax($vCantidadMax){
        $this->vCantidadMax = $vCantidadMax;
    }

    /**
     * Establece el valor de colObjPasajero
     */ 
    public function setColObjPasajero($colObjPasajero){
        $this->colObjPasajero = $colObjPasajero;
    }

    /**
     * Establece el valor de objEmpresa
     */ 
    public function setObjEmpresa($objEmpresa){
        $this->objEmpresa = $objEmpresa;
    }

    /**
     * Establece el valor de objResponsable
     */ 
    public function setObjResponsable($objResponsable){
        $this->objResponsable = $objResponsable;
    }

    /**
     * Establece el valor de vImporte
     */ 
    public function setVImporte($vImporte){
        $this->vImporte = $vImporte;
    }

    /**
     * Establece el valor de tipoAsiento
     */ 
    public function setTipoAsiento($tipoAsiento){
        $this->tipoAsiento = $tipoAsiento;
    }

    /**
     * Establece el valor de idaYVuelta
     */ 
    public function setIdaYVuelta($idaYVuelta){
        $this->idaYVuelta = $idaYVuelta;
    }

    /**
     * Establece el valor de mensajeError
     */ 
    public function setMensajeError($mensajeError){
        $this->mensajeError = $mensajeError;
    }
    
    public function __construct(){
        $this->idViaje = "";
        $this->vDestino = "";
        $this->vCantidadMax = "";
        $this->colObjPasajero = []; //no siempre que cree esta instancia voy a poder poner la coleccion de pasajeros
        $this->objEmpresa = ""; //para eso hacemos la funcion
        $this->objResponsable = "";
        $this->vImporte = "";
        $this->tipoAsiento = "";
        $this->idaYVuelta = "";
    }

    //SETEAMOS LOS ATRIBUTOS
    public function cargar($idViaje, $vDestino, $vCantidadMax, $objEmpresa, $objResponsable, $vImporte, $tipoAsiento, $idaYVueltata){		
        $this->setIdViaje($idViaje);
        $this->setVDestino($vDestino);
        $this->setVCantidadMax($vCantidadMax);
        $this->setObjEmpresa($objEmpresa);
        $this->setObjResponsable($objResponsable);
        $this->setVImporte($vImporte);
        $this->setTipoAsiento($tipoAsiento);
        $this->setIdaYVuelta($idaYVueltata);
    }
    
    /**
     * este módulo inserta un nuevo viaje a la BD.
     */
    public function insertar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, tipoAsiento, idayvuelta) 
                    VALUES ('".$this->getVDestino()."',
                    ".$this->getVCantidadMax().",".$this->getObjEmpresa()->getIdEmpresa().",".$this->getObjResponsable()->getRNroEmpleado().",
                    ".$this->getVImporte().",'".$this->getTipoAsiento()."','".$this->getIdaYVuelta()."')";
        if($baseDatos->iniciar()){
            if($baseDatos->ejecutar($consulta)){
                $resp = true;
            }else{
                $this->setMensajeError($baseDatos->getERROR());
            }
        }else{
            $this->setMensajeError($baseDatos->getERROR());
        }
        return $resp;
    }

    /**
     * este módulo actualiza un viaje en la BD
     */
    public function modificar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "UPDATE viaje 
                    SET idViaje = ".$this->getIdViaje().", 
                    vdestino = '".$this->getVDestino()."', 
                    vcantmaxpasajeros = ".$this->getVCantidadMax().", 
                    idempresa = ".$this->getObjEmpresa()->getIdEmpresa().", 
                    rnumeroempleado = ".$this->getObjResponsable()->getRNroEmpleado().", 
                    vimporte = ".$this->getVImporte().",
                    tipoAsiento = '".$this->getTipoAsiento()."',
                    idayvuelta = '".$this->getIdaYVuelta()."' WHERE idviaje = ".$this->getIdViaje();
        if($baseDatos->iniciar()){
            if($baseDatos->ejecutar($consulta)){
                $resp = true;
            }else{
                $this->setMensajeError($baseDatos->getERROR());
            }
        }else{
            $this->setMensajeError($baseDatos->getERROR());
        }
        return $resp;
    }
    

    /**
     * este módulo elimina un viaje de la BD
     */
    public function eliminar(){
        $baseDatos = new BaseDatos();
        $resp=false; 
        $consulta= "DELETE FROM viaje WHERE idviaje = ".$this->getIdViaje();
        if($baseDatos->iniciar()){
            if($baseDatos->ejecutar($consulta)){
                $resp=true;
            }else{
                $this->setMensajeError($baseDatos->getERROR());
            }
        }else{
            $this->setMensajeError($baseDatos->getERROR());
        }return $resp; 
    }




    /**
     * este módulo busca una empresa de la BD y 
     * setea todos los atributos del viaje que encuentra
     */
    public function buscar ($idViaje){
        $baseDatos= new BaseDatos();
		$consulta="SELECT * FROM viaje WHERE idviaje = ".$idViaje;
        $resp=false; 
        if ($baseDatos->iniciar()){
            if($baseDatos->ejecutar($consulta)){
                if($viaje=$baseDatos->registro()){ //obtenemos el registro
                    $objReponsable = new Responsable(); 
                    $objEmpresa = new Empresa(); 
                    $objReponsable->buscar($viaje['rnumeroempleado']);					
                    $objEmpresa->buscar($viaje['idempresa']);	
				    $this->setIdViaje($idViaje);
					$this->setVDestino($viaje['vdestino']);
					$this->setVCantidadMax($viaje['vcantmaxpasajeros']);
					$this->setObjEmpresa($objEmpresa);
					$this->setObjResponsable($objReponsable);
					$this->setVImporte($viaje['vimporte']);
					$this->setTipoAsiento($viaje['tipoAsiento']);
					$this->setIdaYVuelta($viaje['idayvuelta']);
					$resp= true;
                }
            }else{
                $this->setMensajeError($baseDatos->getERROR());
            }
        }else{
            $this->setMensajeError($baseDatos->getERROR());
        }return $resp;
    }


    /**
     * Este módulo recibe por parámetro una condición y nos devuelve
     * un array con todos los viajes que coincidan con la condición 
     * (en caso de haberla, caso contrario, nos devuelve todos los pasajeros)
     * @param $condicion
     * @return array
     */
    public function listar($condicion){
        $resp=null;
        $baseDatos = new BaseDatos();
		$consultaViaje=" SELECT * FROM viaje ";
		if($condicion <> ""){ 
		    $consultaViaje .= " where ".$condicion;
		}
		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consultaViaje)){
                $resp = [];				
				while($viaje=$baseDatos->registro()){	//si la consulta es verdadera
					$objViaje = new Viaje();
					$objViaje->buscar($viaje['idviaje']);
                    array_push($resp, $objViaje);
				}
		 	}else{
                $resp = false;
                $this->setMensajeError($baseDatos->getERROR());
			}
		 }else{
            $resp = false;
            $this->setMensajeError($baseDatos->getERROR());
		 }		
		 return $resp;
    }


    /**
     * Este módulo crea un array con los pasajeros del viaje, los setea
     * y retorna true si se pudo setear o false en caso contrario.
     * @return boolean
     */
    public function arrayObjPasajeros(){
        $baseDatos= new BaseDatos();
        $resp=false;
        $condicion= "idviaje =".$this->getIdViaje();
        if($baseDatos->iniciar()){
            $objPasajero= new Pasajero();
            $coleccion= $objPasajero->listar($condicion);
            if(is_array($coleccion)){
                $this->setColObjPasajero($coleccion);
                $resp= true;
            }else{
                $this->setMensajeError($baseDatos->getERROR());
            }
        }else{
            $this->setMensajeError($baseDatos->getERROR());
        }return $resp;
    }

    /**
     * Este módulo verifica si hay asientos disponibles en el viaje, retorna true
     * si los hay, o false en caso contrario
     * @return boolean
     */
    public function asientosLibres(){
        $this->getColObjPasajero();
        $n= count($this->getColObjPasajero());
        $asientoDisponible=false;
        if($n < $this->getVCantidadMax()){
            $asientoDisponible=true;
        }return $asientoDisponible;

    }
    

    public function __toString(){
        return  "El id del viaje es: ".$this->getIdViaje()."\n".
                "El destino del viaje es: ".$this->getVDestino()."\n".
                "La cantidad máxima de pasajeros es: ".$this->getVCantidadMax()."\n".
                "Los pasajeros del viaje son: "."\n".$this->pasajerosToString()."\n"."\n".
                "El importe del viaje es: ".$this->getVImporte()."\n".
                "El tipo de asiento del viaje es: ".$this->getTipoAsiento()."\n".
                "El viaje es de : ".$this->getIdaYVuelta()."\n".
                "------------------------------"."\n".
                "Los datos de la empresa son: "."\n".$this->getObjEmpresa().
                "------------------------------"."\n".
                "Los datos del responsable del viaje son: "."\n".$this->getObjResponsable().
                "------------------------------"."\n";
    }

    /** Método que convierte la coleccion de los objetos 
     * pasajeros en una cadena de caracteres. 
     * @return string
     */
    public function pasajerosToString(){
        $coleccion = $this->getColObjPasajero();
        $string = "";
        foreach ($coleccion as $objPasajero){
            $string .= $objPasajero;
        }
        return $string;
    }

}