<html>

    <?php
    include ("../conexion/conexion.php");

    $conexion = crearConexion();

    $adicionales = "select * from adicional";
    $resAdicionales = $conexion->query($adicionales);

    $id = "";
    $nombre = "";
    $precio = "";
    $mostrar = "";
    if (isset($_GET["accion"])) {
        if (($_GET["accion"]) == 'editar') {
            if (isset($_GET["id"])) {
                $editarAdicional = "select * from adicional where id=" . $_GET["id"];
                $resAdicional = $conexion->query($editarAdicional);
                while ($registroAdicional = $resAdicional->fetch_array(MYSQLI_BOTH)) {
                    $id = $registroAdicional["ID"];
                    $nombre = $registroAdicional["NOMBRE"];
                    $precio = $registroAdicional["PRECIO"];
                    $mostrar = $registroAdicional["MOSTRAR"];
                }
            }
        } else if (($_GET["accion"]) == 'eliminar') {
            if (isset($_GET["id"])) {
                $eliminarAdicional = "delete from adicional where id=" . $_GET["id"];
                $conexion->query($eliminarAdicional);
                Header("Location: configuracionAdicional.php");
            }
        }
    }
    ?>

    <script type="text/javascript">

        function editar(id) {
            window.location.href = "configuracionAdicional.php?accion=editar&id=" + id;
        }

        function eliminar(id) {
            window.location.href = "configuracionAdicional.php?accion=eliminar&id=" + id;
        }

    </script>
    <div align="center"><h1>Configuraci&#243;n adicionales</h1></div>
    <body style="background-color:lightblue;">
        <table border="2" cellspacing="0" align="center" width="40%" style="background-color:rgb(255,255,255);">
            <tr align="center">
                <td>
                    <b>Adicionales</b>
                </td>
                <td>
                    <b>Precio</b>
                </td>
                <td>
                    <b>Mostrar</b>
                </td>
                <td width="70px">
                </td>
                <td width="70px">
                </td>
            </tr>
            <tr align="center">
                <?php
                while ($registroAdicionales = $resAdicionales->fetch_array(MYSQLI_BOTH)) {
                    echo '<tr align="center">
                      <td>' . $registroAdicionales['NOMBRE'] . '</td>
                      <td>$' . $registroAdicionales['PRECIO'] . '</td>';
                    if ($registroAdicionales['MOSTRAR'] == true) {
                        echo '<td><input type="checkbox" checked style="width:15px;height:15px;" disabled="disabled"></td>';
                    } else {
                        echo '<td><input type="checkbox" style="width:15px;height:15px;" disabled="disabled"></td>';
                    }
                    echo '<td><input type="button" value="Editar" style="width:70px;height:20px" onClick="editar(' . $registroAdicionales['ID'] . ')"></td>
                      <td><input type="button" value="Eliminar" style="width:70px;height:20px" onClick="eliminar(' . $registroAdicionales['ID'] . ')"></td> </tr>';
                }
                ?>

            </tr>
        </table>
        <form method="POST" action="../controlador/guardar.php" >
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="formulario" value="adicional">
            <table width="30%" align="center" cellspacing="40">
                <table width="30%" align="center" cellspacing="40">
                    <tr>
                        <td>
                            <fieldset style="padding-left:0px;padding-right:0px;padding-top:0px;padding-bottom:0px;border:2px solid">
                                <legend align="center"><h2>Adicional</h2></legend>
                                <table width="100%">
                                    <tr>
                                        <td align="right" width="50%">
                                            <b>Adicional</b>
                                        </td>
                                        <td align="left"  width="50%">
                                            <input type="text" name="nombreAdicional" value="<?php echo $nombre ?>">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td align="right" width="50%">
                                            <b>Precio</b>
                                        </td>
                                        <td align="left"  width="50%">
                                            <input type="text" name="precioAdicional" value="<?php echo $precio ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right" width="50%" >
                                            <b>Mostrar</b>
                                        </td>
                                        <td align="left"  width="50%">
                                            <input type="checkbox" name="mostrarAdicional"  
                                            <?php
                                            if ($mostrar == true) {
                                                echo 'checked ';
                                            }
                                            ?>
                                                   style="width:15px;height:15px;" >
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                </table>

                <table align="center" width="80%">
                    <tr>
                        <td align="center">
                        <input type="button" value="Atras" style="width:150px;height:40px" onClick=" window.location.href = 'configuracion.php'">
                    </td>
                    <td align="center">
                        <input type="button" value="Limpiar" style="width:150px;height:40px" onClick=" window.location.href = 'configuracionAdicional.php'">
                    </td>
                    <td align="center">
                        <input type="submit" value="Guardar" style="width:150px;height:40px">
                    </td>
                    </tr>
                </table>    
        </form>
    </body>
</html>
