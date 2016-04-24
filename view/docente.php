<?php
$ruta   = $_SESSION['datos_usuario']['ins_imagen'];
$nombre = $_SESSION['datos_usuario']['nombre']." ".$_SESSION['datos_usuario']['apellido1']." ".$_SESSION['datos_usuario']['apellido2'];
$tipo_usuario =   $_SESSION['datos_usuario']['tip_nombre']
?>

<!DOCTYPE>
<html>
<head>
    <meta charset="UTF-8">
    <title>Docente</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="dist/css/skins/skin-blue.min.css" rel="stylesheet" type="text/css" />
    <style>
        .skin-blue .wrapper{
            background: transparent;
        }
        .skin-blue .main-header .logo {
            overflow: auto;
            padding-top: .51rem;
        }
        .navbar-nav>.user-menu .user-image {
            float: none; 
            width: 40px;
            height: 40px;
            margin-right: 0px;
            margin-top: 5px;
            margin-left: 5px;
        }
        @media (min-width: 768px){
            .navbar-nav>li>a {
                padding: 0;
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <a  class="logo">
            <span class="logo-lg"><h4><b><?php  echo $tipo_usuario;?></b></h4></span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">

            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" id="_btn_ocul">
                <span class="sr-only"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src=<?php echo $ruta;?>  class="user-image" alt="Imagena Admin" id="tumbUserImage"/>
                            <span class="hidden-xs" id="spNameUser"></span>
                        </a>
                        <ul class="dropdown-menu"> 
                            <li class="user-header">
                                <img src=<?php echo $ruta;?> class="img-circle" alt="User Image" id="imageUser"/>
                                <p id="pNameUser">
                                    <?php echo $nombre ; ?>
                                </p>
                                <small id="smEspecialidad"></small>
                            </li>
                            
                            <li class="user-body">
                            </li>
                        
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="./cerrar" class="btn btn-default btn-flat">Cerrar Sesion</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>


        </nav>



    </header>
   
    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left info">
                    <p id="user_name"></p>
                </div>
            </div>

            <ul class="sidebar-menu" id="sidebar-menu">
                <li class="treeview active">
                    <a>
                        <i class="glyphicon glyphicon glyphicon-tower "></i>
                        <span>Registro</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="active" rel="./view/notas/agregarNotas.php" id="_li1" style="cursor:pointer">
                            <a>
                                <i class="fa fa-circle-o li__"></i>
                                <span>Notas</span>
                            </a>
                        </li>   
                    </ul>
                </li>
                <li class="treeview">
                    <a>
                        <i class="glyphicon glyphicon glyphicon-tower "></i>
                        <span>Historial</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li rel="" id="_li2" style="cursor:pointer">
                            <a>
                                <i class="fa fa-circle-o li__"></i>
                                <span>Notas</span>
                            </a>
                        </li>   
                    </ul>
                </li>
            </ul>
        </section>
    </aside>

    
    <div class="content-wrapper">
        
        <section  >

            <iframe style="width: 100%; min-height: 500px;" src="" frameborder="0"  id="iframe" ></iframe>

        </section>
    </div>

    
    <footer class="main-footer">
       
        <div class="pull-right hidden-xs">

        </div>
        

    </footer>

</div>


<script src="plugins/jQuery/jQuery.js"></script>
<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="dist/js/app.min.js" type="text/javascript"></script>
<script  type="text/javascript" src="js/menuPrincipal.js" ></script>
</body>
</html>


