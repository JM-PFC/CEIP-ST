$(document).ready(function () {
  //var tabCounter = $('#tabs').data('counter');
  
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