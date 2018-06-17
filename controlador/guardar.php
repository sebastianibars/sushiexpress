<?php

include ("../conexion/conexion.php");

$conexion = crearConexion();
$noCambiarPagina = false;

 date_default_timezone_set("America/Argentina/Mendoza");
        $fechaActual = date("d-m-Y H:i:s",strtotime('-1 hour',strtotime(date("d-m-Y H:i:s"))));
        $horaActual = date("H",strtotime($fechaActual));

        if($horaActual < 17){
            $diaFin = date("d");
            $mesFin = date("m");
            $añoFin = date("Y");

            $fechaInicio = date("d-m-Y H:i:s",strtotime(date("d-m-Y H:i:s")."- 1 days"));
            $diaInicio = date("d",strtotime($fechaInicio));
            $mesInicio = date("m",strtotime($fechaInicio));
            $añoInicio = date("Y",strtotime($fechaInicio));
        }else{
            $fechaFin = date("d-m-Y H:i:s",strtotime(date("d-m-Y")."+ 1 days"));
            $diaFin = date("d",strtotime($fechaFin));
            $mesFin = date("m",strtotime($fechaFin));
            $añoFin = date("Y",strtotime($fechaFin));        

            $diaInicio = date("d");
            $mesInicio = date("m");
            $añoInicio = date("Y");
        }      

