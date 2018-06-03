<?php
include ("../conexion/conexion.php");
$conexion = crearConexion();

$consultaPedidoPromocion="SELECT agrupate.id AGRUPATEID,agrupate.NUMEROPROMOCION,pedidopromocion.ID,pedidopromocion.TOTAL FROM agrupate
                           inner join pedidopromocion on agrupate.PEDIDOPROMOCIONID = pedidopromocion.id
                           where agrupate.MOSTRAR = true
                           order by NUMEROPROMOCION asc";
$resConsultaPedidoPromocion = $conexion->query($consultaPedidoPromocion); 

////////////////////////////////////////////////////////////////////////////////////////////

$listPedidos = "SELECT btn_agrupate_tmp.BOTONID,agrupate.NUMEROPROMOCION,btn_agrupate_tmp.PRECIO,PEDIDOPROMOCION.TOTAL PRECIOFIJO, btn_agrupate_tmp.CANTIDAD FROM btn_agrupate_tmp
                inner join agrupate on agrupate.PEDIDOPROMOCIONID = btn_agrupate_tmp.BOTONID
                inner join PEDIDOPROMOCION on agrupate.PEDIDOPROMOCIONID = PEDIDOPROMOCION.id";
$resListPedidos = $conexion->query($listPedidos);

//////////////////////////////////////////////////////////////////////////////////////////////
$consultaPedidoId = "SELECT PEDIDOFINALID FROM pedidofinal_usuario_1";
$resConsultaPedidoId = $conexion->query($consultaPedidoId);

while ($registroConsultaPedidoId = $resConsultaPedidoId->fetch_array(MYSQLI_BOTH)) {
    $pedidoFinalId = $registroConsultaPedidoId["PEDIDOFINALID"];
}
///////////////////////////////////////////////////////////////////////////////////////////////////
$listaCodigos = "SELECT CODIGO FROM AGRUPATE_CODIGO_TMP WHERE PEDIDOFINALID = $pedidoFinalId"; 
$resListaCodigos = $conexion->query($listaCodigos);

$codigo1Formulario ="";
$codigo2Formulario ="";
$segundoCodigoCondicion = false;
while ($registroListaCodigos = $resListaCodigos->fetch_array(MYSQLI_BOTH)) {
    if($codigo1Formulario == ""){
        $codigo1Formulario = $registroListaCodigos["CODIGO"];
    }
    if($segundoCodigoCondicion){
        $codigo2Formulario = $registroListaCodigos["CODIGO"];
    } 
    $segundoCodigoCondicion = true;
}
/////////////////////////////////////////////////////////////////////////////////////////////////// 
 
if (isset($_GET["accion"])) {
    if (($_GET["accion"]) == 'agregarPedido') {
        if (isset($_GET["id"])) {
            $idBoton=$_GET["id"];
            $precio=$_GET["precio"];
            $codigo1="";
            $codigo2="";
            if (isset($_GET["codigo1"])) {
                $codigo1=$_GET["codigo1"];
            }
            if (isset($_GET["codigo2"])) {
                $codigo2=$_GET["codigo2"];
            }

            $consultaPedidoFinal ="SELECT PEDIDOFINALID FROM pedidofinal_usuario_1";
            $resConsultaPedidoFinal=$conexion->query($consultaPedidoFinal);
            while ($registroConsultaPedidoFinal = $resConsultaPedidoFinal->fetch_array(MYSQLI_BOTH)) {
                $pedidoFinalId = $registroConsultaPedidoFinal["PEDIDOFINALID"];
            }            
            
            $eliminarCodTmp = "DELETE FROM AGRUPATE_CODIGO_TMP WHERE PEDIDOFINALID = $pedidoFinalId";
            $conexion->query($eliminarCodTmp);
            
            $borrarDatosTablaTemporal = "delete from BTN_AGRUPATE_TMP where BOTONID = $idBoton";
            $conexion->query($borrarDatosTablaTemporal);
            $cantPedidos = 0;
            
            if($codigo1 != '' || $codigo2 != ''){
                if($codigo1 != ''){
                    $guardarCodigo = "INSERT INTO AGRUPATE_CODIGO_TMP (PEDIDOFINALID,CODIGO) VALUES ($pedidoFinalId,$codigo1)";
                    $conexion->query($guardarCodigo);  
                    $cantPedidos = $cantPedidos+1;
                }
                if($codigo2 != ''){
                    $guardarCodigo = "INSERT INTO AGRUPATE_CODIGO_TMP (PEDIDOFINALID,CODIGO) VALUES ($pedidoFinalId,$codigo2)";
                    $conexion->query($guardarCodigo); 
                    $cantPedidos = $cantPedidos+1;
                }
                $guardarDatosTablaTemporal = "insert into BTN_AGRUPATE_TMP (BOTONID,CANTIDAD,PRECIO) values ($idBoton,$cantPedidos,$precio)";
                $conexion->query($guardarDatosTablaTemporal);
            }
            
            Header("Location: pedido.php");
        }
    }else if (($_GET["accion"]) == 'guardarCantidadPrecio') {
        if (isset($_GET["id"])) {
            $id=$_GET["id"];
            $precio=$_GET["precio"];
            $cantidad=$_GET["cantidad"];
        }
        $actualizarCantidadPrecio = "update BTN_AGRUPATE_TMP set CANTIDAD=$cantidad, precio=$precio where botonid=$id";
        $conexion->query($actualizarCantidadPrecio);
               
        Header("Location: agrupate.php");
    }
}   


