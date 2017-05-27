$(document).ready(function () {
	//Mascaras
	$('.telefono').mask('000 00 00 00'); 

  //Se oculta los mensajes flash tras un tiempo.
  setTimeout(function() {
    $('.notificacion').slideUp();
  }, 8000);

	//Se cambia el estilo de icono de admin.
  $(document).on('mouseenter',"#img_admin",function(event){ 
		$(this).addClass('hide');
		$('#img_admin_hover').removeClass('hide');
	});
  $(document).on('mouseleave',"#img_admin_hover",function(event){ 
		$(this).addClass('hide');
		$('#img_admin').removeClass('hide');
	});

	//Se activa la descripción emergente en los avisos.
	$("*").tooltip({
		placement: "bottom"
	});

  $("#gallery").unitegallery({
    slider_enable_arrows:true,
    slider_enable_zoom_panel:false,
    slider_textpanel_enable_title:true,
    slider_textpanel_bg_opacity:0,
    gallery_width:550,              //gallery width  
    gallery_height:350,             //gallery height 
    slider_control_zoom:false, 
    slider_scale_mode: "fill", //fill, fit, down.
    slider_controls_always_on: true,  
    slider_enable_progress_indicator: false,
    gallery_autoplay:true,           //true / false - begin slideshow autoplay on start
    gallery_play_interval: 6000,        //play interval of the slideshow
    gallery_pause_on_mouseover: true, 
    slider_textpanel_text_valign:"top",      //middle, top, bottom - text vertical align
    thumb_width:120,
    thumb_height:70,
    thumb_border_color: "#aea703",
    thumb_over_border_color: "#ded84d",
    thumb_selected_border_width:4,
    thumb_selected_border_color: "#ded84d",
    thumb_round_corners_radius:5,
    thumb_transition_easing: "linear",
    strippanel_enable_buttons:false,
    strippanel_enable_handle:false,
    theme_enable_hidepanel_button:false,
  }); 


  $('.bloque-menu-alumno a').on('mouseover', function(event) {
    clase=$(this).attr("color");
    $(this).closest(".bloque-item-menu").addClass(clase);
    $(this).find("i").addClass(clase);
    $(this).closest(".bloque-item-menu").next().addClass(clase);
  });
  $('.bloque-menu-alumno a').on('mouseleave', function(event) {
      clase=$(this).attr("color");
      $(this).closest(".bloque-item-menu").removeClass(clase);
      $(this).find("i").removeClass(clase);
      $(this).closest(".bloque-item-menu").next().removeClass(clase);
  });

  $(".imgLiquidFill").imgLiquid({
    fill: true,
    //horizontalAlign: "center",
    //verticalAlign: "center"
    horizontalAlign: "center",
    verticalAlign: "none"
  });



  //Se incrementa el contador de las noticias.
  $(document).on('click',".contenido_noticia a",function(event){ 

    href=$(this).attr("href");
    array=href.split("/");
    id=array[array.length-1];
    
    $.ajax({
      type: 'POST',
      url: Routing.generate('contador_noticias'),
      data:{id:id}, 
      dataType: 'json',
      success: function(response){

      }
    })
  });

  //Se cambia el icono de enviar mensaje a profesores.
  $(document).on('mouseenter',".i_hover",function(event){ 
    $(this).find("#second").removeClass('hidden');
    $(this).find("#first").addClass('hidden');
  });

  $(document).on('mouseleave',".i_hover",function(event){ 
    $(this).find("#second").addClass('hidden');
    $(this).find("#first").removeClass('hidden');
  });

  ///////////////////////////////////////////
  //      Cursos impartidos Profesor       //
  ///////////////////////////////////////////

  $(document).on('click',"#lista_alumnos .info_alumno",function(){ 
    locale=$("#contenedor_cursos_profesor").attr("locale");
    id=$(this).attr("id");

    $("#info_alumno .modal-body").load(Routing.generate("info_alumno", {id:id, _locale:locale}), function(){
    });
  });

  //Se oculta el aviso mostrado del enlace cuando se abre una ventana modal.
  $('.modal').on('show.bs.modal', function (e) {
    $(".tooltip").remove();
  })

  $(document).on('click',".info_profesor",function(){ 

    locale=$("#contenedor_cursos_profesor").attr("locale");
    id=$(this).attr("id");

    $("#info_profesor .modal-body").load(Routing.generate("info_profesor", {id:id, _locale:locale}), function(){
    });
  });

  //Se cambia las opciones de cursos impartidos por el tutor.
  $(document).on('click',".alumnos_grupo #tutor button",function(){ 
    $('.alumnos_grupo #tutor button').removeClass('active');
    $(this).addClass('active');

    $('.alumnos_grupo  #contenido_mi_grupo>div').addClass('hidden');
    id=$(this).attr("id");
    $('.alumnos_grupo #contenido_mi_grupo>div[id="'+id+'"]').first().removeClass('hidden');
    //Se muestra la primera opción en caso de que un profesor imparta optativa
    $("#alumnos_del_grupo").addClass('active');
    $("#alumnos_optativa").removeClass('active');

    $("#alumnos .descargar-datos button").addClass('hidden');
    $("#alumnos .descargar-datos[class*='"+id+"'] button").removeClass('hidden');

    $("#contenido_mi_grupo .descargar-datos button").addClass('hidden');
    $("#contenido_mi_grupo .descargar-datos[class*='"+id+"'] button").removeClass('hidden');
    
    $("#contenido_mi_grupo .descargar-datos").addClass('hidden');
    $("#contenido_mi_grupo .descargar-datos[class*='"+id+"']").removeClass('hidden');


    if($(this).attr("id")=="#1"){
      $("#solo_optativa").removeClass('hidden');
      
      if($("#solo_optativa").size()>0 && !$("#solo_optativa").hasClass('hidden')){
        $("#alumnos_grupo_pdf button").addClass('hidden');
        $("#alumnos_optativa_pdf button").removeClass('hidden');
        $("#contenido_mi_grupo #alumnos_optativa_pdf").removeClass('hidden');
      }
    }
    else{
      $("#solo_optativa").addClass('hidden');
      $("#lista_alumnos .alumnos_optativa").addClass('hidden');
    }
  });

  //Se cambia el listado de alumnos del grupo o de la optativa impartida en ese grupo por el profesor.
  $(document).on('click',"#tipo_lista #alumnos_del_grupo",function(){ 
    $('#tipo_lista #alumnos_del_grupo').addClass('active');
    $('#tipo_lista #alumnos_optativa').removeClass('active');

    $(".alumnos_optativa").addClass('hidden');
    $(".alumnos_del_grupo ").removeClass('hidden');

    $(".alumnos_optativa #alumnos_optativa_pdf").addClass('hidden');
    $(".alumnos_del_grupo #alumnos_grupo_pdf").removeClass('hidden');
    
    $("#alumnos_grupo_pdf[class*='#1'] button").removeClass('hidden');

  });

  $(document).on('click',"#tipo_lista #alumnos_optativa",function(){ 
    $('#tipo_lista #alumnos_del_grupo').removeClass('active');
    $('#tipo_lista #alumnos_optativa').addClass('active');

    $(".alumnos_optativa #alumnos_optativa_pdf").removeClass('hidden');
    $(".alumnos_del_grupo #alumnos_grupo_pdf").addClass('hidden');

    $(".alumnos_optativa").removeClass('hidden');
    $(".alumnos_del_grupo ").addClass('hidden');

    $("#alumnos_grupo_pdf[class*='#1'] button").addClass('hidden');
    
  });


});