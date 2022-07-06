<?php


//Implementar dentro de la clase TestViajes una operación que permita ingresar, modificar y eliminar la información de la empresa de viajes.
//Implementar dentro de la clase TestViajes una operación que permita ingresar, modificar y eliminar la información de un viaje, 
//teniendo en cuenta las particularidades expuestas en el dominio a lo largo del cuatrimestre.


include "Viaje.php";
include "Pasajero.php";
include "Responsable.php";
include "Empresa.php";
include "BaseDatos.php";

/**
 * Muestra un menú (del viaje) para que el usuario elija y devuelve la opción elegida
 * @return int 
 */
function menu(){
    echo "\n"."MENU"."\n";
    echo "1) Saber la cantidad de pasajeros."."\n";
    echo "2) Ver datos viaje."."\n";
    echo "3) Ver datos de todos los pasajeros."."\n";
    echo "4) Modificar datos del pasajero."."\n";
    echo "5) Agregar pasajeros."."\n";
    echo "6) Eliminar un pasajero."."\n";
    echo "7) Modificar responsable viaje"."\n";
    echo "8) Ver datos de un pasajero"."\n";
    echo "9) Cambiar datos de viaje."."\n";
    echo "0) Salir"."\n";
    echo "Opcion: ";
    $menu = trim(fgets(STDIN));
    echo "\n";
    return $menu;
}

/**
 * Muestra el menu principal
 * @return int 
 */
function menuPrincipal(){
    echo "\n"."MENÚ PRINCIPAL DE OPCIONES"."\n"; 
    echo "Ingrese una opción: "."\n".
    "0. Salir"."\n".
    "1. Cargar un nuevo VIAJE"."\n".
    "2. Cargar una nueva EMPRESA"."\n".
    "3. Cargar un nuevo RESPONSABLE"."\n".
    "4. Modificar datos de una empresa"."\n".
    "5. Modificar datos de un responsable"."\n".
    "6. Modificar un viaje"."\n".
    "7. Ver todos los viajes."."\n".
    "8. Elimina un viaje"."\n";
    echo "Opcion: ";
    $menu = trim(fgets(STDIN));
    echo "\n";
    return $menu;
}


/**
 * Devuelve por pantalla un string que separa los puntos
 */
function separador(){
    echo "**************************************************"."\n";
}


/**
 * Este módulo crea un viaje en la BD
 */
//$idViaje, $vDestino, $vCantidadMax, $objEmpresa, $objResponsable, $vImporte, $tipoAsiento, $idaYVuelta//

function nuevoViaje(){
    $objEmpresa = solicitarEmpresa();
    $objResponsable = solicitarResponsable();
    echo "Ingrese el destino que desee para su viaje "." : ";
    $destinoViaje = trim(fgets(STDIN));
    echo "Ingrese la cantidad máxima de personas que pueden realizar el viaje : ";
    $cantMaxPasajeros = trim(fgets(STDIN));
    $cantMaxPasajeros = verificadorInt($cantMaxPasajeros);
    echo "Ingrese A si el viaje es de ida y B si es ida y vuelta : "."\n";
    $idaYVuelta = verificadorLetra(strtolower(trim(fgets(STDIN))));
    if($idaYVuelta== "A"){
        $idaYVuelta= "ida";
    }else{
        $idaYVuelta="ida y vuelta";
    }
    echo "Ingrese el importe del viaje : ";
    $importeViaje = verificadorInt(trim(fgets(STDIN)));
    echo "Ingrese el tipo de asiento del viaje: A para asiento de primera clase y B para asiento stándar: "."\n";
    $tipoAsiento = verificadorLetra(strtolower(trim(fgets(STDIN))));
    if($idaYVuelta== "A"){
        $tipoAsiento= "primera clase";
    }else{
        $tipoAsiento="asiento stándar";
    }
    $objViaje = new Viaje(); //iniciamos el id como null porque el id lo carga la bd
    $objViaje->cargar(null, $destinoViaje, $cantMaxPasajeros, $objEmpresa, $objResponsable, $importeViaje, $tipoAsiento, $idaYVuelta);
    //$idViaje, $vDestino, $vCantidadMax, $objEmpresa, $objResponsable, $vImporte, $tipoAsiento, $idaYVuelta//
    if(existeViaje($objViaje)){
        separador();
        echo "Ya existe ese viaje en la BD"."\n";
        separador();
    }else{
        $resp = $objViaje->insertar();
        if($resp){
            separador();
            echo "El viaje pudo crearse"."\n";
            separador();
        }else{
            separador();
            echo "No se pudo insertar el viaje a la BD. Error: "."\n".$objViaje->getMensajeError();
            separador();
        }
    }
}


