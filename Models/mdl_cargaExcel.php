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
            $total  = 0;
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
                    echo "<p>P$solicitud $texto</p>";
                } else {
                    echo "<p>Se presentaron errores P$solicitud: <br><br>$query</p>";
                }
            } else {
                echo "<p>El producto P$solicitud con estado " . $datos['estado'] . " no pudo ser actualizado. Total datos: " . $total . ".</p>";
            }
        }
        mysqli_close($connection);
    }

}

?>