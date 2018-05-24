<html>

    <?php
    include ("../conexion/conexion.php");

    $conexion = crearConexion();

    $empleados = "select * from empleado";
    $resEmpleados = $conexion->query($empleados);

    $id = "";
    $nombre = "";
    $apellido = "";
    $dni = "";
    $delivery = "";
    $caja = "";
    $alias = "";
    $presente = "";
    if (isset($_GET["accion"])) {
        if (($_GET["accion"]) == 'editar') {
            if (isset($_GET["id"])) {
                $editarEmpleado = "select * from empleado where id=" . $_GET["id"];
                $resEmpleado = $conexion->query($editarEmpleado);
                while ($registroEmpleado = $resEmpleado->fetch_array(MYSQLI_BOTH)) {
                    $id = $registroEmpleado['ID'];
                    $nombre = $registroEmpleado['NOMBRE'];
                    $apellido = $registroEmpleado['APELLIDO'];
                    $dni = $registroEmpleado['DNI'];
                    $delivery = $registroEmpleado['DELIVERY'];
                    $caja = $registroEmpleado['CAJA'];
                    $alias = $registroEmpleado['ALIAS'];
                    $presente = $registroEmpleado['PRESENTE'];
                }
            }
        } else if (($_GET["accion"]) == 'eliminar') {
            if (isset($_GET["id"])) {
                $eliminarEmpleado = "delete from empleado where id=" . $_GET["id"];
                $conexion->query($eliminarEmpleado);
                Header("Location: configuracionEmpleado.php");
            }
        }
    }
    ?>

    <script type="text/javascript">

        function editar(id) {
            window.location.href = "configuracionEmpleado.php?accion=editar&id=" + id;
        }

        function eliminar(id) {
            window.location.href = "configuracionEmpleado.php?accion=eliminar&id=" + id;
        }

    </script>


    <div align="center"><h1>Configuraci&#243;n empleados</h1></div>
    <body style="background-color:lightblue;">
        <table border="2" cellspacing="0" align="center" width="95%" style="background-color:rgb(255,255,255);">
            <tr align="center">
                <td width="20%">
                    <b>Nombre</b>			
                </td>
                <td width="20%">
                    <b>Apellido</b>
                </td>
                <td width="15%">
                    <b>DNI</b>
                </td>
                <td width="10%">
                    <b>Delivery</b>
                </td>
                <td width="10%">
                    <b>Caja</b>
                </td>
                <td width="10%">
                    <b>Alias</b>
                </td>
                <td width="10%">
                    <b>Presente</b>
                </td>
                <td width="70px">

                </td>
                <td width="70px">

                </td>
            </tr>

            <?php
            while ($registroEmpleados = $resEmpleados->fetch_array(MYSQLI_BOTH)) {

                echo '<tr align="center">
                      <td>' . $registroEmpleados['NOMBRE'] . '</td>
                      <td>' . $registroEmpleados['APELLIDO'] . '</td>
                      <td>' . $registroEmpleados['DNI'] . '</td>';
                if ($registroEmpleados['DELIVERY'] == true) {
                    echo '<td><input type="checkbox" checked style="width:15px;height:15px;" disabled="disabled"></td>';
                } else {
                    echo '<td><input type="checkbox" style="width:15px;height:15px;" disabled="disabled"></td>';
                }
                if ($registroEmpleados['CAJA'] == true) {
                    echo '<td><input type="checkbox" checked style="width:15px;height:15px;" disabled="disabled"></td>';
                } else {
                    echo '<td><input type="checkbox" style="width:15px;height:15px;" disabled="disabled"></td>';
                }

                echo '<td>' . $registroEmpleados['ALIAS'] . '</td>';

                if ($registroEmpleados['PRESENTE'] == true) {
                    echo '<td><input type="checkbox" checked style="width:15px;height:15px;" disabled="disabled"></td>';
                } else {
                    echo '<td><input type="checkbox" style="width:15px;height:15px;" disabled="disabled"></td>';
                }
                echo '<td><input type="button" value="Editar" style="width:70px;height:20px" onClick="editar(' . $registroEmpleados['ID'] . ')"></td>
                      <td><input type="button" value="Eliminar" style="width:70px;height:20px" onClick="eliminar(' . $registroEmpleados['ID'] . ')"></td> </tr>';
            }
            ?>
        </table>
        <form method="POST" action="../controlador/guardar.php">
            <table width="500px" align="center" cellspacing="40">
                <tr>
                    <td>
                        <fieldset style="padding-left:0px;padding-right:0px;padding-top:0px;padding-bottom:0px;border: 2px solid">
                            <legend align="center"><h2>Empleado</h2></legend>
                            <table width="100%">
                                <input type="hidden" name="idEmpleado" value="<?php echo $id ?>">
                                <input type="hidden" name="formulario" value="empleado">
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Nombre </b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="text" name="nombreEmpleado" value="<?php echo $nombre ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Apellido </b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="text" name="apellidoEmpleado" value="<?php echo $apellido ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" width="50%">
                                        <b>DNI </b>
                                    </td>
                                    <td align="left"  width="50%" >
                                        <input type="text" name="dniEmpleado" value="<?php echo $dni ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Delivery </b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="checkbox" name="deliveryEmpleado" 
                                        <?php
                                        if ($delivery == true) {
                                            echo 'checked ';
                                        }
                                        ?>
                                               style="width:15px;height:15px;" >
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Caja </b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="checkbox" name="cajaEmpleado"
                                        <?php
                                        if ($caja == true) {
                                            echo 'checked';
                                        }
                                        ?>
                                               style="width:15px;height:15px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Alias </b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="text" name="aliasEmpleado" value="<?php echo $alias ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Presente </b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="checkbox" name="presenteEmpleado" 
                                        <?php
                                        if ($presente == true) {
                                            echo 'checked';
                                        }
                                        ?>
                                               style="width:15px;height:15px;">
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
                        <input type="button" value="Limpiar" style="width:150px;height:40px" onClick=" window.location.href = 'configuracionEmpleado.php'">
                    </td>
                    <td align="center">
                        <input type="submit" value="Guardar" style="width:150px;height:40px">
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
