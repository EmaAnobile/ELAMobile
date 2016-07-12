
function SelGroup(lv_boton) {
    $.ajax ({
      type: 'POST',
      url: 'http://localhost:4567/index',
      data: $(this).serialize(),
      //Enviamos para mostrar un mensaje al precionar el botón
       success: function (data) {
         var algo='prueba'
      $('#mensaje').html(lv_boton)
      }
// $.get('/index', function(data) {
//    $('#mensaje').html(data);
// })
    });
 };
