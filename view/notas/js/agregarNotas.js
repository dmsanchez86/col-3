'use strict';

var estudiantesCalificados = [];

$().ready(function($) {

  // validamos si ya se han registrado estudiantes
  if(localStorage.getItem('configuracionEstudiantes') != null){
    estudiantesCalificados = JSON.parse(localStorage.getItem('configuracionEstudiantes'));
  }
	
	// evento del select que me carga los estudiantes de la asignatura
	$('#selectGrupo').unbind('change').change(function(){
		if($(this).val() != ""){
      // mostramos el mensaje de cargando
      $('body').loading({
        message: 'Cargando Estudiantes...',
        onStart: function(loading) {
          loading.overlay.slideDown(400);
        },
        onStop: function(loading) {
          loading.overlay.slideUp(400);
        }
      });

      // obtenemos el id del grupo
			var $idGrupo = $(this).val();

      // consultamos los estudiantes de ese grupo
			$.ajax({
				url: './index.php',
				type: 'POST',
				data: {
					url: 'obtenerEstudiantesPorGrupo',
					idGrupo: $idGrupo,
				},
				success: function(data){

          // limpiamos la tabla que muestra los estudiantes
					$("#tableStudents tbody").empty();

          // se encierra en un try catch para que no salga error si no hay estudiantes
          try{
            var $data = JSON.parse(data);

            // llenamos la tabla con los estudiantes
  					$data.forEach( function(element, index) {
  						$("#tableStudents tbody").append(App.tmpl("rowStudents", element));
  					});

            // quitamos el mensaje de carga
            $('body').loading('stop');

            // mostramos la tabla
            $('#tableStudents').fadeIn();

            // evento del boton calificar
            $('button[id-estudiante]').unbind('click').click(function(){
              var idEstudiante = $(this).attr('id-estudiante');
              var nombreEstudiante = $(this).attr('nombre-estudiante');
              var idGrupoEstudiante = $(this).attr('id-grupoestudiante');

              if($('#selectCantidad').val() == ""){
                $('#selectCantidad').notify("Seleccione una cantidad");
                $('#selectCantidad').focus();
              }else{
                var pase = false;
                var cantidad = $('#selectCantidad').val();
                var conf = JSON.parse(localStorage.getItem('configuracionEstudiantes'));
                var datosNotasEstudiante = {};

                if(conf != null){ 
                  for(var i = 0; i < conf.length; i++){
                    if(conf[i]['idEstudiante'] == idEstudiante && cantidad == conf[i]['notas'].length){
                      pase = true;
                      datosNotasEstudiante = conf[i];
                    }
                  }
                }

                var num = 1;

                // se limpia la tabla
                $('#modalNotas table thead tr').empty();
                $('#modalNotas table tbody tr').empty();

                // inserto la cantidad de notas
                for (var i = cantidad; i > 0; i--) {
                  var param = {n: num};
                  $('#modalNotas table thead tr').append(App.tmpl("tableHead", param));
                  $('#modalNotas table tbody tr').append(App.tmpl("tableBody", param));
                  num++;
                };

                if(pase){
                  $('#modalNotas table tbody tr td').each(function(index, el) {
                    $(el).find('input').val(datosNotasEstudiante['notas'][index].value);
                  });
                }

                // pongo el nombre del estudiante en el titulo del modal
                $('#modalNotas .modal-title span').text(nombreEstudiante).attr({'id-estudiante': idEstudiante, 'id-grupoestudiante': idGrupoEstudiante});
                $('#modalNotas').modal(); // abro el modal
              }
            });
            
          }catch(e){}
        }
      });     
    }else{
      $("#tableStudents tbody").empty();
      $('#tableStudents').fadeOut();
    }
	});

  // evento que guarda las notas
  $('#saveNotes').unbind('click').click(function(){
    var notas = [];

    // recorremos todas las notas
    $('#tableNotas tbody tr > td').each(function(index, el) {
      var $el = $(el);

      if($el.find('input').val() != ""){
        // creamos la nota
        var note = {
          value: $el.find('input').val(),
          name: $el.find('input').attr('name')
        };

        // la añadimos al arreglo de las notas
        notas.push(note);

        // si las notas son iguales a la cantidad que escogio guardamos todas las notas con el id del estudiante y el grupo
        if($('#tableNotas tbody tr > td').length == notas.length){
          // creamos el estudiante
          var estudiante = {
            idEstudiante: $('#modalNotas .modal-title span').attr('id-estudiante'),
            idGrupoEstudiante: $('#modalNotas .modal-title span').attr('id-grupoestudiante'),
            notas: notas
          };

          // guardamos las notas en la base de datos
          $.ajax({
            url: './index.php',
            type: 'POST',
            data: {
              url: 'guardarNotasEstudiante',
              estudiante: estudiante,
            },
            success: function(data){

              var $data = JSON.parse(data);

              // si todo es correcto
              if($data.status){
                // añadimos el estudiante
                estudiantesCalificados.push(estudiante);

                // guardamos el estudiante con las notas en el local storage para usarlos mas adelante o si recarga la pagina volver a tener los datos
                localStorage.setItem("configuracionEstudiantes", JSON.stringify(estudiantesCalificados));

                // ocultamos el modal
                $('button[data-dismiss]').click();
                $('#tableStudents').notify($data.message, "success"); // mostramos el mensaje de que se guardaron las notas
              }else{
                $('#saveNotes').notify($data.message); // error guardando las notas
              }

            }
          });
        }
      }else{ // si no ingreso nada le mostramos el mensaje que debe ingresar la nota
        $el.find('input').notify('Llene el campo');
      }
    });
  });
});

var App = {};

// Render Templates
(function(){
  var cache = {};
    App.tmpl = function tmpl(str, data){
    // Figure out if we're getting a template, or if we need to
    // load the template - and be sure to cache the result.
    var fn = !/\W/.test(str) ?
      cache[str] = cache[str] ||
        tmpl(document.getElementById(str).innerHTML) :
     
      // Generate a reusable function that will serve as a template
      // generator (and which will be cached).
      new Function("obj",
        "var p=[],print=function(){p.push.apply(p,arguments);};" +
       
        // Introduce the data as local variables using with(){}
        "with(obj){p.push('" +
       
        // Convert the template into pure JavaScript
        str
          .replace(/[\r\t\n]/g, " ")
          .split("<%").join("\t")
          .replace(/((^|%>)[^\t]*)'/g, "$1\r")
          .replace(/\t=(.*?)%>/g, "',$1,'")
          .split("\t").join("');")
          .split("%>").join("p.push('")
          .split("\r").join("\\'")
      + "');}return p.join('');");
   
    // Provide some basic currying to the user
    return data ? fn( data ) : fn;
  };
})();