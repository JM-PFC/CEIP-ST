$(document).ready(function () {
  // Redireccionar a la url inicial si se abre alguna url en otra pestaña o ventana nueva.
  var pathname = window.location.pathname;
  if(pathname.indexOf("admin")<0){
    // Se oculta el contenido para que no se vea al redireccionar.
    $("body").addClass("oculto");
    window.location.replace("http://localhost/Symfony/web/app_dev.php/admin/");
  }

	//Bloquear el botón back del navegador.
  if (typeof history.pushState === "function") {
    history.pushState("jibberish", null, null);
    window.onpopstate = function () {
      history.pushState('newjibberish', null, null);
      // Handle the back (or forward) buttons here
      // Will NOT handle refresh, use onbeforeunload for this.
    };
  }
  else{
    var ignoreHashChange = true;
    window.onhashchange = function(){
      if (!ignoreHashChange){
        ignoreHashChange = true;
        window.location.hash = Math.random();
        // Detect and redirect change here
        // Works in older FF and IE9
        // * it does mess with your hash symbol (anchor?) pound sign
        // delimiter on the end of the URL
      }
      else {
        ignoreHashChange = false;   
      }
    };
  }
  
  if($('#tabs>ul:first').children("li").size() == 0){  
    // Si no hay pestañas ocultamos la barra de pestañas para mostrar el logo.
    // No se usa $("#tabs").hide(); porque oculta el favicon de la web.
    $("#tabs").addClass("invisible");
       
    // Se asigna el valor 1 al contador de pestañas.
    $('#tabs').data('counter', 1);
    var tabCounter = $('#tabs').data('counter');
  }

  ///////////////////////////////////
  // Pestañas Menú Administracción //
  ///////////////////////////////////

  for(i=1 ; i<7 ; i++){
      $('#'+i).hide();
  };

  // Se muestra el menú activado por defecto.
  var elem_activo=$(".menu>li[class='activo']").children('a').attr("href");  
  $(elem_activo).show();
  
  //Se añade el título de la opción activa inicial como hash en la barra de navegación.
  //if($("#tabs").is(':hidden')){
  //title=$(".menu>li>a[href="+elem_activo+"]").attr("title");
  //window.location.hash = title;
  //}

  // Se muestra las demás opciones del menú.
  $(".menu>li>a").on("click",function(event){
    //Se comprueba que no es el botón "salir"      
    if (!$(this).parent().hasClass('salir')){
      event.preventDefault();
      if ($(this).closest("li").attr("class") == "activo"){ // Detecta la pestaña actual.
        return;  
      }

      // Se muestra la opción seleccionada y ocultamos el resto.
      for(i=1 ; i<7 ; i++ ){
        $('#'+i).hide();
      };
      $($(this).attr("href")).fadeIn('fast');
      $($(this).attr("href")).attr("style","display: block");


      // Se asigna la clase activo a la opción pulsada.
      $('li').removeClass("activo");
      $(this.parentNode).addClass("activo");
    
      // Se añade el título de la opción como hash si no hay ninguna pestaña abierta.
      //if($("#tabs ul li").length==0)
      //window.location.hash = $($(this)).attr("title");
    }
    //Se elimina el foco del elemento.
    $(this).blur();
  });
       
	var num_tabs = $("#tabs ul li").length +1, //onmousedown='return false;' evita arrastar las pestañas.
  tabTemplate = "<li style=''><a onmousedown='return false;' title='#{title}' href='#{href}' ref='#{ref}'>#{label}</a> <span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>",
	tabs = $( "#tabs" ).tabs();


  function esVocal(letra) 
  {
    letra.toLowerCase()
    if(letra=="a" || letra=="e" || letra=="é" || letra=="i" || letra=="í" || letra=="o" || letra=="ó"|| letra=="u" )
      return true;
    else
       return false;   
  }

 	// Función que añade una nueva pestaña.
	function addTab() 
	{
    // Se actualiza el contador según el número de pestañas.
    tabCounter = $('#tabs>ul:first').children("li").size()+1;

		var label = tabTitle || "Tab " + tabCounter;
    if(label.length>21)
    {
      if(esVocal(label.substring(20, 21))){
        if(label.length==22)
          label=label.substring(0, 22);
        else if(!esVocal(label.substring(21, 22)))
          label=label.substring(0, 22)+"...";
        else
          label=label.substring(0, 21)+"...";
      }
      else{
        if(label.length==22)
          label=label.substring(0, 22);
        else if(!esVocal(label.substring(21, 22)))
          label=label.substring(0, 20)+"...";
        else if(esVocal(label.substring(19, 20))){
          label=label.substring(0, 21)+"...";
        }
        else{
          label=label.substring(0, 20)+"...";
        }
      }
    }
    

		id = "tabs-" + tabCounter;
		li = $( tabTemplate.replace( /#\{title\}/g, tabTitle ).replace( /#\{href\}/g, "#" + id ).replace( /#\{ref\}/g, tabRef ).replace( /#\{label\}/g, label ) );
		tabs.find( ".ui-tabs-nav" ).append( li );
    //Se mueve detrás de las pestañas la opción next.
    jQuery('#next_tab').appendTo('.ui-tabs-nav');
	  tabs.append( "<div id='" + id + "'></div>" );
		tabs.tabs( "refresh" );
	}

	// Icono cerrar: Cierra la pestaña seleccionada.
	tabs.delegate( "span.ui-icon-close", "click", function() 
	{
    // Se actualiza el contador según el número de pestañas restantes.
    tabCounter = $('#tabs>ul:first').children("li").size()-1;
		
    n_total= $("#tabs>ul:first").children("li").size();
		elemento=$( this ).closest( "li" ).attr( "aria-controls" );
		siguiente=$(this).closest("li").next().attr( "aria-controls" );
    ultimo=$('#tabs>ul>li:last').attr("aria-controls");
    primero=$('#tabs>ul>li:first').attr("aria-controls");

		selec=$("#tabs>ul>li[aria-controls='"+elemento+"']").attr("aria-selected");
		selec_elem=$("#tabs>ul>li[aria-selected='true']").attr("aria-controls");
                  
    // Variables para el hash de la barra de navegación inicial.
    //ref_padre =$("#tabs>ul>li[aria-selected='true']>a").attr("ref").substr(0, 1);
    //padre=$(".menu li a[href='#"+ref_padre+"']").attr("title");
    //new_title=padre+"/"+$("#tabs>ul>li[aria-selected='true']>a").attr("title");
		
    var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
	
		$( "#" + panelId ).remove();
          			
   	$("#tabs>ul>li").attr("aria-selected","false");
    $("#tabs>ul>li").attr("aria-expanded","false");
    $("#tabs>ul>li").attr("tabindex","-1");
    $("#tabs>ul>li").attr("class","ui-state-default ui-corner-top ");

		$("#tabs>div").attr("aria-hidden","true");
   	$("#tabs>div").attr("style","display: none");
    
    // Se cierra la pestaña abierta.
		if(selec=='true')
		{
			if(n_total==1)
			{
      // Se muestra el div con el logo al cerrar todas las pestañas.
      $(".close_tabs").css("display", "none");
      $( "#tabs" ).addClass( "invisible" );
      $("#fondo_logo").show();
      //window.location.hash =$(".menu li[class='activo']>a").attr("title");
			}
			else if(elemento==ultimo)
			{
      	$("#tabs>ul>li:last").attr("aria-selected","true");
      	$("#tabs>ul>li:last").attr("aria-expanded","true");
      	$("#tabs>ul>li:last").attr("tabindex","0");
      	$("#tabs>ul>li:last").attr("class","ui-state-default ui-corner-top ui-tabs-active ui-state-active  ");
	       
      	$("#tabs>div:last").attr("aria-hidden","false");
      	$("#tabs>div:last").attr("style","display: block");
            
        //variables para el hash de la barra de navegación para el elemtento actualizado.
        //ref_padre =$("#tabs>ul>li[aria-selected='true']>a").attr("ref").substr(0, 1);
        //padre=$(".menu li a[href='#"+ref_padre+"']").attr("title");
        //new_title=padre+"/"+$("#tabs>ul>li[aria-selected='true']>a").attr("title");
        //window.location.hash =new_title;
			}
			else{
      	$("#" + siguiente).attr("aria-hidden","false");
      	$("#" + siguiente).attr("style","display: block");

			  new_elem=$("#tabs>ul>li[aria-controls='"+siguiente+"']");
				
			  $(new_elem).attr("aria-selected","true");
			  $(new_elem).attr("aria-expanded","true");
			  $(new_elem).attr("tabindex","0");
			  $(new_elem).attr("class","ui-state-default ui-corner-top ui-tabs-active ui-state-active ");
            
        // Se reorganiza el id de todas las pestañas restantes.
        for (i = 2; i <= tabCounter+1; i++){
          id = "tabs-" + (i-1);
          $("#tabs>ul li:nth-child("+i+")").attr("aria-controls",id);
          $("#tabs>ul li:nth-child("+i+") a").attr("href",'#'+id);
          $("#tabs>ul li:nth-child("+i+")").attr("aria-labelledby",'ui-id-'+(i-1));
          $("#tabs>ul li:nth-child("+i+") a").attr("id",'ui-id-'+(i-1));
          $("#tabs>div:nth-child("+(i)+")").attr("id",id);     
        }
        //variables para el hash de la barra de navegación para el elemtento actualizado.
        //ref_padre =$("#tabs>ul>li[aria-selected='true']>a").attr("ref").substr(0, 1);
        //padre=$(".menu li a[href='#"+ref_padre+"']").attr("title");
        //new_title=padre+"/"+$("#tabs>ul>li[aria-selected='true']>a").attr("title");
        //window.location.hash =new_title;
      }
		}
		// Se cierra otra pestaña cualquiera.
    else{
	  	$("#" + selec_elem).attr("aria-hidden","false");
	   	$("#" + selec_elem).attr("style","display: block");

		  new_elem=$("#tabs>ul>li[aria-controls='"+selec_elem+"']");
				
		  $(new_elem).attr("aria-selected","true");
		  $(new_elem).attr("aria-expanded","true");
		  $(new_elem).attr("tabindex","0");
		  $(new_elem).attr("class","ui-state-default ui-corner-top ui-tabs-active ui-state-active ");

      // Se reorgaiza el id de todas las pestañas restantes.
      for (i = 2; i <= tabCounter+1; i++){
        id = "tabs-" + (i-1);
        $("#tabs>ul li:nth-child("+i+")").attr("aria-controls",id);
        $("#tabs>ul li:nth-child("+i+") a").attr("href",'#'+id);
        $("#tabs>ul li:nth-child("+i+")").attr("aria-labelledby",'ui-id-'+(i-1));
        $("#tabs>ul li:nth-child("+i+") a").attr("id",'ui-id-'+(i-1));
        $("#tabs>div:nth-child("+(i)+")").attr("id",id);     
      }

      //variables para el hash de la barra de navegación para el elemtento actualizado.
      //ref_padre =$("#tabs>ul>li[aria-selected='true']>a").attr("ref").substr(0, 1);
      //padre=$(".menu li a[href='#"+ref_padre+"']").attr("title");
      //new_title=padre+"/"+$("#tabs>ul>li[aria-selected='true']>a").attr("title");
      //window.location.hash =new_title;
		}
    //Se oculta prev y next de las pestañas si hay menos de 7.
    if(n_total-1<=6){
      $("#prev_tab").attr("style","display:none");
      $("#next_tab").attr("style","display:none");
    } 
    //Se muestra si hay pestaña siguiente oculta o en otro caso la anterior oculta.
    if($(".ui-tabs-nav li").last().attr("style")!=""){
      $(".ui-tabs-nav").find("li[style='']").last().next().attr("style","");
    }
    else if($(".ui-tabs-nav li").first().attr('style')!=""){
      $(".ui-tabs-nav").find("li[style='']").first().prev().attr("style","");
    }

    //Se oculta "next" si no hay más pestañas a la derecha.
    if($(".ui-tabs-nav").find("li").last().attr("style")==""){
      $("#next_tab").addClass("ui-state-disabled");
    }
	});

	tabs.bind( "keyup", function( event ) 
	{
		if ( event.altKey && event.keyCode === $.ui.keyCode.BACKSPACE ) 
		{
			var panelId = tabs.find( ".ui-tabs-active" ).remove().attr( "aria-controls" );
			$( "#" + panelId ).remove();
			tabs.tabs( "refresh" );
		}
	});

  // Se añade una referencia a a cada enlace con una variable "ref".
  $(".contenedor>div").each(function(i){
    id_div=$(this).attr("id");

    $(this).find("div a").each(function(j){
      $(this).attr("ref",id_div+"."+(j+1));
    });
  });
   	      
  // Se selecciona una opción del menú.
  $(".contenedor div div").on("click", "a:not(.no_tab)",function(event) {
  	event.preventDefault(); //cancela el comportamiento por defecto
    // Se cierra las notificaciones.
    PNotify.removeAll();

    n_total= $('#tabs>ul:first').children("li").size();
 	  tabTitle = $(this).attr("title");
 	  tabRef = $(this).attr("ref");
    tab_active= $(".ui-tabs-nav li[class*='ui-tabs-active']").attr("aria-controls");

    //variables para el hash de la barra de navegación.
    //ref_padre =tabRef.substr(0, 1);
    //padre=$(".menu li a[href='#"+ref_padre+"']").attr("title");
    //new_title=padre+"/"+$(this).attr("title");

    //Comprueba que no está abierta esa pestaña.
 	  var unico=0;
    for (i = 0; i < n_total; i++){
		  var ref2= $('#tabs>ul>li:eq('+i+') a').attr("ref");
  	  if(tabRef==ref2)
  	    unico=1;
    }
    if(n_total<=14 || (n_total<=15 && unico==1)){	
	    if(unico==0){

        //Se activa el prev y next de las pestañas.
        if(n_total>=6){
          $("#prev_tab").attr("style","");
          $("#next_tab").attr("style","");

          $(".ui-tabs-nav").find("li[style!='display:none']").first().attr("style","display:none");
          $("#prev_tab").removeClass("ui-state-disabled");
          $("#next_tab").addClass("ui-state-disabled");
        }

        addTab();
          
        // Se elimina los eventos asociados a los elementos a (para que no se repitan los eventos onclick)
        $("#tabs>ul>li a").off();

    	  var active="aria-controls='#tabs-"+tabCounter+"'";

        $("#tabs>ul>li").attr("aria-selected","false");
    	  $("#tabs>ul>li").attr("aria-expanded","false");
    	  $("#tabs>ul>li").attr("tabindex","-1");
    	  $("#tabs>ul>li").attr("class","ui-state-default ui-corner-top");

     	  $("#tabs>ul>li:last").attr("aria-selected","true");
   		  $("#tabs>ul>li:last").attr("aria-expanded","true");
   		  $("#tabs>ul>li:last").attr("tabindex","0");
   		  $("#tabs>ul>li:last").attr("class","ui-state-default ui-corner-top ui-tabs-active ui-state-active ");

 			  $("#tabs>div").attr("aria-hidden","true");
 			  $("#tabs>div").attr("style","display: none");
    		
 			  $("#tabs-"+tabCounter).attr("aria-hidden","false");
			  $("#tabs-"+tabCounter).attr("style","display: block");
        //Se usa tabCounter+1 para diferenciar el primer elemento de la lista que es el div que contiene "prev"
        $("#tabs ul li:nth-child("+(tabCounter+1)+")").attr("aria-labelledby",'ui-id-'+tabCounter);
        $("#tabs ul li:nth-child("+(tabCounter+1)+") a").attr("id",'ui-id-'+tabCounter);
        $("#tabs>div:nth-child("+(tabCounter+1)+")").attr("aria-labelledby",'ui-id-'+tabCounter); 
        
        $("#tabs-"+tabCounter).html('<div class="ajaxload"><img src="/Symfony/web/bundles/backend/images/loading.gif"/></div>');
        // Se selecciona el contenido del div.
			  $("#tabs-"+tabCounter).load($(this).attr('href'));

        // Se incrementa el contador.
        $('#tabs').data('counter', tabCounter++);

        //window.location.hash =new_title;

        // Se oculta el div con el logo al abrir una pestaña.
        $( "#tabs" ).addClass( "visible" );
        $( "#tabs" ).removeClass( "invisible" );
        $("#fondo_logo").hide();
        $(".close_tabs").css("display", "inline");
        $("#tabs div").animate({scrollTop:0},"fast");
      }
      else{

        $("#tabs>ul>li").attr("aria-selected","false");
    	  $("#tabs>ul>li").attr("aria-expanded","false");
    	  $("#tabs>ul>li").attr("tabindex","-1");
    	  $("#tabs>ul>li").attr("class","ui-state-default ui-corner-top");

    	  elemento=$("#tabs>ul>li>a[ref='"+tabRef +"']").parent();
    		
        $(elemento).attr("aria-selected","true");
        $(elemento).attr("aria-expanded","true");
        $(elemento).attr("tabindex","0");
        $(elemento).attr("class","ui-state-default ui-corner-top ui-tabs-active ui-state-active ");

        activo=elemento.attr("aria-controls");
    	  $("#tabs>div").attr("aria-hidden","true");
    	  $("#tabs>div").attr("style","display: none");

    	  $("#"+activo).attr("aria-hidden","false");
			  $("#"+activo).attr("style","display: block");

        //window.location.hash =new_title;
   	  }
 	  }
    else{
      /*$( "#dialog-message" ).dialog({
 			  	modal: true,buttons: {
 				  	Ok: function() {
 					  	$( this ).dialog( "close" );
 					  }
 				  }
 			  });
 			*/  

      // Se muestra el aviso de pestañas permitidas.
      id=$(".contenedor div[style='display: block;']").attr("id");

      setTimeout(function(){ 
        $("#dialog-message").removeClass("oculto");
        $(".contenedor div[id='"+id+"']").attr("style","display: none;");
        //Se desactiva temporalmente los enlaces del menú almostrar el aviso.
        $(".menu li a").css("pointer-events","none");
        $(".contenido_main .close_tabs button").css("pointer-events","none");  
      }, 200);
      setTimeout(function(){ 
        $("#dialog-message").addClass("oculto");
        $(".contenedor div[id='"+id+"']").attr("style","display: block;"); 
        $(".menu li a").css("pointer-events","visible");
        $(".contenido_main .close_tabs button").css("pointer-events","visible");  
      }, 2000);
 	  }
    event.stopPropagation();
    //Se elimina el foco del elemento.
    $(this).blur();

    if($(".ui-tabs-nav li a[ref='"+tabRef+"']").length > 0 && $(".ui-tabs-nav li a[ref='"+tabRef+"']").closest("li").attr("style")!=""){
      tab_new= $(".ui-tabs-nav li a[ref='"+tabRef+"']").attr("href").split("-");
      tab_activ=tab_active.split("-");

      $(".ui-tabs-nav").find("li[style='']").attr("style","display:none");

      if(parseInt(tab_new[1])<parseInt(tab_activ[1])){

        for (i = tab_new[1]; i <= parseInt(tab_new[1])+5; i++){
        $(".ui-tabs-nav").find("li[aria-controls='tabs-"+i+"']").attr("style","");
        }

      }
      else{
        for (i = parseInt(tab_new[1])-5; i <=parseInt(tab_new[1]); i++){
        $(".ui-tabs-nav").find("li[aria-controls='tabs-"+i+"']").attr("style","");
        }
      }
      
      //Se oculta "next" si no hay más pestañas a la derecha.
      if($(".ui-tabs-nav").find("li").last().attr("style")==""){
        $("#next_tab").addClass("ui-state-disabled");
      }
      else{
        $("#next_tab").removeClass("ui-state-disabled");
      }

      //Se oculta "prev" si no hay más pestañas a la izquierda.
      if($(".ui-tabs-nav").find("li").first().attr("style")==""){
        $("#prev_tab").addClass("ui-state-disabled");
      }
      else{
        $("#prev_tab").removeClass("ui-state-disabled");
      }
    }   
  });  


	$(" span.ui-icon-close ").click(function() { 		
		$("#tabs").tabs("option","remove",1);
	});

  // Se elimina los eventos asociados a los elementos li (para que no se repitan los eventos onclick)
  $("#tabs ul li").off();

  // Se elimina de la lista el valor inicial del select.
  $("select option[value='']").css("display", "none");

  // Se selecciona las pestañas abiertas.
  $(document).on("click","#tabs>ul>li a",function(event){
    event.preventDefault(); //cancela el comportamiento por defecto.
    // Se cierra las notificaciones.
    PNotify.removeAll(); 

    n_total= $("#tabs>ul:first").children("li").size();
    elemento=$(this).attr("id");
    ultimo=$('#tabs>ul>li>a:last').attr("id");
    title=$(this).attr("title");

    //variables para el hash de la barra de navegación.
    //ref_padre =$(this).attr("ref").substr(0, 1);
    //padre=$(".menu li a[href='#"+ref_padre+"']").attr("title");

    //Se desactiva el contenido anterior.
    $("#tabs>ul>li").attr("aria-selected","false");
    $("#tabs>ul>li").attr("aria-expanded","false");
    $("#tabs>ul>li").attr("tabindex","-1");
    $("#tabs>ul>li").attr("class","ui-state-default ui-corner-top ");

    $("#tabs>div").attr("aria-hidden","true");
    $("#tabs>div").attr("style","display: none");
       
    //Activamos el contenido nuevo.
    if(elemento==ultimo){
      $("#tabs>ul>li:last").attr("aria-selected","true");
      $("#tabs>ul>li:last").attr("aria-expanded","true");
      $("#tabs>ul>li:last").attr("tabindex","0");
      $("#tabs>ul>li:last").attr("class","ui-state-default ui-corner-top ui-tabs-active ui-state-active  ");
       
      $("#tabs>div:last").attr("aria-hidden","false");
      $("#tabs>div:last").attr("style","display: block");
    }
    else{
      ref=$(this).attr("href");
      $(ref).attr("aria-hidden","false");
      $(ref).attr("style","display: block");

      elemento=$(this).parent();
      $(elemento).attr("aria-selected","true");
      $(elemento).attr("aria-expanded","true");
      $(elemento).attr("tabindex","0");
      $(elemento).attr("class","ui-state-default ui-corner-top ui-tabs-active ui-state-active ");
    }
    //Se elimina el foco del elemento.
    $(this).blur();
    //Hash actualizado.
    //new_title=padre+"/"+$("#tabs>ul>li[aria-selected='true']>a").attr("title");
    //window.location.hash = new_title;
    event.stopPropagation();    
  });
  
  // Se carga el contenido de las pestañas correspondiente al enlace.
  $(document).on("click","#tabs div div a[id='open_tab']",function(event){
    event.preventDefault();
    href=$(this).attr("href");

  // Se simula el pulsar el botón correspondiente al href del enlace pulsado.
  $(".contenedor a[href='"+href+"']").trigger("click");
    event.stopImmediatePropagation();
  }); 
 
  // Se carga el contenido de los enlaces internos.
  $(document).on("click","#tabs div div a[id!='open_tab']",function(event){
    event.preventDefault();
    if($(this).attr("cargar-href")=="off")
    {
      return false;
    }
 
    var elemento=$("#tabs>div[aria-hidden='false']").attr("id");         
    $("#"+elemento).load(this.href);// Se carga el contenido.     
    event.stopPropagation();    
  }); 

  // Se cierra todas las pestañas abiertas con el botón cerrar.
  $(document).on("click",".close_tabs button ",function(event){
    event.preventDefault();
    // Se lanza el evento click del icono de cerrar en todas las pestañas abiertas.
    $("#tabs ul li span").trigger("click"); 
  }); 

  //Se añade la clase ui-satate-hover a prev y next de las pestañas.
  $(document).on("mouseover","#prev_tab", function () {
    $(this).addClass("ui-state-hover");
  });

  $(document).on("mouseout","#prev_tab", function () {
    $(this).removeClass("ui-state-hover");
  });
  $(document).on("mouseover","#next_tab", function () {
    $(this).addClass("ui-state-hover");
  });

  $(document).on("mouseout","#next_tab", function () {
    $(this).removeClass("ui-state-hover");
  });

  //$("#prev_tab").addClass("ui-state-disabled");
  $(document).on("mousedown","#prev_tab", function () {
    $("#prev_tab a img").css("width","13px");
    $("#prev_tab a img").css("height","13px");
    $("#prev_tab a img").css("padding-top","1px");
    $("#prev_tab a img").css("padding-left","2px");
  });

  $(document).on("mouseup","#prev_tab", function () {
    $("#prev_tab a img").css("width","15px");
    $("#prev_tab a img").css("height","15px");
    $("#prev_tab a img").css("padding-top","0px");
    $("#prev_tab a img").css("padding-left","0px");
  });

  $(document).on("mousedown","#next_tab", function () {
    $("#next_tab a img").css("width","13px");
    $("#next_tab a img").css("height","13px");
    $("#next_tab a img").css("padding-top","1px");
    $("#next_tab a img").css("padding-right","2px");
  });

  $(document).on("mouseup","#next_tab", function () {
    $("#next_tab a img").css("width","15px");
    $("#next_tab a img").css("height","15px");
    $("#next_tab a img").css("padding-top","0px");
    $("#next_tab a img").css("padding-right","0px");
  });

  $(document).on("click","#prev_tab", function () {
    if($(".ui-tabs-nav").find("li").first().attr("style")!=""){
      $(".ui-tabs-nav").find("li[style!='display:none']").first().prev().attr("style","");
      $(".ui-tabs-nav").find("li[style='']").last().attr("style","display:none");
      $("#next_tab").removeClass("ui-state-disabled");
    }
    //Se oculta "prev" si no hay más pestañas a la izquierda.
    if($(".ui-tabs-nav").find("li").first().attr("style")==""){
      $("#prev_tab").addClass("ui-state-disabled");
    }
  });
  $(document).on("click","#next_tab", function () {
    if($(".ui-tabs-nav").find("li").last().attr("style")!=""){
      $(".ui-tabs-nav").find("li[style='']").last().next().attr("style","");
      $(".ui-tabs-nav").find("li[style='']").first().attr("style","display:none");
      $("#prev_tab").removeClass("ui-state-disabled");
    }

    //Se oculta "next" si no hay más pestañas a la derecha.
    if($(".ui-tabs-nav").find("li").last().attr("style")==""){
      $("#next_tab").addClass("ui-state-disabled");
    }
  });

});

// se ejecuta cuando la página completa está completamente cargado,
// incluyendo todos los marcos, objetos e imágenes
$(window).load(function() {
  $(".centrado").removeClass("oculto");
});





/*
$(document).ready(function () {
  // Redireccionar a la url inicial si se abre alguna url en otra pestaña o ventana nueva.
  var pathname = window.location.pathname;
  if(pathname.indexOf("admin")<0){
    // Se oculta el contenido para que no se vea al redireccionar.
    $("body").addClass("oculto");
    window.location.replace("http://localhost/Symfony/web/app_dev.php/admin/");
  }

  //Bloquear el botón back del navegador.
  if (typeof history.pushState === "function") {
    history.pushState("jibberish", null, null);
    window.onpopstate = function () {
      history.pushState('newjibberish', null, null);
      // Handle the back (or forward) buttons here
      // Will NOT handle refresh, use onbeforeunload for this.
    };
  }
  else{
    var ignoreHashChange = true;
    window.onhashchange = function(){
      if (!ignoreHashChange){
        ignoreHashChange = true;
        window.location.hash = Math.random();
        // Detect and redirect change here
        // Works in older FF and IE9
        // * it does mess with your hash symbol (anchor?) pound sign
        // delimiter on the end of the URL
      }
      else {
        ignoreHashChange = false;   
      }
    };
  }
  
  if($('#tabs>ul:first').children("li").size() == 0){  
    // Si no hay pestañas ocultamos la barra de pestañas para mostrar el logo.
    // No se usa $("#tabs").hide(); porque oculta el favicon de la web.
    $("#tabs").addClass("invisible");
       
    // Se asigna el valor 1 al contador de pestañas.
    $('#tabs').data('counter', 1);
    var tabCounter = $('#tabs').data('counter');
  }

  ///////////////////////////////////
  // Pestañas Menú Administracción //
  ///////////////////////////////////

  for(i=1 ; i<7 ; i++){
      $('#'+i).hide();
  };

  // Se muestra el menú activado por defecto.
  var elem_activo=$(".menu>li[class='activo']").children('a').attr("href");  
  $(elem_activo).show();
  
  //Se añade el título de la opción activa inicial como hash en la barra de navegación.
  //if($("#tabs").is(':hidden')){
  //title=$(".menu>li>a[href="+elem_activo+"]").attr("title");
  //window.location.hash = title;
  //}

  // Se muestra las demás opciones del menú.
  $(".menu>li>a").on("click",function(event){
    //Se comprueba que no es el botón "salir"      
    if (!$(this).parent().hasClass('salir')){
      event.preventDefault();
      if ($(this).closest("li").attr("class") == "activo"){ // Detecta la pestaña actual.
        return;  
      }

      // Se muestra la opción seleccionada y ocultamos el resto.
      for(i=1 ; i<7 ; i++ ){
        $('#'+i).hide();
      };
      $($(this).attr("href")).fadeIn('fast');
      $($(this).attr("href")).attr("style","display: block");


      // Se asigna la clase activo a la opción pulsada.
      $('li').removeClass("activo");
      $(this.parentNode).addClass("activo");
    
      // Se añade el título de la opción como hash si no hay ninguna pestaña abierta.
      //if($("#tabs ul li").length==0)
      //window.location.hash = $($(this)).attr("title");
    }
    //Se elimina el foco del elemento.
    $(this).blur();
  });

  var num_tabs = $("#tabs ul li").length +1,
  tabTemplate = "<li><a title='#{title}' href='#{href}' ref='#{ref}'>#{label}</a> <span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>",
  tabs = $( "#tabs" ).tabs();


  function esVocal(letra) 
  {
    letra.toLowerCase()
    if(letra=="a" || letra=="e" || letra=="é" || letra=="i" || letra=="í" || letra=="o" || letra=="ó"|| letra=="u" )
      return true;
    else
       return false;   
  }

  // Función que añade una nueva pestaña.
  function addTab() 
  {
    // Se actualiza el contador según el número de pestañas.
    tabCounter = $('#tabs>ul:first').children("li").size()+1;

    var label = tabTitle || "Tab " + tabCounter;
    if(label.length>19)
    {
      if(esVocal(label.substring(18, 19))){
        if(label.length==20)
          label=label.substring(0, 20);
        else if(!esVocal(label.substring(19, 20)))
          label=label.substring(0, 20)+"...";
        else
          label=label.substring(0, 19)+"...";
      }
      else{
        if(label.length==20)
          label=label.substring(0, 20);
        else if(!esVocal(label.substring(19, 20)))
          label=label.substring(0, 18)+"...";
        else if(esVocal(label.substring(17, 18))){
          label=label.substring(0, 19)+"...";
        }
        else{
          label=label.substring(0, 18)+"...";
        }
      }
    }
    

    id = "tabs-" + tabCounter;
    li = $( tabTemplate.replace( /#\{title\}/g, tabTitle ).replace( /#\{href\}/g, "#" + id ).replace( /#\{ref\}/g, tabRef ).replace( /#\{label\}/g, label ) );
    tabs.find( ".ui-tabs-nav" ).append( li );
    //Se mueve detrás de las pestañas la opción next.
    jQuery('#next_tab').appendTo('.ui-tabs-nav');
    tabs.append( "<div id='" + id + "'></div>" );
    tabs.tabs( "refresh" );
  }

  // Icono cerrar: Cierra la pestaña seleccionada.
  tabs.delegate( "span.ui-icon-close", "click", function() 
  {
    // Se actualiza el contador según el número de pestañas restantes.
    tabCounter = $('#tabs>ul:first').children("li").size()-1;
    
    n_total= $("#tabs>ul:first").children("li").size();
    elemento=$( this ).closest( "li" ).attr( "aria-controls" );
    siguiente=$(this).closest("li").next().attr( "aria-controls" );
    ultimo=$('#tabs>ul>li:last').attr("aria-controls");
    primero=$('#tabs>ul>li:first').attr("aria-controls");

    selec=$("#tabs>ul>li[aria-controls='"+elemento+"']").attr("aria-selected");
    selec_elem=$("#tabs>ul>li[aria-selected='true']").attr("aria-controls");
                  
    // Variables para el hash de la barra de navegación inicial.
    //ref_padre =$("#tabs>ul>li[aria-selected='true']>a").attr("ref").substr(0, 1);
    //padre=$(".menu li a[href='#"+ref_padre+"']").attr("title");
    //new_title=padre+"/"+$("#tabs>ul>li[aria-selected='true']>a").attr("title");
    
    var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
  
    $( "#" + panelId ).remove();
                
    $("#tabs>ul>li").attr("aria-selected","false");
    $("#tabs>ul>li").attr("aria-expanded","false");
    $("#tabs>ul>li").attr("tabindex","-1");
    $("#tabs>ul>li").attr("class","ui-state-default ui-corner-top ");

    $("#tabs>div").attr("aria-hidden","true");
    $("#tabs>div").attr("style","display: none");
    
    // Se cierra la pestaña abierta.
    if(selec=='true')
    {
      if(n_total==1)
      {
      // Se muestra el div con el logo al cerrar todas las pestañas.
      $(".close_tabs").css("display", "none");
      $( "#tabs" ).addClass( "invisible" );
      $("#fondo_logo").show();
      //window.location.hash =$(".menu li[class='activo']>a").attr("title");
      }
      else if(elemento==ultimo)
      {
        $("#tabs>ul>li:last").attr("aria-selected","true");
        $("#tabs>ul>li:last").attr("aria-expanded","true");
        $("#tabs>ul>li:last").attr("tabindex","0");
        $("#tabs>ul>li:last").attr("class","ui-state-default ui-corner-top ui-tabs-active ui-state-active  ");
         
        $("#tabs>div:last").attr("aria-hidden","false");
        $("#tabs>div:last").attr("style","display: block");
            
        //variables para el hash de la barra de navegación para el elemtento actualizado.
        //ref_padre =$("#tabs>ul>li[aria-selected='true']>a").attr("ref").substr(0, 1);
        //padre=$(".menu li a[href='#"+ref_padre+"']").attr("title");
        //new_title=padre+"/"+$("#tabs>ul>li[aria-selected='true']>a").attr("title");
        //window.location.hash =new_title;
      }
      else{
        $("#" + siguiente).attr("aria-hidden","false");
        $("#" + siguiente).attr("style","display: block");

        new_elem=$("#tabs>ul>li[aria-controls='"+siguiente+"']");
        
        $(new_elem).attr("aria-selected","true");
        $(new_elem).attr("aria-expanded","true");
        $(new_elem).attr("tabindex","0");
        $(new_elem).attr("class","ui-state-default ui-corner-top ui-tabs-active ui-state-active ");
            
        // Se reorganiza el id de todas las pestañas restantes.
        for (i = 1; i <= tabCounter; i++){
          id = "tabs-" + i;
          $("#tabs ul li:nth-child("+i+")").attr("aria-controls",id);
          $("#tabs ul li:nth-child("+i+") a").attr("href",'#'+id);
          $("#tabs ul li:nth-child("+i+")").attr("aria-labelledby",'ui-id-'+i);
          $("#tabs ul li:nth-child("+i+") a").attr("id",'ui-id-'+i);
          $("#tabs>div:nth-child("+(i+1)+")").attr("id",id);    
        }
        //variables para el hash de la barra de navegación para el elemtento actualizado.
        //ref_padre =$("#tabs>ul>li[aria-selected='true']>a").attr("ref").substr(0, 1);
        //padre=$(".menu li a[href='#"+ref_padre+"']").attr("title");
        //new_title=padre+"/"+$("#tabs>ul>li[aria-selected='true']>a").attr("title");
        //window.location.hash =new_title;
      }
    }
    // Se cierra otra pestaña cualquiera.
    else{
      $("#" + selec_elem).attr("aria-hidden","false");
      $("#" + selec_elem).attr("style","display: block");

      new_elem=$("#tabs>ul>li[aria-controls='"+selec_elem+"']");
        
      $(new_elem).attr("aria-selected","true");
      $(new_elem).attr("aria-expanded","true");
      $(new_elem).attr("tabindex","0");
      $(new_elem).attr("class","ui-state-default ui-corner-top ui-tabs-active ui-state-active ");

      // Se reorgaiza el id de todas las pestañas restantes.
      for (i = 1; i <= tabCounter; i++){
        id = "tabs-" + i;
        $("#tabs ul li:nth-child("+i+")").attr("aria-controls",id);
        $("#tabs ul li:nth-child("+i+") a").attr("href",'#'+id);
        $("#tabs ul li:nth-child("+i+")").attr("aria-labelledby",'ui-id-'+i);
        $("#tabs ul li:nth-child("+i+") a").attr("id",'ui-id-'+i);
        $("#tabs>div:nth-child("+(i+1)+")").attr("id",id); 
            
      }

      //variables para el hash de la barra de navegación para el elemtento actualizado.
      //ref_padre =$("#tabs>ul>li[aria-selected='true']>a").attr("ref").substr(0, 1);
      //padre=$(".menu li a[href='#"+ref_padre+"']").attr("title");
      //new_title=padre+"/"+$("#tabs>ul>li[aria-selected='true']>a").attr("title");
      //window.location.hash =new_title;
    }
  });


  tabs.bind( "keyup", function( event ) 
  {
    if ( event.altKey && event.keyCode === $.ui.keyCode.BACKSPACE ) 
    {
      var panelId = tabs.find( ".ui-tabs-active" ).remove().attr( "aria-controls" );
      $( "#" + panelId ).remove();
      tabs.tabs( "refresh" );
    }
  });

  // Se añade una referencia a a cada enlace con una variable "ref".
  $(".contenedor>div").each(function(i){
    id_div=$(this).attr("id");

    $(this).find("div a").each(function(j){
      $(this).attr("ref",id_div+"."+(j+1));
    });
  });
          
  // Se selecciona una opción del menú.
  $(".contenedor div div").on("click", "a",function(event) {
    event.preventDefault(); //cancela el comportamiento por defecto

    n_total= $('#tabs>ul:first').children("li").size();
    tabTitle = $(this).attr("title");
    tabRef = $(this).attr("ref");

    //variables para el hash de la barra de navegación.
    //ref_padre =tabRef.substr(0, 1);
    //padre=$(".menu li a[href='#"+ref_padre+"']").attr("title");
    //new_title=padre+"/"+$(this).attr("title");

    //Comprueba que no está abierta esa pestaña.
    var unico=0;
    for (i = 0; i < n_total; i++){
      var ref2= $('#tabs>ul>li:eq('+i+') a').attr("ref");
      if(tabRef==ref2)
        unico=1;
    }
    if(n_total<=6 || (n_total<=7 && unico==1)){ 
      if(unico==0){

        addTab();
          
        // Se elimina los eventos asociados a los elementos a (para que no se repitan los eventos onclick)
        $("#tabs>ul>li a").off();

        var active="aria-controls='#tabs-"+tabCounter+"'";

        $("#tabs>ul>li").attr("aria-selected","false");
        $("#tabs>ul>li").attr("aria-expanded","false");
        $("#tabs>ul>li").attr("tabindex","-1");
        $("#tabs>ul>li").attr("class","ui-state-default ui-corner-top");

        $("#tabs>ul>li:last").attr("aria-selected","true");
        $("#tabs>ul>li:last").attr("aria-expanded","true");
        $("#tabs>ul>li:last").attr("tabindex","0");
        $("#tabs>ul>li:last").attr("class","ui-state-default ui-corner-top ui-tabs-active ui-state-active ");

        $("#tabs>div").attr("aria-hidden","true");
        $("#tabs>div").attr("style","display: none");
        
        $("#tabs-"+tabCounter).attr("aria-hidden","false");
        $("#tabs-"+tabCounter).attr("style","display: block");
          
        $("#tabs ul li:nth-child("+(tabCounter)+")").attr("aria-labelledby",'ui-id-'+tabCounter);
        $("#tabs ul li:nth-child("+(tabCounter)+") a").attr("id",'ui-id-'+tabCounter);
        $("#tabs>div:nth-child("+(tabCounter+1)+")").attr("aria-labelledby",'ui-id-'+tabCounter); 

        // Se selecciona el contenido del div.
        $("#tabs-"+tabCounter).load($(this).attr('href'));
          
        // Se incrementa el contador.
        $('#tabs').data('counter', tabCounter++);

        //window.location.hash =new_title;

        // Se oculta el div con el logo al abrir una pestaña.
        $( "#tabs" ).addClass( "visible" );
        $( "#tabs" ).removeClass( "invisible" );
        $("#fondo_logo").hide();
        $(".close_tabs").css("display", "inline");
        $("#tabs div").animate({scrollTop:0},"fast");
      }
      else{

        $("#tabs>ul>li").attr("aria-selected","false");
        $("#tabs>ul>li").attr("aria-expanded","false");
        $("#tabs>ul>li").attr("tabindex","-1");
        $("#tabs>ul>li").attr("class","ui-state-default ui-corner-top");

        elemento=$("#tabs>ul>li>a[ref='"+tabRef +"']").parent();
        
        $(elemento).attr("aria-selected","true");
        $(elemento).attr("aria-expanded","true");
        $(elemento).attr("tabindex","0");
        $(elemento).attr("class","ui-state-default ui-corner-top ui-tabs-active ui-state-active ");

        activo=elemento.attr("aria-controls");
        $("#tabs>div").attr("aria-hidden","true");
        $("#tabs>div").attr("style","display: none");

        $("#"+activo).attr("aria-hidden","false");
        $("#"+activo).attr("style","display: block");

        //window.location.hash =new_title;
      }
    }
    else{
      /*$( "#dialog-message" ).dialog({
          modal: true,buttons: {
            Ok: function() {
              $( this ).dialog( "close" );
            }
          }
        });
      

      // Se muestra el aviso de pestañas permitidas.
      id=$(".contenedor div[style='display: block;']").attr("id");

      setTimeout(function(){ 
        $("#dialog-message").removeClass("oculto");
        $(".contenedor div[id='"+id+"']").attr("style","display: none;");
        //Se desactiva temporalmente los enlaces del menú almostrar el aviso.
        $(".menu li a").css("pointer-events","none");
        $(".contenido_main .close_tabs button").css("pointer-events","none");  
      }, 200);
      setTimeout(function(){ 
        $("#dialog-message").addClass("oculto");
        $(".contenedor div[id='"+id+"']").attr("style","display: block;"); 
        $(".menu li a").css("pointer-events","visible");
        $(".contenido_main .close_tabs button").css("pointer-events","visible");  
      }, 2000);
    }
    event.stopPropagation();
    //Se elimina el foco del elemento.
    $(this).blur();   
  });  


  $(" span.ui-icon-close ").click(function() {    
    $("#tabs").tabs("option","remove",1);
  });

  // Se elimina los eventos asociados a los elementos li (para que no se repitan los eventos onclick)
  $("#tabs ul li").off();

  // Se elimina de la lista el valor inicial del select.
  $("select option[value='']").css("display", "none");

  // Se selecciona las pestañas abiertas.
  $(document).on("click","#tabs>ul>li a",function(event){
    event.preventDefault(); //cancela el comportamiento por defecto.
    n_total= $("#tabs>ul:first").children("li").size();
    elemento=$(this).attr("id");
    ultimo=$('#tabs>ul>li>a:last').attr("id");
    title=$(this).attr("title");

    //variables para el hash de la barra de navegación.
    //ref_padre =$(this).attr("ref").substr(0, 1);
    //padre=$(".menu li a[href='#"+ref_padre+"']").attr("title");

    //Se desactiva el contenido anterior.
    $("#tabs>ul>li").attr("aria-selected","false");
    $("#tabs>ul>li").attr("aria-expanded","false");
    $("#tabs>ul>li").attr("tabindex","-1");
    $("#tabs>ul>li").attr("class","ui-state-default ui-corner-top ");

    $("#tabs>div").attr("aria-hidden","true");
    $("#tabs>div").attr("style","display: none");
       
    //Activamos el contenido nuevo.
    if(elemento==ultimo){
      $("#tabs>ul>li:last").attr("aria-selected","true");
      $("#tabs>ul>li:last").attr("aria-expanded","true");
      $("#tabs>ul>li:last").attr("tabindex","0");
      $("#tabs>ul>li:last").attr("class","ui-state-default ui-corner-top ui-tabs-active ui-state-active  ");
       
      $("#tabs>div:last").attr("aria-hidden","false");
      $("#tabs>div:last").attr("style","display: block");
    }
    else{
      ref=$(this).attr("href");
      $(ref).attr("aria-hidden","false");
      $(ref).attr("style","display: block");

      elemento=$(this).parent();
      $(elemento).attr("aria-selected","true");
      $(elemento).attr("aria-expanded","true");
      $(elemento).attr("tabindex","0");
      $(elemento).attr("class","ui-state-default ui-corner-top ui-tabs-active ui-state-active ");
    }
    //Se elimina el foco del elemento.
    $(this).blur();
    //Hash actualizado.
    //new_title=padre+"/"+$("#tabs>ul>li[aria-selected='true']>a").attr("title");
    //window.location.hash = new_title;
    event.stopPropagation();    
  });
  
  // Se carga el contenido de las pestañas correspondiente al enlace.
  $(document).on("click","#tabs div div a[id='open_tab']",function(event){
    event.preventDefault();
    href=$(this).attr("href");

  // Se simula el pulsar el botón correspondiente al href del enlace pulsado.
  $(".contenedor a[href='"+href+"']").trigger("click");
    event.stopImmediatePropagation();
  }); 
 
  // Se carga el contenido de los enlaces internos.
  $(document).on("click","#tabs div div a[id!='open_tab']",function(event){
    event.preventDefault();
    if($(this).attr("cargar-href")=="off")
    {
      return false;
    }
 
    var elemento=$("#tabs>div[aria-hidden='false']").attr("id");         
    $("#"+elemento).load(this.href);// Se carga el contenido.     
    event.stopPropagation();    
  }); 

  // Se cierra todas las pestañas abiertas con el botón cerrar.
  $(document).on("click",".close_tabs button ",function(event){
    event.preventDefault();
 
    // Se lanza el evento click del icono de cerrar en todas las pestañas abiertas.
    $("#tabs ul li span").trigger("click"); 
  }); 


});
*/