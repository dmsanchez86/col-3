<?php 
require '../../controller/Ejecucion.php';
require '../../model/Asignatura.php';

session_start();

$datosUsuario = $_SESSION['datos_usuario'];
$asignatura = new Asignatura();

if(count($datosUsuario) == 0){ die(); }

$data = json_decode($asignatura->getGruposPorDocente($datosUsuario['id']));
#var_dump($data);

?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">

        <title>Agregar Nota</title>

        <link href="../../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" /> 
        <link rel="stylesheet" href="../../plugins/loading/loading.css" type="text/css"/>

        <style>
            #tableStudents{display: none;}
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="content-wrapper">
                <div class="container"> 
                    <div class="well">
                        <h3 class="title">Agregar Nota</h3>
                        <div class="row">
                            <div class="col-md-2 col-xs-6">
                                <label for="selectGrupo">Seleccione el Grupo</label>
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <select name="grupo" id="selectGrupo" class="form-control">
                                    <option value="">Seleccione</option>
                                    <?php 
                                        foreach ($data as $key) {
                                            echo "<option value='".$key->gru_id."'>".$key->gru_nombre."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-1 col-xs-12"></div>
                            <div class="col-md-4 col-xs-6">
                                <label for="selectCantidad">Seleccione la cantidad de notas a calificar</label>
                            </div>
                            <div class="col-md-2 col-xs-6">
                                <select name="grupo" id="selectCantidad" class="form-control">
                                    <option value="">-</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="well">
                        <h5 class="subtitle">Estudiantes</h5>
                        <table id="tableStudents" class="table table-striped table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Id Estudiante</th>
                                    <th>Estudiante</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div> 
            </div>
        </div>

        <div class="modal fade" id="modalNotas">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Calificar usuario <span></span></h4>
              </div>
              <div class="modal-body">
                <table id="tableNotas" class="table table-striped table-hover table-responsive">
                    <thead>
                        <tr>
                        </tr>
                    </thead>
                    <tbody>
                        <tr></tr>
                    </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveNotes">Guardar Notas</button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Templates -->
        <script type="text/html" id="tableHead">
            <th style="font-size: 11px;">
                Nota <%= n %>
            </th>
        </script>
        <script type="text/html" id="tableBody">
            <td>
                <input type="number" class="form-control" name="nota<%= n %>" id="nota<%= n %>" min="0" max="10">
            </td>
        </script>
        <script type="text/html" id="rowStudents">
            <tr>
                <td><%= id %></td>
                <td><%= id_estudiante %></td>
                <td><%= usu_nombre+ " " +usu_apellido1+ " " +usu_apellido2 %></td>
                <td>
                    <button type="button" class="btn btn-info" id-estudiante="<%= id_estudiante %>" id-grupoestudiante="<%= id %>" nombre-estudiante="<%= usu_nombre+ " " +usu_apellido1+ " " +usu_apellido2 %>">Calificar</button>
                </td>
            </tr>
        </script>
        <!-- Fin Templates -->

        <!-- Scripts -->
        <script src="../../plugins/jQuery/jQuery-2.1.3.min.js"></script>
        <script src="../../plugins/loading/loading.js"></script>
        <script src="../../plugins/notify/notify.js"></script>
        <script src="../../js/bootstrap.js"></script>
        <script src="../../js/general.js"></script>
        <script src="js/agregarNotas.js"></script>
    </body>
</html>
          

		