if (isset($_POST["formulario"])) {
    if ($_POST["formulario"] == "empleado") {
        $idEmpleado = $_POST["idEmpleado"];
        $nombreEmpleado = $_POST["nombreEmpleado"];
        $apellidoEmpleado = $_POST["apellidoEmpleado"];
        $dniEmpleado = $_POST["dniEmpleado"];
        $aliasEmpleado = $_POST["aliasEmpleado"];

        if (isset($_POST["cajaEmpleado"])) {
            $cajaEmpleado = 1;
        } else {
            $cajaEmpleado = 0;
        }

        if (isset($_POST["presenteEmpleado"])) {
            $presenteEmpleado = 1;
        } else {
            $presenteEmpleado = 0;
        }

        if (isset($_POST["deliveryEmpleado"])) {
            $deliveryEmpleado = 1;
        } else {
            $deliveryEmpleado = 0;
        }

        if ($idEmpleado == "") {
            $sqlGuardar = "insert into empleado (NOMBRE,APELLIDO,DNI,DELIVERY,CAJA,ALIAS,PRESENTE) "
                    . "values ('$nombreEmpleado','$apellidoEmpleado','$dniEmpleado','$deliveryEmpleado',"
                    . "'$cajaEmpleado','$aliasEmpleado','$presenteEmpleado')";
        } else {
            $sqlGuardar = "update empleado set "
                    . "NOMBRE='$nombreEmpleado',"
                    . "APELLIDO='$apellidoEmpleado',"
                    . "DNI='$dniEmpleado',"
                    . "DELIVERY='$deliveryEmpleado',"
                    . "CAJA='$cajaEmpleado',"
                    . "ALIAS='$aliasEmpleado',"
                    . "PRESENTE='$presenteEmpleado' "
                    . "where ID='$idEmpleado'";
        }
        $url = "../vista/configuracionEmpleado.php";
    } else if ($_POST["formulario"] == "tabla") {
        $idTabla = $_POST["idTabla"];
        $maximo = $_POST["maximoTabla"];
        $minimo = $_POST["minimoTabla"];
        $precio = $_POST["precioTabla"];
        $piezas = $_POST["piezasTabla"];
        if (isset($_POST["mostrarTabla"])) {
            $mostrar = 1;
        } else {
            $mostrar = 0;
        }

        if ($idTabla == "") {
            $sqlGuardar = "insert into tabla (CANTIDADPIEZAS,MINIMOCANTIDAD,MAXIMOCANTIDAD,PRECIO,MOSTRAR)  "
                    . "values ('$piezas','$minimo','$maximo','$precio','$mostrar')";
        } else {
            $sqlGuardar = "update tabla set "
                    . "CANTIDADPIEZAS='$piezas',"
                    . "MINIMOCANTIDAD='$minimo',"
                    . "MAXIMOCANTIDAD='$maximo',"
                    . "PRECIO='$precio',"
                    . "MOSTRAR='$mostrar' "
                    . "where ID='$idTabla'";
        }
        
        $url = "../vista/configuracionTabla.php";
    }  else if ($_POST["formulario"] == "cantidadPiezas") {
        
        $minimoPiezas = $_POST["minimoPiezas"];
        
        $countPiezas = "select * from minimopiezas";
        $cantidadPiezas = $conexion->query($countPiezas);
        
        $cantFilas = $cantidadPiezas->num_rows;
        
        if($cantFilas>0){
            $sqlGuardar="update minimopiezas set piezas='$minimoPiezas'";
        }else{
            $sqlGuardar="insert into minimopiezas (piezas) value ('$minimoPiezas')";
        }
        $url = "../vista/configuracionTabla.php";
    } else if ($_POST["formulario"] == "grupoVariedad") {
        
        $nombreGrupoVariedad = $_POST["nombreGrupoVariedad"];
        $idGrupoVariedad=$_POST["idGrupoVariedad"];
        
        if($idGrupoVariedad==""){
            $sqlGuardar="insert into grupotabla(nombre) values ('$nombreGrupoVariedad')";
        }else {
            $sqlGuardar="update grupotabla set NOMBRE='$nombreGrupoVariedad' where ID='$idGrupoVariedad'";
        }
        
        $url = "../vista/configuracionVariedadTabla.php";
    } else if ($_POST["formulario"] == "variedad") {
        
        $idVariedad=$_POST["idVariedad"];
        $nombreVariedad = $_POST["nombreVariedad"];
        $IdGrupoVariedad=$_POST["selectGrupoVariedad"];
        
        if (isset($_POST["mostrarVariedad"])) {
            $mostrar = 1;
        } else {
            $mostrar = 0;
        }

        if($idVariedad==""){
            $sqlGuardar="insert into variedadtabla(NOMBRE,GRUPOTABLAID,MOSTRAR) values ('$nombreVariedad',$IdGrupoVariedad,'$mostrar')";
        }else {
            $sqlGuardar="update variedadtabla set NOMBRE='$nombreVariedad',GRUPOTABLAID=$IdGrupoVariedad,MOSTRAR='$mostrar' where ID=$idVariedad";
        }

        $url = "../vista/configuracionVariedadTabla.php";
    } else if ($_POST["formulario"] == "delicia") {
        $idDelicia=$_POST["id"];
        $nombreDelicia = $_POST["nombreDelicia"];
        $precioDelicia = $_POST["precioDelicia"];
        if($precioDelicia==""){
            $precioDelicia=0;
        }
            
        if (isset($_POST["mostrarDelicia"])) {
            $mostrar = 1;
        } else {
            $mostrar = 0;
        }
        
        if($idDelicia==""){
            $sqlGuardar="insert into delicia(NOMBRE,PRECIO,MOSTRAR) values ('$nombreDelicia',$precioDelicia,'$mostrar')";
        }else {
            $sqlGuardar="update delicia set NOMBRE='$nombreDelicia',PRECIO=$precioDelicia,MOSTRAR=$mostrar where ID=$idDelicia";
            echo $sqlGuardar;
        }
        
        $url = "../vista/configuracionDelicia.php";
    }else if ($_POST["formulario"] == "editarCantidadDelicia") {

        $idCantidadDelicia=$_POST["idCantidadDelicia"];
        $idDeliciaCantidadDelicia=$_POST["idDeliciaCantidadDelicia"];
        $cantidadCantidadDelicia = $_POST["cantidadDelicia"];
        $precioCantidadDelicia = $_POST["precioCantidad"];
        
        if($idCantidadDelicia==""){
            $sqlGuardar="insert into cantidaddelicia(DELICIAID,CANTIDAD,PRECIO) values ('$idDeliciaCantidadDelicia',$cantidadCantidadDelicia,'$precioCantidadDelicia')";
            $updateDelicia = "update delicia set PRECIO=null, CANTIDAD=true where ID=".$idDeliciaCantidadDelicia;
            $ejecutarQuery = $conexion->query($updateDelicia);
        }else {
            $sqlGuardar="update cantidaddelicia set DELICIAID='$idDeliciaCantidadDelicia',PRECIO=$precioCantidadDelicia,CANTIDAD='$cantidadCantidadDelicia' where ID=$idCantidadDelicia";
        }
        
        $url = "../vista/configuracionDelicia.php?accion=cargarCantidad&id=". $idDeliciaCantidadDelicia;
    } else if ($_POST["formulario"] == "cantidadDelicia") {

        $idCantidadDelicia=$_POST["idCantidadDelicia"];
        $idDeliciaCantidadDelicia=$_POST["idDeliciaCantidadDelicia"];
        $cantidadCantidadDelicia = $_POST["cantidadCantidadDelicia"];
        $precioCantidadDelicia = $_POST["precioCantidadDelicia"];
        
        if($idCantidadDelicia==""){
            $sqlGuardar="insert into cantidaddelicia(DELICIAID,CANTIDAD,PRECIO) values ('$idDeliciaCantidadDelicia',$cantidadCantidadDelicia,'$precioCantidadDelicia')";
            $updateDelicia = "update delicia set PRECIO=0, CANTIDAD=true where ID=".$idDeliciaCantidadDelicia;
            $ejecutarQuery = $conexion->query($updateDelicia);
        }else {
            $sqlGuardar="update cantidaddelicia set DELICIAID='$idDeliciaCantidadDelicia',PRECIO=$precioCantidadDelicia,CANTIDAD='$cantidadCantidadDelicia' where ID=$idCantidadDelicia";
        }
        
        $url = "../vista/configuracionCantidadDelicia.php?accion=cargar&id=". $idDeliciaCantidadDelicia;
    } else if ($_POST["formulario"] == "bebida") {

        $idBebida=$_POST["id"];
        $nombreBebida=$_POST["nombreBebida"];
        $precioBebida = $_POST["precioBebida"];
        
         if (isset($_POST["mostrarBebida"])) {
            $mostrarBebida = 1;
        } else {
            $mostrarBebida = 0;
        }
               
        if($idBebida==""){
            $sqlGuardar="insert into bebida(NOMBRE,PRECIO,MOSTRAR) values ('$nombreBebida',$precioBebida,'$mostrarBebida')";
        }else {
            $sqlGuardar="update bebida set NOMBRE='$nombreBebida',PRECIO=$precioBebida,MOSTRAR='$mostrarBebida' where ID=$idBebida";
        }
        
        $url = "../vista/configuracionBebida.php";
    }else if ($_POST["formulario"] == "adicional") {

        $idAdicional=$_POST["id"];
        $nombreAdicional=$_POST["nombreAdicional"];
        $precioAdicional = $_POST["precioAdicional"];
        
         if (isset($_POST["mostrarAdicional"])) {
            $mostrarAdicional = 1;
        } else {
            $mostrarAdicional = 0;
        }
               
        if($idAdicional==""){
            $sqlGuardar="insert into adicional(NOMBRE,PRECIO,MOSTRAR) values ('$nombreAdicional',$precioAdicional,'$mostrarAdicional')";
        }else {
            $sqlGuardar="update adicional set NOMBRE='$nombreAdicional',PRECIO=$precioAdicional,MOSTRAR='$mostrarAdicional' where ID=$idAdicional";
        }
        
        $url = "../vista/configuracionAdicional.php";
    }else if ($_POST["formulario"] == "delivery") {

        $idDelivery=$_POST["id"];
        $zonaDelivery=$_POST["zonaDelivery"];
        $precioDelivery = $_POST["precioDelivery"];
        
        if($idDelivery==""){
            $sqlGuardar="insert into delivery(ZONA,PRECIO) values ('$zonaDelivery',$precioDelivery)";
        }else {
            $sqlGuardar="update delivery set ZONA='$zonaDelivery',PRECIO=$precioDelivery where ID=$idDelivery";
        }
        
        $url = "../vista/configuracionDelivery.php";
    }else if ($_POST["formulario"] == "pedidosYa") {
        $idPedidosYa=$_POST["id"];
        $numeroPromocion=$_POST["numeroPromocion"];
        $idPedidoPromocion = $_POST["idPedidoPromocion"];

        if (isset($_POST["mostrarPedidosYa"])) {
            $mostrarPedidosYa = 1;
        } else {
            $mostrarPedidosYa = 0;
        }        
        if($idPedidosYa==""){
            $sqlGuardar="insert into pedidosya(NUMEROPROMOCION,PEDIDOPROMOCIONID,MOSTRAR) values ($numeroPromocion,$idPedidoPromocion,$mostrarPedidosYa)";
        }else {
            $sqlGuardar="update pedidosya set NUMEROPROMOCION=$numeroPromocion,PEDIDOPROMOCIONID=$idPedidoPromocion,MOSTRAR=$mostrarPedidosYa where ID=$idPedidosYa";
        }
        
        $url = "../vista/pedidosYaPromociones.php";
    }else if ($_POST["formulario"] == "promociones") {
        $idPromocion=$_POST["id"];
        $numeroPromocion=$_POST["numeroPromocion"];
        $idPedidoPromocion = $_POST["idPedidoPromocion"];

        if (isset($_POST["mostrarpromociones"])) {
            $mostrarPromociones = 1;
        } else {
            $mostrarPromociones = 0;
        }        
        if($idPromocion==""){
            $sqlGuardar="insert into promociones(NUMEROPROMOCION,PEDIDOPROMOCIONID,MOSTRAR) values ($numeroPromocion,$idPedidoPromocion,$mostrarPromociones)";
        }else {
            $sqlGuardar="update promociones set NUMEROPROMOCION=$numeroPromocion,PEDIDOPROMOCIONID=$idPedidoPromocion,MOSTRAR=$mostrarPromociones where ID=$idPromocion";
        }
        
        $url = "../vista/configuracionPromociones.php";
    }else if ($_POST["formulario"] == "datosCliente") {
       
        $idCliente="";
        $idTelefono="";
        $idDireccion="";
        $nombre="";
        $notas="";
        $numeroPedido="";
        $paga="";
        
        $consultarPedidoFinal="SELECT PEDIDOFINALID FROM PEDIDOFINAL_USUARIO_1";
        $resPedidoFinalId = $conexion->query($consultarPedidoFinal);
        while ($registroPedidoFinalId = $resPedidoFinalId->fetch_array(MYSQLI_BOTH)) {
            $pedidoFinalId=$registroPedidoFinalId["PEDIDOFINALID"];
        }
        if (isset($_POST["nombre"])) {
            $nombre=$_POST["nombre"];
        }
        if (isset($_POST["notas"])) {
            $notas=$_POST["notas"];
        }
        if (isset($_POST["paga"])) {
            $paga=$_POST["paga"];
        }
        if (isset($_POST["idCliente"]) && $_POST["idCliente"]!=0) {
            $idCliente=$_POST["idCliente"];
        }else{
            $numeroCliente=$_POST["numeroCliente"];
            $guardarCliente="insert into cliente (NUMERO) VALUES ('$numeroCliente')";
            $conexion->query($guardarCliente);
            $seleccionarClienteId="SELECT MAX(ID) ID FROM cliente";
            $resSeleccionarClienteId=$conexion->query($seleccionarClienteId);
            while ($registroSeleccionarClienteId = $resSeleccionarClienteId->fetch_array(MYSQLI_BOTH)) {
                 $idCliente=$registroSeleccionarClienteId["ID"];
            }
        }
        if (isset($_POST["idTelefono"]) && $_POST["idTelefono"]!=0) {
            $idTelefono=$_POST["idTelefono"];
        }else{
            $numeroTelefono=$_POST["numeroTelefono"];
            $guardarTelefono="insert into telefono (NUMERO) VALUES ($numeroTelefono)";
            $conexion->query($guardarTelefono);
            $seleccionarTelefonoId="SELECT MAX(ID) ID FROM telefono";
            $resSeleccionarTelefonoId=$conexion->query($seleccionarTelefonoId);
            while ($registroSeleccionarTelefonoId = $resSeleccionarTelefonoId->fetch_array(MYSQLI_BOTH)) {
                 $idTelefono=$registroSeleccionarTelefonoId["ID"];
            }
        }
        if (isset($_POST["idDireccion"]) && $_POST["idDireccion"]!=0) {
            $idDireccion = $_POST["idDireccion"];
        }else{
            $zonaDireccion=$_POST["zona"];
            $calleDireccion=$_POST["calle"];
            $numeroDireccion=$_POST["numeracion"];
            $pisoDireccion=$_POST["piso"];
            $departamentoDireccion=$_POST["departamento"];
            $deliveryDireccion=$_POST["delivery"];
            
            $guardarDireccion="insert into direccion (CALLE, NUMERACION, PISO, DEPARTAMENTO,ZONA,delivery)
                               values ('$calleDireccion',$numeroDireccion,$pisoDireccion,$departamentoDireccion,'$zonaDireccion',$deliveryDireccion)";
            $conexion->query($guardarDireccion);
            $seleccionarDireccionId="SELECT MAX(ID) ID FROM direccion";
            $resSeleccionarDireccionId=$conexion->query($seleccionarDireccionId);
            while ($registroSeleccionarDireccionId = $resSeleccionarDireccionId->fetch_array(MYSQLI_BOTH)) {
                 $idDireccion=$registroSeleccionarDireccionId["ID"];
            }
        }
        
       
        
        $consultaNumeroPedido="SELECT IFNULL(MAX(NUMEROPEDIDO+1),1) NUMEROPEDIDO FROM  pedidofinal 
                               WHERE FECHAPEDIDO BETWEEN '$añoInicio-$mesInicio-$diaInicio 17:00:00' and '$añoFin-$mesFin-$diaFin 16:59:59'";
        $resNumeroPedido = $conexion->query($consultaNumeroPedido);
        while ($registroNumeroPedido = $resNumeroPedido->fetch_array(MYSQLI_BOTH)) {
            $numeroPedido=$registroNumeroPedido["NUMEROPEDIDO"];
        }
        
        $sqlGuardar="update pedidofinal set DIRECCIONID = $idDireccion,
                                                    CLIENTEID = $idCliente,
                                                    TELEFONOID = $idTelefono,
                                                    NOMBRE = '$nombre',
                                                    NOTAS = '$notas',
                                                    PAGA = '$paga',
                                                    NUMEROPEDIDO = $numeroPedido,
                                                    DELIVERYID = null,
                                                    CAMBIOLOCALID = 0
                                                    where id=(select PEDIDOFINALID FROM pedidofinal_usuario_1)";
        $noCambiarPagina=true;
        
   }else if ($_POST["formulario"] == "agrupate") {
        $idAgrupate=$_POST["id"];
        $numeroPromocion=$_POST["numeroPromocion"];
        $idPedidoPromocion = $_POST["idPedidoPromocion"];

        if (isset($_POST["mostrarAgrupate"])) {
            $mostrarAgrupate = 1;
        } else {
            $mostrarAgrupate = 0;
        }        
        if($idAgrupate==""){
            $sqlGuardar="insert into agrupate(NUMEROPROMOCION,PEDIDOPROMOCIONID,MOSTRAR) values ($numeroPromocion,$idPedidoPromocion,$mostrarAgrupate)";
        }else {
            $sqlGuardar="update agrupate set NUMEROPROMOCION=$numeroPromocion,PEDIDOPROMOCIONID=$idPedidoPromocion,MOSTRAR=$mostrarAgrupate where ID=$idAgrupate";
        }
        
        $url = "../vista/agrupatePromociones.php";
    }else if ($_POST["formulario"] == "pedidoRetira") {
       
        $nombre="";
        $notas="";
        $paga="";
        
        $consultarPedidoFinal="SELECT PEDIDOFINALID FROM PEDIDOFINAL_USUARIO_1";
        $resPedidoFinalId = $conexion->query($consultarPedidoFinal);
        while ($registroPedidoFinalId = $resPedidoFinalId->fetch_array(MYSQLI_BOTH)) {
            $pedidoFinalId=$registroPedidoFinalId["PEDIDOFINALID"];
        }
        if (isset($_POST["nombre"])) {
            $nombre=$_POST["nombre"];
        }
        if (isset($_POST["notas"])) {
            $notas=$_POST["notas"];
        }
        if (isset($_POST["paga"])) {
            $paga=$_POST["paga"];
        }
        
        $numeroTelefono=$_POST["numeroTelefono"];
        $existeTelefono = "SELECT ID FROM TELEFONO WHERE NUMERO =".$numeroTelefono;
        $resExisteTelefono = $conexion->query($existeTelefono);
        $existe = $resExisteTelefono->num_rows;
        if($existe > 0){
            while ($registroExisteTelefono = $resExisteTelefono->fetch_array(MYSQLI_BOTH)) {
                $idTelefono=$registroExisteTelefono["ID"];
            }
        }else{        
            $guardarTelefono="insert into telefono (NUMERO) VALUES ($numeroTelefono)";
            $conexion->query($guardarTelefono);
            $seleccionarTelefonoId="SELECT MAX(ID) ID FROM telefono";
            $resSeleccionarTelefonoId=$conexion->query($seleccionarTelefonoId);
            while ($registroSeleccionarTelefonoId = $resSeleccionarTelefonoId->fetch_array(MYSQLI_BOTH)) {
                $idTelefono=$registroSeleccionarTelefonoId["ID"];
            }
        }

        $consultaNumeroPedido="SELECT IFNULL(MAX(NUMEROPEDIDO+1),1) NUMEROPEDIDO FROM  pedidofinal
                               WHERE FECHAPEDIDO BETWEEN '$añoInicio-$mesInicio-$diaInicio 17:00:00' and '$añoFin-$mesFin-$diaFin 16:59:59'";
        $resNumeroPedido = $conexion->query($consultaNumeroPedido);
        while ($registroNumeroPedido = $resNumeroPedido->fetch_array(MYSQLI_BOTH)) {
            $numeroPedido=$registroNumeroPedido["NUMEROPEDIDO"];
        }
        
        $sqlGuardar="update pedidofinal set NOMBRE = '$nombre',
                                            NOTAS = '$notas',
                                            PAGA = '$paga',
                                            NUMEROPEDIDO = $numeroPedido,
                                            TELEFONOID = $idTelefono,
                                            DELIVERYID = null,
                                            CAMBIOLOCALID = 0
                                            where id=(select PEDIDOFINALID FROM pedidofinal_usuario_1)";
        $url = "../vista/inicio.php";
   }else if ($_POST["formulario"] == "pedidoEspera") {
       
        $nombre="";
        $notas="";
        $paga="";
        
        $consultarPedidoFinal="SELECT PEDIDOFINALID FROM PEDIDOFINAL_USUARIO_1";
        $resPedidoFinalId = $conexion->query($consultarPedidoFinal);
        while ($registroPedidoFinalId = $resPedidoFinalId->fetch_array(MYSQLI_BOTH)) {
            $pedidoFinalId=$registroPedidoFinalId["PEDIDOFINALID"];
        }
        if (isset($_POST["nombre"])) {
            $nombre=$_POST["nombre"];
        }
        if (isset($_POST["notas"])) {
            $notas=$_POST["notas"];
        }
        if (isset($_POST["paga"])) {
            $paga=$_POST["paga"];
        }

        $consultaNumeroPedido="SELECT IFNULL(MAX(NUMEROPEDIDO+1),1) NUMEROPEDIDO FROM  pedidofinal
                               WHERE FECHAPEDIDO BETWEEN '$añoInicio-$mesInicio-$diaInicio 17:00:00' and '$añoFin-$mesFin-$diaFin 16:59:59'";
        $resNumeroPedido = $conexion->query($consultaNumeroPedido);
        while ($registroNumeroPedido = $resNumeroPedido->fetch_array(MYSQLI_BOTH)) {
            $numeroPedido=$registroNumeroPedido["NUMEROPEDIDO"];
        }
        
        $sqlGuardar="update pedidofinal set NOMBRE = '$nombre',
                                            NOTAS = '$notas',
                                            PAGA = '$paga',
                                            NUMEROPEDIDO = $numeroPedido,
                                            DELIVERYID = null,
                                            CAMBIOLOCALID = 0
                                            where id=(select PEDIDOFINALID FROM pedidofinal_usuario_1)";
        $url = "../vista/inicio.php";
   }
   //else if ($_POST["formulario"] == "codigosAgrupate") {
   //     $codigo1 = "";
   //    $codigo2 = "";
   //     if (isset($_POST["codigo1"])) {
   //         $codigo1=$_POST["codigo1"];
   //     }
   //     if (isset($_POST["codigo2"])) {
   //         $codigo2=$_POST["codigo2"];
   //     }
   //}
   
    $ejecutarQuery = $conexion->query($sqlGuardar);
    
    if(!$noCambiarPagina){
      Header("Location: $url");
    }
}
?>

