<?php
 include ("../conexion/conexion.php");

    $conexion = crearConexion();
    $delicias = "select * from delicia where mostrar=true";
    $resDelicia = $conexion->query($delicias);
    
    ////////////////////////////////////////////////////////////////////////777
    $listDelicias = "SELECT btn_delicia_prom_tmp.BOTONID,delicia.NOMBRE,btn_delicia_prom_tmp.PRECIO,delicia.PRECIO PRECIOFIJO,btn_delicia_prom_tmp.CANTIDAD CANTIDADBTN,delicia.CANTIDAD CANTIDADDEL FROM btn_delicia_prom_tmp
                     INNER JOIN delicia ON delicia.id = btn_delicia_prom_tmp.BOTONID";
    $resListDelicias = $conexion->query($listDelicias);
    
    
    if (isset($_GET["accion"])) {
        if (($_GET["accion"]) == 'guardarDelicia') {
            if (isset($_GET["id"])) {
                $idBoton = $_GET["id"];
                $cantBtnDelicia = "SELECT * FROM btn_delicia_prom_tmp where BOTONID = $idBoton";
                $resCantBtnDelicia = $conexion->query($cantBtnDelicia);
                $resultCantBtnDelicia = $resCantBtnDelicia->num_rows;

                if ($resultCantBtnDelicia > 0) {
                    $borrarDatosTablaTemporal = "delete from btn_delicia_prom_tmp where BOTONID = $idBoton";
                    $conexion->query($borrarDatosTablaTemporal);
                } else {
                    $cantidadFija="SELECT CANTIDAD FROM delicia where id=$idBoton";
                    $resCantidadFija=$conexion->query($cantidadFija);
                    while ($registroCantidadFija = $resCantidadFija->fetch_array(MYSQLI_BOTH)) {
                        $cantidadFija=$registroCantidadFija["CANTIDAD"];
                    }
                    if($cantidadFija==true){
                        $consultaCantidad="SELECT CANTIDAD,PRECIO FROM cantidaddelicia where deliciaid=$idBoton order by cantidad asc limit 1";
                        $resConsultaCantidad=$conexion->query($consultaCantidad);
                        while ($registroConsultaCantidad = $resConsultaCantidad->fetch_array(MYSQLI_BOTH)) {
                            $cantidad=$registroConsultaCantidad["CANTIDAD"];
                            $precio=$registroConsultaCantidad["PRECIO"];
                        }
                    }else{
                        $consultaCantidad="SELECT PRECIO FROM delicia where id=$idBoton";
                        $resConsultaCantidad=$conexion->query($consultaCantidad);
                        while ($registroConsultaCantidad = $resConsultaCantidad->fetch_array(MYSQLI_BOTH)) {
                            $cantidad=1;
                            $precio=$registroConsultaCantidad["PRECIO"];
                        }
                    }                   
                    $guardarDatosTablaTemporal = "insert into btn_delicia_prom_tmp (BOTONID,CANTIDAD,PRECIO) value ($idBoton,$cantidad,$precio)";
                    $conexion->query($guardarDatosTablaTemporal);
                }
                Header("Location: deliciasPromocion.php");
            }
        }else if (($_GET["accion"]) == 'guardarCantidadPrecio') {
            if (isset($_GET["id"])) {
                $id=$_GET["id"];
                $precio=$_GET["precio"];
                $cantidad=$_GET["cantidad"];
            }
            $actualizarCantidadPrecio = "update btn_delicia_prom_tmp set CANTIDAD=$cantidad, precio=$precio where botonid=$id";
            $conexion->query($actualizarCantidadPrecio);
                
            Header("Location: deliciasPromocion.php");
        }else if (($_GET["accion"]) == 'borrarSeleccion') {
            $borrarTablas="DELETE FROM btn_delicia_prom_tmp";
            $conexion->query($borrarTablas); 
                       
            Header("Location: deliciasPromocion.php");
        }
    }
?>
<script type="text/javascript">
    function guardarDelicia(id){
        window.location.href = "deliciasPromocion.php?accion=guardarDelicia&id=" + id;
    }

    function guardarCantidadPrecioDeliciaCantidadFija(valor){
        var idCantidadPrecio = valor.split("_");
        var id=idCantidadPrecio[0];
        var cantidad =idCantidadPrecio[1];
        var precio=idCantidadPrecio[2];   
        window.location.href = "deliciasPromocion.php?accion=guardarCantidadPrecio&id=" + id+"&cantidad="+cantidad+"&precio="+precio;
    }

    function borrarSeleccion(){
        window.location.href = "deliciasPromocion.php?accion=borrarSeleccion";        
    }
