<?php

class Visitante{
    public static function buscar($busqueda){

        require('../Core/connection.php');

        $consulta="SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.idFrente, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idConvocatoria, pys_actualizacionproy.codProy
        from pys_actualizacionproy
        where pys_actualizacionproy.est = '1' and (pys_actualizacionproy.codProy like '%".$busqueda."%')";

        $resultado = mysqli_query($connection, $consulta);

        $rowcount=mysqli_num_rows($resultado);

        if ($rowcount == 0) {
            echo '<option value="">No se han encontrado resultados</option>';
        } else {
            while ($fila = mysqli_fetch_array($resultado)) {
                $var200=$fila["idProy"];
                $var100=$fila["nombreProy"];
                $var999=$fila["codProy"];
                echo '<option value="'.$var200.'">'.$var999.' - '.$var100.'</option>';
            }
        }

        mysqli_close($connection);
    }

   
    public static function buscaCordinador($seleccion){
        require('../Core/connection.php');
        $cordinadores ="";
        $consulta="SELECT pys_personas.idPersona, pys_personas.nombres, pys_personas.apellido1, pys_personas.apellido2 
        FROM pys_celulascoordinador
        INNER JOIN pys_personas ON pys_celulascoordinador.idPersona = pys_personas.idPersona
        INNER JOIN pys_actualizacionproy ON pys_celulascoordinador.idCelula = pys_actualizacionproy.idCelula
        WHERE pys_celulascoordinador.estado = 1 AND  pys_actualizacionproy.est = 1 AND pys_actualizacionproy.idProy='$seleccion'";

        $resultado = mysqli_query($connection, $consulta);

        $rowcount=mysqli_num_rows($resultado);

        if ($rowcount == 0) {
        } else {
            while ($fila = mysqli_fetch_array($resultado)) {
                $nombres=$fila['nombres'];
                $apellido1=$fila['apellido1'];
                $apellido2=$fila['apellido2'];
                $cordinador = $nombres.' '.$apellido1.' '.$apellido2.', ';
                $cordinadores .= $cordinador;
            }
            echo  $cordinadores;
        }

        mysqli_close($connection);
    }

    public static function buscaAsesor($seleccion){

        require('../Core/connection.php');
        $asesores = "";
        $sql="SELECT  pys_personas.idPersona, pys_personas.nombres, pys_personas.apellido1, pys_personas.apellido2 
        FROM pys_asignados
        INNER JOIN pys_personas on pys_asignados.idPersona = pys_personas.idPersona
        INNER JOIN pys_actualizacionproy on pys_asignados.idProy = pys_actualizacionproy.idProy
        where pys_asignados.est = 1 and  pys_actualizacionproy.est = 1 and pys_actualizacionproy.idProy='$seleccion' and (pys_asignados.idRol='ROL024' or pys_asignados.idRol='ROL025')";

        $resultado = mysqli_query($connection, $sql);
        $rowcount=mysqli_num_rows($resultado);
        if ($rowcount == 0) {
        } else {
            while ($fila = mysqli_fetch_array($resultado)) {
                $var=$fila[0];
                $var1=$fila[1];
                $var2=$fila[2];
                $var3=$fila[3];

                $asesor = $var1.' '.$var2.' '.$var3.', ';
                $asesores .= $asesor;
            }
            echo $asesores;
        }

        mysqli_close($connection);
    }

    public static function cargaSelect(){
        require('../Core/connection.php');
        $string = "";
        $sql="SELECT * FROM `pys_personas` WHERE ((`idEquipo`= 'EQU008') or (`idEquipo`= 'EQU006') or (`idEquipo`= 'EQU005') )  and est = '1' ORDER BY apellido1;";

        $cs = mysqli_query($connection, $sql);

        $numero_registro=mysqli_num_rows($cs);

        if ($numero_registro==0) {
            $string .= "<script> alert ('No hay categorias registradas en la base de datos');</script>";
        }
        
        while ($fila = mysqli_fetch_array($cs)) {
            $string .= "<option value='". $fila["idPersona"]."'> ".$fila["apellido1"].' '.$fila["apellido2"].' '.$fila["nombres"]." </option>";
        }
        return $string;
        mysqli_close($connection);
    }

