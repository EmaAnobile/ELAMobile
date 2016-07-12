
function SelGroup() {
    $.ajax ({
      type: 'POST',
      url: 'http://localhost:4567/index',
      data: $(this).serialize(),
      //Enviamos para mostrar un mensaje al precionar el botón
      success: function (data) {
      alert (@id)
      }
    });
};
