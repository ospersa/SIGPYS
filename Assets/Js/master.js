$(window).on('load', function () {
    $('.loader').delay(350).fadeOut('slow');
});

$('.modal').on('load', function () {
    inicializarCampos();
});

$('.modal').on('change', function () {
    $('select').formSelect();
    /* $('select').addClass('hide'); */
    inicializarCampos();
});

$(document).ready(function () {
    let path = window.location.pathname;
    let comp = path.split("/")
    if (comp.pop() == 'home.php') {
        $('main').css('background-color','#2D3340')
        if ($('#chartSolicitud')) {
            chartSolicitud();
        }
        if ($('#chartSolIni')) {
            chartSolicitudIni();
        }
        if ($('#chartTiempo')) {
            chartTiempo();
        }
        if ($('#chartInv')) {
            chartInventario();
        }
        if ($('#chartCot')) {
            chartCotizacion();
        }
        if ($('#chartProyecto')) {
            chartProyecto();
        }
    }

    $("#busqueda").keyup(function () {
        let consulta2 = $(this).val();
        $.ajax({
            url: "../Controllers/ctrl_visitante.php",
            type: "POST",
            data: "b=" + consulta2,
            error: function () {
                alert("error: No se puede realizar la busqueda en este momento.");
            },
            success: function (data) {
                $("#smProy").empty();
                $("#smProy").append('<option value="" disabled selected>Select your option.</option>');
                $("#smProy").append(data);
                $('select').formSelect();
            }
        });
    });

    $("#smProy").change(function () {
        let consulta2 = $(this).val();
        $.ajax({
            url: "../Controllers/ctrl_visitante.php",
            type: "POST",
            data: "c=" + consulta2,
            error: function () {
                alert("error: No se puede realizar la busqueda en este momento.");
            },
            success: function (data) {
                $("#cordinador").val(data);
                M.textareaAutoResize($('#cordinador'));
                $('#cordinador').attr('disabled', 'disabled');
            }
        });
        $.ajax({
            url: "../Controllers/ctrl_visitante.php",
            type: "POST",
            data: "a=" + consulta2,
            error: function () {
                alert("error: No se puede realizar la busqueda en este momento.");
            },
            success: function (data) {
                $("#gestor").val(data);
                M.textareaAutoResize($('#gestor'));
                $('#gestor').attr('disabled', 'disabled');
            }
        });
    });
    inicializarCampos();

    if ($(".dropdown-trigger")) {
        $(".dropdown-trigger").dropdown({
            closeOnClick: false
        });
    }

    $('.modal').modal({
        onOpenEnd: function () {
            inicializarCampos();
        }
    });

    $('#btnRegTiempo').click(function(){
        var fecha = $("#fechaDia").val();
        $('.modal').modal({
            onOpenStart: modalAgenda(fecha),
            onOpenEnd: inicializarCampos(),
        });
    });

    $('.collapsible').collapsible({
        onOpenEnd: function () {
            if ($('textarea').length > 0) {
                M.textareaAutoResize($(".materialize-textarea"));
            }
            $('select').formSelect();
        }
    });

    $('.datepicker').datepicker({
        container: 'body'
    });

    $('#txtFechIni').datepicker({
        showClearBtn: true,
        i18n: {
            clear: 'Borrar'
        },
        onSelect: function () {
            $('#check').prop('disabled', true),
                $('#txtFechFin').focus()
        }
    });

    $('#txtFechFin').datepicker({
        showClearBtn: true,
        i18n: {
            clear: 'Borrar'
        },
        onSelect: function () {
            $('#check').prop('disabled', true)
        }
    });

    $('.datepicker-clear').click(function () {
        if ($('#txtFechIni').val() === "" && $('#txtFechFin').val() === "") {
            $('#check').prop('disabled', false);
        }
    });

    $('#check').change(function () {
        if ($(this).is(':checked')) {
            $('#txtFechIni').prop('disabled', true);
            $('#txtFechFin').prop('disabled', true);
        } else {
            $('#txtFechIni').prop('disabled', false);
            $('#txtFechFin').prop('disabled', false);
        }
    });

    $('table tbody#cotizacion').paginathing({
        perPage: 10,
        insertAfter: 'table.responsive-table',
        pageNumbers: false,
    });

    $("#btn-search").hover(
        function () {
            $("#txt-search").removeClass("hide");
        },
        function () {
            $("#btn-search").click(function () {
                $("#txt-search").addClass("hide");
            });
        }
    );

    $('.ico-clean').click(function () {
        $('#txt-search').val('');
    });

    $('#btnAsignar').click(function () {
        $('#txtValCotizacion').removeAttr("required");
    });

    $('#frmPeriodos').submit(function () {
        $('#cantDias').prop('disabled', false);
    });

    $('#sltPeriodo').change(function () {
        var valor = $(this).val();
        $('#div_dinamico').empty();
        $.ajax({
            type: "POST",
            url: "../Controllers/ctrl_busPersona.php",
            data: $("#sltPeriodo").serialize(),
            success: function (data) {
                $('#div_dinamico').html(data);
            }
        })
    });

    $('#sltPeriodo2').change(function () {
        var persona = "";
        var periodo = $(this).val();
        $('#div_dinamico').empty();
        var personaFrm = $('#txtPersona').val();
        if (personaFrm == null) {
            $.ajax({
                type: "POST",
                url: "../Controllers/ctrl_busPersona.php",
                data: $("#sltPeriodo2").serialize(),
                success: function (data) {
                    $('#div_sltdinamico').html(data);
                    $("select").formSelect();
                    $('#sltPersona').change(function () {
                        persona = $(this).val();
                        $('#div_dinamico').empty();
                        $.ajax({
                            type: "POST",
                            url: "../Controllers/ctrl_busPersonaProy.php",
                            data: {
                                persona: persona,
                                periodo: periodo
                            },
                            beforeSend: function (xhr) {
                                $('#div_dinamico').html("<div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
                            },
                            success: function (data) {
                                $('#div_dinamico').html(data);
                            },
                        })
                    });
                }
            })
        } else {
            $.ajax({
                type: "POST",
                url: "../Controllers/ctrl_busPersonaProy.php",
                data: {
                    persona: personaFrm,
                    periodo: periodo
                },
                success: function (data) {
                    $('#div_dinamico').html(data);
                    M.textareaAutoResize($("textarea"));
                }
            })
        }
    });

    $('#sltPeriodoPlan').change(function () {
        $('#div_dinamico').empty();
        var periodo = $(this).val();
        $.ajax({
            type: "POST",
            url: "../Controllers/ctrl_busPersona.php",
            data: $('#sltPeriodoPlan').serialize(),
            success: function (data) {
                $('#div_sltdinamico').html(data);
                $('select').formSelect();
                $('#sltPersonaPlan').change(function () {
                    var persona = $(this).val();
                    $('#div_dinamico').empty();
                    $.ajax({
                        type: "POST",
                        url: "../Controllers/ctrl_infPlaneacionXls.php",
                        data: {
                            persona: persona,
                            periodo: periodo
                        },
                        beforeSend: function () {
                            $('#div_dinamico').html("<div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
                        },
                        success: function (data) {
                            $('#div_dinamico').html(data);
                            M.textareaAutoResize($("textarea"));
                        }
                    });
                });
            }
        });
    });

    $('#sltPeriodoPlan').change(function () {
        $('#div_dinamico').empty();
        $.ajax({
            type: "POST",
            url: "../Controllers/ctrl_infPlaneacionXls.php",
            data: $('#sltPeriodoPlan').serialize(),
            success: function (data) {
                $('#div_dinamico').html(data);
            }
        });
    });

    /* MODULO COTIZACIONES */
    $(function () {
        var $form = $("#frmCotizador");
        var $input = $form.find("#txtValCotizacion, #txtDiferencia");
        $input.on("keyup change", function (event) {
            var selection = window.getSelection().toString();
            if (selection !== '') {
                return;
            }
            if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                return;
            }
            var $this = $(this);
            var input = $this.val();
            var input = input.replace(/[\D\s\._\-]+/g, "");
            input = input ? parseInt(input, 10) : 0;
            $this.val(function () {
                return (input === 0) ? "" : "$ " + input.toLocaleString("es-CO");
            });
        });
    })

    $('#btnAsignar').prop('disabled', true);

    $('.asignacion').on("keyup change", function () {
        var disable = false;
        $('.asignacion').each(function () {
            if (!$(this).val()) {
                disable = true;
            }
        });
        $('#btnAsignar').prop('disabled', disable);
    });

    if ($('#txtObsAprobacion').val() != "" && $('#txtEnlAprobacion').val() != "") {
        $('#btnAprobar').prop("disabled", "true");
        $('#txtObsAprobacion').prop("readonly", "true");
        $('#txtEnlAprobacion').prop("readonly", "true");
    }

    $('#txtDiferencia').val("$ " + ($('#txtValCot').text() - $('#txtTotalRecurso').val()).toLocaleString("es-CO"));
    /* MODULO COTIZACIONES */

    /** Busqueda proyecto en solicitud inicial*/
    var consulta;
    $('#txtBusquedaProy').keyup(function () {
        consulta = $('#txtBusquedaProy').val();
        $.ajax({
            type: "POST",
            url: "../Controllers/ctrl_solicitudInicial.php",
            data: "b=" + consulta,
            dataType: "html",
            beforeSend: function () {
                $('#sltProyecto').html("<div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
            },
            error: function () {
                alert("Error: No se puede realizar la busqueda en este momento");
            },
            success: function (data) {
                $("#sltProyecto").empty();
                $("#sltProyecto").append(data);
                $('select').formSelect();
            }
        })
    })
    /* Busqueda de proyecto en informe notas de tiempos */
    $('#txtBusquedaProyInf').keyup(function () {
        consulta = $('#txtBusquedaProyInf').val();
        $.ajax({
            type: "POST",
            url: "../Controllers/ctrl_infNotasTiempos.php",
            data: "b=" + consulta,
            dataType: "html",
            beforeSend: function () {
                $('#sltProyecto').html("<div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
            },
            error: function () {
                alert("Error: No se puede realizar la busqueda en este momento");
            },
            success: function (data) {
                $("#sltProyecto").empty();
                $("#sltProyecto").append(data);
                $('select').formSelect();
            }
        })
    })
    /* */
    $('#txtBusquedaProyUsu').keyup(function () {
        consulta = $('#txtBusquedaProyUsu').val();
        $.ajax({
            type: "POST",
            url: "../Controllers/ctrl_terminacionProductoServicio.php",
            data: "b=" + consulta,
            dataType: "html",
            beforeSend: function () {
                $('#sltProyectoUsu').html("<div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
            },
            error: function () {
                alert("Error: No se puede realizar la busqueda en este momento");
            },
            success: function (data) {
                $("#sltProyectoUsu").empty();
                $("#sltProyectoUsu").append(data);
                $('select').formSelect();
            }
        })
    })
    /** Selects para inventario */
    $('#txtBusquedaPersona').keyup(function () {
        consulta = $('#txtBusquedaPersona').val();
        $.ajax({
            type: "POST",
            url: "../Controllers/ctrl_inventario.php",
            data: "persona=" + consulta,
            dataType: "html",
            beforeSend: function () {
                $('#sltPersona').html("<div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
            },
            error: function () {
                alert("Error: No se puede realizar la busqueda en este momento");
            },
            success: function (data) {
                $("#sltPersona").empty();
                $("#sltPersona").append(data);
                $('select').formSelect();
            }
        })
    })

    $('#txtBusquedaProyecto').keyup(function () {
        consulta = $('#txtBusquedaProyecto').val();
        $.ajax({
            type: "POST",
            url: "../Controllers/ctrl_inventario.php",
            data: "proyecto=" + consulta,
            dataType: "html",
            beforeSend: function () {
                $('#sltProyecto').html("<div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
            },
            error: function () {
                alert("Error: No se puede realizar la busqueda en este momento");
            },
            success: function (data) {
                $("#sltProyecto").empty();
                $("#sltProyecto").append(data);
                $('select').formSelect();
            }
        })
    })
    $('#txtBusquedaEquipo').keyup(function () {
        consulta = $('#txtBusquedaEquipo').val();
        $.ajax({
            type: "POST",
            url: "../Controllers/ctrl_inventario.php",
            data: "equipo=" + consulta,
            dataType: "html",
            beforeSend: function () {
                $('#sltEquipo').html("<div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
            },
            error: function () {
                alert("Error: No se puede realizar la busqueda en este momento");
            },
            success: function (data) {
                $("#sltEquipo").empty();
                $("#sltEquipo").append(data);
                $('select').formSelect();
            }
        })
    })

    $('#txtBusquedaProducto').keyup(function () {
        consulta = $('#txtBusquedaProducto').val();
        $.ajax({
            type: "POST",
            url: "../Controllers/ctrl_inventario.php",
            data: "producto=" + consulta,
            dataType: "html",
            beforeSend: function () {
                $('#sltProducto').html("<div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
            },
            error: function () {
                alert("Error: No se puede realizar la busqueda en este momento");
            },
            success: function (data) {
                $("#sltProducto").empty();
                $("#sltProducto").append(data);
                $('select').formSelect();
            }
        })
    })

    $('#btnDescargar').click(function () {
        $('select[required]').css({
            display: 'inline',
            position: 'absolute',
            float: 'left',
            padding: 0,
            margin: 0,
            border: '1px solid rgba(255,255,255,0)',
            height: 0,
            width: 0,
            top: '3em',
            left: '9em'
        });
        $(window).on('load', function () {
            $('.loader').delay(350).fadeOut('slow');
        });
    })


    /** Busqueda de solicitudes específicas */
    $('#txt-search').keypress(function (e) {
        var tecla = (e.keyCode ? e.keyCode : e.wich);
        var url = $('#txt-search').data('url');
        if (tecla == 13) {
            busqueda(url);
        }
    })

    $('table tbody#misSolicitudes').paginathing({
        perPage: 6,
        insertAfter: 'table.responsive-table',
        pageNumbers: false,
    });

    $('#sltFrenteInf').on('change',function(){
        var selectValor = $(this).val();
        if(selectValor !=  ""){
            $('#txtBusquedaProy').prop('disabled', true);
            $('#sltProy').prop('disabled', true);
            $('#sltGestor').prop('disabled', true);
            inicializarCampos();
        }else if(selectValor == ""){
            $('#txtBusquedaProy').prop('disabled', false);
            $('#sltProy').prop('disabled', false);
            $('#sltGestor').prop('disabled', false);
            inicializarCampos();
        }
    });
    
    $('#sltGestor').on('change',function(){
        var selectValor = $(this).val();
        if(selectValor !=  ""){
            $('#txtBusquedaProy').prop('disabled', true);
            $('#sltProy').prop('disabled', true);
            $('#sltFrenteInf').prop('disabled', true);
            inicializarCampos();
        }else if(selectValor == ""){
            $('#txtBusquedaProy').prop('disabled', false);
            $('#sltProy').prop('disabled', false);
            $('#sltFrenteInf').prop('disabled', false);
            inicializarCampos();
        }
    });

    $('#txtBusquedaProy').keyup('change',function(){
        var selectValor = $(this).val();
        if (selectValor !=  "") {
            $('#sltFrenteInf').prop('disabled', true)
            $('#sltGestor').prop('disabled', true)
            inicializarCampos();
        } else if (selectValor == "") {
            $('#sltFrenteInf').prop('disabled', false)
            $('#sltGestor').prop('disabled', false)
            inicializarCampos();
            $('#sltProy').removeAttr('required')
        }
        
    });
    
    $('#txtDesc').mouseenter(function(){
        $('#txtDesc').removeClass("truncate");
        
    });

    $('#txtDesc').mouseleave(function(){
        $('#txtDesc').addClass("truncate");
        
    });
    $('#fechaA')

    $('.fixed-action-btn').floatingActionButton();

});

function cerrar() {
    $.ajax({
        beforeSend: function () {
            $('#fondo').addClass("hide");
            $('#editRegistro').addClass("hide");
        }
    });
}; 

function editarRegistro(idTiempo) {
    $.ajax({
        type: "POST",
        url: '../Controllers/ctrl_regtime.php',
        data: {
            idTiempo: idTiempo
        },
        beforeSend: function () {
            $('#fondo').removeClass("hide");
            $('#editRegistro').removeClass("hide");
        },
        success: function (data) {
            $('#editRegistro').html(data);
            $('#editRegistro').slideDown("slow");
            $('#sltFaseEdit').formSelect();
            inicializarCampos();
        }
    });
};

function ocultarEditar(id) {
    let path = window.location.pathname;
    let comp = path.split("/")
    var mensaje = confirm("¿Esta seguro de eliminar el Registro?");
    //Detectamos si el usuario acepto el mensaje
    if (mensaje) {
        $.ajax({
            type: "POST",
            url: '../Controllers/ctrl_regtime.php',
            data: {
                idDeletTiempo: id
            },
            success: function (data) {
                alert(data);
                if (comp.pop() == 'infMisTiempos.php') {
                    buscar('../Controllers/ctrl_infMisTiempos.php');
                } else{
                    location.reload();
                }
            }
        });
    }

}

function confirPassword(val1, val2, boton) {
    val1 = $(val1).val();
    val2 = $(val2).val();
    if (val1 == val2) {
        $("#passText").addClass("hide");
        $(boton).removeClass("disabled");
    } else {
        $("#passText").removeClass("hide");
        $(boton).addClass("disabled");
    }
}

function checkbox(check) {
    var checkActive = $(check);
    var checked = $(check).attr('data-checked');
    if (checked == 'false') {
        $(checkActive).attr('checked', 'checked');
        $('#txtpassPer1, #txtpass1Per1').removeAttr('disabled');
        $(checkActive).attr('data-checked', 'true');
        $('#passwords').removeClass('hide');
        $("#btnActPassword").addClass("disabled");
    } else if (checked == 'true') {
        $(checkActive).removeAttr('checked');
        $('#txtpassPer1, #txtpass1Per1').attr('disabled', true);
        $('#txtpassPer1, #txtpass1Per1').removeClass('validate');
        $(checkActive).attr('data-checked', 'false');
        $('#passwords').addClass('hide');
        $("#btnActPassword").removeClass("disabled");

    }

}

function busqueda(url) {
    $.ajax({
        type: "POST",
        url: url,
        data: $("#txt-search").serialize(),
        beforeSend: function () {
            $('#div_dinamico').html("<div class='center-align'><div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div></div>");
        },
        success: function (data) {
            inicializarCampos();
            $('#div_dinamico').html(data);
            $('#div_dinamico').slideDown("slow");
            $('table tbody').paginathing({
                perPage: 6,
                insertAfter: 'table.responsive-table',
                pageNumbers: false,
            });
        }
    });
    return false;
}

function busquedaMultiple(url) {
    $.ajax({
        type: "POST",
        url: url,
        data: $("#terminarSerPro").serialize(),
        beforeSend: function () {
            $('#div_dinamico').html("<div class='center-align'><div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div></div>");
        },
        success: function (data) {
            $('#div_dinamico').empty();
            $('#div_dinamico').html(data);
            $('#div_dinamico').slideDown("slow");
            var tooltips = $('.tooltipped');
            if (tooltips.length != 0) {
                $('.tooltipped').tooltip();
            };
        }
    });

}

function busquedaUsu(url) {
    $.ajax({
        type: "POST",
        url: url,
        data: $("#txt-usu").serialize(),
        beforeSend: function () {
            $('#div_usuario').html("<div class='center-align'><div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div></div>");
        },
        success: function (data) {
            $('#div_usuario').html(data);
            $('#div_usuario').slideDown("slow");
            $("select").formSelect();

        }
    });
    return false;
}

function pruebas(url) {
    $.ajax({
        type: "POST",
        url: url,
        data: $("#txt-search").serialize(),
        beforeSend: function () {
            $('#div_dinamico').html("<div class='center-align'><div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div></div>");
        },
        success: function (data) {
            $('#div_dinamico').html(data);
            $('#div_dinamico').slideDown("slow");
            $('table tbody').paginathing({
                perPage: 6,
                insertAfter: 'table.responsive-table',
                pageNumbers: false,
            });
        }
    });
}

function cargarResAgenda(fecha, elem) {
    let letra = elem.find("h6");
    let path = window.location.pathname;
    let comp = path.split("/")
    let url = "";
    if (comp.pop() == 'agenda.php') {
        url = '../Controllers/ctrl_agenda.php';
    } else if (comp.pop() == 'agendaAdmin.php') {
        url = '../Controllers/ctrl_agendaAdmin.php';
    }
    let idper = $('#sltPersona').val();
    $.ajax({
        type: "POST",
        url: url,
        data: {
            fech: fecha,
            idper: idper
        },
        beforeSend: function () {
            $('#div_dinamico1').html("<div class='center-align'><div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div></div>");
        },
        success: function (data) {
            $.each($('.fechPer'), function () {
                let color2 = $(this).hasClass('w');
                let color2T = $(this).hasClass('v');
                if (color2) {
                    $(this).removeClass(' blue  darken-4').addClass('teal lighten-5');
                    $(this).find('h6').removeClass('white-text').addClass('black-text');
                } else if (color2T) {
                    $(this).removeClass(' blue  darken-4').addClass('teal darken-3');
                } else {
                    $(this).removeClass(' blue  darken-4').addClass('teal accent-4');
                }
            });
            let color = elem.hasClass('lighten-5');
            let colorT = elem.hasClass('darken-3');
            if (color) {
                elem.removeClass('teal lighten-5').addClass(' blue  darken-4 w');
                letra.removeClass('black-text').addClass('white-text');
            } else if (colorT){
                elem.removeClass('teal darken-3').addClass(' blue  darken-4 v');
            } else{
                elem.removeClass('teal accent-4').addClass(' blue  darken-4 ');
            }
            $('#fechaA').text(fecha);
            $('#fechaDia').val(fecha);
            $('#div_dinamico1').html(data);
            $('#div_dinamico').slideDown("slow");
            planeacionDia(fecha, idper, url);
            ifRegistrarTiempo(fecha)
            inicializarCampos();
        }
    });
};
function ifRegistrarTiempo(fecha){
    $.ajax({
        type: "POST",
        url: '../Controllers/ctrl_agenda.php',
        data: {
            dateRegistrado: fecha,
        },
        success: function (data) {
            if (data==1){
                $('#btnRegTiempo').removeClass('disabled');  
            } else if (data==0) {
                $('#btnRegTiempo').addClass('disabled');
            }
        }
    });

}
function planeacionDia(fecha, idper, url){
    var string = "";
    $.ajax({
        type: "POST",
        url: url,
        data: {
            fech2: fecha,
            idper: idper
        },
        beforeSend: function () {
            $('#div_dinamico2').html("<div class='center-align'><div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div></div>");
        },
        success: function (data) {
            let tasks = JSON.parse(data);
            $('#div_dinamico2').hide();
            tasks.forEach(task => {
                string += `         <div class="card">
                                        <div class="card-content ">
                                            <div class="row" style="margin-bottom: 0;">
                                            <form id="formAgenda${task.cont}" action="../Controllers/ctrl_agenda.php" method="post">
                                                <input id="idAgenda" name="idAgenda" value="${task.idAgenda}" type="hidden">
                                                <input id="idSol" name="idSol" value="${task.idSol}" type="hidden">
                                                <input id="fecha" name="fecha" value="${task.fecha}" type="hidden">
                                                <div class="col l12 m12 s12">
                                                    <h6>${task.codProy} -- ${task.nombreProy}</h6>
                                                </div>
                                                <div class="input-field col l12 m12 s12">
                                                    <p class="left-align"><strong>P${task.idSol}:</strong> ${task.descripcionSol}</p>
                                                </div>
                                                <div class="col l2 m2 s12">
                                                    <p class="left-align"><strong>Tiempo:</strong></p>
                                                </div>
                                                <div class="input-field col l2 m2 s12">
                                                    <input type="number" class="validate" name="horas" value="${task.horaAgenda}" ${task.type}>
                                                    <label for="horas" class="active">Horas</label>
                                                </div>
                                                <div class="input-field col l2 m2 s12">
                                                    <input type="number" class="validate" name="min" value="${task.minAgenda}" ${task.type}>
                                                    <label for="min" class="active">Minutos</label>
                                                </div>
                                                <div class="input-field col l12 m12 s12">
                                                    <textarea name="obser" class="materialize-textarea" ${task.type}>${task.notaAgenda}</textarea>
                                                    <label for="obser" class="active"><strong>Actividad:</strong></label>
                                                </div>`;
                            if (idper == null){
                                string += `     <div class="col l5 m5 s12 left-align">
                                                    <p>
                                                        <label>
                                                            <input type="checkbox" id="checkFechaC${task.cont}" name="checkFechaC${task.cont}" class="filled-in" data-checked="false" onclick ="checkFechaC(\'#checkFechaC${task.cont}\',\'${task.cont}\')">
                                                            <span>Mover a otro día</span>
                                                        </label>
                                                    <p>
                                                </div>
                                                <div class="input-field col l7 m7 s12">
                                                    <input id="fechaCambio${task.cont}" name="fechaCambio" type="text" class="datepicker" placeholder="Seleccione" disabled>
                                                    <label for="fechaCambio${task.cont}" class="active">Seleccione la nueva fecha</label>
                                                </div>`;
                            }
                            string += `
                                ${task.agenda}
                                ${task.text}
                                            </form>
                                        </div>
                                    </div>
                                </div>
               `;
            });
            $('#div_dinamico2').html(string);
            $('#div_dinamico2').slideDown("slow");
            $('.tooltipped').tooltip();
            $('.collapsible').collapsible();
        }
    });


};

function modalAgenda(fecha){
    var string = "";
    $.ajax({
        type: "POST",
        url: '../Controllers/ctrl_agenda.php',
        data: {
            fech2: fecha
        },
        beforeSend: function () {
            $('#modalA').html("<div class='center-align'><div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div></div>");
        },
        success: function (data) {
            if (data !="[]"){
                let tasks = JSON.parse(data);     
                    string += `
                    <input id="fecha" name="fecha" value="`+fecha+`" type="hidden">
                    <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Producto/Servicio</th>
                            <th>Descripción del Producto/Servicio</th>
                            <th>Actividad</th>
                            <th>Tiempo</th>
                            <th>Fase</th>
                            <th>Registrar</th>
                        </tr>
                    </thead>
                    <tbody>`;
                    tasks.forEach(task => {
                        if (task.estAgenda == 1){
                        string += `
                            <tr>
                            <td>P${task.idSol}</td>
                                <td><p class="truncate">${task.descripcionSol}</p></td>
                                <td><p class="truncate">${task.notaAgenda}</p></td>
                                <td>${task.horaAgenda} h ${task.minAgenda} m </td>
                                <td>${task.fase}</td>
                                <td><p>
                                <label>
                                <input type="checkbox" id="checkReg${task.cont}" name="idAgenda[]" value="${task.idAgenda}"  class="filled-in"  data-checked="false" onclick= "checkRegistarT('#checkReg${task.cont}')" />
                                <span></span>
                                </label></td>
                            </tr>`;
                        }
                    });
                    string += `
                    </tbody>
                    </table>`;
                    $('#modalA').html(string);
                    inicializarCampos();
                
            } else{
                $('#modalA').html("No se ha registrado ningun Producto/Servicio para este día");
            }
        }
    });
};

function checkRegistarT(check) {
    var checkActive = $(check);
    var checked = $(check).attr('data-checked');
    if (checked == 'false') {
        $(checkActive).attr('checked', 'checked');
        $(check).parent().parent().parent().siblings().find("div select").removeAttr("disabled").attr("required", "required");
        inicializarCampos();
        $(checkActive).attr('data-checked', 'true');
    } else if (checked == 'true') {
        $(checkActive).removeAttr('checked');
        $(check).parent().parent().parent().siblings().find("div select").attr("disabled", "disabled");
        $(checkActive).attr('data-checked', 'false');
        inicializarCampos();
    }
}

function regTiemposModalA(){
    var checked = $('#modalAgenda').find('.filled-in');
    var checkedL = $('#modalAgenda').find('.filled-in').length;
    var cont = 0;
    $("#formModalA").submit(function(e){
        e.preventDefault();
    });
    $.each($('#modalAgenda .filled-in'), function () {  
        if($(this).attr('data-checked')== 'false'){
            cont +=1;
        }
    });
    if (cont === checkedL) {
        M.toast({ html: "Seleccione mínimo un producto/servicio"},3000);
        
        /* inicializarCampos(); */
    } else {
        $.ajax({
        type: "POST",
        url: '../Controllers/ctrl_agenda.php',
        data: $('#formModalA').serialize(),
        success: function (response) {
            let data = JSON.parse(response)
            data.forEach( task => {
                M.toast({ html: `${task.mensaje}`, displayLength: 2000, completeCallback: function(){window.location="../Views/agenda.php?hoy="+`${task.fecha}`}});
                }
            );
        }
        });
        /* inicializarCampos(); */
    }


};

function agendaDia(fecha){
    window.location="../Views/agenda.php?hoy="+fecha;
};

function seleccionarDia(hoy){
    $.each($('.fechPer'), function () {  
        let fecha = $(this).find('h6').text();   
        if(  fecha == hoy ){
            let elem = $(this);
            cargarResAgenda(hoy, elem)
        }
    });
}
/*------- Duplicar div de proyecto en Agenda -------*/

function duplicarDiv() {
    let form = $('#proyAgend');
    let cont = form.find('.conteo').length + 1;
    let url = '../Controllers/ctrl_agenda.php';
    $.ajax({
        type: "POST",
        url: url,
        data: {
            cantidad: cont
        },
        success: function (data) {
            form.append(data);
            inicializarCampos();
        }
    });
};

/*------- Eliminar div de proyecto en Agenda -------*/
function eliminarDiv(cont) {
    let div = "#cardPro" + cont;
    $(div).remove();
    inicializarCampos();
};

function cargaSolicitudesProy(elem1, dir, destino, long) {
    $.ajax({
        type: "POST",
        url: dir,
        data: {
            proyecto: $(elem1).val(),
            long: long,
        },
        beforeSend: function () {
            $(destino).html("<div class='center-align'><div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div></div>");
        },
        success: function (data) {
            $(destino).html(data);
            $("select").formSelect();
            inicializarCampos();
        }
    })
}
$("select[name='sltPersona']").on("change", () => {
    cargaPanelUsu();
});

function cargaPanelUsu() {
    let elem = $('#sltPersona');
    let persona = elem.val();
    $.ajax({
        type: "POST",
        url: '../Controllers/ctrl_agendaAdmin.php',
        data: {
            idper: persona,
            cod: 3,
        },
        success: function (data) {
            $("#div_dinaPanel").html(data);
            inicializarCampos();
        }
    })
}

/*------- Inicio JS para boton actualizar -------*/
function actualiza(val, url) {
    $('#val').val(val);
    $.ajax({
        type: "POST",
        url: url,
        data: $("#actForm").serialize(),
        success: function () {}
    });
    return false;
}
/*------- Fin JS para boton actualizar -------*/
/*------- Inicio JS para boton suprimir -------*/
function suprimir(value, url) {
    $('#val').val(value);
    $.ajax({
        type: "POST",
        url: url,
        data: $("#actForm").serialize()
    });
    return false;
}
/*------- Fin JS para boton suprimir -------*/

/*------- Inicio envio de datos a modal -------*/
function envioData(valor, dir) {
    $('.modal-content').load(dir + "?id=" + valor, function () {
        $('#cod').val(valor);
        textbus = $("#txt-search").val();
        $('#valbus').val(textbus);
        $("select").formSelect();
        inicializarCampos();
        var textareas = $(".textarea");
        if (textareas.length != 0) {
            M.textareaAutoResize($(".textarea"));
        }
    });
}
/*------- Fin envio de datos a modal -------*/

/*------- Fin envio de datos a modal -------*/
function cargaSelect(elem, dir, destino) {
    $.ajax({
        type: "POST",
        url: dir,
        data: $(elem).serialize(),
        beforeSend: function () {
            $(destino).html("<div class='center-align'><div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div></div>");
        },
        success: function (data) {
            $(destino).html(data);
            $("select").formSelect();
            $(".datepicker").datepicker();
        }
    })
}

function cargaSelectTipProduc(elem1, elem2, dir, destino) {
    $.ajax({
        type: "POST",
        url: dir,
        data: {
            dato1: $(elem1).val(),
            dato2: elem2,
        },
        beforeSend: function () {
            $(destino).html("<div class='center-align'><div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div></div>");
        },
        success: function (data) {
            $(destino).html(data);
            $("select").formSelect();
            $(".datepicker").datepicker();
        }
    })
}

function validar(i) {
    var hrsPresup = $("input[name='tiempoDisponible[" + i + "]']").val();
    var hrsAsig = parseInt($("input[name='horasInvertir[" + i + "]']").val());
    var minAsig = parseInt($("input[name='minutosInvertir[" + i + "]']").val());
    var totalHrsDisponible = $('#txtHorasMes').val();
    var totalDisponible = parseFloat(totalHrsDisponible * 60);
    var totalHrsAsignado = 0;
    var totalMinAsignado = 0;
    $('.hrsInvertir').each(function () {
        if (!isNaN($(this).val())) {
            totalHrsAsignado += Number($(this).val());
        }
    })
    $('.minInvertir').each(function () {
        if (!isNaN($(this).val())) {
            totalMinAsignado += Number($(this).val());
        }
    })
    totalAsignado = (totalHrsAsignado * 60) + totalMinAsignado;

    var tiempoDisponible = totalDisponible - totalAsignado;

    if (!isNaN(hrsAsig) && !isNaN(minAsig)) {
        if (tiempoDisponible >= 0) {
            M.toast({
                html: "<i class='small material-icons white-text'>done</i><p class='white-text'>  Horas disponibles para asignar: <strong>" + (tiempoDisponible / 60).toFixed(2) + "<strong></p>"
            });
        } else {
            M.toast({
                html: "<i class='small material-icons red-text'>error</i><p class='red-text'>  Se ha excedido en: <strong>" + Math.abs((tiempoDisponible / 60).toFixed(2)) + " horas.<strong></p>"
            });
        }

    }

    if (isNaN(hrsAsig)) {
        hrsAsig = 0;
    }
    if (isNaN(minAsig)) {
        minAsig = 0;
    }
    hrsPresup = hrsPresup.replace(" hrs", "");
    var minPresup = hrsPresup * 60;
    var minTotales = (hrsAsig * 60) + minAsig;
    if (minTotales > minPresup) {
        $('#horasInvertir' + i + '').addClass("alert-input");
        $('#minutosInvertir' + i + '').addClass("alert-input");
    } else {
        $('#horasInvertir' + i + '').removeClass("alert-input");
        $('#minutosInvertir' + i + '').removeClass("alert-input");
    }
}

function calcula() {
    cot = $('#txtValCotizacion').val().replace(/[($)\s\._\-]+/g, '');
    rec = $('#txtTotalRecurso').val();
    dif = cot - rec;
    $('#txtDiferencia').val("$ " + (cot - rec).toLocaleString("es-CO"));
}

function format(val) {
    num = $(val).val().replace(/\./g, '');
    if (!isNaN(num)) {
        num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1.');
        num = num.split('').reverse().join('').replace(/^[\.]/, '');
        $(val).val(num);
    } else {
        alert("Solo se permite numeración en '$' pesos colombianos");
        $(val).val().replace(/[^\d\.]*/g, '');
    }

}

/**  */
function mostrarInfo(url) {
    $.ajax({
        type: "POST",
        url: url,
        data: $("#frmInfNomina").serialize(),
        success: function (data) {
            $('#div_dinamico').html(data);
            $('#div_dinamico').slideDown("slow");
            $('table tbody').paginathing({
                perPage: 6,
                insertAfter: 'table.responsive-table',
                pageNumbers: false,
            });
        }
    });
    return false;
}

/** Función para enviar los datos de formulario para el informe de P/S */

function buscar(url) {
    $.ajax({
        type: "POST",
        url: url,
        data: $('form').serialize(),
        beforeSend: function () {
            $('#div_dinamico').html("<div class='row'><div class='col l6 m6 s12 offset-l3 offset-m3'><div class='progress'><div class='indeterminate'></div></div><p class='center-align'>Cargando...</p></div></div>");
        },
        success: function (data) {
            $('#div_dinamico').html(data);
            $('#div_dinamico').slideDown("slow");
        }
    });
}

function registrarTiempo(id) {
    var mensaje = confirm("¿Esta seguro de registrar el tiempo?");
    var formT = "formAgenda" + id;
    var newformT = new FormData(document.getElementById(formT));
    newformT.append("cod", "1");
    var newform3 = document.getElementById(formT);
    var fecha = newform3['fecha'].value;
    //Detectamos si el usuario acepto el mensaje
    if (mensaje) {
        $.ajax({
            type: "POST",
            url: '../Controllers/ctrl_agenda.php',
            data: newformT,
            contentType: false,
            processData: false,
            success: function (data) {
                alert(data);
                location.replace("agenda.php?hoy="+fecha);
            }
        });
    }

}

function cancelarAgenda(id) {
    var mensaje = confirm("¿Esta seguro de cancelar la actividad?");
    var form2 = "formAgenda" + id;
    var newform2 = new FormData(document.getElementById(form2));
    var newform3 = document.getElementById(form2);
    var fecha = newform3['fecha'].value;
    newform2.append("cod", "2");
    //Detectamos si el usuario acepto el mensaje
    if (mensaje) {
        $.ajax({
            type: "POST",
            url: '../Controllers/ctrl_agenda.php',
            data: newform2,
            contentType: false,
            processData: false,
            success: function (data) {
                alert(data);
                location.replace("agenda.php?hoy="+fecha);
            }
        });
    }

}
function checkFechaC(check, cod) {
    var checkActive = $(check);
    var checked = $(check).attr('data-checked');
    var fechaCambio = "fechaCambio" + cod;

    if (checked == 'false') {
        $(checkActive).attr('checked', 'checked');
        document.getElementById(fechaCambio).disabled = false;
        $(checkActive).attr('data-checked', 'true');
		inicializarCampos();
    } else if (checked == 'true') {
        $(checkActive).removeAttr('checked');
        document.getElementById(fechaCambio).disabled = true;
        $(checkActive).attr('data-checked', 'false');
		inicializarCampos();
    }

}


function checkProd(check, num, long) {
    var checkActive = $(check);
    var checked = $(check).attr('data-checked');
    var min = "min" + long + "--" + num;
    var horas = "horas" + long + "--" + num;
    var obser = "obser" + long + "--" + num;

    if (checked == 'false') {
        $(checkActive).attr('checked', 'checked');
        document.getElementById(horas).disabled = false;
        document.getElementById(min).disabled = false;
        document.getElementById(obser).disabled = false;
        $(checkActive).attr('data-checked', 'true');
    } else if (checked == 'true') {
        $(checkActive).removeAttr('checked');
        document.getElementById(horas).disabled = true;
        document.getElementById(min).disabled = true;
        document.getElementById(obser).disabled = true;
        $(checkActive).attr('data-checked', 'false');
    }

}

function activar(id, url, nombre) {
    var string = "¿Esta seguro de activar " + nombre + "?";
    var mensaje = confirm(string);
    //Detectamos si el usuario acepto el mensaje
    if (mensaje) {
        $.ajax({
            type: "POST",
            url: url,
            data: {
                cod: id,
            },
            success: function (data) {
                alert(data);
                location.reload();
            }
        });
    }
}

/** Funciones para generar Graficos en el Home */

function chartSolicitud() {
    $.ajax({
        url: '../Controllers/ctrl_home.php',
        type: 'POST',
        data: {
            action: 'Sol'
        },
        success: function (response) {
            let tasks = JSON.parse(response);
            let template1 = [];
            let template2 = [];
            tasks.forEach(task => {
                var labels = template1.push(`${task.estado}`);
                var votes = template2.push(task.cantidad);
            });
            generarChart("chartSolicitud", "doughnut", "", template1, template2)
        }
    });
}

function chartSolicitudIni() {
    $.ajax({
        url: '../Controllers/ctrl_home.php',
        type: 'POST',
        data: {
            action: 'SolIni'
        },
        success: function (response) {
            let tasks = JSON.parse(response);
            let template1 = [];
            let template2 = [];
            tasks.forEach(task => {
                var labels = template1.push(`${task.label}`);
                var votes = template2.push(task.cant);
            });
            generarChart("chartSolIni", "doughnut", "", template1, template2)
        }
    });

}

function chartInventario() {
    $.ajax({
        url: '../Controllers/ctrl_home.php',
        type: 'POST',
        data: {
            action: 'Inve'
        },
        success: function (response) {
            let tasks = JSON.parse(response);
            let template1 = [];
            let template2 = [];
            tasks.forEach(task => {
                var labels = template1.push(`${task.label}`);
                var votes = template2.push(task.cant);
            });
            generarChart("chartInv", "doughnut", "", template1, template2)
        }
    });
}

function chartCotizacion() {
    $.ajax({
        url: '../Controllers/ctrl_home.php',
        type: 'POST',
        data: {
            action: 'Coti'
        },
        success: function (response) {
            let tasks = JSON.parse(response);
            let template1 = [];
            let template2 = [];
            tasks.forEach(task => {
                var labels = template1.push(`${task.label}`);
                var votes = template2.push(task.cant);
            });
            generarChart("chartCot", "doughnut", "", template1, template2)
        }
    });
}

function chartTiempo() {
    $.ajax({
        url: '../Controllers/ctrl_home.php',
        type: 'POST',
        data: {
            action: 'Tiem'
        },
        success: function (response) {
            let tasks = JSON.parse(response);
            let template1 = [];
            let template2 = [];
            tasks.forEach(task => {
                var labels = template1.push(`${task.label}`);
                var votes = template2.push(task.cant);
            });
            generarChart("chartTiempo", "horizontalBar", "Tiempo", template1, template2);
        }
    });
}

function chartProyecto() {
    $.ajax({
        url: '../Controllers/ctrl_home.php',
        type: 'POST',
        data: {
            action: 'Proy'
        },
        success: function (response) {
            let tasks = JSON.parse(response);
            let template1 = [];
            let template2 = [];
            let template3 = [];
            tasks.forEach(task => {
                var proy = template1.push(`${task.proyecto}`);
                var gast = template2.push(task.ejecutado);
                var sobra = template3.push(task.presupuesto);
            });
            generarChart3Template("chartProyecto", "bar", template1, template2, template3);
        }
    });
}


function generarChart(elem, tipo, label, template1, template2) {
    switch (tipo) {
        case 'doughnut':
            var dat = {
                labels: template1,
                datasets: [{
                    data: template2,
                    backgroundColor: ['#3f9c96', '#444344', '#8cb821', '#266669', '#7da3a6', '#f5e88a'  ]
                                  }]
            };
            var option = {
                legend: {
                    position: 'right'
                }
            };
            break;
        case 'line':
            var color = '#3f9c96';
            var dat = {
                labels: template1,
                datasets: [{
                    data: template2,
                    backgroundColor: color,
                    borderColor: color
                }]
            };
            var option = {
                legend: {
                    position: 'top'
                }
            };
            break;
        case 'horizontalBar':
            var dat = {
                labels: [label],
                datasets: [{
                        label: template1[0],
                        data: [template2[0]],
                        stack: 'Stack 0',
                        backgroundColor: [
                            '#266669'

                        ]
                    },
                    {
                        label: template1[1],
                        data: [template2[1]],
                        stack: 'Stack 0',
                        backgroundColor: [
                            '#f5e88a'

                        ]
                    }
                ]
            };
            var option = {

                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true
                    }
                }
            };
            break;
    }
    var config = {
        type: tipo,
        data: dat,
        options: option
    };
    var ctx = document.getElementById(elem);
    var ctx = document.getElementById(elem).getContext('2d');
    var chart = new Chart(ctx, config);
}

function generarChart3Template(elem, tipo, template1, template2, template3) {
    var colorPre = '#3f9c96';
    var colorEje = '#8cb821';
    var config = {
        type: tipo,
        data: {
            labels: template1,
            datasets: [{
                    label: 'Ejecutado',
                    data: template2,
                    stack: 'Stack 1',
                    backgroundColor: [
                        colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje, colorEje
                    ]
                },
                {
                    label: 'Presupuesto',
                    data: template3,
                    stack: 'Stack 2',
                    backgroundColor: [
                        colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre, colorPre
                    ]
                }
            ]
        },
        options: {
            legend: {
                display: false
            },
            tooltips: {
                mode: 'index',
                intersect: false
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true
                }
            }
        }
    };
    var ctx = document.getElementById(elem);
    var ctx = document.getElementById(elem).getContext('2d');
    var chart = new Chart(ctx, config);
}



/**vista visitantes */

function duplicarDivVis() {
    let url = '../Controllers/ctrl_visitante.php';
    let form = $('#visitantes');
    let cont = (form.find('.cardVis').length) + 1;
    $.ajax({
        type: "POST",
        url: url,
        data: {
            cantidad: cont
        },
        success: function (data) {
            $('.cardVis:last-child').after(data);
            $('select').formSelect();
            inicializarCampos();
        }
    });
};

function eliminarDivVis(cont) {
    let div = "#cardVis" + cont;
    $(div).remove();
    inicializarCampos();
};

function ocultardivVisit() {
    $("#loaderVisit").addClass('hide')
}

function mostrardivVisit() {
    $('#visitantesid').submit(function(e){
        return false;
    });
    $("#loaderVisit").removeClass('hide');
    $.ajax({
        type: "POST",
        url: '../Controllers/ctrl_visitante.php',
        data: $("#visitantesid").serialize(),
        success: function (data) {
            $('#loaderVisit').html(data)     
            setTimeout(function(){ location.reload()}, 10000);
        }
    });
}

function actTiempo(){
    $("#editTiempo").submit(function(e){
        e.preventDefault();
    });
    $.ajax({
        type: "POST",
        url: '../Controllers/ctrl_infMisTiempos.php',
        data: $("#editTiempo").serialize(),
        success: function (data) {
            alert(data);
            buscar('../Controllers/ctrl_infMisTiempos.php');
            $("#modalinfTiempos").modal('close')
        }
    });
}
function actSolEsp(){
    $("#actFormSolEs").submit(function(e){
        e.preventDefault();
    });
    $.ajax({
        type: "POST",
        url: '../Controllers/ctrl_solicitudEspecifica.php',
        data: $("#actFormSolEs").serialize(),
        success: function (data) {
            alert(data);
            buscarEst('../Controllers/ctrl_solicitudEspecifica.php', $("#valbus").val());
            $("#modalSolicitudEspecifica").modal('close')
        }
    });
}
function cancelarSolicitudEspecifica() {
    $("#actFormSolEs").submit(function(e){
        e.preventDefault();
    });
    $.ajax({
        type: "POST",
        url: '../Controllers/ctrl_solicitudEspecifica.php',
        data: {
            idSolicitud: $('#cod').val(),
            accion: 'cancelar'
        },
        success: function (data) {
            alert(data);
            buscarEst('../Controllers/ctrl_solicitudEspecifica.php', $("#valbus").val());
            $("#modalSolicitudEspecifica").modal('close')
        }
    });
}
function eliminarSolicitudEspecifica() {
    $("#actFormSolEs").submit(function(e){
        e.preventDefault();
    });
    $.ajax({
        type: "POST",
        url: '../Controllers/ctrl_solicitudEspecifica.php',
        data: {
            idSolicitud: $('#cod').val(),
            accion: 'eliminar'
        },
        success: function (data) {
            alert(data);
            buscarEst('../Controllers/ctrl_solicitudEspecifica.php', $("#valbus").val());
            $("#modalSolicitudEspecifica").modal('close')
        }
    });
}
function buscarEst(url,cod) {
    $.ajax({
        type: "POST",
        url: url,
        data: {
            'txt-search': cod
        },
        beforeSend: function () {
            $('#div_dinamico').html("<div class='row'><div class='col l6 m6 s12 offset-l3 offset-m3'><div class='progress'><div class='indeterminate'></div></div><p class='center-align'>Cargando...</p></div></div>");
        },
        success: function (data) {
            $('#div_dinamico').html(data);
            $('#div_dinamico').slideDown("slow");
        }
    });
}

/** Función para inicializar los campos de materialize */
function inicializarCampos() {

    if ($('select').length > 0) {
        $('select').formSelect();
    }

    var collapsibles = $('.collapsible');
    if (collapsibles.length != 0) {
        $('.collapsible').collapsible();
    }

    var tabs = $('.tabs');
    if (tabs.length != 0) {
        $('.tabs').tabs();
        //var active = $('.tabs .active').attr('href');
        //$('.tabs-content ' + active).show();
    }

    var selects = $('.collapsible');
    if (selects.length != 0) {
        $('select').formSelect();
    }

    var select = $('select');
    if (select.length != 0) {
        $('select').formSelect();
    }

    var textareas = $(".textarea");
    if (textareas.length != 0) {
        M.textareaAutoResize($(".textarea"));
    }

    var datepickers = $(".datepicker");
    if (datepickers.length != 0) {
        $('.datepicker').datepicker();

    }

    var tooltips = $('.tooltipped');
    if (tooltips.length != 0) {
        $('.tooltipped').tooltip();
    }

    $('.sidenav').sidenav();

}

function actualizaEstadoProductoServicio (idProductoServicio, idEstado, dir) {
    $.ajax({
        type: "POST",
        url: dir,
        data: {
            productoServicio: idProductoServicio,
            estado: $(idEstado).val(),
            actualizarEstado: 1
        },
        success: function (data) {
            M.toast({html: data, classes: 'rounded'});
        }
    })
}

function addSltArea () {
    let selects = $("#multiselect").find('select');
    let elem = $(selects[0]).data('elem');
    elem++;
    $(selects[0]).data('elem',elem);

    $.ajax({
        type:       "POST",
        url:        "../Controllers/ctrl_proyecto.php",
        data:       { addSltAreaElem: elem },
        success:    function (data) {
            $('#multiselect').append(data);
            inicializarCampos();
        }
    })
}

function removeSltArea (element, idArea) {
    if (idArea == null) {
        $(element).parent().parent().remove();
    } else {
        var idProy = $('#cod').val();
        $.ajax({
            type:       "POST",
            url:        "../Controllers/ctrl_proyecto.php",
            data:       { removeSltAreaElem: idArea, areaIdProy: idProy },
            success:    function (data) {
                let datos = JSON.parse(data);
                if (datos[0] == 'Correcto') {
                    $(element).parent().parent().remove();
                    M.toast({html: datos[1]})
                } else {
                    M.toast({html: datos[1]})
                }
            }
        })
    }
}