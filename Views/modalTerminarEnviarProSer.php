<h4>Terminación <?php echo $proOser;?> </h4>
<div class="row">
    <form id="actForm" action="../Controllers/ctrl_terminacionProductoServicio.php" method="post" class="col l12 m12 s12">
        <div class="row">
            <input type="text" name="idSol" id="idSol" value="" hidden>
            <div class="input-field col l12 m12 s12 ">
                <input type="email" name="ccemail" id="ccemail" placeholder="Ingrese los correos separados por ; " >
                <label for="ccemail" class="active">Destinatarios*</label>
            </div>
            <input type="text" name="msjEmail1" id="msjEmail1" value="<?php echo $msjEmail;?>" hidden>
            <div class="input-field col l12 m12 s12 ">
                <label for="msjEmail" class="active">Cuerpo del Correo</label>
                <p class="left-align">
                    <?php
                        echo $msjEmail;
                        ?>
                </p>
            </div>
            <div class="input-field col l12 m12 s12 ">
                <label for="obs" class="active">Observaciones</label>
                <input type="text" name="obs" id="obs" >
            </div> 
            <div class="input-field col l12 m12 s12 ">
                <label for="msjNota" class="active">Cuerpo del Correo</label>
                <p class="left-align">
                    <strong>Nota:</strong><em>En caso de no recibir alguna respuesta a este correo con respecto a cambios, modificaciones o elementos adicionales para el producto/servicio aquí entregado,  pasados 8 días daremos por finalizado el mismo y si son necesarios los cambios, modificaciones o elementos adicionales para este producto o servicio es necesario generar una nueva solicitud a nuestro equipo de P&S.</em> <br />
                    Cordialmente,<br />
                    <?php
                    echo $msjEmail2 = $nombre.' '.$apellido1.' '.$apellido2."<br />". $usuario.'@uniandes.edu.co <br />'.$nombreRol ;
                    ?>
                </p>
            </div>      
            <input type="text" name="msjEmail2" id="msjEmail2" value="<?php echo $msjEmail2; ?>" hidden>
        </div>
        <div class="input-field col l6 m12 s12 offset-l3 ">
            <button class="btn waves-effect waves-light" type="submit" name="btnTerEnvi">Terminar y enviar correo</button>
        </div>
    </form>
</div>