?>
<script type="text/javascript">
    function agregarAgrupate(idAgrupate,precio){
        var urlCod1="";
        var urlCod2="";

        if(document.getElementById('codigo1').value != ''){
            urlCod1 = "&codigo1="+document.getElementById('codigo1').value;
        }
        if(document.getElementById('codigo2').value != '' && document.getElementById('divCodigo2').style.visibility == 'visible'){
            urlCod2 = "&codigo2="+document.getElementById('codigo2').value;
        }
        window.location.href = "agrupate.php?accion=agregarPedido&id=" + idAgrupate+"&precio="+precio+urlCod1+urlCod2;
    }

    function guardarCantidadPrecioPedido(valor){
        var idCantidadPrecio = valor.split("_");
        var id=idCantidadPrecio[0];
        var cantidad =idCantidadPrecio[1];
        var precio=idCantidadPrecio[2];   
        window.location.href = "agrupate.php?accion=guardarCantidadPrecio&id=" + id+"&cantidad="+cantidad+"&precio="+precio;
    }
    
    function ocultarMostrarCodigo(value){
        if(value == 1){
            document.getElementById('divCodigo2').style.visibility = "hidden";
        }else{
            document.getElementById('divCodigo2').style.visibility = "visible";
        }
    }
    
    function borrarCodigos(){
        document.getElementById('codigo1').value = '';
        document.getElementById('codigo2').value = '';
    }
 

</script>

