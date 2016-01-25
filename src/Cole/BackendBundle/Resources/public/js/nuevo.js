 $(document).ready(function () {
 	$('.dni').mask('A0000000-S',{
        onComplete: function(event) {
        var dni = $("#alumno_responsable1_dni").val();
alert("hola");
    $.ajax({
      type: 'POST',
      url: Routing.generate('comprobar_padre'),
      data: {dni:dni},
      dataType: 'json',
      success: function(response) {

        if(confirm(response.message+response.name+"\n\n\n¿Desea obtener sus datos?")==true)
        {
          $.ajax({
            type: 'POST',
            url: Routing.generate('obtenerdatos_responsable'),
            data: {dni:dni},
            dataType: 'json',
            success: function(response) {   
            
              $("#alumno_responsable1_nombre").focus().val(response.data['nombre']);
              $("#alumno_responsable1_fechaNacimiento").focus().val(response.data['fechaNacimiento']);
              $("#alumno_responsable1_profesion").focus().val(response.data['profesion']);
              $("#alumno_responsable1_estadoCivil").focus().val(response.data['estadoCivil']);
              $("#alumno_responsable1_movil").focus().val(response.data['movil']);
              $("#alumno_responsable1_email").focus().val(response.data['email']);
              $("#alumno_responsable2_dni").focus();
            }
          })     
        event.stopPropagation();
        }
         
      }
  })
 event.preventDefault();
    }}, {
    'translation': {A: {pattern: /[X-Zx-z]|[0-9]/}},
     placeholder: "________-__"});
 });