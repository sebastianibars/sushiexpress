<?php
include ("../conexion/conexion.php");
$conexion = crearConexion();

$resConsultaClientes=null;
$telefono=null;
$cliente=null;
$idCliente=null;;
$numCliente=null;
$idTelefono=null;
$numeroTelefono=null;
$idDireccion=null;
$calleDireccion=null;
$numeracionDireccion=null;
$pisoDireccion=null;
$departamentoDireccion=null;
$zonaDireccion=null;
$delivery=null;

$consultaPrecio="SELECT FECHAENTREGA,PRECIOTOTAL FROM PEDIDOFINAL";
$resConsultaPrecio = $conexion->query($consultaPrecio);     
while ($resultConsultaPrecio = $resConsultaPrecio->fetch_array(MYSQLI_BOTH)) {
    $precioTotal=$resultConsultaPrecio["PRECIOTOTAL"];
    $fechaEntrega=$resultConsultaPrecio["FECHAENTREGA"];
}
$horaEntrega=substr($fechaEntrega, 11,-3);

$consultaDelivery="SELECT ID,PRECIO FROM delivery";
$resConsultaDelivery = $conexion->query($consultaDelivery); 

if (isset($_GET["accion"])){
    if($_GET["accion"]=="buscar"){
        if (isset($_GET["telefono"]) || isset($_GET["cliente"])) {
            $condition=" where ";
            $union=false;
            if(isset($_GET["telefono"]) && isset($_GET["cliente"])){
                $union=true;
            }
            if(isset($_GET["telefono"])){
                $telefono=$_GET["telefono"];
                $telefonoCondition="NUMEROTELEFONO like '%$telefono%'";
                $condition=$condition.$telefonoCondition;
            }

            if(isset($_GET["cliente"])){
                $cliente=$_GET["cliente"];
                $clienteCondition="NUMEROCLIENTE like '%$cliente%'";
                if($union){
                    $condition=$condition.' and ';                    
                }
                $condition=$condition.$clienteCondition;
            }
            $consultaClientes="SELECT * FROM(
                SELECT 
                cliente.ID IDCLIENTE,
                cliente.NUMERO NUMEROCLIENTE,
                TELEFONO.ID IDTELEFONO,
                TELEFONO.NUMERO NUMEROTELEFONO,
                DIRECCION.ID IDDIRECCION,
                DIRECCION.CALLE,
                DIRECCION.NUMERACION,
                DIRECCION.PISO,
                DIRECCION.DEPARTAMENTO,
                DIRECCION.ZONA ,
                DIRECCION.DELIVERY
                FROM cliente 
                left join cliente_telefono on cliente.id = cliente_telefono.IDCLIENTE
                left join telefono on cliente_telefono.IDTELEFONO = telefono.id
                left join telefono_direccion on telefono_direccion.IDTELEFONO=telefono.id
                left join direccion on direccion.id = telefono_direccion.IDDIRECCION) this_";
            
            $consultaClientes=$consultaClientes.$condition;
            $resConsultaClientes = $conexion->query($consultaClientes);     
        }
    }
    if($_GET["accion"]=="cargar"){
        $idCliente=$_GET["cliente"];
        $idTelefono=$_GET["telefono"];
        $idDireccion=$_GET["direccion"];
        $cliente=$_GET["clienteBuscar"];
        $telefono=$_GET["telefonoBuscar"];
        
        if($idCliente!=0){
            $conCliente="SELECT ID,NUMERO FROM CLIENTE WHERE ID=".$idCliente;
            $resConCliente = $conexion->query($conCliente);     
            while ($resultConCliente = $resConCliente->fetch_array(MYSQLI_BOTH)) {
                $idCliente=$resultConCliente["ID"];
                $numCliente=$resultConCliente["NUMERO"];
            }
        }
        if($idTelefono!=0){
            $consultaTelefono="SELECT ID,NUMERO FROM TELEFONO WHERE ID=".$idTelefono;
            $resConsultaTelefono = $conexion->query($consultaTelefono);     
            while ($resultConsultaTelefono = $resConsultaTelefono->fetch_array(MYSQLI_BOTH)) {
                $idTelefono=$resultConsultaTelefono["ID"];
                $numeroTelefono=$resultConsultaTelefono["NUMERO"];
            }
        }
        if($idDireccion!=0){
            $consultaDireccion="SELECT ID,CALLE,NUMERACION,PISO,DEPARTAMENTO,ZONA,DELIVERY FROM DIRECCION WHERE ID=".$idDireccion;
            $resConsultaDireccion = $conexion->query($consultaDireccion);     
            while ($resultConsultaDireccion = $resConsultaDireccion->fetch_array(MYSQLI_BOTH)) {
                $idDireccion=$resultConsultaDireccion["ID"];
                $calleDireccion=$resultConsultaDireccion["CALLE"];
                $numeracionDireccion=$resultConsultaDireccion["NUMERACION"];
                $pisoDireccion=$resultConsultaDireccion["PISO"];
                $departamentoDireccion=$resultConsultaDireccion["DEPARTAMENTO"];
                $zonaDireccion=$resultConsultaDireccion["ZONA"];
                $delivery=$resultConsultaDireccion["DELIVERY"];
            }
        }
    }
    
   
}

