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

if (isset($_GET["accion"])) {
    if (($_GET["accion"]) == 'agregarPedido') {
        if (isset($_GET["id"])) {
            $idBoton=$_GET["id"];
            $precio=$_GET["precio"];
            $agregarAgrupate = "select * from BTN_AGRUPATE_TMP where botonid=$idBoton" ;
            $resAgregarAgrupate = $conexion->query($agregarAgrupate);
            $resultAgregarAgrupate = $resAgregarAgrupate->num_rows;

            if ($resultAgregarAgrupate > 0) {
                $borrarDatosTablaTemporal = "delete from BTN_AGRUPATE_TMP where BOTONID = $idBoton";
                $conexion->query($borrarDatosTablaTemporal);
            } else {
                $guardarDatosTablaTemporal = "insert into BTN_AGRUPATE_TMP (BOTONID,CANTIDAD,PRECIO) values ($idBoton,1,$precio)";
                $conexion->query($guardarDatosTablaTemporal);
            }
            Header("Location: agrupate.php");
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
   window.location.href = "agrupate.php?accion=agregarPedido&id=" + idAgrupate+"&precio="+precio;
}

function guardarCantidadPrecioPedido(valor){
    var idCantidadPrecio = valor.split("_");
    var id=idCantidadPrecio[0];
    var cantidad =idCantidadPrecio[1];
    var precio=idCantidadPrecio[2];   
    window.location.href = "agrupate.php?accion=guardarCantidadPrecio&id=" + id+"&cantidad="+cantidad+"&precio="+precio;
}
 

</script>

<html>
    <div align="center"><h1>Agrupate</h1></div>
    <body style="background-color:lightblue;">
    <table width="100%" >
        <tr>
            <td width="30%" valign="top">
                <table cellspacing="0" width="100%" border="2" align="center" valign="top" style="background-color:rgb(255,255,255);">
                    <tr >
                        <td align="center">
                            <b>Promoción</b>
                        </td>
                        <td align="center">
                            <b>Cantidad</b>
                        </td>
                        <td align="center">
                            <b>Precio</b>
                        </td>
                    </tr>
                    <?php

                        while ($registroListPedidos = $resListPedidos->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr valign="top" align="center" ><td>Promoción '.$registroListPedidos["NUMEROPROMOCION"].'</td>
                                  <td><select style="width:50px;height:18px" onchange="guardarCantidadPrecioPedido(this.value)">';
                                for($s=1;$s<=10;$s++){
                                    echo '<option ';
                                    if($registroListPedidos["CANTIDAD"]==$s){
                                        echo 'selected ';
                                    }
                                    echo 'value="'.$registroListPedidos["BOTONID"].'_'.$s.'_'.($s*$registroListPedidos["PRECIOFIJO"]).'">'.$s.'</option>';
                                }
                                echo '</select></td>'; 
                            
                            echo '<td>$'.$registroListPedidos["PRECIO"].'</td></tr>';
                        } 
                         ?>
                </table>
            </td>
            <td width="70%">
                 <table align="center" border="2" cellspacing="0" width="100%" style="background-color:rgb(255,255,255);font-size:13px">
         <tr align="center">
             <td>
                 <b>Promoción N°</b>
             </td>
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
                 $numeroPromocion=$registroConsultaPedidoPromocion["NUMEROPROMOCION"];
                 $total=$registroConsultaPedidoPromocion["TOTAL"];
                 echo '<tr align="center" >
                         <td valign="middle" >
                         <input type="button" value="Promoción '.$numeroPromocion.'" style="width:120px;height:25px;';
                                
                        $btnAgrupate="SELECT * FROM BTN_AGRUPATE_TMP";
                        $resBtnAgrupate= $conexion->query($btnAgrupate);
                        while ($registroBtnAgrupate = $resBtnAgrupate->fetch_array(MYSQLI_BOTH)) {
                            if ($registroBtnAgrupate["BOTONID"] == $id) {
                                echo 'background-color: GRAY;color: WHITE;';
                            }
                        }
                echo '" onClick="agregarAgrupate('.$id.','.$total.')"/></td>                         
                         <td>';
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
                <input type="button" value="Atras" style="width:120px;height:40px" onClick=" window.location.href = 'pedido.php'">
            </td>
            <td>
                <input type="button" value="Borrar Todo" style="width:120px;height:40px">
            </td>
        </tr>
    </table>
    </body>
</html>
