<?php
    include_once('../Models/mdl_visitante.php');

	$carga          =(isset($_POST['carga'])) ? $_POST['carga'] :  null;
	$buscar         =(isset($_POST['b'])) ? $_POST['b'] :  null;
	$cordinador     =(isset($_POST['c'])) ? $_POST['c'] :  null;
	$asesor         =(isset($_POST['a'])) ? $_POST['a'] :  null;
	$data           =(isset($_POST['text'])) ? $_POST['text'] :  null;
	$proyecto       =(isset($_POST['smProy'])) ? $_POST['smProy'] :  null;
	$solicitante    =(isset($_POST['smSolicitante'])) ? $_POST['smSolicitante'] :  null;
	$cantidad       =(isset($_POST['cantidad'])) ? $_POST['cantidad'] :  null;

	$selectSolicitante = Visitante::cargaSelect();

    if ($cantidad != null){
        Visitante::duplicarDiv ($cantidad);
	}
	if ($buscar != null) {
		Visitante::buscar($buscar);
	}

	if ($cordinador != null) {
		Visitante::buscaCordinador($cordinador);
	}

	if ($asesor != null) {
		Visitante::buscaAsesor($asesor);
	}

	if($data != null && $proyecto != null && $solicitante!= null){
		Visitante::registrar($proyecto, $solicitante, $data);
    } 