    public static function registrar($proyecto, $solicitante, $data){
        require('../Core/connection.php');
        $producto2 = "";
       /*  require_once 'mail.php'; */

        $begin = mysqli_query($connection, "BEGIN;"); 

        /* contador para tabla pys_solicitudes */
        $sql="select count(idSol), Max(idSol) from pys_solicitudes";
        $cs=mysqli_query($connection, $sql);
        while ($result=mysqli_fetch_array($cs)) {
            $count=$result[0];
            $max=$result[1];
        }
        if ($count==0) {
            $codsol="S00001";
        } else {
            $codsol='S'.substr((substr($max, 1)+100001), 1);
        }
        $x = 8;

        for($i = 0; $i < count($data); $i++){
            if((fmod($i, $x)) == 0 ){
                $producto2 .= "\n\n".$data[$i].' ';
            }elseif(!empty($data[$i])){
                $producto2 .= "\n".$data[$i];
            }
        }

        $producto = '<table border="1">
                    <tbody>
                        <tr>
                            <th bgcolor="#009688"><font color ="#ffffff">Productos/servicios de</font></th>
                            <th bgcolor="#009688"><font color ="#ffffff">Productos/servicios</font></th>
                            <th bgcolor="#009688"><font color ="#ffffff">Descripción Productos/servicios</font></th>
                            <th bgcolor="#009688"><font color ="#ffffff">Contexto de uso de los productos/servicios</font></th>
                            <th bgcolor="#009688"><font color ="#ffffff">Profesor(es)</font></th>
                            <th bgcolor="#009688"><font color ="#ffffff">Monto máximo a invertir</font></th>
                            <th bgcolor="#009688"><font color ="#ffffff">Fecha de pre entrega</font></th>
                            <th bgcolor="#009688"><font color ="#ffffff">Fecha esperada de entrega</font></th>
                        </tr>
                        <tr>';
                    
        for($i = 0; $i < count($data); $i++){
            if( (fmod($i, $x)) != 7 ){
                $producto .= "<td>".$data[$i]."</td>";
            }else{
                $producto .= "<td>".$data[$i]."</td></tr>";
            }
        }

        $producto .= "</tbody></table>";

        $sql2="SELECT idCM FROM pys_cursosmodulos WHERE estProy = '1' AND idCurso = 'CR0051' AND nombreCursoCM = '' AND idProy = '$proyecto'";
        $cs2=mysqli_query($connection, $sql2);
        while ($result=mysqli_fetch_array($cs2)) {
            $idCM = $result['idCM'];
        }

        /*Código de inserción en la tabla pys_solicitudes*/
        $sql="INSERT INTO pys_solicitudes VALUES ('$codsol', 'TSOL01', '', '', '$solicitante', '$solicitante', '$producto2', now(), '1')";
        $cs=mysqli_query($connection, $sql);

        //Código de inserción en la tabla pys_actsolicitudes
        $sql1="INSERT INTO pys_actsolicitudes VALUES (0, 'ESS003', '$codsol', '$idCM', 'SER047', '$solicitante', '$solicitante', null, now(), '$producto2','0','0','1')";
        $cs1=mysqli_query($connection, $sql1);

        if ($cs && $cs1) {
            /** Si ambas consultas son exitosas hacemos commit en la base de datos y guardamos los cambios */
            $commit = mysqli_query($connection, "COMMIT;");
            if ($commit) {
                //Visitante::informacionEmail($solicitante, $proyecto, $producto, $codsol);
                //Mensaje de guardado
              $string = '<h4 class="teal-text">Registrado</h4><br>
              <p>Gracias. Su solicitud se registró correctamente. Será direccionado automáticamente a la página principal.</p><br>';
               
            } else {
                $rollback = mysqli_query($connection, "ROLLBACK;");
                if ($rollback) {
                    //Mensaje de guardado
                    $string ='<h4 class="teal-text">No Registrado</h4><br>
                    <p>Opss!. Su solicitud no se registró correctamente. Será direccionado automáticamente a la página principal.</p><br>';
                    
                }
            }
        } else {
            $rollback = mysqli_query($connection, "ROLLBACK;");
            if ($rollback) {
                //Mensaje de guardado
                $string ='<h4 class="teal-text">No Registrado</h4><br>
                <p>Opss!. Su solicitud no se registró correctamente. Será direccionado automáticamente a la página principal.</p><br>';
            }
        }
        echo $string;
    }

