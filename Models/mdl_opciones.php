<?php 

class Opcion {

    public static function onLoad($id) {
        require('../Core/connection.php');
        $query = "SELECT * FROM pys_options WHERE option_id = '$id';";
        $result = mysqli_query($connection, $query);
        $data = mysqli_fetch_array($result);
        return $data;
        mysqli_close($connection);
    }

    public static function busqueda($buscar) {
        require('../Core/connection.php');
        $buscar = mysqli_real_escape_string($connection, $buscar);
        if ( empty ($buscar) ) {
            $query = "SELECT * FROM pys_options WHERE option_state = '1';";
        } else {
            $query = "SELECT * FROM pys_options 
                WHERE (option_name LIKE '%$buscar%' OR option_description LIKE '%$buscar%') AND option_state = '1';";
        }
        $result = mysqli_query($connection, $query);
        $registry = mysqli_num_rows($result);
        if ($registry > 0) {
            echo '  <table class="responsive-table left">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Valor</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($data = mysqli_fetch_array($result)) {
                $id = $data['option_id'];
                echo '      <tr>
                                <td>'.$data['option_name'].'</td>
                                <td>'.$data['option_description'].'</td>
                                <td>'.$data['option_value'].'</td>
                                <td><a href="#modalOpciones" class="waves-effect waves-light modal-trigger" onclick="envioData(\''.$id.'\',\'modalOpciones.php\');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                            </tr>';
            }
            echo '      </tbody>
                    </table>';
        } else {
            echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda</h6></div>';
        }
        mysqli_close($connection);
    }

    public static function registrarOpcion ($name, $description, $value) {
        require('../Core/connection.php');
        $query = "SELECT * FROM pys_options WHERE option_name = '$name' AND option_state = '1';";
        $result = mysqli_query($connection, $query);
        $registry = mysqli_num_rows($result);
        if ($registry > 0) {
            echo "<script> alert ('Ya existe una opción configurada con el nombre seleccionado.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
        } else {
            $query1 = "INSERT INTO pys_options VALUES (NULL, '$name', '$description', '$value', '1');";
            $result1 = mysqli_query($connection, $query1);
            if ($result1) {
                echo "<script> alert ('Opción guardada correctamente.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
            } else {
                echo "<script> alert ('Ocurrió un error y el registro no pudo ser guardado. Intente nuevamente.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
            }
        }
        mysqli_close($connection);
    }

    public static function actualizarOpcion ($id, $name, $description, $value) {
        require('../Core/connection.php');
        $query = "SELECT * FROM pys_options WHERE option_id = '$id';";
        $result = mysqli_query($connection, $query);
        $data = mysqli_fetch_array($result);
        $diff = ($data['option_name'] != $name || $data['option_description'] != $description || $data['option_value'] != $value) ? true : false;
        $void = (empty($name) || $name == ' ' || empty($description) || $description == ' ' || empty($value) || $value == ' ') ? true : false;
        if ($void) {
            echo "<script> alert ('Algún campo está vacío y no se pudo actualizar el registro.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
        } else {
            if (!$diff) {
                echo "<script> alert ('La información ingresada es la misma. El registro no fue actualizado');</script>";
                echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
            } else {
                $query2 = "UPDATE pys_options SET option_name = '$name', option_description = '$description', option_value = '$value' WHERE option_id = '$id';";
                $result2 = mysqli_query($connection, $query2);
                if ($result) {
                    echo "<script> alert ('Registro actualizado correctamente');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
                } else {
                    echo "<script> alert ('Ocurrió un error y el registro no pudo ser actualizado. Intente nuevamente.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
                }
            }
        }
        mysqli_close($connection);
    }

    public static function eliminarOpcion ($id) {
        require('../Core/connection.php');
        $query = "UPDATE pys_options SET option_state = '0' WHERE option_id = '$id';";
        $result = mysqli_query($connection, $query);
        if ($result) {
            echo "<script> alert ('Registro eliminado correctamente');</script>";
            echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
        } else {
            echo "<script> alert ('Ocurrió un error y el registro no pudo ser eliminado. Intente nuevamente.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
        }
        mysqli_close($connection);
    }

}

?>