$(window).on('load', function () {
    $('.loader').delay(350).fadeOut('slow');
});

$('.modal').on('load', function () {
    inicializarCampos();
});

$('.modal').on('change', function () {
    $('select').formSelect();
    $('select').addClass('hide');
    inicializarCampos();
});

$(document).ready(function () {

    inicializarCampos();


    $('.tabs').tabs();

    $('.collapsible').collapsible();

    $('.sidenav').sidenav();

    $('select').formSelect();

    if ($(".dropdown-trigger")) {
        $(".dropdown-trigger").dropdown();
    }

    $('.modal').modal({
        onOpenEnd: function () {
            inicializarCampos();
        }
    });

    $('.collapsible').collapsible({
        onOpenEnd: function () {
            M.textareaAutoResize($(".materialize-textarea"));
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

});

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
        }
    });
};

function cerrar() {
    $.ajax({
        beforeSend: function () {
            $('#fondo').addClass("hide");
            $('#editRegistro').addClass("hide");
        }
    });
};

function ocultarEditar(id) {
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
                location.reload();
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

$('.fechPer').click(function(e){
    let elem = $(this)
    let fecha = elem.find('h6').text();
    let url = '../Controllers/ctrl_agenda.php';
    let idper =$('#sltPersona').val()
    $.ajax({
        type: "POST",
        url: url,
        data: {fech:fecha,
            idper:idper},
        beforeSend: function () {
            $('#div_dinamico').html("<div class='center-align'><div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div></div>");
        },
        success: function (data) {
            $.each($('.fechPer'), function(){
                $(this).removeClass('red').addClass('teal');
            });
            elem.removeClass('teal').addClass('red');
            $('#div_dinamico').html(data);
            $('#div_dinamico').slideDown("slow");
            inicializarCampos();
        }
    });
});

function duplicarDiv(){
    let elem = $(this);
    let form =$('#proyAgend');
    let cont =form.find('.conteo').length +1;
    let url = '../Controllers/ctrl_agenda.php';
    $.ajax({
        type: "POST",
        url: url,
        data: {
            cantidad : cont
        },
        success: function (data) {
            console.log(data);
            form.append(data);
            inicializarCampos();
        }
    });
}; 

function cargaSolicitudesProy(elem1, dir, destino) {
    $.ajax({
        type: "POST",
        url: dir,
        data: {
            proyecto: $(elem1).val()
        },
        beforeSend: function () {
            $(destino).html("<div class='center-align'><div class='preloader-wrapper small active'><div class='spinner-layer spinner-teal-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div></div>");
        },
        success: function (data) {
            $(destino).html(data);
            $("select").formSelect();
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

/** Función para inicializar los campos de materialize */
function inicializarCampos() {

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

}