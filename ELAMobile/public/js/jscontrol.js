
function SelGroup() {
    $.ajax ({
      type: 'GET',
      url: 'http://localhost:4567/index',
      data: $(this).serialize(),
      //Enviamos para mostrar un mensaje al precionar el botón
      success: function (data) {
        console.log('llego');
        if (data == "true") {
          alert("Mamaa Mia!!")
        }
      };
    });
}