?>
<html>
    <script type="text/javascript">

    function cargarCliente(idCliente,idTelefono,idDireccion){
        var cliente=document.getElementById("clienteId").value;
        var telefono=document.getElementById("telefonoId").value;
        window.location.href ='datosClientePedidoFinal.php?accion=cargar&cliente='+idCliente+'&telefono='+idTelefono+'&direccion='+idDireccion+'&clienteBuscar='+cliente+'&telefonoBuscar='+telefono;            
    }
        
    function buscar(){
        var cliente=document.getElementById("clienteId").value;
        var telefono=document.getElementById("telefonoId").value;
        var url='datosClientePedidoFinal.php?accion=buscar&';
        var union=false;
        if(cliente!="" && telefono!=""){
            union=true;
        }
        if(cliente!=""){
            url=url+'cliente='+cliente+'&';                
        }
        if(telefono!=""){
            if(union){
                url=url+'&';
            }
            url=url+'telefono='+telefono;
        }
        window.location.href =url;
    }
        
    function volverPaginaPadre(){
         window.parent.cambiarPagina();
    }

    </script>
    
    
    <body>
        <table width="100%">
            <tr>
                <td ></td>
                <td width="60%">
                    <table width="100%" cellspacing="10" >
                        <tr>
                            <td align="center" width="45%">
                             <?php
                                if($cliente!=null){
                                    echo '<b>Cliente N°: </b><input type="text" id="clienteId"v value="'.$cliente.'">';
                                }else{
                                     echo '<b>Cliente N°: </b><input type="text" id="clienteId">';
                                }
                            ?>                    
                            </td>
                            <td align="center" width="10%"> 
                                <input type="button" value="Buscar" style="width:120px;height:25px" onClick="buscar()">
                            </td>
                            <td align="center" width="45%">
                                <?php
                                    if($telefono!=null){
                                        echo '<b>Teléfono N°: </b><input type="text" id="telefonoId"v value="'.$telefono.'">';
                                    }else{
                                        echo '<b>Teléfono N°: </b><input type="text" id="telefonoId">';
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" colspan="3" >
                                <div style="height: 100px;overflow-y:scroll;">
                                    <table border="2" cellspacing="0" style="background-color:rgb(255,255,255);" width="100%">
                                        <tr width="100%" align="center">
                                            <td>
                                                Cliente N°                                
                                            </td>
                                            <td>
                                                Teléfono
                                            </td>
                                            <td>
                                                Zona/Barrio/Complejo
                                            </td>
                                            <td>
                                                Calle
                                            </td>
                                            <td>
                                                Número
                                            </td>
                                            <td>
                                                Piso
                                            </td>
                                            <td>
                                                Depto
                                            </td>
                                            <td width="100px">
                                            </td

                                        </tr>
                                            <?php
                                                if($resConsultaClientes!=null){
                                                    while ($resultConsultaClientes = $resConsultaClientes->fetch_array(MYSQLI_BOTH)) {
                                                       $idCliente=$resultConsultaClientes["IDCLIENTE"];
                                                       $numeroCliente=$resultConsultaClientes["NUMEROCLIENTE"];
                                                       $idTelefono=$resultConsultaClientes["IDTELEFONO"];
                                                       $numeroTelefonoTabla=$resultConsultaClientes["NUMEROTELEFONO"];
                                                       $idDireccion=$resultConsultaClientes["IDDIRECCION"];
                                                       $calle=$resultConsultaClientes["CALLE"];
                                                       $numeracion=$resultConsultaClientes["NUMERACION"];
                                                       $piso=$resultConsultaClientes["PISO"];
                                                       $departamento=$resultConsultaClientes["DEPARTAMENTO"];
                                                       $zona=$resultConsultaClientes["ZONA"];
                                                       $delivery=$resultConsultaClientes["DELIVERY"];

                                                       if($idDireccion==""){
                                                           $idDireccion=0;
                                                       }
                                                       if($idCliente==""){
                                                           $idCliente=0;
                                                       }
                                                       if($idTelefono==""){
                                                           $idTelefono=0;
                                                       }
                                                       
                                                       echo '<tr align="center">
                                                                <td>'
                                                                .$numeroCliente.
                                                               '</td>
                                                                 <td>'
                                                                .$numeroTelefonoTabla.
                                                               '</td>
                                                                 <td>'
                                                                .$zona.
                                                               '</td>
                                                                 <td>'
                                                                .$calle.
                                                               '</td>
                                                                 <td>'
                                                                .$numeracion.
                                                               '</td>
                                                                 <td>'
                                                                .$piso.
                                                               '</td>
                                                                 <td>'
                                                                .$departamento.
                                                                '</td>
                                                               <td>
                                                                   <input type="button" value="Cargar cliente" style="width:100px;height:20px" onClick="cargarCliente('.$idCliente.','.$idTelefono.','.$idDireccion.')">
                                                               </td>
                                                           </tr>
                                                           <input type="hidden" value="'.$delivery.'" id="delivery">';                            
                                                   }
                                                }
                                            ?>                                     
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="20%">
                    <form method="POST" action="../controlador/guardar.php" id="formDatosCliente">
                    <input type="hidden" name="formulario" value="datosCliente"/>
                        <?php
                            if($idCliente!=null){
                                echo '<input type="hidden" name="idCliente" value="'.$idCliente.'">';
                            }else{
                                echo '<input type="hidden" name="idCliente" value="">';
                            }   
                            if($idTelefono!=null){
                                echo '<input type="hidden" name="idTelefono" value="'.$idTelefono.'">';
                            }else{
                                echo '<input type="hidden" name="idTelefono" value="">';
                            }   
                             if($idDireccion!=null){
                                echo '<input type="hidden" name="idDireccion" value="'.$idDireccion.'">';
                            }else{
                                echo '<input type="hidden" name="idDireccion" value="">';
                            }   
                        ?>
                        <table align="center">
                            <tr>
                                <td align="right">
                                    <b>Cliente Nº</b>
                                </td>
                                <td>
                                    <?php
                                    if($numCliente!=null){
                                        echo '<input type="text" name="numeroCliente" value="'.$numCliente.'">';
                                    }else{
                                        echo '<input type="text" name="numeroCliente" value="">';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <b>Nombre</b>
                                </td>
                                <td>
                                    <input type="text" name="nombre">
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <b>Teléfono</b>
                                </td>
                                <td>
                                    <?php
                                    if($numeroTelefono!=null){
                                        echo '<input type="text" name="numeroTelefono" value="'.$numeroTelefono.'">';
                                    }else{
                                        echo '<input type="text" name="numeroTelefono" value="">';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <b>Zona/Barrio/Complejo</b>
                                </td>
                                <td>
                                    <?php
                                    if($zonaDireccion!=null){
                                        echo '<input type="text" name="zona" value="'.$zonaDireccion.'">';
                                    }else{
                                        echo '<input type="text" name="zona" value="">';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <b>Calle</b>
                                </td>
                                <td>
                                    <?php
                                    if($calleDireccion!=null){
                                        echo '<input type="text" name="calle" value="'.$calleDireccion.'">';
                                    }else{
                                        echo '<input type="text" name="calle" value="">';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <b>Número</b>
                                </td>
                                <td>
                                    <?php
                                    if($numeracionDireccion!=null){
                                        echo '<input type="text" name="numeracion" value="'.$numeracionDireccion.'">';
                                    }else{
                                        echo '<input type="text" name="numeracion" value="">';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <b>Piso</b>
                                </td>
                                <td>
                                    <?php
                                    if($pisoDireccion!=null){
                                        echo '<input type="text" name="piso" value="'.$pisoDireccion.'">';
                                    }else{
                                        echo '<input type="text" name="piso" value="">';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <b>Depto</b>
                                </td>
                                <td>
                                    <?php
                                    if($departamentoDireccion!=null){
                                        echo '<input type="text" name="departamento" value="'.$departamentoDireccion.'">';
                                    }else{
                                        echo '<input type="text" name="departamento" value="">';
                                    }
                                    ?>
                                </td>
                            </tr
                            <tr>
                                <td align="right">
                                    <b>Paga con</b>
                                </td>
                                <td>
                                   <input type="text" name="paga" value="">
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <b>Notas</b>
                                </td>
                                <td>
                                   <textarea rows="5" cols="22" name="notas">
                                   </textarea>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <b>Delivery</b>
                                </td>
                                <td align="left" width="50%" >
                                    <?php
                                    echo '<select style="width:70px;height:25px" name="delivery">';
                                        while ($resultConsultaDelivery = $resConsultaDelivery->fetch_array(MYSQLI_BOTH)) {
                                            $idDelivery=$resultConsultaDelivery["ID"];
                                            $precioDelivery=$resultConsultaDelivery["PRECIO"];
                                            if($precioDelivery==$delivery){
                                                echo '<option selected value="'.$idDelivery.'">$'.$precioDelivery.'</option>';
                                            }else{
                                                echo '<option value="'.$idDelivery.'">$'.$precioDelivery.'</option>';
                                            }
                                        }
                                    echo '</select>';
                                    ?>                           
                                </td>
                            </tr>
                        </table>
                        <table align="center">
                            <tr align="center">
                                <td>
                                    <?php
                                        echo '<b>Total: $'.$precioTotal,'</b>';
                                    ?>
                                </td>
                            </tr>
                            <tr align="center">
                                <td>
                                    <?php
                                        echo '<b>Hora de entrega: '.$horaEntrega.' hs</b>';
                                    ?>
                                </td>
                            </tr>
                        </table>
                        <table align="center">
                            <tr >
                                <td>
                                    <input type="submit" value="Finalizar pedido" style="width:140px;height:40px" onclick="volverPaginaPadre()" />
                                </td>
                                <td>
                                    <input type="button" value="Borrar" style="width:140px;height:40px" onClick="window.location.href = 'finalPedido.php'">
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
                <td></td>
            </tr>
        </table>
    </body>
</html>