<?php
require('../Controllers/ctrl_asignados.php');
?>
<div class="modal-content center-align">
    <h4>Vista Previa Correo de Asignación</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_asignados.php" method="post" class="col l10 m10 s10">
            <div class="row">
                <div class="input-field col l12 m12 s12 ">
                        <p class= "left-align" >Cordial saludo,</p>
                    <textarea class="materialize-textarea" name="msj1" id="msj1" >Este mensaje informa la asignación formal a su solicitud de servicio. </textarea>
                    <label for="msj1" class="active"></label>
                </div>
                <div class="input-field col l12 m12 s12 ">
                    <p class="left-align">
                        <?php
                         echo $infoSol = "  <strong><u>Información de la solicitud realizada:</u></strong><br><br>
                                            <strong>Código de la solicitud: </strong>".$idSolIni."<br>
                                            <strong>Solicitante:</strong> ".$nombres.' '.$apellido1.' '.$apellido2."<br>
                                            <strong>Proyecto:</strong> ".$codProy." - ".$nombreProy."<br><br>";
                        ?>
                    </p>
                </div>
                <div class="input-field col l12 m12 s12 ">
                    <label for="obs" class="active">Observación de la solicitud</label>
                    <textarea class="materialize-textarea" name="obs" id="obs" ></textarea>
                </div> 
                <div class="input-field col l12 m12 s12 ">
                    <p class="left-align">
                        <?php
                            echo $infoAsi = "<strong><u>Información de la Asignación:</u></strong><br><br>
                            <strong>Servicio: </strong>".$nombreEqu." - ".$nombreSer."<br>
                            <strong>Código Producto/Servicio: </strong>P".$id." <br>
                            <strong>Fecha prevista de entrega: </strong>".$fechPrev."<br>
                            <strong>Descripción del producto/servicio: </strong>".$ObservacionAct."<br><br>"
                            .$responsables;
                            ?>
                    </p>
                </div>
                
                <div class="input-field col l12 m12 s12 ">
                    <p class="left-align">
                        <?php
                        echo $nota= '<i>Tenga en cuenta que la persona o personas a cargo se pondrán en contacto con usted, en caso de requerirse complementar la información para la prestación del servicio o para aclaraciones pertinentes.
                        Estaremos atentos a sus comentarios.</i>
                        <br><br>
                        Cordialmente,<br><br>';
                        echo $msj2 = ' _________________________________________<br>Equipo de Producción y Soporte<br>Centro de Innovación en Tecnología y Educación - Conecta-TE<br>Facultad de Educación<br>Universidad de los Andes<br>apoyoconectate@uniandes.edu.co<br>';
                        ?>
                    </p>
                </div>  
                <!-- <input type="text" name="correoSol" id="correoSol" value="<?php //echo $correo; ?>" hidden> -->
                <input type="text" name="nombreSer" id="nombreSer" value="<?php echo $nombreSer; ?>" hidden>
                <input type="text" name="nombreEqu" id="nombreEqu" value="<?php echo $nombreEqu; ?>" hidden>
                <input type="text" name="codProy" id="codProy" value="<?php echo $codProy; ?>" hidden>
                <input type="text" name="infoSol" id="infoSol" value="<?php echo $infoSol; ?>" hidden>
                <input type="text" name="idSol" id="idSol" value="<?php echo $id; ?>" hidden>
                <input type="text" name="infoAsi" id="infoAsi" value="<?php echo $infoAsi; ?>" hidden>
                <input type="text" name="nota" id="nota" value="<?php echo $nota; ?>" hidden>
                <input type="text" name="msj2" id="msj2" value="<?php echo $msj2; ?>" hidden>
            </div>
            <div class="input-field col l6 m12 s12 offset-l3 ">
                <button class="btn waves-effect waves-light" type="submit" name="btnEnvAsig">Enviar correo</button>
            </div>
        </form>
    </div>
</div>