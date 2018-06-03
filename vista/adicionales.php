<?php
 include ("../conexion/conexion.php");

    $conexion = crearConexion();
    $adicionales = "select * from adicional where mostrar=true";
    $resAdicionales = $conexion->query($adicionales);
    
    ////////////////////////////////////////////////////////////////////////777
    $listAdicionales = "SELECT btn_adicional_tmp.BOTONID,adicional.NOMBRE,btn_adicional_tmp.PRECIO PRECIOBTN,adicional.PRECIO PRECIOFIJO,btn_adicional_tmp.CANTIDAD FROM btn_adicional_tmp
                     INNER JOIN adicional ON adicional.id = btn_adicional_tmp.BOTONID";
    $resListAdicionales = $conexion->query($listAdicionales);
  
    if (isset($_GET["accion"])) {
        if (($_GET["accion"]) == 'guardarAdicional') {
            if (isset($_GET["id"])) {
                $idBoton = $_GET["id"];
                $cantBtnAdicional = "SELECT * FROM BTN_ADICIONAL_TMP where BOTONID = $idBoton";
                $resCantBtnAdicional = $conexion->query($cantBtnAdicional);
                $resultCantBtnAdicional = $resCantBtnAdicional->num_rows;

                if ($resultCantBtnAdicional > 0) {
                    $borrarDatosTablaTemporal = "delete from BTN_ADICIONAL_TMP where BOTONID = $idBoton";
                    $conexion->query($borrarDatosTablaTemporal);
                } else {
                    $consultaCantidad="SELECT PRECIO FROM adicional where id=$idBoton";
                    $resConsultaCantidad=$conexion->query($consultaCantidad);
                    while ($registroConsultaCantidad = $resConsultaCantidad->fetch_array(MYSQLI_BOTH)) {
                        $cantidad=1;
                        $precio=$registroConsultaCantidad["PRECIO"];
                    }
                    $guardarDatosTablaTemporal = "insert into BTN_ADICIONAL_TMP (BOTONID,CANTIDAD,PRECIO) values ($idBoton,$cantidad,$precio)";
                    $conexion->query($guardarDatosTablaTemporal);
                }
                Header("Location: adicionales.php");
            }
        }else if (($_GET["accion"]) == 'guardarCantidadPrecio') {
            if (isset($_GET["id"])) {
                $id=$_GET["id"];
                $precio=$_GET["precio"];
                $cantidad=$_GET["cantidad"];
            }
            $actualizarCantidadPrecio = "update btn_adicional_tmp set CANTIDAD=$cantidad, precio=$precio where botonid=$id";
            $conexion->query($actualizarCantidadPrecio);
                
            Header("Location: adicionales.php");
        }else if (($_GET["accion"]) == 'borrarSeleccion') {
            $borrarTablas="DELETE FROM btn_adicional_tmp";
            $conexion->query($borrarTablas); 
                       
            Header("Location: adicionales.php");
        }
    }
?>
<script type="text/javascript">
    function guardarAdicional(id){
        window.location.href = "adicionales.php?accion=guardarAdicional&id=" + id;
    }

    function guardarCantidadPrecioAdicionalCantidadFija(valor){
        var idCantidadPrecio = valor.split("_");
        var id=idCantidadPrecio[0];
        var cantidad =idCantidadPrecio[1];
        var precio=idCantidadPrecio[2];   
        window.location.href = "adicionales.php?accion=guardarCantidadPrecio&id=" + id+"&cantidad="+cantidad+"&precio="+precio;
    }
    
    function borrarSeleccion(){
        window.location.href = "adicionales.php?accion=borrarSeleccion";
    }
</script>

<html>
    <div align="center"><h1>Adicionales</h1></div>
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

                        while ($registroListAdicionales = $resListAdicionales->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr valign="top" align="center"><td>'.$registroListAdicionales["NOMBRE"].'</td>
                                  <td><select style="width:50px;height:18px" onchange="guardarCantidadPrecioAdicionalCantidadFija(this.value)">';
                                for($s=1;$s<=10;$s++){
                                    echo '<option ';
                                    if($registroListAdicionales["CANTIDAD"]==$s){
                                        echo 'selected ';
                                    }
                                    echo 'value="'.$registroListAdicionales["BOTONID"].'_'.$s.'_'.($s*$registroListAdicionales["PRECIOFIJO"]).'">'.$s.'</option>';
                                }
                                echo '</select></td>'; 
                            
                            echo '<td>$'.$registroListAdicionales["PRECIOBTN"].'</td></tr>';
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
                            while ($registroAdicional = $resAdicionales->fetch_array(MYSQLI_BOTH)) {
                                $i=$i+1;
                                if($i==1){
                                    echo '<tr>';
                                }
                                echo '<td>
                                        <input type="button" style="width:150px;height:30px;';
                                $btnAdicionales="SELECT * FROM btn_adicional_tmp";
                                $resBtnAdicional= $conexion->query($btnAdicionales);
                                while ($registroBtnAdicional = $resBtnAdicional->fetch_array(MYSQLI_BOTH)) {
                                   
                                    if ($registroBtnAdicional["BOTONID"] == $registroAdicional["ID"]) {
                                        
                                        echo 'background-color: GRAY;color: WHITE;';
                                    }
                                }
                                echo '" value="'.$registroAdicional["NOMBRE"].'" onClick="guardarAdicional(' . $registroAdicional['ID'] . ')">
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
