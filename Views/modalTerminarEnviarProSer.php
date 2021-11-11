<h4>Terminación de <?php echo $proOser;?> </h4>
<div class="row">
    <form id="frmCierreProducto" method="post" class="col l12 m12 s12">
        <div class="row">
            
            <div class="input-field col l12 m12 s12 ">
                <input type="text" name="ccemail" id="ccemail" placeholder="Ingrese los correos separados por ; " >
                <label for="ccemail" class="active">Destinatarios</label>
            </div>
            <div class="input-field col l12 m12 s12 ">
                <label for="msjEmail" class="active">Cuerpo del Correo</label>
                <p class="left-align">
                    <?php echo $cuerpo = '
                    Cordial saludo,<br><br>
                    Mediante este mensaje notifico que hemos terminado la realización del siguiente productos/servicio, garantizando que  se encuentra toda la <i> información necesaria </i>para el desarrollo de otros procedimientos<br><br>
                    <strong>Código de la solicitud: </strong>'. $idSolIni.'<br>
                    <strong>Proyecto: </strong>'.$codProy.' - '.$nombreProy.'<br>'.$datoPoS.'<br>';
                    ?>
                </p>
            </div>
            <input type="text" name="cuerpo" id="cuerpo" value="<?php echo $cuerpo;?>" hidden>
            <div class="input-field col l12 m12 s12 ">
                <label for="obs" class="active">Observaciones</label>
                <input type="text" name="obs" id="obs" >
            </div> 
            <div class="input-field col l12 m12 s12 ">
                <label for="nota" class="active">Cuerpo del Correo</label>
                <p class="left-align">
                <?php
                    echo $nota= ' <i><strong>Nota: </strong>En caso de no recibir alguna respuesta a este correo con respecto a cambios, modificaciones o elementos adicionales para el producto/servicio aquí entregado,  pasados 8 días daremos por finalizado el mismo y si son necesarios los cambios, modificaciones o elementos adicionales para este producto o servicio es necesario generar una nueva solicitud a nuestro equipo de P&S.</i>
                    <br><br>
                    Cordialmente,<br><br>';
                    echo $msjEmail2 = ' _________________________________________<br>'.$nombre.' '.$apellido1.' '.$apellido2.'<br>'. $usuario.'@uniandes.edu.co <br>'.$nombreRol ;
                ?>
                </p>
            </div>      
            <input type="text" name="msjEmail2" id="msjEmail2" value="<?php echo $msjEmail2; ?>" hidden>
            <input type="text" name="proOser" id="proOser" value="<?php echo $proOser; ?>" hidden>
            <input type="text" name="id" id="id" value="<?php echo $id; ?>" hidden>
            <input type="text" name="idSolIni" id="idSolIni" value="<?php echo $idSolIni; ?>" hidden>
            <input type="text" name="nota" id="nota" value="<?php echo $nota; ?>" hidden>
            <input type="text" name="codProy" id="codProy" value="<?php echo $codProy; ?>" hidden>
            <input type="text" name="action" id="action" value="close" hidden>
        </div>
        <div class="input-field col l6 m12 s12 offset-l3 ">
            <a href="#" class="btn waves-effect waves-light" name="btnTerEnvi" onclick="closeProduct()">Terminar y enviar correo</a>
        </div>
    </form>
</div>