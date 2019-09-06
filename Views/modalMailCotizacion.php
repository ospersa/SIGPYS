<div class="modal-content center-align">
    <?php 
    require('../Controllers/ctrl_cotizacion.php');

    $solicitud = Cotizacion::infoSolicitud($idSolEsp);
    $cotizacion = Cotizacion::datosCotizacion($idCot);
    //$solicitante = Cotizacion::consultaSolicitante($solicitante);
    $solicitante = "";
    $totalRec = Cotizacion::totalAsignaciones($idSolEsp);
    $recotizacion = Cotizacion::reCotizacion($idSolEsp);
    $solicitudInicial = Cotizacion::infoSolicitudInicial($solicitud['idSolIni']);

    if ($totalRec > $valCot) {
        $diferencia = "$".number_format($totalRec - $valCot, 0, ",", ".");
        echo "
            <div class='card-panel orange lighten-5'>
                <h5 class='red-text'>Por favor verifique los valores ingresados.</h5>
            </div>
            <p>El valor del presupuesto está: <strong>$diferencia</strong> por debajo del total de los recursos asignados al producto.</p>";
    } else if ($recotizacion) {
        echo "
            <div class='row'>
                <h4 class=''>Correo de nuevo presupuesto</h4>
                <div class='divider'></div>
                <div class='row left-align'>    
                    <p>Buen día $solicitante,</p>
                    <p>Informamos el valor del nuevo presupuesto para el producto/servicio <strong>P$idSolEsp</strong> con solicitud inicial <strong>".$solicitud['idSolIni']."</strong>, del proyecto <strong>".$solicitud['codProy']." - ".$solicitud['nombreProy']."</strong>.</p>
                    <p>Si está de acuerdo, por favor a vuelta de correo aceptar el presupuesto para formalizar la asignación de los encargados y comenzar con el desarrollo del producto/servicio solicitado por usted.</p>
                    <table class='responsive-table teal lighten-5' style='border: 2px solid black;'>
                        <tr class='teal lighten-3' style='border: 1px solid black;'>
                            <th colspan='3'>Información solicitud inicial:</th>
                        </tr>
                        <tr class='teal lighten-4' style='border: 1px solid black;'>
                            <th style='border: 1px solid black;'>Código solicitud</th>
                            <th style='border: 1px solid black;'>Fecha solicitud</th>
                            <th style='border: 1px solid black;'>Proyecto</th>
                        </tr>
                        <tr style='border: 1px solid black;'>
                            <td style='border: 1px solid black;'>".$solicitud['idSolIni']."</td>
                            <td style='border: 1px solid black;'>".date("d/m/Y", strtotime($solicitudInicial['fechSol']))."</td>
                            <td style='border: 1px solid black;'>".$solicitud['codProy']." - ".$solicitud['nombreProy']."</td>
                        </tr>
                        <tr class='teal lighten-3' style='border: 1px solid black;'>
                            <th colspan='3'>Información producto / servicio:</th>
                        </tr>
                        <tr class='teal lighten-4' style='border: 1px solid black;'>
                            <th style='border: 1px solid black;'>Código producto / servicio</th>
                            <th style='border: 1px solid black;' colspan='2' style='border: 1px solid black;'>Producto con especificación:</th>
                        </tr>
                        <tr style='border: 1px solid black;'>
                            <td style='border: 1px solid black;'>P".$idSolEsp."</td>
                            <td style='border: 1px solid black;' colspan='2' style='border: 1px solid black;'>".$solicitud['ObservacionAct']." -- ".$cotizacion['obsProducto']."</td>
                        </tr>
                        <tr class='teal lighten-3' style='border: 1px solid black;'>
                            <th colspan='3'>Información nuevo presupuesto:</th>
                        </tr>
                        <tr class='teal lighten-4' style='border: 1px solid black;'>
                            <th style='border: 1px solid black;'>Fecha</th>
                            <th style='border: 1px solid black;'>Valor(PyS)</th>
                            <th style='border: 1px solid black;'>Observación presupuesto</th>
                        </tr>
                        <tr style='border: 1px solid black;'>
                            <td style='border: 1px solid black;'>".date("d/m/Y", strtotime($cotizacion['fechaRegistro']))."</td>
                            <td style='border: 1px solid black;'>$ ".number_format($valCot, 0, ",", ".")."</td>
                            <td style='border: 1px solid black;'>".$cotizacion['obsSolicitante']."</td>
                        </tr>
                    </table>
                    <p>Estaremos atentos a sus comentarios.</p>
                    <p>Cordialmente,</p>
                    <br>
                    <p>
                    _________________________________________<br>
                    <strong>Equipo de Producción y Soporte</strong><br>
                    Centro de Innovación en Tecnología y Educación - Conecta-TE<br>
                    Facultad de Educación<br>
                    Universidad de los Andes<br>
                    apoyoconectate@uniandes.edu.co
                    </p>
                </div>
                <form action='../Controllers/ctrl_cotizacion.php' id='actForm' method='post' class='col l12 m12 s12' autocomplete='off'>
                    <input type='hidden' name='txtSolEsp' value='$idSolEsp'>
                    <input type='hidden' name='txtSolIni' value='".$solicitud['idSolIni']."'>
                    <input type='hidden' name='txtCodProy' value='".$solicitud['codProy']."'>
                    <input type='hidden' name='txtNomProy' value='".$solicitud['nombreProy']."'>
                    <input type='hidden' name='txtObsProd' value='".$solicitud['ObservacionAct']."'>
                    <input type='hidden' name='txtObsProdCot' value='".$cotizacion['obsProducto']."'>
                    <input type='hidden' name='txtValCot' value='".$valCot."'>
                    <input type='hidden' name='txtObsSol' value='".$cotizacion['obsSolicitante']."'>
                    <input type='hidden' name='txtIdProy' value='".$solicitud['idProy']."'>
                    <input type='hidden' name='txtIdCot' value='".$idCot."'>
                    <button class='btn' type='submit' name='btnEnviarCorreoReCot'>Enviar Correo</button>
                </form>
            </div>";
    } else {
        echo "
            <div class='row'>
                <h4>Correo de presupuesto</h4>
                <div class='row left-align'>
                    <div class='divider'></div>
                    <p>Buen día $solicitante,</p>
                    <p>Informamos el valor presupuestado para el producto/servicio <strong>P$idSolEsp</strong> con solicitud inicial <strong>".$solicitud['idSolIni']."</strong>, del proyecto <strong>".$solicitud['codProy']." - ".$solicitud['nombreProy']."</strong>.</p>
                    <p>Si está de acuerdo, por favor a vuelta de correo aceptar el presupuesto para formalizar la asignación de los encargados y comenzar con el desarrollo del producto/servicio solicitado por usted.</p>
                    <table class='responsive-table teal lighten-5' style='border: 2px solid black;'>
                        <tr class='teal lighten-3' style='border: 1px solid black;'>
                            <th colspan='3'>Información solicitud inicial:</th>
                        </tr>
                        <tr class='teal lighten-4' style='border: 1px solid black;'>
                            <th style='border: 1px solid black;'>Código solicitud</th>
                            <th style='border: 1px solid black;'>Fecha solicitud</th>
                            <th style='border: 1px solid black;'>Proyecto</th>
                        </tr>
                        <tr style='border: 1px solid black;'>
                            <td style='border: 1px solid black;'>".$solicitud['idSolIni']."</td>
                            <td style='border: 1px solid black;'>".date("d/m/Y", strtotime($solicitudInicial['fechSol']))."</td>
                            <td style='border: 1px solid black;'>".$solicitud['codProy']." - ".$solicitud['nombreProy']."</td>
                        </tr>
                        <tr class='teal lighten-3' style='border: 1px solid black;'>
                            <th colspan='3'>Información producto / servicio:</th>
                        </tr>
                        <tr class='teal lighten-4' style='border: 1px solid black;'>
                            <th style='border: 1px solid black;'>Código producto / servicio</th>
                            <th style='border: 1px solid black;' colspan='2' style='border: 1px solid black;'>Producto con especificación:</th>
                        </tr>
                        <tr style='border: 1px solid black;'>
                            <td style='border: 1px solid black;'>P".$idSolEsp."</td>
                            <td style='border: 1px solid black;' colspan='2' style='border: 1px solid black;'>".$solicitud['ObservacionAct']." -- ".$cotizacion['obsProducto']."</td>
                        </tr>
                        <tr class='teal lighten-3' style='border: 1px solid black;'>
                            <th colspan='3'>Información presupuesto:</th>
                        </tr>
                        <tr class='teal lighten-4' style='border: 1px solid black;'>
                            <th style='border: 1px solid black;'>Fecha</th>
                            <th style='border: 1px solid black;'>Valor(PyS)</th>
                            <th style='border: 1px solid black;'>Observación presupuesto</th>
                        </tr>
                        <tr style='border: 1px solid black;'>
                            <td style='border: 1px solid black;'>".date("d/m/Y", strtotime($cotizacion['fechaRegistro']))."</td>
                            <td style='border: 1px solid black;'>$ ".number_format($valCot, 0, ",", ".")."</td>
                            <td style='border: 1px solid black;'>".$cotizacion['obsSolicitante']."</td>
                        </tr>
                    </table>
                    <p>Estaremos atentos a sus comentarios.</p>
                    <p>Cordialmente,</p>
                    <br>
                    <p>
                    _________________________________________<br>
                    <strong>Equipo de Producción y Soporte</strong><br>
                    Centro de Innovación en Tecnología y Educación - Conecta-TE<br>
                    Facultad de Educación<br>
                    Universidad de los Andes<br>
                    <a href='mailto:apoyoconectate@uniandes.edu.co'>apoyoconectate@uniandes.edu.co</a>
                    </p>
                </div>
                <form action='../Controllers/ctrl_cotizacion.php' id='actForm' method='post' class='col l12 m12 s12' autocomplete='off'>
                    <input type='hidden' name='txtSolEsp' value='$idSolEsp'>
                    <input type='hidden' name='txtSolIni' value='".$solicitud['idSolIni']."'>
                    <input type='hidden' name='txtCodProy' value='".$solicitud['codProy']."'>
                    <input type='hidden' name='txtNomProy' value='".$solicitud['nombreProy']."'>
                    <input type='hidden' name='txtObsProd' value='".$solicitud['ObservacionAct']."'>
                    <input type='hidden' name='txtObsProdCot' value='".$cotizacion['obsProducto']."'>
                    <input type='hidden' name='txtValCot' value='".$valCot."'>
                    <input type='hidden' name='txtObsSol' value='".$cotizacion['obsSolicitante']."'>
                    <input type='hidden' name='txtIdProy' value='".$solicitud['idProy']."'>
                    <input type='hidden' name='txtIdCot' value='".$idCot."'>
                    <button class='btn' type='submit' name='btnEnviarCorreoCot'>Enviar Correo</button>
                </form>
            </div>";
    }
    ?>      
</div>