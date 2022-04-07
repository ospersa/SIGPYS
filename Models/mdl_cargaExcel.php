<?php
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CargaExcel {

    public static function cargaArchivo ($operacion, $archivo) {
        $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        if(in_array($_FILES["file"]["type"],$allowedFileType)){
            $targetPath = '../Uploads/'.$_FILES['file']['name'];
            $move = move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
            if ($move) {
                $reader = IOFactory::load($targetPath);
                $hojaActual = $reader->getSheet(0);
                $lastRow = $hojaActual->getHighestRow();
                $lastCol = $hojaActual->getHighestColumn();
                $dataArray = $reader->getActiveSheet()->rangeToArray('A2:'.$lastCol.$lastRow);
                if ($operacion == 'servicios') {
                    self::services($dataArray);
                } else if ($operacion == 'productos') {
                    self::products($dataArray);
                }
            } else {
                
            }
        } else {
            echo "<script>alert('NO es un archivo correcto');</script>";
        }
    }

    public static function services ($array) {
        require('../Core/connection.php');
        include_once('mdl_solicitudEspecifica.php');
        $registros = 0;
        foreach ($array as $fila) {
            $solicitud          = substr($fila[0], 1);
            $datos = [
                'estado'            => $fila[6],
                'plataforma'        => $fila[10],
                'servicio'          => $fila[11],
                'clase'             => $fila[12],
                'tipo'              => $fila[13],
                'observacion'       => $fila[14],
                'estudiantesImp'    => $fila[15],
                'docentesImp'       => $fila[16],
                'otrosImp'          => $fila[17],
                'urlResultado'      => $fila[18],
                'motivoAnulacion'   => $fila[19],
                'registro'          => $fila[20],
                'fechaCreacion'     => $fila[23]
            ];
            $total = 0;
            $texto  = '';
            $tiempo         = SolicitudEspecifica::totalTiempo($solicitud);
            $horas          = (!is_null($tiempo[0])) ? $tiempo[0] : 0;
            $minutos        = (!is_null($tiempo[1])) ? $tiempo[1] : 0;
            foreach ($datos as $key => $value) {
                if ($value != null || $value === 0) {
                    $total++;
                }
            }
            if ($datos['estado'] != 'Cancelado' && $total == 13) {
                $consulta1      = "SELECT idResultServ FROM pys_resultservicio WHERE idSol = '$solicitud';";
                $resultado1     = mysqli_query($connection, $consulta1);
                $registros1     = mysqli_num_rows($resultado1);
                if ($registros1 > 0) {
                    $datos1     = mysqli_fetch_array($resultado1);
                    $idServicio = $datos1[0];
                    $query = "UPDATE pys_resultservicio SET idPlat = '".$datos['plataforma']."', idSer = '".$datos['servicio']."', idClProd = '".$datos['clase']."', idTProd = '".$datos['tipo']."', observacion = '".$datos['observacion']."', estudiantesImpac = '".$datos['estudiantesImp']."', docentesImpac = '".$datos['docentesImp']."', otrosImpac = '".$datos['otrosImp']."', urlResultado = '".$datos['urlResultado']."', motivoAnulacion = '".$datos['motivoAnulacion']."', idResponRegistro = '".$datos['registro']."', duracionhora = '".$horas."', duracionmin = '".$minutos."', fechaCreacion = '".$datos['fechaCreacion']."', est = '1' WHERE `pys_resultservicio`.`idResultServ` = '$idServicio' AND `pys_resultservicio`.`idSol` = '$solicitud';";
                    $texto = ' <strong>Actualizado</strong> con exito.';
                } else {
                    $consulta2      = "SELECT count(idResultServ), Max(idResultServ) FROM pys_resultservicio;";
                    $resultado2     = mysqli_query($connection, $consulta2);
                    $datos2         = mysqli_fetch_array($resultado2);
                    $count          = $datos2[0];
                    $max            = $datos2[1];
                    $countResServ = ($count == 0) ? "R00001" : 'R'.substr((substr($max, 3)+100001), 1);
                    $query = "INSERT INTO pys_resultservicio (idResultServ, idSol, idPlat, idSer, idClProd, idTProd, observacion, estudiantesImpac, docentesImpac, otrosImpac, urlResultado, motivoAnulacion, idResponRegistro, duracionhora, duracionmin, fechaCreacion, est) VALUES ('".$countResServ."', '$solicitud', '".$datos['plataforma']."', '".$datos['servicio']."', '".$datos['clase']."', '".$datos['tipo']."', '".$datos['observacion']."', '".$datos['estudiantesImp']."', '".$datos['docentesImp']."', '".$datos['otrosImp']."', '".$datos['urlResultado']."', '".$datos['motivoAnulacion']."', '".$datos['registro']."', '$horas', '$minutos', '".$datos['fechaCreacion']."', '1');";
                    $texto = ' <strong>Guardado</strong> con exito.';
                }
                $result = mysqli_query($connection, $query);
                if ($result) {
                    $registros++;
                    echo "<p>P$solicitud $texto</p>";
                } else {
                    echo "<p>Se presentaron errores P$solicitud: <br><br>$query</p>";
                }
            } else {
                echo "<p>El producto P$solicitud con estado " . $datos['estado'] . " no pudo ser actualizado. Total datos: " . $total . ".</p>";
            }
        }
        echo "<h5>Total registros afectados: $registros</h5>";
        mysqli_close($connection);
    }

    public static function products ($array) {
        require('../Core/connection.php');
        include_once('mdl_solicitudEspecifica.php');
        $registros = 0;
        foreach ($array as $fila) {
            $solicitud                  = substr($fila[0], 1);
            $datos = [
                'estado'                => $fila[6],
                'producto'              => $fila[10],
                'tipoRecurso'           => $fila[11],
                'plataforma'            => $fila[12],
                'claseProducto'         => $fila[13],
                'tipoProducto'          => $fila[14],
                'nombreProducto'        => $fila[15],
                'red'                   => $fila[16],
                'palabrasClave'         => $fila[17],
                'fechaEntrega'          => $fila[18],
                'fechaActualizacion'    => $fila[19],
                'urlVimeo'              => $fila[20],
                'urlServidor'           => $fila[21],
                'observacion'           => $fila[22],
                'varios'                => $fila[23],
                'registro'              => $fila[24],
                'motivoAnulacion'       => $fila[25],
                'duracionMinutos'       => $fila[26],
                'duracionSegundos'      => $fila[27],
                'fechaColciencias'      => $fila[28],
                'sinopsis'              => $fila[29],
                'autorExterno'          => $fila[30],
                'idioma'                => $fila[31],
                'formato'               => $fila[32],
                'tipoContenido'         => $fila[33],
                'areaConocimiento'      => $fila[34]
            ];
            list($estado, $producto, $tipoRecurso, $plataforma, $claseProducto, $tipoProducto, $nombreProducto, $red, $palabrasClave, $fechaEntrega, $fechaActualizacion, $urlVimeo, $urlServidor, $observacion, $varios, $registro, $motivoAnulacion, $duracionMinutos, $duracionSegundos, $fechaColciencias, $sinopsis, $autorExterno, $idioma, $formato, $tipoContenido, $areaConocimiento) = array_values($datos);
            $fechaEntrega = ( is_null($fechaEntrega) ) ? 'NULL' : '"'.$fechaEntrega.'"';
            $fechaActualizacion = ( is_null($fechaActualizacion) ) ? 'NULL' : '"'.$fechaActualizacion.'"';
            $fechaColciencias = ( is_null($fechaColciencias) ) ? 'NULL' : '"'.$fechaColciencias.'"';
            $duracionMinutos = ( is_null($duracionMinutos) ) ? 'NULL' : $duracionMinutos;
            $duracionSegundos = ( is_null($duracionSegundos) ) ? 'NULL' : $duracionSegundos;
            /* Consulta de la existencia de un producto en la base de datos, para evitar duplicidad */
            $query0 = "SELECT idProd FROM pys_productos WHERE idSol = '$solicitud' AND est = '1';";
            $result0 = mysqli_query($connection, $query0);
            $registry0 = mysqli_num_rows($result0);
            $data0 = mysqli_fetch_array($result0);
            $producto = ($registry0 > 0) ? $data0[0] : null;
            if ($estado != 'Cancelado') {
                if ($producto != null) {
                    /* Si el producto ya está creado, se procede a actualizar la información en pys_actproductos */
                    $query = "UPDATE pys_actproductos SET idTRec = '$tipoRecurso', idPlat = '$plataforma', idCLProd = '$claseProducto', idTProd = '$tipoProducto', nombreProd = '$nombreProducto', descripcionProd = '$red', palabrasClave = '$palabrasClave', fechEntregaProd = $fechaEntrega, fechaActualizacionProd = $fechaActualizacion, urlVimeo = '$urlVimeo', urlServidor = '$urlServidor', observacionesProd = '$observacion', varios = '$varios', idResponRegistro = '$registro', motivoAnulacion = '$motivoAnulacion', duracionmin = $duracionMinutos, duracionseg = $duracionSegundos, fechaColciencias = $fechaColciencias, sinopsis = '$sinopsis', autorExterno = '$autorExterno', idioma = '$idioma', formato = '$formato', tipoContenido = '$tipoContenido', idAreaConocimiento = '$areaConocimiento' WHERE idProd = '$producto' AND est = '1';";
                    $result = mysqli_query($connection, $query);
                    if ($result) {
                        echo "<p class='orange-text'>[P$solicitud] Producto $producto actualizado correctamente.</p>";
                    } else {
                        echo "<p class='orange-text'>[P$solicitud] Se presentaron errores y no se pudo actualizar el producto $producto.</p>";
                        echo $query;
                    }
                } else {
                    $producto = SolicitudEspecifica::generarCodigoProducto();
                    /* Si el producto aún no existe, se procede a realizar el registro en las tablas pys_productos y pys_actproductos */
                    $query  = "INSERT INTO pys_productos VALUES ('$producto', '$solicitud', '$tipoRecurso', '$plataforma', '$claseProducto', '$tipoProducto','$nombreProducto','$red', '$palabrasClave', $fechaEntrega, $fechaActualizacion, '$urlVimeo', '$urlServidor', '$observacion', '$varios', '$registro', '$motivoAnulacion', $duracionMinutos, $duracionSegundos, $fechaColciencias, '1')";
                    $query1 = "INSERT INTO pys_actproductos VALUES (NULL, '$producto', '$tipoRecurso', '$plataforma', '$claseProducto', '$tipoProducto', '$nombreProducto', '$red', '$palabrasClave', $fechaEntrega, $fechaActualizacion, '$urlVimeo', '$urlServidor', '$observacion', '$varios', '$registro', '$motivoAnulacion', $duracionMinutos, $duracionSegundos, $fechaColciencias, '$sinopsis', '$autorExterno', '1', $idioma, $formato, $tipoContenido, $areaConocimiento);";
                    /* Preparación de base de datos para en caso de fallo realizar ROLLBACK */
                    mysqli_query($connection, "BEGIN;");
                    $result = mysqli_query($connection, $query);
                    $result1 = mysqli_query($connection, $query1);
                    if ($result && $result1) {
                        /* Si los registros se guardan correctamente procedemos a guardar los cambios en la base */
                        mysqli_query($connection, "COMMIT;");
                        echo "<p class='teal-text'>[P$solicitud] Producto <strong>$producto</strong> creado correctamente.</p>";
                    } else {
                        mysqli_query($connection, "ROLLBACK;");
                        echo "<p class='red-text'>[P$solicitud] se presentaron errores y el producto no pudo ser creado.</p>";
                    }
                }
            } else {
                echo "<p class='red-text'>[P$solicitud] no se modificó porque está cancelado.</p>";
            }
            $registros++;
        }
        echo "<h5>Total registros afectados: $registros</h5>";
        mysqli_close($connection);
    }

}

?>