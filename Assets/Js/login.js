//Guardamos el controlador del div con ID mensaje en una variable
var mensaje = $("#message");
//Ocultamos el contenedor
mensaje.hide();

$(document).ready(function () {
	$('.modal').modal();

	//Cuando validar usuario se envie
	$('#modalform').on("submit", function () {
		console.log("pruebas");
		var formData = new FormData(document.getElementById("modalform"));
		$.ajax({
			url: "../Controllers/ctrl_login.php",
			type: "POST",
			dataType: "HTML",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			success: function (data) {
				$('#div_dinamico').html(data);
			}
		});
	})
});

function confirPassword(val1, val2, boton) {
	val1 = $(val1).val();
	val2 = $(val2).val();
	if (val1 == val2) {
		$("#passText").addClass("hide");
		$(boton).removeAttr("disabled");
	} else {
		$("#passText").removeClass("hide");
		$(boton).attr("disabled", "disabled");
	}
}

function envioData(valor, dir) {
	$('.modal-content').load(dir + "?id=" + valor, function () {
		$('#cod').val(valor);
		$("select").formSelect();
		inicializarCampos();
		var textareas = $(".textarea");
		if (textareas.length != 0) {
			M.textareaAutoResize($(".textarea"));
		}
	});
}

function buscar(url) {
	$.ajax({
		url: url,
		type: "POST",
		data: $('#modalform').serialize(),
		success: function (data) {
			$('#div_dinamico').html(data);
			$('#numCedula, #nomUsu').attr("readOnly", true);
		}
	});

}

//Cuando el formulario con ID acceso se envíe...
$("#login").on("submit", function (e) {
	//Evitamos que se envíe por defecto
	e.preventDefault();
	//Creamos un FormData con los datos del mismo formulario
	var formData = new FormData(document.getElementById("login"));

	//Llamamos a la función AJAX de jQuery
	$.ajax({
		//Definimos la URL del archivo al cual vamos a enviar los datos
		url: "../Controllers/ctrl_login.php",
		//Definimos el tipo de método de envío
		type: "POST",
		//Definimos el tipo de datos que vamos a enviar y recibir
		dataType: "HTML",
		//Definimos la información que vamos a enviar
		data: formData,
		//Deshabilitamos el caché
		cache: false,
		//No especificamos el contentType
		contentType: false,
		//No permitimos que los datos pasen como un objeto
		processData: false
	}).done(function (echo) {
		//Una vez que recibimos respuesta
		//comprobamos si la respuesta no es vacía
		if (echo !== "") {
			//Si hay respuesta (error), mostramos el mensaje
			mensaje.html(echo);
			mensaje.slideDown(500);
		} else {
			//Si no hay respuesta, redirecionamos a donde sea necesario
			//Si está vacío, recarga la página
			window.location.replace("../Views/home.php");
		}
	});
});