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

  //Se cambia el estilo de icono de admin.
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





});