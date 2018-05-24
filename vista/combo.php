    <?php
    include ("../conexion/conexion.php");
    $conexion = crearConexion();
    
    $consultaPedidoPromocion="SELECT pedidopromocion.ID,pedidopromocion.NOMBRE,pedidopromocion.TOTAL FROM pedidopromocion";
    $resConsultaPedidoPromocion = $conexion->query($consultaPedidoPromocion);
    
    if (isset($_GET["accion"])) {
        if (($_GET["accion"]) == 'eliminarCombo') {
            if (isset($_GET["id"])) {
              $id=$_GET["id"];
              $borrarTablaPedido="delete from pedidopromocionbebida where pedidopromocionid=$id";
              $conexion->query($borrarTablaPedido);
              $borrarTablaPedido="delete from pedidopromocion where id=$id";
              $conexion->query($borrarTablaPedido);
              $borrarTablaPedido="delete from pedidopromocionadicional where pedidopromocionid=$id";
              $conexion->query($borrarTablaPedido);
              $borrarTablaPedido="delete from pedidopromocionpiezas where pedidopromocionid=$id";
              $conexion->query($borrarTablaPedido);
              $borrarTablaPedido="delete from pedidopromocionvariedad where pedidopromocionid=$id";
              $conexion->query($borrarTablaPedido);
              $borrarTablaPedido="delete from pedidopromociondelicia where pedidopromocionid=$id";
              $conexion->query($borrarTablaPedido);

              Header("Location: combo.php");
            }
        }
    }
    
    ?>    
<html>
    <script>
    
    function eliminarCombo(id) {
        window.location.href = "combo.php?accion=eliminarCombo&id=" + id;
    }
    </script>
    
     <div align="center"><h1>Armar combos</h1></div>
     <body style="background-color:lightblue;">
     <table align="center" border="2" cellspacing="0" width="80%" style="background-color:rgb(255,255,255);">
         <tr align="center">
             <td>
                 <b> Nombre</b>
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
             <td>
                 
             </td>
         </tr>
         <?php
             while ($registroConsultaPedidoPromocion = $resConsultaPedidoPromocion->fetch_array(MYSQLI_BOTH)) {
                 $id=$registroConsultaPedidoPromocion["ID"];
                 $nombre=$registroConsultaPedidoPromocion["NOMBRE"];
                 $total=$registroConsultaPedidoPromocion["TOTAL"];
                 echo '<tr align="center" >
                         <td valign="middle">'.$nombre.'</td>                         
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
                 echo '</td><td>$'.$total.'</td><td>
                     <input type="button" value="Eliminar" onClick="eliminarCombo('.$id.')"></td></tr>';
             }
         ?>        
         
     </table>
     <table cellspacing="20">
         <tr>
             <td></td>
         </tr>
     </table>
     <table align="center" width="80%">
         <tr align="center">
                <td>
                <input type="button" value="Atras" style="width:150px;height:50px" onClick=" window.location.href = 'configuracion.php'">
            </td>
            <td>
                <input type="button" value="Agregar combo" style="width:150px;height:50px" onClick=" window.location.href = 'pedidoCombo.php'">
            </td>
         </tr>
     </table>
     </body>
</html>