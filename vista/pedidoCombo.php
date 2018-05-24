<html>
    <?php
    include ("../conexion/conexion.php");

    $conexion = crearConexion();
    $tablas ="SELECT tabla.ID,tabla.CANTIDADPIEZAS,tabla.PRECIO FROM btn_cantidad_piezas_prom_tmp
              inner join tabla on tabla.id = btn_cantidad_piezas_prom_tmp.BOTONID";
    $resTablas =  $conexion->query($tablas);    
    /////////////////////////////////////////////////////////////////////////////////////
    $delicias ="SELECT delicia.NOMBRE,btn_delicia_prom_tmp.CANTIDAD,btn_delicia_prom_tmp.PRECIO FROM btn_delicia_prom_tmp
                inner join delicia on delicia.id = btn_delicia_prom_tmp.BOTONID";
    $resDelicias =  $conexion->query($delicias);
    ////////////////////////////////////////////////////////////////////////////////////////////
    $bebidas ="SELECT bebida.NOMBRE,btn_bebida_prom_tmp.CANTIDAD,btn_bebida_prom_tmp.PRECIO FROM btn_bebida_prom_tmp
               inner join bebida on bebida.id = btn_bebida_prom_tmp.BOTONID";
    $resBebidas =  $conexion->query($bebidas);
    //////////////////////////////////////////////////////////////////////////////////////////
    $adicional="SELECT adicional.NOMBRE,btn_adicional_prom_tmp.CANTIDAD,btn_adicional_prom_tmp.PRECIO FROM btn_adicional_prom_tmp
                inner join adicional on adicional.id = btn_adicional_prom_tmp.BOTONID";
    $resAdicional =  $conexion->query($adicional);
    ///////////////////////////////////////////////////////////////////////////////////////////
     if (isset($_GET["accion"])) {
        if (($_GET["accion"]) == 'guardarComboPedido') {
            if (isset($_GET["nombre"])) {
               $nombreCombo=$_GET["nombre"];
               $precioTotal=$_GET["total"];
               $guardarComboPedido="insert into pedidoPromocion (NOMBRE,TOTAL) value ('$nombreCombo',$precioTotal)";
               $conexion->query($guardarComboPedido);
               
               $consultaComboPedido="select MAX(ID)ID from pedidoPromocion";
               $resIdComboPedido =  $conexion->query($consultaComboPedido);
               
                while ($registroIdComboPedido = $resIdComboPedido->fetch_array(MYSQLI_BOTH)) {
                    $idComboPedido = $registroIdComboPedido["ID"];
                }
                
                $consultarTabla="SELECT * FROM btn_cantidad_piezas_prom_tmp";
                $resConsultarTabla =  $conexion->query($consultarTabla);
                while ($registroConsultarTabla = $resConsultarTabla->fetch_array(MYSQLI_BOTH)) {
                    $btnId=$registroConsultarTabla["BOTONID"];
                    $consultaComboPedido="insert into pedidoPromocionPiezas (PEDIDOPROMOCIONID,BTNID) values ($idComboPedido,$btnId)";
                    $conexion->query($consultaComboPedido);
                }
                
                $consultaVariedad="SELECT * FROM btn_variedad_prom_tmp";
                $resConsultaVariedad =  $conexion->query($consultaVariedad);
                while ($registroConsultaVariedad = $resConsultaVariedad->fetch_array(MYSQLI_BOTH)) {
                    $btnId=$registroConsultaVariedad["BOTONID"];
                    $tablaId=$registroConsultaVariedad["TABLAID"];
                    $cantidad=$registroConsultaVariedad["CANTIDAD"];
                    $consultaComboPedido="insert into pedidoPromocionVariedad (PEDIDOPROMOCIONID,BTNID,CANTIDAD,TABLAID) values ($idComboPedido,$btnId,$cantidad,$tablaId)";
                    $conexion->query($consultaComboPedido);
                }
                
                $consultaBebida="SELECT * FROM btn_bebida_prom_tmp";
                $resConsultaBebida =  $conexion->query($consultaBebida);
                while ($registroConsultaBebida = $resConsultaBebida->fetch_array(MYSQLI_BOTH)) {
                    $btnId=$registroConsultaBebida["BOTONID"];
                    $precio=$registroConsultaBebida["PRECIO"];
                    $cantidad=$registroConsultaBebida["CANTIDAD"];
                    $consultaBebida="insert into pedidopromocionbebida (PEDIDOPROMOCIONID,BTNID,CANTIDAD,PRECIO) values ($idComboPedido,$btnId,$cantidad,$precio)";
                    $conexion->query($consultaBebida);
                }
                
                $consultaDelicia="SELECT * FROM btn_delicia_prom_tmp";
                $resConsultaDelicia =  $conexion->query($consultaDelicia);
                while ($registroConsultaDelicia = $resConsultaDelicia->fetch_array(MYSQLI_BOTH)) {
                    $btnId=$registroConsultaDelicia["BOTONID"];
                    $precio=$registroConsultaDelicia["PRECIO"];
                    $cantidad=$registroConsultaDelicia["CANTIDAD"];
                    $consultaDelicia="insert into pedidopromocionDelicia (PEDIDOPROMOCIONID,BTNID,CANTIDAD,PRECIO) values ($idComboPedido,$btnId,$cantidad,$precio)";
                    $conexion->query($consultaDelicia);
                }
                
                $consultaAdicional="SELECT * FROM btn_adicional_prom_tmp";
                $resConsultaAdicional =  $conexion->query($consultaAdicional);
                while ($registroConsultaAdicional = $resConsultaAdicional->fetch_array(MYSQLI_BOTH)) {
                    $btnId=$registroConsultaAdicional["BOTONID"];
                    $precio=$registroConsultaAdicional["PRECIO"];
                    $cantidad=$registroConsultaAdicional["CANTIDAD"];
                    $consultaAdicional="insert into pedidopromocionadicional (PEDIDOPROMOCIONID,BTNID,CANTIDAD,PRECIO) values ($idComboPedido,$btnId,$cantidad,$precio)";
                    $conexion->query($consultaAdicional);
                }
                 
                $borrarTablaTmp="delete from btn_adicional_prom_tmp";
                $conexion->query($borrarTablaTmp);
                $borrarTablaTmp="delete from btn_cantidad_piezas_prom_tmp";
                $conexion->query($borrarTablaTmp);
                $borrarTablaTmp="delete from btn_variedad_prom_tmp";
                $conexion->query($borrarTablaTmp);
                $borrarTablaTmp="delete from btn_delicia_prom_tmp";
                $conexion->query($borrarTablaTmp);
                $borrarTablaTmp="delete from btn_bebida_prom_tmp";
                $conexion->query($borrarTablaTmp);
                
                Header("Location: combo.php");
            }
        }
     }
    ?>
    <script type="text/javascript">
        function guardarPedido(nombreTotal){
            var splitNombreTotal = nombreTotal.split("_");
            var nombre=splitNombreTotal[0];
            var total =splitNombreTotal[1];
            window.location.href = "pedidoCombo.php?accion=guardarComboPedido&nombre="+nombre+"&total="+total;
        }
    </script>
