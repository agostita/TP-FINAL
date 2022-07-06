<?php
class Empresa{
    private $idEmpresa;
    private $eNombre;
    private $eDireccion;
    private $mensajeError;


    /**
     * Obtiene el valor de idEmpresa
     */ 
    public function getIdEmpresa(){
        return $this->idEmpresa;
    }

    /**
     * Obtiene el valor de eNombre
     */ 
    public function getENombre(){
        return $this->eNombre;
    }

    /**
     * Obtiene el valor de eDireccion
     */ 
    public function getEDireccion(){
        return $this->eDireccion;
    }

    /**
     * Obtiene el valor de mensajeError
     */ 
    public function getMensajeError(){
        return $this->mensajeError;
    }


    /**
     * Establece el valor de idEmpresa
     */ 
    public function setIdEmpresa($idEmpresa){
        $this->idEmpresa = $idEmpresa;
    }

    /**
     * Establece el valor de eNombre
     */ 
    public function setENombre($eNombre){
        $this->eNombre = $eNombre;
    }

    /**
     * Establece el valor de eDireccion
     */ 
    public function setEDireccion($eDireccion){
        $this->eDireccion = $eDireccion;
    }

    /**
     * Establece el valor de mensajeError
     */ 
    public function setMensajeError($mensajeError){
        $this->mensajeError = $mensajeError;
    }


    public function __construct(){
        $this->idEmpresa= ""; //va en 0 por el autoincrement?
        $this->eNombre="";
        $this->eDireccion="";
    }
    
    //SETEAMOS LOS ATRIBUTOS
    public function cargar($idEmpresa, $eNombre, $eDireccion){		
        $this->setIdEmpresa($idEmpresa);
        $this->setENombre($eNombre);
        $this->setEDireccion($eDireccion);
    }

    /**
     * este módulo inserta una nueva empresa a la BD.
     */
    public function insertar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "INSERT INTO empresa (enombre, edireccion) 
                    VALUES ('".$this->getENombre()."','".$this->getEDireccion()."')";
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
     * este módulo actualiza una empresa en la BD
     */
    public function modificar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "UPDATE empresa 
                    SET idempresa = ".$this->getIdEmpresa().", 
                    enombre = '".$this->getENombre()."', 
                    edireccion ='".$this->getEDireccion()."' WHERE idempresa = ".$this->getIdEmpresa();
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
     * este módulo elimina una empresa de la BD
     */
    public function eliminar(){
        $baseDatos = new BaseDatos();
        $resp=false; 
        $consulta= "DELETE FROM empresa WHERE idempresa = ".$this->getIdEmpresa();
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
     * este módulo busca una empresa de la BD
     */
    public function buscar ($idEmpresa){
        $baseDatos= new BaseDatos();
        $consulta="SELECT * FROM empresa WHERE idempresa = ".$idEmpresa;
        $resp=false; 
        if ($baseDatos->iniciar()){
            if($baseDatos->ejecutar($consulta)){
                if($empresa=$baseDatos->registro()){
				    $this->setIdEmpresa($idEmpresa);
					$this->setENombre($empresa['enombre']);
					$this->setEDireccion($empresa['edireccion']);
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
		$consultaEmpresa="SELECT * FROM empresa ";
		if($condicion != ""){ 
		    $consultaEmpresa .= " where ".$condicion;
		}
		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consultaEmpresa)){
                $resp = [];				
				while($empresa=$baseDatos->registro()){	
					$objEmpresa = new Empresa();
					$objEmpresa->buscar($empresa['idempresa']);
                    array_push($resp, $objEmpresa);
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

    public function __toString(){    
        return "El id de la empresa es: ".$this->getIdEmpresa()."\n".
        "El nombre de la empresa es: ".$this->getENombre()."\n".
        "La dirección de la empresa es: ".$this->getEDireccion()."\n";
    }

}
