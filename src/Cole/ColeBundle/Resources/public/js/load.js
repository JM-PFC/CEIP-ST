$(document).ready(function () {
  /*** El siguiente código es el evento para plegar y desplegar cajas. Despliega la caja login. **/
  $('div.usuariologin').click(function() {
    $(this).next().slideToggle();
  });
  ////////////////////////////////////////
  //       Carrusel de imagenes         //
  ////////////////////////////////////////
  $('.carrusel > div:gt(0)').hide();
  setInterval(function(){
  	$('.carrusel > div:first')
  		.fadeOut(1500)
  		.next()
  		.fadeIn(1500)
  		.end()
  		.appendTo('.carrusel');
  },4000);

  ////////////////////////////////////////
  //             Noticias               //
  ////////////////////////////////////////

  $(".imgLiquidFill").imgLiquid({
    fill: true,
    //horizontalAlign: "center",
    //verticalAlign: "center"
    horizontalAlign: "center",
    verticalAlign: "none"
  });

  $(".more").toggle(function() {
    $(this).text("Leer menos...").siblings(".complete").show();
  }, function() {
    $(this).text("Leer mas...").siblings(".complete").hide();
  });
  /*
  $('.story-small img').each(function() {
    var maxWidth = 100; // Max width for the image
    var maxHeight = 100;    // Max height for the image
    var ratio = 0;  // Used for aspect ratio
    var width = $(this).width();    // Current image width
    var height = $(this).height();  // Current image height

    // Check if the current width is larger than the max
    if(width > maxWidth){
      ratio = maxWidth / width;   // get ratio for scaling image
      $(this).css("width", maxWidth); // Set new width
      $(this).css("height", height * ratio);  // Scale height based on ratio
      height = height * ratio;    // Reset height to match scaled image
      width = width * ratio;    // Reset width to match scaled image
    }

    // Check if current height is larger than max
    if(height > maxHeight){
      ratio = maxHeight / height; // get ratio for scaling image
      $(this).css("height", maxHeight);   // Set new height
      $(this).css("width", width * ratio);    // Scale width based on ratio
      width = width * ratio;    // Reset width to match scaled image
      height = height * ratio;    // Reset height to match scaled image
    }
  });
  */
  
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



  ///////////////////////////////////////////
  //         Calendario de Eventos         //
  ///////////////////////////////////////////

  $.ajax({
    type: 'POST',
    url: Routing.generate('obtener_eventos'),
    data: {},
    dataType: 'json',
    success: function(response) {
   for (var i = 0; i < response.data.length; i = i + 1) {
    str=response.data[i].datetime.date;

    year=str.substring(0,4);
    month=str.substring(5,7);
    day=str.substring(8,10);

    var myDate = new Date(year,month-1,day);
    response.data[i].datetime = myDate;
    
    }
      $('#calendar').eCalendar({

        weekDays: ["Dom","Lun","Mar","Mie","Jue","Vie","Sab"],
        firstDayOfWeek: 1, //Calendar starting on monday
        months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        textArrows: {previous: '<', next: '>'},
        eventTitle: 'Eventos',
        url: '',
        events: response.data,

        /*[{ //response.data
          title: '- Event Title 1', 
          description: 'Description 1. Without link', 
          datetime: new Date(2016, 10, 24, 17,10) 
        },
        {
          title: '- Event Title 2', 
          description: 'Description 2. Only with link opening in the same tab', 
          datetime: new Date(2016, 10, 23), 
          url: "https://www.google.com"
        },
        {
          title: 'h. | Event Title 2', 
          description: 'Description 3. With link opening on new tab', 
          datetime: new Date(2016, 10, 23, 16), 
          url: "https://www.google.com", 
          url_blank: true
        },
        {
          title: 'h. | Event Title 2', 
          description: 'Description 4. Only with link opening in the same tab', 
          datetime: new Date(2016, 10, 23, 16), 
          url: "https://www.google.com", 
          url_blank: false
        }]*/
      });
    }
  })
  //Se añade los arrow a los títulos de los eventos.
  setTimeout(function() {
    $(".calendar .c-event-list .title").each (function(){ 
      $(this).prepend("<div class='arrow-right'></div>");
    });

    $(".calendar .c-event-list .description").each (function(){ 
      $(this).attr("style","display: none;");
    });
  }, 300); 
 
  $(document).on('click',".calendar .c-event-list .title",function(event){ 
    event.preventDefault();

    if($(this).find("div").hasClass("arrow-right")){
      $(this).find("div").addClass("arrow-down");
      $(this).find("div").removeClass("arrow-right");
      $(this).next().slideDown("fast");
    }
    else{
      $(this).next().slideUp();
      $(this).find("div").removeClass("arrow-down");
      $(this).find("div").addClass("arrow-right");
    }
  });

  //Ajustes de estilo en los días con evento en el calendario.
  $(document).on('click',"#calendar .c-day",function(event){ 
    event.preventDefault();

    $("#calendar .div-over").addClass("oculto");
    //Se elimina la clase "dia_select" del anterior día seleccionado.
    $(this).parent().find("div[class*=dia_select]").removeClass("dia_select");
    //Se añade la clase dia_select para indicar el día seleccionador.
    $(this).addClass("dia_select");

    if($(this).hasClass("c-event")){
      $("#calendar .c-event-grid .c-event-list>div[class!='div-over']").each(function(){  
        $(this).addClass("oculto");
      }); 

      day=$(this).attr("data-event-day");
        
      $("#calendar .c-event-grid .c-event-title").text("Eventos del día "+ day);

      $("#calendar .c-event-grid .c-event-list .c-event-item[data-event-day='"+day+"']").each(function(){  
        $(this).removeClass("oculto");
      }); 
    }
  });

  $(document).on('mouseover',"#calendar .c-day",function(event){ 
    event.preventDefault();

      day=$(this).attr("data-event-day");

      $("#calendar .c-event-grid .c-event-list .c-event-item[data-event-day='"+day+"']").each(function(){  
        $(this).removeClass("c-event-over");
      });

    if($(this).hasClass("dia_select")){
      $(this).removeClass("c-event-over");
    }
  });

  $(document).on('mouseover',"#calendar .c-event-item",function(event){ 
    event.preventDefault();
    day=$(this).attr("data-event-day");
    $(this).removeClass("c-event-over");
  });

  $(document).on('mouseover',"#calendar .c-event-list",function(event){ 
    event.preventDefault();
    day=$(this).find("div[class='c-event-item']").attr("data-event-day");
    $("#calendar .c-grid .c-day[data-event-day='"+day+"'] ").removeClass("c-event-over");
  });

  //Se incrementa el contador al pulsar sobre un título de un evento para ver la información.
  $(document).on('click',".c-event-item .title",function(event){ 
    event.preventDefault();
    if(!$(element).is(":visible")) {

    }
    id=$(this).attr("data-id");

    $.ajax({
      type: 'POST',
      url: Routing.generate('contador_eventos'),
      data:{id:id}, 
      dataType: 'json',
      success: function(response){

      }
    })
  });







});