<html>
    <div align="center"><h1>Agrupate/ClickOn</h1></div>
    <body style="background-color:lightblue;">
    <table width="100%" >
        <tr>
            <form method="POST" action="../controlador/guardar.php" >
            <input type="hidden" name="formulario" value="codigosAgrupate">
                <table align="center" width="80%">
                    <tr align="center">
                        <td align="left">
                            <?php
                            if($codigo2Formulario == ''){
                                echo 'Cantidad  de códigos  <select style="width:50px;height:18px" onchange="ocultarMostrarCodigo(this.value)">
                                <option value="1">1</option>
                                <option value="2">2</option>';
                            }else{
                                echo 'Cantidad  de códigos  <select style="width:50px;height:18px" onchange="ocultarMostrarCodigo(this.value)">
                                <option value="2">2</option>
                                <option value="1">1</option>';
                            }
                            ?>
                            
                        </td>
                        <td align="center">
                            Código 1  <input type="text" name="codigo1" id="codigo1" value="<?php echo $codigo1Formulario?>"/>
                        </td>       
                        <td align="rigth">
                            <?php
                            if($codigo2Formulario == ''){
                                echo '<div id="divCodigo2" style="visibility:hidden;">';
                            }else{
                                echo '<div id="divCodigo2" style="visibility:visible;">';
                            }
                            ?>
                                Código 2  <input type="text" name="codigo2" id="codigo2" value="<?php echo $codigo2Formulario?>">
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
            <table height="30px">
                <tr>
                    <td></td>
                </tr>
            </table>
        </tr>
        <tr>
            <td width="50%">
                 <table align="center" border="2" cellspacing="0" width="80%" style="background-color:rgb(255,255,255);font-size:13px">
            </td>
        </tr>    
        <tr align="center">
             <td>
                 <b>Tabla</b>
             </td>
             <td>
                 <b>Bebidas</b>
             </td>
             <td>
                 <b>Delicias</b>
             </td>
             <td>
                 <b>Adicionales</b>
             </td>
             <td>
                 <b>Precio</b>
             </td>
         </tr>
         <?php
             while ($registroConsultaPedidoPromocion = $resConsultaPedidoPromocion->fetch_array(MYSQLI_BOTH)) {
                 $id=$registroConsultaPedidoPromocion["ID"];
                 $total=$registroConsultaPedidoPromocion["TOTAL"];
                 echo '<tr align="center" >
                         <td valign="middle" >';
                $consultaPedidoPromocionPiezas="SELECT tabla.ID,tabla.CANTIDADPIEZAS FROM pedidopromocionpiezas
                                                INNER JOIN tabla ON tabla.ID = pedidopromocionpiezas.BTNID
                                                WHERE pedidopromocionpiezas.PEDIDOPROMOCIONID=$id";
                 $resConsultaPedidoPromocionPiezas = $conexion->query($consultaPedidoPromocionPiezas);
                 $idTabla=0;
                 while ($registroConsultaPedidoPromocionPiezas = $resConsultaPedidoPromocionPiezas->fetch_array(MYSQLI_BOTH)) {
                     $idTabla=$registroConsultaPedidoPromocionPiezas["ID"];
                     echo $registroConsultaPedidoPromocionPiezas["CANTIDADPIEZAS"].' PIEZAS';
                 }
                 $consultaPedidoPromocionVariedad="SELECT variedadtabla.NOMBRE,pedidopromocionvariedad.CANTIDAD FROM pedidopromocionvariedad
                                                   INNER JOIN variedadtabla on variedadtabla.id = pedidopromocionvariedad.BTNID
                                                   and pedidopromocionvariedad.PEDIDOPROMOCIONID = $id
                                                   and pedidopromocionvariedad.TABLAID = $idTabla";
                 $resConsultaPedidoPromocionVariedad = $conexion->query($consultaPedidoPromocionVariedad);
                 while ($registroConsultaPedidoPromocionVariedad = $resConsultaPedidoPromocionVariedad->fetch_array(MYSQLI_BOTH)) {
                     echo '<br>'.$registroConsultaPedidoPromocionVariedad["NOMBRE"];
                     if($registroConsultaPedidoPromocionVariedad["CANTIDAD"]!=0){
                         echo ' ('.$registroConsultaPedidoPromocionVariedad["CANTIDAD"].')';
                     }               
                 }
                 echo '</td>
                        <td valign="middle">';
                $consultaPedidoPromocionBebida="SELECT bebida.NOMBRE,pedidopromocionbebida.CANTIDAD FROM pedidopromocionbebida
                                                inner join bebida on bebida.id = pedidopromocionbebida.BTNID
                                                and pedidopromocionbebida.PEDIDOPROMOCIONID= $id";
                 $resConsultaPedidoPromocionBebida = $conexion->query($consultaPedidoPromocionBebida);
                 $i=0;
                 while ($registroConsultaPedidoPromocionBebida = $resConsultaPedidoPromocionBebida->fetch_array(MYSQLI_BOTH)) {
                     $i++;
                     if($i!=1){
                         echo '<br>';
                     }
                     echo $registroConsultaPedidoPromocionBebida["NOMBRE"].' ('.$registroConsultaPedidoPromocionBebida["CANTIDAD"].')';
                 }
                 echo '</td>
                        <td>';
                 $consultaPedidoPromocionDelicia="SELECT delicia.NOMBRE,pedidopromociondelicia.CANTIDAD FROM pedidopromociondelicia
                                                  inner join delicia on delicia.id = pedidopromociondelicia.BTNID
                                                  and pedidopromociondelicia.PEDIDOPROMOCIONID= $id";
                 $resConsultaPedidoPromocionDelicia = $conexion->query($consultaPedidoPromocionDelicia);
                 $j=0;
                 while ($registroConsultaPedidoPromocionDelicia = $resConsultaPedidoPromocionDelicia->fetch_array(MYSQLI_BOTH)) {
                     $j++;
                     if($j!=1){
                         echo '<br>';
                     }
                     echo $registroConsultaPedidoPromocionDelicia["NOMBRE"].' ('.$registroConsultaPedidoPromocionDelicia["CANTIDAD"].')';
                 }
                 echo '</td>
                        <td>';
                  $consultaPedidoPromocionAdicional="SELECT adicional.NOMBRE,pedidopromocionadicional.CANTIDAD FROM pedidopromocionadicional
                                                     inner join adicional on adicional.id = pedidopromocionadicional.BTNID
                                                     and pedidopromocionadicional.PEDIDOPROMOCIONID= $id";
                 $resConsultaPedidoPromocionAdicional = $conexion->query($consultaPedidoPromocionAdicional);
                 $k=0;
                 while ($registroConsultaPedidoPromocionAdicional = $resConsultaPedidoPromocionAdicional->fetch_array(MYSQLI_BOTH)) {
                     $k++;
                     if($k!=1){
                         echo '<br>';
                     }
                     echo $registroConsultaPedidoPromocionAdicional["NOMBRE"].' ('.$registroConsultaPedidoPromocionAdicional["CANTIDAD"].')';
                 }
                 echo '</td><td>$'.$total.'</td></tr>';
             }
         ?>        
         
     </table>

    </td>
        </tr>
    </table>
    <table width="100%" cellspacing="40">
        <tr align="center">
            <td>
                <?php
                   echo '<input type="button" value="Atras" style="width:120px;height:40px" onClick="agregarAgrupate('.$id.','.$total.')"/>';
                ?>
            </td>
            <td>
                <input type="button" value="Borrar Todo" style="width:120px;height:40px" onclick="borrarCodigos()">
            </td>
        </tr>
    </table>
    </body>
</html>
