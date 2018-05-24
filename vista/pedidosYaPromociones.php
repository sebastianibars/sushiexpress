<?php
    include ("../conexion/conexion.php");
    $conexion = crearConexion();

    $consultaCombos = "SELECT pedidopromocion.ID,pedidopromocion.NOMBRE FROM pedidopromocion";
    $resConsultaCombos = $conexion->query($consultaCombos);
    ////////////////////////////////////////////////////////////////////////////////////////////
    $consultaPedidosYa="SELECT pedidosya.ID,pedidosya.NUMEROPROMOCION,pedidosya.PEDIDOPROMOCIONID,pedidopromocion.NOMBRE,pedidosya.MOSTRAR 
                        FROM pedidosya
                        inner join pedidopromocion on pedidopromocion.id = pedidosya.PEDIDOPROMOCIONID";
    $resConsultaPedidosYa = $conexion->query($consultaPedidosYa);

    $idPedidosYa="";
    $numeroPromocion="";
    $idPedidoPromocion="";
    $comboId="";
    $mostrar="";
    
if (isset($_GET["accion"])) {
    if (($_GET["accion"]) == 'editar') {
        if (isset($_GET["id"])) {
            $editarPedidosYa = "select * from pedidosya where id=" . $_GET["id"];
            $resEditarPedidosYa = $conexion->query($editarPedidosYa);
            while ($registroEditarPedidosYa = $resEditarPedidosYa->fetch_array(MYSQLI_BOTH)) {
                $idPedidosYa=$registroEditarPedidosYa["ID"];
                $numeroPromocion=$registroEditarPedidosYa["NUMEROPROMOCION"];
                $idPedidoPromocion=$registroEditarPedidosYa["PEDIDOPROMOCIONID"];
                $mostrar=$registroEditarPedidosYa["MOSTRAR"];
            }
        }
    } else if (($_GET["accion"]) == 'eliminar') {
        if (isset($_GET["id"])) {
            $eliminarPedidoYa = "delete from pedidosya where id=" . $_GET["id"];
            $conexion->query($eliminarPedidoYa);
            Header("Location: pedidosYaPromociones.php");
        }
    }
}   
?>
<script>
    function editar(id) {
        window.location.href = "pedidosYaPromociones.php?accion=editar&id=" + id;
    }

    function eliminar(id) {
        window.location.href = "pedidosYaPromociones.php?accion=eliminar&id=" + id;
    }

</script>

<html>
    <div align="center"><h1>Pedidos ya - promociones</h1></div>
    <body style="background-color: lightblue">
        <table width="50%" align="center" border="2" cellspacing="0" style="background-color: rgb(255,255,255);">
            <tr align="center">
                <td>
                    <b>Nº promocion</b>
                </td>
                <td>
                    <b>Nombre combo</b>
                </td>
                <td>
                    <b>Mostrar</b>
                </td>
                <td>
                </td>
                <td></td>
            </tr>
            <?php
                while ($registroConsultaPedidosYa = $resConsultaPedidosYa->fetch_array(MYSQLI_BOTH)) {
                    echo '<tr align="center">
                            <td>'.$registroConsultaPedidosYa["NUMEROPROMOCION"].'</td>
                            <td>'.$registroConsultaPedidosYa["NOMBRE"].'</td>';
                    if ($registroConsultaPedidosYa["MOSTRAR"] == true) {
                        echo '<td><input type="checkbox" checked style="width:15px;height:15px;" disabled="disabled"></td>';
                    } else {
                        echo '<td><input type="checkbox" style="width:15px;height:15px;" disabled="disabled"></td>';
                    }
                    echo '<td style="width:70px;height:20px"><input type="button" value="Editar" style="width:70px;height:20px" onClick="editar(' . $registroConsultaPedidosYa['ID'] . ')"></td>
                          <td style="width:70px;height:20px"><input type="button" value="Eliminar" style="width:70px;height:20px" onClick="eliminar(' . $registroConsultaPedidosYa['ID'] . ')"></td> </tr>';
                }
            ?>           
        </table>
        <table cellspacing="30">
            <tr>
                <td>                
                </td>
            </tr>
        </table>
        <table width="40%" align="center" >
            <tr>
                <td>
                    <fieldset style="padding-left:0px;padding-right:0px;padding-top:0px;padding-bottom:0px;border: 2px solid">
                        <legend align="center"><h2>Pedidos ya - Promocion</h2></legend>
                        <form method="POST" action="../controlador/guardar.php" >
                        <input type="hidden" name="id" value="<?php echo $idPedidosYa ?>">
                        <input type="hidden" name="formulario" value="pedidosYa">
                        <table width="100%">
                            <tr>
                                <td align="right" width="50%">
                                    <b>Nº promocion </b>
                                </td>
                                <td align="left" width="50%" >
                                    <select style="width:160px;height:25px" name="numeroPromocion">
                                    <?php
                                        for ($i = 0; $i <= 15; $i++) {
                                            if($i==0){
                                                echo '<option value="' . $i . '">Seleccionar promoción</option>';
                                            }else{
                                                if($i==$numeroPromocion){
                                                    echo '<option selected value="' . $i . '">' . $i . '</option>';
                                                }else{
                                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                                }
                                            }
                                        }
                                    ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" width="50%">
                                    <b>Nombre combo </b>
                                </td>
                                <td align="left" width="50%" >
                                    <select style="width:150px;height:25px" name="idPedidoPromocion">
                                    <?php
                                        echo '<option value="0" >Seleccionar combo</option>';
                                        while ($registroConsultaCombos = $resConsultaCombos->fetch_array(MYSQLI_BOTH)) {
                                            if($registroConsultaCombos["ID"]==$idPedidoPromocion){
                                                echo '<option selected value="' . $registroConsultaCombos["ID"] . '" selected >' . $registroConsultaCombos["NOMBRE"] . '</option>';
                                            }else{
                                                echo '<option value="' . $registroConsultaCombos["ID"] . '" >' . $registroConsultaCombos["NOMBRE"] . '</option>';
                                            }
                                        }
                                    ?>
                                    </select>
                                </td>
                            </tr>       
                            <tr>
                                <td align="right" width="50%">
                                    <b>Mostrar</b>
                                </td>
                                <td align="left"  width="50%">
                                    <input type="checkbox" name="mostrarPedidosYa"  
                                    <?php
                                        if ($mostrar == true) {
                                            echo 'checked ';
                                        }
                                    ?>
                                    style="width:15px;height:15px;" >
                                </td>
                            </tr>
                        </table>
                    <form>
                </fieldset>
            </tr>
        </table>
        <table cellspacing="20">
            <tr>
                <td></td>
            </tr>
        </table>
        <table width="100%" cellpadding="20">
            <tr align="center">
                <td>
                    <input type="button" value="Atras" style="width:120px;height:40px" onClick=" window.location.href = 'configuracion.html'">
                </td>   
                 <td>
                    <input type="button" value="Limpiar" style="width:120px;height:40px" onClick=" window.location.href = 'pedidosYaPromociones.php'">
                </td>   
                <td>
                    <input type="submit" value="Guardar" style="width:150px;height:40px">
                </td>
            </tr>
        </table>
    </body>
</html>