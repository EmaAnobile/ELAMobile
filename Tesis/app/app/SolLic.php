<?php

session_start();

//Si no se seteo o no existe el usuario
if (!isset($_SESSION['id_usuario']) or 
           $_SESSION['id_usuario'] == -1) 
{

  header("location: LoginSolLic.html");

}

?>

<!-- SolLic.html  -->
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/controller.js"></script>

<!-- Script solicitado para generar la tabla -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<title>ELAMobile - Solicitud Licencias</title>

</head>

  <body>

<nav role="navigation" class="navbar navbar-default" >

        <div class="navbar-header">
            <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="#" class="navbar-brand">ELAMobile</a>
        </div>
        
         <div id="navbarCollapse" >
            <ul class="nav navbar-nav">
                <li class="active"><a href="">Inicio</a></li>
                <li class="active"><a href="">Nosotros</a></li>
            </ul>              
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b><span class="glyphicon glyphicon glyphicon-cog"></span> Configuración</b></a>
                    <ul id="login-dp" class="dropdown-menu">
                        <li>       
                            <a href="LoginSolLic.html"> <span class="glyphicon glyphicon-off"></span> Cerrar Sesión</a>
                        </li>
                    </ul>
            </li>
        </ul> 
</nav>

<!--Cabecera-->

    <header>
              
          <div class="row">
            
             <div class="col-xs-12 col-md-12">
                </br>
                <center><h4>Solicitud de Licencias</h4></center>

             </div>

          </div>

    </header>

<!-- Tabla para solicitar licencias -->

          <div class="table-responsive">          
          <table class="table">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Usuario</th>
                <th></th>
                <th>&nbsp &nbspCantidad</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td> 
                <div class="col-xs-15">
                <input id="nombre" type="text" class="form-control input-sm"  value="<?php echo utf8_encode($_SESSION['datos_usuario']['nombre']);?>" readonly>   
                </div>
                </td>
                <td>
                <div class="col-xs-15">
                <input id="apellido" type="text" class="form-control input-sm" value="<?php echo utf8_encode($_SESSION['datos_usuario']['apellido']);?>" readonly >   
                </div>
                </td>
                <td>
                <div class="col-xs-15">
                <input id="tipo" type="text" class="form-control input-sm" value="<?php echo $_SESSION['datos_usuario']['tipo'];?>" readonly>   
                </div>
                </td>
                <td>
                <div class="col-xs-15">
                <!--<input id="cant" type="text" class="form-control input-sm" value="<?php echo $_SESSION['datos_usuario']['tipo'];?>" readonly>   -->
                </div>
                </td>
                <td>
                <div class="col-xs-5">                
                <input id="cant" type="number" min="0"  class="form-control input-sm" value="" autofocus=""> 
                </div>   
                </td>
              </tr>
            </tbody>
          </table>
          </div>

        <div class="row">
             </br>
            <div class="col-xs-12 col-sm-3 col-xs-offset-6 col-md-offset-10">
        
               <div class='define'>
                <input id="Confirmar" type="button" class="btn btn-primary btn-sm" value="Confirmar"  />
                <input id="Cancelar"  type="button" class="btn btn-primary btn-sm" value="Cancelar"  />
              </div>

            </div>
        
        </div>
 </body>     