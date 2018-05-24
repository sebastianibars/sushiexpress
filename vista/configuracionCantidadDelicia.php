<html>
    <?php
    include ("../conexion/conexion.php");

    $conexion = crearConexion();
    ///////////////////////////////////////////////////////////////////////////////
    $delicias = "select * from delicia order by id asc";
    $resDelicias = $conexion->query($delicias);
    ///////////////////////////////////////////////////////////////////////////////

    $idCantidadDelicia = "";
    $deliciaIdCantidadDelicia = "";
    $precioCantidadDelicia = "";
    $cantidadCantidadDelicia = "";

    if (isset($_GET["accion"])) {
        if (($_GET["accion"]) == 'editar') {
            if (isset($_GET["id"])) {
                $editarCantidadDelicia = "select * from cantidaddelicia where id=" . $_GET["id"];
                $resCantidadDelicia = $conexion->query($editarCantidadDelicia);
                while ($registroCantidadDelicia = $resCantidadDelicia->fetch_array(MYSQLI_BOTH)) {
                    $idCantidadDelicia = $registroCantidadDelicia['ID'];
                    $deliciaIdCantidadDelicia = $registroCantidadDelicia['DELICIAID'];
                    $precioCantidadDelicia = $registroCantidadDelicia['PRECIO'];
                    $cantidadCantidadDelicia = $registroCantidadDelicia['CANTIDAD'];
                }

                $cargarCantidadDelicia = "select * from cantidaddelicia where DELICIAID=" . $deliciaIdCantidadDelicia;
                $resCargarCantidadDelicia = $conexion->query($cargarCantidadDelicia);
                $idDelicia = $deliciaIdCantidadDelicia;
            }
        } else if (($_GET["accion"]) == 'eliminar') {
            if (isset($_GET["id"])) {
                $eliminarCantidadDelicia = "delete from cantidaddelicia where id=" . $_GET["id"];
                $conexion->query($eliminarCantidadDelicia);

                $cargarCantidadDelicia = "select * from cantidaddelicia where DELICIAID=" . $_GET["deliciaid"];
                $resCargarCantidadDelicia = $conexion->query($cargarCantidadDelicia);
                $idDelicia = $_GET["deliciaid"];
            }
        } else if (($_GET["accion"]) == 'cargar') {
            if (isset($_GET["id"])) {
                if ($_GET["id"] == 0) {
                    $primerDelicia = "select * from delicia order by id asc limit 1";
                    $resPrimerDelicia = $conexion->query($primerDelicia);

                    $idDelicia = "0";

                    while ($registroPrimerDelicia = $resPrimerDelicia->fetch_array(MYSQLI_BOTH)) {
                        $idDelicia = $registroPrimerDelicia['ID'];
                    }

                    $cargarCantidadDelicia = "select * from cantidaddelicia where DELICIAID=" . $idDelicia;
                    $resCargarCantidadDelicia = $conexion->query($cargarCantidadDelicia);
                } else {
                    $cargarCantidadDelicia = "select * from cantidaddelicia where DELICIAID=" . $_GET["id"];
                    $resCargarCantidadDelicia = $conexion->query($cargarCantidadDelicia);
                    $idDelicia = $_GET["id"];
                }
            }
        }
    }
    ?>
    <script type="text/javascript">

        function editar(id) {
            window.location.href = "configuracionCantidadDelicia.php?accion=editar&id=" + id;
        }
        function eliminar(id, deliciaid) {
            window.location.href = "configuracionCantidadDelicia.php?accion=eliminar&id=" + id + "&deliciaid=" + deliciaid;
        }
        function cargar(id) {
            window.location.href = "configuracionCantidadDelicia.php?accion=cargar&id=" + id;
        }

    </script>

    <body style="background-color:lightblue;">
        <div align="center"><h1>Configuraci&#243;n cantidad delicias</h1></div>
        <table width="100%">
            <tr align="center">
                <td>
                    <?php
                    echo '<select name="selectCantidadDelicia" style="width:250px;height:40px;font-size: 20px;" align="center" onchange="cargar(this.value)">';
                    while ($registroDeliciSelect = $resDelicias->fetch_array(MYSQLI_BOTH)) {
                        if ($registroDeliciSelect['ID'] == $idDelicia) {
                            echo '<option selected value=' . $registroDeliciSelect['ID'] . ' >' . $registroDeliciSelect['NOMBRE'] . ' </option>';
                        } else {
                            echo '<option value=' . $registroDeliciSelect['ID'] . ' >' . $registroDeliciSelect['NOMBRE'] . ' </option>';
                        }
                    }
                    echo '</select>';
                    ?>
                </td>
            </tr>
        </table>
        <table cellspacing="20">
            <tr cel>                
            </tr>
        </table>

        <table align="center" width="50%" border="2" cellspacing="0" style="background-color:rgb(255,255,255);">
            <tr align="center">
                <td>
                    <b>Cantidad</b>
                </td>
                <td>
                    <b>Precio</b>
                </td>
                <td style="width:70px;height:20px">
                </td>
                <td style="width:70px;height:20px">
                </td>
            </tr>
            <tr align="center">
                <?php
                while ($registroCargarCantidadDelicia = $resCargarCantidadDelicia->fetch_array(MYSQLI_BOTH)) {
                    echo '<tr align="center">
                           <td>' . $registroCargarCantidadDelicia['CANTIDAD'] . '</td>
                           <td>$' . $registroCargarCantidadDelicia['PRECIO'] . '</td>
                           <td><input type="button" value="Editar" style="width:70px;height:20px" onClick="editar(' . $registroCargarCantidadDelicia['ID'] . ')"></td>
                           <td><input type="button" value="Eliminar" style="width:70px;height:20px" onClick="eliminar(' . $registroCargarCantidadDelicia['ID'] . ',' . $registroCargarCantidadDelicia['DELICIAID'] . ')"></td> </tr>';
                }
                ?>
            </tr>
        </table>
        <form method="POST" action="../controlador/guardar.php" >
            <input type="hidden" name="idCantidadDelicia" value="<?php echo $idCantidadDelicia ?>">
            <input type="hidden" name="idDeliciaCantidadDelicia" value="<?php echo $idDelicia ?>">
            <input type="hidden" name="formulario" value="cantidadDelicia">
            <table width="30%" align="center" cellspacing="40">
                <tr>
                    <td>
                        <fieldset style="padding-left:0px;padding-right:0px;padding-top:0px;padding-bottom:0px;border:2px solid">
                            <legend align="center"><h2>Cantidad</h2></legend>
                            <table width="100%">
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Cantidad</b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="text" name="cantidadCantidadDelicia" value="<?php echo $cantidadCantidadDelicia ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Precio</b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="text" name="precioCantidadDelicia" value="<?php echo $precioCantidadDelicia ?>">
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <table cellspacing="20">
                <tr>
                    <td>                
                    </td>
                </tr>
            </table>
            <table align="center" width="80%">
                <tr>
                    <td align="center">
                        <input type="button" value="Limpiar" style="width:150px;height:40px" onClick="window.location.href = 'configuracionCantidadDelicia.php?accion=cargar&id=<?php echo $idDelicia ?>'">
                    </td>
                    <td align="center">
                        <input type="submit" value="Guardar" style="width:150px;height:40px">
                    </td>
                </tr>
            </table>  
        </form>
    </td>
    <table cellspacing="20">
        <tr>
            <td></td>
        </tr>
    </table>
    <table width="100%" align="center">
        <tr>
            <td align="center">
                <input type="button" value="Atras" style="width:150px;height:40px" onClick=" window.location.href = 'configuracion.html'">
            </td>
        </tr>
    </table>
</body>
</html>