    public static function duplicarDiv ($cont){
        echo '<div class="card-panel col s12 m4 l4 text cardVis" id="cardVis'.$cont.'">
        <a class=" btn btn-floating right waves-effect waves-light red" onclick="eliminarDivVis('.$cont.')"><i class="material-icons">delete</i></a>
        <div class="input-field col s12">
            <select id="text[]" name="text[]" required>
                <option value="">Seleccione</option>
                <option value="Diseño" >Diseño</option>
                <option value="Realización" >Realización</option>
                <option value="Soporte" >Soporte</option>
            </select>
            <label  for="text[]">Producto/Servicio de:*</label>
        </div>

        <div class="input-field col s12">
            <textarea id="text[]" name="text[]" class="materialize-textarea" required></textarea>
            <label  for="text[]">Producto/Servicio*</label>
        </div>

        <div class="input-field col s12">
            <textarea id="text[]" name="text[]" class="materialize-textarea" required></textarea>
            <label  for="text[]">Descripción Producto/Servicio*</label>
        </div>

        <div class="input-field col s12">
            <textarea id="text[]" name="text[]" class="materialize-textarea "></textarea>
            <label  for="text[]">Contexto de uso del Producto/Servicio</label>
        </div>

        <div class="input-field col s12">
            <textarea id="text[]" name="text[]" class="materialize-textarea "></textarea>
            <label  for="text[]">Profesor</label>
        </div>

        <div class="input-field col s12">
            <input type="number" id="text[]" name="text[]" class=" " />
            <label  for="text[]">Monto máximo a invertir</label>
        </div>

        <div class="input-field col s12">
            <input id="date" name="text[]" class="datepicker" />
            <label class="active" for="dare">Fecha de pre entrega</label>
        </div>

        <div class="input-field col s12">
            <input id="date1" name="text[]" class="datepicker" required/>
            <label class="active" for="date1">Fecha esperada de entrega*</label>
        </div>
    </div> ';

    }

    public static function informacionEmail($solicitante, $proyecto, $producto, $codsol){
        require('../Core/connection.php');
        require('../Models/mdl_enviarEmail.php');
        $correoCordinadores = "";
        $correoAsesores = "";
        $consulta = "SELECT * FROM  pys_personas WHERE est = 1 AND idPersona = '$solicitante' ";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        $nombres = $datos['nombres'];
        $apellido = $datos['apellido1'].' '.$datos['apellido2'];
        $correoSol = $datos['correo'];
        $consulta2 = "SELECT pys_personas.correo
        FROM pys_celulascoordinador
        INNER JOIN pys_personas ON pys_celulascoordinador.idPersona = pys_personas.idPersona
        INNER JOIN pys_actualizacionproy ON pys_celulascoordinador.idCelula = pys_actualizacionproy.idCelula
        WHERE pys_celulascoordinador.estado = 1 AND  pys_actualizacionproy.est = 1 AND pys_actualizacionproy.idProy='$proyecto'";
        $resultado2 = mysqli_query($connection, $consulta2);
        while ($datos2 = mysqli_fetch_array($resultado2)){
            $correoCor = $datos2['correo'];
            $correoCordinadores .= $correoCor.";";      
        }
        $consulta3 ="SELECT  pys_personas.correo
        FROM pys_asignados
        INNER JOIN pys_personas on pys_asignados.idPersona = pys_personas.idPersona
        INNER JOIN pys_actualizacionproy on pys_asignados.idProy = pys_actualizacionproy.idProy
        where pys_asignados.est = 1 and  pys_actualizacionproy.est = 1 and pys_actualizacionproy.idProy='$proyecto' and (pys_asignados.idRol='ROL024' or pys_asignados.idRol='ROL025')";
        $resultado3 = mysqli_query($connection, $consulta3);
        while ($datos3 = mysqli_fetch_array($resultado3)){
            $correoAse = $datos3['correo'];
            $correoAsesores .= $correoAse.";";
        }
        $consulta4 = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.codProy
        from pys_actualizacionproy
        where pys_actualizacionproy.est = '1' and pys_actualizacionproy.idProy = '$proyecto'";
        $resultado4 = mysqli_query($connection, $consulta4);
        while ($datos4 = mysqli_fetch_array($resultado4)){
            $nombreProy=$datos4["nombreProy"];
            $codProy=$datos4["codProy"];
        }
        $correos = $correoCordinadores.$correoAsesores.$correoSol;
        $asunto = "Comprobante de solicitud: ".$codsol." del proyecto ".$codProy;
        $cuerpo = "Cordial saludo,<br /><br />
        Mediante este correo confirmamos el registro de la solicitud <strong> $codsol </strong> con la siguiente información:<br /><br />


        <strong>Solicitante: </strong>$nomres $apellidos <br />
        <strong>Proyecto: </strong>$codProy - $nombreProy<br /><br />

        $producto<br /><br />


        Recuerde que en los próximos días, si es necesario, recibirá un correo donde le indicaremos qué integrantes del equipo P&S serán pre asignados para indagar sobre la solicitud y determinar su alcance.<br /><br />

        Cordialmente,<br /><br />

        ____________________________________________________<br />
        <strong>Equipo de Producción y Soporte</strong><br />
        Centro de Innovación en Tecnología y Educación - Conecta-TE<br />
        Facultad de Educación<br />
        Universidad de los Andes<br />
        apoyoconectate@uniandes.edu.co
        ";
       // EnviarCorreo::enviarCorreoSolicitud($correos, $asunto, $cuerpo);

    }

}