/**
 * Este módulo se fija que no hayan 2 viajes iguales
 * si ese es el caso no lo agrega a la BD
 * retorna true si existe o false si no existe
 */
function existeViaje($objViajeIngresado){
    $objViaje = new Viaje();
    $arrayObjViaje = $objViaje->listar("");
    $existe = false;
    $n=count($arrayObjViaje);
    $i = 0;
    
    while(!$existe && ($i < $n)){
        if(strtolower($arrayObjViaje[$i]->getVDestino()) == strtolower($objViajeIngresado->getVDestino())&&
           strtolower($arrayObjViaje[$i]->getVCantidadMax()) == strtolower($objViajeIngresado->getVCantidadMax())&&
           strtolower($arrayObjViaje[$i]->getObjEmpresa()) == strtolower($objViajeIngresado->getObjEmpresa())&&
           strtolower($arrayObjViaje[$i]->getObjResponsable()) == strtolower($objViajeIngresado->getObjResponsable())&&
           strtolower($arrayObjViaje[$i]->getVImporte()) == strtolower($objViajeIngresado->getVImporte())&&
           strtolower($arrayObjViaje[$i]->getTipoAsiento()) == strtolower($objViajeIngresado->getTipoAsiento())&&
           strtolower($arrayObjViaje[$i]->getIdaYVuelta()) == strtolower($objViajeIngresado->getIdaYVuelta())){
            $existe=true;
        }else{
            $i++;
        }return $existe;
    }    
}

/**
 * Este módulo le muestra por pantalla al usuario todas las empresas que hay en la BD
 * y solicita que ingrese alguna 
 * @return object
 */
function solicitarEmpresa(){
    $objEmpresa = new Empresa();
    $stringEmpresa = stringObjEmpresa();
    $resp = true;
    do{
        if($resp){
            echo "Ingrese el id de las siguientes empresas, si no existe y desea cargar una: ingrese 0: "."\n".$stringEmpresa;
        }else{ 
            echo "No existe el id de la empresa, inténtelo de nuevo, o bien, ingrese 0 para cargar una nueva: "."\n".$stringEmpresa;
        }
        $idEmpresaElegida = trim(fgets(STDIN));
        $idEmpresaElegida = verificadorInt($idEmpresaElegida);
        if($idEmpresaElegida == 0){
            crearEmpresa();
            $resp = $objEmpresa->buscar(count($objEmpresa->listar("")));
        }else{
            $resp = $objEmpresa->buscar($idEmpresaElegida);
        }
    }while(!$resp);
    return $objEmpresa;
}

/**
 * Este módulo le muestra por pantalla al usuario todos los responsables que hay en la BD
 * y solicita que ingrese alguno 
 * @return object
 */
function solicitarResponsable(){
    $objResponsable = new Responsable();
    $stringResponsable = stringObjResponsable();
    $resp = true;
    do{
        if($resp){
            echo "Ingrese el número de empleado de alguna de los siguientes responsables, en caso de no estar, ingrese 0 para cargar uno: "."\n".$stringResponsable;
        }else{
            echo "El número de empleado no existe, porfavor ingrese uno de los siguientes responsables o ingrese 0 para cargar uno: "."\n".$stringResponsable;
        }
        $responsableElegido = trim(fgets(STDIN));
        $responsableElegido = verificadorInt($responsableElegido);
        if($responsableElegido == 0){
            crearResponsable();
            $resp = $objResponsable->buscar(count($objResponsable->listar("")));
        }else{
            $resp = $objResponsable->buscar($responsableElegido);
        }
    }while(!$resp);
    return $objResponsable;
}

/**
 * Te muestra un array con todos los viajes y el usuario elige con cuál
 * interactuar. Y retorna el objeto
 * @return object
 */
