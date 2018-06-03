<html>
<?php
    include ("../conexion/conexion.php");
    $conexion = crearConexion();
    
    $detalleNumeroPedido="";
    $resConsultaTelefonoNumero="";
    $pedidoCompletoDetalle="";   
    if (isset($_GET["accion"])) {
        if (($_GET["accion"]) == 'cargarDetalle') {
            $pedidoFinalId=$_GET["id"];
            $consultaDetalle ="SELECT 
                            NUMEROPEDIDO,
                            DATE_FORMAT(FECHAPEDIDO, \"%H:%i\" ) FECHAPEDIDO,
                            DATE_FORMAT(FECHAENTREGA, \"%H:%i\" ) FECHAENTREGA,
                            PRECIOTOTAL,
                            NOMBRE,
                            NOTAS,
                            PAGA,
                            RETIRO,
                            DIRECCIONID,
                            CLIENTEID,
                            TELEFONOID,
                            DELIVERYID,
                            CAMBIOLOCALID,
                            E,
                            P,
                            PDF
                            FROM pedidofinal
                            WHERE ID =".$pedidoFinalId;
            $resConsultaDetalle = $conexion->query($consultaDetalle);
            while ($registroConsultaDetalle = $resConsultaDetalle->fetch_array(MYSQLI_BOTH)) {
                $detalleNumeroPedido = $registroConsultaDetalle["NUMEROPEDIDO"];
                $detalleFechaPedido = $registroConsultaDetalle["FECHAPEDIDO"];
                $detalleFechaEntrega = $registroConsultaDetalle["FECHAENTREGA"];
                $detalleNombre = $registroConsultaDetalle["NOMBRE"];
                $detalleTelefonoId = $registroConsultaDetalle["TELEFONOID"];
                                
                $detalleTelefonoNumero="-";
                        
                if(!is_null($detalleTelefonoId)){
                    $consultaTelefono ="SELECT NUMERO FROM TELEFONO WHERE ID =".$detalleTelefonoId;
                    $resConsultaTelefono = $conexion->query($consultaTelefono);
                    while ($registroConsultaTelefono = $resConsultaTelefono->fetch_array(MYSQLI_BOTH)) {
                        $detalleTelefonoNumero = $registroConsultaTelefono["NUMERO"];                
                    }   
                }
                $detalleTotal = $registroConsultaDetalle["PRECIOTOTAL"];
                $detallePaga = $registroConsultaDetalle["PAGA"];
                $detalleVuelto = $detallePaga-$detalleTotal;
                
                if(is_null($registroConsultaDetalle["DELIVERYID"])){
                    $detalleL = "Seleccionar";
                }else if($registroConsultaDetalle["DELIVERYID"]==0){
                    $detalleL = "Listo";
                }else{
                    $consultaDelivery ="SELECT ALIAS FROM EMPLEADO WHERE ID =".$registroConsultaDetalle["DELIVERYID"];
                    $resConsultaDelivery = $conexion->query($consultaDelivery);
                    while ($registroConsultaDelivery = $resConsultaDelivery->fetch_array(MYSQLI_BOTH)) {
                        $detalleL = $registroConsultaDelivery["ALIAS"];                
                    } 
                }
                
                if($registroConsultaDetalle["E"] == 0){
                    $detalleE = "NO";
                }else{
                    $detalleE = "SI";
                }
                
                if($registroConsultaDetalle["P"] == 0){
                    $detalleP = "NO";
                }else{
                    $detalleP = "SI";
                }
                
                if($registroConsultaDetalle["RETIRO"] == 1){
                    $detalleRetiro = "Retira en local";
                }else if($registroConsultaDetalle["RETIRO"] == 2){
                    $detalleRetiro = "Delivery";
                }else{
                    $detalleRetiro = "Espera";
                }
                
                if($registroConsultaDetalle["CAMBIOLOCALID"] == 0){
                    $detalleCambioLocal = "NO";
                }else{
                    $consultaCambioLocal ="SELECT NOMBRE FROM CAMBIOLOCAL WHERE ID = ".$registroConsultaDetalle["CAMBIOLOCALID"];
                    $resConsultaCambioLocal = $conexion->query($consultaCambioLocal);
                    while ($registroConsultaCambioLocal = $resConsultaCambioLocal->fetch_array(MYSQLI_BOTH)) {
                        $detalleCambioLocal = $registroConsultaCambioLocal["NOMBRE"];                
                    } 
                }
                
                if(!is_null($registroConsultaDetalle["DIRECCIONID"])){
                    $consultaDireccion ="SELECT CALLE, NUMERACION, PISO, DEPARTAMENTO, ZONA, DELIVERY FROM DIRECCION WHERE ID = ".$registroConsultaDetalle["DIRECCIONID"];
                    $resConsultaDireccion = $conexion->query($consultaDireccion);
                    while ($registroConsultaDireccion = $resConsultaDireccion->fetch_array(MYSQLI_BOTH)) {
                        $detalleDireccionCalle =  $registroConsultaDireccion["CALLE"];                
                        $detalleDireccionNumeracion =  $registroConsultaDireccion["NUMERACION"];  
                        if(is_null($registroConsultaDireccion["PISO"])){
                            $detalleDireccionPiso = " - ";
                        }else{
                            $detalleDireccionPiso = $registroConsultaDireccion["PISO"];
                        }
                        if(is_null($registroConsultaDireccion["DEPARTAMENTO"])){
                            $detalleDireccionDepartamento = " - ";
                        }else{
                            $detalleDireccionDepartamento = $registroConsultaDireccion["DEPARTAMENTO"];
                        }
                        $detalleDireccionZona =  $registroConsultaDireccion["ZONA"];                
                        $detalleDireccionDelivery =  $registroConsultaDireccion["DELIVERY"];                
                    } 
                }else{
                    $detalleDireccionCalle =  " - ";
                    $detalleDireccionNumeracion =  " - ";  
                    $detalleDireccionPiso = " - ";
                    $detalleDireccionDepartamento = " - ";
                    $detalleDireccionZona = " - ";
                    $detalleDireccionDelivery = " - ";
                }
                
                $detallePDF = $registroConsultaDetalle["PDF"];
                
                if(is_null($registroConsultaDetalle["NOTAS"])){
                    $detalleNota = " - ";
                }else{
                    $detalleNota = $registroConsultaDetalle["NOTAS"];
                }
                
                $consultaPedidoCompletoCantPiezas = "SELECT CANTIDADPIEZASIDRELACION,CANTIDADPIEZASID,CANTIDADPIEZAS 
                                                     FROM PEDIDOFINAL_CANTPIEZAS WHERE PEDIDOFINALID = ".$pedidoFinalId;
                $resConsultaPedidoCompletoCantPiezas = $conexion->query($consultaPedidoCompletoCantPiezas);
                $cantComaPiezas = false;
                while ($registroConsultaPedidoCompletoCantPiezas = $resConsultaPedidoCompletoCantPiezas->fetch_array(MYSQLI_BOTH)) {    
                    if($cantComaPiezas){
                        $pedidoCompletoDetalle = $pedidoCompletoDetalle.", ";
                    }
                    $cantidadPiezasRelacionId = $registroConsultaPedidoCompletoCantPiezas["CANTIDADPIEZASIDRELACION"];
                    $consultaPedidoCompletoVariedad = "SELECT VARIEDADID,NOMBREVARIEDAD,CANTIDAD 
                                                       FROM pedidofinal_variedad WHERE PEDIDOFINALID = ".$pedidoFinalId." 
                                                       AND CANTIDADPIEZASIDRELACION = ".$cantidadPiezasRelacionId;
                    $pedidoCompletoDetalle = $pedidoCompletoDetalle.$registroConsultaPedidoCompletoCantPiezas["CANTIDADPIEZAS"].
                                " PIEZAS[";
                    $resConsultaPedidoCompletoVariedad = $conexion->query($consultaPedidoCompletoVariedad);
                    $cantComa = false;
                    while ($registroConsultaPedidoCompletoVariedad = $resConsultaPedidoCompletoVariedad->fetch_array(MYSQLI_BOTH)) {    
                        if($cantComa){
                            $pedidoCompletoDetalle = $pedidoCompletoDetalle.", ";
                        }
                        $pedidoCompletoDetalle = $pedidoCompletoDetalle.$registroConsultaPedidoCompletoVariedad["NOMBREVARIEDAD"];
                        if($registroConsultaPedidoCompletoVariedad["CANTIDAD"]==0){
                            $pedidoCompletoDetalle=$pedidoCompletoDetalle."(-)";        
                        }else{
                            $pedidoCompletoDetalle=$pedidoCompletoDetalle."(".$registroConsultaPedidoCompletoVariedad["CANTIDAD"].")";        
                        }
                        $cantComa = true;
                    }
                    $pedidoCompletoDetalle = $pedidoCompletoDetalle."]";
                    $cantComaPiezas=true;
                }               

                $consultaDelicia ="SELECT NOMBREDELICIA, CANTIDAD FROM pedidofinal_delicia WHERE PEDIDOFINALID = ".$pedidoFinalId;
                $resConsultaDelicia = $conexion->query($consultaDelicia);
                $comaDelicia = false;
                while ($registroConsultaDelicia = $resConsultaDelicia->fetch_array(MYSQLI_BOTH)) {
                    if(strlen($pedidoCompletoDetalle)!=0 && !$comaDelicia){
                        $pedidoCompletoDetalle = $pedidoCompletoDetalle.", ";
                    }
                    if($comaDelicia){
                        $pedidoCompletoDetalle = $pedidoCompletoDetalle.", ";
                    }
                    $pedidoCompletoDetalle = $pedidoCompletoDetalle.$registroConsultaDelicia["NOMBREDELICIA"]."(".$registroConsultaDelicia["CANTIDAD"].")";
                    $comaDelicia = true;
                }

                $consultaBebida ="SELECT NOMBREBEBIDA,CANTIDAD FROM pedidofinal_bebida WHERE PEDIDOFINALID = ".$pedidoFinalId;
                $resConsultaBebida = $conexion->query($consultaBebida);
                $comaBebida = false;
                while ($registroConsultaBebida = $resConsultaBebida->fetch_array(MYSQLI_BOTH)) {
                    if(strlen($pedidoCompletoDetalle)!=0 && !$comaBebida){
                        $pedidoCompletoDetalle = $pedidoCompletoDetalle.", ";
                    }
                    if($comaBebida){
                        $pedidoCompletoDetalle = $pedidoCompletoDetalle.", ";
                    }
                    $pedidoCompletoDetalle = $pedidoCompletoDetalle.$registroConsultaBebida["NOMBREBEBIDA"]."(".$registroConsultaBebida["CANTIDAD"].")";
                    $comaBebida = true;
                }

                $consultaPedidosYa ="SELECT NOMBREPROMOCION, CANTIDAD FROM pedidofinal_pedidosya  WHERE PEDIDOFINALID = ".$pedidoFinalId;
                $resConsultaPedidosYa = $conexion->query($consultaPedidosYa);
                $comaPedidosYa = false;
                while ($registroConsultaPedidosYa = $resConsultaPedidosYa->fetch_array(MYSQLI_BOTH)) {
                    if(strlen($pedidoCompletoDetalle)!=0 && !$comaPedidosYa){
                        $pedidoCompletoDetalle = $pedidoCompletoDetalle.", ";
                    }
                    if($comaPedidosYa){
                        $pedidoCompletoDetalle = $pedidoCompletoDetalle.", ";
                    }
                    $pedidoCompletoDetalle = $pedidoCompletoDetalle.$registroConsultaPedidosYa["NOMBREPROMOCION"]."(".$registroConsultaPedidosYa["CANTIDAD"].")";
                    $comaPedidosYa = true;
                }

                $consultaAdicional ="SELECT NOMBREADICIONAL, CANTIDAD FROM pedidofinal_adicional WHERE PEDIDOFINALID = ".$pedidoFinalId;
                $resConsultaAdicional = $conexion->query($consultaAdicional);
                $comaAdicional = false;
                while ($registroConsultaAdicional = $resConsultaAdicional->fetch_array(MYSQLI_BOTH)) {
                    if(strlen($pedidoCompletoDetalle)!=0 && !$comaAdicional){
                        $pedidoCompletoDetalle = $pedidoCompletoDetalle.", ";
                    }
                    if($comaAdicional){
                        $pedidoCompletoDetalle = $pedidoCompletoDetalle.", ";
                    }
                    $pedidoCompletoDetalle = $pedidoCompletoDetalle.$registroConsultaAdicional["NOMBREADICIONAL"]."(".$registroConsultaAdicional["CANTIDAD"].")";
                    $comaAdicional = true;
                }

                $consultaPromocion ="SELECT NOMBREPROMOCION, CANTIDAD FROM pedidofinal_promocion WHERE PEDIDOFINALID = ".$pedidoFinalId;
                $resConsultaPromocion = $conexion->query($consultaPromocion);
                $comaPromocion = false;
                while ($registroConsultaPromocion = $resConsultaPromocion->fetch_array(MYSQLI_BOTH)) {
                    if(strlen($pedidoCompletoDetalle)!=0 && !$comaPromocion){
                        $pedidoCompletoDetalle = $pedidoCompletoDetalle.", ";
                    }
                    if($comaPromocion){
                        $pedidoCompletoDetalle = $pedidoCompletoDetalle.", ";
                    }
                    $pedidoCompletoDetalle = $pedidoCompletoDetalle.$registroConsultaPromocion["NOMBREPROMOCION"]."(".$registroConsultaPromocion["CANTIDAD"].")";
                    $comaPromocion = true;
                }
                
                $consultaAgrupate ="SELECT NOMBREPROMOCION, CANTIDAD FROM pedidofinal_agrupate WHERE PEDIDOFINALID = ".$pedidoFinalId;
                $resConsultaAgrupate = $conexion->query($consultaAgrupate);
                $comaAgrupate = false;
                while ($registroConsultaAgrupate = $resConsultaAgrupate->fetch_array(MYSQLI_BOTH)) {
                    if(strlen($pedidoCompletoDetalle)!=0 && !$comaAgrupate){
                        $pedidoCompletoDetalle = $pedidoCompletoDetalle.", ";
                    }
                    if($comaAgrupate){
                        $pedidoCompletoDetalle = $pedidoCompletoDetalle.", ";
                    }
                    $pedidoCompletoDetalle = $pedidoCompletoDetalle.$registroConsultaAgrupate["NOMBREPROMOCION"]."(".$registroConsultaAgrupate["CANTIDAD"].")";
                    $comaAgrupate = true;
                }
            }
        }
        if (($_GET["accion"]) == 'cambiarTipoEntrega') {
            $pedidoFinalId = $_GET["id"];
            $tipoEntregaId = $_GET["tipoEntregaId"];
            $actualizarTipoEntrega = "UPDATE PEDIDOFINAL SET RETIRO = ".$tipoEntregaId." WHERE ID = ".$pedidoFinalId;
            $conexion->query($actualizarTipoEntrega);
            Header("Location: inicio.php");
        }
        if(($_GET["accion"]) == 'cambiarL'){
            $pedidoFinalId = $_GET["id"];
            $idL = $_GET["idL"];
            if($idL == 'Seleccionar'){
                $actualizarL = "UPDATE PEDIDOFINAL SET DELIVERYID = NULL WHERE ID = ".$pedidoFinalId;    
            }else{
                $actualizarL = "UPDATE PEDIDOFINAL SET DELIVERYID = ".$idL." WHERE ID = ".$pedidoFinalId;
            }
            $conexion->query($actualizarL);
            Header("Location: inicio.php");
        }        
        if (($_GET["accion"]) == 'cambiarLocal') {
            $pedidoFinalId = $_GET["id"];
            $localId = $_GET["idLocal"];
            $actualizarLocal = "UPDATE PEDIDOFINAL SET CAMBIOLOCALID = ".$localId." WHERE ID = ".$pedidoFinalId;
            $conexion->query($actualizarLocal);
            Header("Location: inicio.php");
        }
        if (($_GET["accion"]) == 'cambiarE') {
            $pedidoFinalId = $_GET["id"];
            $idE = $_GET["idE"];
            $actualizarE = "UPDATE PEDIDOFINAL SET E = ".$idE." WHERE ID = ".$pedidoFinalId;
            $conexion->query($actualizarE);
            Header("Location: inicio.php");
        }
        if (($_GET["accion"]) == 'cambiarP') {
            $pedidoFinalId = $_GET["id"];
            $idP = $_GET["idP"];
            $actualizarP = "UPDATE PEDIDOFINAL SET P = ".$idP." WHERE ID = ".$pedidoFinalId;
            $conexion->query($actualizarP);
            Header("Location: inicio.php");
        }      
        if (($_GET["accion"]) == 'borrarDatos') {
            $borrarTablas="delete from btn_cantidad_piezas_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_delicia_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_bebida_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_adicional_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_pedidosya_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_promociones_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_variedad_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_agrupate_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_retiro_pedido";
            $conexion->query($borrarTablas);
            $borrarTablas="delete from tmp_fecha_entrega";
            $conexion->query($borrarTablas);
            $borrarTablas="delete from agrupate_codigo_tmp";
            $conexion->query($borrarTablas);
            Header("Location: inicio.php");
        }
        
        
    }else{
        sleep(1);
    }
    
?>  
    <style>
        .contenedor {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0px;
            left: 0px;
            background-color: rgba(0,0,0,0.5);
            visibility: hidden;
        }
        .modal {
            background-color: white;
            border-radius: 4px;
            padding: 25px;
            margin: 10% auto;
            width: 50%;
            height: auto;
        }
        .cerrar {
            width: auto;
            height: auto;
            cursor: pointer;
            color: rgba(255,0,0,0.5);
            padding: 2px;
            float: right;
            font-family: "Calibri";
            font-weight: bold;
        }
        .cerrar:hover{
            color: rgba(255,0,0,1);
        }
    </style>
    <script type="text/javascript">
        
        function llamarDetalle(id){
           window.location.href = "inicio.php?accion=cargarDetalle&id=" + id;
        }
        
        function updateTime() {
            var fecha = new Date();
            var horas = fecha.getHours();
            var minutos = fecha.getMinutes();

            if(horas < 10){
                horas = '0'+horas;
            }
            if(minutos < 10){
                minutos = '0'+minutos;
            }
            document.getElementById('contenedor').innerHTML = '' + horas + ':' + minutos +' hs';
            setTimeout('updateTime()', 1000);
        }
        
        function cambiarTipoEntrega(id,tipoEntregaId){
            window.location.href = "inicio.php?accion=cambiarTipoEntrega&id=" +id+ "&tipoEntregaId="+ tipoEntregaId; 
        }
        
        function cambiarL(id,idL){
            window.location.href = "inicio.php?accion=cambiarL&id=" +id+ "&idL="+ idL; 
        }
        
        function cambiarLocal(id,idLocal){
            window.location.href = "inicio.php?accion=cambiarLocal&id=" +id+ "&idLocal="+ idLocal; 
        }
        
        function cambiarE(id,idE){
            window.location.href = "inicio.php?accion=cambiarE&id=" +id+ "&idE="+ idE; 
        }
        function cambiarP(id,idP){
            document.getElementById('content').innerHTML;
            window.location.href = "inicio.php?accion=cambiarP&id=" +id+ "&idP="+ idP; 
        }
        
    </script>

    <b><div id="contenedor" align="center" style=font-size:40></div></b>
    <body onload="javascript:updateTime()" style="background-color:lightblue;">

        <table width="100%" cellpadding="20">
            <tr align="center" >
                <td>
                    <input type="image" src="../img/configurar.png" onClick=" window.location.href = 'configuracion.php'" >
                </td>   
                <td>
                    <input type="image" src="../img/sushi.png" onClick=" window.location.href = 'pedido.php'" >
                </td>                
            <tr>
        </table>
        <div style="margin-right: 17px">
            <table style="width:100%;background-color: rgb(255,255,255);" border="2" cellspacing="0" align="center" cellpadding="0">
                <tr align="center">
                    <td style="width:3%;">
                        <b>Nro</b>      
                    </td>
                    <td style="width:4%;">
                        <b>Hora</b>
                    </td>
                    <td style="width:16%;">
                        <b>Pedido</b>
                    </td>
                    <td style="width:4%;">
                        <b>Salida</b>
                    </td>
                    <td style="width:10%;">
                        <b>Nombre</b>
                    </td>
                    <td style="width:10%;">
                        <b>Tipo entrega</b>
                    </td>
                    <td style="width:5%;">
                        <b>Total</b>
                    </td>
                    <td style="width:5%;">
                        <b>Paga</b>
                    </td>
                    <td style="width:5%;">
                        <b>Vuelto</b>
                    </td>
                    <td style="width:8%;">
                        <b>L</b>
                    </td>
                    <td style="width:2%;">
                        <b>E</b>
                    </td>
                    <td style="width:2%;">
                        <b>P</b>
                    </td>
                    <td style="width:8%;">
                        <b>Teléfono</b>
                    </td>
                    <td style="width:10%;">
                        <b>Cambio de local</b>
                    </td>
                    <td style="width:60px;height:25px;">
                        <input type="button" style="width:60px;height:25px;background-color: rgb(255,255,255);">
                    </td>
                    <td style="width:60px;height:25px;">
                        <input type="button" style="width:60px;height:25px;background-color: rgb(255,255,255);">
                    </td>
               </tr>
            </table>   
        </div>
            <div style="overflow-y: scroll;height:200px;" id="content">
            <table style="width:100%;background-color: rgb(255,255,255);" border="2" cellspacing="0" align="center" cellpadding="0">
                <?php
                    $consultaPedidoFinal ="SELECT 
                                        ID,
                                        NUMEROPEDIDO,
                                        DATE_FORMAT(FECHAPEDIDO, \"%H:%i\" ) FECHAPEDIDO,
                                        DATE_FORMAT(FECHAENTREGA, \"%H:%i\" ) FECHAENTREGA,
                                        PRECIOTOTAL,
                                        NOMBRE,
                                        NOTAS,
                                        PAGA,
                                        RETIRO,
                                        DIRECCIONID,
                                        CLIENTEID,
                                        TELEFONOID,
                                        DELIVERYID,
                                        CAMBIOLOCALID,
                                        E,
                                        P
                                        FROM pedidofinal";

                    $resConsultaPedidoFinal = $conexion->query($consultaPedidoFinal);
                    while ($registroConsultaPedidoFinal = $resConsultaPedidoFinal->fetch_array(MYSQLI_BOTH)) {
                        $pedidoCompleto="";
                        $pedidoFinalId=$registroConsultaPedidoFinal["ID"];
                        $consultaPedidoCompletoCantPiezas = "SELECT CANTIDADPIEZASIDRELACION,CANTIDADPIEZASID,CANTIDADPIEZAS 
                                                             FROM PEDIDOFINAL_CANTPIEZAS WHERE PEDIDOFINALID = ".$pedidoFinalId;
                        $resConsultaPedidoCompletoCantPiezas = $conexion->query($consultaPedidoCompletoCantPiezas);
                        $cantComaPiezas = false;
                        while ($registroConsultaPedidoCompletoCantPiezas = $resConsultaPedidoCompletoCantPiezas->fetch_array(MYSQLI_BOTH)) {    
                            if($cantComaPiezas){
                                $pedidoCompleto = $pedidoCompleto.", ";
                            }
                            $cantidadPiezasRelacionId = $registroConsultaPedidoCompletoCantPiezas["CANTIDADPIEZASIDRELACION"];
                            $consultaPedidoCompletoVariedad = "SELECT VARIEDADID,NOMBREVARIEDAD,CANTIDAD 
                                                               FROM pedidofinal_variedad WHERE PEDIDOFINALID = ".$pedidoFinalId." 
                                                               AND CANTIDADPIEZASIDRELACION = ".$cantidadPiezasRelacionId;
                            $pedidoCompleto = $pedidoCompleto.$registroConsultaPedidoCompletoCantPiezas["CANTIDADPIEZAS"].
                                        " PIEZAS[";
                            $resConsultaPedidoCompletoVariedad = $conexion->query($consultaPedidoCompletoVariedad);
                            $cantComa = false;
                            while ($registroConsultaPedidoCompletoVariedad = $resConsultaPedidoCompletoVariedad->fetch_array(MYSQLI_BOTH)) {    
                                if($cantComa){
                                    $pedidoCompleto = $pedidoCompleto.", ";
                                }
                                $pedidoCompleto = $pedidoCompleto.$registroConsultaPedidoCompletoVariedad["NOMBREVARIEDAD"];
                                if($registroConsultaPedidoCompletoVariedad["CANTIDAD"]==0){
                                    $pedidoCompleto=$pedidoCompleto."(-)";        
                                }else{
                                    $pedidoCompleto=$pedidoCompleto."(".$registroConsultaPedidoCompletoVariedad["CANTIDAD"].")";        
                                }
                                $cantComa = true;
                            }
                            $pedidoCompleto = $pedidoCompleto."]";
                            $cantComaPiezas=true;
                        }               
                        if(strlen($pedidoCompleto)<60){
                            $consultaDelicia ="SELECT NOMBREDELICIA, CANTIDAD FROM pedidofinal_delicia WHERE PEDIDOFINALID = ".$pedidoFinalId;
                            $resConsultaDelicia = $conexion->query($consultaDelicia);
                            $comaDelicia = false;
                            while ($registroConsultaDelicia = $resConsultaDelicia->fetch_array(MYSQLI_BOTH)) {
                                if(strlen($pedidoCompleto)!=0 && !$comaDelicia){
                                    $pedidoCompleto = $pedidoCompleto.", ";
                                }
                                if($comaDelicia){
                                    $pedidoCompleto = $pedidoCompleto.", ";
                                }
                                $pedidoCompleto = $pedidoCompleto.$registroConsultaDelicia["NOMBREDELICIA"]."(".$registroConsultaDelicia["CANTIDAD"].")";
                                $comaDelicia = true;
                            }
                        }
                        
                        if(strlen($pedidoCompleto)<60){
                            $consultaBebida ="SELECT NOMBREBEBIDA,CANTIDAD FROM pedidofinal_bebida WHERE PEDIDOFINALID = ".$pedidoFinalId;
                            $resConsultaBebida = $conexion->query($consultaBebida);
                            $comaBebida = false;
                            while ($registroConsultaBebida = $resConsultaBebida->fetch_array(MYSQLI_BOTH)) {
                                if(strlen($pedidoCompleto)!=0 && !$comaBebida){
                                    $pedidoCompleto = $pedidoCompleto.", ";
                                }
                                if($comaBebida){
                                    $pedidoCompleto = $pedidoCompleto.", ";
                                }
                                $pedidoCompleto = $pedidoCompleto.$registroConsultaBebida["NOMBREBEBIDA"]."(".$registroConsultaBebida["CANTIDAD"].")";
                                $comaBebida = true;
                            }
                        }
                        
                        if(strlen($pedidoCompleto)<60){
                            $consultaPedidosYa ="SELECT NOMBREPROMOCION, CANTIDAD FROM pedidofinal_pedidosya  WHERE PEDIDOFINALID = ".$pedidoFinalId;
                            $resConsultaPedidosYa = $conexion->query($consultaPedidosYa);
                            $comaPedidosYa = false;
                            while ($registroConsultaPedidosYa = $resConsultaPedidosYa->fetch_array(MYSQLI_BOTH)) {
                                if(strlen($pedidoCompleto)!=0 && !$comaPedidosYa){
                                    $pedidoCompleto = $pedidoCompleto.", ";
                                }
                                if($comaPedidosYa){
                                    $pedidoCompleto = $pedidoCompleto.", ";
                                }
                                $pedidoCompleto = $pedidoCompleto.$registroConsultaPedidosYa["NOMBREPROMOCION"]."(".$registroConsultaPedidosYa["CANTIDAD"].")";
                                $comaPedidosYa = true;
                            }
                        }
                        
                        if(strlen($pedidoCompleto)<60){
                            $consultaAdicional ="SELECT NOMBREADICIONAL, CANTIDAD FROM pedidofinal_adicional WHERE PEDIDOFINALID = ".$pedidoFinalId;
                            $resConsultaAdicional = $conexion->query($consultaAdicional);
                            $comaAdicional = false;
                            while ($registroConsultaAdicional = $resConsultaAdicional->fetch_array(MYSQLI_BOTH)) {
                                if(strlen($pedidoCompleto)!=0 && !$comaAdicional){
                                    $pedidoCompleto = $pedidoCompleto.", ";
                                }
                                if($comaAdicional){
                                    $pedidoCompleto = $pedidoCompleto.", ";
                                }
                                $pedidoCompleto = $pedidoCompleto.$registroConsultaAdicional["NOMBREADICIONAL"]."(".$registroConsultaAdicional["CANTIDAD"].")";
                                $comaAdicional = true;
                            }
                        }
                        
                        if(strlen($pedidoCompleto)<60){
                            $consultaPromocion ="SELECT NOMBREPROMOCION, CANTIDAD FROM pedidofinal_promocion WHERE PEDIDOFINALID = ".$pedidoFinalId;
                            $resConsultaPromocion = $conexion->query($consultaPromocion);
                            $comaPromocion = false;
                            while ($registroConsultaPromocion = $resConsultaPromocion->fetch_array(MYSQLI_BOTH)) {
                                if(strlen($pedidoCompleto)!=0 && !$comaPromocion){
                                    $pedidoCompleto = $pedidoCompleto.", ";
                                }
                                if($comaPromocion){
                                    $pedidoCompleto = $pedidoCompleto.", ";
                                }
                                $pedidoCompleto = $pedidoCompleto.$registroConsultaPromocion["NOMBREPROMOCION"]."(".$registroConsultaPromocion["CANTIDAD"].")";
                                $comaPromocion = true;
                            }
                        }
                        if(strlen($pedidoCompleto)<60){
                            $consultaAgrupate ="SELECT NOMBREPROMOCION, CANTIDAD FROM pedidofinal_agrupate WHERE PEDIDOFINALID = ".$pedidoFinalId;
                            $resConsultaAgrupate = $conexion->query($consultaAgrupate);
                            $comaAgrupate = false;
                            while ($registroConsultaAgrupate = $resConsultaAgrupate->fetch_array(MYSQLI_BOTH)) {
                                if(strlen($pedidoCompleto)!=0 && !$comaAgrupate){
                                    $pedidoCompleto = $pedidoCompleto.", ";
                                }
                                if($comaAgrupate){
                                    $pedidoCompleto = $pedidoCompleto.", ";
                                }
                                $pedidoCompleto = $pedidoCompleto.$registroConsultaAgrupate["NOMBREPROMOCION"]."(".$registroConsultaAgrupate["CANTIDAD"].")";
                                $comaAgrupate = true;
                            }
                        }
                        
                        if(strlen($pedidoCompleto)>60){
                            $pedidoCompleto = substr($pedidoCompleto,0,60)."...";
                        }
                        
                        echo '<tr>';
                            echo '<td align="center" style="width:3%;">';
                                echo $registroConsultaPedidoFinal["NUMEROPEDIDO"];
                            echo '</td>';
                            echo '<td align="center" style="width:4%;">';
                                echo $registroConsultaPedidoFinal["FECHAPEDIDO"];
                            echo '</td>';
                            echo '<td align="center" style="width:16%;font-size:13;">';
                                echo $pedidoCompleto;
                            echo '</td>';
                            echo '<td align="center" style="width:4%;">';
                                echo $registroConsultaPedidoFinal["FECHAENTREGA"];
                            echo '</td>';
                            echo '<td align="center" style="width:10%;">';
                                echo $registroConsultaPedidoFinal["NOMBRE"];
                            echo '</td>';
                            echo '<td align="center" style="width:10%;">';
                                echo '<select style="width:120px;height:25px" onchange="cambiarTipoEntrega('.$registroConsultaPedidoFinal["ID"].',this.value)">';
                                    if($registroConsultaPedidoFinal["RETIRO"] == 1){
                                        echo '<option selected value="1">';
                                    }else{
                                        echo '<option value="1">';
                                    }
                                    echo 'Retira en local</option>';
                                    if($registroConsultaPedidoFinal["RETIRO"] == 2){
                                        echo '<option selected value="2">';
                                    }else{
                                        echo '<option value="2">';
                                    }
                                    echo 'Delivery</option>';
                                    if($registroConsultaPedidoFinal["RETIRO"] == 3){
                                        echo '<option selected value="3">';
                                    }else{
                                        echo '<option value="3">';
                                    }
                                    echo 'Espera</option>';
                                echo '</select>';
                            echo '</td>';
                            echo '<td align="center" style="width:5%;">';
                                echo '$'.$registroConsultaPedidoFinal["PRECIOTOTAL"];
                            echo '</td>';
                            echo '<td align="center" style="width:5%;">';
                                echo '$'.$registroConsultaPedidoFinal["PAGA"];
                            echo '</td>';
                            echo '<td align="center" style="width:5%;">';
                                echo '$'.($registroConsultaPedidoFinal["PAGA"]-$registroConsultaPedidoFinal["PRECIOTOTAL"]);
                            echo '</td>';
                            echo '<td align="center" style="width:8%;">';
                                echo '<select style="width:100px;height:25px" onchange="cambiarL('.$registroConsultaPedidoFinal["ID"].',this.value)">';
                                $consultaEmpleados = "SELECT ID, ALIAS FROM EMPLEADO WHERE DELIVERY = 1 AND PRESENTE = 1";
                                $resConsultaEmpleados = $conexion->query($consultaEmpleados);
                                if(is_null($registroConsultaPedidoFinal["DELIVERYID"])){
                                    echo '<option selected>Seleccionar</option>';
                                }else{
                                    echo '<option >Seleccionar</option>';
                                }
                                if((!is_null($registroConsultaPedidoFinal["DELIVERYID"])) && ($registroConsultaPedidoFinal["DELIVERYID"] == 0)){
                                    echo '<option value="0" selected>Listo</option>';
                                }else{
                                    echo '<option value="0">Listo</option>';
                                }
                                while ($registroConsultaEmpleados = $resConsultaEmpleados->fetch_array(MYSQLI_BOTH)) {
                                    if($registroConsultaEmpleados["ID"] == $registroConsultaPedidoFinal["DELIVERYID"]){
                                        echo '<option value="'.$registroConsultaEmpleados["ID"].'" selected>'.$registroConsultaEmpleados["ALIAS"].'</option>';
                                    }else{
                                        echo '<option value="'.$registroConsultaEmpleados["ID"].'">'.$registroConsultaEmpleados["ALIAS"].'</option>';
                                    }
                                }
                                echo '</select>';
                            echo '</td>'; 
                            echo '<td align="center" style="width:2%;">';
                                if($registroConsultaPedidoFinal["E"] == 0){
                                    echo '<input type="checkbox" style="width:15px;height:15px" onClick="cambiarE('.$registroConsultaPedidoFinal["ID"].',1)">';
                                }else{
                                    echo '<input type="checkbox" style="width:15px;height:15px" checked onClick="cambiarE('.$registroConsultaPedidoFinal["ID"].',0)">';
                                }
                            echo '</td>'; 
                            echo '<td align="center" style="width:2%;">';
                                if($registroConsultaPedidoFinal["P"] == 0){
                                    echo '<input type="checkbox" style="width:15px;height:15px" onClick="cambiarP('.$registroConsultaPedidoFinal["ID"].',1)">';
                                }else{
                                    echo '<input type="checkbox" style="width:15px;height:15px" checked onClick="cambiarP('.$registroConsultaPedidoFinal["ID"].',0)">';
                                }
                            echo '</td>'; 
                            echo '<td align="center" style="width:8%;">';           
                                if(!is_null($registroConsultaPedidoFinal["TELEFONOID"])){
                                    $consultaTelefono ="SELECT NUMERO FROM TELEFONO WHERE ID =".$registroConsultaPedidoFinal["TELEFONOID"];
                                    $resConsultaTelefono = $conexion->query($consultaTelefono);
                                    while ($registroConsultaTelefono = $resConsultaTelefono->fetch_array(MYSQLI_BOTH)) {
                                        echo $registroConsultaTelefono["NUMERO"];
                                    }             
                                }
                            echo '</td>';            
                            echo '<td align="center" style="width:10%;">';
                                echo '<select style="width:100px;height:25px" onchange="cambiarLocal('.$registroConsultaPedidoFinal["ID"].',this.value)">';
                                $consultaLocal = "SELECT ID, NOMBRE FROM CAMBIOLOCAL";
                                $resConsultaLocal = $conexion->query($consultaLocal);
                                if($registroConsultaPedidoFinal["CAMBIOLOCALID"] == 0){
                                    echo '<option value="0" selected>No</option>';
                                }else{
                                    echo '<option value="0">No</option>';
                                }
                                while ($registroConsultaLocal = $resConsultaLocal->fetch_array(MYSQLI_BOTH)) {
                                    if($registroConsultaLocal["ID"] == $registroConsultaPedidoFinal["CAMBIOLOCALID"]){
                                        echo '<option value="'.$registroConsultaLocal["ID"].'" selected>'.$registroConsultaLocal["NOMBRE"].'</option>';
                                    }else{
                                        echo '<option value="'.$registroConsultaLocal["ID"].'">'.$registroConsultaLocal["NOMBRE"].'</option>';
                                    }
                                }
                                echo '</select>';
                            echo '</td>';  
                            echo '<td align="center" style="width:60px;">';
                                echo '<input type="button" value="Detalle" style="width:60px;height:25px;" onClick="llamarDetalle('.$registroConsultaPedidoFinal["ID"].')">';
                            echo '</td>'; 
                            echo '<td align="center" style="width:60px;">';
                                echo '<input type="button" value="Anular" style="width:60px;height:25px;">';
                            echo '</td>'; 
                        echo '</tr>';
                    }
                ?>
            </table>
        </div>    
    </body>    
<script type="text/javascript">

    function detalle(){
        var mimodal = crearModalTexto();
        mostrarModal(mimodal);
    }

    function crearModalTexto() {

        var f = document.createElement("div");
        var m = document.createElement("div");

        f.appendChild(m);
        var cerrar = document.createElement("div");
        var x = document.createTextNode("X");
        cerrar.appendChild(x);
        cerrar.className = "cerrar";
        cerrar.addEventListener("click", function () {
            f.style.visibility = "hidden";
            window.location.href = "inicio.php"; 
        });
        m.appendChild(cerrar);

        //Tabla
        var tabla   = document.createElement("table");
        var tblBody = document.createElement("tbody");
        tabla.style.cellspacing = "0";
        tabla.style.cellpadding = "0";        
        tabla.style.border = "1px solid";
        
        //Hilera cabecera
        var hileraCabecera = document.createElement("tr");
        var celdaCabecera = document.createElement("td");
        celdaCabecera.setAttribute("height", "30px");
        celdaCabecera.style.border = "1px solid"
        celdaCabecera.style.textAlign = "center";
        celdaCabecera.setAttribute("colspan","6");
        var textoCabecera = '<?php echo $detalleNumeroPedido; ?>';
        var tituloCabecera = document.createElement("b");
        tituloCabecera.innerHTML = "Pedido N° "+textoCabecera;  
        celdaCabecera.appendChild(tituloCabecera);
        hileraCabecera.appendChild(celdaCabecera);
        tblBody.appendChild(hileraCabecera);
      
       
        //HIlera 1
        var hilera1 = document.createElement("tr");
        var celda1Hilera1 = document.createElement("td");
        celda1Hilera1.style.border = "1px solid"
        celda1Hilera1.style.textAlign = "left";
        celda1Hilera1.setAttribute("colspan","6");
        var texto = '<?php echo $pedidoCompletoDetalle;?>';
        var textoCelda1Hilera1 = document.createTextNode(texto);
        var tituloCelda1Hilera1 = document.createElement("b");
        tituloCelda1Hilera1.innerHTML = "Pedido: ";  
        celda1Hilera1.appendChild(tituloCelda1Hilera1);
        celda1Hilera1.appendChild(textoCelda1Hilera1);
        hilera1.appendChild(celda1Hilera1);
        tblBody.appendChild(hilera1);
              
        //Hilera 2
        var hilera2 = document.createElement("tr");
        var celda1Hilera2 = document.createElement("td");
        celda1Hilera2.style.border = "1px solid"
        celda1Hilera2.style.textAlign = "left";
        celda1Hilera2.setAttribute("width","50%");
        celda1Hilera2.setAttribute("colspan","3");
        var texto ='<?php echo $detalleFechaPedido;?>';
        var textoCelda1Hilera2 = document.createTextNode(texto);
        var tituloCelda1Hilera2 = document.createElement("b");
        tituloCelda1Hilera2.innerHTML = "Hora: ";  
        celda1Hilera2.appendChild(tituloCelda1Hilera2);
        celda1Hilera2.appendChild(textoCelda1Hilera2);
        hilera2.appendChild(celda1Hilera2);
          
        var celda2Hilera2 = document.createElement("td");
        celda2Hilera2.style.border = "1px solid"
        celda2Hilera2.style.textAlign = "left";
        celda2Hilera2.setAttribute("width","50%");
        celda2Hilera2.setAttribute("colspan","3");
        var texto ='<?php echo $detalleFechaEntrega;?>';
        var textoCelda2Hilera2 = document.createTextNode(texto);
        var tituloCelda2Hilera2 = document.createElement("b");
        tituloCelda2Hilera2.innerHTML = "Salida: ";  
        celda2Hilera2.appendChild(tituloCelda2Hilera2);
        celda2Hilera2.appendChild(textoCelda2Hilera2);
        hilera2.appendChild(celda2Hilera2);
        tblBody.appendChild(hilera2);
    
        //Hilera 3
        var hilera3 = document.createElement("tr");
        var celda1Hilera3 = document.createElement("td");
        celda1Hilera3.style.border = "1px solid"
        celda1Hilera3.style.textAlign = "left";
        celda1Hilera3.setAttribute("width","50%");
        celda1Hilera3.setAttribute("colspan","3");
        var texto ='<?php echo $detalleNombre;?>';
        var textoCelda1Hilera3 = document.createTextNode(texto);
        var tituloCelda1Hilera3 = document.createElement("b");
        tituloCelda1Hilera3.innerHTML = "Nombre: ";  
        celda1Hilera3.appendChild(tituloCelda1Hilera3);
        celda1Hilera3.appendChild(textoCelda1Hilera3);
        hilera3.appendChild(celda1Hilera3);
          
        var celda2Hilera3 = document.createElement("td");
        celda2Hilera3.style.border = "1px solid"
        celda2Hilera3.style.textAlign = "left";
        celda2Hilera3.setAttribute("width","50%");
        celda2Hilera3.setAttribute("colspan","3");
        var texto ='<?php echo $detalleTelefonoNumero;?>';
        var textoCelda2Hilera3 = document.createTextNode(texto);
        var tituloCelda2Hilera3 = document.createElement("b");
        tituloCelda2Hilera3.innerHTML = "Teléfono: ";  
        celda2Hilera3.appendChild(tituloCelda2Hilera3);
        celda2Hilera3.appendChild(textoCelda2Hilera3);
        hilera3.appendChild(celda2Hilera3);
        tblBody.appendChild(hilera3);
       
        //Hilera 4
        var hilera4 = document.createElement("tr");
        var celda1Hilera4 = document.createElement("td");
        celda1Hilera4.style.border = "1px solid"
        celda1Hilera4.style.textAlign = "left";
        celda1Hilera4.setAttribute("width","33%");
        celda1Hilera4.setAttribute("colspan","2");
        var texto ='<?php echo '$'.$detalleTotal;?>';
        var textoCelda1Hilera4 = document.createTextNode(texto);
        var tituloCelda1Hilera4 = document.createElement("b");
        tituloCelda1Hilera4.innerHTML = "Total: ";  
        celda1Hilera4.appendChild(tituloCelda1Hilera4);
        celda1Hilera4.appendChild(textoCelda1Hilera4);
        hilera4.appendChild(celda1Hilera4);
          
        var celda2Hilera4 = document.createElement("td");
        celda2Hilera4.style.border = "1px solid"
        celda2Hilera4.style.textAlign = "left";
        celda2Hilera4.setAttribute("width","33%");
        celda2Hilera4.setAttribute("colspan","2");
        var texto ='<?php echo '$'.$detallePaga;?>';
        var textoCelda2Hilera4 = document.createTextNode(texto);
        var tituloCelda2Hilera4 = document.createElement("b");
        tituloCelda2Hilera4.innerHTML = "Paga: ";  
        celda2Hilera4.appendChild(tituloCelda2Hilera4);
        celda2Hilera4.appendChild(textoCelda2Hilera4);
        hilera4.appendChild(celda2Hilera4);
  
        var celda3Hilera4 = document.createElement("td");
        celda3Hilera4.style.border = "1px solid"
        celda3Hilera4.style.textAlign = "left";
        celda3Hilera4.setAttribute("width","33%");
        celda3Hilera4.setAttribute("colspan","2");
        var texto ='<?php echo '$'.$detalleVuelto;?>';
        var textoCelda3Hilera4 = document.createTextNode(texto);
        var tituloCelda3Hilera4 = document.createElement("b");
        tituloCelda3Hilera4.innerHTML = "Vuelto: ";  
        celda3Hilera4.appendChild(tituloCelda3Hilera4);
        celda3Hilera4.appendChild(textoCelda3Hilera4);    
        hilera4.appendChild(celda3Hilera4);
        tblBody.appendChild(hilera4);
                
        //Hilera 5
        var hilera5 = document.createElement("tr");
        var celda1Hilera5 = document.createElement("td");
        celda1Hilera5.style.border = "1px solid"
        celda1Hilera5.style.textAlign = "left";
        celda1Hilera5.setAttribute("width","33%");
        celda1Hilera5.setAttribute("colspan","2");
        var texto ='<?php echo $detalleL;?>';
        var textoCelda1Hilera5 = document.createTextNode(texto);
        var tituloCelda1Hilera5 = document.createElement("b");
        tituloCelda1Hilera5.innerHTML = "L: ";  
        celda1Hilera5.appendChild(tituloCelda1Hilera5);
        celda1Hilera5.appendChild(textoCelda1Hilera5);
        hilera5.appendChild(celda1Hilera5);
          
        var celda2Hilera5 = document.createElement("td");
        celda2Hilera5.style.border = "1px solid"
        celda2Hilera5.style.textAlign = "left";
        celda2Hilera5.setAttribute("width","33%");
        celda2Hilera5.setAttribute("colspan","2");
        var texto ='<?php echo $detalleE;?>';
        var textoCelda2Hilera5 = document.createTextNode(texto);
        var tituloCelda2Hilera5 = document.createElement("b");
        tituloCelda2Hilera5.innerHTML = "E: ";  
        celda2Hilera5.appendChild(tituloCelda2Hilera5);
        celda2Hilera5.appendChild(textoCelda2Hilera5);
        hilera5.appendChild(celda2Hilera5);
  
        var celda3Hilera5 = document.createElement("td");
        celda3Hilera5.style.border = "1px solid"
        celda3Hilera5.style.textAlign = "left";
        celda3Hilera5.setAttribute("width","33%");
        celda3Hilera5.setAttribute("colspan","2");
        var texto ='<?php echo $detalleP;?>';
        var textoCelda3Hilera5 = document.createTextNode(texto);
        var tituloCelda3Hilera5 = document.createElement("b");
        tituloCelda3Hilera5.innerHTML = "P: ";  
        celda3Hilera5.appendChild(tituloCelda3Hilera5);
        celda3Hilera5.appendChild(textoCelda3Hilera5);    
        hilera5.appendChild(celda3Hilera5);
        tblBody.appendChild(hilera5);
    
        //Hilera 6
        var hilera6 = document.createElement("tr");
        var celda1Hilera6= document.createElement("td");
        celda1Hilera6.style.border = "1px solid"
        celda1Hilera6.style.textAlign = "left";
        celda1Hilera6.setAttribute("width","50%");
        celda1Hilera6.setAttribute("colspan","3");
        var texto ='<?php echo $detalleRetiro;?>';
        var textoCelda1Hilera6 = document.createTextNode(texto);
        var tituloCelda1Hilera6 = document.createElement("b");
        tituloCelda1Hilera6.innerHTML = "Tipo entrega: ";  
        celda1Hilera6.appendChild(tituloCelda1Hilera6);
        celda1Hilera6.appendChild(textoCelda1Hilera6);
        hilera6.appendChild(celda1Hilera6);
          
        var celda2Hilera6 = document.createElement("td");
        celda2Hilera6.style.border = "1px solid"
        celda2Hilera6.style.textAlign = "left";
        celda2Hilera6.setAttribute("width","50%");
        celda2Hilera6.setAttribute("colspan","3");
        var texto ='<?php echo $detalleCambioLocal;?>';
        var textoCelda2Hilera6 = document.createTextNode(texto);
        var tituloCelda2Hilera6 = document.createElement("b");
        tituloCelda2Hilera6.innerHTML = "Cambio de local: ";  
        celda2Hilera6.appendChild(tituloCelda2Hilera6);
        celda2Hilera6.appendChild(textoCelda2Hilera6);
        hilera6.appendChild(celda2Hilera6);
        tblBody.appendChild(hilera6);
        
        //HIlera 11
        var hilera11 = document.createElement("tr");
        var celda1Hilera11 = document.createElement("td");
        celda1Hilera11.style.border = "1px solid"
        celda1Hilera11.style.textAlign = "left";
        celda1Hilera11.setAttribute("colspan","6");
        var textoCelda1Hilera11 = document.createTextNode(texto);
        var tituloCelda1Hilera11 = document.createElement("b");
        var texto ='<?php echo $detalleNota;?>';
        var textoCelda2Hilera11 = document.createTextNode(texto);
        tituloCelda1Hilera11.innerHTML = "Nota: ";  
        celda1Hilera11.appendChild(tituloCelda1Hilera11);
        celda1Hilera11.appendChild(textoCelda2Hilera11);
        hilera11.appendChild(celda1Hilera11);
        tblBody.appendChild(hilera11);
        
        //HIlera cabecera Direccion
        var hileraDireccion = document.createElement("tr");
        var celda1HileraDireccion = document.createElement("td");
        celda1HileraDireccion.setAttribute("height", "30px");
        celda1HileraDireccion.style.border = "1px solid"
        celda1HileraDireccion.style.textAlign = "center";
        celda1HileraDireccion.setAttribute("colspan","6");
        var tituloCelda1HileraDireccion = document.createElement("b");
        tituloCelda1HileraDireccion.innerHTML = "DIRECCIÓN";  
        celda1HileraDireccion.appendChild(tituloCelda1HileraDireccion);
        hileraDireccion.appendChild(celda1HileraDireccion);
        tblBody.appendChild(hileraDireccion);
        
        //Hilera 7
        var hilera7 = document.createElement("tr");
        var celda1Hilera7= document.createElement("td");
        celda1Hilera7.style.border = "1px solid"
        celda1Hilera7.style.textAlign = "left";
        celda1Hilera7.setAttribute("width","50%");
        celda1Hilera7.setAttribute("colspan","3");
        var texto ='<?php echo $detalleDireccionCalle;?>';
        var textoCelda1Hilera7 = document.createTextNode(texto);
        var tituloCelda1Hilera7 = document.createElement("b");
        tituloCelda1Hilera7.innerHTML = "Calle: ";  
        celda1Hilera7.appendChild(tituloCelda1Hilera7);
        celda1Hilera7.appendChild(textoCelda1Hilera7);
        hilera7.appendChild(celda1Hilera7);
          
        var celda2Hilera7 = document.createElement("td");
        celda2Hilera7.style.border = "1px solid"
        celda2Hilera7.style.textAlign = "left";
        celda2Hilera7.setAttribute("width","50%");
        celda2Hilera7.setAttribute("colspan","3");
        var texto ='<?php echo $detalleDireccionNumeracion;?>';
        var textoCelda2Hilera7 = document.createTextNode(texto);
        var tituloCelda2Hilera7 = document.createElement("b");
        tituloCelda2Hilera7.innerHTML = "Numeración: ";  
        celda2Hilera7.appendChild(tituloCelda2Hilera7);
        celda2Hilera7.appendChild(textoCelda2Hilera7);
        hilera7.appendChild(celda2Hilera7);
        tblBody.appendChild(hilera7);
        
        //Hilera 8
        var hilera8 = document.createElement("tr");
        var celda1Hilera8= document.createElement("td");
        celda1Hilera8.style.border = "1px solid"
        celda1Hilera8.style.textAlign = "left";
        celda1Hilera8.setAttribute("width","50%");
        celda1Hilera8.setAttribute("colspan","3");
        var texto ='<?php echo $detalleDireccionPiso;?>';
        var textoCelda1Hilera8 = document.createTextNode(texto);
        var tituloCelda1Hilera8 = document.createElement("b");  
        tituloCelda1Hilera8.innerHTML = "Piso: ";  
        celda1Hilera8.appendChild(tituloCelda1Hilera8);
        celda1Hilera8.appendChild(textoCelda1Hilera8);
        hilera8.appendChild(celda1Hilera8);
          
        var celda2Hilera8 = document.createElement("td");
        celda2Hilera8.style.border = "1px solid"
        celda2Hilera8.style.textAlign = "left";
        celda2Hilera8.setAttribute("width","50%");
        celda2Hilera8.setAttribute("colspan","3");
        var texto ='<?php echo $detalleDireccionDepartamento;?>';
        var textoCelda2Hilera8 = document.createTextNode(texto);
        var tituloCelda2Hilera8 = document.createElement("b");
        tituloCelda2Hilera8.innerHTML = "Departamento: ";  
        celda2Hilera8.appendChild(tituloCelda2Hilera8);
        celda2Hilera8.appendChild(textoCelda2Hilera8);
        hilera8.appendChild(celda2Hilera8);
        tblBody.appendChild(hilera8);
        
        //Hilera 9
        var hilera9 = document.createElement("tr");
        var celda1Hilera9= document.createElement("td");
        celda1Hilera9.style.border = "1px solid"
        celda1Hilera9.style.textAlign = "left";
        celda1Hilera9.setAttribute("width","50%");
        celda1Hilera9.setAttribute("colspan","3");
        var texto ='<?php echo $detalleDireccionZona;?>';
        var textoCelda1Hilera9 = document.createTextNode(texto);
        var tituloCelda1Hilera9 = document.createElement("b");
        tituloCelda1Hilera9.innerHTML = "Zona: ";  
        celda1Hilera9.appendChild(tituloCelda1Hilera9);
        celda1Hilera9.appendChild(textoCelda1Hilera9);
        hilera9.appendChild(celda1Hilera9);
          
        var celda2Hilera9 = document.createElement("td");
        celda2Hilera9.style.border = "1px solid"
        celda2Hilera9.style.textAlign = "left";
        celda2Hilera9.setAttribute("width","50%");
        celda2Hilera9.setAttribute("colspan","3");
        var texto ='<?php echo '$'.$detalleDireccionDelivery;?>';
        var textoCelda2Hilera9 = document.createTextNode(texto);
        var tituloCelda2Hilera9 = document.createElement("b");
        tituloCelda2Hilera9.innerHTML = "Precio Delivery: ";  
        celda2Hilera9.appendChild(tituloCelda2Hilera9);
        celda2Hilera9.appendChild(textoCelda2Hilera9);
        hilera9.appendChild(celda2Hilera9);
        tblBody.appendChild(hilera9);
        
        //HIlera 10
        var hilera10 = document.createElement("tr");
        var celda1Hilera10 = document.createElement("td");
        celda1Hilera10.style.border = "1px solid"
        celda1Hilera10.style.textAlign = "left";
        celda1Hilera10.setAttribute("colspan","6");
        var textoCelda1Hilera10 = document.createTextNode(texto);
        var tituloCelda1Hilera10 = document.createElement("b");
        var texto ='<?php echo $detallePDF;?>';
        var textoCelda2Hilera10 = document.createTextNode(texto);
        tituloCelda1Hilera10.innerHTML = "Dirección PDF: ";  
        celda1Hilera10.appendChild(tituloCelda1Hilera10);
        celda1Hilera10.appendChild(textoCelda2Hilera10);
        hilera10.appendChild(celda1Hilera10);
        tblBody.appendChild(hilera10);
    
        tabla.appendChild(tblBody);
        tabla.setAttribute("width", "100%");
        tabla.setAttribute("cellspacing", "0");
        m.appendChild(tabla);
        f.className = "contenedor";
        m.className = "modal";

        document.body.appendChild(f);
        return f;
    }
    
    function mostrarModal(obj) {
        obj.style.visibility = "visible";
    }
</script>
</html>
    <?php 
    
    if (isset($_GET["accion"])) {
        if (($_GET["accion"]) == 'cargarDetalle') {

            echo "<script type=\"text/javascript\">";
            echo "detalle();";
            echo "</script>";   
        }
    }
    
?>