</script>

<html>
    <div align="center"><h1>Delicias</h1></div>
    <body style="background-color:lightblue;">
        <table width="100%">
            <tr>				
                <td  valign="middle" width="50%">
                    <table cellspacing="0" width="100%" border="2" style="background-color:rgb(255,255,255);">
                        <tr >
                            <td align="center">
                                <b>Pedido</b>
                            </td>
                            <td align="center">
                                <b>Cantidad</b>
                            </td>
                            <td align="center">
                                <b>Precio</b>
                            </td>
                        </tr>
                        
                          <?php

                        while ($registroListDelicias = $resListDelicias->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr valign="top" align="center"><td>'.$registroListDelicias["NOMBRE"].'</td>';
                            if($registroListDelicias["CANTIDADDEL"]==true){
                                echo '<td><select style="width:50px;height:18px" onchange="guardarCantidadPrecioDeliciaCantidadFija(this.value)">';
                                $cantBtn=$registroListDelicias["BOTONID"];
                                $consultaCantidadVariable="SELECT * FROM cantidaddelicia where deliciaid=$cantBtn";
                                $resConsultaCantidadVariable=$conexion->query($consultaCantidadVariable);
                                while ($registroConsultaCantidadVariable = $resConsultaCantidadVariable->fetch_array(MYSQLI_BOTH)) {
                                    echo '<option ';
                                    if($registroListDelicias["CANTIDADBTN"]==$registroConsultaCantidadVariable["CANTIDAD"]){
                                        echo 'selected ';
                                    }
                                    echo 'value="'.$registroListDelicias["BOTONID"].'_'.$registroConsultaCantidadVariable["CANTIDAD"].'_'.$registroConsultaCantidadVariable["PRECIO"].'">'.$registroConsultaCantidadVariable["CANTIDAD"].'</option>';
                                }
                            }else{
                                echo '<td><select style="width:50px;height:18px" onchange="guardarCantidadPrecioDeliciaCantidadFija(this.value)">';
                                for($s=1;$s<=10;$s++){
                                    echo '<option ';
                                    if($registroListDelicias["CANTIDADBTN"]==$s){
                                        echo 'selected ';
                                    }
                                    echo 'value="'.$registroListDelicias["BOTONID"].'_'.$s.'_'.($s*$registroListDelicias["PRECIOFIJO"]).'">'.$s.'</option>';
                                }
                                echo '</select></td>'; 
                            }
                            echo '<td>$'.$registroListDelicias["PRECIO"].'</td></tr>';
                        } 
                         ?>                   
                    </table>
                    <table width="100%" cellspacing="30" >
                        <tr >
                            <td></td>
                        </tr>
                    </table>                    
                </td>	
                <td  valign="top" width="50%">
                    <table align="center">
                          <?php
                            $i=0;
                            while ($registroDelicia = $resDelicia->fetch_array(MYSQLI_BOTH)) {
                                $i=$i+1;
                                if($i==1){
                                    echo '<tr>';
                                }
                                echo '<td>
                                        <input type="button" style="width:150px;height:30px;';
                                $btnDelicias="SELECT * FROM btn_delicia_prom_tmp";
                                $resBtnDelicia= $conexion->query($btnDelicias);
                                while ($registroBtnDelicia = $resBtnDelicia->fetch_array(MYSQLI_BOTH)) {
                                   
                                    if ($registroBtnDelicia["BOTONID"] == $registroDelicia["ID"]) {
                                        
                                        echo 'background-color: GRAY;color: WHITE;';
                                    }
                                }
                                echo '" value="'.$registroDelicia["NOMBRE"].'" onClick="guardarDelicia(' . $registroDelicia['ID'] . ')">
                                      </td>';
                                if($i==3){
                                    echo '</tr>';
                                    $i=0;
                                }
                            }
                             if($i==2){
                               echo '</tr>';
                          }
                            ?>                     
                    </table>
                </td>
            </tr>
        </table> 
        <table cellspacing="20">
            <tr>
                <td></td>
            </tr>
        </table>
        <table width="100%" >
                        <tr align="center">
                            <td>
                                <input type="button" value="Atras" style="width:120px;height:40px" onClick=" window.location.href = 'pedidoCombo.php'">
                            </td>
                            <td>
                                <input type="button" value="Borrar Todo" style="width:120px;height:40px" onClick="borrarSeleccion()">
                            </td>
                        </tr>
                    </table>
    </body>
</html>
