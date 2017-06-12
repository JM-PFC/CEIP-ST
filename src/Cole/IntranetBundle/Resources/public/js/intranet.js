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
      modalDialog.css("margin-top", Math.max(0, ($(window).height() - modalDialog.height()) / 2)-100); //Se le ha restado 100 para que suba un poco de la mitad
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

    $('body').removeClass('waiting');
/*
  $timeout(function() {
    $('body').removeClass('waiting');
  }, 50)
*/
  //Se cambia el cursor a espera cuando se hace click en los elemento con la clase "waiting".
  $(document).on('click',".waiting",function(event){ 
    $('body').addClass('waiting');
  });

  $(document).on('click',".sidebar-nav a, .enrutaje a, #enlacemiperfil a, .contenedor_noticia a, .barramensajes a, .modal #form_submit,.boton_enviar button, .modal button[type='submit'], #consultar, .bloque-dashboard>a",function(event){ 
    $('body').addClass('waiting');
  });
  //Se utiliza para que se elimine la clase waiting al volver atrás en el navegador.
  $(window).bind('beforeunload', function() {
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

  //Se añade el id del seguimiento en botón de eliminar de la ventana modal para luego generar la ruta.
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
          if(parseInt($(this).attr("id"))>parseInt(id)){
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
            if(parseInt($(this).attr("id"))>parseInt(id)){
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



  ///////////////////////////////////////////
  //  Reserva Instalaciones/Equipamientos  //
  ////////////////////////////////// ////////

  // Se deshabilita los días no lectivos en el calendario de reservas. 
  $(document).on('click',"#actualizar_calendario_lectivo",function(event){
    //Se obtiene el contenedor principal según si el calendario está en un formulario o no.
    contenedor=$("#reserva_fecha");

    event.preventDefault();
    // Retardo para ejecutarlo una vez cargado el datepicker.
    contenedor.find("#div_leyenda").addClass("hidden"); 
    // Se elimina los estilos del día actual en el calendario.     
    setTimeout(function() {
      contenedor.find('a.ui-state-highlight').removeClass('ui-state-active');
      contenedor.find('a.ui-state-highlight').removeClass('ui-state-hover');
      contenedor.find('a.ui-state-highlight').removeClass('ui-state-highlight');
    }, 1);

    setTimeout(function(){ 
      mes=contenedor.find("tbody td[data-handler='selectDay']").attr("data-month");
      año=contenedor.find("tbody td[data-handler='selectDay']").attr("data-year");
      mes++;
      if(mes!=10 && mes!=11 && mes!=12){
        mes='0'+mes;
      }
      //Se carga los festivos en la leyenda.
      contenedor.find("#div_leyenda").empty();
      contenedor.find("#div_leyenda").load(Routing.generate('festivos_por_mes', {id:mes}));
      contenedor.find("tbody td a").removeClass("festivo");
      //Se eliminan la clases en los enlaces para que inicialmente esten todos habilitados y luego se deshabiliten los festivos correspondientes.
      contenedor.find("tbody td:not([class*='ui-datepicker-week-end'],[class=' ui-datepicker-unselectable ui-state-disabled '])").attr("class","");
      $.ajax({
        type: 'POST',
        url: Routing.generate('dias_festivos'),
        data: {mes:mes},
        dataType: 'json',
        success: function(response) {
          
        setTimeout(function(){
          for (var key in response.data) { 
            // Se añade un enlace en los días festivos deshabilitados del fin de semana, para que luego se encuentre como elemento "a".
            contenedor.find(".ui-datepicker-calendar td[class=' ui-datepicker-week-end ui-datepicker-unselectable ui-state-disabled '] span").filter(function(){return $(this).text()==response.data[key]["dia"];}).each(function(){  
              $( "<a class='ui-state-default' href='javascript:void(0);'>"+response.data[key]["dia"]+"</a>" ).insertAfter( $(this));
              $(this).closest("td").attr("data-month",(mes-1));
              $(this).remove();
            });

            contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a:contains('"+response.data[key]["dia"]+"')").each(function(){  
              var dato=response.data[key]["dia"];
              var comp= $(this).text();
              // Con :contains se obtiene los días del calendario que contiene en sus dígitos el dato dado.
              // Si el dato obtenido tiene un solo dígito, se excluye los días de dos dígitos del calendario que contiene ese dato.
              if(String(dato).length=="1"){
                if(String(comp).length=="1"){
                  $(this).closest("td").addClass("ui-datepicker-unselectable");
                  $(this).closest("td:not(.ui-datepicker-week-end) a").addClass(" ui-state-disabled");//Se le añade la clase no:lectivo a todos los elementos "a" de los días festivos.
                  // Se comprueba que el día festivo es un domingo y que el lunes no hay ningún festivo añadido. 
                  if($(this).closest("td").hasClass("ui-datepicker-week-end") &&  $(this).closest("tr").find("td:last a").text() == $(this).text() && !contenedor.find("#div_leyenda h4[id='"+$(this).closest("tr").next("tr").find("td:nth-child(1) a").text()+"']").length){
                    // Mostramos el traspado del festivo al lunes.
                    $(this).closest("tr").next("tr").find("td:nth-child(1) a").closest("td").addClass("ui-datepicker-unselectable");
                    $(this).closest("tr").next("tr").find("td:nth-child(1) a").closest("td a").addClass("ui-state-disabled");

                    $(this).closest("tr").next("tr").find("td:nth-child(1) a").attr("tipo","traslado");

                      //Se muestra solo un traslado del festivo (ya que hay dos elementos con el mismo día).
                      contenedor.find("#div_leyenda h4[id='"+dato+"']").each(function(){
                        if($(this).next().text().indexOf(" Vacaciones ")<0){
                          $( "<h4 id='"+(dato+1)+"'>"+(dato+1)+"</h4><h4 id='h4_descripcion'>Traslado del Festivo del día "+dato+"</h4>" ).insertAfter( $(this).next());
                        }
                      });
                      //Se comprueba que exista el elemento "a" del traslado del festivo para añadirle el título del festivo.
                      //Si no existe es porque el día ha pasado con respecto al día actual y se ha convertido en un span, y no se puede mostrar.
                      if($(this).closest("tr").next("tr").find("td:nth-child(1) a").size()>0){
                        $(this).closest("tr").next("tr").find("td:nth-child(1) a").attr("title",contenedor.find("#div_leyenda h4[id="+ $(this).closest("tr").next("tr").find("td:nth-child(1) a").text()+"]").next("h4").text());
                      }
                  }
                }
              }
              else{

                $(this).closest("td").addClass("ui-datepicker-unselectable");
                $(this).closest("td:not(.ui-datepicker-week-end) a").addClass(" ui-state-disabled");
                // Se comprueba que el día festivo es un domingo, que el lunes no hay ningún festivo añadido y que el festivo no coincida con el último día del mes. 
                if($(this).closest("td").hasClass("ui-datepicker-week-end") &&  $(this).closest("tr").find("td:last a").text() == $(this).text() && !contenedor.find("#div_leyenda h4[id='"+$(this).closest("tr").next("tr").find("td:nth-child(1) a").text()+"']").length && contenedor.find("tbody tr:last td:last a").text()!=$(this).text() ){
                  // Mostramos el traspado del festivo al lunes.
                  $(this).closest("tr").next("tr").find("td:nth-child(1) a").closest("td").addClass("ui-datepicker-unselectable");
                  $(this).closest("tr").next("tr").find("td:nth-child(1) a").closest("td a").addClass("ui-state-disabled");
                  
                  $(this).closest("tr").next("tr").find("td:nth-child(1) a").attr("tipo","traslado");

                  
                    //Se muestra solo un traslado del festivo (ya que hay dos elementos con el mismo día).
                    contenedor.find("#div_leyenda h4[id='"+dato+"']").each(function(){

                      if($(this).next().text().indexOf(" Vacaciones ")<0){
                        $( "<h4 id='"+(dato+1)+"'>"+(dato+1)+"</h4><h4 id='h4_descripcion'>Traslado del Festivo del día "+dato+"</h4>" ).insertAfter( $(this).next());
                      }
                    });
                    //Se comprueba que exista el elemento "a" del traslado del festivo para añadirle el título del festivo.
                    //Si no existe es porque el día ha pasado con respecto al día actual y se ha convertido en un span, y no se puede mostrar.
                    if($(this).closest("tr").next("tr").find("td:nth-child(1) a").size()>0){
                      $(this).closest("tr").next("tr").find("td:nth-child(1) a").attr("title",contenedor.find("#div_leyenda h4[id="+ $(this).closest("tr").next("tr").find("td:nth-child(1) a").text()+"]").next("h4").text());
                    }
                }
              }
            });
          }
        }, 50);
          // Se comprueba de que existe un día de comienzo/fin de vacaciones.
          if(response.inicio_vacaciones && response.fin_vacaciones){
            var descripcion_inicio=contenedor.find("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").next().text().split(" ");
            var descripcion_fin=contenedor.find("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").next().text().split(" ");
            // Se comprueba que existe ambos días de vacaciones en el mismo mes y que pertenecen al mismo tipo de vacaciones.
            if(descripcion_inicio[3]==descripcion_fin[3]){
              // Se retarda para modificar los días festivos dentro de las vacaciones.
              setTimeout(function(){
                contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a").filter(function(){return $(this).text()==response.inicio_vacaciones["dia"];}).removeClass("festivo");
                contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a").filter(function(){return $(this).text()==response.fin_vacaciones["dia"];}).removeClass("festivo");
                // Se comprueba si existe al inicio de vacaciones día siguiente con festivo de traslado para eliminarlo.
                if(contenedor.find("#div_leyenda h4[id='"+(response.inicio_vacaciones["dia"]+1)+"']").next().text().indexOf("Traslado") >= 0){
                    contenedor.find("#div_leyenda h4[id='"+(response.inicio_vacaciones["dia"]+1)+"']").next().remove();
                    contenedor.find("#div_leyenda h4[id='"+(response.inicio_vacaciones["dia"]+1)+"']").remove();
                    contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a").filter(function(){return $(this).text()==(response.inicio_vacaciones["dia"]+1);}).removeClass("festivo");                
                }
                // Se añade la clase vacaciones a los días correspondientes.
                for(var i = response.inicio_vacaciones["dia"]; i <= response.fin_vacaciones["dia"]; i++){
                  contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a").filter(function(){return $(this).text()==i;}).closest("td").addClass("ui-datepicker-unselectable");
                  contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a").filter(function(){return $(this).text()==i;}).closest("td a").addClass("ui-state-disabled");

                  contenedor.find("#div_leyenda h4[id='"+i+"']").addClass("vacaciones"); 
                  contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'][class='ui-datepicker-unselectable'] a").filter(function(){return $(this).text()==i;}).attr("title",contenedor.find("#div_leyenda h4[class='vacaciones']").next().text().replace("Inicio ",""));
                }
                // Si se repite el día de inicio de vacaciones, se elimina la clase vacaciones en los días de la leyenda que no contiene la información de las vacaciones.
                if(contenedor.find("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").length>1){
                  contenedor.find("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").each(function(){
                    if($(this).next().text().indexOf(" Vacaciones ")<0){
                      $(this).removeClass("vacaciones");
                    }
                  });
                }
                // Si se repite el día de fin de vacaciones, se elimina la clase vacaciones en los días de la leyenda que no contiene la información de las vacaciones.
                if(contenedor.find("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").length>1){
                  contenedor.find("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").each(function(){
                    if($(this).next().text().indexOf(" Vacaciones ")<0){
                      $(this).removeClass("vacaciones");
                    }               
                    // Se elimina el día de final de vacaciones en la leyenda para unificarlo con el día de inicio.
                    else if($(this).next().text().indexOf(" Vacaciones ")>=0){
                      $(this).next().remove();
                      $(this).remove();
                    }
                  });
                  // Se modifica el inicio de vacaciones en la leyenda para añadir el día de fin de vacaciones.
                  contenedor.find("#div_leyenda h4[class='vacaciones']").text(response.inicio_vacaciones["dia"]+"-"+response.fin_vacaciones["dia"]);
                  contenedor.find("#div_leyenda h4[class='vacaciones']").next().text(contenedor.find("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").next().text().replace("Inicio ",""));
                }
              }, 20);
            }
          }
          else if(response.inicio_vacaciones){
            var date = new Date(año,mes,1);
            var fecha_ultimoDia = new Date(date.getFullYear(), date.getMonth() , 0).toString().split(" ");
            último_día=fecha_ultimoDia[2];
            
            setTimeout(function(){
            // Si se repite el día de inicio de vacaciones, se asigna la clase vacaciones al día de inicio de las vacaciones.
            if(contenedor.find("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").length>1){
              contenedor.find("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").each(function(){
                if($(this).next().text().indexOf(" Vacaciones ")>0){
                  $(this).addClass("vacaciones");   
                }
              });
              // Se comprueba que el festivo está tras las vacaciones en la leyenda, en caso contrario se modifica.
              if(contenedor.find("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").closest("h4[id='"+response.inicio_vacaciones["dia"]+"']").text()==response.inicio_vacaciones["dia"]){
                vacaciones=contenedor.find("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").next().text();
                contenedor.find("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").next().remove();
                contenedor.find("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").remove();

                $( "<h4 id='"+response.inicio_vacaciones["dia"]+"'class='vacaciones'>"+response.inicio_vacaciones["dia"]+"</h4><h4 id='h4_descripcion'>"+vacaciones+"</h4>" ).insertBefore( contenedor.find("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class!='vacaciones']")); 
              }
            }
            else{
              contenedor.find("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").addClass("vacaciones");
            }
            // Se modifica los días desde inicio de vacaciones hasta final de mes puesto que sigue en el siguiente mes.
            for(var i = response.inicio_vacaciones["dia"]; i <= último_día; i++){
              contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a").filter(function(){return $(this).text()==i;}).closest("td").addClass("ui-datepicker-unselectable");
              contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a").filter(function(){return $(this).text()==i;}).closest("td a").addClass("ui-state-disabled");
              
              contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'][class='ui-datepicker-unselectable'] a").filter(function(){return $(this).text()==i;}).attr("title",contenedor.find("#div_leyenda h4[class='vacaciones']").next().text().replace("Inicio ",""));
            }

            }, 50);
          }
          else if(response.fin_vacaciones){

            setTimeout(function(){
            //Se añade la clase vacaciones, solo a los elementos de ese día que contiene la información de las vacaciones.
            if(contenedor.find("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").length>1){
              contenedor.find("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").each(function(){
                if($(this).next().text().indexOf(" Vacaciones ")>0){
                  $(this).addClass("vacaciones");   
                }
              });
              // Se comprueba que el festivo está tras las vacaciones en la leyenda, en caso contrario se modifica.
              if(contenedor.find("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class='vacaciones']").closest("h4[id='"+response.fin_vacaciones["dia"]+"']").text()==response.fin_vacaciones["dia"]){
                vacaciones=contenedor.find("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class='vacaciones']").next().text();
                contenedor.find("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class='vacaciones']").next().remove();
                contenedor.find("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class='vacaciones']").remove();

                $( "<h4 id='"+response.fin_vacaciones["dia"]+"'class='vacaciones'>"+response.fin_vacaciones["dia"]+"</h4><h4 id='h4_descripcion'>"+vacaciones+"</h4>" ).insertBefore( contenedor.find("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class!='vacaciones']")); 
              }
            }
            else{
              contenedor.find("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").addClass("vacaciones");
            }
            // Se modifica los días desde inicio del mes hasta final de vacaciones puesto que sigue en el mes anterior.
            for(var i = response.fin_vacaciones["dia"]; i >= 1; i--){
              contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a").filter(function(){return $(this).text()==i;}).closest("td").addClass("ui-datepicker-unselectable");
              contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a").filter(function(){return $(this).text()==i;}).closest("td a").addClass("ui-state-disabled");

              contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'][class='ui-datepicker-unselectable']").find("a").filter(function(){return $(this).text()==i;}).attr("title",contenedor.find("#div_leyenda h4[class='vacaciones']").next().text().replace("Fin ",""));
            }
            }, 50);
          }
        }
      })

      // Se Comprueba si el último día del mes anterior es un domingo y es festivo, para traspasar el festivo al primer día del mes actual.
      dia_1=contenedor.find("tbody tr:first td:first a");
      if( dia_1.text() == "1" ){
        // Se obtiene un string con los datos el último día del mes anterior (Ej: Sun Jan 31 2016) .
        fecha=new Date(dia_1.closest("div").find("div>span:nth-child(2) ").text(), "0"+contenedor.find("tbody tr:first td:first").attr("data-month"), 0).toDateString();
        fecha=fecha.split(" ");
        dia_ant=fecha[2];
        // Asignamos a una variable el mes anterior que correcponda. En datepicker aparece el número del mes-1.
        if(contenedor.find("tbody tr:first td:first").attr("data-month")=="0"){
          mes_ant="12";
        }
        else if(contenedor.find("tbody tr:first td:first").attr("data-month").length=="1"){
          mes_ant=0+contenedor.find("tbody tr:first td:first").attr("data-month");
        }
        else{
          mes_ant=contenedor.find("tbody tr:first td:first").attr("data-month");
        }
        var MES = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
        $.ajax({
          type: 'POST',
          url: Routing.generate('comprobar_festivo'),
          data: {mes:mes_ant, dia:dia_ant},
          dataType: 'json',
          success: function(response) {
            if(response.data!=null)
            {
              dia_1.closest("td").addClass("ui-datepicker-unselectable");
              dia_1.closest("td a").addClass("ui-state-disabled");
              setTimeout(function(){
                contenedor.find("#div_leyenda").prepend("<h4 id='"+dia_1.text()+"'>"+dia_1.text()+"</h4><h4 id='h4_descripcion'>Traslado del Festivo del día "+dia_ant+" de "+MES[contenedor.find("tbody tr:first td:last").attr("data-month")-1]+" </h4>" );
              }, 20);
            }
          }
        })
      }
      setTimeout(function(){
        // Se actualizan los title de los festivos que aún no tiene el atributo asignado.
        contenedor.find("#div_leyenda h4[id!='h4_descripcion']").each(function(){
          dato=$(this).text();
          if((contenedor.find(".ui-datepicker-calendar td").find("a").filter(function(){return $(this).text()==dato;}).attr("title")!="Vacaciones de Semana Santa" && contenedor.find(".ui-datepicker-calendar td").find("a").filter(function(){return $(this).text()==dato;}).attr("title")!="Vacaciones de Navidad") || (contenedor.find(".ui-datepicker-calendar td").find("a").filter(function(){return $(this).text()==dato;}).attr("title")==undefined) ){
            contenedor.find(".ui-datepicker-calendar td").find("a").filter(function(){return $(this).text()==dato;}).attr("title",$(this).next("h4").text());
          }
        }); 
        // Se unifica el título de las vacaciones.
        contenedor.find(".ui-datepicker-calendar td a[title^='Inicio de Vacaciones']").each(function(){
          $(this).attr("title",$(this).attr("title").replace("Inicio ",""));
        });
        contenedor.find(".ui-datepicker-calendar td a[title^='Fin de Vacaciones']").each(function(){
          $(this).attr("title",$(this).attr("title").replace("Fin ",""));
        });
      }, 200); 
    }, 170);
  });




  $(document).on('click',"#reserva_equipamiento button",function(event){ 
    contenedor= $(this).closest("div[class^='dashboard']");

    // Se marca el equipamiento seleccionado.
    if(!$(this).hasClass("active")){
      contenedor.find("button").removeClass("active"); 
      $(this).addClass("active");
    }

    // Se llama a la función de comprobar las horas de reserva cuando seleccionamos una instalación y un día.
    if(contenedor.find("#reserva_fecha tbody td a").hasClass("ui-state-default ui-state-active")){
      HorasReserva(contenedor.find("#reserva_modulos"));
    }

    //Asignar el valor del select de en equipamiento!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  });

  //Se simula el hacer click en el calendario, haciendo click en un input oculto que contiene el día deleccionado.
  $(document).on('click',"#dia_seleccionado",function(e){
    contenedor= $(this).closest("div[class^='dashboard']");

    if(contenedor.find("#reserva_equipamiento button").hasClass("active")){
      HorasReserva(contenedor.find("#reserva_modulos"));
    }
  });

  //Se marca las horas de reserva seleccionada.
  $(document).on('click',"#reserva_modulos button",function(event){
    contenedor= $(this).closest("div[class^='dashboard']");

    if($(this).hasClass("active")){
      $(this).removeClass("active");
    }
    else{
      $(this).addClass("active");
    }
  });


  // Función para deshabilitar los botones de las horas reservadas.
  function HorasReserva(contenedor) {
    container=contenedor.closest("div[class^='dashboard']");
    
    fecha=container.find("#dia_seleccionado").val();
    fecha= fecha.split("/");
    fecha= fecha[2]+"-"+fecha[1]+"-"+fecha[0];
    equipamiento=container.find("#reserva_equipamiento button[class*='active']").text();

    // Se comprueba si el usuario tiene reservas en el día indicado y se muestra.
    // Se comprueba si quedan unidades de equipamiento o la instalación está libre para el día indicado.
    $.ajax({
      type: 'POST',
      url: Routing.generate('comprobar_reservas_profesor', {_locale:locale}),
      data: {equipamiento:equipamiento, fecha:fecha},
      dataType: 'json',
      success: function(response) {
        contenedor.find("button").removeClass('disabled')
        //contenedor.find("button").removeClass(); //Elimina todas as clases, pero tambien la clase btn inicial.
        contenedor.find("button").attr('class', 'btn'); //Se resete ale valor de class para borrar las demás clases.

        if(response.data!=null)
        {
          for (var key in response.data) { 
            container.find("#reserva_modulos button[clase="+response.data[key]["horaClase"]+"]").addClass('disabled')
            container.find("#reserva_modulos button[clase="+response.data[key]["horaClase"]+"]").addClass("reservado");
          }
        }
        if(response.data2!=null)
        {
          for (var key in response.data2) { 
            container.find("#reserva_modulos button[clase="+response.data2[key]["horaClase"]+"]").addClass('disabled');
          }
        }
        //Se cambia el estilo de la última columna al seleccionar los demás datos.
        $("#reserva_modulos").removeClass('modulos_disabled');
        
        // Se desactivan las horas de reserva del día actual inferiores a la hora actual.
        var f = new Date();
        if(String(f.getDate()).length==1){
          if(String(f.getMonth()+1).length==1){
            d="0"+f.getDate()+"/0"+(f.getMonth()+1)+"/"+f.getFullYear();
          }
          else{
            d="0"+f.getDate()+"/"+(f.getMonth()+1)+"/"+f.getFullYear();
          }
        }
        else{
          if(String(f.getMonth()+1).length==1){
            d=f.getDate()+"/0"+(f.getMonth()+1)+"/"+f.getFullYear();
          }
          else{
            d=f.getDate()+"/"+(f.getMonth()+1)+"/"+f.getFullYear();
          }
        }

        if(d==container.find("#dia_seleccionado").val()){

          container.find("#reserva_modulos button").each(function(){
            var dt = new Date();
            var time = ('0'+dt.getHours()).slice(-2) + ":" + ('0'+dt.getMinutes()).slice(-2);
   
            if($(this).attr("min").replace(':', '') <= time.replace(':', '')){
              $(this).addClass('disabled');
            }
          });
        }
      }
    })
  }


  //Se selecciona los valores asignados en su correspondiente select oculto.

  //Equipamiento seleccionado
  $(document).on('click',"#reserva_equipamiento button",function(e){
      id=$(this).attr("id");

      $("#cole_backendbundle_reserva_equipamiento option[value='"+id+"']").prop('selected', true);
  });

  //Equipamiento seleccionado.
  $(document).on('click',"#reserva_equipamiento button",function(e){
    id=$(this).attr("id");
    $("#cole_backendbundle_reserva_equipamiento option[value='"+id+"']").prop('selected', true);
  });

  //Día seleccionado.
  $(document).on('click',"#dia_seleccionado",function(e){
    fecha=$(this).val();
    array=fecha.split("/");

    $("#cole_backendbundle_reserva_fecha_date_day").val(array[0]);
    $("#cole_backendbundle_reserva_fecha_date_month").val(array[1]);
    $("#cole_backendbundle_reserva_fecha_date_year").val(array[2]).change;
  });

  //Equipamiento seleccionado.
  $(document).on('click',"#reserva_modulos button",function(e){
    id=$(this).attr("id");
    //Se activa o desactiva los horarios selecionados en los checkbox ocultos.
    if($("#cole_backendbundle_reserva_seleccion_"+id).is(':checked')){
      $("#cole_backendbundle_reserva_seleccion_"+id).prop('checked', false);
    }
    else{
      $("#cole_backendbundle_reserva_seleccion_"+id).prop('checked', true);
    }
    //Se selecciona en la lista el horario seleccionado, aunque sólo vale para validar el formulario ya que se obtiene en el controlador mediante el array.
    if($("#reserva_modulos .active").size()>0){
      $("#cole_backendbundle_reserva_horario option[value='"+id+"']").prop('selected', true);
    }else{
      $("#cole_backendbundle_reserva_horario option[value='']").prop('selected', true);
    }
    //Se quita el foco a cualquier input para que se valide el formulario y se active el botón de reservar.
    $("#cole_backendbundle_reserva_fecha_date_year").blur();

  });

  //Se añade el id del equipamiento en botón de eliminar de la ventana modal para luego generar la ruta.
  $(document).on('click','.container_reserva #btn_eliminar' ,function() {
    id=$(this).closest('.container_reserva').attr("id");
    $("#eliminar_reserva_modal .modal-body").load(Routing.generate("reserva_eliminar", {id:id, _locale:locale}), function(){
    }); 
  });
/*
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
*/






});