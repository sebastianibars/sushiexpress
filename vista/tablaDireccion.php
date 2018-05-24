<?php
include ("../conexion/conexion.php");
$conexion = crearConexion();
$resConsultaClientes=null;
$telefono=null;
$cliente=null;
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
        DIRECCION.ZONA 
        FROM cliente 
        left join cliente_telefono on cliente.id = cliente_telefono.IDCLIENTE
        left join telefono on cliente_telefono.IDTELEFONO = telefono.id
        left join telefono_direccion on telefono_direccion.IDTELEFONO=telefono.id
        left join direccion on direccion.id = telefono_direccion.IDDIRECCION) this_";
    
    $consultaClientes=$consultaClientes.$condition;
    $resConsultaClientes = $conexion->query($consultaClientes);     
}

?>

<script src="datosClientePedidoFinal.php"></script>
    <script type="text/javascript">
        var nombre="seba";
        
        function buscar(){
            var cliente=document.getElementById("clienteId").value;
            var telefono=document.getElementById("telefonoId").value;
            var url='tablaDireccion.php?';
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
    </script>
    <html>
    <head>
         
       <table width="100%" cellspacing="10" >
            <tr>
                <td align="center" width="45%">
                    <?php
                    if($cliente!=null){
                        echo '<b>Cliente N°: </b><input type="text" id="clienteId"v value="'.$cliente.'">';
                    }else{
                        echo '<b>Cliente N°: </b><input type="text" id="clienteId"v>';
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
                <table border="2" cellspacing="0" style="background-color:rgb(255,255,255);" width="100%">
                        <tr width="100%" align="center">
                            <td>
                                Cliente N°                                
                            </td>
                            <td>
                                Teléfono
                            </td>
                            <td>
                                Zona/Barrio/Compleejo
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
                           <td>
                        </tr>
                        <?php
                        if($resConsultaClientes!=null){
                            while ($resultConsultaClientes = $resConsultaClientes->fetch_array(MYSQLI_BOTH)) {
                               $idCliente=$resultConsultaClientes["IDCLIENTE"];
                               $numeroCliente=$resultConsultaClientes["NUMEROCLIENTE"];
                               $idTelefono=$resultConsultaClientes["IDTELEFONO"];
                               $numeroTelefono=$resultConsultaClientes["NUMEROTELEFONO"];
                               $idDireccion=$resultConsultaClientes["IDDIRECCION"];
                               $calle=$resultConsultaClientes["CALLE"];
                               $numeracion=$resultConsultaClientes["NUMERACION"];
                               $piso=$resultConsultaClientes["PISO"];
                               $departamento=$resultConsultaClientes["DEPARTAMENTO"];
                               $zona=$resultConsultaClientes["ZONA"];

                               echo '<tr align="center">
                                        <td>'
                                        .$numeroCliente.
                                       '</td>
                                         <td>'
                                        .$numeroTelefono.
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
                                   </tr>';                            
                           }
                        }
                        ?>                                     
                    </table>
                </td>
            </tr>
        </table>
      
</html>