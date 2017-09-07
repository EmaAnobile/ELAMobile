$(document).ready(function () {
    $('#boton-guardar').click(function (e) {
        e.preventDefault();

        var cantidadUsuarios = $('#usuarios option:selected').length;
        if (cantidadUsuarios <= 0)
        {
            alert('Debe seleccionar al menos un usuario')
            return;
        }

        var cantidadLicencias = $('#tipo-licencia option:selected').data('cantidad');

        if (cantidadLicencias < cantidadUsuarios) {
            alert('Dispone solo de ' + cantidadLicencias + ' licencias de ese tipo' +
                    ' y esta intentando asignar ' + cantidadUsuarios + ' usuarios')
            return;
        }
        $('#formulario').submit()
    })
})