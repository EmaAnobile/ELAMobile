//DECLARACIÓN DE VARIABLES GLOBALES
var gv_boton = null;
var gv_tipo = null;
var gv_letra = null;
var gv_user = null;
var gv_pass = null;
var gv_cantidad = null;
var codigo = new Array();
var x = '20';

//Documento Jquery que se ejecuta cuando
//toda la página este cargada en el cliente
$(document).ready(function (data) {
    // Cambiamos el estilo de la letra.   
    $(".Boton_select").toggleClass("h3");

    //Al presionar un boton con el estilo Boton_select
    $(".Boton_select").click(function () {
        // Obtenemos la letra seleccionada.
        procesar_boton($(this));
    });

    //Al presionar el botón voz.
    $("#voz").click(function () {
        // Pisamos el valor con vacío.
        //meSpeak.speak("holaaaa!");
        meSpeak.speak($('#mensaje').val().toLowerCase());
    });

    $('#espacio').click(function () {
        mostrar_texto(" ");
    })

    $('#borrar').click(function () {
        palabraComposite.borrarUltima();
        $('#mensaje').val(palabraComposite.procesar_texto());
    })

    $('#borrar_todo').click(function () {
        palabraComposite.borrarTodo();
        $('#mensaje').val(palabraComposite.procesar_texto());
    })

    $('#cancelar').click(function () {
        inicializar_botones();
    })
    $('#cancelar').hide();
});

function inicializar_botones() {
    $('#cancelar').hide();
    $('#borrar').show();
    $('#borrar_todo').show();
    $('#espacio').show();
        
    $('.Boton_select').html('');

    var keys = Object.keys(grupos);

    for (var i = 0; i < keys.length; i++) {
        var key = keys[i];
        var textos = grupos[key];
        var htmlGrupo = '<p>';
        for (var j = 0; j < textos.length; j++) {
            var texto = textos[j];
            htmlGrupo += '<div class="col-md-4 col-sm-4 col-xs-6">' + texto + '</div>';
        }
        htmlGrupo += '</p>';

        $($('.Boton_select')[i]).html(htmlGrupo);
        $($('.Boton_select')[i]).data('grupo', key);
    }
}

function procesar_boton(objeto) {
    var idGrupo = $(objeto).data('grupo');

    if (idGrupo != undefined) {
        $('.Boton_select').html('')
        $('.Boton_select').removeAttr('data-grupo');
        $('.Boton_select').removeData('grupo');
        $('#cancelar').show();
        $('#borrar').hide();
        $('#borrar_todo').hide();
        $('#espacio').hide();        
        procesar_grupo(grupos[idGrupo]);
    } else {
        mostrar_texto($(objeto).find('.texto').html());
        inicializar_botones();
    }
}

//Función para distribuir las letras 
//del grupo en cada uno de los circulos
function procesar_grupo(listadoTexto) {
    if (listadoTexto == undefined)
        return;

    for (i = 0; i < listadoTexto.length; i++) {
        var texto = listadoTexto[i];
        var div = $('<p><div class="col-md-4 col-md-push-4 texto">' + texto + '</div></p>')
        $($('.Boton_select')[i]).html(div);
    }
}

function mostrar_texto(texto) {
    palabraComposite.formar_palabra(new Letra(texto));
    $('#mensaje').val(palabraComposite.procesar_texto());
}