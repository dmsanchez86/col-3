'use strict';

$().ready(function($) {
	
	// evento del select que me carga los estudiantes de la asignatura
	$('#selectGrupo').unbind('change').change(function(){
		if($(this).val() != ""){
			var $idGrupo = $(this).val();

			$.ajax({
				url: './index.php',
				type: 'POST',
				data: {
					url: 'obtenerEstudiantesPorGrupo',
					idGrupo: $idGrupo,
				},
				success: function(data){
					console.log(JSON.parse(data));

					$("#tableStudents tbody").empty();

          try{
            var $data = JSON.parse(data);


  					$data.forEach( function(element, index) {
  						console.log(element);
  						$("#tableStudents tbody").append(App.tmpl("rowStudents", element));
  					});

            $('#tableStudents').fadeIn();

            // evento del boton calificar
            $('button[id-estudiante]').unbind('click').click(function(){
              var idEstudiante = $(this).attr('id-estudiante');
              var nombreEstudiante = $(this).attr('nombre-estudiante');

              if($('#selectCantidad').val() == ""){
                alert("Seleccione una cantidad");
                $('#selectCantidad').focus();
              }else{
                var cantidad = $('#selectCantidad').val();
                // alert(cantidad);
                var num = 1;

                $('#modalNotas table thead tr').empty();
                $('#modalNotas table tbody tr').empty();

                for (var i = cantidad; i > 0; i--) {
                  var param = {n: num};
                  $('#modalNotas table thead tr').append(App.tmpl("tableHead", param));
                  $('#modalNotas table tbody tr').append(App.tmpl("tableBody", param));
                  num++;
                };

                $('#modalNotas .modal-title span').text(nombreEstudiante);
                $('#modalNotas').modal();
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