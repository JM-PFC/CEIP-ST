$(document).ready(function () {

  locale=$("#intranet").attr("locale");

	//Mascaras
	$('.telefono').mask('000 00 00 00'); 

  // Se establece las variables con los audios para las notificaciones.
  if (navigator.userAgent.search("Firefox") >= 0) { //Firefox solo admite archivos .ogg
    var aviso = new Audio();
    aviso.src = "/Symfony/web/bundles/backend/sounds/aviso.ogg";
    var ok = new Audio();
    ok.src = "/Symfony/web/bundles/backend/sounds/ok.ogg"
    var exito = new Audio();
    exito.src = "/Symfony/web/bundles/backend/sounds/exito.ogg";
    var error = new Audio();
    error.src = "/Symfony/web/bundles/backend/sounds/error.ogg";
    var errorPNotify = new Audio();
    errorPNotify.src = "/Symfony/web/bundles/backend/sounds/errorPNotify.ogg";
    var blocker = new Audio();
    blocker.src = "/Symfony/web/bundles/backend/sounds/blocker.ogg";   
  }
  else{
    var aviso = new Audio();
    aviso.src = "/Symfony/web/bundles/backend/sounds/aviso.mp3";
    var ok = new Audio();
    ok.src = "/Symfony/web/bundles/backend/sounds/ok.mp3"
    var exito = new Audio();
    exito.src = "/Symfony/web/bundles/backend/sounds/exito.mp3";
    var error = new Audio();
    error.src = "/Symfony/web/bundles/backend/sounds/error.mp3";
    var errorPNotify = new Audio();
    errorPNotify.src = "/Symfony/web/bundles/backend/sounds/errorPNotify.mp3";
    var blocker = new Audio();
    blocker.src = "/Symfony/web/bundles/backend/sounds/blocker.mp3";
  }


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

  //Se alinea la ventana modal al centro de la pantalla.
  function alignModal(){
    var modalDialog = $(this).find(".modal-dialog");
    /* Se le aplica la alineación vertical al margen superior*/
      modalDialog.css("margin-top", Math.max(0, ($(window).height() - modalDialog.height()) / 2)-50); //Se le ha restado 50 para que suba un poco de la mitad
    }

    // Se alinea la ventana modal cuando se muestra.
    $(".fade-scale").on("shown.bs.modal", alignModal);
    //Se añade efecto de sonido a los avisos.
    $('.fade-scale').on('show.bs.modal', function (e) {
      aviso.play();
    })
    //Se alinea la ventana cuando se cambia el tamaño de la pantalla.
    $(window).on("resize", function(){
        $(".modal:visible").each(alignModal);
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


  //Se cambia el cursor a espera cuando se hace click en los elemento con la clase "waiting".
  $(document).on('click',".waiting",function(event){ 
    $('body').addClass('waiting');
  });

  $(document).on('click',".sidebar-nav a, .enrutaje a, #enlacemiperfil a, .contenedor_noticia a, .barramensajes a, .modal #form_submit,.boton_enviar button, .modal button[type='submit'], #consultar, .bloque-dashboard>a",function(event){ 
    $('body').addClass('waiting');
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

  ///////////////////////////////////////////
  //         Seguimientos Profesor         //
  ///////////////////////////////////////////


  //Efectos sobre el botón de añadir seguimientos.
  $(document).on('mouseenter',".add_seguimiento_scroll",function(event){ 
    $(this).animate({'right': -10,opacity: 1 },500);
  });

  $(document).on('mouseleave',".add_seguimiento_scroll",function(event){ 
     $(this).animate({'right': -150, opacity: 0.8 },500);
  });

  //Se pulsa un botón en las opciones de selección.
  $(document).on('click',"#seleccion_nuevo_seguimiento button",function(event){ 
    div=$(this).closest("div");
    div.find("button").removeClass('active');
    $(this).addClass('active');

    //Se deshabilita el formulario y el botón de enviar hasta que cumpla las condiciones que se comprueban luego.
    $("#seguimiento_descripcion").prop("disabled", true);
    $("#formulario_nuevo_seguimiento .boton_enviar button").prop('disabled', true);
    //Se oculta el nombre del alumno por si se cambia de opción.
    $(".nombre_alumno_seguimiento").addClass('hidden');
    
    locale=$("#seleccion_nuevo_seguimiento").attr("locale");
    orden=$(this).closest("div").parent();
    //Se marca la opción actual.
    $("#seleccion_nuevo_seguimiento #orden").css("background-color", "#337ab7");
    if(orden.attr("id")=="1"){
      $("#seleccion_nuevo_seguimiento #2 #orden").css("background-color", "#ea9239");
      $("#seleccion_nuevo_seguimiento #2").removeClass('hidden');
      $("#seleccion_nuevo_seguimiento #3 button").removeClass('active');

      if ($(window).width() < 768) {
        var pos = $("#seleccion_nuevo_seguimiento #2").offset().top;
        $("html").scrollTop(pos-300);
      }
      id=$(this).attr("id");
      //Se obtiene las asignaturas impartidas por el profesor en ese grupo.
      $.ajax({
          type: 'POST',
          url: Routing.generate('asignaturasGrupo_profesor', {id:id, _locale:locale}),
          success: function(response){
           $("#seleccion_nuevo_seguimiento #asignatura").empty();
            for(key in response.asignaturas){
              $("#seleccion_nuevo_seguimiento #asignatura").append('<button id="'+response.asignaturas[key]["id"]+'" class="btn btn-primary col-xs-12 ">'+response.asignaturas[key]["abreviatura"]+'</button>');
            }

            $("#seleccion_nuevo_seguimiento #3").addClass('hidden');
          }
      })
      //Se selecciona el grupo en el select oculto.
      $("#seguimiento_grupo option[value='"+id+"']").prop('selected', true);
    }
    else if(orden.attr("id")=="2"){
      id=$(this).attr("id");
      $("#seleccion_nuevo_seguimiento #3 button").removeClass('active');
      $("#seleccion_nuevo_seguimiento #3 #orden").css("background-color", "#ea9239");
      $("#seleccion_nuevo_seguimiento #3").removeClass('hidden');

      if ($(window).width() < 768) {
        var pos = $("#seleccion_nuevo_seguimiento #3").offset().top;
        $("html").scrollTop(pos-300);
      }
      //Se selecciona el grupo en el select oculto.
      $("#seguimiento_asignatura option[value='"+id+"']").prop('selected', true);
    }
    else{
      if($(this).attr("id")=="grupo"){
        $("#seguimiento_descripcion").prop("disabled", false);
        if ($(window).width() < 768) {
          var pos = $("#seguimiento_descripcion").offset().top;
          $("html").scrollTop(pos);
        }
        if($("#seguimiento_descripcion").val().length == 0){
          $("#formulario_nuevo_seguimiento .boton_enviar button").prop('disabled', true);
        }
        else{
          $("#formulario_nuevo_seguimiento .boton_enviar button").prop('disabled', false);
        }
      }
      else{

        grupo=$("#seleccion_nuevo_seguimiento #cursos .active").attr("id");
        asignatura=$("#seleccion_nuevo_seguimiento #asignatura .active").attr("id");
        if(asignatura){
          $("#lista_alumnos_seguimiento .modal-body").load(Routing.generate("AlumnosGrupoAsignatura", {id:grupo, asig:asignatura, _locale:locale}), function(){
        
          });
        }
        else{//Para Infantil
          $("#lista_alumnos_seguimiento .modal-body").load(Routing.generate("AlumnosGrupo", {id:grupo, _locale:locale}), function(){
            });
        }
             
      }
    }
  });

  // Se desactiva el botón de alumno de la opción de destinatario cuando cerramos la ventana modal sin elegir alumno.
  $(document).on('click','#lista_alumnos_seguimiento .close, #lista_alumnos_seguimiento .class-footer button' ,function() {
    $("#seleccion_nuevo_seguimiento #3 button").removeClass('active');
  });


  // Se muestra el alumno elegido de la venatan modal de la opción destinatario.
  $(document).on('click','#lista_alumnos_seguimiento tr' ,function() {
    nombre=$(this).attr("nombre");
    id=$(this).attr("id");

    if($(this).attr("sexo")=="Masculino"){
      $(".alumno").text(nombre);
      $(".alumno").parent().removeClass('hidden');
      $(".alumno").parent().attr('id',id);
    }
    else{
      $(".alumna").text(nombre);
      $(".alumna").parent().removeClass('hidden');
      $(".alumna").parent().attr('id',id);
    }
    //Se selecciona el alumno en el select oculto.
    $("#seguimiento_alumno option[value='"+id+"']").prop('selected', true);

    //Se cierra la ventana modal.
    $('#lista_alumnos_seguimiento').modal('toggle');

    $("#seguimiento_descripcion").prop("disabled", false);
    if ($(window).width() < 768) {
      var pos = $("#seguimiento_descripcion").offset().top;
      $("html").scrollTop(pos);
    }
    if($("#seguimiento_descripcion").val().length == 0){
      $("#formulario_nuevo_seguimiento .boton_enviar button").prop('disabled', true);
    }
    else{
      $("#formulario_nuevo_seguimiento .boton_enviar button").prop('disabled', false);
    }
  });

  
  $(document).on('keyup paste cut','#seguimiento_descripcion' ,function() {
    if($(this).val().length == 0){
      $("#formulario_nuevo_seguimiento .boton_enviar button").prop('disabled', true);
    }
    else{
      $("#formulario_nuevo_seguimiento .boton_enviar button").prop('disabled', false);
    }
  });

  //Se añade el id del seguimiento en botón de eliminar de la ventana modal para luegp generar la ruta.
  $(document).on('click','#contenedor_seguimientos #btn_eliminar' ,function() {
    id=$(this).closest('.seguimiento').attr("id");
    $("#eliminar_seguimiento_modal .modal-body").load(Routing.generate("seguimiento_eliminar", {id:id, _locale:locale}), function(){
    }); 
  });

  $(document).on('click','#eliminar_seguimiento_modal #eliminar' ,function() {
    $('#eliminar_seguimiento_modal').modal('toggle');
    id=$(this).attr("seguimiento");
    $.ajax({
      type: 'POST',
      url: Routing.generate("seguimiento_delete", {id:id, _locale:locale}),        
      success: function() {

      }
    })
  });

    //Se añade el id del seguimiento en botón de editar de la ventana modal para luegp generar la ruta.
  $(document).on('click','#contenedor_seguimientos #btn_editar' ,function() {
    id=$(this).closest('.seguimiento').attr("id");
    $("#editar_seguimiento_modal .modal-body").load(Routing.generate("seguimiento_edit", {id:id, _locale:locale}), function(){
    }); 
  });

  $(document).on('click','#editar_seguimiento_modal #editar' ,function() {
    $('#editar_seguimiento_modal').modal('toggle');
    id=$(this).attr("seguimiento");
    $.ajax({
      type: 'POST',
      url: Routing.generate("seguimiento_update", {id:id, _locale:locale}),        
      success: function() {

      }
    })
  });

  ///////////////////////////////////////////
  //          Seguimientos Alumno          //
  ///////////////////////////////////////////

  //Se marca el seguimiento como leido.
  $(document).on('click','#leido' ,function() {
    boton=$(this);
    id=$(this).closest(".seguimiento").attr("id");
    alumno=$(this).closest("#contenedor_seguimientos").attr("alumno");
    $(this).closest(".seguimiento").animate({'right': -1000,opacity: 1 },400, function() {

      //Se elimina de la tabla de avisos el seguimiento leido.
      $.ajax({
        url: Routing.generate('seguimiento_leido', {_locale:"es",id:id, alumno:alumno}),
        dataType: 'json',
        success: function(response) {

        }
      });

      //Se actualiza el número de avisos en la barra superior.
      num_avisos=parseInt($(".barra_cabecera #barraalertasmovil .numalertas").text())-parseInt(1);
      $("#barraalertasmovil .numalertas").text(num_avisos);
      //En caso de que exista menos de 5 seguimientos, se inserta el seguimiento leido en la posición correspondiente.
      if($(".seguimiento").size()<5){
        index=0;
        $(".seguimiento:not([fecha])").each(function(){
          if($(this).attr("id")>id){
            index=$(this).attr("id");
          }
        });
        //Se elimina los estilos de seguimiento actualizado.
        $(this).closest(".seguimiento").find(".aviso").remove();
        boton.remove(); 
        $(this).closest(".seguimiento").find(".icono i").removeClass('c_naranja');
        //Se inserta el seguimiento leido en la primera posición de antiguos seguimientos.
        if(index==0 && $(".seguimiento:not([fecha])").size()>0){
          id=$(".seguimiento:not([fecha])").first().attr("id");
          $(this).closest(".seguimiento").insertBefore($(".seguimiento[id='"+id+"']"));
          $(this).closest(".seguimiento").animate({'right': 0,opacity: 1 },400);
        }//Se inserta el seguimiento leido tras el último seguimiento sin leer.
        else if(index==0 && $(".seguimiento").size()>1){
          id=$(".seguimiento").last().attr("id");
          $(this).closest(".seguimiento").insertAfter($(".seguimiento[id='"+id+"']"));
          $(this).closest(".seguimiento").animate({'right': 0,opacity: 1 },400); 
        }//Se muestra el único seguimiento.
        else if (index==0 && $(".seguimiento").size()==1){
          $(this).closest(".seguimiento").animate({'right': 0,opacity: 1 },400); 
        }//Se inserta el seguimiento leido en la posición correspondiente entre los antiguos seguimientos.
        else{
          $(this).closest(".seguimiento").insertAfter($(".seguimiento[id='"+index+"']"));
          $(this).closest(".seguimiento").animate({'right': 0,opacity: 1 },400);
        }
      }
      else{
        //Se comprueba si hay seguimientos antiguos mostrado para insertar el seguimiento leido en la posición correspondiente.
        if($(".seguimiento:not([fecha])").size()>0 && $(".seguimiento:not([fecha])").last().attr("id")<id){
          index=0;
          $(".seguimiento:not([fecha])").each(function(){
            if($(this).attr("id")>id){
              index=$(this).attr("id");
            }
          });
          //Se elimina los estilos de seguimiento actualizado.
          $(this).closest(".seguimiento").find(".aviso").remove();
          boton.remove(); 
          $(this).closest(".seguimiento").find(".icono i").removeClass('c_naranja');

          if(index==0){
            id=$(".seguimiento:not([fecha])").first().attr("id");
            $(this).closest(".seguimiento").insertBefore($(".seguimiento[id='"+id+"']"));
            $(this).closest(".seguimiento").animate({'right': 0,opacity: 1 },400);
          }
          else{
            $(this).closest(".seguimiento").insertAfter($(".seguimiento[id='"+index+"']"));
            $(this).closest(".seguimiento").animate({'right': 0,opacity: 1 },400);
          }
        }
        else{ 
          //Se elimina el seguimiento y se marca como leido en la base de datos, para que se pueda cargar en su posición.
          $(this).closest(".seguimiento").remove();

          //Se carga nuevos seguimientos en el contenedor si sólo quedan 3.
          if($(".seguimiento[fecha]").size()<=3){
            id=$("#contenedor_seguimientos").attr("alumno");
            fecha=$("#contenedor_seguimientos>div").not(':hidden').last().attr("fecha");
                
            if(fecha){
              $.ajax({
                url: Routing.generate('cargar_nuevos_seguimientos_alumno', {_locale:"es",id:id ,fecha:fecha}),
                dataType: 'json',
                success: function(response) {
                  $('#contenedor_seguimientos').append(response.html);
                }
              });
            }
            else{
              iden=$("#contenedor_seguimientos>div").not(':hidden').last().attr("id");
              $.ajax({
                url: Routing.generate('cargar_seguimientos_alumno', {_locale:"es",id:id ,iden:iden}),
                dataType: 'json',
                success: function(response) {
                  $('#contenedor_seguimientos').append(response.html);
                }
              });
            }
          }
        }
      }
    });
  });

  //Se marca el seguimiento como leido.
  $(document).on('click','#consultar' ,function() {
    boton=$(this);
    id=$(this).closest(".seguimiento").attr("id");
    if($(this).closest("#contenedor_seguimientos").attr("profesor")){
      user=$(this).closest("#contenedor_seguimientos").attr("profesor");
      tipo="Profesor";
    }else{
      user=$(this).closest("#contenedor_seguimientos").attr("alumno");
      tipo="Alumno";
    }
      //Se elimina de la tabla de avisos el seguimiento seleccionado.
      $.ajax({
        url: Routing.generate('seguimiento_consultado', {_locale:"es",id:id, user:user, tipo:tipo}),
        dataType: 'json',
        success: function(response) {

        }
      });
  });










});