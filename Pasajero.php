<?php
class Pasajero{
    private $pDocumento;
    private $pNombre;
    private $pApellido;
    private $pTelefono;
    private $objViaje;
    private $mensajeError;


    /**
     * Obtiene el valor de pDocumento
     */ 
    public function getPDocumento(){
        return $this->pDocumento;
    }

    /**
     * Obtiene el valor de pNombre
     */ 
    public function getPNombre(){
        return $this->pNombre;
    }

    /**
     * Obtiene el valor de pApellido
     */ 
    public function getPApellido(){
        return $this->pApellido;
    }

    /**
     * Obtiene el valor de pTelefono
     */ 
    public function getPTelefono(){
        return $this->pTelefono;
    }

    /**
     * Obtiene el valor de objViaje
     */ 
    public function getObjViaje(){
        return $this->objViaje;
    }

    /**
     * Obtiene el valor de mensajeError
     */ 
    public function getMensajeError(){
        return $this->mensajeError;
    }

    /**
     * Establece el valor de pDocumento
     */ 
    public function setPDocumento($pDocumento){
        $this->pDocumento = $pDocumento;
    }

    /**
     * Establece el valor de pNombre
     */ 
    public function setPNombre($pNombre){
        $this->pNombre = $pNombre;
    }

    /**
     * Establece el valor de pApellido
     */ 
    public function setPApellido($pApellido){
        $this->pApellido = $pApellido;
    }

    /**
     * Establece el valor de pTelefono
     */ 
    public function setPTelefono($pTelefono){
        $this->pTelefono = $pTelefono;
    }

    /**
     * Establece el valor de objViaje
     */ 
    public function setObjViaje($objViaje){
        $this->objViaje = $objViaje;
    }

    /**
     * Establece el valor de mensajeError
     */ 
    public function setMensajeError($mensajeError){
        $this->mensajeError = $mensajeError;
    }

    public function __construct(){
        $this->pDocumento="";
        $this->pNombre="";
        $this->pApellido="";
        $this->pTelefono="";
        $this->objViaje="";

    }

    //SETEAMOS LOS ATRIBUTOS
    public function cargar($pDocumento, $pNombre, $pApellido, $pTelefono, $objViaje){		
        $this->setPDocumento($pDocumento);
        $this->setPNombre($pNombre);
        $this->setPApellido($pApellido);
        $this->setPTelefono($pTelefono);
        $this->setObjViaje($objViaje);
    }

    /**
     * este módulo inserta un nuevo pasajero a la BD.
     */
    public function insertar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "INSERT INTO pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje) 
                    VALUES (".$this->getPDocumento().",'".$this->getPNombre()."','".$this->getPApellido()."',".$this->getPTelefono().",".$this->getObjViaje()->getIdViaje().")";

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
     * este módulo actualiza un pasajero en la BD
     */
    public function modificar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "UPDATE pasajero 
                    SET pdocumento = '".$this->getPDocumento()."', 
                    pnombre = '".$this->getPNombre()."', 
                    papellido ='".$this->getPApellido()."', 
                    ptelefono = '".$this->getPTelefono()."', 
                    idviaje = '".$this->getObjViaje()->getIdViaje()."' WHERE pdocumento = '".$this->getPDocumento()."'";
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
     * este módulo elimina pasajeros de la BD
     */
    public function eliminar(){
        $baseDatos = new BaseDatos();
        $resp=false; 
        $consulta= "DELETE FROM pasajero WHERE pdocumento =".$this->getPDocumento();
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
     * este módulo busca pasajeros en la BD
     */
    public function buscar ($documento){
        $baseDatos= new BaseDatos();
        $consulta= "SELECT * FROM pasajero WHERE pdocumento =".$documento;
        $resp=false; 
        if ($baseDatos->iniciar()){
            if($baseDatos->ejecutar($consulta)){
                if($pasajero=$baseDatos->registro()){//en pasajero se va a almacenar un array 
                    $this->setPDocumento($documento);
					$this->setPNombre($pasajero['pnombre']);
					$this->setPApellido($pasajero['papellido']);
					$this->setPTelefono($pasajero['ptelefono']);
                    $objViaje = new Viaje();
                    $objViaje->buscar($pasajero['idviaje']);
                    $this->setObjViaje($objViaje);
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
     * un array con todos los pasajeros que coincidan con la condición 
     * (en caso de haberla, caso contrario, nos devuelve todos los pasajeros)
     * @param $condicion
     * @return array
     */
    public function listar($condicion){
        $resp=null;
        $baseDatos = new BaseDatos();
		$consultaPasajero="SELECT * FROM pasajero ";
		if($condicion <> ""){
		    $consultaPasajero .= " where ".$condicion;
		}
		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consultaPasajero)){
                $resp = [];				
				while($pasajero=$baseDatos->registro()){	
					$objPasajero = new Pasajero();
					$objPasajero->buscar($pasajero['pdocumento']);
                    array_push($resp, $objPasajero);
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

    public function __toString()
    {
        return 
            "El nombre del pasajero es: ".$this->getPNombre()."\n".
            "El apellido del pasajero es: ".$this->getPApellido()."\n".
            "El documento del pasajero es: ".$this->getPDocumento()."\n".
            "El teléfono del pasajero es: ".$this->getPTelefono()."\n";
    }
}