<div align="center"><h1>Preparar pedidos para combo</h1></div>
    <body style="background-color:lightblue;">
        <table>
            <tr>
                <td width="10%" valign="top">
                    <table cellspacing="10px">
                        <tr>
                            <td>
                                <input type="button" value="Tablas" style="width:120px;height:40px" onClick=" window.location.href = 'tablasPromocion.php'">
                            </td>
                        </tr>		
                        <tr>
                            <td>
                                <input type="button" value="Delicias" style="width:120px;height:40px" onClick=" window.location.href = 'deliciasPromocion.php'">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="button" value="Bebidas" style="width:120px;height:40px" onClick=" window.location.href = 'bebidasPromocion.php'">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="button" value="Adicionales" style="width:120px;height:40px" onClick=" window.location.href = 'adicionalesPromocion.php'">
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="75%" valign="top" >
                    <table cellspacing="0" width="100%" border="2" style="background-color:rgb(255,255,255);">
                        <tr >
                            <td align="center">
                                <b>Pedido</b>
                            </td>
                            <td align="center">
                                <b>Variedad</b>
                            </td>
                            <td align="center">
                                <b>Cantidad</b>
                            </td>
                            <td align="center">
                                <b>Precio</b>
                            </td>
                        </tr>
                    <?php
                    $total=0;
                        while ($registroTablas = $resTablas->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr align="center"><td>'.$registroTablas["CANTIDADPIEZAS"].' PIEZAS</td>';  
                            $variedadTabla ="SELECT variedadtabla.NOMBRE,btn_variedad_prom_tmp.CANTIDAD FROM btn_variedad_prom_tmp
                                            inner join variedadtabla on variedadtabla.ID = btn_variedad_prom_tmp.BOTONID
                                            where TABLAID =".$registroTablas["ID"];
                            $resVariedadTabla = $conexion->query($variedadTabla);
                            echo '</td><td>';
                            $i=0;
                            while ($registroVariedadTabla = $resVariedadTabla->fetch_array(MYSQLI_BOTH)) {
                                $i++;
                                if($i!=1){
                                    echo '<br>';
                                }
                                echo $registroVariedadTabla["NOMBRE"];
                            }
                            echo '</td><td>';
                            $j=0;
                            $resVariedadTabla = $conexion->query($variedadTabla);
                            while ($registroVariedadTabla = $resVariedadTabla->fetch_array(MYSQLI_BOTH)) {
                                $j++;
                                if($j!=1){
                                    echo '<br>';
                                }
                                if ($registroVariedadTabla["CANTIDAD"]==0){
                                    echo '-';
                                }else{
                                    echo $registroVariedadTabla["CANTIDAD"];
                                }
                            }
                            echo '</td><td>$'.$registroTablas["PRECIO"].'</td></tr>';
                            $total=$total+$registroTablas["PRECIO"];
                        }
                        while ($registroDelicias = $resDelicias->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr align="center"><td>'.$registroDelicias["NOMBRE"].'</td>
                                 <td>-</td>
                                 <td>'.$registroDelicias["CANTIDAD"].'</td>
                                 <td>$'.$registroDelicias["PRECIO"].'</td></tr>';
                                $total=$total+$registroDelicias["PRECIO"];
                        }
                        while ($registroBebidas = $resBebidas->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr align="center"><td>'.$registroBebidas["NOMBRE"].'</td>
                                 <td>-</td>
                                 <td>'.$registroBebidas["CANTIDAD"].'</td>
                                 <td>$'.$registroBebidas["PRECIO"].'</td></tr>';
                                $total=$total+$registroBebidas["PRECIO"];
                        } 
                        while ($registroAdicional = $resAdicional->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr align="center"><td>'.$registroAdicional["NOMBRE"].'</td>
                                 <td>-</td>
                                 <td>'.$registroAdicional["CANTIDAD"].'</td>
                                 <td>$'.$registroAdicional["PRECIO"].'</td></tr>';
                                $total=$total+$registroAdicional["PRECIO"];
                        } 
                        echo '</table>
                    <h2 align="center">Total: $<input id="idTotal" style="width:70px;height:30px" type="text" value="'.$total.'"></h2>';
                    ?>
                </td>	
                <td  width="15%" valign="top">
                     <table cellspacing="50">
                        <tr>
                            <td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
                    <table align="center">
                        <tr>
                            <td>
                                <b>Nombre pedido </b><input type="text" name="nombrePedido" id="idPedido" />
                            </td>
                        </tr>
                    </table>
        <table cellspacing="20" align="center" width="80%" >
            <tr align="center">
                <td>
                    <input type="button" value="Atras" style="width:120px;height:40px" onClick=" window.location.href = 'combo.php'">
                </td>
                <td>
                    <input type="button" value="Eliminar pedido" style="width:120px;height:40px" onClick=" window.location.href = 'pedidoCombo.php'">
                </td>
                <td>
                    <input type="button" value="Guardar" style="width:120px;height:40px" onClick="guardarPedido(document.getElementById('idPedido').value+'_'+document.getElementById('idTotal').value)">
                </td>             
            </tr>
        </table>
    </body>
</html>
