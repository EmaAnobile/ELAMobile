<?php

session_start();

//Si no se seteo o no existe el usuario
if (!isset($_SESSION['id_usuario']) or 
           $_SESSION['id_usuario'] == -1) 
{

  header("location: SolLic.php");

}

date_default_timezone_set('America/Argentina/Buenos_Aires');

$gv_cantidad = $_GET["cantidad"];

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

<script src="assets/js/custom.js"></script>
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
        
         <!--<div id="navbarCollapse" >
            <ul class="nav navbar-nav">
                <li class="active"><a href="">Inicio</a></li>
                <li class="active"><a href="">Nosotros</a></li>
            </ul>              
        </div>-->

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
                <center><h4>Licencias Solicitadas</h4></center>

             </div>

          </div>

    </header>



<body  onload="nobackbutton();"  >
  
   Fecha: <?php echo date("d/m/Y") ?>
   <br>
   Hora: &nbsp&nbsp <?php echo date("H:i:s") ?>
   <br>
   Nombre: <?php echo utf8_encode($_SESSION['datos_usuario']['nombre']);?>
   <br>
   Apellido: <?php echo utf8_encode($_SESSION['datos_usuario']['apellido']);?>   
   <br>
   Licencias Generadas: 
                         <?php 

                           $gv_cont     = 1;
                           $gv_array    = 0;
                        
                            while ( $gv_cont <= $gv_cantidad ) 
                             {
                                                         
                             $str = "Licencias".$gv_cont.date("H:i:s"); echo substr(md5($str), 0, 10)."XXXXXXXXXX";
                             echo("</br>");

                             $gv_cont++;

                             $licencias = array();

                             $licencias[$gv_array] = md5($str);

                             $gv_array++;

                            }


                         ?>        

        <div class="row">
             </br>
            <div class="col-xs-12 col-sm-3 col-xs-offset-6 col-md-offset-10">
        
               <div class='define'>
                <input id="Pagar" type="button" class="btn btn-primary btn-sm" value="Pagar"  />
              </div>

            </div>
        
        </div>                                              
                            

</body>