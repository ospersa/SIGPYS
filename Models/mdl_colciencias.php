<?php
    Class Colciencias {

        public static function onLoadProyecto($idProy) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_proyectos WHERE idProy = '$idProy' AND est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $nombreProyecto = $datos['nombreProy'];
            $codProyecto = $datos['codProy'];
            echo '  <div class="row"></div>
                    <div class="row">
                        <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                            <input type="text" name="txtProyecto" id="txtProyecto" value="'.$nombreProyecto.'" readonly>
                            <label for="txtProyecto">Proyecto</label>
                        </div>
                        <div class="input-field col l3 m3 s12">
                            <input type="text" name="txtCodProyecto" id="txtCodProyecto" value="'.$codProyecto.'" readonly>
                            <label for="txtCodProyecto">Proyecto</label>
                        </div>
                    </div>';
            mysqli_close($connection);
        }

    }
?>