function viajeModificar()
{
    separador();
    $objViaje = new Viaje();
    echo "Aquí tiene todos los viajes que puede elegir: "."\n";
    separador();
    echo stringObjViajes();
    echo "Ingrese el id del viaje con el que desea interactuar: ";
    $id = trim(fgets(STDIN));
    $id = verificadorInt($id);
    $resp = $objViaje->buscar($id);
    while(!$resp){
        echo "Lo siento. El id ingresado es incorrecto, intente de nuevo, o bien, ingrese otro: "."\n";
        echo stringObjViajes();
        $id = trim(fgets(STDIN));
        $id = verificadorInt($id);
        $resp = $objViaje->buscar($id);
    }
    separador();
    return $objViaje;
}


/////////////////////////////////////////////////////////////// FUNCIONES PARA CONVERTIR EN STRING////////////////////////////////////////////////////////////////////

/**
 * Este módulo recorre un array con OBJETOS VIAJES y los devuelve 
 * en forma de cadena de caracteres para mostrar los datos. 
 * @return string
 */
function stringObjViajes(){
    $stringViajes = "";
    $objViaje = new Viaje();
    $arrayObjViaje = $objViaje->listar("");
    $n=count($arrayObjViaje);
    if($n > 0){
        foreach($arrayObjViaje as $viaje){
            $viaje->arrayObjPasajeros();
            $stringViajes.= $viaje."\n"."\n";
        }
    }
    return $stringViajes;
}

/**
 * Este módulo recorre un array de EMPRESAS y las 
 * devuelve en forma de cadena de caracteres para mostrar los datos. 
 * @return string
 */
function stringObjEmpresa(){
    $separador = "**********************************";
    $objEmpresa = new Empresa();
    $stringEmpresa = $separador."\n";
    $arrayObjEmpresa = $objEmpresa->listar("");
    $n=count($arrayObjEmpresa);
    if($n > 0){
        foreach($arrayObjEmpresa as $empresa){
            $stringEmpresa.= $empresa."\n".$separador."\n";
        }
    }
    return $stringEmpresa;
}

/**
 * Este módulo recorre un array de RESPONSABLES y las 
 * devuelve en forma de cadena de caracteres para ver los datos del responsable
 * @return string
 */
function stringObjResponsable(){
    $separador = "**********************************";
    $objResponsable = new Responsable();
    $stringResponsable = $separador."\n";
    $arrayObjResponsable = $objResponsable->listar("");
    $n=count($arrayObjResponsable);
    if($n > 0){
        foreach($arrayObjResponsable as $responsable){
            $stringResponsable.= $responsable."\n".$separador."\n";
        }
    }
    return $stringResponsable;
}




////////////////////////////////////////////FUNCIONES PARA CREAR EMPRESA, RESPONSABLE Y PASAJERO//////////////////////////////////////////////////////////////////////

/**
 * Crea una empresa en la BD
 */
function crearEmpresa(){
    echo "Ingrese un nombre para la empresa: ";
    $nombreE = trim(fgets(STDIN));
    echo "Ingrese la dirección: ";
    $direccionE = trim(fgets(STDIN));
    echo "\n";
    $objEmpresa = new Empresa();
    $objEmpresa->cargar(null, $nombreE, $direccionE);
    //cargar($idEmpresa, $eNombre, $eDireccion)//
    $resp = $objEmpresa->insertar();
    if($resp){
        separador();
        echo "La empresa fue insertada a la BD con éxito"."\n";
        separador();
    }else{
        separador();
        echo "La empresa no pudo ser insertada a la BD. Error: "."\n".$objEmpresa->getMensajeError()."\n";
        separador();
    }
}

/**
 * Crea un responsable en la BD
 */
function crearResponsable(){
    separador();
    echo "Ingrese un número de licencia para el responsable: ";
    $nroLicenciaR =  trim(fgets(STDIN));
    echo "Ingrese un nombre para el responsable: ";
    $nombreR =  trim(fgets(STDIN));
    echo "Ingrese un apellido para el responsable: ";
    $apellidoR =  trim(fgets(STDIN));
    separador();
    echo "\n";
    $objResponsable = new Responsable();
    $objResponsable->cargar(null,$nroLicenciaR,$nombreR,$apellidoR);
    //cargar($rNroEmpleado, $rNroLicencia, $rNombre, $rApellido)//
    $resp = $objResponsable->insertar();
    if($resp){
        separador();
        echo "El responsable fue insertado a la BD con éxito"."\n";
        separador();
    }else{
        separador(); 
        echo "El responsable no pudo ser insertado a la BD. Error: "."\n".$objResponsable->getMensajeError()."\n";
        separador();
    }
}

