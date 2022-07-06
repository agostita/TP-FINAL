<?php
class Responsable{
    private $rNroEmpleado;
    private $rNroLicencia; 
    private $rNombre;
    private $rApellido;
    private $mensajeError;


    /**
     * Obtiene el valor de rNroEmpleado
     */ 
    public function getRNroEmpleado(){
        return $this->rNroEmpleado;
    }

    /**
     * Obtiene el valor de rNroLicencia
     */ 
    public function getRNroLicencia(){
        return $this->rNroLicencia;
    }

    /**
     * Obtiene el valor de rNombre
     */ 
    public function getRNombre(){
        return $this->rNombre;
    }

    /**
     * Obtiene el valor de rApellido
     */ 
    public function getRApellido(){
        return $this->rApellido;
    }


    /**
     * Obtiene el valor de mensajeError
     */ 
    public function getMensajeError(){
        return $this->mensajeError;
    }


    /**
     * Establece el valor de rNroEmpleado
     */ 
    public function setRNroEmpleado($rNroEmpleado){
        $this->rNroEmpleado = $rNroEmpleado;
    }

    /**
     * Establece el valor de rNroLicencia
     */ 
    public function setRNroLicencia($rNroLicencia){
        $this->rNroLicencia = $rNroLicencia;
    }

    /**
     * Establece el valor de rNombre
     */ 
    public function setRNombre($rNombre){
        $this->rNombre = $rNombre;
    }

    /**
     * Establece el valor de rApellido
     */ 
    public function setRApellido($rApellido){
        $this->rApellido = $rApellido;
    }

    /**
     * Establece el valor de mensajeError
     */ 
    public function setMensajeError($mensajeError){
        $this->mensajeError = $mensajeError;
    }

    public function __construct(){
        $this->rNroEmpleado="";
        $this->rNroLicencia=""; 
        $this->rNombre="";
        $this->rApellido="";
    }

    //SETEAMOS LOS ATRIBUTOS
    public function cargar($rNroEmpleado, $rNroLicencia, $rNombre, $rApellido){		
        $this->setRNroEmpleado($rNroEmpleado);
        $this->setRNroLicencia($rNroLicencia);
        $this->setRNombre($rNombre);
        $this->setRApellido($rApellido);
    }

    /**
     * este módulo inserta un nuevo responsable a la BD.
     */
    public function insertar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "INSERT INTO responsable (rnumerolicencia, rnombre, rapellido) 
                    VALUES (".$this->getRNroLicencia().",'".$this->getRNombre()."','".$this->getRApellido()."')";
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
     * este módulo actualiza un responsable en la BD
     */
    public function modificar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "UPDATE responsable 
        SET rnumerolicencia = ".$this->getRNroLicencia().", 
        rnombre = '".$this->getRNombre()."', 
        rapellido ='".$this->getRApellido()."' WHERE rnumeroempleado = ".$this->getRNroEmpleado();
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
        $consulta= "DELETE FROM responsable WHERE rnumeroempleado = ".$this->getRNroEmpleado();
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
    public function buscar ($rNroEmpleado){
        $baseDatos= new BaseDatos();
		$consulta="SELECT * FROM responsable WHERE rnumeroempleado = ".$rNroEmpleado;
        $resp=false; 
        if ($baseDatos->iniciar()){
            if($baseDatos->ejecutar($consulta)){
                if($responsable=$baseDatos->registro()){
				    $this->setRNroEmpleado($rNroEmpleado);
					$this->setRNroLicencia($responsable['rnumerolicencia']);
					$this->setRNombre($responsable['rnombre']);
                    $this->setRApellido($responsable['rapellido']);
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
		$consultaResponsable="SELECT * FROM responsable ";
		if($condicion != ""){ 
		    $consultaResponsable .= " where ".$condicion;
		}
		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consultaResponsable)){
                $resp = [];				
				while($responsable=$baseDatos->registro()){	
					$objResponsable = new Responsable();
					$objResponsable->buscar($responsable['rnumeroempleado']);
                    array_push($resp, $objResponsable);
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
		return 
            "El número de empleado es: ".$this->getRNroEmpleado()."\n".
            "El número de licencia es: ".$this->getRNroLicencia()."\n".            
            "El nombre del responsable del viaje es: ".$this->getRNombre()."\n".
			"El apellido del responsable del viaje es: ".$this->getRApellido()."\n";
    }

}
