//Guardamos el controlador del div con ID mensaje en una variable
var mensaje = $("#message");
//Ocultamos el contenedor
mensaje.hide();

$(document).ready(function(){
    $('.modal').modal();
  });

function confirPassword(val1, val2, boton){
	val1 = $(val1).val();
	val2 = $(val2).val();
	if(val1 == val2){
		$("#passText").addClass("hide");
		$(boton).removeClass("disabled");
	}else{
		$("#passText").removeClass("hide");
		$(boton).addClass("disabled");
	}
}
  
function buscar(url, e){
	e.preventDefault();
	console.log(url)
    $.ajax({
        type: "POST",
        url: url,
		data: $('form').serialize(),
		ValidarUser: 1,
        beforeSend: function(){
			console.log(data)
            $('#div_dinamico').html("<div class='row'><div class='col l6 m6 s12 offset-l3 offset-m3'><div class='progress'><div class='indeterminate'></div></div><p class='center-align'>Cargando...</p></div></div>");
        },
        success: function (data){
            $('#div_dinamico').html(data);
            $('#div_dinamico').slideDown("slow");
        }
    });
}
//Cuando el formulario con ID acceso se envíe...
$("#login").on("submit", function(e){
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
	}).done(function(echo){
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