/**
 * Crea un pasajero en la BD
 * @param obj
 */
function crearUnPasajero($objViaje){
    echo "Ingrese un DNI para el pasajero: ";
    $dniP =  trim(fgets(STDIN));
    echo "Ingrese un nombre para el pasajero: ";
    $nombreP =  trim(fgets(STDIN));
    echo "Ingrese un apellido para el pasajero: ";
    $apellidoP =  trim(fgets(STDIN));    
    echo "Ingrese un número de teléfono para el pasajero: ";
    $telefonoP =  trim(fgets(STDIN));
    echo "\n";
    $objPasajero = new Pasajero();
    $resp = $objPasajero->buscar($dniP);
    if($resp){
        existePasajero($objPasajero, $objViaje);
    }else{
        $objPasajero->cargar($dniP,$nombreP,$apellidoP,$telefonoP,$objViaje);
        //cargar($pDocumento, $pNombre, $pApellido, $pTelefono, $objViaje)//
        $resp = $objPasajero->insertar();
        if($resp){
            separador();
            echo "El pasajero fue insertado a la BD con éxito"."\n";
        }else{
            separador();
            echo "El pasajero no pudo ser insertado a la BD. Error: "."\n".$objPasajero->getMensajeError()."\n";
        }
    }

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/**
 * Este módulo recibe por parámetro un pasajero que ya existe y le pregunta si desea cambiar el viaje
 * @param obj
 * @param obj
 */
function existePasajero($objPasajeroIngresado, $objViaje){
    echo "Ese pasajero ya existe. Quiere cambiarlo del viaje: ".$objPasajeroIngresado->getObjViaje()->getIdViaje()." al: "
    .$objViaje->getIdViaje()." ?"."\n"."Si/No"."\n";
    $opcionElegida = trim(fgets(STDIN));
    while((strtolower($opcionElegida) <> "si") && (strtolower($opcionElegida) <> "no")){
        echo "La opción elegida es incorrecta. Tiene que ingresar: "."\n"."Si/No"."\n";
        $opcionElegida = trim(fgets(STDIN));
    }
    if(strtolower($opcionElegida) == "si"){
        $objPasajeroIngresado->setObjViaje($objViaje);
        $objPasajeroIngresado->modificar();
        separador();
        echo "El pasajero pudo modificarse"."\n";
    }else{
        separador();
        echo "El pasajero no realizó modificaciones"."\n";
    }
}

////////////////////////////////////////////////MÓDULOS QUE VERIFICAN////////////////////////////////////////////////////////

/**
 * Verifica que el valor ingresado sea A o B, en caso contrario vuelve a pedir que ingrese 
 * el valor correcto
 * @return $dato
 */

function verificadorLetra($dato){
    while($dato <> "a" && $dato <> "b"){ 
        echo "Esa opción no es correcta, por favor ingrese A o ingrese B";
        $dato=trim(fgets(STDIN));
    }return $dato;
}

/**
 * Verifica que el valor ingreasado sea un entero, en caso contario lo vuelve a pedir hasta que sea un entero
 * @param int $dato
 * @return int
 */
function verificadorInt($dato){
    while(is_numeric($dato) == false){
        echo "Por favor, el valor ".$dato." debe ser numérico. ";
        $dato = trim(fgets(STDIN));
    }
    return $dato;
}




/********************************* MÓDULOS PARA MODIFICAR DATOS *****************************************************/
/**
 * Este modulo cambia los datos del responsable
 * @param object $objResponsable
 */
function modificarDatoResponsable($objResponsable){
    do{
        echo "Ingrese la opción del dato que desea modificar del responsable: "."\n".
             "1. Modificar Nombre "."\n".
             "2. Modificar Apellido "."\n".
             "3. Modificar Número de Licencia "."\n".
             "4. Ver los datos del responsable "."\n".
             "5. Salir "."\n";
        $opcion = trim(fgets(STDIN));
        switch ($opcion){
            case 1: 
                separador();
                echo "Ingrese el nuevo nombre del responsable: "; 
                $nuevoNombre = trim(fgets(STDIN));
                $objResponsable->setRNombre($nuevoNombre);
                $resp = $objResponsable->modificar();
                if($resp == true){
                    echo "El nombre del responsable se ha modificado"."\n";
                }else{
                    echo "Lo siento. El nombre no se pudo modificar. Error: ".$objResponsable->getMensajeError()."\n";
                }
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            case 2: 
                separador();
                echo "Ingrese el nuevo apellido del responsable: "; 
                $nuevoApellido = trim(fgets(STDIN));
                $objResponsable->setRApellido($nuevoApellido);
                $resp = $objResponsable->modificar();
                if($resp == true){
                    echo "El apellido se ha modificado"."\n";
                }else{
                    echo "Lo siento. El apellido no se pudo modificar. Error: ".$objResponsable->getMensajeError()."\n";
                }
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 3: 
                separador();
                echo "Ingrese el nuevo número de licencia del responsable: "; 
                $nuevoNumLicencia = trim(fgets(STDIN));
                $objResponsable->setRNroLicencia($nuevoNumLicencia);
                $resp = $objResponsable->modificar();
                if($resp == true){
                    echo "El número de licencia se ha modificado"."\n";
                }else{
                    echo "Lo siento. El número de licencia no se pudo modificar. Error: ".$objResponsable->getMensajeError()."\n";
                }
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 4: 
                separador();
                echo $objResponsable;
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 5: 
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            default:
            echo "La opción ingresada es incorrecta. Ingrese un número del 1 al 5"."\n"."\n";
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                
        }
        }while($opcion <> 5);
}


/**
 * Este módulo recibe como parámetro un objeto pasajero y
 * lo modifica
 * @param object $objPasajero
 */
function modificarDatoPasajero($objPasajero){
    do{
        echo "Ingrese la opción del dato que desea modificar del pasajero: "."\n".
             "1. Cambiar Nombre "."\n".
             "2. Cambiar Apellido "."\n".
             "3. Cambiar Teléfono "."\n".
             "4. Ver los datos del pasajero "."\n".
             "5. Salir "."\n";
        $opcion = trim(fgets(STDIN));
        switch ($opcion){
            case 1: 
                separador();
                echo "Ingrese el nuevo nombre del pasajero : "; 
                $nuevoNombre = trim(fgets(STDIN));
                $objPasajero->setPNombre($nuevoNombre);
                $resp = $objPasajero->modificar();
                if($resp == true){
                    echo "El nombre se ha modificado"."\n";
                }else{
                    echo "Lo siento. El nombre no se pudo modificar. Error: ".$objPasajero->getMensajeError()."\n";
                }
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 2: 
                separador();
                echo "Ingrese el nuevo apellido del pasajero: "; 
                $nuevoApellido = trim(fgets(STDIN));
                $objPasajero->setPApellido($nuevoApellido);
                $resp = $objPasajero->modificar();
                if($resp == true){
                    echo "El apellido se ha modificado"."\n";
                }else{
                    echo "Lo siento. El apellido no se pudo modificar. Error: ".$objPasajero->getMensajeError()."\n";
                }
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 3: 
                separador();
                echo "Ingrese el nuevo teléfono del pasajero: "; 
                $nuevoTelefono = trim(fgets(STDIN));
                $objPasajero->setPTelefono($nuevoTelefono);
                $resp = $objPasajero->modificar();
                if($resp == true){
                    echo "El teléfono se ha modificado"."\n";
                }else{
                    echo "Lo siento. El teléfono no se pudo modificar. Error ".$objPasajero->getMensajeError()."\n";;
                }
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 4: 
                separador();
                echo $objPasajero;
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 5: 
            break;                
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            default: 
            echo "La opción ingresada es incorrecta. Ingrese un número del 1 al 5"."\n"."\n";
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                
        }
        }while($opcion <> 5);
}



/**
 * Este modulo cambia los datos de la empresa
 * @param object $objEmpresa
 */
function modificarDatoEmpresa($objEmpresa){
    do{
        echo "Ingrese la opción del dato que desea modificar de la empresa: "."\n".
             "1. Modificar Nombre "."\n".
             "2. Modificar Direccion "."\n".
             "3. Ver datos "."\n".
             "4. Salir "."\n";
        $opcion = trim(fgets(STDIN));
        switch ($opcion){
            case 1: 
                separador();
                echo "Ingrese el nuevo nombre de la empresa: "; 
                $nuevoNombre = trim(fgets(STDIN));
                $objEmpresa->setENombre($nuevoNombre);
                $resp = $objEmpresa->modificar();
                if($resp == true){
                    echo "El nombre de la empresa se ha modificado"."\n";
                }else{
                    echo "Lo siento. El nombre de la empresa no se pudo modificar. Error: ".$objEmpresa->getMensajeError()."\n";
                }
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 2: 
                separador();
                echo "Ingrese la nueva dirección de la empresa: "; 
                $nuevaDireccion = trim(fgets(STDIN));
                $objEmpresa->setEDireccion($nuevaDireccion);
                $resp = $objEmpresa->modificar();
                if($resp == true){
                    echo "La direccion se ha modificado"."\n";
                }else{
                    echo "Lo siento. La dirección de la empresa no se pudo modificar. Error:".$objEmpresa->getMensajeError()."\n";
                }
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 3: 
                separador();
                echo $objEmpresa;
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 4: 
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            default:
            echo "La opción ingresada es incorrecta. Ingrese un número del 1 al 4 "."\n"."\n";
            break;
                
        }
        }while($opcion <> 4);
}

/**
 * Este modulo cambia los datos del viaje
 * @param object $objViaje
 */
function modificarDatosViaje($objViaje){
    do{
        echo "Ingrese la opción del dato que desea modificar del viaje: "."\n".
             "1. Modificar el destino "."\n".
             "2. Modificar cantidad máxima de pasajeros "."\n".
             "3. Modificar el importe del viaje "."\n".
             "4. Modificar el  tipo de asiento del viaje "."\n".
             "5. Modificar si es de ida o ida y vuelta "."\n".
             "6. Mostrar datos del viaje "."\n".
             "7. Salir "."\n";
        $opcion = trim(fgets(STDIN));
        switch ($opcion){
            case 1: 
                separador();
                echo "Ingrese el nuevo destino del viaje: ";
                $nuevoDestino = trim(fgets(STDIN));
                $objViaje->setVDestino($nuevoDestino);
                $resp = $objViaje->modificar();
                if($resp){
                    echo "El destino se ha modificado"."\n";
                }else{
                    echo "Lo siento. El destino del viaje no se pudo modificar. Error: ".$objViaje->getMensajeError()."\n";
                }
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 2: 
                separador();
                echo "Ingrese la nueva cantidad máxima de pasajeros del viaje: ";
                $nuevaCapacidad = trim(fgets(STDIN));
                $nuevaCapacidad = verificadorInt($nuevaCapacidad);
                $objViaje->setVCantidadMax($nuevaCapacidad);
                $resp = $objViaje->modificar();
                if($resp){
                    echo "La cantidad máxima de pasajeros del viaje se ha modificado"."\n";
                }else{
                    echo "Lo siento. La cantidad máxima no se pudo modificar. Error: ".$objViaje->getMensajeError()."\n";
                }
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 3: 
                separador();
                echo "Ingrese el nuevo importe del viaje: ";
                $nuevoImporte = trim(fgets(STDIN));
                $nuevoImporte = verificadorInt($nuevoImporte);
                $objViaje->setVImporte($nuevoImporte);
                $resp = $objViaje->modificar();
                if($resp){
                    echo "El importe del viaje ha sido modificado."."\n";
                }else{
                    echo "Lo siento. El importe del viaje no se pudo modificar. Error: ".$objViaje->getMensajeError()."\n";
                }
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 4: 
                separador();
                echo "Si desea modificar el tipo de asiento del viaje ingrese la letra A para primera clase o la letra B para asiento standar: ";
                $nuevoTipoAsiento = verificadorLetra(trim(fgets(STDIN)));
                $objViaje->setTipoAsiento($nuevoTipoAsiento);
                $resp = $objViaje->modificar();
                if($resp){
                    echo "El tipo de asiento del viaje pudo modificarse"."\n";
                }else{
                    echo "Lo siento. El tipo de asiento no se pudo modificar. Error: ".$objViaje->getMensajeError()."\n";
                }
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 5: 
                separador();
                echo "Si desea modificar el tipo de de viaje, por favor ingrese la letra A para viaje de ida o la letra B para ida y vuelta:";
                $nuevoTipoViaje = verificadorLetra(trim(fgets(STDIN)));
                $objViaje->setIdaYVuelta($nuevoTipoViaje);
                $resp = $objViaje->modificar();
                if($resp){
                    echo "El tipo de viaje se ha modificado"."\n";
                }else{
                    echo "Lo siento. El tipo de viaje no se pudo mofificar. Error: ".$objViaje->getMensajeError()."\n";
                }
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 6: 
                separador();
                echo $objViaje;
                separador();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case 7: 
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            default:
            echo "La opción ingresada es incorrecta. Ingrese un número del 1 al 7"."\n"."\n";
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                
        }
        }while($opcion <> 7);
}



/**
 * Este modulo modifica datos del viaje
 */
function opcionesViaje(){
    $objViaje = viajeModificar();
    $opcion = menu();
    do {
    switch ($opcion) {

        // Volver al menu inicial
        case 0: 
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        // Saber la cantidad de pasajeros
        case 1: 
            separador();
            $objViaje->getColObjPasajero();
            $n=count($objViaje->getColObjPasajero());
            echo "La cantidad de pasajeros del viaje con destino a ".$objViaje->getVDestino()." es: ".$n."\n";
            separador();
        break;
    
        // Ver todos los datos del viaje
        case 2: 
            separador();
            $objViaje->arrayObjPasajeros();
            echo "Los datos del viaje son: "."\n".$objViaje."\n";
            separador();
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Ver los datos de los pasajeros
        case 3: 
            separador();
            $objViaje->arrayObjPasajeros();
            $arrayObjPasajeros = $objViaje->getColObjPasajero();
            $n=count($arrayObjPasajeros);
            if($n > 0){
                echo "Las personas del viaje con destino a ".$objViaje->getVDestino()." son: "."\n";
                foreach($arrayObjPasajeros as $objPasajero){
                    separador();
                    echo $objPasajero;
                    separador();
                }
            }else{
                separador();
                echo "No hay pasajeros en este viaje"."\n";
                separador();
            }
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Modificar los datos de un pasajero
        case 4: 
            separador();
            echo "Ingrese el DNI de que pasajero desea cambiar el dato: ";
            $dni = trim(fgets(STDIN));
            $objPasajero = new Pasajero();
            $resp = $objPasajero->buscar($dni);
            if($resp){
                modificarDatoPasajero($objPasajero);
            }else{
                echo "El DNI del pasajero ingresado no existe!"."\n";
            }
            separador();
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            
        // Agregar un pasajero al viaje
        case 5: 
            separador();
            $superaCapacidad = $objViaje->asientosLibres();
            if($superaCapacidad){
                echo "Ingrese cuantos pasajeros nuevos ingresarán al viaje: ";
                $cantPasajerosNuevos = verificadorInt(trim(fgets(STDIN)));
                $cantidadAumentada = count($objViaje->getColObjPasajero()) + $cantPasajerosNuevos;
                if($cantidadAumentada <= $objViaje->getVCantidadMax()){
                    for($i = 0;$i < $cantPasajerosNuevos;$i++){
                        crearUnPasajero($objViaje);
                    }                
                }else{
                    echo "La cantidad de pasajeros es superior a la capacidad máxima"."\n";
                }
            }else{
                echo "El vuelo ya está lleno"."\n";
            }
            separador();
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            
        // Eliminar un pasajero del viaje
        case 6: 
            separador();
            echo "Ingrese el DNI del pasajero que desea eliminar: ";
            $dni = trim(fgets(STDIN));
            $objPasajero = new Pasajero();
            $resp = $objPasajero->buscar($dni);
            if($resp){
                $resp = $objPasajero->eliminar($dni);
                if($resp){
                    echo "El pasajero se pudo eliminar correctamente"."\n";
                }else{
                    echo "No se pudo elimiar el pasajero. Error: ".$objPasajero->getMensajeError()."\n";
                }
            }else{
                echo "El DNI del pasajero ingresado no existe"."\n";
            }
            separador();
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        // Modificar responsable viaje
        case 7: 
            separador();
            modificarDatoResponsable($objViaje->getObjResponsable());
            separador();
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
        // Ver datos de un pasajero
        case 8: 
            separador();
            echo "Ingrese el DNI del pasajero que desea buscar: ";
            $dni = trim(fgets(STDIN));
            $objPasajero = new Pasajero();
            $resp = $objPasajero->buscar($dni);
            if($resp){
                echo "Los datos datos del pasajero ".$dni." son:"."\n";
                echo $objPasajero;
            }else{
                echo "El pasajero ingresado no existe"."\n";
            }
            separador();
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
        // Cambiar datos del viaje
        case 9: 
            modificarDatosViaje($objViaje);
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
        default: 
            echo "El número que ingresó no es válido, por favor ingrese un número del 0 al 9"."\n"."\n";
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        $opcion = menu();
    } while ($opcion != 0);
}

function opcionesInicio($opcion){
    switch($opcion){
            
        // Salir del programa
        case 0:
            exit();
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Cargar un viaje
        case 1:
            separador();
            nuevoViaje();
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Cargar una empresa
        case 2:
            crearEmpresa();
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Cargar una responsable
        case 3:
            crearResponsable();
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Modificar datos de una empresa
        case 4:
            $stringEmpresa = stringObjEmpresa();
            $objEmpresa = new Empresa();
            echo "Ingrese la identificación de la empresa que desea modificar: "."\n".$stringEmpresa;
            $empresaModificar = trim(fgets(STDIN));
            $resp = $objEmpresa->buscar($empresaModificar);
            if($resp){
                modificarDatoEmpresa($objEmpresa);
            }else{
                separador();
                echo "El id de la empresa seleccionada no existe"."\n";
                separador();
            }
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Modificar datos de un responsable
        case 5:
            $stringResponsable = stringObjResponsable();
            $objResponsable = new Responsable();
            echo "Ingrese el número de empleado del responsable que desea modificar: "."\n".$stringResponsable;
            $responsableModificar = trim(fgets(STDIN));
            $resp = $objResponsable->buscar($responsableModificar);
            if($resp){
                modificarDatoResponsable($objResponsable);
            }else{
                separador();
                echo "El número del responsable seleccionado no existe"."\n";
                separador();
            }
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //Modificar un viaje
        case 6:
            opcionesViaje();
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Mostrar todos los viajes
        case 7:
            separador();
            echo stringObjViajes();
            separador();
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        // Elimina un viaje siempre y cuando no tenga pasajeros cargados
        case 8:
            separador();
            echo "Los viajes son: "."\n".stringObjViajes();
            echo "Ingrese el id del viaje que desea eliminar: ";
            $objViaje = new Viaje();
            $id = trim(fgets(STDIN));
            $id = verificadorInt($id);
            $resp = $objViaje->buscar($id);
            while(!$resp){
                echo "El id ingresado no existe, por favor ingrese uno de los viajes mostrados"."\n".stringObjViajes();
                $id = trim(fgets(STDIN));
                $resp = $objViaje->buscar($id);
            }
            $objViaje->arrayObjPasajeros();
            $n=count($objViaje->getColObjPasajero());
            if($n == 0){ //si tiene 0 pasajeros lo elimina
                $resp = $objViaje->eliminar();
                if($resp){
                    echo "El viaje se pudo eliminar"."\n";
                }else{ 
                    echo "El viaje no se puede eliminar porque el id ingresado no coincide con ningún viaje existente."."\n";
                }
            }else{
                separador();
                echo "El viaje tiene pasajeros y no puede ser eliminado"."\n";
            }
            separador();
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        default : 
        echo " La opción ingresada es incorrecta. Ingresar un número del 0 al 8"."\n";
        break;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
}

/**
 * Este módulo le indica al usuario que no hay viajes cargados
 * y que lo cargue
 */
do{
    $objViaje = new Viaje();
    $opcion = "";
    $n=count($objViaje->listar(""));
    if($n > 0){
        $opcion = menuPrincipal();
        opcionesInicio($opcion);
    }else{
        echo "\n"."No hay viajes cargados! Por favor ingrese alguna de las siguientes opciones: "."\n".
        "Ingrese una opción: "."\n".
        "0. Salir"."\n".
        "1. Cargar un nuevo VIAJE"."\n".
        "2. Cargar una nueva EMPRESA"."\n".
        "3. Cargar un nuevo RESPONSABLE"."\n";
        $opcion=trim(fgets(STDIN));
        switch ($opcion){
            // Salir del programa
            case 0:
                exit();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // Cargar un viaje
            case 1:
                separador();
                nuevoViaje();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // Cargar una empresa
            case 2:
                crearEmpresa();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // Cargar una responsable
            case 3:
                crearResponsable();
            break;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        }
    }    
}while($opcion <> 0);

?>