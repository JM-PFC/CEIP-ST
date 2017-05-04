$(document).ready(function () {
	//Mascaras
	$('.telefono').mask('000 00 00 00'); 

  //Se oculta los mensajes flash tras un tiempo.
  setTimeout(function() {
    $('.notificacion').slideUp();
  }, 8000);

	//Se cambia el estilo de icono de admin.
	$('#img_admin').on('mouseover', function(event) {
		$(this).addClass('hide');
		$('#img_admin_hover').removeClass('hide');
	});
	$('#img_admin_hover').on('mouseleave', function(event) {
		$(this).addClass('hide');
		$('#img_admin').removeClass('hide');
	});

	//Se activa la descripción emergente en los avisos.
	$("*").tooltip({
		placement: "bottom"
	});


/*
	//Se actualiza los datos personales de los usuarios.
  $(document).on("submit","#datos_personales",function(event){
  	event.preventDefault();
    form= $(this);

  	//Se comrpueba si hay algún dato erroneo en los input.
    if($(this).find(".has-error").size()>0){
    	return false;
    }

    tipo=$(".tipo").attr("tipo");
		id=$(".tipo").attr("id");


    var datos= new Object();
    $(this).find("input").each(function(){
    	elemento=$(this).val().replace(/ /g, "");
    	if($(this).attr("value")!=elemento){
				datos[$(this).attr("id")]=$(this).val();
    	}
    });
    if(!$.isEmptyObject(datos)){
      $.ajax({
        type: 'POST',
        url: Routing.generate('datos_personales', {_locale:"es"}),
        data: {datos:datos, tipo:tipo, id:id},
        dataType: 'json',
      success: function(response) {
          div=form.closest("#intranet").parent();

$(div).load(Routing.generate('intranet_perfil', {_locale:"es"}));
      	}
      })
    }

    event.stopPropagation();   

  });
*/




});