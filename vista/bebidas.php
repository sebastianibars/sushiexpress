<?php
 include ("../conexion/conexion.php");

    $conexion = crearConexion();
    $bebidas = "select * from bebida where mostrar=true";
    $resBebidas = $conexion->query($bebidas);
    
    ////////////////////////////////////////////////////////////////////////777
    $listBebidas = "SELECT btn_bebida_tmp.BOTONID,bebida.NOMBRE,btn_bebida_tmp.PRECIO PRECIOBTN,bebida.PRECIO PRECIOFIJO,btn_bebida_tmp.CANTIDAD FROM btn_bebida_tmp
                     INNER JOIN bebida ON bebida.id = btn_bebida_tmp.BOTONID";
    $resListBebidas = $conexion->query($listBebidas);
  
    if (isset($_GET["accion"])) {
        if (($_GET["accion"]) == 'guardarBebida') {
            if (isset($_GET["id"])) {
                $idBoton = $_GET["id"];
                $cantBtnBebida = "SELECT * FROM BTN_BEBIDA_TMP where BOTONID = $idBoton";
                $resCantBtnBebida = $conexion->query($cantBtnBebida);
                $resultCantBtnBebida = $resCantBtnBebida->num_rows;

                if ($resultCantBtnBebida > 0) {
                    $borrarDatosTablaTemporal = "delete from BTN_BEBIDA_TMP where BOTONID = $idBoton";
                    $conexion->query($borrarDatosTablaTemporal);
                } else {
                    $consultaCantidad="SELECT PRECIO FROM bebida where id=$idBoton";
                    $resConsultaCantidad=$conexion->query($consultaCantidad);
                    while ($registroConsultaCantidad = $resConsultaCantidad->fetch_array(MYSQLI_BOTH)) {
                        $cantidad=1;
                        $precio=$registroConsultaCantidad["PRECIO"];
                    }
                    $guardarDatosTablaTemporal = "insert into BTN_BEBIDA_TMP (BOTONID,CANTIDAD,PRECIO) value ($idBoton,$cantidad,$precio)";
                    $conexion->query($guardarDatosTablaTemporal);
                }
                Header("Location: bebidas.php");
            }
        }else if (($_GET["accion"]) == 'guardarCantidadPrecio') {
            if (isset($_GET["id"])) {
                $id=$_GET["id"];
                $precio=$_GET["precio"];
                $cantidad=$_GET["cantidad"];
            }
                $actualizarCantidadPrecio = "update btn_bebida_tmp set CANTIDAD=$cantidad, precio=$precio where botonid=$id";
                $conexion->query($actualizarCantidadPrecio);
                
                Header("Location: bebidas.php");
        }else if (($_GET["accion"]) == 'borrarSeleccion') {
            $borrarTablas="DELETE FROM btn_bebida_tmp";
            $conexion->query($borrarTablas); 
                       
            Header("Location: bebidas.php");
        }
    }
?>
<script type="text/javascript">
    function guardarBebida(id){
        window.location.href = "bebidas.php?accion=guardarBebida&id=" + id;
    }

    function guardarCantidadPrecioBebidaCantidadFija(valor){
        var idCantidadPrecio = valor.split("_");
        var id=idCantidadPrecio[0];
        var cantidad =idCantidadPrecio[1];
        var precio=idCantidadPrecio[2];   
        window.location.href = "bebidas.php?accion=guardarCantidadPrecio&id=" + id+"&cantidad="+cantidad+"&precio="+precio;
    }
    
    function borrarSeleccion(){
        window.location.href = "bebidas.php?accion=borrarSeleccion";
    }

</script>

<html>
    <div align="center"><h1>Bebidas</h1></div>
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

                        while ($registroListBebidas = $resListBebidas->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr valign="top" align="center"><td>'.$registroListBebidas["NOMBRE"].'</td>
                                  <td><select style="width:50px;height:18px" onchange="guardarCantidadPrecioBebidaCantidadFija(this.value)">';
                                for($s=1;$s<=10;$s++){
                                    echo '<option ';
                                    if($registroListBebidas["CANTIDAD"]==$s){
                                        echo 'selected ';
                                    }
                                    echo 'value="'.$registroListBebidas["BOTONID"].'_'.$s.'_'.($s*$registroListBebidas["PRECIOFIJO"]).'">'.$s.'</option>';
                                }
                                echo '</select></td>'; 
                            
                            echo '<td>$'.$registroListBebidas["PRECIOBTN"].'</td></tr>';
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
                            while ($registroBebida = $resBebidas->fetch_array(MYSQLI_BOTH)) {
                                $i=$i+1;
                                if($i==1){
                                    echo '<tr>';
                                }
                                echo '<td>
                                        <input type="button" style="width:150px;height:30px;';
                                $btnBebidas="SELECT * FROM btn_bebida_tmp";
                                $resBtnBebida= $conexion->query($btnBebidas);
                                while ($registroBtnBebida = $resBtnBebida->fetch_array(MYSQLI_BOTH)) {
                                   
                                    if ($registroBtnBebida["BOTONID"] == $registroBebida["ID"]) {
                                        
                                        echo 'background-color: GRAY;color: WHITE;';
                                    }
                                }
                                echo '" value="'.$registroBebida["NOMBRE"].'" onClick="guardarBebida(' . $registroBebida['ID'] . ')">
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
                                <input type="button" value="Atras" style="width:120px;height:40px" onClick=" window.location.href = 'pedido.php'">
                            </td>
                            <td>
                                <input type="button" value="Borrar Todo" style="width:120px;height:40px" onClick="borrarSeleccion()">
                            </td>
                        </tr>
                    </table>
    </body>
</html>
