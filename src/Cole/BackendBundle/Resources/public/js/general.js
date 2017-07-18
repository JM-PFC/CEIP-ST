$(document).ready(function () {
  $('.dni').mask('A0000000-S',
  {'translation': {A: {pattern: /[X-Zx-z]|[0-9]/}},
     placeholder: "________-__"});
  $('.telefono').mask('000 00 00 00'); 
  $('.fecha').mask('00/00/0000', {placeholder: "__/__/____"});
  $('.dni').css( "text-transform", "uppercase");

  $("input[id^='año_acamedico']").mask('0000');
  $("input[id^='año_profesional']").mask('00');
  $("input[id^='edit_año_acamedico']").mask('0000');
  $("input[id^='edit_año_profesional']").mask('00');
  $("input[type='time']").mask('00:00', {placeholder: "__:__"});  
  $("#registro_ratio input").mask('00');

  //Se evita arrastar los enlaces del menú.
  $( ".barra_menu a, .barra_menu li" ).attr("onmousedown","return false;");

  //Se establece el color del botón de confirmación de las notificaciones para usar con swal().
  color="#5A88B6";
  color_rojo="#DE5757";
  // Se establece el estilo para pNotify
  //PNotify.prototype.options.styling = "jqueryui";

  // Se establece la pila de los avisos de PNotify
  var right_Stack = {"dir1": "up", "dir2": "left", "context": $(".contenido_main"), "push": "top", "firstpos1": 20, "firstpos2":20};
  var left_Stack = {"dir1": "up", "dir2": "right", "context": $(".contenido_main"), "push": "top","firstpos1": 20, "firstpos2":20};
  var left_Stack_dialog = {"dir1": "up", "dir2": "right", "context": $("body"), "push": "top","firstpos1": 20, "firstpos2":30};
  var right_Stack_dialog = {"dir1": "up", "dir2": "left", "context": $("body"), "push": "top", "firstpos1": 20, "firstpos2":40};

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

  // Se convierte los caracteres con acentos a los mismos sin acentos en la busqueda de DataTable().
  jQuery.fn.DataTable.ext.type.search.string = function ( data ) {
    return ! data ?
        '' :
        typeof data === 'string' ?
            data
                .replace( /έ/g, 'ε' )
                .replace( /[ύϋΰ]/g, 'υ' )
                .replace( /ό/g, 'ο' )
                .replace( /ώ/g, 'ω' )
                .replace( /ά/g, 'α' )
                .replace( /[ίϊΐ]/g, 'ι' )
                .replace( /ή/g, 'η' )
                .replace( /\n/g, ' ' )
                .replace( /á/g, 'a' )
                .replace( /é/g, 'e' )
                .replace( /í/g, 'i' )
                .replace( /ó/g, 'o' )
                .replace( /ú/g, 'u' )
                .replace( /ê/g, 'e' )
                .replace( /î/g, 'i' )
                .replace( /ô/g, 'o' )
                .replace( /è/g, 'e' )
                .replace( /ï/g, 'i' )
                .replace( /ü/g, 'u' )
                .replace( /ã/g, 'a' )
                .replace( /õ/g, 'o' )
                .replace( /ç/g, 'c' )
                .replace( /ì/g, 'i' ) :
            data;
  };

  //Para el caso de que las celdas de las tablas contengan elemento html que contenga texto a buscar.
  // Se configurar un tipo de columna personalizado para quitar etiquetas html y normalizar los caracteres para la búsqueda
  $.fn.dataTableExt.ofnSearch['html-string'] = function(sData) {
  // Etiquetas de tira html (you will need to test this regex for your needs. Source from http://stackoverflow.com/a/25885923/1544886)
    sData = sData.replace(/(&nbsp;|<([^>]+)>)/ig, "");
  // Aplicar carácter normalizador a este tipo de columna
    return jQuery.fn.DataTable.ext.type.search.string(sData);
  }


  /////////////////////////////
  // Métodos para validación //
  /////////////////////////////
  function validateEmpty(field) {
    if ($(field).val() == null) {
      return false;
    }
    if ($(field).val().trim().length == 0) {  
      $(field).prev().append("<span class='error'>Dato Requerido</span>");
      return false;
    }
      return true;
  }

  function validateEmpty_(field) {
    if ($(field).val() == null) {
      return false;
    }
    if ($(field).val().trim().length == 0) {  
      $(field).prev().append("<span class='error'>Dato Requerido</span>");
      return false;
    }
      return true;
  }
  

  function validateWords(field) {
    if(!$(field).val()){
      return true;
    }
    var filter = /^[A-Za-záéíóúÁÉÍÓÚüÜñÑ]{2,}([\s][A-Za-záéíóúÁÉÍÓÚüÜñÑ.\(\)]+)*$/;
    if (!filter.test($(field).val())) {
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false
    }
    else{
      return true;
    }
  }

  function validateLetters(field) {
    if(!$(field).val()){
      return true;
    }
    var filter = /^[^0-9]{2,}$/;
    if (!filter.test($(field).val())) {
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false
    }
    else{
      return true;
    }
  }

    function validateLetterInitial(field) {
    if(!$(field).val()){
      return true;
    }
    var filter = /^[A-Za-záéíóúÁÉÍÓÚüÜñÑ]/;
    if (!filter.test($(field).val())) {
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false
    }
    else{
      return true;
    }
  }

  function validateNumbers(field) {
    if(!$(field).val()){
      return true;
    }
    var filter = /^[0-9]+$/;
    if (!filter.test($(field).val())) {
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false
    }
    else{
      return true;
    }
  }

  function validateLength(field) {
    if(!$(field).val()){
        return true;
    }
    min =  $(field).attr("lengthmin");
    max =  $(field).attr("maxlength");

    if ($(field).val().length==max || $(field).val().length==min ) {
      return true;
    }
    else{
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false;
    }
  }


  function validateDni(field) {
    var filter = /(^([X-Zx-z]{1})([0-9]{7})([-]?)([A-Za-z]{1})$)|((^[0-9]{8})[-]?([A-Za-z]{1}$))/;

    var numero;
    var letr;
    var letra;
    var expresion_regular_dni;
    if (!filter.test($(field).val())) {
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false;
    }
    //Se comprueba que la letra del NIF sea la correcta.
    else{
      /*  Hay que descomentar ésto para que compruebe la letra del DNI (cuando acabemos)!!!!!!!!!!!!!!!!
ut
      //Se le quita el separador para pasar el dni.
      dni=$(field).val().replace("-","");

      numero = dni.substr(0,dni.length-1);
      letr = dni.substr(dni.length-1,1);
      numero = numero % 23;
      //Se crea una string con las letras del abecedario ( sin la ñ) en ese orden.
      letra='TRWAGMYFPDXBNJZSQVHLCKET';
      //Se coge un substring de un solo caracter de esa cadena de letras que empiece en la posición marcada 
      // por el número que conseguimos al hacer la operación de módulo anterior.
      letra=letra.substring(numero,numero+1);
      if (letra!=letr.toUpperCase()) {
        $(field).prev().append("<span class='error'>Dato inválido</span>");
        return false;
      }else{
        return true;
      }

      Hay que borrar el return siguiente cuando al descomentar esto*/  
        return true;

    }
  }

  function validateFecha(field) {
    var filter = /^(0[1-9]|[12][0-9]|3[01])[\/](0[1-9]|1[012])[\/](19|20|21)[0-9]{2,}$/;
    if (!filter.test($(field).val())) {
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false
    }
    else{
      return true;
    }
  }
  
  var actual= new Date();

  function validateFecha_Adulto(field) {
    fecha=$(field).val().split('/');
    if(fecha[2]<(actual.getFullYear()-80) || fecha[2]>(actual.getFullYear()-18)){
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false;
    }
    else{
      return true;
    }
  }

  function validateFecha_Niño(field) {
    fecha=$(field).val().split('/');
    if(fecha[2]<(actual.getFullYear()-18) || fecha[2]>(actual.getFullYear()-2)){
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false;
    }
    else{
      return true;
    }
  }

  function validateAño(field) {
    if(!$(field).val()){
      return true;
    }
    if($(field).val()<(actual.getFullYear()-60) || $(field).val()>(actual.getFullYear())){
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false;
    }
    else{
      return true;
    }
  }
  function validateCP(field) {
    var filter = /^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$/;
    if (!filter.test($(field).val())) {
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false
    }
    else{
      return true;
    }
  }
  
  function validateTelefono(field) {
    if(!$(field).val()){
        return true;
    }
    var filter = /^\d{3}([- .]?\d{2}){3}$/;
    if (!filter.test($(field).val())) {
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false
    }
    else{
      return true;
    }
  }

  function validateEmail(field) {
    if(!$(field).val()){
        return true;
    }
    var filter = /^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
    if (!filter.test($(field).val())) {
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false
    }
    else{
      return true;
    }
  }
  function validateWeb(field) {
    if(!$(field).val()){
        return true;
    }
    var filter = /^(www|http:\/\/)(.*\.(com$|es$|net$|org$))$/ ;
    if (!filter.test($(field).val())) {
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false
    }
    else{
      return true;
    }
  }

  function validateEqual(field) {
    if(!$(field).val()){
        return true;
    }
    if (($(field).attr("id")=="alumno_responsable2_dni" && $(field).val()==$("#alumno_responsable1_dni").val()) ||
         $(field).attr("id")=="alumno_responsable1_dni" && $(field).val()==$("#alumno_responsable2_dni").val()) {
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false
    }
    else{
      return true;
    }
  }

  function validateMaxSize(field) {
    if (($(field))[0].files.length > 0) {
      var sizeByte = ($(field))[0].files[0].size;
      var siezekiloByte = parseInt(sizeByte / 1024);
    }
    if(!$(field).val()){
        return true;
    }
    if(siezekiloByte > $(field).attr('size')){
      return false;
    }
    else{
      return true;
    }
  }

  /*

  if (($(field))[0].files.length > 0) {

      var sizeByte = ($(field))[0].files[0].size;
      var siezekiloByte = parseInt(sizeByte / 1024);
             alert(siezekiloByte);
      if(siezekiloByte > $(field).attr('size')){

        $(field).prev().append("<span class='error'>Dato inválido</span>");
        return false
      }
      else{
        return true;
      }
    }
    else{
      return true;
    }  
  */

  function validateMimeTypes(field) {

    extensiones_permitidas = $(field).attr('mimeTypes').split(",");
    extension = ($(field).val().substring($(field).val().lastIndexOf("."))).toLowerCase();
    // Se comprueba si la extensión está entre las permitidas.
    permitida = false;
    for (var i = 0; i < extensiones_permitidas.length; i++) {
      if (extensiones_permitidas[i] == extension) {
        permitida = true;
        break;
      }
    } 
    if(!$(field).val()){
        return true;
    }
    if(permitida){
      return true;
    }
    else{
      return false;
    }
  }

  var message = {
    "Empty":"Este campo no puede estar vacío.",
    "Words":"Contiene caracteres inválidos o insuficientes.",
    "Letters":"Este campo no puede contener números.",
    "LetterInitial":"Este campo debe empezar con una letra",
    "Numbers":"Este campo solo puede contener números",
    "Length":"La longitud del campo es incorrecta.",
    "Dni":"No es un DNI o NIE válido.",
    "Fecha":"No es una fecha válida.<br> Formato válido: 31/12/2000",
    "Fecha_Adulto":"No es una fecha válida.<br> Introduzca un año del "+ (actual.getFullYear()-80) + " al "+(actual.getFullYear()-18)+".",
    "Fecha_Niño":"No es una fecha válida.<br> Introduzca un año del "+ (actual.getFullYear()-18) + " al "+(actual.getFullYear()-2)+".",
    "Año":"No es un año válido.<br> Introduzca un año del "+ (actual.getFullYear()-60) + " al "+(actual.getFullYear())+".",
    "CP":"No es un código postal válido.",
    "Telefono":"No es un número de teléfono válido.",
    "Email":"No es un email válido.",
    "Web":"No es una direccion web válida.",
    "Equal":"Ya existe este representante del alumno.",
    "MaxSize":"El tamaño supera el limite permitido.",
    "MimeTypes":"No es un archivo válido.",
  };

    function validation(field) {
 
    $(field).prev().find(".error").remove();
    $(field).next(".mensaje").remove();

    var values = $(field).attr('validation').split(",");
    for (var i=0; i<values.length; i++) {
      validate = 'validate' + values[i].trim()+'('+$(field).attr("id")+')';
      isValid=eval(validate);
       
      if (isValid) {
        $(field).removeClass("invalid");
        $(field).attr("validated", true);
        //Se comprueba si hay algún error para habilitar/deshabilitar el botón (submit).
        if($(field).closest("form[id$='_edit']").size()==0){
          if($(field).closest("form").find(":input[class='invalid']").size()>0){
            $(field).closest("form").find("button[id$='_submit']").prop("disabled",true);
          }
          else{
            $(field).closest("form").find("button[id$='_submit']").prop("disabled",false);
          }
        }
        else{

          if($(field).closest("form").find(":input[class='invalid']").size()==0 && $(field).closest("form").find(":input[class='modified']").size()>0){
            $(field).closest("form").find("button[id$='_submit']").prop("disabled",false);
          }
          else{
            $(field).closest("form").find("button[id$='_submit']").prop("disabled",true);
          }
        }
      } 
      else {
        $(field).addClass("invalid");   
        $(field).attr("validated", false);
        if(values[i].trim()!="Empty_"){
          $(field).after("<span class='mensaje'>"+message[values[i].trim()]+"</span>");
        }
        $(field).focus();
        //Se deshabilita el botón (submit).
        $(field).closest("form").find("button[id$='_submit']").prop("disabled",true);
      
        return false;
      }
    }
  }

    function limpiarForm(form) {
    form.each (function(){ 
      this.reset();
    });
    form.find("input[type='radio']").prop("checked", true);

    form.find(":input").prev().find(".error").remove();
    form.find(":input").next(".mensaje").remove();
    form.find(":input").removeClass("invalid");
    form.find("input[validated='true']").attr("validated", "");
    form.find("#leyenda p").empty();
    form.find("input[id$='sexo_0']").prop("checked", true);


    if(form.attr("id")=="alumno_nuevo"){
      $("#foto_no").prop("checked", true);
      $("#alumno_foto").prop("disabled", true);  
      $("#alumno_responsable2_dni").keyup();
    }

    if(form.attr("id").indexOf("busqueda_") >= 0){
      form.find("#contenedor_lista div[id^='lista_']").empty();
    }

    if(form.attr("id").indexOf("padres_nuevo") >= 0){
      form.find("input:first").focus();
    }

    // Si contiene pestañas se activa la primera pestaña.
    if($("div[id='tab-container']").length )
      {
        mostrarTab(form,"tab1");
      }
  }

  // Función para limpiar los campos del formulario.
  $(document).on("click",".limpiar",function(event){
    event.preventDefault();
    form= $(this).closest("form");
    limpiarForm(form);
  });

  // Función para mostrar una pestaña de un formulario dado el formulario y el id del tab.
  function mostrarTab(form, tab) {
    $("form #content-form-menu").find("[id^='tab']").hide(); //Oculta todo el contenido
    $("form #tab-container li").attr("class",""); //Resetea el valor de los id
    $("form li a[name="+tab+"]").parent().attr("class","selected"); //Activa la españa actual
    $("form #content-form-menu #"+ tab).css("display","inline"); //Muestra el contenido de la pestaña actual
    $("form #" +tab+" input:first").focus();
  }

  //Se valida en línea los campos de los formularios al salir de ellos.
  $(document).on('blur','form :input[type!=file]' ,function() {
    if ($(this).attr('validation')) {    
      validation($(this));
    }
  });

  // Se valida en línea los campos de los formularios con errores para no tener que esperar en abandonar el campo.
  $(document).on('keyup','form :input[type!=file][class*="invalid"]' ,function() {
    if ($(this).attr('validation')) {    
      validation($(this));
    }
  });

setInterval(function(){
    // Se añade un índice de tabuláción a todos los input.
  $("form").find(":input[class!='readonly']").each(function(i){
    $(this).attr("tabindex",i+1);
  });
}, 500);


setInterval(function(){
    // Se añade un índice de tabuláción a todos los input.
  $("#tabs #asignar_aula").find(":input").each(function(i){
    $(this).attr("tabindex",i+1);
  });
}, 500);


  // Se muestra y se oculta el mensaje de error en los campos del formulario.
  $(document).on("mouseover","form :input", function () {
      if($(this).next().prop("tagName")=="SPAN"){
        $(this).next().css("display", "block");
      }
    });
  $(document).on("mouseout","form :input[type!=radio]", function () {
      if($(this).next().prop("tagName")=="SPAN"){
        $(this).next().css("display", "none");
      }
    });

  // Para no ocultar el button
  $(document).on("mouseover","form :button", function () {
    $(this).next().css("display", "block");
    }
  );

  // Se obliga a guardar en mayuscula la letra del Dni con el guión de separación de la máscara del campo.
  $(document).on('blur','form .dni' ,function() {
    $(this).val($(this).val().toUpperCase());
  });



  //Efecto para cambiar de pestañas de opciones en el formulario.
  $(document).on("click",'#tab-container a',function(event){
    event.preventDefault();
    form= $(this).closest("form");

    if ($(this).closest("li").attr("class") == "selected"){ //Se detecta la pestaña actual.
      //Se elimina el foco del elemento.
      $(this).blur();
      return;       
    }
    else{            
      form.find("#content-form-menu div[id^='tab']").hide(); // Se oculta todo el contenido.
      form.find("#tab-container li").attr("class",""); // Se resetea el valor de los id.
      $(this).parent().attr("class","selected"); // Se activa la españa actual.
      form.find('#content-form-menu #'+ $(this).attr('name')).css("display","inline"); // Se muestra el contenido de la pestaña actual.
      //$('#' + $(this).attr('name')).fadeIn(); 
      //$("#"+$(this).attr("name")+" input:first").focus();  Se selecciona el primer campo de la pestaña actual.
    }
    //Se elimina el foco del elemento.
    $(this).blur();
  });


  $(document).on("keydown",'#tab-container a', function(e){

    tab=$(this).attr("name");
    if(e.keyCode == 9 || (e.keyCode == 13 ))
    {
      va=$("#"+tab).find(":input").attr("id");
      $("#"+va).focus()
    }
    e.preventDefault();
  });

  // Transforma el texto con capitalize para guardarlo en la base de datos.
  $(document).on("keyup",'input:not([class*="text_transform_none"])', function(){
    var txt = $(this).val();
    $(this).val(txt.replace(/^(.)/g, function($1){ return $1.toUpperCase(); }));
    $(this).css("text-transform", "none");
      if($(this).val().length==0 && !$(this).attr('placeholder')){
        $(this).css("text-transform", "capitalize");
      }
      else if($(this).val().length==2){
        $(this).css("text-transform", "capitalize");
      }
  });

  // Al presionar cualquier tecla en cualquier elemento del formulario se ejectua la siguiente función
  // Función para desplazarse mediante teclado por los campos.
  $(document).on("keydown",':input', function(e){
    // Solo importa si las teclas presionadas fueron TAB o ENTER. (Para ver el código de otras teclas: http://www.webonweboff.com/tips/js/event_key_codes.aspx)
    // Y que no sean botones o textarea.
    if(e.keyCode == 9 || (e.keyCode == 13 && (!($(this).is("textarea")) && !($(this).is("button"))&& ($(this).attr("type")!=("file")) )))
    {
      // Se obtiene el número del tabindex del campo actual.
      var currentTabIndex = $(this).attr('tabindex');
      // Se le suma 1 para tener el siguiente.
      var nextTabIndex    = parseInt(currentTabIndex) + 1;
      // Se obtiene (si existe) el siguiente elemento usando la variable nextTabIndex
      // Se comprueba si estan en una ventana modal o no, para obtenerlo según el div que los contiene.
      if($(this).closest("div[class^='ui-dialog']").attr("class")!=undefined){ //:not(:button)  
        var nextField= $(this).closest("div[class^='ui-dialog']").find(':input[tabindex='+nextTabIndex+']');
      }
      else{
        var nextField= $(this).closest("div[id^='tabs-']").find(':input[tabindex='+nextTabIndex+']');
      }

      // Se salta los elementos no activos.
      while(nextField.attr("disabled")=="disabled")
      {
      var nextTabIndex= parseInt(nextTabIndex) + 1;
      var nextField= $('[tabindex='+nextTabIndex+']');
      }
      // Comprobar si se encontró el elemento.
      if(nextField.length > 0 && nextField.attr("type")!="hidden")
      {
        // Se marca la primera opción del radio de la foto al pasar a ese campo.
        if( $(this).attr("type")!="radio" && nextField.attr("type")=="radio")
        {
          if(!nextField.prop('checked')&& !nextField.nextAll("input").prop('checked')){
            nextField.prop('checked', true);
          }
          else if(nextField.prop('checked')){
            nextField.focus();
            return false;
          }
          else{
            nextField.nextAll("input").focus();
            return false;
          }
        }
        // Comprobar que se pasa al segundo radio de la foto.
        if( $(this).attr("type")=="radio" && nextField.attr("type")=="radio")
        { 
          // Se pasa al siguiente campo si no se sube foto sumando 2 al Tabindex.
          if(nextField.nextAll("input").attr("type")=="file")
          {
            nextTabIndex++;      
          }
          // Se salta al campo file si está habilitado sumando 1 al Tabindex.
          nextTabIndex++;            
          nextField= $(this).closest("div[id^='tabs-']").find(':input[tabindex='+nextTabIndex+']');
        }
        // Se salta al campo file si está habilitado sumando 1 al Tabindex.
        if(nextField.attr("id")=="editar_responsable2_modal" && nextField.closest("div[id='responsable']").hasClass("block-one oculto") ){
          nextTabIndex+=9;
          nextField= $(this).closest("div[id^='tabs-']").find(':input[tabindex='+nextTabIndex+']');
        }
        // Se salta al campo file si está habilitado sumando 1 al Tabindex.
        if(nextField.attr("id")=="editar_responsable2_modal" && nextField.closest("div[id='noresponsable']").hasClass("block-one oculto") ){
          nextTabIndex++;
          nextField= $(this).closest("div[id^='tabs-']").find(':input[tabindex='+nextTabIndex+']');
        }

        if($(this).attr("id")==$(this).closest("form").find("div[id='botones_form'] div:last button").attr("id")){
          nextField=$(this).closest("form").find("div[id='botones_form'] div:first button");
        }
        // Se muestra el tab que contiene el siguiente campo si es diferente al actual.
        tab= $(this).closest("div[id^='tab']").attr("id");
        next_tab=nextField.closest("div[id^='tab']").attr("id");
        if(tab!=next_tab && nextField.attr("type")!="submit")
        {
          mostrarTab($(this).closest("form"),next_tab);
        }
        // Se selecciona el siguiente campo.
        nextField.focus();
      }
      else{
        nextField=$(this).closest("form").find("div[id='botones_form'] div:first button");  
        nextField.focus();    
      }
       // Ignorar el funcionamiento predeterminado (enviar el formulario)
      e.preventDefault();
    }
  });



//////////////////////////////////
//   Formularios de Creacción   //
//////////////////////////////////

// Marcamos el input DNi cuando se modifique para lanzar comprobar_padre.
$(document).on('change',"input[id$='_dni']",function(event){
  $(this).attr("edit","true");
});

$(document).on("blur","input[id$='responsable1_dni']",function() {
    form= $(this).closest("form");
    // Se evita repetirlo al hacer submit con la clase "class" que se ha añadido en el submit.
    // Se comprueba si se ha modificado el input DNI para comprobar si existe. 
    if(!($(this).find("div[id$='alumno_submit']").attr('class')) && $(this).attr("edit")=="true")
    {
      var dni = $(this).val();
      $.ajax({
        type: 'POST',
        url: Routing.generate('comprobar_padre'),
        data: {dni:dni},
        dataType: 'json',
        success: function(response) {
         if((!($(this).find("#tab2").length) || ($("#tab2").attr("style")=="display: inline;"))&& response.success==true )
         {
           /*setTimeout(function() {
              $(".confirm").focus();
            }, 300);       Modal dialog does not focus correctly when show option is set*/
          aviso.play();
          swal({
            title: "Responsable registrado en el sistema",
            html: "El DNI introducido pertenece al responsable: <br><br><h3>"+response.name+"</h3><br>¿Desea obtener sus datos?",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonColor: color,
            confirmButtonText: "¡Adelante!",
            }).then(function () {
              $.ajax({
                type: 'POST',
                url: Routing.generate('obtenerdatos_responsable'),
                data: {dni:dni},
                dataType: 'json',
                success: function(response) {   
                form.find("input[id$='responsable1_nombre']").focus().val(response.data['nombre']).prop('readonly', true);
                form.find("input[id$='responsable1_fechaNacimiento']").focus().val(response.data['fechaNacimiento']).prop('readonly', true);
                form.find("input[id$='responsable1_profesion']").focus().val(response.data['profesion']).prop('readonly', true);
                form.find("input[id$='responsable1_estadoCivil']").focus().val(response.data['estadoCivil']).prop('readonly', true);
                form.find("input[id$='responsable1_movil']").focus().val(response.data['movil']).prop('readonly', true);
                form.find("input[id$='responsable1_email']").focus().val(response.data['email']).prop('readonly', true);
                form.find("input[id$='responsable2_dni']").focus();
                }
              })
            }, function (dismiss) {

              if(form.find("input[id$='responsable1_nombre']").val()==''){
                form.find("input[id$='responsable1_nombre']").removeClass("invalid");
                form.find("input[id$='responsable1_nombre']").prev().find(".error").remove();
                form.find("input[id$='responsable1_nombre']").next(".mensaje").remove();         
              }
              form.find("input[id$='responsable1_dni']").focus();
              form.find("input[id$='responsable1_dni']").val('');
              form.find("input[id$='responsable1_dni']").keyup();
            }
          );
         }   
        } 
      })
      $(this).attr("edit",false);
    }
  });

$(document).on('keyup',"input[id$='responsable1_dni']",function(e){
  form= $(this).closest("form");
    if($(this).val().trim().length == 0){
        form.find("input[id*='responsable1']").each(function(){
          $(this).val("");
          $(this).prev().find(".error").remove();
          $(this).next(".mensaje").remove();
          $(this).removeClass("invalid");
          $(this).attr("validated", false);
          $(this).prop("readonly", false);
        });      
        form.find("input[id$='responsable1_estadoCivil']").prop("readonly", false);  
        form.find("input[id$='responsable1_dni']").prop("disabled", false);
    }
    if(($(this).val().trim().length == 10)){
      form.find("input[id*='responsable1']").each(function(){
        $(this).attr("validated", "");
        $(this).prop("disabled", false);
      });
    }
    if(($(this).val().trim().length < 10) && form.find("input[id$='responsable1_nombre']").attr("readonly")=='readonly'){
        var dni=form.find("input[id$='responsable1_dni']").val();
        form.find("input[id*='responsable1']").each(function(){
          $(this).val("");
          $(this).prev().find(".error").remove();
          $(this).next(".mensaje").remove();
          $(this).removeClass("invalid");
          $(this).attr("validated", false);
          $(this).prop("readonly", false);
        });   
        form.find("input[id$='responsable1_dni']").val(dni);   
        form.find("input[id$='responsable1_estadoCivil']").prop("readonly", false);  
        form.find("input[id$='responsable1_dni']").prop("disabled", false);
    }
  });


$(document).on('keyup',"input[id$='responsable2_dni']",function(e){
  form= $(this).closest("form");

    if($(this).val().trim().length == 0){
        form.find("input[id^='alumno_responsable2_']").each(function(){
          $(this).val("");
          $(this).prev().find(".error").remove();
          $(this).next(".mensaje").remove();
          $(this).removeClass("invalid");
          $(this).attr("validated", true);
          $(this).prop("disabled", true);
        });      
        form.find(":input[id$='responsable2_estadoCivil']").prop("disabled", true);  
        form.find("input[id$='responsable2_dni']").prop("disabled", false);
    }
    if(($(this).val().trim().length == 10)){
      form.find(":input[id^='alumno_responsable2_']").each(function(){
        $(this).attr("validated", "");
        $(this).prop("disabled", false);
      });
    }
    if(($(this).val().trim().length < 10) && form.find("input[id$='responsable2_nombre']").attr("readonly")=='readonly'){
        var dni=form.find("input[id$='responsable2_dni']").val();
        form.find("input[id^='alumno_responsable2_']").each(function(){
          $(this).val("");
          $(this).prev().find(".error").remove();
          $(this).next(".mensaje").remove();
          $(this).removeClass("invalid");
          $(this).attr("validated", false);
          $(this).prop("readonly", false);
          $(this).prop("disabled", true);
        });   
        form.find("input[id$='responsable2_dni']").val(dni);   
        form.find("input[id$='responsable2_estadoCivil']").prop("readonly", false);  
        form.find("input[id$='responsable2_dni']").prop("disabled", false);
    }
  });
 
 $(document).on('blur',"#alumno_responsable2_dni",function() {
  //Se elimina la validación Empty, puesto que este campo puede estar vacío.
  validaciones= $(this).attr("validation");
  validaciones = validaciones.replace("Empty,", "");
  $(this).attr("validation", validaciones);

    if(($(this).val().trim().length == 0)){
      $("#tab2").find(":input[id^='alumno_responsable2_']").each(function(){
        $(this).prev().find(".error").remove();
        $(this).next(".mensaje").remove();
        $(this).removeClass("invalid");
        $(this).attr("validated", true);
      });
    }
  //Se evita repetirlo al hacer submit.
    if(!$('div #alumno_submit').attr('class') && $(this).attr("edit")=="true")
    {
      var dni = $("#alumno_responsable2_dni").val();
              //$(document.body).css({'cursor' : 'wait'});   //para cambiar el cursor mientras se carga
      $.ajax({
        type: 'POST',
        url: Routing.generate('comprobar_padre'),
        data: {dni:dni},
        dataType: 'json',
        success: function(response) {
                        //$(document.body).css({'cursor' : 'auto'});

         if((!($(this).find("#tab2").length) || ($("#tab2").attr("style")=="display: inline;"))&& response.success==true )
         {

          aviso.play();
          swal({
            title: "Responsable registrado en el sistema",
            html: "El DNI introducido pertenece al responsable: <br><br><h3>"+response.name+"</h3><br>¿Desea obtener sus datos?",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonColor: color,
            confirmButtonText: "¡Adelante!"
            }).then(function () {
              $.ajax({
                type: 'POST',
                url: Routing.generate('obtenerdatos_responsable'),
                data: {dni:dni},
                dataType: 'json',
                success: function(response) {    
                  $("#alumno_responsable2_nombre").focus().val(response.data['nombre']).prop('readonly', true);
                  $("#alumno_responsable2_fechaNacimiento").focus().val(response.data['fechaNacimiento']).prop('readonly', true);
                  $("#alumno_responsable2_profesion").focus().val(response.data['profesion']).prop('readonly', true);
                  $("#alumno_responsable2_estadoCivil").focus().val(response.data['estadoCivil']).prop('readonly', true);
                  $("#alumno_responsable2_movil").focus().val(response.data['movil']).prop('readonly', true);
                  $("#alumno_responsable2_email").focus().val(response.data['email']).prop('readonly', true); 
                }
              })
            }, function (dismiss) {
              $("#alumno_responsable2_dni").focus();
              $("#alumno_responsable2_dni").val('');
              $("#alumno_responsable2_dni").keyup();
            }
          );
         }   
        }
      })
      $(this).attr("edit",false);
    }
  });


 $(document).on("submit",".formulario_alumno",function(event){
    event.preventDefault();

    $('div #alumno_submit').addClass("no"); //Se utiliza en el evento blur de DNI para no duplicar la validación.
    form= $(this).closest("form");

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
    $(this).find("div[id^='tab']").each(function(){
      $(this).find(":input").each(function(){
        //No se valida el campos DNI del segundo responsable si está vacío y ninguno de sus campos deshabilitados.
        if((!$(this).attr("validated") || !$(this).attr("validated")==false) && !$(this).is(':disabled') && !($(this).attr("id")=="alumno_responsable2_dni" && $(this).val()=="")){
          if($(this).attr("validation")){
            validation($(this));
          }
        }
      });
    });

    //":input"añade a los input radio,select...
    $(this).find(":input").each(function(){
      if(($(this).attr("validated")=="false")) {
        //Se muestra el input inválido.
        tab= $(this).closest("div[id^='tab']").attr("id");
        mostrarTab($(this).closest("form"),tab);
        $(this).focus();
        val=1;
        return false;
      }       
    });
          
    var formdata=new FormData($(this)[0]);
    //Se obtiene el curso de ingreso para actualizar la pestaña de asignar grupo si está mostrado ese grupo.
    curso=$("#alumno_nuevo #alumno_cursoIngreso").val();

    if(val==0){
      $.ajax({
        type: 'POST',
        url: Routing.generate('alumno_create'),//url: $(this).attr('action')
        data:formdata, //$(this).serialize()
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        //async: false, 

        // Mostramos un mensaje con la respuesta de PHP
        success: function(response) {  
          limpiarForm(form);
          //Actualizamos la tabla de alumnos y padres. (Está en alumnos_antiguo de prueba)
          $("#alumnos_antiguo").update_tab();
          // Antiguo aviso de confirmación
             //form.find("div[id='result']").html("<div id='message'></div>");
             //form.find("div[id='message']").html("<h2> Usuario guardado</h2>").hide();
             //form.find("div[id='message']").fadeIn('slow').delay(5000).fadeOut('slow');
          alumno=response.alumno;
          $.ajax({
            type: 'POST',
            url: Routing.generate('matricular_alumno'),
            data: {alumno:alumno},
            dataType: 'json',
            success: function(response) {
              // Notificación de confirmación.
              exito.play();
              new PNotify({
                text:"Alumno matriculado",
                addclass: "custom",
                type: "success",
                shadow: true,
                hide: true,
                buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
                },
                stack: right_Stack,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              });
              //Actualización de pestañas.
              $("#anular_matricula").update_tab();
              //Se actualiza la pestaña de asignar grupos si está abierta y tiene seleccionado el curso actualizado.
              if($("#asignar_grupos #lista_cursos select option:selected").attr("value")==curso){
                $("#asignar_grupo").update_tab();   
              }
              $("#asignar_optativa").update_tab();
              $("#ficha_alumno").update_tab();
            }
          })
          event.stopPropagation();   
        },
        error: function (response, desc, err){
          if (response.responseJSON && response.responseJSON.message) {          
            if(response.responseJSON.result == 0) {
              //Se elimina las clases de error, para luego añadirlas a los campos que siguen inválidos.
              form.find(":input").each(function(i){  
                $(this).prev().find(".error").remove();
                $(this).next(".mensaje").remove();
                $(this).removeClass("invalid");
                $(this).attr("validated", true);
              });
              //Se muestra los campos inválidos.        
              for (var key in response.responseJSON.data) { 
                form.find(":input[id='"+key+"']").addClass("invalid");   
                form.find(":input[id='"+key+"']").attr("validated", false);
                form.find(":input[id='"+key+"']").after("<span class='mensaje'>"+response.responseJSON.data[key]+"</span>");
                form.find(":input[id='"+key+"']").prev().append("<span class='error'>Dato inválido</span>");
              }
              //Se muestra el primer campo inválido.
              for (var key in response.responseJSON.data) { 
                //form=$("form").find(":input[id='"+key+"']").closest("form");
                tab= form.find(":input[id='"+key+"']").closest("div[id^='tab']").attr("id");
                mostrarTab(form, tab);
                form.find(":input[id='"+key+"']").focus();
                return false;
              }
            } 
            //alert(response.responseJSON.message);
          } 
          else {
            error.play();
            swal({
              title: "Error en el sistema",
              html: "Se ha producido un error en el sistema, por favor cierra la pestaña <span>Nuevo Alumno</span> y vuelva a intentarlo de nuevo.",
              type: "error",
              showCancelButton: false,
              confirmButtonColor: color
            });
          }
        }
      })
    }
    $('div #alumno_submit').removeClass("no");
      return false;
  });


$(document).on("submit",".formulario_profesor",function(event){
    event.preventDefault();
    form= $(this).closest("form");

    //Se comrpueba si el dni existe en el sistema con la funcion comprobar dni al salir del input.
    $("#profesor_dni").blur();

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
      $(this).find(":input").each(function(){
        if((!$(this).attr("validated") || !$(this).attr("validated")==false)){
          if($(this).attr("validation")){
            validation($(this));
          }
        }
      });

    //":input"añade a los input radio,select...
    $(this).find(":input").each(function(){
      if(($(this).attr("validated")=="false")) {
        //Se muestra el input inválido.
        tab= $(this).closest("div[id^='tab']").attr("id");
        mostrarTab($(this).closest("form"),tab);
        $(this).focus();
        val=1;
        return false;
      }       
    });

    var perfilAcad='';
    var perfilProf='';
    // Se guarda el contenido del perfil Académico en su variable.   
    $(this).find("#tab2 :input").each(function(){
      if(($(this).val()=="")) {
        perfilAcad+="~";
      }
      else{
        perfilAcad+=$(this).val();
      }
      perfilAcad+="|";
    });

    // Se guarda el contenido del perfil Profesional en su variable.   
    $(this).find("#tab3 #perfil :input").each(function(){
      if(($(this).val()=="")) {
        perfilProf+="~";
      }
      else{
        perfilProf+=$(this).val();
      }
      perfilProf+="|";
    });

    $("#profesor_perfilAcademico").val(perfilAcad);     
    $("#profesor_perfilProfesional").val(perfilProf); 
          
    var formdata=new FormData($(this)[0])
    //Se le pasa el valor de las horas de jornada laboral manualmente por el problema de representar decimales en input number.
    formdata.append('horas',$("#profesor_horas").val());
    formdata.append('lectivas', $("#profesor_horasLectivas").val());
    if(val==0){
      $.ajax({
        type: 'POST',
        url: Routing.generate('profesor_create'),//url: $(this).attr('action')
        data:formdata, //$(this).serialize()
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        //async: false, 

        // Mostramos un mensaje con la respuesta de PHP
        success: function(response) {
        
          limpiarForm(form);
          //Hay que actualizar la pestaña que contiene la tabla de profesores.
          form.find("div[id='message']").remove();
          
          //form.find("div[id='result']").html("<div id='message'></div>");
          //form.find("div[id='message']").html("<h2> Profesor guardado</h2>").hide();
          //form.find("div[id='message']").fadeIn('slow').delay(5000).fadeOut('slow');

          // Notificación de confirmación.
          exito.play();
          new PNotify({
            text:"Profesor registrado",
            addclass: "custom",
            type: "success",
            shadow: true,
            hide: true,
            buttons: {
            sticker: false,
            labels:{close: "Cerrar"}
            },
            stack: right_Stack,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
          });
          
          //Actualización de pestañas.
          $("#tutor_grupo").update_tab();
          $("#clases_impartidas").update_tab();
          $("#ficha_profesor").update_tab();
          $("#profesor_antiguo").update_tab();
          $("#equipo_directivo").update_tab();

          event.stopPropagation();   
        },
        error: function (response, desc, err){
          if (response.responseJSON && response.responseJSON.message) {
            if(response.responseJSON.result == 0) {
              //Se elimina las clases de error, para luego añadirlas a los campos que siguen inválidos.
               form.find(":input").each(function(i){  
                $(this).prev().find(".error").remove();
                $(this).next(".mensaje").remove();
                $(this).removeClass("invalid");
                $(this).attr("validated", true);
              });
              //Se muestra los campos inválidos.        
              for (var key in response.responseJSON.data) { 
                form.find(":input[id='"+key+"']").addClass("invalid");   
                form.find(":input[id='"+key+"']").attr("validated", false);
                form.find(":input[id='"+key+"']").after("<span class='mensaje'>"+response.responseJSON.data[key]+"</span>");
                form.find(":input[id='"+key+"']").prev().append("<span class='error'>Dato inválido</span>");
              }
            } 
            alert(response.responseJSON.message);
          } 
          else {
            alert(desc);
          }
        }
      })
    }
      return false;
  });


  $(document).on("submit","#padres_nuevo",function(event){
    event.preventDefault();
    form= $(this).closest("form");

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
      $(this).find(":input").each(function(){
        if((!$(this).attr("validated") || !$(this).attr("validated")==false)){
          if($(this).attr("validation")){
            validation($(this));
          }
        }
      });

    //":input"añade a los input radio,select...
    $(this).find(":input").each(function(){
      if(($(this).attr("validated")=="false")) {
        //Se muestra el input inválido.
        $(this).focus();
        val=1;
        return false;
      }       
    });
     
    if(val==0){
      aviso.play();
      swal({
        title: "Nueva asignación de responsable",
        html: "<p class='justificado'>Se va a asignar un nuevo responsable al alumno y no se podrá recuperar el anterior.</p><br>¿Estas seguro de realizar la nueva asignación?",
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonColor: color,
        confirmButtonText: "¡Adelante!"
        }).then(function () {

          $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data:form.serialize(), 
            dataType: 'json',
  
            // Mostramos un mensaje con la respuesta de PHP
            success: function(response) {
              var id_alumno = $("#padres_nuevo").attr("alumno");
              var id_responsable = response.responsable;
              var responsable = $("#padres_nuevo").attr("responsable");
              $("#padres_dialog").dialog('close');
              limpiarForm(form);

              if(response.responsableDni!=$("#edit_alumno_responsable_dni_1").val() && response.responsableDni!=$("#edit_alumno_responsable_dni_2").val()){
                $.ajax({
                  type: 'POST',
                  url: Routing.generate('asignar_responsable'),
                  data: {id_alumno:id_alumno,id_responsable:id_responsable,responsable:responsable},
                  dataType: 'json',
                  success: function(response) {
                  //Se actualiza la información del responsable añadido en los formularios cuyo alumno sea el actual. 
                  $("form[action*='"+id_alumno+"']").each (function(){
                  form=$(this); 
                    if(responsable=="responsable1"){
                      form.find("#edit_alumno_responsable_dni_1").focus().val(response.data['dni']);
                      form.find("#edit_alumno_responsable1_nombre").focus().val(response.data['nombre']);
                      form.find("#edit_alumno_responsable1_fechaNacimiento").focus().val(response.data['fechaNacimiento']);
                      form.find("#edit_alumno_responsable1_profesion").focus().val(response.data['profesion']);
                      form.find("#edit_alumno_responsable1_estadoCivil").focus().val(response.data['estadoCivil']);
                      form.find("#edit_alumno_responsable1_movil").focus().val(response.data['movil']);
                      form.find("#edit_alumno_responsable1_email").focus().val(response.data['email']); 
                    }
                    else{
                      form.find("#edit_alumno_responsable_dni_2").focus().val(response.data['dni']);
                      form.find("#edit_alumno_responsable2_nombre").focus().val(response.data['nombre']);
                      form.find("#edit_alumno_responsable2_fechaNacimiento").focus().val(response.data['fechaNacimiento']);
                      form.find("#edit_alumno_responsable2_profesion").focus().val(response.data['profesion']);
                      form.find("#edit_alumno_responsable2_estadoCivil").focus().val(response.data['estadoCivil']);
                      form.find("#edit_alumno_responsable2_movil").focus().val(response.data['movil']);
                      form.find("#edit_alumno_responsable2_email").focus().val(response.data['email']); 
                    }
                    form.find("#noresponsable").addClass("oculto");
                    form.find("#responsable").removeClass("oculto");
                  });
                  }
                })  
              }
              else{
                error.play();
                swal({
                  title: "Responsable asignado al alumno",
                  text: "Este responsable ya se encuentra asignado a este alumno.",
                  type: "error",
                  showCancelButton: false,
                  confirmButtonColor: color
                });
              }
            },
            error: function (response, desc, err){
              if (response.responseJSON && response.responseJSON.message) {
                if(response.responseJSON.result == 0) {
                  //Se elimina las clases de error, para luego añadirlas a los campos que siguen inválidos.
                  form.find(":input").each(function(i){  
                    $(this).prev().find(".error").remove();
                    $(this).next(".mensaje").remove();
                    $(this).removeClass("invalid");
                    $(this).attr("validated", true);
                  });
                  //Se muestra los campos inválidos.        
                  for (var key in response.responseJSON.data) { 
                    form.find(":input[id='"+key+"']").addClass("invalid");   
                    form.find(":input[id='"+key+"']").attr("validated", false);
                    form.find(":input[id='"+key+"']").after("<span class='mensaje'>"+response.responseJSON.data[key]+"</span>");
                    form.find(":input[id='"+key+"']").prev().append("<span class='error'>Dato inválido</span>");
                  }
                } 
                alert(response.responseJSON.message);
              }    
              else {
                error.play();
                swal({
                  title: "Error en el sistema",
                  text: "Se ha producido un error en el sistema, por favor intentelo más tarde.",
                  type: "error",
                  showCancelButton: false,
                  confirmButtonColor: color
                });
              }
            }
          })
        return false;
        }
      );
    }
  });


  $(document).on('click',"#botones_form button[id$='_restablecer']",function(event){
    event.preventDefault();
    form= $(this).closest("form");
    //Se recupera el dir original antes de restablecer (se elimina _m del nombre)
    if(form.attr("id")=="noticias_edit"){
      titulo=$("#noticias_edit #noticias_galeria").val();
      $.ajax({
        type: 'POST',
        url: Routing.generate('recuperar_galeria'),
        data:{titulo:titulo}, 
        dataType: 'json',
        success: function(response){
        }
      });
    }

    var arr = form.attr('action').split('/');
    if($(this).closest(".ui-dialog").length){
      div=$(this).closest("div[id$='_dialog']");
      $(div).load(Routing.generate(arr[4]+"_edit", {id:arr[5]}));
    }
    else{
      div=$(this).closest("div[id^='tabs-']");
      $(div).load(Routing.generate(arr[4]+"_edit", {id:arr[5]})); 
    }

  });

  $(document).on('click',"#botones_form button[id$=nuevaFicha]", function(event){
    event.preventDefault();
    // Se cierra las notificaciones.
    PNotify.removeAll();
    form= $(this).closest("form");

    div=$(this).closest("div[id^='tabs-']");
    var arr = form.attr('id').split('_');
    $(div).load(Routing.generate(arr[0]+'_search'));
  });

    // Añadir foto
  $(document).on('click','#iconos_foto a', function(event){ 
    event.preventDefault();
    form= $(this).closest("form");

    if($(this).attr("id") == "icono_eliminar"){

      form.find("div[class='columna_foto'] div[id!='iconos_foto']").each(function(){
          $(this).addClass("oculto");
      });
      form.find("#por_defecto").removeClass("oculto");
      form.find("#icono_eliminar").addClass("disable");
      form.find("input[type='file']").attr("validated","true");
      form.find("input[type='file']").val("");
      form.find("#actualizada img").attr("src",form.find("#por_defecto img").attr("src"));

      if(form.find("#actual img").attr("src").indexOf("SinFoto") < 0){
          form.find("#icono_restablecer").removeClass("disable"); 
      }
    }
    else if($(this).attr("id") == "icono_restablecer"){

      form.find("div[class='columna_foto'] div[id!='iconos_foto']").each(function(){
          $(this).addClass("oculto");
      });
      form.find('#actual').removeClass("oculto");
      form.find("#actualizada img").attr("src",form.find("#actual img").attr("src"));
      form.find("#icono_restablecer").addClass("disable");
      if(form.find("#actual img").attr("src").indexOf("SinFoto") >= 0)
      {
        form.find("#icono_eliminar").addClass("disable");
      }
      else{
        form.find("#icono_eliminar").removeClass("disable");
      }
      
      form.find("#error_foto").empty();
      form.find("#por_defecto img").removeClass('invalid');

      form.find("input[type='file']").attr("validated","true");
      form.find("input[type='file']").val("");
    }
    else{
      form.find(".columna_foto #actualizada").addClass("modif");
      form.find(".columna_foto #actualizada").removeClass("oculto");
      form.find(".columna_foto #actual").addClass("oculto");
      form.find(".columna_foto #por_defecto").addClass("oculto");
      form.find("input[type='file']").trigger('click');
      //form.find('#actual').addClass("oculto");
    }

    comprobarEditForm(form);

    event.stopPropagation(); 
  });
  // Se deshabilita los botones del formulario al insertar una nueva foto.
  $(document).on('change','.columna_foto input[type="file"]',function() {
    form= $(this).closest("form");
    form.find("#botones_form button[id$='_submit']").prop("disabled",false); 
    form.find("#botones_form button[id$='_restablecer']").prop("disabled",false); 
    
  });


/////////////////////////////////
// Busqueda en los formularios //
/////////////////////////////////
  
  $(document).on("click","#contenedor_lista td", function(event){ 
    event.preventDefault();
    form= $(this).closest("form");
    
    var arr = form.attr('id').split('_');
    var div= $(this).closest("div[id^='tabs-']");

    $(div).empty();
    // Se añade un gif para la espera de la carga del contenido actualizado.
    $(div).html('<div class="ajaxload"><img src="/Symfony/web/bundles/backend/images/loading.gif"/></div>');

    $(div).load(Routing.generate(arr[1]+'_edit', {id:$(this).closest("tr").attr("id")}));
  });

  // Se muestra la lista de alumnos o profesores activos.
  $(document).on('click',"form[id^='busqueda_'] #btn_activos",function(event){
    event.preventDefault();
    form= $(this).closest("form");

    form.find(".activos").removeClass("oculto");
    form.find(".inactivos").addClass("oculto");

    //Se restablece el buscador y la lista según la opción marcada en el select(o mostrar todo si no hay opción).
    form.find("#buscador input").val("");
    form.find("#buscador input").keyup();
    
    form.find(".block_search input").val("");
    form.find(".block_search input").keyup();
  });

  // Se muestra la lista de alumnos o profesores inactivos.
  $(document).on('click',"form[id^='busqueda_'] #btn_inactivos",function(event){
    event.preventDefault();
    form= $(this).closest("form");

    form.find(".activos").addClass("oculto");
    form.find(".inactivos").removeClass("oculto");

    //Se restablece el buscador y la lista según la opción marcada en el select(o mostrar todo si no hay opción).
    form.find("#buscador input").val("");
    form.find("#buscador input").keyup();

    form.find(".block_search input").val("");
    form.find(".block_search input").keyup();
  });
  //Búsqueda por curso
  $(document).on('change','form[id^="busqueda_"] #lista_cursos select',function() {
    form= $(this).closest("form");
    select=$(this);

    form.find("#contenedor_lista span").remove();     
    curso=$(this).find("option:selected").text().replace("de", "");

    //Se elimina el contenido de la búsqueda si había.
    form.find(".block_search input").val("");
    form.find(".block_search input").keyup();
    
    //Se elimina el contenido del buscador cuando se selecciona un curso para la búsqueda.
    form.find("#buscador input").val(""); 
    form.find("#buscador input").keyup(); 
    
    //Se define el valor el select correspondiente al último curso .
    id=1;

    valor=form.find("#lista_cursos select option:selected").val();

    // Se modifica el valor en las dos listas.
    if(form.find(".activos select option:selected").val()!= form.find(".inactivos select option:selected").val()){
      form.find("#lista_cursos select").val(valor).change();
    }
  
    form.find(".contenedor_registro").each(function(){
      // Se selecciona el option del select oculto con z-index para filtrar el curso.
      if($(this).find("select[class='"+id+"'] option[value='"+curso+"']").length){   
        // Se selecciona y se muestra con change().
        $(this).find("select[class='"+id+"']").val(curso).change();
      }
      else if(select.find("option:selected").text()=="Todos los cursos" || select.find("option:selected").text()=="Mostrar todo"){
        $(this).find("select[class='"+id+"']").val("").change();
      }
      else{
        $(this).find("tbody").empty();
        $(this).find("tbody").append("<tr class='odd no_cursor'><td class='dataTables_empty'>Actualmente no se han asignado "+form.attr("tipo")+" en el curso seleccionado</td></tr>");
        $(this).find("thead tr th").removeClass("sorting_asc");
      }
    });

    //Se muestra el botón de limpiar búsqueda.
    form.find(".limpiar_busqueda").prop("disabled",false);
  });



  $(document).on("click",".limpiar_busqueda",function(event) {
    event.preventDefault();
    form=$(this).closest("form");

    //Se limpia el contenido de los input con la función limpiar definida anteriormente.

    //Se muestra toda la lista al limpiar el filtro en buscador que esá oculto.
    form.find("#lista_cursos select").val("0").change();// Se muestra la lista completa.
    form.find("#lista_cursos select").val("").change();// Se restablece el valor inicial del select. 
    form.find(".block_search input").val("");// Se elimina el valor del input.
    form.find(".block_search input").keyup(); // Se elimina la busqueda.
    $(this).prop("disabled",true);
  });

//////////////////////////////////
// Formularios de actualización //
//////////////////////////////////

  // Función para comprobar si se ha editado algo el formulario para mostrar los botones de guardar y restablecer.
  function comprobarEditForm(form) {
    var val=0;
    // Se comprueba si se ha modificado el valor inicial de algún input.
    form.find("input[type!='file']").each(function(){

      //Se omite que se compruebe los inputs de la hora en el formulario de editar eventos si la opción "Todo el día" está activa.
      if(form.attr("id")=="eventos_edit" && $(this).attr("class")=="timepicki-input" && form.find("#all_day input").is(':checked')){
      }
      else if(($(this).val()!=$(this).attr("value") && $(this).attr("value")!=undefined) || ($(this).val() && $(this).attr("value")==undefined) ){
        $(this).addClass("modified");
        form.find("button[id$='_submit']").prop("disabled",false);
        if(form.find("button[id$='_restablecer']").length){
          form.find("button[id$='_restablecer']").prop("disabled",false);
        }
        else{
          form.find("button[id$='_rest']").prop("disabled",false);
        }
        val=1;
      }
      else{
        $(this).removeClass("modified");
      }
    });

    //Se comprueba si se ha cambiado la opción inicial del radio.
      form.find("input[type='radio']").each(function(){
        if($(this).is(':checked') && $(this).attr("checked")!="checked"){
          $(this).addClass("modified");
          form.find("button[id$='_submit']").prop("disabled",false);
          if(form.find("button[id$='_restablecer']").length){
            form.find("button[id$='_restablecer']").prop("disabled",false);
          }
          else{
            form.find("button[id$='_rest']").prop("disabled",false);
          }          
          val=1;
        }
        else{
          $(this).removeClass("modified");
        }  
      });

      //Se comprueba si se ha cambiado la opción inicial del checkbox.
      form.find("input[type='checkbox']").each(function(){
        if($(this).is(':checked') && $(this).attr("checked")!="checked" || !($(this).is(':checked')) && $(this).attr("checked")=="checked"){
          $(this).addClass("modified");
          form.find("button[id$='_submit']").prop("disabled",false);
          if(form.find("button[id$='_restablecer']").length){
            form.find("button[id$='_restablecer']").prop("disabled",false);
          }
          else{
            form.find("button[id$='_rest']").prop("disabled",false);
          }          
          val=1;
        }
        else{
          $(this).removeClass("modified");
        }  
      });

      // Se comprueba si se ha modificado la foto inicial.
      // Se actualiza la foto inicial.
      if(form.find("#actualizada").attr('class')=="" || form.find("#actualizada").attr('class')=="modif" ){
          form.find(".columna_foto div[id!='iconos_foto'] img").addClass("modified");
          form.find("button[id$='_submit']").prop("disabled",false);
          if(form.find("button[id$='_restablecer']").length){
            form.find("button[id$='_restablecer']").prop("disabled",false);
          }
          else{
            form.find("button[id$='_rest']").prop("disabled",false);
          }
          val=1;
      }
      // Se elimina la foto inicial.
      else if(form.find("#por_defecto").attr('class')=="" && form.find("#actual img").attr("src")!=form.find("#por_defecto img").attr("src") ){
          form.find(".columna_foto div[id!='iconos_foto'] img").addClass("modified");
          form.find("button[id$='_submit']").prop("disabled",false);
          if(form.find("button[id$='_restablecer']").length){
            form.find("button[id$='_restablecer']").prop("disabled",false);
          }
          else{
            form.find("button[id$='_rest']").prop("disabled",false);
          }
          val=1;
      }
      else{
        form.find(".columna_foto div[id!='iconos_foto'] img").removeClass("modified");
      }

      // Se comprueba si se ha modificado el valor inicial de algún select.
      form.find("select option:selected").each(function(){
        //$(this).attr("selected")!=undefined
        if($(this).attr("value")!=$(this).closest("select").attr("seleccionado") && $(this).closest("select").attr("seleccionado")!=""){
          $(this).closest("select").addClass("modified");
          form.find("button[id$='_submit']").prop("disabled",false);
          if($(this).closest("form").find("button[id$='_restablecer']").length){
            form.find("button[id$='_restablecer']").prop("disabled",false);
          }
          else{
            form.find("button[id$='_rest']").prop("disabled",false);
          }
          val=1;
          if(form.attr("id")=="eventos_edit"){
            if($(this).parent().parent().attr("id")=="eventos_datetime_date" ){
              form.find(".ui-datepicker td a").removeClass("modified");
              form.find(".ui-datepicker .ui-datepicker-current-day a").addClass("modified");
            }
          }
        }
        else{
          $(this).closest("select").removeClass("modified");

        }
      });
    
    // Se comprueba si el formulario tiene textarea y si se ha modificado el valor inicial.
    if(form.find("textarea")){
      if(form.find("textarea").val()!=form.find("textarea").attr("value")){
        form.find("textarea").addClass("modified");
        form.find("button[id$='_submit']").prop("disabled",false);
        if(form.find("button[id$='_restablecer']").length){
          form.find("button[id$='_restablecer']").prop("disabled",false);
        }
        else{
          form.find("button[id$='_rest']").prop("disabled",false);
        }
        val=1;
      }
      else{
        form.find("textarea").removeClass("modified");
      }
    }

    //Se comprueba la opción "Todo el día" del formulario de editar eventos.
    if(form.attr("id")=="eventos_edit"){
      if((form.find("#all_day input").attr("value")=="checked" && !form.find("#all_day input").is(':checked')) || (form.find("#all_day input").attr("value")=="" && form.find("#all_day input").is(':checked'))  ){
          form.find("#all_day input").addClass("modified");
          form.find("button[id$='_submit']").prop("disabled",false);
          if(form.find("button[id$='_restablecer']").length){
            form.find("button[id$='_restablecer']").prop("disabled",false);
          }
          else{
            form.find("button[id$='_rest']").prop("disabled",false);
          }          
          val=1;
      }
      else{
        form.find("#all_day input").removeClass("modified");      
      }
      //Se elimina la clase modified a los input de la hora en timepicki.
      if(form.find("#all_day input").is(':checked')){
        form.find(".time input").removeClass("modified");
        form.find(".mins input").removeClass("modified");
      }
    }

    // Si no se ha modificado nada se deshabilita los botones de nuevo.
    if(val==0){
      form.find("button[id$='_submit']").prop("disabled",true);
      if(form.find("button[id$='_restablecer']").length){
        form.find("button[id$='_restablecer']").prop("disabled",true);
      }
      else{
        form.find("button[id$='_rest']").prop("disabled",true);
      }
    }

    //Se comprueba la lista de cursos si existe en el formulario de antiguos alumnos.
    if(form.attr("id")=="antiguo_alumno_edit"){
      if(form.find("#lista_cursos").length){
        comprobarSelect(form);
      }
      else{
      form.find("button[id$='_submit']").prop("disabled",false);
      }
    }

    if(form.find(":input[class*='invalid']").size()>0){
      form.find("button[id$='_submit']").prop("disabled",true);
    }

  }

  //Función para comprobar si se ha editado algo el formulario para mostrar los botones de guardar y restablecer.
  function comprobarSelect(form) {
    // Se comprueba si se ha modificado el valor inicial de la lista de cursos.
    lista=form.find("#lista_cursos select")
    if(lista.find("option:selected").attr("value")==""){
      form.find("button[id$='_submit']").prop("disabled",true);
      lista.removeClass("modified");
    }
    else{
      form.find("button[id$='_submit']").prop("disabled",false);
      form.find("button[id$='_rest']").prop("disabled",false);
      lista.addClass("modified");
    }
  }



  // Se llama a la función de comprobar formulario editado, en el caso que se modifique algún elemento del formulario.
  
  $(document).on("blur keyup paste cut","form[id$='_edit'] input",function() {
    //Se omite la validación en editar noticias.
    if($(this).closest("form").attr("id")=="noticias_edit"){
      return false;
    }
    comprobarEditForm($(this).closest("form"));
  });

  $(document).on("change","form[id$='_edit'] input[type='number']",function() {
    comprobarEditForm($(this).closest("form"));
  });

  $(document).on("change","form[id$='_edit'] input[type='radio']",function() {
    if($(this).closest("form").attr("id")=="noticias_edit"){
      return false;
    }
    comprobarEditForm($(this).closest("form"));
  });

  $(document).on("change","form[id$='_edit'] input[type='checkbox']",function() {
    comprobarEditForm($(this).closest("form"));
  });

  $(document).on("change","form[id$='_edit'] select",function() {
    if($(this).closest("form").attr("id")=="noticias_edit"){
      return false;
    }
    comprobarEditForm($(this).closest("form"));
  });

  $(document).on("change keyup paste click input blur cut","form[id$='_edit'] textarea",function() {
    if($(this).closest("form").attr("id")=="noticias_edit"){
      return false;
    }
    comprobarEditForm($(this).closest("form"));
  });



/*
  $(document).on("keyup","#profesor_edit input",function() {
    comprobarEditForm($("#profesor_edit"));
  });

  $(document).on("change","#profesor_edit input[type='radio']",function() {
    comprobarEditForm($("#profesor_edit"));
  });

  $(document).on("keyup","#alumno_edit input",function() {
    comprobarEditForm($("#alumno_edit"));
  });

  $(document).on("change","#alumno_edit input[type='radio']",function() {
    comprobarEditForm($("#alumno_edit"));
  });

  $(document).on("change","#alumno_edit select",function() {
    comprobarEditForm($("#alumno_edit"));
  });

  $(document).on("keyup","#alumno_edit textarea",function() {
    comprobarEditForm($("#alumno_edit"));
  });

  $(document).on("keyup","#centro_edit input",function() {
    comprobarEditForm($("#centro_edit"));
  });

  $(document).on("keyup","#antiguo_alumno_edit input",function() {
    comprobarEditForm($("#antiguo_alumno_edit"));
  });

  $(document).on("change","#antiguo_alumno_edit input[type='radio']",function() {
    comprobarEditForm($("#antiguo_alumno_edit"));
  });

  $(document).on("change","#antiguo_alumno_edit select",function() {
    comprobarEditForm($("#antiguo_alumno_edit"));

  });

  $(document).on("keyup","#antiguo_alumno_edit textarea",function() {
    comprobarEditForm($("#antiguo_alumno_edit"));
  });
    */
  //Se guarda el formulario alumno editado.
  $(document).on("submit","#alumno_edit",function(event) {
    event.preventDefault();
    form= $(this).closest("form");

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
    form.find(":input[type!='file']").each(function(){
      //Si no tiene segundo responsable se asigna true al valor de validated para que sean aceptados en la validación.
      if( $(this).closest("div[id='responsable']").hasClass("oculto")){
        $(this).attr("validated",true);
      }
      if(!$(this).attr("validated") || $(this).attr("validated")==false){
        if($(this).attr("validation")){
          validation($(this));
        }
      }
    });

    //":input"añade a los input radio,select...
    form.find(":input").each(function(){
      if(($(this).attr("validated")=="false")) {
        //Se muestra el input inválido.
        $(this).focus();
        val=1;
        return false;
      }       
    });

    var estado= "";

    if(!form.find("#actual").hasClass("oculto")){
      estado= "actual";
    }
    else if(!form.find("#actualizada").hasClass("oculto")){
      estado= "actualizado";
    }
    else{
      estado= "eliminado";
    }
          
    var formdata=new FormData($(this)[0]);
    formdata.append('estado', estado);

    if(val==0){
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formdata, 
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
      
          $("#icono_restablecer").addClass("disable");

          //Actualización de pestañas.
          $("#anular_matricula").update_tab();
          $("#alumnos_antiguo").update_tab();
          $("#asignar_optativa").update_tab();
          $("#ficha_alumno").update_tab();
          $("#asignar_grupo").update_tab();   

          var arr = form.attr('action').split('/');
          div=form.closest("div[id^='tabs-']");
          $(div).load(Routing.generate('alumno_edit', {id:arr[5]}), function(responseTxt, statusTxt, xhr){
            if(statusTxt == "success"){
              form= $("#alumno_edit");

              // Antiguo aviso de confirmación
              //form.find("div[id='message']").remove();
              //form.find("div[id='result']").html("<div id='message'></div>");
              //form.find("div[id='message']").html("<h2> Datos actualizados</h2>").hide();
              //form.find("div[id='message']").fadeIn('fast').delay(5000).fadeOut('slow');

              // Notificación de confirmación.
              exito.play();

              new PNotify({
                text:"Datos actualizados",
                addclass: "custom",
                type: "success",
                shadow: true,
                hide: true,
                buttons: {
                  sticker: false,
                  labels:{close: "Cerrar"}
                },
                stack: right_Stack,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              });
            }

            if(statusTxt == "error")
              alert("Error: " + xhr.status + ": " + xhr.statusText);
          });
        } 
      })
 
    }
  });

  $(document).on('click',"#tabs form[id$='alumno_edit'] button[id$='_modal']",function(event){
    event.preventDefault();
    form= $(this).closest("form");

    var resp= $(this).attr('id').split('_');
    var alum = $(this).closest(form).attr('action').split('/');

    $('#padres_dialog').load(Routing.generate("padres_new"), function(){
      $('#padres_dialog form').attr("responsable",resp[1]);
      $('#padres_dialog form').attr("alumno",alum[5]);
      $( '<div class="ui-widget-overlay ui-front" style="z-index: 99;"></div>' ).insertAfter($(".ui-dialog[aria-describedby='padres_dialog']"));
    }).dialog('open'); 
  });

  $(document).on("submit","#profesor_edit",function(event) {
    event.preventDefault();
    form= $(this).closest("form");

    var perfilAcad='';
    var perfilProf='';
    // Se guarda el contenido del perfil Académico en su variable.   
    form.find("div[id='perfil_acad'] :input").each(function(){
      if(($(this).val()=="")) {
        perfilAcad+="~";
      }
      else{
        perfilAcad+=$(this).val();
      }
      perfilAcad+="|";
    });

    // Se guarda el contenido del perfil Profesional en su variable.   
    form.find("div[id='perfil_prof'] :input").each(function(){
      if(($(this).val()=="")) {
        perfilProf+="~";
      }
      else{
        perfilProf+=$(this).val();
      }
      perfilProf+="|";
    });

    $("#edit_profesor_perfilAcademico").val(perfilAcad);     
    $("#edit_profesor_perfilProfesional").val(perfilProf); 

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
    form.find(":input[type!='file']").each(function(){

      if(($(this).attr("id")!="edit_profesor_perfilAcademico" && $(this).attr("id")!="edit_profesor_perfilProfesional")&&(!$(this).attr("validated") || $(this).attr("validated")==false)){
        if($(this).attr("validation")){
          validation($(this));
        }
      }
    });

    //":input"añade a los input radio,select...
    form.find(":input").each(function(){
      if(($(this).attr("validated")=="false")) {
        //Se muestra el input inválido.
        $(this).focus();
        val=1;
        return false;
      }       
    });

    var estado= "";

    if(!form.find("#actual").hasClass("oculto")){
      estado= "actual";
    }
    else if(!form.find("#actualizada").hasClass("oculto")){
      estado= "actualizado";
    }
    else{
      estado= "eliminado";
    }
          
    var formdata=new FormData($(this)[0]);
    formdata.append('estado', estado);
    //Se le pasa el valor de las horas de jornada laboral manualmente por el problema de representar decimales en input number.
    formdata.append('horas',$("#edit_profesor_horas").val());
    formdata.append('lectivas', $("#edit_profesor_horasLectivas").val());

    if(val==0){
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formdata, 
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
      
          $("#icono_restablecer").addClass("disable");

          //Actualización de pestañas.
          $("#consultar_instalaciones").update_tab();
          $("#profesor_antiguo").update_tab();
          $("#profesor_asignar_grupo").update_tab();
          $("#clases_impartidas").update_tab();
          $("#asignar_horario").update_tab();
          $("#listarlog").update_tab();
          $("#equipo_directivo").update_tab();
          

          var arr = form.attr('action').split('/');
          div=form.closest("div[id^='tabs-']");
          $(div).load(Routing.generate('profesor_edit', {id:arr[5]}), function(responseTxt, statusTxt, xhr){
            if(statusTxt == "success"){
              form= $("#profesor_edit");

              // Notificación de confirmación.
              $(".ui-pnotify").remove();
              exito.play();

              new PNotify({
                text:"Datos actualizados",
                addclass: "custom",
                type: "success",
                shadow: true,
                hide: true,
                buttons: {
                  sticker: false,
                  labels:{close: "Cerrar"}
                },
                stack: right_Stack,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              });
            }

            if(statusTxt == "error")
              alert("Error: " + xhr.status + ": " + xhr.statusText);
          });
        } 
      })
 
    }
  });
  //Se restablece la contraseña del responsable del alumno.
  $(document).on("click","#alumno_edit .restablecer_password",function(event) {
    event.preventDefault();
    id=$(this).attr("id");
    nombre=$(this).closest("div").find(".full_name_div input").attr("value");
    aviso.play();
    swal({
      title: "Restablecer contraseña del responsable",
      html: "<p class='justificado'>Se va a restablecer la contraseña inicial del responsable <strong class='negrita'>"+nombre+"</strong> y no se podrá recuperar la contraseña actual. ¿Estas seguro de continuar?</p>",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        $.ajax({
          type: 'POST',
          url: Routing.generate('restablecer_contraseña_responsable', {id:id}),
          data:{id:id},
          dataType: 'json',
  
          success: function(response) {
            // Notificación de confirmación.
            $(".ui-pnotify").remove();
            exito.play();

            new PNotify({
              text:"Contraseña restablecida.",
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
          }
        })
      }, function (dismiss) {

      }
    ); 
  });


  //Se restablece la contraseña del profesor.
  $(document).on("click","#profesor_edit .btn_restablecer",function(event) {
    event.preventDefault();
    id=$(this).attr("id");

    aviso.play();
    swal({
      title: "Restablecer contraseña del profesor",
      html: "<p class='justificado'>Se va a restablecer la contraseña del profesor a la inicial y no se podrá recuperar la actual. ¿Estas seguro de continuar?</p>",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        $.ajax({
          type: 'POST',
          url: Routing.generate('restablecer_contraseña_profesor', {id:id}),
          data:{id:id},
          dataType: 'json',
  
          success: function(response) {
            // Notificación de confirmación.
            $(".ui-pnotify").remove();
            exito.play();

            new PNotify({
              text:"Contraseña restablecida.",
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
          }
        })
      }, function (dismiss) {

      }
    ); 
  });

  //Se comprueba que el DNI no existe en el sistema si se modifica a un responsable.
  $(document).on("blur","input[id^='edit_alumno_responsable_dni_']",function() {
    form= $(this).closest("form");
    input=$(this);
    input_name=$(this).closest("div").next().find("input");

    var arr = form.attr('action').split('/');

    if($(this).val()!=''){
      var dni=$(this).val();
      var dni_anterior=$(this).attr("value");
      $.ajax({
        type: 'POST',
        url: Routing.generate('comprobar_dni_padre_editado'),
        data: {dni:dni, dni_anterior:dni_anterior},
        dataType: 'json',
        success: function(response) {   
          if(response.data!=null){
            input.addClass("invalid");   
            input.attr("validated", false);
            input.after("<span class='mensaje'>Este DNI ya existe en el sistema.</span>");
            //Se comprueba que no exista el aviso de error para no repetirlo.
              input.prev().find("span").remove();
              input.prev().append("<span class='error'>Dato inválido</span>");

            input_name.focus();
            if(input_name.val()==''){
              input_name.removeClass("invalid");
              input_name.prev().find(".error").remove();
              input_name.next(".mensaje").remove();         
            }
          }
        }  
      })
    }
  });

  $(document).on('click',"#eliminar_responsable",function(event){
    event.preventDefault();
    form= $(this).closest("form");

    var alum = $(this).closest(form).attr('action').split('/');
    var id_alumno =alum[5];

    aviso.play();
    swal({
      title: "Eliminación del responsable del alumno",
      text: "Se va a eliminar el responsable del alumno y no se podrá recuperar. ¿Estas seguro de continuar?",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {
        $.ajax({
          type: 'POST',
          url: Routing.generate('eliminar_responsable'),
          data:{id_alumno:id_alumno},
          dataType: 'json',
  
          success: function(response) {
          //Se actualiza la información del responsable añadido en los formularios cuyo alumno sea el actual. 
          $("form[action*='"+id_alumno+"']").each (function(){
          form=$(this); 
            // Se borran los valores del segundo responsable y se oculta el formulario.
            form.find("#responsable").addClass("oculto");
            form.find("#responsable input").val("");
            // Se muestra un aviso para indicar que solo hay un responsable.
            form.find("#noresponsable").removeClass("oculto");
          });
          }
        })
      }, function (dismiss) {

      }
    );  
    return false;
  });

$(document).on("blur","input[id='profesor_dni']",function(event) {
  form= $(this).closest("form");

  if($(this).val()!=''){
    var dni=$(this).val();
    $.ajax({
      type: 'POST',
      url: Routing.generate('comprobar_dni_profesor'),
      data: {dni:dni},
      dataType: 'json',
      success: function(response) {      
        if(response.data!=null){
          form.find("input[id$='profesor_dni']").addClass("invalid");   
          form.find("input[id$='profesor_dni']").attr("validated", false);
          form.find("input[id$='profesor_dni']").after("<span class='mensaje'>Este DNI ya existe en el sistema.</span>");
          //Se comprueba que no exista el aviso de error para no repetirlo.
          if(form.find("input[id$='profesor_dni']").prev().find('span[class="error"]').size()==0){
            form.find("input[id$='profesor_dni']").prev().append("<span class='error'>Dato inválido</span>");
          }
          form.find("input[id$='profesor_nombre']").focus();
          if(form.find("input[id$='profesor_nombre']").val()==''){
            form.find("input[id$='profesor_nombre']").removeClass("invalid");
            form.find("input[id$='profesor_nombre']").prev().find(".error").remove();
            form.find("input[id$='profesor_nombre']").next(".mensaje").remove();         
          }
        }
      } 
    })
  }
  event.stopPropagation();  
});
  

$(document).on("blur","input[id='edit_profesor_dni']",function(event) {
  form= $(this).closest("form");

  var arr = form.attr('action').split('/');

  if($(this).val()!=''){
    var dni=$(this).val();

    $.ajax({
      type: 'POST',
      url: Routing.generate('comprobar_dni_profesor_editado'),
      data: {dni:dni, id:arr[5]},
      dataType: 'json',
      success: function(response) {      
        if(response.data!=null){
          form.find("input[id$='profesor_dni']").addClass("invalid");   
          form.find("input[id$='profesor_dni']").attr("validated", false);
          form.find("input[id$='profesor_dni']").after("<span class='mensaje'>Este DNI ya existe en el sistema.</span>");
          //Se comprueba que no exista el aviso de error para no repetirlo.
          if(form.find("input[id$='profesor_dni']").prev().find('span[class="error"]').size()==0){
            form.find("input[id$='profesor_dni']").prev().append("<span class='error'>Dato inválido</span>");
          }
          form.find("input[id$='profesor_nombre']").focus();
          if(form.find("input[id$='profesor_nombre']").val()==''){
            form.find("input[id$='profesor_nombre']").removeClass("invalid");
            form.find("input[id$='profesor_nombre']").prev().find(".error").remove();
            form.find("input[id$='profesor_nombre']").next(".mensaje").remove();         
          }
        }
      } 
    })
  }
  event.stopPropagation();
});

  // Función para mostrar imagen previa de un input file
  function mostrarImagen(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#actualizada .img-small').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  //Se muestra la misma foto que tiene inicialmente cuando se quiere añadir otra.
  $(document).on("click","#icono_añadir",function() {
    form= $(this).closest("form");
    if(!form.find("#actual").hasClass('oculto')){
      form.find("#actualizada img").attr("src",form.find("#actual img").attr("src"));
    }
    else if(!form.find("#por_defecto").hasClass('oculto')){
      form.find("#actualizada img").attr("src",form.find("#por_defecto img").attr("src"));
    }
  });

  //Se comprueba que es válida la foto actualizada del usuario existente.
  $(document).on("change","form[id$='edit'] input[type='file']",function() {
    form= $(this).closest("form");
    mostrarImagen(this);

    if (($(this))[0].files.length > 0) {
      var sizeByte = ($(this))[0].files[0].size;
      var siezekiloByte = parseInt(sizeByte / 1024);
    }

    extensiones_permitidas = new Array(".png", ".jpg", ".jpeg"); 
    extension = (form.find("input[type='file']").val().substring(form.find("input[type='file']").val().lastIndexOf("."))).toLowerCase();
    // Se comprueba si la extensión está entre las permitidas.
    permitida = false;
    for (var i = 0; i < extensiones_permitidas.length; i++) {
      if (extensiones_permitidas[i] == extension) {
        permitida = true;
        break;
      }
    } 

    if(permitida && (siezekiloByte < $(this).attr('size')))
    {
      form.find("#error_foto").empty();

      form.find("div[class='columna_foto'] div[id!='iconos_foto']").each(function(){
        $(this).addClass("oculto");
      });
      form.find("#actualizada").removeClass("oculto");
      
      if(form.find("#actual img").attr("src").indexOf("SinFoto") >= 0)
      {
        form.find("#icono_eliminar").removeClass("disable");
      }
      else
      {
        form.find("#icono_restablecer").removeClass("disable");
        form.find("#icono_eliminar").removeClass("disable");
      }
    }
    else
    {
      form.find("#icono_eliminar").addClass("disable");
      form.find("#icono_restablecer").removeClass("disable");
      form.find("#actualizada img").attr("src","");
      form.find("div[class='columna_foto'] div[id!='iconos_foto']").each(function(){
        $(this).addClass("oculto");
      });
      form.find('#por_defecto').removeClass("oculto");
      form.find("#error_foto").empty();

      if(!permitida)
      {
        form.find("#por_defecto img").addClass('invalid');
        form.find("#error_foto").append("<p>- Archivo no válido.<br><br>Formatos válidos: .png / .jpg / .jpeg</p>");
      }
      else
      {
        form.find("#por_defecto img").addClass('invalid');
        form.find("#error_foto").append("<p>- El tamaño supera el limite permitido.<br><br>Tamaño máximo permitido: "+$(this).attr('size')+"KB</p>");
      }
    }
  });
  
  //Se comprueba que es válida la foto añadida al nuevo usuario.
  $(document).on("change","form[id$='nuevo'] input[type='file']",function() {
    form= $(this).closest("form");
    if(form.find("input[type='file']")!= null)
    {
      form.find("#leyenda p").remove();
      form.find("#leyenda").append("<p>"+form.find("input[type='file']").val()+"</p>");
    }
    if ($(this).attr('validation')) {    
      validation($(this));
    }
  });

  //////////////////////////////////
  //           Centro             //
  //////////////////////////////////

  //Se actualiza los datos del centro
  $(document).on("submit","#centro_edit",function(event) {
    event.preventDefault();
    form= $(this).closest("form");

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
    form.find(":input[type!='file']").each(function(){
      if(!$(this).attr("validated") || $(this).attr("validated")==false){
        if($(this).attr("validation")){
          validation($(this));
        }
      }
    });

    //":input"añade a los input radio,select...
    form.find(":input").each(function(){
      if(($(this).attr("validated")=="false")) {
        //Se muestra el input inválido.
        $(this).focus();
        val=1;
        return false;
      }       
    });

    if(val==0){

      $.ajax({
        type: 'PUT',
        url: $(this).attr('action'),
        data:$(this).serialize(), 
  
        success: function() {

        var arr = form.attr('action').split('/');
        tab=$(".contenido_main").find("div[aria-hidden='false']");
        $(tab).load(Routing.generate('centro_edit', {id:arr[5]}), function(responseTxt, statusTxt, xhr){
            if(statusTxt == "success"){
              form= $("#centro_edit");

              // Antiguo aviso de confirmación
              //form.find("div[id='message']").remove();
              //form.find("div[id='result']").html("<div id='message'></div>");
              //form.find("div[id='message']").html("<h2> Datos actualizados</h2>").hide();
              //form.find("div[id='message']").fadeIn('fast').delay(5000).fadeOut('slow');

              // Notificación de confirmación.
              $(".ui-pnotify").remove();
              exito.play();

              new PNotify({
                text:"Datos actualizados",
                addclass: "custom",
                type: "success",
                shadow: true,
                hide: true,
                buttons: {
                  sticker: false,
                  labels:{close: "Cerrar"}
                },
                stack: right_Stack,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              });
            }

            if(statusTxt == "error")
              alert("Error: " + xhr.status + ": " + xhr.statusText);
          });
        }
      })
      return false;
    }
  });

  //Se abre la ventana modal para asignar horarios de atención del centro.
  $(document).on('click',"#centro_edit #horarios_atencion",function(event){
    event.preventDefault();
   
    $('#horarios_atencion_dialog').load(Routing.generate("horarios_atencion"), function(){

    }).dialog('open'); 
  });

  //Se cierra la ventana modal de horarios de atención del centro.
  $(document).on('click',"#horarios_atencion_dialog #button_hora_centro",function(event){
    event.preventDefault();
   
    $('#horarios_atencion_dialog').dialog('close');
    $('#horarios_atencion_dialog').empty();
  });


  function actualizarContenido(horario,div) {
    array=horario.split("|");

    if(horario=="" || horario==null){
      $(div).find("#show div[id*='hora_']>div").append('<span class="no_horario">Actualmente no existe ningún horario de contacto establecido.</span');
    }
    else{      
      if(array[0].indexOf("-")>0){
        array[0]=reemplazarDatos(array[0]);
        $(div).find("#show div[id*='hora_']>div").append('<span class="dias">De '+array[0]+'</span');
      }
      else{
        array[0]=reemplazarDatos(array[0]);
        $(div).find("#show div[id*='hora_']>div").append('<span class="dias">'+array[0]+'</span');
      }

      if(array[1].indexOf("/")>0){
        array[1]=array[1].replace("/","  /  ");
        array[1]=array[1].replace(/-/g,"h. - ");
        $(div).find("#show div[id*='hora_']>div").append('<span class="horas">'+array[1]+'</span');
      }
      else{
        array[1]=array[1].replace("-"," - ");
        $(div).find("#show div[id*='hora_']>div").append('<span class="horas">'+array[1]+'</span');
      }
    }
  }
  //Se guarda el nuevo horario en la base de datos.
  $(document).on('click',"#horarios_atencion_dialog .guardar",function(event){
    id=$(this).closest("div").prev().prev();

    // Se obtiene el horario.
    contenido="";
    id.find("#list_added>span[class!='title']").each(function(index, el) {
      contenido+=$(this).find(".color_day").text();
      contenido+="|";
      contenido+=$(this).find(".color_hours").text();
      contenido+="*";
    });
    contenido=contenido.substring(0,contenido.length-1);
    contenido=contenido.replace(/ /g,"");
    
    // Se guarda el horario.
    if(contenido==""){
      id.find(".vacio").removeClass('oculto');
      id.next().next().find(".guardar").prop('disabled',true);
      errorPNotify.play();
      new PNotify({
        title: "Debe añadir un horario.",
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        width: "335px",
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        stack: left_Stack_dialog,
        animate_speed: "fast",
        animate: {
          animate: true,
          in_class: "fadeInLeft",
          out_class: "fadeOutLeft",
        }
      });
    }
    else{
      tipo=id.attr("id");
      $.ajax({
        type: 'POST',
        url: Routing.generate('registrar_horario_atencion'),
        data:{tipo:tipo, contenido:contenido}, 
        dataType: 'json',
        success: function(response){
          exito.play();
          new PNotify({
            text:"Nuevo horario registrado.",
            addclass: "custom",
            type: "success",
            shadow: true,
            hide: true,
            animation: "fade",
            animate_speed: 'fast',
            delay: 4000,
            buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
            },
            stack: right_Stack_dialog,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
          });
          //Se elimina el contenido que muestra el horario de atención.
          $(id).find("#show div[id*='hora_']>div>span").remove();
         
          //Se actualiza el nuevo horario de.
          arr=contenido.split("*");
          for (var i = 0; i<arr.length; i++) {
            actualizarContenido(arr[i],id);
          }
          id.next().next().find(".volver").click();
        }
      })
    }
  });
  // Se elimina el horario actual de la base de datos.
  $(document).on('click',"#horarios_atencion_dialog .eliminar",function(event){
    id=$(this).closest("div").prev();
    aviso.play();
    swal({
      title: "Eliminación del horario de atención.",
      text: "¿Estas seguro de continuar? No podrás deshacer este paso...",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        tipo=id.attr("id");
        contenido="";
        $.ajax({
          type: 'POST',
          url: Routing.generate('registrar_horario_atencion'),
          data:{tipo:tipo, contenido:contenido}, 
          dataType: 'json',
          success: function(response){
            exito.play();
            new PNotify({
              text:"Horario de atención eliminado.",
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              animation: "fade",
              animate_speed: 'fast',
              delay: 4000,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack_dialog,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });

            //Se elimina el contenido que muestra el horario de atención.
            $(id).find("#show div[id*='hora_']>div>span").remove();

            //Se actualiza el aviso de horario de atención.
            actualizarContenido(contenido,id);
          }
        })
      }, function (dismiss) {
        
      }
    );
    return false;
  });

  //Se cambia de opción en la ventana modal de horarios de atención.
  $(document).on("click","#horarios_atencion_dialog #arrow_next_1", function () {
  $("#horarios_atencion_dialog #formularios img").css( 'pointer-events', 'none' );
    div=$("#horarios_atencion_dialog #formularios>div>div:visible").attr("id");

   if(div==1){
      $("#horarios_atencion_dialog #1").hide("slide",{ direction: "left" }, 300, function() 
      { 
        $("#horarios_atencion_dialog #2").show("slide", { direction: "right" }, 300, function() 
        { 
          $("#horarios_atencion_dialog #formularios img").css( 'pointer-events', 'auto' );
        });
      });
    }
    else if(div==2){
      $("#horarios_atencion_dialog #2").hide("slide",{ direction: "left" }, 300, function() 
      { 
        $("#horarios_atencion_dialog #3").show("slide", { direction: "right" }, 300, function() 
        { 
          $("#horarios_atencion_dialog #formularios img").css( 'pointer-events', 'auto' );
        });
      });
    }
    else if(div==3){
      $("#horarios_atencion_dialog #3").hide("slide",{ direction: "left" }, 300, function() 
      { 
        $("#horarios_atencion_dialog #4").show("slide", { direction: "right" }, 300, function() 
        { 
          $("#horarios_atencion_dialog #formularios img").css( 'pointer-events', 'auto' );
        });
      });
    }
    else{
      $("#horarios_atencion_dialog #4").hide("slide",{ direction: "left" }, 300, function() 
      { 
        $("#horarios_atencion_dialog #1").show("slide", { direction: "right" }, 300, function() 
        { 
          $("#horarios_atencion_dialog #formularios img").css( 'pointer-events', 'auto' );
        });
      });
    }
  });

  $(document).on("click","#horarios_atencion_dialog #arrow_prev_1", function () {
    $("#horarios_atencion_dialog #formularios img").css( 'pointer-events', 'none' );
    div=$("#horarios_atencion_dialog #formularios>div>div:visible").attr("id");

   if(div==1){
      $("#horarios_atencion_dialog #1").hide("slide",{ direction: "right" }, 300, function() 
      { 
        $("#horarios_atencion_dialog #4").show("slide", { direction: "left" }, 300, function(){ 
          $("#horarios_atencion_dialog #formularios img").css( 'pointer-events', 'auto' );
        });
      });
    }
    else if(div==2){
      $("#horarios_atencion_dialog #2").hide("slide",{ direction: "right" }, 300, function() 
      { 
        $("#horarios_atencion_dialog #1").show("slide", { direction: "left" }, 300, function(){ 
          $("#horarios_atencion_dialog #formularios img").css( 'pointer-events', 'auto' );
        });
      });
    }
    else if(div==3){
      $("#horarios_atencion_dialog #3").hide("slide",{ direction: "right" }, 300, function() 
      { 
        $("#horarios_atencion_dialog #2").show("slide", { direction: "left" }, 300, function(){ 
          $("#horarios_atencion_dialog #formularios img").css( 'pointer-events', 'auto' );
        });
      });
    }
    else{
      $("#horarios_atencion_dialog #4").hide("slide",{ direction: "right" }, 300, function() 
      { 
        $("#horarios_atencion_dialog #3").show("slide", { direction: "left" }, 300, function() {
          $("#horarios_atencion_dialog #formularios img").css( 'pointer-events', 'auto' );
        });
      });
    }
  });


  function restablecerDatos(div) {
 
    $(div).find("input[type!='checkbox'][type!='radio']").val(""); 
    $(div).find("select").val("");
    $(div).find("#tipo input:checked").each(function() {
      $(this).prop('checked', false);         
    });
    $(div).find("#selec").click();
    $(div).find("#simple").click();
    $(div).next().next().find(".guardar").prop("disabled",true);

    $(div).find(".vacio").addClass('oculto');
  }

  //Se añade un nuevo horario al contenedor seleccionado.
  $(document).on('click',"#horarios_atencion_dialog #add span",function(event){
    cont=$(this).closest(".container_add");
    cont.find(".vacio").addClass("oculto");

    if($(this).parent().next().find("#list_added>span").length<4){
      horario=obtenerHorario(cont);
      array=horario.split("|");
      cont.find("#list_added").append('<span><p class="color_day">'+array[0]+'</p><p class="color_hours">'+array[1]+'</p><span></span></span>');
      //Se deshabilita la opción de "Añadir" si hay 4 horarios añadidos.
      if(cont.find("#list_added>span").length==4){
        cont.find("#add").addClass('disab');
      }
      restablecerDatos(cont);
      cont.next().next().find(".guardar").prop("disabled",false);
    }
  });


  //Opciones con los botones.
  $(document).on('click',"#horarios_atencion_dialog .nuevo",function(event){
    id=$(this).closest("div").prev();
    id.find("#edit").removeClass('oculto');
    id.find("#show").addClass('oculto');
    id.next().addClass('oculto');
    id.next().next().removeClass('oculto');
  });

  $(document).on('click',"#horarios_atencion_dialog .volver",function(event){
    id=$(this).closest("div").prev().prev();
    id.find("#edit").addClass('oculto');
    id.find("#show").removeClass('oculto');
    id.next().removeClass('oculto');
    id.next().next().addClass('oculto');
    id.find("#edit #add").addClass('disab');
    //Se restablece el contenedor.
    restablecerDatos(id);
    id.find("#list_added span[class!='title']").remove();
  });

  //////////////////////////////////
  //           Cursos             //
  //////////////////////////////////
  
  // Se abre la ventana modal de Añadir curso
  $(document).on('click',"#registro_cursos a[id$='_modal']",function(event){
    event.preventDefault();
    var nivel= $(this).attr('id').split('_');
    
    $('#cursos_dialog').load(Routing.generate("curso_new"), function(){
      $('#cursos_dialog form').attr("nivel",nivel[1].charAt(0).toUpperCase() + nivel[1].slice(1) );
      $("#curso_nivel").val($("#curso_nuevo").attr("nivel"));
    }).dialog('open'); 
  });

  // Se abre la ventana modal de Editar curso
  $(document).on('click',"#registro_cursos a[href$='edit']",function(event){
    event.preventDefault();
    var arr= $(this).attr('href').split('/');
    $('#cursos_dialog').load(Routing.generate(arr[4]+"_edit", {id:arr[5]}), function(){
    }).dialog('open'); 
  });

  // Se abre la ventana modal de Ordenar cursos
  $(document).on('click',"#registro_cursos #ordenar",function(event){
    event.preventDefault();

    $('#ordenar_cursos_dialog').load(Routing.generate("Listar_cursos"), function(){
      //Se centra la ventana modal en la pantalla.
      $('.ui-dialog').position({my: 'center', at: 'center', of: window, collision: 'fit'});
      //Se abre la ventana modal una vez cargado el contenido.
      $('#ordenar_cursos_dialog').dialog('open');
    }); 

  });

  //Función para centrar ventana modal al cambiar el tamaño de la pantalla.
  $(window).resize(function(event) {
    $('div[id*="_dialog"]').parent().position({
        my: 'center',
        at: 'center',
        of: window,
        collision: 'fit'
    });
  });
  $(document).on("click","#ordenar_cursos_dialog #guardar",function(event){
    event.preventDefault();
    // Se usa el método serialize de sortable para obtener un array con los id ordenados. 
    var cursos = $("#ordenar_cursos").sortable("serialize", {attribute: "id"});

    $.ajax({
      type: 'POST',
      url: Routing.generate('ordenar_cursos'),
      data: cursos,
      dataType: 'json',
      success: function(response) {      
        $("#ordenar_cursos_dialog").dialog('close');
        $("#grupos_curso").update_tab();
        $("#nuevo_curso").update_tab();
        $("#asignar_aula").update_tab();
        $("#lista_cursos select").load(Routing.generate('curso_list'));
        $("#alumno_cursoIngreso").load(Routing.generate('curso_list'));
      } 
    })
  });

  $(document).on("click","#ordenar_cursos_dialog #restablecer",function(event){
    event.preventDefault();
    $('#ordenar_cursos_dialog').load(Routing.generate("Listar_cursos"), function(){
      //Se centra la ventana modal en la pantalla.
      $('.ui-dialog').position({my: 'center', at: 'center', of: window, collision: 'fit'});
      //Se abre la ventana modal una vez cargado el contenido.
      $('#ordenar_cursos_dialog').dialog('open');
    });
  });



  $(document).on("submit","#curso_nuevo",function(event){
    event.preventDefault();
    form= $(this).closest("form");

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
      $(this).closest("form").find(":input").each(function(){
        if((!$(this).attr("validated") || !$(this).attr("validated")==false)){
          if($(this).attr("validation")){
            validation($(this));
          }
        }
      });

    //":input"añade a los input radio,select...
    $(this).closest("form").find(":input").each(function(){
      if(($(this).attr("validated")=="false")) {
        //Se muestra el primer input inválido.
        $(this).focus();
        val=1;
        return false;
      }       
    });

    if(val==0){
      $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data:$(this).serialize(), 
      dataType: 'json',
  
      // Mostramos un mensaje con la respuesta de PHP
      success: function(response) {

        if(response.error){
          error.play();
          swal({
            title: "Curso registrado en el sistema",
            text: 'El curso introducido ya está registrado en el sistema.',
            type: "error",
            confirmButtonColor: color
          });
          return false;
        }
        // Se crean los grupos correspondiente al curso.
        var curso=$("#curso_curso").val();
        var nivel=$("#curso_nivel").val();
        var num_grupos=1;
        $.ajax({
          type: 'POST',
          url: Routing.generate('Asignar_Ngrupos'),
          data: {curso:curso,nivel:nivel,num_grupos:num_grupos},
        })
        
        $("#cursos_dialog").dialog('close');
        limpiarForm(form);
        
        div=$("#registro_cursos").closest("div[id^='tabs-']");
        $(div).empty();
        $(div).load(Routing.generate('curso'));

        $("#tabs #lista_cursos").empty();
        $("#tabs #lista_cursos").load(Routing.generate('alumno_listaCursos'));
        
        $("#button_grupos_rest").trigger("click");

        //Actualización del contenido de pestañas.
        $("#asignar_aula").update_tab();
        $("#asignaturas_cursos").update_tab();
        $("#tutor_grupo").update_tab();
        $("#profesor_asignar_grupo").update_tab();
        $("#alumnos_multiple").update_tab();
        $("#anular_matricula").update_tab();
        $("#asignar_grupo").update_tab();
        $("#ficha_alumno").update_tab();
        $("#grupos_curso").update_tab();
        $("#asignaturas_cursos").update_tab();
        $("#ratio_curso").update_tab();

        },
      error: function (response, desc, err){
          if (response.responseJSON && response.responseJSON.message) {
            if(response.responseJSON.result == 0) {
              //Se elimina las clases de error, para luego añadirlas a los campos que siguen inválidos.
               form.find(":input").each(function(i){  
                $(this).prev().find(".error").remove();
                $(this).next(".mensaje").remove();
                $(this).removeClass("invalid");
                $(this).attr("validated", true);
              });
              //Se muestra los campos inválidos.        
              for (var key in response.responseJSON.data) { 
                form.find(":input[id='"+key+"']").addClass("invalid");   
                form.find(":input[id='"+key+"']").attr("validated", false);
                form.find(":input[id='"+key+"']").after("<span class='mensaje'>"+response.responseJSON.data[key]+"</span>");
                form.find(":input[id='"+key+"']").prev().append("<span class='error'>Dato inválido</span>");
              }
            } 
            alert(response.responseJSON.message);
          } 
          else {
            alert(desc);
          }
        }
      })
    }
  });

  $(document).on("submit","#curso_edit",function(event){
    event.preventDefault();
    form= $(this).closest("form");

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
      $(this).closest("form").find(":input").each(function(){
        if((!$(this).attr("validated") || !$(this).attr("validated")==false)){
          if($(this).attr("validation")){
            validation($(this));
          }
        }
      });

    //":input"añade a los input radio,select...
    $(this).closest("form").find(":input").each(function(){
      if(($(this).attr("validated")=="false")) {
        //Se muestra el primer input inválido.
        $(this).focus();
        val=1;
        return false;
      }       
    });

    if(val==0){
      $.ajax({
        type: 'PUT',
        url: $(this).attr('action'),
        data:$(this).serialize(), 
  
        success: function(response) {

        if(response.error){
          error.play();
          swal({
            title: "Curso registrado en el sistema",
            text: 'El curso introducido ya está registrado en el sistema.',
            type: "error",
            confirmButtonColor: color
          });
          return false;
        }

        $("#cursos_dialog").dialog('close');
        tab=$(".contenido_main").find("div[aria-hidden='false']");
        $(tab).load(Routing.generate('curso'));
        $("#tabs #lista_cursos").empty();
        $("#tabs #lista_cursos").load(Routing.generate('alumno_listaCursos'));

        //Actualización del contenido de pestañas.
        $("#asignar_aula").update_tab();
        $("#asignaturas_cursos").update_tab();
        $("#tutor_grupo").update_tab();
        $("#profesor_asignar_grupo").update_tab();
        $("#alumnos_multiple").update_tab();
        $("#anular_matricula").update_tab();
        $("#asignar_grupo").update_tab();
        $("#ficha_alumno").update_tab();
        $("#grupos_curso").update_tab();
        $("#asignar_horario").update_tab();
        $("#asignaturas_cursos").update_tab();
        $("#ratio_curso").update_tab();

        $("#button_grupos_rest").trigger("click");
        }
      })  
    }
  });

  $(document).on("click","#curso_delete button",function(event){
    event.preventDefault();
    form= $(this).closest("form");
    var arr= $('#curso_delete').attr('action').split('/');

    var curso=$('#curso_curso').val();
    var nivel=$('#curso_nivel').val();

    $.ajax({
      type: 'POST',
      url: Routing.generate('alumnos_asignados_curso'),
      data: {curso:curso,nivel:nivel},
      dataType: 'json',
  
      success: function(response) {
        aviso.play();
        swal({
          title: "Eliminación del curso del sistema",
          text: "¿Estas seguro de continuar? No podrás deshacer este paso...",
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          confirmButtonColor: color,
          confirmButtonText: "¡Adelante!"
          }).then(function () {

            // Si no hay alumnos asignados al curso se puede eliminar.
            if(response.data!=null){ 
              error.play();
              swal({
                title: "La eliminación no se ha efectuado",
                html: '<p class="justificado">El curso <span class="negrita">'+curso+" de "+nivel+'</span> no se puede eliminar porque existen alumnos matriculados en este curso. Debe cancelar las matrículas del curso para poder eliminarlo.</p>',
                type: "error",
                confirmButtonColor: color,
              });
              $("#cursos_dialog").dialog('close');
            }
            else{
              // Se elimina el curso y sus relaciones (imparte y grupo). 
              $.ajax({
                type: 'DELETE',
                url: Routing.generate(arr[4]+"_delete", {id:arr[5]}),
                data: $('#curso_delete').serialize(),
        
                success: function() {
                  $("#cursos_dialog").dialog('close');
                  tab=$(".contenido_main").find("div[aria-hidden='false']");
                  $(tab).load(Routing.generate('curso'));
        
                  $("#tabs #lista_cursos").empty();
                  $("#tabs #lista_cursos").load(Routing.generate('alumno_listaCursos'));
      
                  $("#button_grupos_rest").trigger("click");

                  //Actualización del contenido de pestañas.
                  $("#asignar_aula").update_tab();
                  $("#asignaturas_cursos").update_tab();
                  $("#tutor_grupo").update_tab();
                  $("#profesor_asignar_grupo").update_tab();
                  $("#alumnos_multiple").update_tab();
                  $("#anular_matricula").update_tab();
                  $("#asignar_grupo").update_tab();
                  $("#ficha_alumno").update_tab();
                  $("#grupos_curso").update_tab();
                  $("#asignar_horario").update_tab();
                  $("#asignaturas_cursos").update_tab();
                  $("#ratio_curso").update_tab();
                }
              })
            }
          }, function (dismiss) {

          }
        );     
      }
    })
    return false;
  });

  //////////////////////////////////
  //    Nº de grupos por curso    //
  //////////////////////////////////

  // Se indica el select modificado.
  $(document).on('change',"#Ngrupo",function(event){
    $(this).closest("tr").find("select").removeClass("error_guardar");
    $(this).closest("tr").find("span").addClass("oculto");
    if(!$("#registro_Ngrupos").find("select").hasClass('error_guardar')){
      $("#registro_Ngrupos #aviso_error").addClass("oculto");
    }

    if($(this).val()!=$(this).find("option[selected='selected']").val()){
      $(this).addClass("modified");
    }
    else{
      $(this).removeClass("modified");
    }
  });

  // Se quita la marca del select modificado y se crea o se elimina el Nº de grupos correspondiente.
  $(document).on('click',"#contenedor_registro_Ngrupos button",function(event){
    if (!$(this).closest("tr").find("select").hasClass('modified')){
      return false;
    }
    if (!$(this).closest("tbody").find("select").hasClass('error_guardar')){
      //$("#aviso_error").addClass("oculto");
      $(this).closest("tr").find("span").addClass("oculto");
    }
    //$(this).closest("tr").find("select").removeClass("error_guardar");

    num_grupos=$(this).closest("tr").find("select").val();
    num_grupos_ant=$(this).closest("tr").find("select option[selected='selected']").val();
    curso=$(this).closest("tr").children('td').slice(1, 2).html();
    nivel=$(this).closest("tr").children('td').slice(2, 3).html();

    tr=$(this).closest("tr");

    if(num_grupos<num_grupos_ant){
      /**/
      $.ajax({
        type: 'POST',
        url: Routing.generate('alumnos_asignados_grupo'),
        data: {curso:curso,nivel:nivel,num_grupos:num_grupos,num_grupos_ant:num_grupos_ant},
        dataType: 'json',
        async:false,
  
        success: function(response) {

          // Si no hay alumnos o profesores asignados al grupo se puede eliminar.
          if(response.data!=null){
            blocker.play();  
            tr.find("select").addClass("modified");
            tr.find("select").addClass("error_guardar");
            tr.find("select").removeClass("modified");
            $("#registro_Ngrupos #aviso_error").removeClass("oculto");
            tr.find("span").removeClass("oculto");
            //alert(response.data);
            //tr.find("select").val(num_grupos_ant);
          }
          else{
            // Se actualiza el atributo numGrupos de la entidad Curso y se crean los grupos correspondiente al curso.
            $.ajax({
              type: 'POST',
              url: Routing.generate('Asignar_Ngrupos'),
              data: {curso:curso,nivel:nivel,num_grupos:num_grupos},
              async:false,
            })

            tr.find("select").removeClass("modified");

            tr.find("select option:eq("+(num_grupos-1)+")").attr('selected',true);
            tr.find("select option:eq("+(num_grupos_ant-1)+")").attr('selected',false);

            // Se actualizan las pestañas que utilicen grupos.
            $("#asignar_aula").update_tab();
            $("#asignar_grupo").update_tab();
            $("#profesor_asignar_grupo").update_tab();
            $("#tutor_grupo").update_tab();
          }
        }
      })
    
      return false;

    }
    $(this).closest("tr").find("select").removeClass("error_guardar");
    $(this).closest("tr").find("select").removeClass("modified");

    $("#registro_Ngrupos #aviso_error").addClass("oculto");
    // Se actualiza el atributo numGrupos de la entidad Curso y se crean los grupos correspondiente al curso.
    $.ajax({
      type: 'POST',
      url: Routing.generate('Asignar_Ngrupos'),
      data: {curso:curso,nivel:nivel,num_grupos:num_grupos},
      async:false,

    })

    $(this).closest("tr").find("select option:eq("+(num_grupos-1)+")").attr('selected',true);
    $(this).closest("tr").find("select option:eq("+(num_grupos_ant-1)+")").attr('selected',false);

    // Se actualiza la pestaña de asignar aula.
    $("#asignar_aula").update_tab();
  });

  // Se modifica el nº de grupo en todos los cursos a la vez.
  $(document).on('change',"#select_grupos_all",function(event){
    value=$("#select_grupos_all").val();

    //Se desactiva la primera opción del select.
    $(this).find("option:eq('0')").prop("disabled", true);

    $("#contenedor_registro_Ngrupos select").each(function(){
      if($(this).val()!=value){
        $(this).closest("tr").find("span").addClass("oculto");
        $(this).removeClass("error_guardar");

        if(value!=$(this).find("option[selected='selected']").val()){
          $(this).addClass("modified");
        }
        else{
          $(this).removeClass("modified");
        }
        $(this).val(value);
      }  
    });

    if (!$("#contenedor_registro_Ngrupos").find("select").hasClass('error_guardar')){
      $("#registro_Ngrupos #aviso_error").addClass("oculto");
    }
  });

  // Se quita la marca de los select modificados y se crea o se elimina el Nº de grupos correspondiente.
  $(document).on('click',"#registro_Ngrupos #button_grupos_all",function(event){
    $("#registro_Ngrupos #button_grupos_all").addClass('oculto');
    $("#registro_Ngrupos #button_grupos_rest").addClass('oculto');
    $("#registro_Ngrupos #load").removeClass('oculto');

    $("#contenedor_registro_Ngrupos select[class='modified']").each(function(){  
      $(this).closest("tr").find("button").click();
    });

    $("#registro_Ngrupos #button_grupos_all").removeClass('oculto');
    $("#registro_Ngrupos #button_grupos_rest").removeClass('oculto');
    $("#registro_Ngrupos #load").addClass('oculto');
    // Se actualiza las pestañas una vez asignado todo.
    $("#select_grupos_all option:eq(0)").prop('selected', true);
      setTimeout(function(){ 
        $("#asignar_aula").update_tab();
        $("#tutor_grupo").update_tab();
        $("#profesor_asignar_grupo").update_tab();
      },3000);
  
  });

  // Se actualiza la pestaña de asignar grupos.
  $(document).on('click',"#registro_Ngrupos #button_grupos_rest",function(event){
    div=$(this).closest("div[id^='tabs-']");
    $(div).load(Routing.generate("curso_show"));
  });


  //////////////////////////////////
  //         Asignar Aula         //
  //////////////////////////////////

  // Se indica el select modificado.
  $(document).on('change',"#aula",function(event){
    if(($(this).val()=="" && $(this).attr("value")=="") || $(this).val()==$(this).attr("value")){
      $(this).removeClass("modified");
    }
    else{
      $(this).addClass("modified");
    }
  });
  // Se muestra la lista de aulas disponibles para asignar a un grupo.
  $(document).on('click',"#contenedor_asignar_aula input",function(event){
    event.preventDefault();
    tr=$(this).closest("tr");

    $('#aulas_dialog').load(Routing.generate("Listar_aulas"), function(){
      //Se centra la ventana modal en la pantalla.
      $('.ui-dialog').position({my: 'center', at: 'center', of: window, collision: 'fit'});
      //Se abre la ventana modal una vez cargado el contenido.
      $("#aulas_dialog #curso").append(tr.find("td:nth-child(1)").text()+" de "+tr.find("td:nth-child(2)").text()+" - Grupo "+tr.find("td:nth-child(3)").text());
      $("#aulas_dialog #content-form div").attr("grupo",tr.attr("id"));
      //Se elmina de la ventana modal todas las aulas ocupadas.
      $("#asignar_aula .contenedor_registro input[class='asig']").each(function(i){  
        id=$(this).attr("id");
        $("#aulas_dialog button[id='"+id+"']").remove();
      });

      if($("#aulas_dialog #content-form button").size()==0){
        $("#aulas_dialog #content-form p").remove();
        $("#aulas_dialog #content-form").append("<p class='vacio'>No quedan más aulas disponibles</p>");
      }
      $('#aulas_dialog').dialog('open');
    }); 
  });

  // Se asigna el aula al grupo seleccionado.
  $(document).on('click',"#aulas_dialog #content-form button",function(event){

      grupo=$(this).closest("div").attr("grupo");
      aula=$(this).attr("id");

      // Se actualiza el atributo aula de la entidad Grupo.
      $.ajax({
        type: 'POST',
        url: Routing.generate('asignar_aula'),
        data: {grupo:grupo,aula:aula},
        success: function(response) {
          $("#aulas_dialog").dialog('close');
          $("#asignar_aula").update_tab();
          $("#asignar_aula #vaciar").prop("disabled",false);
        }
      })
  });

  $(document).on('click',"#aulas_dialog #cancelar",function(event){
    $("#aulas_dialog").dialog('close');
  });

  $(document).on('click',"#asignar_aula #vaciar",function(event){
    aviso.play();
    swal({
      title: "Eliminación de asignaciones de aulas.",
      html: "<p>Se eliminarán todas las asignaciones de aulas de los grupos.</p></br>¿Estas seguro de continuar? No podrás deshacer este paso...",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {
        $.ajax({
          type: 'POST',
          url: Routing.generate('vaciar_aulas'),
          success: function(response) {
            $("#asignar_aula").update_tab();
          }
        })
      }, function (dismiss) {

      }
    );
  });

  // Se elimina el aula asignada al grupo seleccionado.
  $(document).on('click',"#contenedor_asignar_aula img",function(event){
      grupo=$(this).closest("tr").attr("id");
      aula=null;

      // Se actualiza el atributo aula de la entidad Grupo.
      $.ajax({
        type: 'POST',
        url: Routing.generate('asignar_aula'),
        data: {grupo:grupo,aula:aula},
        success: function(response) {
          $("#aulas_dialog").dialog('close');
          $("#asignar_aula").update_tab();

          //Se deshabilita el botón de vaciar aulas cuando no hay ninguna asignada.
          if($("#contenedor_asignar_aula input[value!='']").size()==0){
            $("#asignar_aula #vaciar").prop("disabled",true);
          }
        }
      })
  });


  // Se hace click en toda la fila de la tabla.
  $(document).on('click',".contenedor_registro .inner_table td",function(event){
    $(this).find("a").trigger("click");
  });


//////////////////////////////////
//         Asignaturas          //
//////////////////////////////////
  //Se muestra la edición de asignatura al darle click sobre el div del color.
  $(document).on('click',"#registro_asignaturas td span",function(event){
    $(this).closest("td").prev().find("a").click();
  });

  $(document).on('click',"#registro_asignaturas a[id$='_modal']",function(event){
    event.preventDefault();
    var tipo= $(this).attr('id').split('_');
    var num=$(this).closest("div").next().find(".underline").size();
    $('#asignaturas_dialog').load(Routing.generate("asignatura_new"), function(){
      $('#asignaturas_dialog form').attr("tipo",tipo[1].charAt(0).toUpperCase() + tipo[1].slice(1) );
      $("#asignatura_tipo").val($("#asignatura_nueva").attr("tipo"));
      //Se oculta el checkbox "opcional" al registrar asignaturas troncales.
      if(tipo[1]=="troncal"){
        $("#asignatura_opcional").closest('div').addClass('oculto');
      }
      //Se establece como máximo 3 asignaturas opcionales.
      if(num==3){
        $("#asignatura_opcional").prop("disabled",true);
        $("#asignatura_opcional").attr("title","Solo se permiten 3 asignaturas opcionales");
        $("#asignatura_opcional").css("cursor","default");
      }
    }).dialog('open'); 
  });

  $(document).on('click',"#registro_asignaturas a[href$='edit']",function(event){
    event.preventDefault();
    var arr= $(this).attr('href').split('/');
    var num=$(this).closest("table").find(".underline").size();
    $('#asignaturas_dialog').load(Routing.generate(arr[4]+"_edit", {id:arr[5]}), function(){
      //Se establece como máximo 3 asignaturas opcionales.
      if(num==3 && !$("#asignatura_opcional").is(':checked')){
        $("#asignatura_opcional").prop("disabled",true);
        $("#asignatura_opcional").attr("title","Solo se permiten 3 asignaturas opcionales");
        $("#asignatura_opcional").css("cursor","default");
      }
    }).dialog('open'); 
  });

  $(document).on("submit","#asignatura_nueva",function(event){
    event.preventDefault();
    form= $(this).closest("form");

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
      $(this).closest("form").find(":input").each(function(){
        if((!$(this).attr("validated") || !$(this).attr("validated")==false)){
          if($(this).attr("validation")){
            validation($(this));
          }
        }
      });

    //":input"añade a los input radio,select...
    $(this).closest("form").find(":input").each(function(){
      if(($(this).attr("validated")=="false")) {
        //Se muestra el primer input inválido.
        $(this).focus();
        val=1;
        return false;
      }       
    });

    //Se muestra el aviso de error si no tiene color seleccionado.
    if($("#asignaturas_dialog #asignatura_color").hasClass('invalid')){
      $("#asignaturas_dialog #color").addClass('invalid');
      $("#asignaturas_dialog #div_error").removeClass('oculto');
    }

  if(val==0){
    $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data:$(this).serialize(), 
      dataType: 'json',
  
      // Mostramos un mensaje con la respuesta de PHP
      success: function(response) {
        if(response.error){
          error.play();
          swal({
            title: "Asignatura registrada en el sistema",
            text: 'La asignatura introducida ya está registrada en el sistema.',
            type: "error",
            confirmButtonColor: color,
          });
          return false;
        }
        if(response.opcional){
          error.play();
          swal({
            title: "Asignatura Específica No Opcional",
            text: 'Existen 3 asignaturas opcionales registradas en el sistema, por lo que la asignatura actual se ha registrado como no opcional.',
            type: "warning",
            confirmButtonColor: color,
          });
        }
        
        //Actualización de pestañas.
        $("#asignaturas_cursos").update_tab();
        $("#profesor_asignar_grupo").update_tab();
        $("#asignar_horario").update_tab();
        $("#asignar_optativa").update_tab();

        $("#asignaturas_dialog").dialog('close');
        limpiarForm(form);
        
        div=$("#registro_asignaturas").closest("div[id^='tabs-']");
        $(div).empty();
        $(div).load(Routing.generate('asignatura'));
        //Actualizamos las listas de asignaturas de las demás pestañas.
        //$("#tabs #lista_asignaturas").empty();
        //$("#tabs #lista_asignaturas").load(Routing.generate('alumno_listaAsignaturas'));
        },
        error: function (response, desc, err){
          if (response.responseJSON && response.responseJSON.message) {
            if(response.responseJSON.result == 0) {
              //Se elimina las clases de error, para luego añadirlas a los campos que siguen inválidos.
               form.find(":input").each(function(i){  
                $(this).prev().find(".error").remove();
                $(this).next(".mensaje").remove();
                $(this).removeClass("invalid");
                $(this).attr("validated", true);
              });
              //Se muestra los campos inválidos.        
              for (var key in response.responseJSON.data) { 
                form.find(":input[id='"+key+"']").addClass("invalid");   
                form.find(":input[id='"+key+"']").attr("validated", false);
                form.find(":input[id='"+key+"']").after("<span class='mensaje'>"+response.responseJSON.data[key]+"</span>");
                form.find(":input[id='"+key+"']").prev().append("<span class='error'>Dato inválido</span>");
              }
            } 
            alert(response.responseJSON.message);
          } 
          else {
            alert(desc);
          }
        }
      })
    }
  });

  $(document).on("submit","#asignatura_edit",function(event){
    event.preventDefault();
    form= $(this).closest("form");

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
      $(this).closest("form").find(":input").each(function(){
        if((!$(this).attr("validated") || !$(this).attr("validated")==false)){
          if($(this).attr("validation")){
            validation($(this));
          }
        }
      });

    //":input"añade a los input radio,select...
    $(this).closest("form").find(":input").each(function(){
      if(($(this).attr("validated")=="false")) {
        //Se muestra el primer input inválido.
        $(this).focus();
        val=1;
        return false;
      }       
    });

    //Se muestra el aviso de error si no tiene color seleccionado.
    if($("#asignaturas_dialog #asignatura_color").hasClass('invalid')){
      $("#asignaturas_dialog #color").addClass('invalid');
      $("#asignaturas_dialog #div_error").removeClass('oculto');
    }

    if(val==0){

      elemento=$(this);
      //Se comprueba si se modifica una asignatura añadiendo/eliminando la opción "opcional" para eliminar todas las asignaciones de todas las asignaturas opcionales.
      if($("#asignatura_edit #asignatura_opcional").hasClass('modified')){
        aviso.play();
        swal({
          title: "Actualización de Asignatura Opcional",
          html: "<p class='justificado'>Se va a realizar cambios en una asignatura específica opcional, por lo que se eliminará todas las asignaciones de esta asignatura registrada en el sistema.</p><br>¿Estas seguro de continuar? No podrás deshacer este paso...",
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          confirmButtonColor: color,
          confirmButtonText: "¡Adelante!"
          }).then(function () {
            opcional=1;//Variable para indicar si se ha modificado una asignatura opcional.
            $.ajax({
              type: 'PUT',
              url: elemento.attr('action'),
              data:elemento.serialize()+"&opcional="+opcional, 
  
              success: function(response) {

                if(response.error){
                  error.play();
                  swal({
                    title: "Asignatura registrada en el sistema",
                    text: 'La asignatura introducida ya está registrada en el sistema.',
                    type: "error",
                    confirmButtonColor: color,
                  });
                  return false;
                }
                if(response.opcional){
                  error.play();
                  swal({
                    title: "Asignatura Específica No Opcional",
                    text: 'Existen 3 asignaturas opcionales registradas en el sistema, por lo que la asignatura actual se ha guardado como no opcional.',
                    type: "warning",
                    confirmButtonColor: color,
                  });
                }

                //Actualización de pestañas.
                $("#asignaturas_cursos").update_tab();
                $("#profesor_asignar_grupo").update_tab();
                $("#asignar_horario").update_tab();
                $("#asignar_optativa").update_tab();

                $("#asignaturas_dialog").dialog('close');
                tab=$(".contenido_main").find("div[aria-hidden='false']");
                $(tab).load(Routing.generate('asignatura'));
                //$("#tabs #lista_asignaturas").empty();
                //$("#tabs #lista_asignaturas").load(Routing.generate('alumno_listaAsignatura'));

              }
            })
          }, function (dismiss) {

          }
        );
      }
      else{
        opcional=0;//Variable para indicar si se ha modificado una asignatura opcional.
        $.ajax({
          type: 'PUT',
          url: $(this).attr('action'),
          data:$(this).serialize()+"&opcional="+opcional, 
  
          success: function(response) {

            if(response.error){
              error.play();
              swal({
                title: "Asignatura registrada en el sistema",
                text: 'La asignatura introducida ya está registrada en el sistema.',
                type: "error",
                confirmButtonColor: color,
              });
              return false;
            }
            if(response.opcional){
              error.play();
              swal({
                title: "Asignatura Específica No Opcional",
                text: 'Existen 3 asignaturas opcionales registradas en el sistema, por lo que la asignatura actual se ha guardado como no opcional.',
                type: "warning",
                confirmButtonColor: color,
              });
            }

            //Actualización de pestañas.
            $("#asignaturas_cursos").update_tab();
            $("#profesor_asignar_grupo").update_tab();
            $("#asignar_horario").update_tab();
            $("#asignar_optativa").update_tab();

            $("#asignaturas_dialog").dialog('close');
            tab=$(".contenido_main").find("div[aria-hidden='false']");
            $(tab).load(Routing.generate('asignatura'));
            //$("#tabs #lista_asignaturas").empty();
            //$("#tabs #lista_asignaturas").load(Routing.generate('alumno_listaAsignatura'));
          }
        })
      }
    }
  });


  $(document).on("click","#asignatura_delete button",function(event){
    event.preventDefault();
    form= $(this).closest("form");
    var arr= $('#asignatura_delete').attr('action').split('/');
    aviso.play();
    swal({
      title: "Eliminación de la asignatura.",
      html: "<p class='justificado'>Se va a eliminar la asignatura del sistema y todas las asignaciones de esta asignatura registradas en el sistema.</p></br>¿Estas seguro de continuar? No podrás deshacer este paso...",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        $.ajax({
          type: 'DELETE',
          url: Routing.generate(arr[4]+"_delete", {id:arr[5]}),
          data: $('#asignatura_delete').serialize(),
        
          success: function() {
            $("#asignaturas_dialog").dialog('close');
            tab=$(".contenido_main").find("div[aria-hidden='false']");
            $(tab).load(Routing.generate('asignatura'));
            //$("#tabs #lista_asignaturas").empty();
            //$("#tabs #lista_asignaturas").load(Routing.generate('alumno_listaAsignatura'));
            
            //Actualización de pestañas.
            $("#asignaturas_cursos").update_tab();
            $("#profesor_asignar_grupo").update_tab();
            $("#asignar_horario").update_tab();
            $("#asignar_optativa").update_tab();
          }
        })
      }, function (dismiss) {

      }
    );
      return false;
  });


//////////////////////////////////
//     Asignaturas por curso    //
//////////////////////////////////

  $(document).on('click',"#asignaturas_cursos a[id$='_modal']",function(event){
    event.preventDefault();
    var curso= $(this).closest("h2").text();
    var id=$(this).closest("div").attr("idcurso");

    $.ajax({
      type: 'POST',
      url: Routing.generate('comprobar_asignaturas'),
      success: function(response) {
        // Si no hay asignaturas registradas muestra un aviso.
        if(response.data==null){
          error.play(); 
          swal({
            title:"No se pueden asignar asignaturas",
            text: 'Actualmente no hay asignaturas registrada en el sistema',
            type: "error",
            showCancelButton: false,
            confirmButtonColor: color
          });
        }
        else{
          $('#asignatura_curso_dialog').load(Routing.generate("asignaturas_curso_new", {id:id}), function(){
          }).dialog('open'); 
        }
      }
    })
  });

  //Se asigna todas las asignaturas troncales al curso.
  $(document).on('click',"#asignatura_curso_dialog #all_troncales",function(event){
    event.preventDefault();
    $("#asignatura_curso_dialog #lista_troncal button").click();
    $(this).addClass('disabled');
  });

  //Se asigna todas las asignaturas específicas al curso.
  $(document).on('click',"#asignatura_curso_dialog #all_especificas",function(event){
    event.preventDefault();
    $("#asignatura_curso_dialog #lista_especifica button").click();
    $(this).addClass('disabled');
  });

  $(document).on('click',"#asignatura_curso_dialog #asignaturas_curso_restablecer",function(event){
    event.preventDefault();
    $("#asignaturas_cursos #contenedor_registro:not(.oculto) img").click();
  });

  $(document).on('click',"#asignatura_curso_dialog #asignaturas_curso_submit",function(event){
    event.preventDefault();

    var nuevas = new Object();
    var asignadas = new Object();
    var eliminadas = Array();

    var valor=0;
    //Se asigna un valor para el error de distinto Nº de Módulos para asignaturas opcionales  y se eliminan los avisos previamente.
    var opcional=0;
    $("#asignatura_curso_dialog .aviso_opcional").remove();
    $("#asignatura_curso_dialog .aviso_opcional2").remove();

    // Se valida que ningún input esté vacío. Si hay algún input vacío no se realiza nada.
    $("#asignatura_curso_dialog #contenedor_asignaturas li input[type='number']").each(function(){ 
      if($(this).val()==""){
        $(this).addClass("invalid");
        valor=1;
      }
    });

    $("#asignatura_curso_dialog #contenedor_asignaturas li[opcional='1'] input[type='number']").each(function(){
      num=$(this).val();
      $("#asignatura_curso_dialog #contenedor_asignaturas li[opcional='1'] input[type='number']").each(function(){
        if($(this).val()!=num){
          $("#asignatura_curso_dialog #contenedor_asignaturas li[opcional='1'] input[type='number']").addClass("invalid");
          opcional=1;
        }
      });
    });

    if(opcional==1){
      $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",true);
      $("#asignatura_curso_dialog #aviso_error").addClass('oculto');
      if(valor==0){
        $( '<div id="aviso_error" class=" aviso_opcional oculto"><span></span><span>El Nº de módulos en las asignaturas específicas opcionales debe ser el mismo.</span></div>' ).insertAfter( $("#asignatura_curso_dialog #aviso_error"));
        $("#asignatura_curso_dialog .aviso_opcional").removeClass('oculto');
      }
      else{
        $( '<div id="aviso_error" class=" aviso_opcional2 oculto"><span></span><span>El Nº de módulos no puede estar vacío y deben ser iguales en asignaturas opcionales.</span></div>').insertAfter( $("#asignatura_curso_dialog #aviso_error"));
        $("#asignatura_curso_dialog .aviso_opcional2").removeClass('oculto');
      }
      return false;
    }

    if(valor==1){
      $("#asignatura_curso_dialog #aviso_error").removeClass('oculto');
      $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",true);
      return false;
    }
    var index = 1;
    // Se obtienen las asignaturas nuevas.(Insertar)
    $("#asignatura_curso_dialog #contenedor_asignaturas li[estado='nueva']").each(function(){

      id=$(this).attr("id");
      valor=$(this).find("input[type='number']").val();
      libro=$(this).find("input[type='text']").val();
      nuevas[index++] = [id, valor, libro];  
    });

    var cambioModulos = Array();
    var index = 1;
    // Se obtienen las asignaturas que ya estaban asignadas pero el número de módulos ha sido modificado.(Actualizar)
    $("#asignatura_curso_dialog #contenedor_asignaturas li[estado='asignada']").each(function(){

      id=$(this).attr("id");
      valor=$(this).find("input[type='number']").val();
      libro=$(this).find("input[type='text']").val();
      //Se comprueba si se ha modificado el número de módulos, porque si se ha modificado sólo el libro no se eliminará las asignaciones de esa asignatura.
      cambioModulo=0;
      if(valor!=$(this).find("input[type='number']").attr("valor")){
        cambioModulo=1;
      }

      if(valor!=$(this).find("input[type='number']").attr("valor") || libro!=$(this).find("input[type='text']").attr("valor")){
        asignadas[index++] = [id, valor, libro, cambioModulo];  
      }
      //Se obtiene las asignaturas a las que se le ha modificado el número de módulos, para mostrar aviso de eliminación de registro de profesores.
      if(valor!=$(this).find("input[type='number']").attr("valor")){
        asigcurso=$(this).attr("asigcurso");
        cambioModulos.push(asigcurso);
      }

    });

    // Se obtienen las asignaciones de asignaturas que han sido eliminadas.(Eliminar)
    $("#asignatura_curso_dialog #lista_asignaturas button[class*='asignada']").each(function(){
      id=$(this).attr("id");
      asigcurso=$(this).attr("asigcurso");

      if(id && $("#asignatura_curso_dialog #contenedor_asignaturas li[id='"+id+"']").size()==0){
        eliminadas.push(asigcurso);
      }
    });

    //Si no existe cambios se avisa.
    if($.isEmptyObject(nuevas) && $.isEmptyObject(asignadas) && $.isEmptyObject(eliminadas)){
      $('#asignatura_curso_dialog').dialog('close');
      errorPNotify.play();

      new PNotify({
        text:'No hay modificaciones de asignaturas para este curso.',
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        stack: right_Stack,
        animate: {
          animate: true,
          in_class: "fadeInRight",
          out_class: "fadeOutRight",
        }
      });
      return false;
    }

    //Nº de módulos asignados para comprobar con el nº de módulos permitidos en el horario.
    var no_opcionales=0;
    var opcionales=0;
    var num_opcionales=0;
    $("#asignatura_curso_dialog #contenedor_asignaturas li[opcional!='1'] input[type='number']").each(function(){
        no_opcionales+=parseInt($(this).val());
    });

    $("#asignatura_curso_dialog #contenedor_asignaturas li[opcional='1'] input[type='number']").each(function(){
        opcionales+=parseInt($(this).val());
    });

    $("#asignatura_curso_dialog #contenedor_asignaturas li[opcional='1']").each(function(){
        num_opcionales++;
    });

    //Las asignaciones optativas se dividen entre el número de optativas, para obtener el número de módulos asignados.
    modulos=no_opcionales+(opcionales/num_opcionales);

    curso=$("#asignatura_curso_dialog fieldset").attr("id");
    name_curso=$("#asignatura_curso_dialog fieldset").attr("name");

    if(!$.isEmptyObject(cambioModulos)){

      $.ajax({
        type: 'POST',
        url: Routing.generate('comprobar_asignaciones_profesores'),
        data: {eliminadas:cambioModulos},
        dataType: 'json',
        success: function(response) {
  
          texto="";
          if(response.error!=""){

            texto+="<p class='justificado'>Se ha actualizado el número de módulos en las siguientes asignaturas de <br>"+name_curso+" que tienen asignados profesores en alguno de sus grupos:</p><br>";
            for (var key in response.error) { 
              if (texto.indexOf(response.error[key][0][1]) < 0){
                texto+="<p class='justificado negrita'>- "+response.error[key][0][1]+"</p>"; 
              }
            }
            texto+="<br><table><p class='justificado'>Si deseas continuar se borrarán las siguientes asignaciones de profesores:<br></p><thead><tr><th>Profesor</th><th>Asignatura</th><th>Grupo</th></tr></thead><tbody>";
            for (var key in response.error) { 
              texto+="<tr><td>"+response.error[key][0][0]+"</td><td>"+response.error[key][0][1]+"</td><td>"+response.error[key][0][2]+"</td></tr>";
            }
            texto+="</tbody><br></p></table>";
            texto+="<br><br>¿Estas seguro de continuar? No podrás deshacer este paso...";
            error.play();
            swal({
              title: "Asignaturas del Curso Actualizadas",
              html: texto,
              type: "warning",
              showCancelButton: true,
              cancelButtonText: "Cancelar",
              confirmButtonColor: color,
              confirmButtonText: "¡Adelante!",
              width: "600px"
              }).then(function () {

               if(!$.isEmptyObject(eliminadas)){
                // Se muestra un aviso en caso de que se vaya a eliminar asignaturas con asignaciones de profesores registradas en el sistema.
                $.ajax({
                  type: 'POST',
                  url: Routing.generate('comprobar_asignaciones_profesores'),
                  data: {eliminadas:eliminadas},
                  dataType: 'json',
                  success: function(response) {
                    texto="";
                    if(response.error!=""){
                      texto+="<p class='justificado'>Las siguientes asignaturas que se quieren eliminar tienen asignados profesores en alguno de los grupos de "+name_curso+":</p><br>";
                      for (var key in response.error) { 
                        if (texto.indexOf(response.error[key][0][1]) < 0){
                          texto+="<p class='justificado negrita'>- "+response.error[key][0][1]+"</p>"; 
                        }
                      }
                      texto+="<br><table><p class='justificado'>Si deseas continuar se borrarán las siguientes asignaciones de profesores:<br></p><thead><tr><th>Profesor</th><th>Asignatura</th><th>Grupo</th></tr></thead><tbody>";
                      for (var key in response.error) { 
                        texto+="<tr><td>"+response.error[key][0][0]+"</td><td>"+response.error[key][0][1]+"</td><td>"+response.error[key][0][2]+"</td></tr>";
                      }
                      texto+="</tbody><br></p></table>";
                      texto+="<br><br>¿Estas seguro de continuar? No podrás deshacer este paso...";
                      error.play();
                      swal({
                        title: "Asignaturas del Curso Eliminadas",
                        html: texto,
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonText: "Cancelar",
                        confirmButtonColor: color,
                        confirmButtonText: "¡Adelante!",
                        width: "600px"
                        }).then(function () {
                          $.ajax({
                            type: 'POST',
                            url: Routing.generate('asignar_asignaturas_curso'),
                            data: {curso:curso, nuevas:nuevas, asignadas:asignadas, eliminadas:eliminadas, modulos:modulos},
                            dataType: 'json',
                            success: function(response) {
                              if(response.data==null){
                                $('#asignatura_curso_dialog').dialog('close');
                                errorPNotify.play();

                                new PNotify({
                                  text:'No hay modificaciones de asignaturas para este curso.',
                                  addclass: "custom",
                                  type: "error",
                                  shadow: true,
                                  hide: true,
                                  buttons: {
                                    sticker: false,
                                    labels:{close: "Cerrar"}
                                  },
                                  stack: right_Stack,
                                  animate: {
                                    animate: true,
                                    in_class: "fadeInRight",
                                    out_class: "fadeOutRight",
                                  }
                                });
                                return false;
                              }

                              if(response.data!=1)
                              {
                                error.play();
                                texto="<p class='justificado'> El número de módulos seleccionados sobrepasa al número de módulos semanales:</p></br>";
                                texto=texto+"<li>Nº de módulos seleccionados: <span class='negrita color_rojo_swal'>"+modulos+" </span></li>";
                                texto=texto+"<li>Nº de módulos semanales: <span class='negrita'>"+response.data+" </span></li></br>";
                                texto=texto+"<p>Seleccione el número de módulos de las asignaturas teniendo en cuenta el número de modulos semanales.</p></p>";
                                swal({
                                  title: "Nº de Módulos No Permitidos",
                                  html: texto,
                                  type: "error",
                                  confirmButtonColor: color
                                });
                                return false;
                              }

                              //Se obtiene el número de asignaciones registradas, actualizadas y eliminadas.
                              var1=response.asignadas;
                              var2=response.actualizadas;
                              var3=response.eliminadas;
                              text="";
                              if(var1==1){
                                text+=" <span>"+var1+"  Asignación registrada <span>";
                              }
                              else if(var1>1){
                                text+=" <span>"+var1+"  Asignaciones registradas <span>";
                              }

                              if(var2==1){
                                text+=" <span>"+var2+"  Asignación actualizada<span>";
                              }
                              else if(var2>1){
                                text+=" <span>"+var2+"  Asignaciones actualizadas<span>";
                              }

                              if(var3==1){
                                text+=" <span>"+var3+"  Asignación eliminada<span>";
                              }
                              else if(var3>1){
                                text+=" <span>"+var3+"  Asignaciones eliminadas<span>";
                              }
                              if(var1!=0 || var2!=0 || var3!=0){
                                exito.play();
                                new PNotify({
                                  text:text,
                                  addclass: "custom",
                                  type: "success",
                                  shadow: true,
                                  hide: true,
                                  width: "335px",
                                  animation: "fade",
                                  animate_speed: 'fast',
                                  delay: 4000,
                                  buttons: {
                                    sticker: false,
                                    labels:{close: "Cerrar"}
                                  },
                                  stack: right_Stack_dialog,
                                  animate: {
                                    animate: true,
                                    in_class: "fadeInRight",
                                    out_class: "fadeOutRight",
                                  }
                                });
                              }
                              id=$("#asignaturas_cursos .lista_cursos .elected").attr("id");
                              $("#tabs>div[style='display: block']").load(Routing.generate("asignaturas_cursos"), function(){
                                $("#asignaturas_cursos .lista_cursos button[id='"+id+"']").click();
                              });

                              $('#asignatura_curso_dialog').dialog('close');
                              $("#profesor_asignar_grupo").update_tab();
                              $("#asignar_horario").update_tab();
                              $("#asignar_optativa").update_tab();
                            }
                          })
                        }, function (dismiss) {

                        }
                      );
                    } //Se realiza lo mismo sin mostrar aviso para el caso de que ninguna de las asignaturas eliminada tenga asignada un profesor.
                    else{
                      $.ajax({
                        type: 'POST',
                        url: Routing.generate('asignar_asignaturas_curso'),
                        data: {curso:curso, nuevas:nuevas, asignadas:asignadas, eliminadas:eliminadas, modulos:modulos},
                        dataType: 'json',
                        success: function(response) {
                          if(response.data==null){
                            $('#asignatura_curso_dialog').dialog('close');
                            errorPNotify.play();

                            new PNotify({
                              text:'No hay modificaciones de asignaturas para este curso.',
                              addclass: "custom",
                              type: "error",
                              shadow: true,
                              hide: true,
                              buttons: {
                                sticker: false,
                                labels:{close: "Cerrar"}
                              },
                              stack: right_Stack,
                              animate: {
                                animate: true,
                                in_class: "fadeInRight",
                                out_class: "fadeOutRight",
                              }
                            });
                            return false;
                          }
                          if(response.data!=1)
                          {
                            error.play();
                            texto="<p class='justificado'> El número de módulos seleccionados sobrepasa al número de módulos semanales:</p></br>";
                            texto=texto+"<li>Nº de módulos seleccionados: <span class='negrita color_rojo_swal'>"+modulos+" </span></li>";
                            texto=texto+"<li>Nº de módulos semanales: <span class='negrita'>"+response.data+" </span></li></br>";
                            texto=texto+"<p>Seleccione el número de módulos de las asignaturas teniendo en cuenta el número de modulos semanales.</p></p>";
                            swal({
                              title: "Nº de Módulos No Permitidos",
                              html: texto,
                              type: "error",
                              confirmButtonColor: color
                            });
                            return false;
                          }

                          //Se obtiene el número de asignaciones registradas, actualizadas y eliminadas.
                          var1=response.asignadas;
                          var2=response.actualizadas;
                          var3=response.eliminadas;
                          text="";
                          if(var1==1){
                            text+=" <span>"+var1+"  Asignación registrada <span>";
                          }
                          else if(var1>1){
                            text+=" <span>"+var1+"  Asignaciones registradas <span>";
                          }

                          if(var2==1){
                            text+=" <span>"+var2+"  Asignación actualizada<span>";
                          }
                          else if(var2>1){
                            text+=" <span>"+var2+"  Asignaciones actualizadas<span>";
                          }

                          if(var3==1){
                            text+=" <span>"+var3+"  Asignación eliminada<span>";
                          }
                          else if(var3>1){
                            text+=" <span>"+var3+"  Asignaciones eliminadas<span>";
                          }
                          if(var1!=0 || var2!=0 || var3!=0){
                            exito.play();
                            new PNotify({
                              text:text,
                              addclass: "custom",
                              type: "success",
                              shadow: true,
                              hide: true,
                              width: "335px",
                              animation: "fade",
                              animate_speed: 'fast',
                              delay: 4000,
                              buttons: {
                                sticker: false,
                                labels:{close: "Cerrar"}
                              },
                              stack: right_Stack_dialog,
                              animate: {
                                animate: true,
                                in_class: "fadeInRight",
                                out_class: "fadeOutRight",
                              }
                            });
                          }
                          id=$("#asignaturas_cursos .lista_cursos .elected").attr("id");
                          $("#tabs>div[style='display: block']").load(Routing.generate("asignaturas_cursos"), function(){
                            $("#asignaturas_cursos .lista_cursos button[id='"+id+"']").click();
                          });

                          $('#asignatura_curso_dialog').dialog('close');
                          $("#profesor_asignar_grupo").update_tab();
                          $("#asignar_horario").update_tab();
                          $("#asignar_optativa").update_tab();
                        }
                      })
                    }
                  }
                })
               }
               //Se realiza lo mismo sin mostrar aviso para el caso de que no exista asignación en las asignaturas eliminadas.
               else{
                $.ajax({
                  type: 'POST',
                  url: Routing.generate('asignar_asignaturas_curso'),
                  data: {curso:curso, nuevas:nuevas, asignadas:asignadas, eliminadas:eliminadas,modulos:modulos},
                  dataType: 'json',
                  success: function(response) {
                    if(response.data==null){
                      $('#asignatura_curso_dialog').dialog('close');
                      errorPNotify.play();

                      new PNotify({
                        text:'No hay modificaciones de asignaturas para este curso.',
                        addclass: "custom",
                        type: "error",
                        shadow: true,
                        hide: true,
                        buttons: {
                          sticker: false,
                          labels:{close: "Cerrar"}
                        },
                        stack: right_Stack,
                        animate: {
                          animate: true,
                          in_class: "fadeInRight",
                          out_class: "fadeOutRight",
                        }
                      });
                      return false;
                    }

                    if(response.data!=1)
                    {
                      error.play();
                      texto="<p class='justificado'> El número de módulos seleccionados sobrepasa al número de módulos semanales:</p></br>";
                      texto=texto+"<li>Nº de módulos seleccionados: <span class='negrita color_rojo_swal'>"+modulos+" </span></li>";
                      texto=texto+"<li>Nº de módulos semanales: <span class='negrita'>"+response.data+" </span></li></br>";
                      texto=texto+"<p>Seleccione el número de módulos de las asignaturas teniendo en cuenta el número de modulos semanales.</p></p>";
                      swal({
                        title: "Nº de Módulos No Permitidos",
                        html: texto,
                        type: "error",
                        confirmButtonColor: color
                      });
                      return false;
                    }

                    //Se obtiene el número de asignaciones registradas, actualizadas y eliminadas.
                    var1=response.asignadas;
                    var2=response.actualizadas;
                    var3=response.eliminadas;
                    text="";
                    if(var1==1){
                      text+=" <span>"+var1+"  Asignación registrada <span>";
                    }
                    else if(var1>1){
                      text+=" <span>"+var1+"  Asignaciones registradas <span>";
                    }

                    if(var2==1){
                      text+=" <span>"+var2+"  Asignación actualizada<span>";
                    }
                    else if(var2>1){
                      text+=" <span>"+var2+"  Asignaciones actualizadas<span>";
                    }

                    if(var3==1){
                      text+=" <span>"+var3+"  Asignación eliminada<span>";
                    }
                    else if(var3>1){
                      text+=" <span>"+var3+"  Asignaciones eliminadas<span>";
                    }
          
                    if(var1!=0 || var2!=0 || var3!=0){
                      exito.play();
                      new PNotify({
                        text:text,
                        addclass: "custom",
                        type: "success",
                        shadow: true,
                        hide: true,
                        width: "335px",
                        animation: "fade",
                        animate_speed: 'fast',
                        delay: 4000,
                        buttons: {
                          sticker: false,
                          labels:{close: "Cerrar"}
                        },
                        stack: right_Stack_dialog,
                        animate: {
                          animate: true,
                          in_class: "fadeInRight",
                          out_class: "fadeOutRight",
                        }
                      });
                    }
                    id=$("#asignaturas_cursos .lista_cursos .elected").attr("id");
                    $("#tabs>div[style='display: block']").load(Routing.generate("asignaturas_cursos"), function(){
                      $("#asignaturas_cursos .lista_cursos button[id='"+id+"']").click();
                    });          
                    $('#asignatura_curso_dialog').dialog('close');
                    $("#profesor_asignar_grupo").update_tab();
                    $("#asignar_horario").update_tab();
                    $("#asignar_optativa").update_tab();
                  }
                })
               }
              }, function (dismiss) {
                if (dismiss === 'cancel') {
                  return false;
                }
              }
            )
          }
          else{
            if(!$.isEmptyObject(eliminadas)){

              // Se muestra un aviso en caso de que se vaya a eliminar asignaturas con asignaciones de profesores registradas en el sistema.
              $.ajax({
                type: 'POST',
                url: Routing.generate('comprobar_asignaciones_profesores'),
                data: {eliminadas:eliminadas},
                dataType: 'json',
                success: function(response) {
                  texto="";
                  if(response.error!=""){
                    texto+="<p class='justificado'>Las siguientes asignaturas que se quieren eliminar tienen asignados profesores en alguno de los grupos de "+name_curso+":</p><br>";
                    for (var key in response.error) { 
                      if (texto.indexOf(response.error[key][0][1]) < 0){
                        texto+="<p class='justificado negrita'>- "+response.error[key][0][1]+"</p>"; 
                      }
                    }
                    texto+="<br><table><p class='justificado'>Si deseas continuar se borrarán las siguientes asignaciones de profesores:<br></p><thead><tr><th>Profesor</th><th>Asignatura</th><th>Grupo</th></tr></thead><tbody>";
                    for (var key in response.error) { 
                      texto+="<tr><td>"+response.error[key][0][0]+"</td><td>"+response.error[key][0][1]+"</td><td>"+response.error[key][0][2]+"</td></tr>";
                    }
                    texto+="</tbody><br></p></table>";
                    texto+="<br><br>¿Estas seguro de continuar? No podrás deshacer este paso...";
                    error.play();
                    swal({
                      title: "Asignaturas del Curso Eliminadas",
                      html: texto,
                      type: "warning",
                      showCancelButton: true,
                      cancelButtonText: "Cancelar",
                      confirmButtonColor: color,
                      confirmButtonText: "¡Adelante!",
                      width: "600px"
                      }).then(function () {
                        $.ajax({
                          type: 'POST',
                          url: Routing.generate('asignar_asignaturas_curso'),
                          data: {curso:curso, nuevas:nuevas, asignadas:asignadas, eliminadas:eliminadas, modulos:modulos},
                          dataType: 'json',
                          success: function(response) {
                            if(response.data==null){
                              $('#asignatura_curso_dialog').dialog('close');
                              errorPNotify.play();

                              new PNotify({
                                text:'No hay modificaciones de asignaturas para este curso.',
                                addclass: "custom",
                                type: "error",
                                shadow: true,
                                hide: true,
                                buttons: {
                                  sticker: false,
                                  labels:{close: "Cerrar"}
                                },
                                stack: right_Stack,
                                animate: {
                                  animate: true,
                                  in_class: "fadeInRight",
                                  out_class: "fadeOutRight",
                                }
                              });
                              return false;
                            }

                            if(response.data!=1)
                            {
                              error.play();
                              texto="<p class='justificado'> El número de módulos seleccionados sobrepasa al número de módulos semanales:</p></br>";
                              texto=texto+"<li>Nº de módulos seleccionados: <span class='negrita color_rojo_swal'>"+modulos+" </span></li>";
                              texto=texto+"<li>Nº de módulos semanales: <span class='negrita'>"+response.data+" </span></li></br>";
                              texto=texto+"<p>Seleccione el número de módulos de las asignaturas teniendo en cuenta el número de modulos semanales.</p></p>";
                              swal({
                                title: "Nº de Módulos No Permitidos",
                                html: texto,
                                type: "error",
                                confirmButtonColor: color
                              });
                              return false;
                            }

                            //Se obtiene el número de asignaciones registradas, actualizadas y eliminadas.
                            var1=response.asignadas;
                            var2=response.actualizadas;
                            var3=response.eliminadas;
                            text="";
                            if(var1==1){
                              text+=" <span>"+var1+"  Asignación registrada <span>";
                            }
                            else if(var1>1){
                              text+=" <span>"+var1+"  Asignaciones registradas <span>";
                            }

                            if(var2==1){
                              text+=" <span>"+var2+"  Asignación actualizada<span>";
                            }
                            else if(var2>1){
                              text+=" <span>"+var2+"  Asignaciones actualizadas<span>";
                            }

                            if(var3==1){
                              text+=" <span>"+var3+"  Asignación eliminada<span>";
                            }
                            else if(var3>1){
                              text+=" <span>"+var3+"  Asignaciones eliminadas<span>";
                            }
                            if(var1!=0 || var2!=0 || var3!=0){
                              exito.play();
                              new PNotify({
                                text:text,
                                addclass: "custom",
                                type: "success",
                                shadow: true,
                                hide: true,
                                width: "335px",
                                animation: "fade",
                                animate_speed: 'fast',
                                delay: 4000,
                                buttons: {
                                  sticker: false,
                                  labels:{close: "Cerrar"}
                                },
                                stack: right_Stack_dialog,
                                animate: {
                                  animate: true,
                                  in_class: "fadeInRight",
                                  out_class: "fadeOutRight",
                                }
                              });
                            }
                            id=$("#asignaturas_cursos .lista_cursos .elected").attr("id");
                            $("#tabs>div[style='display: block']").load(Routing.generate("asignaturas_cursos"), function(){
                              $("#asignaturas_cursos .lista_cursos button[id='"+id+"']").click();
                            });

                            $('#asignatura_curso_dialog').dialog('close');
                            $("#profesor_asignar_grupo").update_tab();
                            $("#asignar_horario").update_tab();
                            $("#asignar_optativa").update_tab();
                          }
                        })
                      }, function (dismiss) {

                      }
                    );
                  } //Se realiza lo mismo sin mostrar aviso para el caso de que ninguna de las asignaturas eliminada tenga asignada un profesor.
                  else{
                    $.ajax({
                      type: 'POST',
                      url: Routing.generate('asignar_asignaturas_curso'),
                      data: {curso:curso, nuevas:nuevas, asignadas:asignadas, eliminadas:eliminadas, modulos:modulos},
                      dataType: 'json',
                      success: function(response) {
                        if(response.data==null){
                          $('#asignatura_curso_dialog').dialog('close');
                          errorPNotify.play();

                          new PNotify({
                            text:'No hay modificaciones de asignaturas para este curso.',
                            addclass: "custom",
                            type: "error",
                            shadow: true,
                            hide: true,
                            buttons: {
                              sticker: false,
                              labels:{close: "Cerrar"}
                            },
                            stack: right_Stack,
                            animate: {
                              animate: true,
                              in_class: "fadeInRight",
                              out_class: "fadeOutRight",
                            }
                          });
                          return false;
                        }
                        if(response.data!=1)
                        {
                          error.play();
                          texto="<p class='justificado'> El número de módulos seleccionados sobrepasa al número de módulos semanales:</p></br>";
                          texto=texto+"<li>Nº de módulos seleccionados: <span class='negrita color_rojo_swal'>"+modulos+" </span></li>";
                          texto=texto+"<li>Nº de módulos semanales: <span class='negrita'>"+response.data+" </span></li></br>";
                          texto=texto+"<p>Seleccione el número de módulos de las asignaturas teniendo en cuenta el número de modulos semanales.</p></p>";
                          swal({
                            title: "Nº de Módulos No Permitidos",
                            html: texto,
                            type: "error",
                            confirmButtonColor: color
                          });
                          return false;
                        }

                        //Se obtiene el número de asignaciones registradas, actualizadas y eliminadas.
                        var1=response.asignadas;
                        var2=response.actualizadas;
                        var3=response.eliminadas;
                        text="";
                        if(var1==1){
                          text+=" <span>"+var1+"  Asignación registrada <span>";
                        }
                        else if(var1>1){
                          text+=" <span>"+var1+"  Asignaciones registradas <span>";
                        }

                        if(var2==1){
                          text+=" <span>"+var2+"  Asignación actualizada<span>";
                        }
                        else if(var2>1){
                          text+=" <span>"+var2+"  Asignaciones actualizadas<span>";
                        }

                        if(var3==1){
                          text+=" <span>"+var3+"  Asignación eliminada<span>";
                        }
                        else if(var3>1){
                          text+=" <span>"+var3+"  Asignaciones eliminadas<span>";
                        }
                        if(var1!=0 || var2!=0 || var3!=0){
                          exito.play();
                          new PNotify({
                            text:text,
                            addclass: "custom",
                            type: "success",
                            shadow: true,
                            hide: true,
                            width: "335px",
                            animation: "fade",
                            animate_speed: 'fast',
                            delay: 4000,
                            buttons: {
                              sticker: false,
                              labels:{close: "Cerrar"}
                            },
                            stack: right_Stack_dialog,
                            animate: {
                              animate: true,
                              in_class: "fadeInRight",
                              out_class: "fadeOutRight",
                            }
                          });
                        }
                        id=$("#asignaturas_cursos .lista_cursos .elected").attr("id");
                        $("#tabs>div[style='display: block']").load(Routing.generate("asignaturas_cursos"), function(){
                          $("#asignaturas_cursos .lista_cursos button[id='"+id+"']").click();
                        });

                        $('#asignatura_curso_dialog').dialog('close');
                        $("#profesor_asignar_grupo").update_tab();
                        $("#asignar_horario").update_tab();
                        $("#asignar_optativa").update_tab();
                      }
                    })
                  }
                }
              })
            }
            //Se realiza lo mismo sin mostrar aviso para el caso de que no exista asignación en las asignaturas eliminadas.
            else{
              $.ajax({
                type: 'POST',
                url: Routing.generate('asignar_asignaturas_curso'),
                data: {curso:curso, nuevas:nuevas, asignadas:asignadas, eliminadas:eliminadas,modulos:modulos},
                dataType: 'json',
                success: function(response){
                  if(response.data==null){
                    $('#asignatura_curso_dialog').dialog('close');
                    errorPNotify.play();

                    new PNotify({
                      text:'No hay modificaciones de asignaturas para este curso.',
                      addclass: "custom",
                      type: "error",
                      shadow: true,
                      hide: true,
                      buttons: {
                        sticker: false,
                        labels:{close: "Cerrar"}
                      },
                      stack: right_Stack,
                      animate: {
                        animate: true,
                        in_class: "fadeInRight",
                        out_class: "fadeOutRight",
                      }
                    });
                    return false;
                  }

                  if(response.data!=1)
                  {
                    error.play();
                    texto="<p class='justificado'> El número de módulos seleccionados sobrepasa al número de módulos semanales:</p></br>";
                    texto=texto+"<li>Nº de módulos seleccionados: <span class='negrita color_rojo_swal'>"+modulos+" </span></li>";
                    texto=texto+"<li>Nº de módulos semanales: <span class='negrita'>"+response.data+" </span></li></br>";
                    texto=texto+"<p>Seleccione el número de módulos de las asignaturas teniendo en cuenta el número de modulos semanales.</p></p>";
                    swal({
                      title: "Nº de Módulos No Permitidos",
                      html: texto,
                      type: "error",
                      confirmButtonColor: color
                    });
                    return false;
                  }

                  //Se obtiene el número de asignaciones registradas, actualizadas y eliminadas.
                  var1=response.asignadas;
                  var2=response.actualizadas;
                  var3=response.eliminadas;
                  text="";
                  if(var1==1){
                    text+=" <span>"+var1+"  Asignación registrada <span>";
                  }
                  else if(var1>1){
                    text+=" <span>"+var1+"  Asignaciones registradas <span>";
                  }

                  if(var2==1){
                    text+=" <span>"+var2+"  Asignación actualizada<span>";
                  }
                  else if(var2>1){
                    text+=" <span>"+var2+"  Asignaciones actualizadas<span>";
                  }

                  if(var3==1){
                    text+=" <span>"+var3+"  Asignación eliminada<span>";
                  }
                  else if(var3>1){
                    text+=" <span>"+var3+"  Asignaciones eliminadas<span>";
                  }
          
                  if(var1!=0 || var2!=0 || var3!=0){
                    exito.play();
                    new PNotify({
                      text:text,
                      addclass: "custom",
                      type: "success",
                      shadow: true,
                      hide: true,
                      width: "335px",
                      animation: "fade",
                      animate_speed: 'fast',
                      delay: 4000,
                      buttons: {
                        sticker: false,
                        labels:{close: "Cerrar"}
                      },
                      stack: right_Stack_dialog,
                      animate: {
                        animate: true,
                        in_class: "fadeInRight",
                        out_class: "fadeOutRight",
                      }
                    });
                  }
                  id=$("#asignaturas_cursos .lista_cursos .elected").attr("id");
                  $("#tabs>div[style='display: block']").load(Routing.generate("asignaturas_cursos"), function(){
                    $("#asignaturas_cursos .lista_cursos button[id='"+id+"']").click();
                  });          
                  $('#asignatura_curso_dialog').dialog('close');
                  $("#profesor_asignar_grupo").update_tab();
                  $("#asignar_horario").update_tab();
                  $("#asignar_optativa").update_tab();
                }
              })
            }
          }
        }
      })
    } //Se realiza lo mismo sin mostrar aviso para el caso de que no exista asignación con módulos actualizados.
    else{
      if(!$.isEmptyObject(eliminadas)){
        // Se muestra un aviso en caso de que se vaya a eliminar asignaturas con asignaciones de profesores registradas en el sistema.
        $.ajax({
          type: 'POST',
          url: Routing.generate('comprobar_asignaciones_profesores'),
          data: {eliminadas:eliminadas},
          dataType: 'json',
          success: function(response) {
            texto="";
            if(response.error!=""){
              texto+="<p class='justificado'>Las siguientes asignaturas que se quieren eliminar tienen asignados profesores en alguno de los grupos de "+name_curso+":</p><br>";
              for (var key in response.error) { 
                if (texto.indexOf(response.error[key][0][1]) < 0){
                  texto+="<p class='justificado negrita'>- "+response.error[key][0][1]+"</p>"; 
                }
              }
              texto+="<br><table><p class='justificado'>Si deseas continuar se borrarán las siguientes asignaciones de profesores:<br></p><thead><tr><th>Profesor</th><th>Asignatura</th><th>Grupo</th></tr></thead><tbody>";
              for (var key in response.error) { 
                texto+="<tr><td>"+response.error[key][0][0]+"</td><td>"+response.error[key][0][1]+"</td><td>"+response.error[key][0][2]+"</td></tr>";
              }
              texto+="</tbody><br></p></table>";
              texto+="<br><br>¿Estas seguro de continuar? No podrás deshacer este paso...";
              error.play();
              swal({
                title: "Asignaturas del Curso Eliminadas",
                html: texto,
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                confirmButtonColor: color,
                confirmButtonText: "¡Adelante!",
                width: "600px"
                }).then(function () {
                  $.ajax({
                    type: 'POST',
                    url: Routing.generate('asignar_asignaturas_curso'),
                    data: {curso:curso, nuevas:nuevas, asignadas:asignadas, eliminadas:eliminadas, modulos:modulos},
                    dataType: 'json',
                    success: function(response) {
                      if(response.data==null){
                        $('#asignatura_curso_dialog').dialog('close');
                        errorPNotify.play();

                        new PNotify({
                          text:'No hay modificaciones de asignaturas para este curso.',
                          addclass: "custom",
                          type: "error",
                          shadow: true,
                          hide: true,
                          buttons: {
                            sticker: false,
                            labels:{close: "Cerrar"}
                          },
                          stack: right_Stack,
                          animate: {
                            animate: true,
                            in_class: "fadeInRight",
                            out_class: "fadeOutRight",
                          }
                        });
                        return false;
                      }

                      if(response.data!=1)
                      {
                        error.play();
                        texto="<p class='justificado'> El número de módulos seleccionados sobrepasa al número de módulos semanales:</p></br>";
                        texto=texto+"<li>Nº de módulos seleccionados: <span class='negrita color_rojo_swal'>"+modulos+" </span></li>";
                        texto=texto+"<li>Nº de módulos semanales: <span class='negrita'>"+response.data+" </span></li></br>";
                        texto=texto+"<p>Seleccione el número de módulos de las asignaturas teniendo en cuenta el número de modulos semanales.</p></p>";
                        swal({
                          title: "Nº de Módulos No Permitidos",
                          html: texto,
                          type: "error",
                          confirmButtonColor: color
                        });
                        return false;
                      }

                      //Se obtiene el número de asignaciones registradas, actualizadas y eliminadas.
                      var1=response.asignadas;
                      var2=response.actualizadas;
                      var3=response.eliminadas;
                      text="";
                      if(var1==1){
                        text+=" <span>"+var1+"  Asignación registrada <span>";
                      }
                      else if(var1>1){
                        text+=" <span>"+var1+"  Asignaciones registradas <span>";
                      }

                      if(var2==1){
                        text+=" <span>"+var2+"  Asignación actualizada<span>";
                      }
                      else if(var2>1){
                        text+=" <span>"+var2+"  Asignaciones actualizadas<span>";
                      }
                      if(var3==1){
                        text+=" <span>"+var3+"  Asignación eliminada<span>";
                      }
                      else if(var3>1){
                        text+=" <span>"+var3+"  Asignaciones eliminadas<span>";
                      }
                      if(var1!=0 || var2!=0 || var3!=0){
                        exito.play();
                        new PNotify({
                          text:text,
                          addclass: "custom",
                          type: "success",
                          shadow: true,
                          hide: true,
                          width: "335px",
                          animation: "fade",
                          animate_speed: 'fast',
                          delay: 4000,
                          buttons: {
                            sticker: false,
                            labels:{close: "Cerrar"}
                          },
                          stack: right_Stack_dialog,
                          animate: {
                            animate: true,
                            in_class: "fadeInRight",
                            out_class: "fadeOutRight",
                          }
                        });
                      }
                      id=$("#asignaturas_cursos .lista_cursos .elected").attr("id");
                      $("#tabs>div[style='display: block']").load(Routing.generate("asignaturas_cursos"), function(){
                        $("#asignaturas_cursos .lista_cursos button[id='"+id+"']").click();
                      });

                      $('#asignatura_curso_dialog').dialog('close');
                      $("#profesor_asignar_grupo").update_tab();
                      $("#asignar_horario").update_tab();
                      $("#asignar_optativa").update_tab();
                    }
                  })
                }, function (dismiss) {

                }
              );
            } //Se realiza lo mismo sin mostrar aviso para el caso de que ninguna de las asignaturas eliminada tenga asignada un profesor.
            else{
              $.ajax({
                type: 'POST',
                url: Routing.generate('asignar_asignaturas_curso'),
                data: {curso:curso, nuevas:nuevas, asignadas:asignadas, eliminadas:eliminadas, modulos:modulos},
                dataType: 'json',
                success: function(response) {
                  if(response.data==null){
                    $('#asignatura_curso_dialog').dialog('close');
                    errorPNotify.play();

                    new PNotify({
                      text:'No hay modificaciones de asignaturas para este curso.',
                      addclass: "custom",
                      type: "error",
                      shadow: true,
                      hide: true,
                      buttons: {
                        sticker: false,
                        labels:{close: "Cerrar"}
                      },
                      stack: right_Stack,
                      animate: {
                        animate: true,
                        in_class: "fadeInRight",
                        out_class: "fadeOutRight",
                      }
                    });
                    return false;
                  }
                  if(response.data!=1)
                  {
                    error.play();
                    texto="<p class='justificado'> El número de módulos seleccionados sobrepasa al número de módulos semanales:</p></br>";
                    texto=texto+"<li>Nº de módulos seleccionados: <span class='negrita color_rojo_swal'>"+modulos+" </span></li>";
                    texto=texto+"<li>Nº de módulos semanales: <span class='negrita'>"+response.data+" </span></li></br>";
                    texto=texto+"<p>Seleccione el número de módulos de las asignaturas teniendo en cuenta el número de modulos semanales.</p></p>";
                    swal({
                      title: "Nº de Módulos No Permitidos",
                      html: texto,
                      type: "error",
                      confirmButtonColor: color
                    });
                    return false;
                  }

                  //Se obtiene el número de asignaciones registradas, actualizadas y eliminadas.
                  var1=response.asignadas;
                  var2=response.actualizadas;
                  var3=response.eliminadas;
                  text="";
                  if(var1==1){
                    text+=" <span>"+var1+"  Asignación registrada <span>";
                  }
                  else if(var1>1){
                    text+=" <span>"+var1+"  Asignaciones registradas <span>";
                  }

                  if(var2==1){
                    text+=" <span>"+var2+"  Asignación actualizada<span>";
                  }
                  else if(var2>1){
                    text+=" <span>"+var2+"  Asignaciones actualizadas<span>";
                  }

                  if(var3==1){
                    text+=" <span>"+var3+"  Asignación eliminada<span>";
                  }
                  else if(var3>1){
                    text+=" <span>"+var3+"  Asignaciones eliminadas<span>";
                  }
                  if(var1!=0 || var2!=0 || var3!=0){
                    exito.play();
                    new PNotify({
                      text:text,
                      addclass: "custom",
                      type: "success",
                      shadow: true,
                      hide: true,
                      width: "335px",
                      animation: "fade",
                      animate_speed: 'fast',
                      delay: 4000,
                      buttons: {
                        sticker: false,
                        labels:{close: "Cerrar"}
                      },
                      stack: right_Stack_dialog,
                      animate: {
                        animate: true,
                        in_class: "fadeInRight",
                        out_class: "fadeOutRight",
                      }
                    });
                  }
                  id=$("#asignaturas_cursos .lista_cursos .elected").attr("id");
                  $("#tabs>div[style='display: block']").load(Routing.generate("asignaturas_cursos"), function(){
                    $("#asignaturas_cursos .lista_cursos button[id='"+id+"']").click();
                  });

                  $('#asignatura_curso_dialog').dialog('close');
                  $("#profesor_asignar_grupo").update_tab();
                  $("#asignar_horario").update_tab();
                  $("#asignar_optativa").update_tab();
                }
              })
            }
          }
        })
      }
      //Se realiza lo mismo sin mostrar aviso para el caso de que no exista asignación en las asignaturas eliminadas.
      else{
        $.ajax({
          type: 'POST',
          url: Routing.generate('asignar_asignaturas_curso'),
          data: {curso:curso, nuevas:nuevas, asignadas:asignadas, eliminadas:eliminadas,modulos:modulos},
          dataType: 'json',
          success: function(response){
            if(response.data==null){
              $('#asignatura_curso_dialog').dialog('close');
              errorPNotify.play();

              new PNotify({
                text:'No hay modificaciones de asignaturas para este curso.',
                addclass: "custom",
                type: "error",
                shadow: true,
                hide: true,
                buttons: {
                  sticker: false,
                  labels:{close: "Cerrar"}
                },
                stack: right_Stack,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              });
              return false;
            }

            if(response.data!=1)
            {
              error.play();
              texto="<p class='justificado'> El número de módulos seleccionados sobrepasa al número de módulos semanales:</p></br>";
              texto=texto+"<li>Nº de módulos seleccionados: <span class='negrita color_rojo_swal'>"+modulos+" </span></li>";
              texto=texto+"<li>Nº de módulos semanales: <span class='negrita'>"+response.data+" </span></li></br>";
              texto=texto+"<p>Seleccione el número de módulos de las asignaturas teniendo en cuenta el número de modulos semanales.</p></p>";
              swal({
                title: "Nº de Módulos No Permitidos",
                html: texto,
                type: "error",
                confirmButtonColor: color
              });
              return false;
            }

            //Se obtiene el número de asignaciones registradas, actualizadas y eliminadas.
            var1=response.asignadas;
            var2=response.actualizadas;
            var3=response.eliminadas;
            text="";
            if(var1==1){
              text+=" <span>"+var1+"  Asignación registrada <span>";
            }
            else if(var1>1){
              text+=" <span>"+var1+"  Asignaciones registradas <span>";
            }

            if(var2==1){
              text+=" <span>"+var2+"  Asignación actualizada<span>";
            }
            else if(var2>1){
              text+=" <span>"+var2+"  Asignaciones actualizadas<span>";
            }

            if(var3==1){
              text+=" <span>"+var3+"  Asignación eliminada<span>";
            }
            else if(var3>1){
              text+=" <span>"+var3+"  Asignaciones eliminadas<span>";
            }
          
            if(var1!=0 || var2!=0 || var3!=0){
              exito.play();
              new PNotify({
                text:text,
                addclass: "custom",
                type: "success",
                shadow: true,
                hide: true,
                width: "335px",
                animation: "fade",
                animate_speed: 'fast',
                delay: 4000,
                buttons: {
                  sticker: false,
                  labels:{close: "Cerrar"}
                },
                stack: right_Stack_dialog,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              });
            }
            id=$("#asignaturas_cursos .lista_cursos .elected").attr("id");
            $("#tabs>div[style='display: block']").load(Routing.generate("asignaturas_cursos"), function(){
              $("#asignaturas_cursos .lista_cursos button[id='"+id+"']").click();
            });          
            $('#asignatura_curso_dialog').dialog('close');
            $("#profesor_asignar_grupo").update_tab();
            $("#asignar_horario").update_tab();
            $("#asignar_optativa").update_tab();
          }
        })
      }
    }
  });

  //Se bloquea el presionar una tecla en el inpur number del número de módulos.
  $(document).on('keypress',"#asignatura_curso_dialog #contenedor_asignaturas li input[type='number']",function(event){
    event.preventDefault();
  });

  //Se valida el número de módulos modificado.
  $(document).on('change paste cut',"#asignatura_curso_dialog #contenedor_asignaturas li input[type='number']",function(event){
    event.preventDefault();
    //Se habilita los botones "guardar" y "restablecer".
    $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",false);
    $("#asignatura_curso_dialog #asignaturas_curso_restablecer").prop("disabled",false);

    if($(this).val()!="" ){
      $(this).removeClass('invalid');
      if($(this).val()==$(this).attr("valor") && $(this).prev().val()==$(this).prev().attr("valor") ){
        $(this).closest("li").removeClass('back_modificado');
        if($("#asignatura_curso_dialog #contenedor_asignaturas .back_modificado").size()==0 && $("#asignatura_curso_dialog #lista_asignaturas button:not(.elected)[class*='asignada']").size()==0){
          //Se deshabilita los botones de "guardar" y "restablecer".
          $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",true);
          $("#asignatura_curso_dialog #asignaturas_curso_restablecer").prop("disabled",true);
        }
        if($("#asignatura_curso_dialog #contenedor_asignaturas li input[class='invalid']").size()>0){
          $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",true);
        }
      }
      else{
        $(this).closest("li").addClass('back_modificado');
        //Se habilita los botones de "guardar" y "restablecer".
        $("#asignatura_curso_dialog #asignaturas_curso_restablecer").prop("disabled",false);
        if($("#asignatura_curso_dialog #contenedor_asignaturas li input[class='invalid']").size()>0){
          $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",true);
        }else{
          $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",false);
        }
      }
      //Se comprueba sise ha modificado el Nº de grupo en una asignatura opcional, para modificar las demás asignaturas opcionales con el mismo valor.
      if($(this).closest("li").attr("opcional")==1){
        //Se elimina a todos los input de asignaturas opcionales la clase invalid.
        $("#asignatura_curso_dialog #contenedor_asignaturas li[opcional='1'] input[type='number']").removeClass('invalid');
        //Se elimina el aviso de error si no hay más errores.
        if($("#asignatura_curso_dialog #contenedor_asignaturas li[opcional!='1'] .invalid").size()==0){
          $("#asignatura_curso_dialog #aviso_error").addClass('oculto');
        }
        valor=$(this).val();
        li=$(this).closest("li");
        $("#asignatura_curso_dialog #contenedor_asignaturas li[opcional='1']").each(function(){
            if($(this)!=li){
              $(this).find("input[type='number']").val(valor);
              //Se comprueba si se ma modificado algún valor de las demás asignaturaś(más abajo).
              $(this).find("input[type='number']").blur();
            }
        });
      }
    }
    else{
      $(this).addClass('invalid');
      $(this).closest("li").addClass('back_modificado');
      $("#asignatura_curso_dialog #aviso_error").removeClass('oculto');
      //Se deshabilita el botón de "guardar".
      $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",true);
    }
    //Se elimina el aviso de error si no existe más errores.
    if($("#asignatura_curso_dialog #contenedor_asignaturas li input[class='invalid']").size()==0){
      $("#asignatura_curso_dialog #aviso_error").addClass('oculto');
      
      //Se habilita/deshabilita los botones de "guardar" y "restablecer" si hay modificaciones o no.
      if($("#asignatura_curso_dialog #contenedor_asignaturas .back_modificado").size()>0 || $("#asignatura_curso_dialog #lista_asignaturas button:not(.elected)[class*='asignada']").size()>0){
        $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",false);
        $("#asignatura_curso_dialog #asignaturas_curso_restablecer").prop("disabled",false);
      }
      else{
        $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",true);
        $("#asignatura_curso_dialog #asignaturas_curso_restablecer").prop("disabled",true);
      }
    }
  });

  //Se utiliza para mostrar si se ha modificado algún valor al modificar anteriormente el Nº de módulos de una asignatura opcional.
  $(document).on('blur',"#asignatura_curso_dialog #contenedor_asignaturas li input[type='number']",function(event){
  event.preventDefault();
    if($(this).attr("valor")){
      if($(this).attr("valor")==$(this).val()){
        $(this).closest("li").removeClass('back_modificado');
      }
      else{
        $(this).closest("li").addClass('back_modificado');
      }
    }
  });

  //Se valida el libro modificado.
  $(document).on('keyup change',"#asignatura_curso_dialog #contenedor_asignaturas li input[type='text']",function(event){
    event.preventDefault();
    //Se habilita los botones "guardar" y "restablecer".
    $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",false);
    $("#asignatura_curso_dialog #asignaturas_curso_restablecer").prop("disabled",false);

    if($(this).val()==$(this).attr("valor") && $(this).next().val()==$(this).next().attr("valor") ){
      $(this).closest("li").removeClass('back_modificado');
      if($("#asignatura_curso_dialog #contenedor_asignaturas .back_modificado").size()==0){
        //Se deshabilita los botones de "guardar" y "restablecer".
        $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",true);
        $("#asignatura_curso_dialog #asignaturas_curso_restablecer").prop("disabled",true);
      }
    }
    else{
      $(this).closest("li").addClass('back_modificado');
      //Se habilita los botones de "guardar" y "restablecer".
      $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",false);
      $("#asignatura_curso_dialog #asignaturas_curso_restablecer").prop("disabled",false);
    }
  });

  //Se elimina las asignaciones de asignaturas del curso.
  $(document).on('click',"#asignaturas_cursos #eliminar_en_curso ",function(event){
    event.preventDefault();

    //Se avisa si no hay asignaturas asignadas al curso.
    if($("#asignaturas_cursos #contenedor_registro:not(.oculto) tbody tr:not(.no_cursor)").size()==0){
      $(".ui-pnotify").remove();

      errorPNotify.play();
      new PNotify({
        text:'No hay asignaturas asignadas al curso.',
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        stack: right_Stack,
        animate: {
          animate: true,
          in_class: "fadeInRight",
          out_class: "fadeOutRight",
        }
      });
      $("#asignaturas_cursos #eliminar_en_curso").prop("disabled",true);
      return false;
    }
    nombre_curso=$(this).find("span").text();
    aviso.play();
    swal({
      title: "Eliminación de Asignación de Asignaturas",
      html: "<p>Se van a eliminar las asignaciones de las asignaturas de <span class='negrita'>"+nombre_curso+" de Primaria</span>.</p><br>¿Estas seguro de continuar? No podrás deshacer este paso...",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      width: "600px",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!",
      }).then(function () {

        curso=$("#asignaturas_cursos #eliminar_en_curso").attr("curso"); //Id del curso.
        $.ajax({
          type: 'POST',
          url: Routing.generate('eliminar_asignaciones_asignaturas'),
          data: {curso:curso},
          dataType: 'json',
          success: function(response) {
            if(response.data==null){
              errorPNotify.play();

              new PNotify({
                text:'No hay asignaturas asignadas al curso.',
                addclass: "custom",
                type: "error",
                shadow: true,
                hide: true,
                buttons: {
                  sticker: false,
                  labels:{close: "Cerrar"}
                },
                stack: right_Stack,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              });
              return false;
            }
            exito.play();
            new PNotify({
              text:"Se han eliminado las asignaturas asignadas al curso.",
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              animation: "fade",
              animate_speed: 'fast',
              delay: 4000,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
            id=$("#asignaturas_cursos .lista_cursos .elected").attr("id");
            $("#tabs>div[style='display: block']").load(Routing.generate("asignaturas_cursos"), function(){
              $("#asignaturas_cursos .lista_cursos button[id='"+id+"']").click();
            });
            $("#profesor_asignar_grupo").update_tab();
          }
        })
      }, function (dismiss) {

      }
    );
  });

  //Se elimina las asignaciones de asignaturas de todos los cursos.
  $(document).on('click',"#asignaturas_cursos #eliminar_todo ",function(event){
    event.preventDefault();

    //Se avisa si no hay asignaturas asignadas en ningún curso.
    if($("#asignaturas_cursos #contenedor_registro tbody tr:not(.no_cursor)").size()==0){
      errorPNotify.pause();
      errorPNotify.currentTime=0.0;
      $(".ui-pnotify").remove();

      errorPNotify.play();

      new PNotify({
        text:'No hay asignaturas asignadas en ningún curso.',
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        stack: right_Stack,
        animate: {
          animate: true,
          in_class: "fadeInRight",
          out_class: "fadeOutRight",
        }
      });
      $("#asignaturas_cursos #eliminar_todo").prop("disabled",true);
      return false;
    }
    aviso.play();
    swal({
      title: "Eliminación de Asignación de Asignaturas",
      html: "<p>Se van a eliminar las asignaturas de todos los cursos.</p><br>¿Estas seguro de continuar? No podrás deshacer este paso...",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        $.ajax({
          type: 'POST',
          url: Routing.generate('eliminar_todas_asignaciones_asignaturas'),
          dataType: 'json',
          success: function(response) {
            if(response.data==null){
              errorPNotify.play();

              new PNotify({
                text:'No hay asignaturas asignadas en ningún curso.',
                addclass: "custom",
                type: "error",
                shadow: true,
                hide: true,
                buttons: {
                  sticker: false,
                  labels:{close: "Cerrar"}
                },
                stack: right_Stack,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              });
              return false;
            }
            exito.play();
            new PNotify({
              text:"Se han eliminado las asignaciones de asignaturas de todos los cursos.",
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              animation: "fade",
              animate_speed: 'fast',
              delay: 4000,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
            id=$("#asignaturas_cursos .lista_cursos .elected").attr("id");
            $("#tabs>div[style='display: block']").load(Routing.generate("asignaturas_cursos"), function(){
              $("#asignaturas_cursos .lista_cursos button[id='"+id+"']").click();
            });
            $("#profesor_asignar_grupo").update_tab();
          }
        })
      }, function (dismiss) {

      }
    );
  });

  //Se asigna una lista al curso.
  $(document).on('click',"#asignatura_curso_dialog #lista_troncal button",function(event){
    event.preventDefault();
    id=$(this).attr("id");
    tipo=$(this).attr("tipo");

    $(this).addClass('elected');
    if($("#asignatura_curso_dialog #contenedor_asignaturas li[id='"+id+"']").size()==0){
      if($(this).hasClass('asignada')){
        $('<li class="back_modificado" id="'+id+'"tipo="'+tipo+'" estado="asignada"><p>'+$(this).attr("title")+'</p></input><input placeholder="Libro de la asignatura" valor="'+$(this).attr("libro")+'" style="text-transform: capitalize;" type="text"><input type="number" min="1" max="9" valor="'+$(this).attr("valor")+'"></input><img src="/Symfony/web/bundles/backend/images/menu/eliminar.png"></li>').insertBefore($("#asignatura_curso_dialog #cabecera_especifica"));
      }else{
        $('<li class="back_modificado" id="'+id+'"tipo="'+tipo+'" estado="nueva"><p>'+$(this).attr("title")+'</p></input><input placeholder="Libro de la asignatura" style="text-transform: capitalize;" type="text"><input type="number" min="1" max="9"></input><img src="/Symfony/web/bundles/backend/images/menu/eliminar.png"></li>').insertBefore($("#asignatura_curso_dialog #cabecera_especifica"));
      }

    }

    //Se deshabilita el botón de seleccionar todas las asignaturas troncales cuando estan todas seleccionadas.
    if($("#asignatura_curso_dialog #lista_asignaturas #lista_troncal button:not(.elected)").size()==0){
      $("#asignatura_curso_dialog #all_troncales").addClass('disabled');
    }

    $("#asignatura_curso_dialog #contenedor_asignaturas #cabecera_troncal").removeClass('oculto');
    $("#asignatura_curso_dialog #contenedor_asignaturas .aviso").addClass('oculto');
    
    //Se habilita los botones "guardar" y "restablecer".
    $("#asignatura_curso_dialog #asignaturas_curso_restablecer").prop("disabled",false);
    if($("#asignatura_curso_dialog #contenedor_asignaturas li input[class='invalid']").size()>0){
      $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",true);
    }
    else{
      $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",false);
    }
  });

  $(document).on('click',"#asignatura_curso_dialog #lista_especifica button",function(event){
    event.preventDefault();
    id=$(this).attr("id");
    tipo=$(this).attr("tipo");
    opcional=$(this).attr("opcional");

    $(this).addClass('elected');
    if(opcional==1){
      if($("#asignatura_curso_dialog #contenedor_asignaturas li[id='"+id+"']").size()==0){
        if($(this).hasClass('asignada')){
          $("#asignatura_curso_dialog #contenedor_asignaturas ul").append('<li class="back_modificado" id="'+id+'"tipo="'+tipo+'" estado="asignada" opcional="'+$(this).attr("opcional")+'"><p>'+$(this).attr("title")+'</p></input><input placeholder="Libro de la asignatura" valor="'+$(this).attr("libro")+'" style="text-transform: capitalize;" type="text"><input type="number" min="1" max="9" valor="'+$(this).attr("valor")+'"></input><img src="/Symfony/web/bundles/backend/images/menu/eliminar.png"></li>');
        }else{
          $("#asignatura_curso_dialog #contenedor_asignaturas ul").append('<li class="back_modificado" id="'+id+'"tipo="'+tipo+'" estado="nueva" opcional="'+$(this).attr("opcional")+'"><p>'+$(this).attr("title")+'</p></input><input placeholder="Libro de la asignatura" style="text-transform: capitalize;" type="text"><input type="number" min="1" max="9"></input><img src="/Symfony/web/bundles/backend/images/menu/eliminar.png"></li>');
        }
      }
      $("#asignatura_curso_dialog #contenedor_asignaturas #cabecera_especifica_opcional").removeClass('oculto');
    }
    else{
      if($("#asignatura_curso_dialog #contenedor_asignaturas li[id='"+id+"']").size()==0){
        if($(this).hasClass('asignada')){
          $('<li class="back_modificado" id="'+id+'"tipo="'+tipo+'" estado="asignada" opcional="'+$(this).attr("opcional")+'"><p>'+$(this).attr("title")+'</p></input><input placeholder="Libro de la asignatura" valor="'+$(this).attr("libro")+'" style="text-transform: capitalize;" type="text"><input type="number" min="1" max="9" valor="'+$(this).attr("valor")+'"></input><img src="/Symfony/web/bundles/backend/images/menu/eliminar.png"></li>').insertBefore($("#asignatura_curso_dialog #cabecera_especifica_opcional"));
        }else{
          $('<li class="back_modificado" id="'+id+'"tipo="'+tipo+'" estado="nueva" opcional="'+$(this).attr("opcional")+'"><p>'+$(this).attr("title")+'</p></input><input placeholder="Libro de la asignatura" style="text-transform: capitalize;" type="text"><input type="number" min="1" max="9"></input><img src="/Symfony/web/bundles/backend/images/menu/eliminar.png"></li>').insertBefore($("#asignatura_curso_dialog #cabecera_especifica_opcional"));
        }
      }
      $("#asignatura_curso_dialog #contenedor_asignaturas #cabecera_especifica").removeClass('oculto');
    }

    //Se deshabilita el botón de seleccionar todas las asignaturas específicas cuando estan todas seleccionadas.
    if($("#asignatura_curso_dialog #lista_asignaturas #lista_especifica button:not(.elected)").size()==0){
      $("#asignatura_curso_dialog #all_especificas").addClass('disabled');
    }


    $("#asignatura_curso_dialog #contenedor_asignaturas .aviso").addClass('oculto');
    
    //Se habilita los botones "guardar" y "restablecer".
    $("#asignatura_curso_dialog #asignaturas_curso_restablecer").prop("disabled",false);
    if($("#asignatura_curso_dialog #contenedor_asignaturas li input[class='invalid']").size()>0){
      $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",true);
    }
    else{
      $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",false);
    }
  });

  //Se elimina la lista asignada al curso
  $(document).on('click',"#asignatura_curso_dialog #contenedor_asignaturas img",function(event){
    event.preventDefault();
    id=$(this).closest("li").attr("id");
    tipo=$(this).closest("li").attr("tipo");
    asigcurso=$(this).closest("li").attr("asigcurso");//Id de asignación de asignatura/grupo
    //Se habilita los botones "guardar" y "restablecer".
    $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",false);
    $("#asignatura_curso_dialog #asignaturas_curso_restablecer").prop("disabled",false);

    $("#asignatura_curso_dialog #lista_asignaturas button[id='"+id+"']").removeClass('elected');
    $("#asignatura_curso_dialog #lista_asignaturas button[id='"+id+"']").closest("div").next().removeClass('disabled');
    //Se le asigna el Id de la asignación de asignatura/grupo para comprobar que asignación ha sido eliminada.
    $("#asignatura_curso_dialog #lista_asignaturas button[id='"+id+"']").attr('asigcurso',asigcurso);

    $(this).closest("li").remove();

    //Se comprueba si no hay más asignaturas de un tipo para eliminar la cabecera de ese tipo(ol).
    if(tipo=="Troncal"){
      if($("#asignatura_curso_dialog #contenedor_asignaturas li[tipo='Troncal']").size()==0){
        $("#asignatura_curso_dialog #contenedor_asignaturas #cabecera_troncal").addClass('oculto');
      }
    }
    else{
      if($("#asignatura_curso_dialog #contenedor_asignaturas li[opcional='']").size()==0){
        $("#asignatura_curso_dialog #contenedor_asignaturas #cabecera_especifica").addClass('oculto');
      }
      if($("#asignatura_curso_dialog #contenedor_asignaturas li[opcional='1']").size()==0){
        $("#asignatura_curso_dialog #contenedor_asignaturas #cabecera_especifica_opcional").addClass('oculto');
      }
    } 

    //Se oculta el aviso de error si no quedan elementos con input vacíos. Si quedan se deshabilita el botón "guardar".
    if($("#asignatura_curso_dialog #contenedor_asignaturas li input[class='invalid']").size()==0){
      $("#asignatura_curso_dialog #aviso_error").addClass('oculto');
      //Se habilita/deshabilita los botones si hay modificaciones o no.
      if($("#asignatura_curso_dialog #contenedor_asignaturas .back_modificado").size()>0 || $("#asignatura_curso_dialog #lista_asignaturas button:not(.elected)[class*='asignada']").size()>0){
        $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",false);
        $("#asignatura_curso_dialog #asignaturas_curso_restablecer").prop("disabled",false);
      }
      else{
        $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",true);
        $("#asignatura_curso_dialog #asignaturas_curso_restablecer").prop("disabled",true);
      }
    }
    else{
      $("#asignatura_curso_dialog #asignaturas_curso_submit").prop("disabled",true);
    }
  });


//////////////////////////////////
//       Periodo Lectivo        //
//////////////////////////////////
  
  // Botones del calendario.
  $(document).on('click',"#botones_leyenda a[id='inicio']",function(event){
    event.preventDefault();
    fecha=$(this).attr("fecha").split("/");
    $("#calendario")

    month=parseInt($("#calendario td[data-handler='selectDay'] ").attr("data-month"))+1;
    mes=parseInt(fecha[1]);

    year=$("#calendario td[data-handler='selectDay'] ").attr("data-year");             
    if(año==year && mes!=month){
      $("#calendario").datepicker("setDate", $(this).attr("fecha"));
    }
  });

  $(document).on('click',"#botones_leyenda a[id='final']",function(event){
    fecha=$(this).attr("fecha").split("/");
    $("#calendario")

    month=parseInt($("#calendario td[data-handler='selectDay'] ").attr("data-month"))+1;
    mes=parseInt(fecha[1]);

    year=$("#calendario td[data-handler='selectDay'] ").attr("data-year");             
    if(año==year && mes!=month){
      $("#calendario").datepicker("setDate", $(this).attr("fecha"));
    }

  });
  
  $(document).on('click',"#botones_leyenda a[id='hoy']",function(event){
    event.preventDefault();  
    if(!$("#calendario td").hasClass("ui-datepicker-today")){
      $("#calendario").datepicker().datepicker("setDate", new Date());
    }
  });

  $(document).on("mouseover","#botones_leyenda a", function () {
    $(this).addClass("ui-state-hover");
  });

  $(document).on("mouseout","#botones_leyenda a", function () {
    $(this).removeClass("ui-state-hover");
  });
  
 
 // Se actualiza el calendario de festivos
  $(document).on('click',"#actualizar_calendario",function(event){
    event.preventDefault();
    // Retardo para ejecutarlo una vez cargado el datepicker.
        $("#d_calendario #div_leyenda").addClass("oculto");      

    setTimeout(function(){ 
      mes=$("#d_calendario  #contenedor_calendar tbody td[data-handler='selectDay']").attr("data-month");
      año=$("#d_calendario  #contenedor_calendar tbody td[data-handler='selectDay']").attr("data-year");
      mes++;
      if(mes!=10 && mes!=11 && mes!=12){
        mes='0'+mes;
      }
      //Se carga los festivos en la leyenda.
      $("#d_calendario #div_leyenda").empty();
      $("#d_calendario #div_leyenda").load(Routing.generate('festivos_por_mes', {id:mes}));
      $("#calendario td a").removeClass("festivo");
      $("#calendario td a").removeClass("vacaciones");

      $.ajax({
        type: 'POST',
        url: Routing.generate('dias_festivos'),
        data: {mes:mes},
        dataType: 'json',
        success: function(response) {
          for (var key in response.data) { 
            $("#calendario .ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a:contains('"+response.data[key]["dia"]+"')").each(function(){  
              var dato=response.data[key]["dia"];
              var comp= $(this).text();
              // Con :contains se obtiene los días del calendario que contiene en sus dígitos el dato dado.
              // Si el dato obtenido tiene un solo dígito, se excluye los días de dos dígitos del calendario que contiene ese dato.
              if(String(dato).length=="1"){
                if(String(comp).length=="1"){
                  $(this).addClass("festivo");
                  // Se comprueba que el día festivo es un domingo y que el lunes no hay ningún festivo añadido. 
                  if($(this).closest("td").hasClass(" ui-datepicker-week-end ") &&  $(this).closest("tr").find("td:last a").text() == $(this).text() && !$("#d_calendario #div_leyenda h4[id='"+$(this).closest("tr").next("tr").find("td:nth-child(1) a").text()+"']").length){
                    // Mostramos el traspado del festivo al lunes.
                    $(this).closest("tr").next("tr").find("td:nth-child(1) a").addClass("festivo");
                    $(this).closest("tr").next("tr").find("td:nth-child(1) a").attr("tipo","traslado");
                    setTimeout(function(){
                      //Se muestra solo un traslado del festivo (ya que hay dos elementos con el mismo día).
                      $("#d_calendario #div_leyenda h4[id='"+dato+"']").each(function(){
                        if($(this).next().text().indexOf(" Vacaciones ")<0){
                          $( "<h4 id='"+(dato+1)+"'>"+(dato+1)+"</h4><h4 id='h4_descripcion'>Traslado del Festivo del día "+dato+"</h4>" ).insertAfter( $(this).next());
                        }
                      });
                    }, 50);
                  }
                }
              }
              else{
                $(this).addClass("festivo");
                // Se comprueba que el día festivo es un domingo, que el lunes no hay ningún festivo añadido y que el festivo no coincida con el último día del mes. 
                if($(this).closest("td").hasClass(" ui-datepicker-week-end ") &&  $(this).closest("tr").find("td:last a").text() == $(this).text() && !$("#d_calendario #div_leyenda h4[id='"+$(this).closest("tr").next("tr").find("td:nth-child(1) a").text()+"']").length && $("#calendario tbody tr:last td:last a").text()!=$(this).text() ){
                  // Mostramos el traspado del festivo al lunes.
                  $(this).closest("tr").next("tr").find("td:nth-child(1) a").addClass("festivo");
                  $(this).closest("tr").next("tr").find("td:nth-child(1) a").attr("tipo","traslado");
                  setTimeout(function(){
                    //Se muestra solo un traslado del festivo (ya que hay dos elementos con el mismo día).
                    $("#d_calendario #div_leyenda h4[id='"+dato+"']").each(function(){
                      if($(this).next().text().indexOf(" Vacaciones ")<0){
                        $( "<h4 id='"+(dato+1)+"'>"+(dato+1)+"</h4><h4 id='h4_descripcion'>Traslado del Festivo del día "+dato+"</h4>" ).insertAfter( $(this).next());
                      }
                    });
                  }, 50);
                }
              }
            });
          }
          // Se comprueba de que existe un día de comienzo/fin de vacaciones.
          if(response.inicio_vacaciones && response.fin_vacaciones){
            var descripcion_inicio=$("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'").next().text().split(" ");
            var descripcion_fin=$("#d_calendario #div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'").next().text().split(" ");
            // Se comprueba que existe ambos días de vacaciones en el mismo mes y que pertenecen al mismo tipo de vacaciones.
            if(descripcion_inicio[3]==descripcion_fin[3]){
              // Se retarda para modificar los días festivos dentro de las vacaciones.
              setTimeout(function(){
                $("#calendario .ui-datepicker-calendar td[data-month='"+(mes-1)+"']").find("a").filter(function(){return $(this).text()==response.inicio_vacaciones["dia"];}).removeClass("festivo");
                $("#calendario .ui-datepicker-calendar td[data-month='"+(mes-1)+"']").find("a").filter(function(){return $(this).text()==response.fin_vacaciones["dia"];}).removeClass("festivo");
                // Se comprueba si existe al inicio de vacaciones día siguiente con festivo de traslado para eliminarlo.
                if($("#d_calendario #div_leyenda h4[id='"+(response.inicio_vacaciones["dia"]+1)+"'").next().text().indexOf("Traslado") >= 0){
                    $("#d_calendario #div_leyenda h4[id='"+(response.inicio_vacaciones["dia"]+1)+"'").next().remove();
                    $("#d_calendario #div_leyenda h4[id='"+(response.inicio_vacaciones["dia"]+1)+"'").remove();
                    $("#calendario .ui-datepicker-calendar td[data-month='"+(mes-1)+"']").find("a").filter(function(){return $(this).text()==(response.inicio_vacaciones["dia"]+1);}).removeClass("festivo");                
                }
                // Se añade la clase vacaciones a los días correspondientes.
                for(var i = response.inicio_vacaciones["dia"]; i <= response.fin_vacaciones["dia"]; i++){
                  $("#calendario .ui-datepicker-calendar td[data-month='"+(mes-1)+"']").find("a").filter(function(){return $(this).text()==i;}).addClass("vacaciones");
                  //$("#d_calendario #div_leyenda h4[id='"+i+"'").addClass("vacaciones");              
                }
                $("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'").addClass("vacaciones");              
                $("#d_calendario #div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'").addClass("vacaciones");              

                // Si se repite el día de inicio de vacaciones, se elimina la clase vacaciones en los días de la leyenda que no contiene la información de las vacaciones.
                if($("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").length>1){
                  $("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").each(function(){
                    if($(this).next().text().indexOf(" Vacaciones ")<0){
                      $(this).removeClass("vacaciones");
                    }
                  });
                }
                // Si se repite el día de fin de vacaciones, se elimina la clase vacaciones en los días de la leyenda que no contiene la información de las vacaciones.
                if($("#d_calendario #div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").length>1){
                  $("#d_calendario #div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").each(function(){
                    if($(this).next().text().indexOf(" Vacaciones ")<0){
                      $(this).removeClass("vacaciones");
                    }               
                    // Se elimina el día de final de vacaciones en la leyenda para unificarlo con el día de inicio.
                    //else if($(this).next().text().indexOf(" Vacaciones ")>=0){
                     // $(this).next().remove();
                     // $(this).remove();
                   // }
                  });

                  // Se modifica el inicio de vacaciones en la leyenda para añadir el día de fin de vacaciones.
                  //$("#d_calendario #div_leyenda h4[class='vacaciones']").text(response.inicio_vacaciones["dia"]+"-"+response.fin_vacaciones["dia"]);
                  //$("#d_calendario #div_leyenda h4[class='vacaciones']").next().text($("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").next().text().replace("Inicio ",""));
                }
            // Se modifica el inicio de vacaciones en la leyenda para añadir el día de fin de vacaciones.
                  //$("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").text(response.inicio_vacaciones["dia"]+"-"+response.fin_vacaciones["dia"]);
                  //$("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").next().text($("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").next().text().replace("Inicio Vacaciones de ",""));
                  //$("#d_calendario #div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").next().remove();
                  //$("#d_calendario #div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").remove();
              }, 20);
            }
          }
          else if(response.inicio_vacaciones){

            var date = new Date(año,mes,1);
            var fecha_ultimoDia = new Date(date.getFullYear(), date.getMonth() , 0).toString().split(" ");
            último_día=fecha_ultimoDia[2];
            
            setTimeout(function(){
            // Si se repite el día de inicio de vacaciones, se asigna la clase vacaciones al día de inicio de las vacaciones.
            if($("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").length>1){
              $("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").each(function(){
                if($(this).next().text().indexOf(" Vacaciones ")>0){
                  $(this).addClass("vacaciones")
                }
              });
              // Se comprueba que el festivo está tras las vacaciones en la leyenda, en caso contrario se modifica.
              if($("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").closest("h4[id='"+response.inicio_vacaciones["dia"]+"']").text()==response.inicio_vacaciones["dia"]){
                vacaciones=$("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").next().text();
                $("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").next().remove();
                $("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").remove();

                $( "<h4 id='"+response.inicio_vacaciones["dia"]+"'class='vacaciones'>"+response.inicio_vacaciones["dia"]+"</h4><h4 id='h4_descripcion'>"+vacaciones+"</h4>" ).insertBefore( $("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class!='vacaciones']")); 
              }
            }
            else{
              $("#d_calendario #div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").addClass("vacaciones");
            }

            // Se modifica los días desde inicio de vacaciones hasta final de mes puesto que sigue en el siguiente mes.
            for(var i = response.inicio_vacaciones["dia"]; i <= último_día; i++){
              $("#calendario .ui-datepicker-calendar td[data-month='"+(mes-1)+"']").find("a").filter(function(){return $(this).text()==i;}).addClass("vacaciones");
            }

            }, 50);
          }
          else if(response.fin_vacaciones){

            setTimeout(function(){

            //Se añade la clase vacaciones, solo a los elementos de ese día que contiene la información de las vacaciones.
            if($("#d_calendario #div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").length>1){
              $("#d_calendario #div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").each(function(){
                if($(this).next().text().indexOf(" Vacaciones ")>0){
                  $(this).addClass("vacaciones");
                }
              });
              // Se comprueba que el festivo está tras las vacaciones en la leyenda, en caso contrario se modifica.
              if($("#d_calendario #div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class='vacaciones']").closest("h4[id='"+response.fin_vacaciones["dia"]+"']").text()==response.fin_vacaciones["dia"]){
                vacaciones=$("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class='vacaciones']").next().text();
                $("#d_calendario #div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class='vacaciones']").next().remove();
                $("#d_calendario #div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class='vacaciones']").remove();

                $( "<h4 id='"+response.fin_vacaciones["dia"]+"'class='vacaciones'>"+response.fin_vacaciones["dia"]+"</h4><h4 id='h4_descripcion'>"+vacaciones+"</h4>" ).insertBefore( $("#d_calendario #div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class!='vacaciones']")); 
              }
            }
            else{
              $("#d_calendario #div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").addClass("vacaciones");
            }

            // Se modifica los días desde inicio del mes hasta final de vacaciones puesto que sigue en el mes anterior.
            for(var i = response.fin_vacaciones["dia"]; i >= 1; i--){
              $("#calendario .ui-datepicker-calendar td[data-month='"+(mes-1)+"']").find("a").filter(function(){return $(this).text()==i;}).addClass("vacaciones");
            }

            }, 50);
          }
        }
      })

      // Se Comprueba si el último día del mes anterior es un domingo y es festivo, para traspasar el festivo al primer día del mes actual.
      dia_1=$("#calendario tbody tr:first td:first a");
      if( dia_1.text() == "1" ){
        // Se obtiene un string con los datos el últimodía del mes anterior (Ej: Sun Jan 31 2016) .
        fecha=new Date(dia_1.closest("div").find("div>span:nth-child(2) ").text(), "0"+$("#calendario tbody tr:first td:last").attr("data-month"), 0).toDateString();
        fecha=fecha.split(" ");
        dia_ant=fecha[2];
        // Asignamos a una variable el mes anterior que correcponda. En datepicker aparece el número del mes-1.
        if($("#calendario tbody tr:first td:last").attr("data-month")=="0"){
          mes_ant="12";
        }
        else if($("#calendario tbody tr:first td:last").attr("data-month").length=="1"){
          mes_ant=0+$("#calendario tbody tr:first td:last").attr("data-month");
        }
        else{
          mes_ant=$("#calendario tbody tr:first td:last").attr("data-month");
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
              dia_1.addClass("festivo");
              setTimeout(function(){
                $("#d_calendario #div_leyenda").prepend("<h4 id='"+dia_1.text()+"'>"+dia_1.text()+"</h4><h4 id='h4_descripcion'>Traslado del Festivo del día "+dia_ant+" de "+MES[$("#calendario tbody tr:first td:last").attr("data-month")-1]+" </h4>" );
              }, 20);
            }
          }
        })
      }
      setTimeout(function(){
        // Se muestra la leyenda una vez actualizado el calendario. 
        $("#d_calendario #div_leyenda").removeClass("oculto");
      }, 150);        
    }, 150);
  });

  // Insertar festivos en calendario.
  $(document).on('click',"#calendario a[class='festivo']",function(event){
    var tipo= $(this).attr('id').split('_');
    
    $('#festivos_dialog').load(Routing.generate("festivos_new"), function(){
      $('#festivos_dialog form').attr("tipo",tipo[1].charAt(0).toUpperCase() + tipo[1].slice(1) );
      $("#festivos_tipo").val($("#festivos_nueva").attr("tipo"));
    }).dialog('open'); 
  });


  // Vistas de Festivos.
  $(document).on('click',"#vistas_festivos input",function(event){
    var option= $(this).attr('id').split('_');
    $("#contenedor_calendario>div").attr("style","display: none;");
    $("#contenedor_calendario>div[id*='"+option[1]+"']").attr("style","display: block;");
  });

  // Insertar festivos en listas.
  $(document).on('click',"#asignacion_festivos a[id$='_modal']",function(event){
    event.preventDefault();
    var tipo= $(this).attr('id').split('_');
    
    $('#festivos_dialog').load(Routing.generate("festivos_new"), function(){
      $('#festivos_dialog form').attr("tipo",tipo[1].charAt(0).toUpperCase() + tipo[1].slice(1) );
      $("#festivos_tipo").val($("#festivos_nueva").attr("tipo"));
    }).dialog('open'); 
  });

  $(document).on('change',"#dia_festivo select",function(event){
    $("#mes_festivo select option").prop("disabled", false);    
    if($(this).val()==31){
      $("#mes_festivo select option[value='04']").prop("disabled", true);  
      $("#mes_festivo select option[value='06']").prop("disabled", true);  
      $("#mes_festivo select option[value='09']").prop("disabled", true);  
      $("#mes_festivo select option[value='11']").prop("disabled", true);  
      $("#mes_festivo select option[value='02']").prop("disabled", true);  
    }
    if($(this).val()==30){
      $("#mes_festivo select option[value='02']").prop("disabled", true);  
    }
    if($(this).val()==29){
      var ano = (new Date).getFullYear();
        if ($(this).closest("form").attr("tipo")!="Curso" || !((ano % 4 == 0) && ((ano % 100 != 0) || (ano % 400 == 0)))){
          $("#mes_festivo select option[value='02']").prop("disabled", true);  
        }
    }
    if($("#mes_festivo select").val()==null){
      $("#mes_festivo select option[value=' ']").prop('selected',true);
    }
    $("#festivos_dia").val($("#dia_festivo select").val());
  });

  $(document).on('change',"#mes_festivo select",function(event){
    $("#dia_festivo select option").prop("disabled", false);  

    if($(this).val()==04 || $(this).val()==06 || $(this).val()==09 || $(this).val()==11){
      $("#dia_festivo select option[value='31']").prop("disabled", true);  
    }
    if($(this).val()==02){
      $("#dia_festivo select option[value='31']").prop("disabled", true);
      $("#dia_festivo select option[value='30']").prop("disabled", true);  
      var ano = (new Date).getFullYear();
      if ($(this).closest("form").attr("tipo")!="Curso" || !((ano % 4 == 0) && ((ano % 100 != 0) || (ano % 400 == 0)))){
        $("#dia_festivo select option[value='29']").prop("disabled", true);  
      }
    }
    if($("#dia_festivo select").val()==null){
      $("#dia_festivo select option[value=' ']").prop('selected',true);
    }
    $("#festivos_mes").val($("#mes_festivo select option:selected").text());
    $("#festivos_numMes").val($("#mes_festivo select").val());
  });

   $(document).on('change',"#tipo_festivo select",function(event){
    $("#festivos_tipo").val($("#tipo_festivo select").val());
  });

  $(document).on('change',"form[id^='festivos_'] :input",function(event){
    form= $(this).closest("form");

    if($(this).val()=="")
    {
      $(this).addClass("invalid");
      form.find("span").removeClass("oculto");
    }
    else{
      $(this).removeClass("invalid");

      if (!form.find(":input").hasClass('invalid')){
        form.find("span").addClass("oculto");
      }
    }
  });

  $(document).on("submit","#festivos_nueva",function(event){
    event.preventDefault();
    form= $(this).closest("form");
    var change=0;

    $("#festivos_nueva :input").removeClass("invalid");

      if($("#festivos_tipo").val()==""){
      $("#tipo_festivo select").addClass("invalid");
      $("#festivos_nueva span").removeClass("oculto");
      change=1;
    }
    if($("#festivos_dia").val()==""){
      $("#dia_festivo select").addClass("invalid");
      $("#festivos_nueva span").removeClass("oculto");
      change=1;
    }
    if($("#festivos_mes").val()==""){
      $("#mes_festivo select").addClass("invalid");
      $("#festivos_nueva span").removeClass("oculto");
      change=1;
    }
    if($("#festivos_descripcion").val()==""){
      $("#festivos_descripcion").addClass("invalid");
      $("#festivos_nueva span").removeClass("oculto");

      change=1;
    }
    if(change==1){
      return 0;
    }
    else{
      $("#festivos_nueva span").addClass("oculto");
    }

    var dia= $("#dia_festivo select").val();
    var mes= $("#mes_festivo select").val();
    var descripcion="";

    $.ajax({
      type: 'POST',
      url: Routing.generate('comprobar_festivo'),
      data: {dia:dia, mes:mes ,descripcion:descripcion},
      dataType: 'json',
      success: function(response) {
        if(response.data!=null)
        {
          error.play();
          swal({
            title: "Día festivo ya asignado",
            text: 'El día y mes introducido ya tiene asignado un festivo en el sistema.',
            type: "error",
            confirmButtonColor: color
          });
          return false;
        }
        else{
          $.ajax({
            type: 'POST',
            url: $("#festivos_nueva").attr('action'),
            data:$("#festivos_nueva").serialize(), 
            dataType: 'json',
  
            success: function(response) {
              if($("#festivos_nueva").attr("tipo")!=""){
                tipo=$("#festivos_tipo").val();
                $("#festivos_dialog").dialog('close');
                //Se actualiza la lista de festivos.
                div=$("#asignacion_festivos div[id$='"+tipo.toLowerCase()+"'] .Festivos_list");
                $(div).empty();
                $(div).load(Routing.generate('festivos_por_tipo', {id:tipo}));
              }
              else{
                $("#festivos_dialog").dialog('close');
                //Se actualiza la lista de festivos.
                div=$("#asignacion_festivos div[style='display: block;'] .Festivos_list");
                $(div).empty();
                var tipo= form.attr("tipo");
                $(div).load(Routing.generate('festivos_por_tipo', {id:tipo}));

              }
              limpiarForm(form);

              //Actualización del contenido de pestañas.
              $("#reservar_instalaciones").update_tab();
              $("#reservar_equipamientos").update_tab();
              $("#registrar_noticias").update_tab();
              $("#registrar_eventos").update_tab();

              $("#actualizar_calendario").trigger("click");
            }
          })
          return false;
        } 
      } 
    })
  });


  $(document).on('click',"#asignacion_festivos a[href$='edit']",function(event){
    event.preventDefault();
    var arr= $(this).attr('href').split('/');
    $('#festivos_dialog').load(Routing.generate(arr[4]+"_edit", {id:arr[5]}), function(){
    }).dialog('open'); 
  });

  $(document).on("submit","#festivos_edit",function(event){
    event.preventDefault();
    form= $(this).closest("form");
        var change=0;
    $("#festivos_nueva :input").removeClass("invalid");

    if($("#dia_festivo select option:selected").val()==" "){
      $("#dia_festivo select").addClass("invalid");
      $("#festivos_edit span").removeClass("oculto");
      change=1;
    }
    if($("#mes_festivo select option:selected").val()==" "){
      $("#mes_festivo select").addClass("invalid");
      $("#festivos_edit span").removeClass("oculto");
      change=1;
    }
    if($("#festivos_descripcion").val()==""){
      $("#festivos_descripcion").addClass("invalid");
      $("#festivos_edit span").removeClass("oculto");

      change=1;
    }
    if(change==1){
      return 0;
    }
    else{
      $("#festivos_edit span").addClass("oculto");
    }
    var dia= $("#dia_festivo select").val();
    var mes= $("#mes_festivo select").val();
    var descripcion=$("#festivos_descripcion").val();
  
    $.ajax({
      type: 'PUT',
      url: $("#festivos_edit").attr('action'),
      data:$("#festivos_edit").serialize(), 
  
      success: function() {
        var tipo= $("#festivos_tipo").attr("value");
        if($("#festivos_edit").attr("tipo")!=""){
          tipo2=$("#festivos_tipo").val();
          $("#festivos_dialog").dialog('close'); 
                
          //Se actualizan las listas modificadas de los festivos.
          div=$("#asignacion_festivos div[id$='"+tipo.toLowerCase()+"'] .Festivos_list");
          $(div).empty();
          $(div).load(Routing.generate('festivos_por_tipo', {id:tipo}));

          div2=$("#asignacion_festivos div[id$='"+tipo2.toLowerCase()+"'] .Festivos_list");
          $(div2).empty();
          $(div2).load(Routing.generate('festivos_por_tipo', {id:tipo2}));
        }
          else{
          $("#festivos_dialog").dialog('close'); 

          //Se actualiza las lista de festivos.
          div=$("#asignacion_festivos div[style='display: block;'] .Festivos_list");
          $(div).empty();
          $(div).load(Routing.generate('festivos_por_tipo', {id:tipo}));
        }

        //Actualización del contenido de pestañas.
        $("#reservar_instalaciones").update_tab();
        $("#reservar_equipamientos").update_tab();
        $("#registrar_noticias").update_tab();
        $("#registrar_eventos").update_tab();

        $("#actualizar_calendario").trigger("click");
      }
    })
    return false;
  });

  //Eliminación de festivos.
  $(document).on("click","#festivos_delete button",function(event){
    event.preventDefault();
    form= $(this).closest("form");
    var arr= $('#festivos_delete').attr('action').split('/');
    
    aviso.play();
    swal({
      title: "Eliminación del día festivo del sistema.",
      text: "¿Estas seguro de continuar? No podrás deshacer este paso...",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        $.ajax({
          type: 'DELETE',
          url: Routing.generate(arr[4]+"_delete", {id:arr[5]}),
          data: $('#festivos_delete').serialize(),
        
          success: function() {
            tipo=$("#festivos_tipo").val();

            if($("#festivos_nueva").attr("tipo")!=""){
              $("#festivos_dialog").dialog('close');
              div=$("#asignacion_festivos div[id$='"+tipo.toLowerCase()+"'] .Festivos_list");
              $(div).empty();
              $(div).load(Routing.generate('festivos_por_tipo', {id:tipo}));
            }
            else{
              $("#festivos_dialog").dialog('close');
              div=$("#asignacion_festivos div[style='display: block;'] .Festivos_list");
              $(div).empty();
              $(div).load(Routing.generate('festivos_por_tipo', {id:tipo}));
            }

            //Actualización del contenido de pestañas.
            $("#reservar_instalaciones").update_tab();
            $("#reservar_equipamientos").update_tab();
            $("#registrar_noticias").update_tab();
            $("#registrar_eventos").update_tab();

            $("#actualizar_calendario").trigger("click");
          }
        })
      }, function (dismiss) {

      }
    );
    return false;
  });


  $(document).on('change',"#contenedor_festivos input[type!='radio']",function(event){
    $(this).removeClass("invalid");
    $(this).removeClass("modified");
 
    if($(this).val().toString()==$(this).attr("value")+" " || $(this).val().toString()==$(this).attr("value")){
      $(this).removeClass("modified");
      return false;
    }

    var dato=$(this).attr("id").split("_");

    if($(this).val()==" "){
      $(this).removeClass("modified");
      if($("#inicio_"+dato[1]).val()==" " && $("#fin_"+dato[1]).val()==" "){
        $("#inicio_"+dato[1]).removeClass("invalid");
        $("#fin_"+dato[1]).removeClass("invalid");
      }
    }
    else{
      $(this).addClass("modified");
     /* if((!$("#inicio_"+dato[1]).val()=="" && !$("#inicio_"+dato[1]).val()==" " && !$("#fin_"+dato[1]).val()=="" && !$("#fin_"+dato[1]).val()==" ") && ($("#inicio_"+dato[1]).val() < $("#fin_"+dato[1]).val())){
        $("#inicio_"+dato[1]).addClass("invalid");
        $("#fin_"+dato[1]).addClass("invalid");

      }*/
    }
  });

  $(document).on('keyup',"#contenedor_festivos input[type!='radio']",function(e){
    e.preventDefault();

    var festivo= $(this).attr("id").split("_"); 
    //Se elimina el valor de los inputs al presionar una tecla diferente al tabulador y Enter.
    if(e.keyCode == 9 || e.keyCode == 13)
    {
      if(festivo[0]=="inicio"){
        $("#fin_"+festivo[1]).focus();
      }
      else{
        $("#inicio_"+festivo[1]).focus();
      }
    }      
    else{
      $(this).val("");
      dia=$("#ui-datepicker-div").find("a[class*='ui-state-active']");
      dia.removeClass("ui-state-active ui-state-hover");
    }
  });

  $(document).on('click',"#button_festivos_all",function(event){
    //Retardo por si se da el caso de modificar solo un input de vacaciones y le damos a guardar sin antes actualizarse el otro input.
   setTimeout(function(){
    if($("#contenedor_festivos input[type!='radio'][class*='modified']").length==0){
      return false;
    }

    $("#contenedor_festivos input[type!='radio'][class*='modified']").each(function(){  
      id=$(this).attr("id");

      if($(this).val()){
        fecha=$(this).val().split(" ");
        dia=fecha[0];
        mes=fecha[1];
        if(mes=="Diciembre"){
          año=$("#año_inicio").text();
        }
        else{
          año=$("#año_fin").text();
        }

        $.ajax({
          type: 'POST',
          url: Routing.generate('registrar_dia_vacaciones'),
          data: {id:id, dia:dia, mes:mes, año:año},
          dataType: 'json',
          success: function(response) {
            // Se actualiza la pestaña de periodo lectivo y reservas de equipamientos.
            $("#periodo_lectivo").update_tab();
            $("#reservar_instalaciones").update_tab();
            $("#reservar_equipamientos").update_tab();
          }
        })
      }
      else{
        $.ajax({
          type: 'POST',
          url: Routing.generate('eliminar_dia_vacaciones'),
          data: {id:id},
          dataType: 'json',
          success: function(response) {
            // Se actualiza la pestaña de periodo lectivo.
            $("#periodo_lectivo").update_tab();
            $("#reservar_instalaciones").close_tab();
            $("#reservar_equipamientos").close_tab();
          }
        })
      }
    });
    
    var texto="";

    if($("#asignacion_festivos #inicio_navidad").hasClass('modified')||$("#asignacion_festivos #fin_navidad").hasClass('modified')){
      if($("#asignacion_festivos #inicio_navidad").attr("value")==""){
        texto+="<span>- Vacaciones de Navidad <strong>registrada</strong><span>";
      }
      else{
        if($("#asignacion_festivos #inicio_navidad").val()!=""){
          texto+="<span>- Vacaciones de Navidad <strong>actualizada</strong><span>";
        }
        else{
          texto+="<span>- Vacaciones de Navidad <strong>eliminada</strong><span>";
        }
      }
    }

    if($("#asignacion_festivos #inicio_semanasanta").hasClass('modified')||$("#asignacion_festivos #fin_semanasanta").hasClass('modified')){
      if($("#asignacion_festivos #inicio_semanasanta").attr("value")==""){
        texto+="<span>- Vacaciones de Semana Santa <strong>registrada</strong><span>";
      }
      else{
        if($("#asignacion_festivos #inicio_semanasanta").val()!=""){
          texto+="<span>- Vacaciones de Semana Santa <strong>actualizada</strong><span>";
        }
        else{
          texto+="<span>- Vacaciones de Semana Santa <strong>eliminada</strong><span>";
        }
      }
    }
    // Se establece el efecto para la notificación de error en el caso de que se de varias veces seguidas a guardar con algunas opción sin marcar.
    $(".ui-pnotify").remove();

    exito.play();
    new PNotify({
      title:texto,
      addclass: "custom",
      type: "success",
      shadow: true,
      hide: true,
      width: "420px",
      buttons: {
        sticker: false,
        labels:{close: "Cerrar"}
      },
      stack: right_Stack,
      animate: {
        animate: true,
        in_class: "fadeInRight",
        out_class: "fadeOutRight",
      }
    });
    return false;

   },200);
  });

  // Se actualiza la pestaña de periodo lectivo.
  $(document).on('click',"#button_festivos_rest",function(event){
    $("#contenedor_festivos input[type!='radio'][class*='modified']").each(function(){  
      $(this).val($(this).attr("value"));
      $(this).removeClass("modified");
    });
  });

  $(document).on('click',"#button_fecha_curso_rest",function(event){
    $("#fin_curso_disable").trigger("click");
  });

  $(document).on('click',"#button_fecha_curso",function(event){
    $("#curso_dialog  #aviso_error").hide();

    $("#curso_dialog input").each(function(){
      $(this).removeClass("error_guardar");

      if($(this).val()==" " || $(this).val()==""){
        $(this).addClass("error_guardar");
      }  
    });
    if($("#curso_dialog input").hasClass("error_guardar")){
      $("#curso_dialog  #aviso_error").show();
    }
    else{
      $("#curso_dialog  #aviso_error").hide();
      var inicio=$("#fecha_inicio").val().split(" ");
      var fin=$("#fecha_fin").val().split(" ");
      var curso=$("#curso_dialog legend").text().split(" ");
      var MES = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

      mes_ini = jQuery.inArray( inicio[1], MES );
      mes_fin = jQuery.inArray( fin[1], MES );

      ini = new Date(curso[1], mes_ini, inicio[0]);
      fin = new Date(curso[3], mes_fin, fin[0]);

      fecha_ini = ini.toLocaleString();
      fecha_fin = fin.toLocaleString();

      $.ajax({
        type: 'POST',
        url: Routing.generate('registrar_fecha_curso'),
        data: {fecha_ini:fecha_ini.substring(0,9), fecha_fin:fecha_fin.substring(0,9)},
        dataType: 'json',
        success: function(response) {
          exito.play();
          new PNotify({
            text:"Se ha registrado la fecha de inicio y final del curso.",
            addclass: "custom",
            type: "success",
            shadow: true,
            hide: true,
            buttons: {
            sticker: false,
            labels:{close: "Cerrar"}
            },
            stack: right_Stack,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
          });
          // Se añade un gif para la espera de la carga del contenido actualizado.
          $('#asignacion_festivos').html('<div class="ajaxload"><img src="/Symfony/web/bundles/backend/images/loading.gif"/></div>');
          // Se actualiza la pestaña de periodo lectivo.
          $("#curso_dialog div[class='nuevo']").removeClass("nuevo");
          $("#periodo_lectivo").update_tab();
          $("#curso_dialog").dialog('close');
        }
      })
    }
  });

  $(document).on('focus',"#registro_fecha_curso input",function(event){
    setTimeout(function(){ 
        $("#registro_fecha_curso").next().addClass('oculto');
    },100);

  });

   $(document).on('blur',"#registro_fecha_curso input",function(event){

  $("#registro_fecha_curso").next().removeClass('oculto');
  });



  $(document).on('change',"#registro_fecha_curso input",function(event){

    if($(this).val()!=" " && $(this).val()!=""){
      $(this).removeClass("error_guardar");
    }
    else{
      //Se oculta el día anterior seleccionado si el input está vacío.
      if($(this).attr("id")=="fecha_inicio"){
        dia=$("#inicio_curso").find("a[class*='ui-state-active']");
        dia.removeClass("ui-state-active ui-state-hover");
      }
      else{
        dia=$("#fin_curso").find("a[class*='ui-state-active']");
        dia.removeClass("ui-state-active ui-state-hover");
      }
    }

    if(!$("#curso_dialog input").hasClass("error_guardar")){
      $("#curso_dialog  #aviso_error").hide();
    }
  });

  $(document).on("click","button[title='Close']",function(event){
    event.preventDefault()
    if($(this).closest("div").next().attr("id")=="curso_dialog" && $(this).closest("div").next().children('div').attr("id")=="nuevo"){
      $("#tabs ul li[aria-selected='true'] span").trigger("click");
    }
  }); 

  $(document).on('blur','#fecha_inicio' ,function() {
    $(this).removeClass("error_guardar");

    if($(this).val()==" " || $(this).val()==""){
      $(this).addClass("error_guardar");
    }  

  });

  $(document).on('blur','#fecha_fin' ,function() {
    $(this).removeClass("error_guardar");

    if($(this).val()==" " || $(this).val()==""){
      $(this).addClass("error_guardar");
    }  
  });
  //Se valida que estan registradas las fechas de vacaciones cuando se genera el PDF del calendario del curso.
  $(document).on("click","#asignacion_festivos #calendario_completo",function(event){
      // Se establece el efecto para la notificación de error en el caso de que se de varias veces seguidas a guardar con algunas opción sin marcar.
      $(".ui-pnotify").remove();
      
      if(($("#asignacion_festivos #inicio_navidad").val()=="" && !$("#asignacion_festivos #inicio_navidad").hasClass('modified')) || ($("#asignacion_festivos #inicio_semanasanta").val()=="" && !$("#asignacion_festivos #inicio_semanasanta").hasClass('modified'))){
        var texto="";
        if(($("#asignacion_festivos #inicio_navidad").val()=="" && !$("#asignacion_festivos #inicio_navidad").hasClass('modified')) && ($("#asignacion_festivos #inicio_semanasanta").val()=="" && !$("#asignacion_festivos #inicio_semanasanta").hasClass('modified'))){
          texto+="<span>Vacaciones de Navidad<span><span>Vacaciones de Semana Santa<span>";
        }
        else if($("#asignacion_festivos #inicio_navidad").val()=="" && !$("#asignacion_festivos #inicio_navidad").hasClass('modified')){
          texto+="<strong>Vacaciones de Navidad<strong>";
        }
        else{
          texto+="<span>Vacaciones de Semana Santa<span>";
        }
        errorPNotify.play();
        new PNotify({
          title: "Debe seleccionar las siguientes fechas para continuar:",
          text:texto,
          addclass: "custom",
          type: "error",
          shadow: true,
          hide: true,
          width: "335px",
          buttons: {
            sticker: false,
            labels:{close: "Cerrar"}
          },
          stack: left_Stack,
          animate_speed: "fast",
          animate: {
              animate: true,
              in_class: "fadeInLeft",
              out_class: "fadeOutLeft",
          }
        });
        return false;
      }
  }); 

  //////////////////////////////////
  //        Horario Clase         //
  //////////////////////////////////
  //Se cambia de forma genérica en el contenedor de opciones.
  $(document).on("click","#one",function(event){
    div=$(this).closest(".div_change");
    div.find("#cont_one").removeClass('oculto');
    div.find("#cont_two").addClass('oculto');
    div.find("#one").removeClass('btn_not_select');
    div.find("#two").addClass('btn_not_select');
    div.closest(".block_insert").find("#button_one").removeClass('oculto');
    div.closest(".block_insert").find("#button_two").addClass('oculto');

  });
  $(document).on("click","#two",function(event){
    div=$(this).closest(".div_change");
    div.find("#cont_one").addClass('oculto');
    div.find("#cont_two").removeClass('oculto');
    div.find("#one").addClass('btn_not_select');
    div.find("#two").removeClass('btn_not_select');
    div.closest(".block_insert").find("#button_one").addClass('oculto');
    div.closest(".block_insert").find("#button_two").removeClass('oculto');
  });

  $(document).on("click","#registro_horario #button_generate",function(event){
    //Se añade el tipo de horario al botón para luego guardarlo en la base de datos.
    $("#registro_horario #button_horario_save").attr("tipo","manual");

    // Se vacia el contenedor para el nuevo horario.
    $("#contenedor_nuevo_horario tbody").empty();

    // Se oculta el aviso de error por si estaba mostrado.
    $("#registro_horario #aviso_error").addClass("oculto");

    // Se añade las filas con cada hora de clase con los horarios en el nuevo contenedor.
    for (var i = 1; i <= $("#total_horas").val(); i++) {
      // Se añade el horario de recreo.
      if(parseInt($("#horas_recreo").val())+1 == i){
        cont='<tr><td><span class="oculto">*</span>';
        cont+='</td>';
        cont+='<td>RECREO';
        cont+='</td>';
        cont+='<td><input type="time" step="1"> </input>';
        cont+='</td>';
        cont+='<td><input type="time" step="1"> </input>';
        cont+='</td>';
        cont+='</tr>';
        $("#contenedor_nuevo_horario tbody:last-child").append(cont);
      }
      // Se añade el horario de comida, cuando esté activa la opción.
      if(parseInt($("#total_horas").val())-parseInt($("#horas_comida").val())+1 == i && !$("#registro_horario #no").is(':checked') ){
        cont='<tr><td><span class="oculto">*</span>';
        cont+='</td>';
        cont+='<td>COMIDA';
        cont+='</td>';
        cont+='<td><input type="time" step="1"> </input>';
        cont+='</td>';
        cont+='<td><input type="time" step="1"> </input>';
        cont+='</td>';
        cont+='</tr>';
        $("#contenedor_nuevo_horario tbody:last-child").append(cont);
      }
      contenido='<tr><td><span class="oculto">*</span>';
      contenido+='</td>';
      contenido+='<td>'+i+'º';
      contenido+='</td>';
      contenido+='<td><input type="time" step="1"> </input>';
      contenido+='</td>';
      contenido+='<td><input type="time" step="1"> </input>';
      contenido+='</td>';
      contenido+='</tr>';
      $("#contenedor_nuevo_horario tbody:last-child").append(contenido);
    }
    // Máscara para los input time.
    $("input[type='time']").mask('AB:CB',
    {'translation': {A: {pattern: /[0-1]/}, B: {pattern: /[0-9]/}, C: {pattern: /[0-5]/}},
     placeholder: "__:__"});

    $("#contenedor_nuevo_horario").find(":input").each(function(i){
      $(this).attr("tabindex",i+1);
    });
    
    //Se deshabilita todos los input menos los de la primera hora.
    $("#contenedor_nuevo_horario").find("input").each(function(i){
      $(this).prop("disabled", true);
    });
    $("#contenedor_nuevo_horario input:first").prop("disabled", false);
    $("#contenedor_nuevo_horario input:first").closest("td").next("td").find("input").prop("disabled", false);
    $("#button_horario_save").prop("disabled", true);

    // Se muestra el contenedor para el nuevo horario.
    $("#contenedor_registro_horario").addClass("oculto");
    $("#contenedor_nuevo_horario").removeClass("oculto");

    $("#contenedor_nuevo_horario input:first").focus();
    $("#contenedor_nuevo_horario input:first").attr('placeholder','');
    $("#button_horario_clear").prop("disabled", true);  

    // Se muestra el panel de guardar el nuevo horario.
    $("#nuevo_horario_guardar").removeClass("oculto");
    $("#registro_horario_guardar").addClass("oculto");
  });

  //Se genera y se guarda automáticamente el horario con módulos de la misma duración.
  $(document).on("click","#registro_horario #button_auto_generate",function(event){
    /*Se añade el tipo de horario, duracion de los módulos, posición del recreo y duración del recreo
      al botón para luego guardarlo en la base de datos.*/
    $("#registro_horario #button_horario_save").attr("tipo","auto");
    $("#registro_horario #button_horario_save").attr("duracion",$("#registro_horario #tiempo_modulo").val());
    $("#registro_horario #button_horario_save").attr("recreo",$("#registro_horario #horas_recreo_auto").val());
    $("#registro_horario #button_horario_save").attr("tiempo",$("#registro_horario #tiempo_recreo").val());

    //Se valida que la duración del día esté dentro del horario del centro.
    if($("#registro_horario #inicio_clase option:selected").val() < $("#registro_horario #inicio_horario_disable").val()){
      // Se establece el efecto para la notificación de error en el caso de que se de varias veces seguidas a guardar con algunas opción sin marcar.
      $(".ui-pnotify").remove();
        
      errorPNotify.play();

      new PNotify({
        text:'La hora de inicio de las clases no puede ser anterior a la hora de apertura del centro.',
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        stack: right_Stack,
        animate: {
          animate: true,
          in_class: "fadeInRight",
          out_class: "fadeOutRight",
        }
      });
      return false;
    }
    else{
      num_modulos=parseInt($("#registro_horario #total_horas_auto option:selected").val());
      duracion_modulos=parseInt($("#registro_horario #tiempo_modulo option:selected").val());
      duracion_recreo=parseInt($("#registro_horario #tiempo_recreo option:selected").val());

      total=(num_modulos*duracion_modulos)+duracion_recreo;

      array_inicio_clases=$("#registro_horario #inicio_clase option:selected").val().split(":");
      array_cierre_centro=$("#registro_horario #fin_horario_disable").val().split(":");

      inicio_clases=(parseInt(array_inicio_clases[0])*60)+parseInt(array_inicio_clases[1]);
      cierre_centro=(parseInt(array_cierre_centro[0])*60)+parseInt(array_cierre_centro[1]);
      permitido=cierre_centro-inicio_clases;

      if(permitido<total){
        // Se establece el efecto para la notificación de error en el caso de que se de varias veces seguidas a guardar con algunas opción sin marcar.
        $(".ui-pnotify").remove();

        errorPNotify.play();

        new PNotify({
          text:'Las horas del horario seleccionado sobrepasa el horario del centro.',
          addclass: "custom",
          type: "error",
          shadow: true,
          hide: true,
          buttons: {
            sticker: false,
            labels:{close: "Cerrar"}
          },
          stack: right_Stack,
          animate: {
            animate: true,
            in_class: "fadeInRight",
            out_class: "fadeOutRight",
          }
        });
        return false;
      }
    }


    // Se vacia el contenedor para el nuevo horario.
    $("#contenedor_nuevo_horario tbody").empty();

    // Se oculta el aviso de error por si estaba mostrado.
    $("#registro_horario #aviso_error").addClass("oculto");

    // Se añade las filas con cada hora de clase con los horarios en el nuevo contenedor.
    for (var i = 1; i <= $("#total_horas_auto").val(); i++) {
      // Se añade el horario de recreo.
      if(parseInt($("#horas_recreo_auto").val())+1 == i){
        cont='<tr><td><span class="oculto">*</span>';
        cont+='</td>';
        cont+='<td>RECREO';
        cont+='</td>';
        cont+='<td><input type="time" step="1"> </input>';
        cont+='</td>';
        cont+='<td><input type="time" step="1"> </input>';
        cont+='</td>';
        cont+='</tr>';
        $("#contenedor_nuevo_horario tbody:last-child").append(cont);
      }
      contenido='<tr><td><span class="oculto">*</span>';
      contenido+='</td>';
      contenido+='<td>'+i+'º';
      contenido+='</td>';
      contenido+='<td><input type="time" step="1"> </input>';
      contenido+='</td>';
      contenido+='<td><input type="time" step="1"> </input>';
      contenido+='</td>';
      contenido+='</tr>';
      $("#contenedor_nuevo_horario tbody:last-child").append(contenido);
    }
    tabindex=1;
    $("#contenedor_nuevo_horario").find(":input").each(function(i){
      $(this).attr("tabindex",tabindex);
      tabindex++;
    });
    // Se añade el valor a todos los input de forma automática sumando la duración de cada módulo a los input pares.
    valor=$("#registro_horario #inicio_clase option:selected").val();
    $("#contenedor_nuevo_horario input:first").val(valor);

    $("#contenedor_nuevo_horario input").each(function(i=1){
      horas=0;
      minutos=0;
      //Se comprueba si es el segundo input del recreo para sumarle la duración del recreo.
      if(i-1==($("#registro_horario #horas_recreo_auto option:selected").val())*2){
        array=valor.split(":");
        horas=parseInt(array[0]);

        minutos=parseInt(array[1]);
        minutos+=parseInt($("#registro_horario #tiempo_recreo option:selected").val());
        if(minutos>=60){
          minutos-=60;
          horas+=1;
        }
        valor=('0'+horas).slice(-2)+":"+('0'+minutos).slice(-2);
      }
      else{
        if(i%2)
        {
          array=valor.split(":");
          horas=parseInt(array[0]);

          minutos=parseInt(array[1]);
          minutos+=parseInt($("#registro_horario #tiempo_modulo option:selected").val());
          if(minutos>=60){
            minutos-=60;
            horas+=1;
          }
          valor=('0'+horas).slice(-2)+":"+('0'+minutos).slice(-2);
        }
      }
      $(this).val(valor);
    });

    //Se guarda el horario nuevo automáticamente sin mostrar.
    $("#registro_horario #button_horario_save").click();
  });

  $(document).on("click","#registro_horario #button_rest",function(event){
    // Se restablece el valor inicial de cada select y se desactiva el botón.
    $("#registro_horario .block_insert select").prop('selectedIndex',0);
    $("#button_rest").prop("disabled", true);
    $("#button_generate").prop("disabled", true);
  });

    $(document).on("click","#registro_horario #button_auto_rest",function(event){
    // Se elimina los valores de cada select y se desactiva el botón.
    $("#registro_horario .block_insert select").prop('selectedIndex',0);
    $("#button_auto_rest").prop("disabled", true);
    $("#button_auto_generate").prop("disabled", true);
  });

  $(document).on('change',"#registro_horario .div_change #cont_two select",function(event){
    $("#button_rest").prop("disabled", false);
    // Se desactiva la primera opción del select.
    $(this).find("option:eq('0')").prop("disabled", true);
    // Se comprueba que todos los select activos tienen un valor para activar el botón de generar nuevo horario.
    $("#registro_horario .div_change #cont_two").find("select:enabled").each (function(){
      if($(this).val()==0 || $(this).val()==null){
        $("#button_generate").prop("disabled", true);  
        return false;
      }
      $("#button_generate").prop("disabled", false);  
    });
  });

    $(document).on('change',"#registro_horario .div_change #cont_one select",function(event){
    $("#button_auto_rest").prop("disabled", false);
    // Se desactiva la primera opción del select.
    $(this).find("option:eq('0')").prop("disabled", true);
    // Se comprueba que todos los select activos tienen un valor para activar el botón de generar nuevo horario.
    $("#registro_horario .div_change #cont_one").find("select:enabled").each (function(){
      if($(this).val()==0 || $(this).val()==null){
        $("#button_auto_generate").prop("disabled", true);  
        return false;
      }
      $("#button_auto_generate").prop("disabled", false);  
    });
  });




  // Se ejecuta al soltar una tecla introducida en los input pares.
  $(document).on('keyup',"#contenedor_nuevo_horario input:odd",function(event){
    if($(this).val().trim().length==5){
      tab=parseInt($(this).attr("tabindex"));
      // Se habilita los input de la siguiente clase al introducir la hora final de la clase anterior.
      for (var i=tab; i <=tab+2; i++){        
        $("#contenedor_nuevo_horario input[tabindex="+i+"]").prop("disabled", false);
      }
      // Se le asigna la hora final de la clase anterior ala hora inicial de la siguiente clase, 
      // y se pasa el foco a la hora final de la siguiente clase para más comodidad(si ese input está vacío).
      $("#contenedor_nuevo_horario input[tabindex="+(tab+1)+"]").val($(this).val());
      if($("#contenedor_nuevo_horario input[tabindex="+(tab+2)+"]").val()==""){
        $("#contenedor_nuevo_horario input[tabindex="+(tab+2)+"]").focus();
      }
    }
  });

  // Función para validar las horas del horario Escolar.
  function validateTime(tab) {
    div_actual=$(tab).closest("div[id$='_horario']");

    // Se valida los input impares, excepto el primero.
    if($(tab).attr("tabindex")!=1 && $(tab).attr("tabindex")%2==1){
      h_act=$(tab).val().split(':');

      tab_prev=parseInt($(tab).attr("tabindex"))-1;
      h_prev=$(div_actual).find("input[tabindex="+tab_prev+"]").val().split(':');

      tab_next=parseInt($(tab).attr("tabindex"))+1;
      h_next=$(div_actual).find("input[tabindex="+tab_next+"]").val().split(':');
      
      if((h_act[0]<h_prev[0] && h_prev[0]!="")||(h_act[0]>h_next[0] && h_next[0]!="")||((h_act[0]==h_next[0]) && (h_act[1]>=h_next[1]) && h_next[1]!="") || ((h_act[0]==h_prev[0]) &&  (h_act[1]<h_prev[1]) && (h_prev[1]!=""))){
        $(tab).removeClass("modified");
        $(tab).addClass("invalid");
      }
      else{
        $(tab).removeClass("invalid");  
        }
      }
    // Se valida el primer input.  
    else if($(tab).attr("tabindex")%2==1){
      h_act=$(tab).val().split(':');
      tab_next=parseInt($(tab).attr("tabindex"))+1;
      h_next=$(div_actual).find("input[tabindex="+tab_next+"]").val().split(':');

      min=$("#registro_horario #inicio_horario_disable").val();
      inicio=min.split(":");
      max=$("#registro_horario #fin_horario_disable").val();
      fin=max.split(":");

      if((h_act[0]<inicio[0] || h_act[0]>fin[0])||(h_act[0]>h_next[0] && h_next[0]!="" )||((h_act[0]==h_next[0]) && (h_act[1]>=h_next[1])&& h_next[1]!="") ){
        $(tab).removeClass("modified");
        $(tab).addClass("invalid");
      }
      else{
        $(tab).removeClass("invalid");  
      }
    }
    // Se valida los input pares, excepto el último.
    else if($(tab).attr("tabindex")!=$(div_actual).find("input:last ").attr("tabindex") && $(tab).attr("tabindex")%2==0){
      h_act=$(tab).val().split(':');

      tab_prev=parseInt($(tab).attr("tabindex"))-1;
      h_prev=$(div_actual).find("input[tabindex="+tab_prev+"]").val().split(':');

      tab_next=parseInt($(tab).attr("tabindex"))+1;
      h_next=$(div_actual).find("input[tabindex="+tab_next+"]").val().split(':');

      if((h_act[0]<h_prev[0] && h_prev[0]!="")||((h_act[0]==h_prev[0]) && (h_act[1]<=h_prev[1]) && h_prev[1]!="")||(h_act[0]>h_next[0] && h_next[0]!="")||((h_act[0]==h_next[0]) && (h_act[1]>h_next[1]) && h_next[1]!="")){
        $(tab).removeClass("modified");
        $(tab).addClass("invalid");
      }
      else{
        $(tab).removeClass("invalid");
      }
    }
    // Se valida el último input.
    else if($(tab).attr("tabindex")%2==0){
      h_act=$(tab).val().split(':');
      tab_prev=parseInt($(tab).attr("tabindex"))-1;
      h_prev=$(div_actual).find("input[tabindex="+tab_prev+"]").val().split(':');
      
      min=$("#registro_horario #inicio_horario_disable").val();
      inicio=min.split(":");
      max=$("#registro_horario #fin_horario_disable").val();
      fin=max.split(":");

      if((h_act[0]<inicio[0] || h_act[0]>fin[0] || ( h_act[0]==fin[0] && h_act[1]>fin[1] ))||(h_act[0]<h_prev[0] && h_prev[0]!="") ||((h_act[0]==h_prev[0]) && (h_act[1]<=h_prev[1]) && h_prev[1]!="")){
        $(tab).removeClass("modified");
        $(tab).addClass("invalid");
      }
      else{
        $(tab).removeClass("invalid");
      }
    }

    // Se comprueba todos los valores de los input para añadir o quitar los avisos.
    $(div_actual).find("input").each (function(){
      $(this).closest("tr").find("td>span").addClass("oculto");
    });
    $(div_actual).find("input[class='invalid']").each (function(){
      $(this).closest("tr").find("td>span").removeClass("oculto");
    });

    $(div_actual).find("input").each (function(){
      if($(this).hasClass("invalid")){
        $("#registro_horario #aviso_error").removeClass("oculto");
        return false;
      }
      $("#registro_horario #aviso_error").addClass("oculto");
    });
    
    if($(div_actual).attr("id").indexOf("nuevo")>0){
      $(div_actual).find("input").each (function(){                         
        if($(this).val()=="" || $(this).hasClass("invalid")){
          $("#button_horario_save").prop("disabled", true);  
          return false;
        }
        $("#button_horario_save").prop("disabled", false);  
      });
    }
    else{
      // Se indica las modificaciones de los input.
      $(div_actual).find("input").each (function(){
        if($(this).val()!=$(this).attr("val_ini") && $(this).val()!="" &&  !$(this).hasClass("invalid")){     
            $(this).addClass("modified");
        }
        else{
            $(this).removeClass("modified");
          }
      });

      // Se habilta el botón "Guardar", solo para las filas que tengan hora de inicio y fin correctas
      $(div_actual).find("input").each (function(){
        $(this).closest("tr").find("button").prop("disabled", true);
      });

      $(div_actual).find("input[class='modified']").each (function(){
        $(this).closest("tr").find("button").prop("disabled", false);
      });

      $(div_actual).find("input[class='invalid'],input[value='']").each (function(){
        $(this).closest("tr").find("button").prop("disabled", true);
      });

      // Se habilta el botón "Restablecer", cuando se modifica cualquier valor original.
      $(div_actual).find("input").each (function(){
        if($(this).hasClass("modified") || $(this).hasClass("invalid")){     
          $("#horario_rest").prop("disabled", false);  
            return false;
        }
          $("#horario_rest").prop("disabled", true);  
      });
      // Se habilta el botón "Guardar Todo", cuando existe algun valor modificado , algún error  algún input vacío.
      $(div_actual).find("input").each (function(){                         
        if($(this).hasClass("invalid") || !$(div_actual).find("input").hasClass("modified")  || $(this).val()==""){
          $("#button_horario_all").prop("disabled", true);  
          return false;
        }
        $("#button_horario_all").prop("disabled", false);  
      });
    }
  }

  // Se desplaza los botones hacia arriba para mostrar bienel mensaje de error en registro de horario académico.
  $(document).on('blur keyup',"#registro_horario #contenedor_nuevo_horario input",function(e){
    setTimeout(function(){ 
      if($("#registro_horario #contenedor_nuevo_horario .invalid").size()>0){
        $("#registro_horario #button_two").addClass('margin_top');
        $("#registro_horario #contenedor_nuevo_horario").addClass('margin_top_2');
      }
      else{
        $("#registro_horario #button_two").removeClass('margin_top');
        $("#registro_horario #contenedor_nuevo_horario").removeClass('margin_top_2');
      }    
    },10);
  });
  
  $(document).on('blur keyup',"#registro_horario #contenedor_registro_horario input",function(e){
    setTimeout(function(){ 
      if($("#registro_horario #contenedor_registro_horario .invalid").size()>0){
        $("#registro_horario #button_one").addClass('margin_top');
        $("#registro_horario #contenedor_registro_horario").addClass('margin_top_2');
      }
      else{
        $("#registro_horario #button_one").removeClass('margin_top');
        $("#registro_horario #contenedor_registro_horario").removeClass('margin_top_2');
      }    
    },10);
  });
  // Se ejecuta al pulsar una tecla en los input de horario escolar.
  $(document).on('keyup',"#registro_horario div[id$='_horario'] input",function(e){
    div_actual=$(this).closest("div[id$='_horario']");
    min=$("#registro_horario #inicio_horario_disable").val();
    max=$("#registro_horario #fin_horario_disable").val();
    inicio=min.split(":");
    fin=max.split(":");

    // Se elimina el placeholder para que no aparezca al borrar el valor del input.
    //$(this).attr('placeholder','');
    // Se ejecuta al introducir una hora completa en un input.Si se pulsa borrar se ignora.
    if($(this).val().trim().length==5 && e.keyCode != 8){
      $(this).attr("value",$(this).val());
      tab=parseInt($(this).attr("tabindex"));
      if($(div_actual).find("input[tabindex="+(tab+1)+"]").val()==""){
        $(div_actual).find("input[tabindex="+(tab+1)+"]").focus();
      }
      // Se valida en línea la hora introducida.
      val=$(this).val().split(':');
      if((val[0]<inicio[0] || val[0]>fin[0]) || (val[1]<00 || val[1]>59)){
        $(this).removeClass("modified");
        $(this).addClass("invalid");
        $(this).closest("tr").find("td>span").removeClass("oculto");
        $("#registro_horario #aviso_error").removeClass("oculto");
      }
      else{
        $(this).removeClass("invalid");

        /*$(div_actual).find("input").each (function(){
          if($(this).hasClass("invalid")){
            $(this).closest("tr").find("td>span").removeClass("oculto");
            return false;
          }
          $(this).closest("tr").find("td>span").addClass("oculto");
        });

        $(div_actual).find("input").each (function(){
          if($(this).hasClass("invalid")){
            $("#registro_horario #aviso_error").removeClass("oculto");
            return false;
          }
          $("#registro_horario #aviso_error").addClass("oculto");
        });*/
      }

      // Se valida la hora introducida si se ha introducido un valor válido.
      if(!$(this).hasClass("invalid")){

        validateTime($(this));
        // Se valida el elemento anterior y posterior para quitar posibles avisos.
       if(!$(this).hasClass("invalid")){
        tab_prev=parseInt($(this).attr("tabindex"))-1;
        if($(div_actual).find("input[tabindex="+tab_prev+"]").val()!="" && $(this).attr("tabindex")>1){
          validateTime($(div_actual).find("input[tabindex="+tab_prev+"]"));
        }

        tab_next=parseInt($(this).attr("tabindex"))+1;
        if($(div_actual).find("input[tabindex="+tab_next+"]").val()!=""  && $(this).attr("tabindex")<$(div_actual).find("input:last").attr("tabindex")){
          validateTime($(div_actual).find("input[tabindex="+tab_next+"]"));
        }
       }
      }
      // Se comprueba que el div actual sea el de nuevo horario(#contenedor_nuevo_horario).
      if(div_actual.attr("id").indexOf("nuevo")>0){
        //Se habilita el botón "Borrar" al introducir una hora.
        $("#button_horario_clear").prop("disabled", false);  
      }
      // Se validan los demás input con clase "invalid" por si son válidos con los cambios de hora realizados.
      $(div_actual).find("input[class='invalid']").each (function(){
        validateTime($(this));
      });
    }

    if($(this).val().trim().length==0){
      $(this).removeClass("invalid");
      $(this).attr("value","");
      // Se elimina el aviso de error * si no hay ningún valor con inválido en la misma fila.
      $(this).closest("tr").find("input").each (function(){
        if($(this).hasClass("invalid")){
          $(this).closest("tr").find("td>span").removeClass("oculto");
          return false;
        }
        $(this).closest("tr").find("td>span").addClass("oculto");
      });

      $(div_actual).find("input").each (function(){
        if($(this).hasClass("invalid")){
          $("#registro_horario #aviso_error").removeClass("oculto");
          return false;
        }
          $("#registro_horario #aviso_error").addClass("oculto");
      });
      
      // Se habilita/deshabilita opciones en registro de nuevo horario.
      if(div_actual.attr("id").indexOf("nuevo")>0){
        $(div_actual).find("input").each(function(){              
          if($(this).val()!=""){
            $("#button_horario_clear").prop("disabled", false);  
            return false;
          }
          $("#button_horario_clear").prop("disabled", true);
        });
      }
      // Se habilita/deshabilita opciones en editar horario.
      else{
        $("#horario_rest").prop("disabled", false);  
      }
    }

    if($(this).val().length<5){
      if(div_actual.attr("id").indexOf("nuevo")>0){
        $("#button_horario_save").prop("disabled", true);
      }
      else{
        $("#button_horario_all").prop("disabled", true);
        $(this).closest("tr").find("button").prop("disabled", true);
        $(this).removeClass("modified");
      }  
    }
  });

  // Se obliga a introducir valores entre 07:00-19:00 que son los límites permitidos a elegir.
  $(document).on('keydown',"#registro_horario div[id$='_horario'] input",function(e){
    // Se asigna una máscara según el primer valor introducido.
    if($(this).val().trim().length>0 && $(this).val().trim().length<5 && e.keyCode != 8){
      if($(this).val().substr(0, 1)==0){
        $(this).unmask();
        // Máscara para las horas que empiezan por "0"(08:00-09:00).
        $(this).mask('AB:CD',
        {'translation': {A: {pattern: /[0-1]/}, B: {pattern: /[8-9]/}, C: {pattern: /[0-5]/}, D: {pattern: /[0-9]/}},
        placeholder: "__:__"});
      }
      else{
        $(this).unmask();
        // Máscara para las horas que empiezan por "1"(10:00-18:00).
        $(this).mask('AB:CD',
        {'translation': {A: {pattern: /[0-1]/}, B: {pattern: /[0-9]/}, C: {pattern: /[0-5]/}, D: {pattern: /[0-9]/}},
        placeholder: "__:__"});
      }
    }
  });


  // Se oculta el placeholder en el input seleccionado.    
  $(document).on("focus","#registro_horario div[id$='_horario'] input",function(event){
    $(this).data('placeholder',$(this).attr('placeholder')).attr('placeholder','');
  });

  // Se deja el valor en blanco en caso de no introducir una hora completa.
  $(document).on("blur","#registro_horario div[id$='_horario'] input",function(event){
    //$(this).attr('placeholder',$(this).data('placeholder'));
    $(this).data('placeholder',$(this).attr('placeholder')).attr('placeholder','__:__');

    if($(this).val().length<5){
      $(this).val("");
      $(this).removeClass("modified");
      $(this).attr("value","");
    }
  });

  // Se añade ":" tras introducir dos cifras.
  $(document).on('keyup',"input[type='time']",function(e){
    if($(this).val().trim().length==2 && e.keyCode != 8){
      $(this).val($(this).val()+":");
    }
  });
  
  $(document).on("click","#button_horario_clear",function(event){
    // Se restablece el valor inicial de cada input, se elimina la clase invalid en todos los input y se oculta los * en cada fila.
    $("#contenedor_nuevo_horario input").each (function(){
      $(this).attr("value","");
      $(this).val($(this).attr("value"));
      $(this).removeClass("invalid");
      $(this).closest("tr").find("td>span").addClass("oculto");
    });
    // Se elimina el aviso de error
    $("#registro_horario #aviso_error").addClass("oculto");

    //Se deshabilita todos los input menos los de la primera hora.
    $("#contenedor_nuevo_horario").find("input").each(function(i){
      $(this).prop("disabled", true);
    });
    $("#contenedor_nuevo_horario input:first").prop("disabled", false);
    $("#contenedor_nuevo_horario input:first").closest("td").next("td").find("input").prop("disabled", false);
    $("#contenedor_nuevo_horario input:first").focus();
    $("#button_horario_save").prop("disabled", true);  

  });

  $(document).on("click","#button_horario_rest",function(event){
    // Se vacia el contenedor para el nuevo horario y se muestra el contenedor inicial.
    $("#contenedor_nuevo_horario tbody").empty();
    $("#contenedor_nuevo_horario").addClass("oculto");
    $("#contenedor_registro_horario").removeClass("oculto");
    //Se muestra el panel de guardar inicial.
    $("#nuevo_horario_guardar").addClass("oculto");
    $("#registro_horario_guardar").removeClass("oculto"); 
    $("#registro_horario #aviso_error").addClass("oculto");

    //Se restablece el valor inicial de cada input, simulando el pulsar restablecer horario actual.
    $("#horario_rest").trigger("click");
  });

  $(document).on("click","#horario_rest",function(event){
    $("#contenedor_registro_horario #lista_color").load(Routing.generate('horario_escolar_actual'),function(data){
      $("#contenedor_registro_horario").find("input").each (function(){
        $(this).attr("val_ini",$(this).val());
      });
      $("#contenedor_registro_horario").find("input").each(function(i){
        $(this).attr("tabindex",i+1);
      });
    });

    $("#horario_rest").prop("disabled", true);
    $("#button_horario_all").prop("disabled", true);
    $("#contenedor_registro_horario tbody button").each (function(){
      $(this).prop("disabled", true);
    });  
    $("#registro_horario #aviso_error").addClass("oculto");
  });

  $(document).on('change',"#registro_horario input[id='si']",function(event){
    // Se comprueba si select de la comida esta activo y tiene valor para activar el botón de generar nuevo horario.
    if($(this).val()=="on"){
      $("#horas_comida").prop("disabled", false);  
      $("#horas_comida").prev().removeClass("disabled");
      if($("#horas_comida").val()==0){
        $("#button_generate").prop("disabled", true);  
      }
    }
  });

  $(document).on('change',"#registro_horario input[id='no']",function(event){
    if($(this).val()=="on"){
      // Se desactiva el select.
      $("#horas_comida").prop("disabled", true);  
      $("#horas_comida").prev().addClass("disabled");
    }  
    // Se comprueba que los restantes select activos tienen un valor para activar el botón de generar nuevo horario.
    if($("#total_horas").val()!=0 && $("#horas_recreo").val()!=0){
        $("#button_generate").prop("disabled", false);  
    }
  });

  // Se quita la marca de los input modificados y se guarda los valores en la base de datos.
  $(document).on('click',"#registro_horario_guardar #button_horario_all",function(event){

    if($("#contenedor_registro_horario input[value='']").size()>0){
      $("#contenedor_registro_horario input[value='']").each(function(){
        $(this).addClass("invalid");
        $(this).closest("tr").find("td>span").removeClass("oculto");
        $("#registro_horario #aviso_error").removeClass("oculto"); 
      });
      $("#registro_horario #registro_horario_guardar #button_horario_all").prop("disabled",true);
    }

    $("#contenedor_registro_horario input[class='modified']").each(function(){  
      $(this).closest("tr").find("input").removeClass("modified");
      clase=$(this).closest("tr").children('td').slice(1, 2).html();
      hora_ini=$(this).closest("tr").children('td').slice(2, 3).find("input").attr("value");
      hora_fin=$(this).closest("tr").children('td').slice(3, 4).find("input").attr("value");
      ini=hora_ini+":00";
      fin=hora_fin+":00";

      hora_ini=ini.split(":");
      hora_fin=fin.split(":");
      duracion=((parseInt(hora_fin[0])*60)+parseInt(hora_fin[1]))-((parseInt(hora_ini[0])*60)+parseInt(hora_ini[1]));
     
      // Se actualiza las horas modificadas en la entidad Horario.
      $.ajax({
        type: 'POST',
        url: Routing.generate('editar_horario'),
        data: {clase:clase,ini:ini,fin:fin,duracion:duracion},        
      })

      //Actualización de pestañas.
      $("#reservar_instalaciones").update_tab();
      $("#reservar_equipamientos").update_tab();
      $("#consultar_instalaciones").update_tab();
      $("#consultar_equipamientos").update_tab();
      $("#clases_impartidas").update_tab();
      $("#asignar_horario").update_tab();

      $("#registro_horario #registro_horario_guardar #button_horario_all").prop("disabled",true);
      $("#registro_horario #registro_horario_guardar #horario_rest").prop("disabled",true);
    });

  });

  $(document).on('click',"#button_horario_save",function(event){
    tipo=$(this).attr("tipo");//Tipo de horario: manual o auto.
    cadena="";
    cont=0;

    $("#contenedor_nuevo_horario tbody tr").each(function(){ 
      //Se calcula la duración de cada módulo.
      ini=$(this).find("td:nth-child(3) input").val();
      fin=$(this).find("td:nth-child(4) input").val();
      hora_ini=ini.split(":");
      hora_fin=fin.split(":");
      duracion=((parseInt(hora_fin[0])*60)+parseInt(hora_fin[1]))-((parseInt(hora_ini[0])*60)+parseInt(hora_ini[1]));
      //Se guarda todos los datos en una cadena
      cadena+= $(this).find("td:nth-child(2)").text() + "-";
      cadena+= $(this).find("td:nth-child(3) input").val()+ ":00-";
      cadena+= $(this).find("td:nth-child(4) input").val()+ ":00-";
      cadena+= duracion+ "-";

      cont++;
    });
    cadena=cadena.slice(0,-1);

    // Se comprueba si la tabla imparte y reserva estan vacía. 
    // En caso contrario, se avisa delos cambios antes de actualizar el horario.
        
    $.ajax({
      type: 'POST',
      url: Routing.generate('comprobaciones_horario_nuevo'),
      success: function(response) {
        if(response.imparte=="" && response.reserva=="")
        {
          //Se actualiza sin aviso puesto que no modifica nada en el sistema.
          $.ajax({
            type: 'POST',
            url: Routing.generate('nuevo_horario'),
            data: {cadena:cadena,cont:cont,tipo:tipo},
            success: function(response) {
              // Se establece el efecto para la notificación de error en el caso de que se de varias veces seguidas a guardar con algunas opción sin marcar.
              $(".ui-pnotify").remove();
              
              // Notificación de confirmación
              exito.play();
              
              new PNotify({
                text:"Horario Escolar registrado",
                addclass: "custom",
                type: "success",
                shadow: true,
                hide: true,
                buttons: {
                  sticker: false,
                  labels:{close: "Cerrar"}
                },
                stack: right_Stack,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              });
              //Actualización de pestañas.
              $("#reservar_instalaciones").update_tab();
              $("#reservar_equipamientos").update_tab();
              $("#consultar_instalaciones").update_tab();
              $("#consultar_equipamientos").update_tab();
              $("#clases_impartidas").update_tab();
              $("#asignar_horario").update_tab();

              // Se muestra el calendario actual actualizado.
              $("#registro_horario_guardar #horario_rest").prop("disabled",false);
              $("#registro_horario_guardar #horario_rest").trigger("click");
              $("#nuevo_horario_guardar #button_horario_rest").trigger("click");

              //Se muestra la tabla del nuevo horario escolar.  
              $("#contenedor_registro_horario table th").removeClass("oculto");
              $("#aviso_horario").addClass("oculto");  
            } 
          })
        }
        else{
          aviso.play();
          texto='<p class="justificado">Se recomienda asignar el Horario Escolar antes del comienzo del curso para no efectuar cambios en el sistema. Si desea realizar la asignación ahora, debe saber que se realizarán los siguientes cambios en el sistema:</p><br>';
          
          if(response.reserva!=""){
            texto=texto+"<li><span class='negrita'>Eliminación de las reservas de instalaciones o equipamientos registradas en el sistema.</span></li></br>";
          }
          if(response.imparte!=""){
            texto=texto+"<li><span class='negrita'>Eliminación de los horarios de las asignaturas en los cursos.</span></li></br>";
          }
          texto=texto+"¿Estas seguro de continuar? No podrás deshacer este paso...";
          swal({
            title: "Registro del Horario Escolar en el sistema.",
            html: texto,
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonColor: color,
            confirmButtonText: "¡Adelante!"
            }).then(function () {
              $.ajax({
                type: 'POST',
                url: Routing.generate('nuevo_horario'),
                data: {cadena:cadena,cont:cont,tipo:tipo},
                success: function(response) {

                  // Notificación de confirmación
                  exito.play();
              
                  new PNotify({
                    text:"Horario Escolar registrado",
                    addclass: "custom",
                    type: "success",
                    shadow: true,
                    hide: true,
                    buttons: {
                      sticker: false,
                      labels:{close: "Cerrar"}
                    },
                    stack: right_Stack,
                    animate: {
                      animate: true,
                      in_class: "fadeInRight",
                      out_class: "fadeOutRight",
                    }
                  });

                  //Actualización de pestañas.
                  $("#reservar_instalaciones").update_tab();
                  $("#reservar_equipamientos").update_tab();
                  $("#consultar_instalaciones").update_tab();
                  $("#consultar_equipamientos").update_tab();
                  $("#clases_impartidas").update_tab();
                  $("#asignar_horario").update_tab();

                  // Se muestra el calendario actual actualizado.
                  $("#aviso_horario").addClass("oculto");
                  $("#registro_horario_guardar #horario_rest").prop("disabled",false);
                  $("#registro_horario_guardar #horario_rest").trigger("click");
                  $("#nuevo_horario_guardar #button_horario_rest").trigger("click");    
                }
              })
            }, function (dismiss) {

            }
          );
        }
      }
    })
  });

  //Se abre la ventana modal para registrar horario del centro
  $(document).on("click","#registro_horario #inicio_horario_disable",function(event){
    event.preventDefault();
    //Se comprueba si existe horario escolar para mostrar aviso de modificaciones.
    if(!$("#registro_horario #aviso_horario").hasClass('oculto')){
        $('#horario_centro_dialog').load(Routing.generate("edit_horario_centro"), function(){
        }).dialog('open');
    }
    else
    {
    $.ajax({
      type: 'POST',
      url: Routing.generate('comprobaciones_horario_nuevo'),
      success: function(response) {
        aviso.play();
        texto='<p class="justificado">Se recomienda asignar el horario del centro antes del comienzo del curso para no efectuar cambios en el sistema. Si desea realizar la asignación ahora, debe saber que se realizarán los siguientes cambios en el sistema:</p><br>';
          
        texto=texto+"<li><span class='negrita'>Eliminación del Horario Escolar actual.</span></li></br>";

        if(response.reserva!=""){
          texto=texto+"<li><span class='negrita'>Eliminación de las reservas de instalaciones o equipamientos registradas en el sistema.</span></li></br>";
        }
        if(response.imparte!=""){
          texto=texto+"<li><span class='negrita'>Eliminación de las asignaciones de profesores en los cursos.</span></li></br>";
        }
        texto=texto+"¿Estas seguro de continuar? No podrás deshacer este paso...";
        swal({
          title: "Asignación del horario del centro.",
          html: texto,
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          confirmButtonColor: color,
          confirmButtonText: "¡Adelante!"
          }).then(function () {
            $('#horario_centro_dialog').load(Routing.generate("edit_horario_centro"), function(){
            }).dialog('open');
          }, function (dismiss) {

          }
        );
      }
    })
    }
  });


  //Se restablece la ventana modal.
  $(document).on("click","#button_hora_centro_rest",function(event){
    $('#horario_centro_dialog').load(Routing.generate("edit_horario_centro"), function(){
    }).dialog('open');
  });

  // Se valida los input al cambiar en timepicki.
  $(document).on('click',"#horario_centro_dialog .timepicker_wrap .prev ",function(event){
    elem=$(this).next().find("input");
    setTimeout(function(){
    //Se valida solo el input modificado. 
    $("#horario_centro_dialog  #aviso_error").hide();
      if(elem.val()!=""){
        elem.removeClass('error_guardar');
      }
      else{
        elem.addClass('error_guardar');
      }
    
    //Se comprueba si hay algún error para ocultar el aviso.
    $("#horario_centro_dialog  #aviso_error").hide();

    $("#horario_centro_dialog .time_pick .timepicker_wrap  input").each(function(index, el) {
      if($(this).hasClass('error_guardar')){
        $("#horario_centro_dialog  #aviso_error").show();
      }
    });
    },100);
  });

  // Se valida los input al cambiar en timepicki.
  $(document).on('click',"#horario_centro_dialog .timepicker_wrap .next ",function(event){
    elem=$(this).prev().find("input");
    setTimeout(function(){ 
    //Si se muestra error entonces comprobamos todoslos valores.  
    if($("#horario_centro_dialog  #aviso_error").is(':visible')){
      //Se valida todos los datos puesto que en este caso al modificar un los minutos puede cambiar las horas.
      $("#horario_centro_dialog .time_pick .timepicker_wrap input").each(function(index, el) {
        if($(this).val()!=""){
          $(this).removeClass('error_guardar');
        }
        else{
          $(this).addClass('error_guardar');
        }
      });
    }
    //Se comprueba si hay algún error para ocultar el aviso.
    $("#horario_centro_dialog  #aviso_error").hide();

    $("#horario_centro_dialog .time_pick .timepicker_wrap input").each(function(index, el) {
      if($(this).hasClass('error_guardar')){
        $("#horario_centro_dialog  #aviso_error").show();
      }
    });
    },100);
  });

  //Se guarda el horario del centro.
  $(document).on('click',"#horario_centro_dialog #button_hora_centro",function(event){
    event.preventDefault();

    $("#horario_centro_dialog  #aviso_error").hide();

    $("#horario_centro_dialog .timepicker_wrap input").each(function(){
      $(this).removeClass("error_guardar");

      if($(this).val()==""){
        $(this).addClass("error_guardar");
      }  
    });

    if($("#horario_centro_dialog input").hasClass("error_guardar")){
      $("#horario_centro_dialog  #aviso_error").show();
    }
    else{
      $("#curso_dialog  #aviso_error").hide();

      inicio= $("#horario_centro_dialog #horario_inicio_centro").val();
      fin= $("#horario_centro_dialog #horario_fin_centro").val(); 

      ini_anterior=$("#horario_centro_dialog #horario_inicio_centro").attr("value");
      fin_anterior=$("#horario_centro_dialog #horario_fin_centro").attr("value");
   
      if(inicio==ini_anterior && fin==fin_anterior){
          $("#horario_centro_dialog ").dialog('close');
          return false;
      }

      $.ajax({
        type: 'POST',
        url: Routing.generate('registrar_horario_centro'),
        data: "inicio="+inicio+"&fin="+fin,
        success: function(response) {

          $.ajax({
            type: 'POST',
            url: Routing.generate('horario_delete'),
            success: function(response) {
              $("#registrar_horario_academico").update_tab();
            }
          })

          exito.play();
          new PNotify({
            text:"Se ha registrado el horario del centro.",
            addclass: "custom",
            type: "success",
            shadow: true,
            hide: true,
            buttons: {
            sticker: false,
            labels:{close: "Cerrar"}
            },
            stack: right_Stack,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
          });
          //Se muestra un aviso si se elimina el horario escolar anterior.
          if($("#registro_horario #aviso_horario").hasClass('oculto')){
            new PNotify({
              text:"Se ha eliminado el horario escolar anterior.",
              addclass: "custom",
              type: "notice",
              shadow: true,
              hide: true,
              buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
          }

          // Se actualiza la pestaña de registrar horario escolar.
          $("#horario_centro_dialog  div[class='nuevo']").removeClass("nuevo");
          $("#horario_centro_dialog ").dialog('close');
        },error: function (response, desc, err){
            alert(desc);
            alert(err);
            }
      })
    }
  });

  //////////////////////////////////
  //        Instalaciones         //
  //////////////////////////////////

  $(document).on("focus","#registro_instalaciones .instalaciones #instalación_nombre",function(event){
    event.preventDefault();
      $("#registro_instalaciones .instalaciones #instalación_nombre").removeClass("invalid_placeholder");
  });

  $(document).on("blur","#registro_instalaciones .instalaciones #instalación_nombre",function(event){
    event.preventDefault();
    if($(this).val()==""){
      $(this).addClass("invalid_placeholder");
    }
    else{
      $(this).removeClass("invalid_placeholder");
    }
  });

  $(document).on("click","#registro_instalaciones .instalaciones #instalación_new",function(event){
    event.preventDefault();

    if($("#registro_instalaciones .instalaciones #instalación_nombre").val()==""){
      $("#registro_instalaciones .instalaciones #instalación_nombre").addClass("invalid_placeholder");
    }
    else{
      $.ajax({
        type: 'POST',
        url: $("#registro_instalaciones .instalaciones #instalación_nueva").attr('action'),
        data:$("#registro_instalaciones .instalaciones #instalación_nueva").serialize(), 
        dataType: 'json',
        success: function(response) {
          //Validación de existencia
          if(response.error){
            error.play();
            swal({
              title: "Instalación registrada en el sistema",
              text: 'La instalación introducida ya está registrada en el sistema.',
              type: "error",
              confirmButtonColor: color
            });
            return false;
          }
          // Se actualiza las pestañas.
          $("#registrar_instalaciones").update_tab();
          $("#reservar_instalaciones").update_tab();
          $("#consultar_instalaciones").update_tab();
          $("#consultar_equipamientos").update_tab();
        }
      })
    }
  });

  $(document).on("click","#registro_instalaciones .instalaciones td a",function(event){
    event.preventDefault();

    $("#registro_instalaciones .instalaciones #instalación_nombre").attr('placeholder',' ');

    $("#registro_instalaciones .instalaciones td a").closest("tr").removeClass("edit_equipamiento");
    $(this).closest("tr").addClass("edit_equipamiento");
    $("#registro_instalaciones .instalaciones  #datos_instalación").load($(this).attr("href"), function(){
      $("#registro_instalaciones .instalaciones #instalación_nombre").attr('placeholder','Inserte nombre para actualizar');
    });
    $("#registro_instalaciones .instalaciones #cerrar_instalación_edit").removeClass("oculto");
    $("#registro_instalaciones .instalaciones #instalación_delete").closest("th").removeClass("oculto");
    $("#registro_instalaciones .instalaciones #instalación_edit").closest("th").removeClass("oculto");
    $("#registro_instalaciones .instalaciones #instalación_new").closest("th").addClass("oculto");
    $("#registro_instalaciones .instalaciones #instalación_nombre").removeClass("invalid_placeholder");
    $("#registro_instalaciones .instalaciones #instalación_nombre").val($(this).text());
  });

  $(document).on("click","#registro_instalaciones .instalaciones #cerrar_instalación_edit",function(event){
    $("#registro_instalaciones .instalaciones td a").closest("tr").removeClass("edit_equipamiento");
    $(".instalaciones #datos_instalación").load(Routing.generate('equipamiento_new'));

    $("#registro_instalaciones .instalaciones #cerrar_instalación_edit").addClass("oculto");
    $("#registro_instalaciones .instalaciones #instalación_delete").closest("th").addClass("oculto");
    $("#registro_instalaciones .instalaciones #instalación_edit").closest("th").addClass("oculto");
    $("#registro_instalaciones .instalaciones #instalación_new").closest("th").removeClass("oculto");
    $("#registro_instalaciones .instalaciones #instalación_nombre").val("");
    $("#registro_instalaciones .instalaciones #instalación_nombre").removeClass("invalid_placeholder");
    $("#registro_instalaciones .instalaciones #instalación_nombre").attr('placeholder','Inserte nueva instalación');
    $("#registro_instalaciones .instalaciones #datos_instalación").removeClass("oculto");
  });

  $(document).on("click","#registro_instalaciones .instalaciones #instalación_edit",function(event){
    event.preventDefault();

    if($("#registro_instalaciones .instalaciones #instalación_nombre").val()==""){
      $("#registro_instalaciones .instalaciones #instalación_nombre").addClass("invalid_placeholder");
    }
    else{
      $.ajax({
        type: 'PUT',
        url: $("#registro_instalaciones .instalaciones #instalación_editar").attr('action'),
        data:$("#registro_instalaciones .instalaciones #instalación_editar").serialize(), 
        success: function(response) {
          //Validación de existencia
          if(response.error){
            error.play();
            swal({
              title: "Instalación registrada en el sistema",
              text: 'La instalación introducida ya está registrada en el sistema.',
              type: "error",
              confirmButtonColor: color
            });
            return false;
          }
          // Se actualiza las pestañas de instalaciones.
          $("#registrar_instalaciones").update_tab();
          $("#reservar_instalaciones").update_tab();
          $("#consultar_instalaciones").update_tab();
          $("#consultar_equipamientos").update_tab();
        }
      })
    }
  });

  $(document).on("click","#registro_instalaciones .instalaciones #instalación_delete",function(event){
    event.preventDefault();
    form= $(this).closest("th").prev().find("div[id='equipamiento_eliminar'] form");

    var arr= form.attr('action').split('/');
    equipamiento=$(" #registro_instalaciones .instalaciones#instalación_nombre").val();
    aviso.play();
    swal({
      title: "Eliminación de la instalación del sistema.",
      text: "¿Estas seguro de continuar? No podrás deshacer este paso...",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        $.ajax({
          type: 'POST',
          url: Routing.generate('equipamiento_reservado'),
          data: {equipamiento:equipamiento},
          dataType: 'json',
          async:false,
  
          success: function(response) {
            // Si no hay alumnos asignados al curso se puede eliminar.
            if(response.data!=null){ 
              error.play();
              swal({
                title: "La eliminación no se ha efectuado",
                html: '<p class="justificado">La instalación <span>"'+equipamiento+'"</span> no se puede eliminar porque está reservada. Debe eliminar las reservas de la instalación para poder eliminarla.</p>',
                type: "error",
                confirmButtonColor: color
              });
            }
            else{
              $.ajax({
                type: 'DELETE',
                url: Routing.generate("equipamiento_delete", {id:arr[5]}),
                data: form.serialize(),
        
                success: function() {
                  // Se actualiza las pestañas de equipamientos.
                  $("#registrar_instalaciones").update_tab();
                  $("#reservar_instalaciones").update_tab();
                  $("#consultar_instalaciones").update_tab();
                  $("#consultar_equipamientos").update_tab();
                }
              })
            }
          }
        })
      }, function (dismiss) {

      }
    );
    return false;
  });

  //////////////////////////////////
  //             Aulas            //
  //////////////////////////////////

  $(document).on("focus","#registro_instalaciones .aulas #aula_nombre",function(event){
    event.preventDefault();
      $("#registro_instalaciones #aula_nombre").removeClass("invalid_placeholder");
  });

  $(document).on("blur","#registro_instalaciones #aula_nombre",function(event){
    event.preventDefault();
    if($(this).val()==""){
      $(this).addClass("invalid_placeholder");
    }
    else{
      $(this).removeClass("invalid_placeholder");
    }
  });

  $(document).on("click","#registro_instalaciones #aula_new",function(event){
    event.preventDefault();

    if($("#registro_instalaciones #aula_nombre").val()==""){
      $("#registro_instalaciones #aula_nombre").addClass("invalid_placeholder");
    }
    else{
      //Se comprueba si el nombre empieza por "Aula", para añadirselo en caso contrario.
      if(!$("#registro_instalaciones #aula_nombre").val().match("^Aula")){
        nuevo="Aula "+$("#registro_instalaciones #aula_nombre").val().toLowerCase();
        $("#registro_instalaciones #aula_nombre").val(nuevo);
      }

      $.ajax({
        type: 'POST',
        url: $("#registro_instalaciones #aula_nueva").attr('action'),
        data:$("#registro_instalaciones #aula_nueva").serialize(), 
        dataType: 'json',
        success: function(response) {
          //Validación de existencia
          if(response.error){
            error.play();
            swal({
              title: "Aula registrada en el sistema",
              text: 'Esta aula ya se encuentra registrada en el sistema.',
              type: "error",
              confirmButtonColor: color
            });
            return false;
          }
          // Actualización de pestañas.
          $("#registrar_instalaciones").update_tab();
          $("#asignar_aula").update_tab();  
        }
      })
    }
  });

  //Se modifica el máximo de caracteres permitido para el nombre de aulas con el foco del elemento.
  $(document).on("focus","#registro_instalaciones #aula_nombre",function(event){
    event.preventDefault();
    $(this).attr("maxlength",10);

  });

  $(document).on("click","#registro_instalaciones .aulas td a",function(event){
    event.preventDefault();

    $("#registro_instalaciones #aula_nombre").attr('placeholder',' ');

    $("#registro_instalaciones .aulas td a").closest("tr").removeClass("edit_equipamiento");
    $(this).closest("tr").addClass("edit_equipamiento");

    $("#cerrar_aula_edit").removeClass("oculto");
    $("#registro_instalaciones #aula_delete").closest("th").removeClass("oculto");
    $("#registro_instalaciones #aula_edit").closest("th").removeClass("oculto");
    $("#registro_instalaciones #aula_new").closest("th").addClass("oculto");
    $("#registro_instalaciones #aula_nombre").removeClass("invalid_placeholder");
    $("#registro_instalaciones #aula_nombre").val($(this).text());
    $("#registro_instalaciones .aulas #datos_instalación").load($(this).attr("href"), function(){
      $("#registro_instalaciones .aulas #aula_nombre").attr('placeholder','Inserte nuevo nombre');
    });
  });

  $(document).on("click","#cerrar_aula_edit",function(event){
    $("#registro_instalaciones .aulas td a").closest("tr").removeClass("edit_equipamiento");
    $("#registro_instalaciones .aulas #datos_instalación").load(Routing.generate('equipamiento_new'));

    $("#cerrar_aula_edit").addClass("oculto");
    $("#registro_instalaciones #aula_delete").closest("th").addClass("oculto");
    $("#registro_instalaciones #aula_edit").closest("th").addClass("oculto");
    $("#registro_instalaciones #aula_new").closest("th").removeClass("oculto");
    $("#registro_instalaciones #aula_nombre").val("");
    $("#registro_instalaciones #aula_nombre").removeClass("invalid_placeholder");
    $("#registro_instalaciones #aula_nombre").attr('placeholder','Inserte nueva aula');
    $("#registro_instalaciones .aulas #datos_instalación").removeClass("oculto");
  });

  $(document).on("click","#registro_instalaciones #aula_edit",function(event){
    event.preventDefault();

    if($("#registro_instalaciones #aula_nombre").val()==""){
      $("#registro_instalaciones #aula_nombre").addClass("invalid_placeholder");
    }
    else{
      //Se comprueba si el nombre empieza por "Aula", para añadirselo en caso contrario.
      if(!$("#registro_instalaciones #aula_nombre").val().match("^Aula")){
        nuevo="Aula "+$("#registro_instalaciones #aula_nombre").val().toLowerCase();
        $("#registro_instalaciones #aula_nombre").val(nuevo);
      }
      $.ajax({
        type: 'PUT',
        url: $("#registro_instalaciones #aula_editar").attr('action'),
        data:$("#registro_instalaciones #aula_editar").serialize(), 
        success: function(response) {
          //Validación de existencia
          if(response.error){
            error.play();
            swal({
              title: "Aula registrada en el sistema",
              text: 'Esta aula ya se encuentra registrada en el sistema.',
              type: "error",
              confirmButtonColor: color
            });
            return false;
          }

          // Actualización de pestañas.
          $("#registrar_instalaciones").update_tab();
          $("#asignar_aula").update_tab();
          $("#profesor_asignar_grupo").update_tab();
          $("#asignar_horario").update_tab();
        }
      })
    }
  });

  $(document).on("click","#registro_instalaciones #aula_delete",function(event){
    event.preventDefault();
    form= $(this).closest("th").prev().find("div[id='equipamiento_eliminar'] form");

    var arr= form.attr('action').split('/');
    equipamiento=$("#registro_instalaciones #aula_nombre").val();
    aviso.play();
    swal({
      title: "Eliminación del aula del sistema.",
      text: "¿Estas seguro de continuar? No podrás deshacer este paso...",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        $.ajax({
          type: 'POST',
          url: Routing.generate('equipamiento_reservado'),
          data: {equipamiento:equipamiento},
          dataType: 'json',
          async:false,
  
          success: function(response) {
            // Si no hay alumnos asignados al curso se puede eliminar.
            if(response.data!=null){ 
              error.play();
              swal({
                title: "La eliminación no se ha efectuado",
                html: '<p class="justificado">El aula <span>"'+equipamiento+'"</span> no se puede eliminar porque está reservada. Debe eliminar las reservas de la instalación para poder eliminarla.</p>',
                type: "error",
                confirmButtonColor: color
              });
            }
            else{
              $.ajax({
                type: 'DELETE',
                url: Routing.generate("equipamiento_delete", {id:arr[5]}),
                data: form.serialize(),
        
                success: function() {
                  // Se actualiza las pestañas de instalaciones y asignar aulas.
                  $("#registrar_instalaciones").update_tab();
                  $("#asignar_aula").update_tab();
                }
              })
            }
          }
        })
      }, function (dismiss) {

      }
    );
    return false;
  });



  //////////////////////////////////
  //        Equipamientos         //
  //////////////////////////////////


  $(document).on("focus","#equipamiento_nueva input",function(event){
    event.preventDefault();
      $(this).removeClass("invalid_placeholder");
  });

  $(document).on("blur","#registro_equipamientos .contenedor_registro_instalaciones form input",function(event){
    event.preventDefault();
    if($(this).val()==""){
      $(this).addClass("invalid_placeholder");
    }
    else{
      $(this).removeClass("invalid_placeholder");
    }
  });

  $(document).on("change","#equipamiento_nueva input",function(event){
    event.preventDefault();
    if($(this).val()==""){
      $(this).addClass("invalid_placeholder");
    }
    else{
      $(this).removeClass("invalid_placeholder");
    }
  });


  $(document).on("keydown","#equipamiento_unidades",function(event){
    if(($("#equipamiento_unidades").val().length==2 && event.keyCode != 8 && event.keyCode != 46&& event.keyCode != 37 && event.keyCode != 39 ) || (  ($("#equipamiento_unidades").val().length==1 || $("#equipamiento_unidades").val().length==0) && !(event.keyCode>=96 && event.keyCode<=105) && !(event.keyCode>=48 && event.keyCode<=57) && event.keyCode != 8 && event.keyCode != 46 && event.keyCode != 37 && event.keyCode != 39)){
      return false;
    }
  });

  $(document).on("click","#equipamiento_new",function(event){
    event.preventDefault();

    if($("#equipamiento_nombre").val()=="" || $("#equipamiento_unidades").val()==""){
      if($("#equipamiento_nombre").val()=="")
        $("#equipamiento_nombre").addClass("invalid_placeholder");
      if($("#equipamiento_unidades").val()=="")
        $("#equipamiento_unidades").addClass("invalid_placeholder");
    }
    else{
      $.ajax({
        type: 'POST',
        url: $("#equipamiento_nueva").attr('action'),
        data:$("#equipamiento_nueva").serialize(), 
        dataType: 'json',
        success: function(response) {
          //Validación de existencia
          if(response.error){
            error.play();
            swal({
              title: "Equipamiento registrado en el sistema",
              text: 'El equipamiento introducido ya está registrado en el sistema.',
              type: "error",
              confirmButtonColor: color
            });
            return false;
          }

          // Se actualiza las pestañas de equipamientos.
          $("#registrar_equipamientos").update_tab();
          $("#reservar_equipamientos").update_tab();
        }
      })
    }
  });

$(document).on("click","#registro_equipamientos td a",function(event){
    $("#equipamiento_nombre").attr('placeholder',' ');

    $("#registro_equipamientos td a").closest("tr").removeClass("edit_equipamiento");
    $(this).closest("tr").addClass("edit_equipamiento");
    $("#datos_equipamiento").load($(this).attr("href"), function(){
      $("#equipamiento_nombre").attr('placeholder','Inserte el nombre para actualizar el equipamiento');
    });
    $("#cerrar_equipamiento_edit").removeClass("oculto");
    $("#equipamiento_delete").closest("th").removeClass("oculto");
    $("#equipamiento_edit").closest("th").removeClass("oculto");
    $("#equipamiento_new").closest("th").addClass("oculto");
    $("#equipamiento_nombre").removeClass("invalid_placeholder");

  });

  $(document).on("click","#cerrar_equipamiento_edit",function(event){
    $("#registro_equipamientos td a").closest("tr").removeClass("edit_equipamiento");
    $("#datos_equipamiento").load(Routing.generate('equipamiento_new'));

    $("#cerrar_equipamiento_edit").addClass("oculto");
    $("#equipamiento_delete").closest("th").addClass("oculto");
    $("#equipamiento_edit").closest("th").addClass("oculto");
    $("#equipamiento_new").closest("th").removeClass("oculto");
    $("#equipamiento_nombre").val("");
    $("#equipamiento_nombre").removeClass("invalid_placeholder");
    $("#equipamiento_nombre").attr('placeholder','Inserte el nombre para añadir nuevo equipamiento');
    $("#datos_equipamiento").removeClass("oculto");
    setTimeout(function(){ 
      $("#equipamiento_tipo").val('Equipamiento');
    },100);
  });

  $(document).on("click","#equipamiento_edit",function(event){
    event.preventDefault();

    if($("#equipamiento_nombre").val()=="" || $("#equipamiento_unidades").val()==""){
      if($("#equipamiento_nombre").val()=="")
        $("#equipamiento_nombre").addClass("invalid_placeholder");
      if($("#equipamiento_unidades").val()=="")
        $("#equipamiento_unidades").addClass("invalid_placeholder");
    }
    else{
      $.ajax({
        type: 'PUT',
        url: $("#equipamiento_editar").attr('action'),
        data:$("#equipamiento_editar").serialize(), 
        success: function(response) {
          //Validación de existencia
          if(response.error){
            error.play();
            swal({
              title: "Equipamiento registrado en el sistema",
              text: 'El equipamiento introducido ya está registrado en el sistema.',
              type: "error",
              confirmButtonColor: color
            });
            return false;
          }
          // Se actualiza las pestañas de equipamientos.
          $("#registrar_equipamientos").update_tab();
          $("#reservar_equipamientos").update_tab();
          $("#consultar_equipamientos").update_tab();
        }
      })
    }
  });

  $(document).on("click","#equipamiento_delete",function(event){
    event.preventDefault();
    form= $(this).closest("th").prev().find("div[id='equipamiento_eliminar'] form");

    var arr= form.attr('action').split('/');
    equipamiento=$("#equipamiento_nombre").val();
    // Audio de notificación
    aviso.play(); 
    // Se muestra la notificación.
    swal({
     title: "Eliminación del equipamiento del sistema.",
     text: "¿Estas seguro de continuar? No podrás deshacer este paso...",
     type: "warning",
     showCancelButton: true,
     cancelButtonText: "Cancelar",
     confirmButtonColor: color,
     confirmButtonText: "¡Adelante!"
     }).then(function () {

      $.ajax({
        type: 'POST',
        url: Routing.generate('equipamiento_reservado'),
        data: {equipamiento:equipamiento},
        dataType: 'json',
        async:false,
  
        success: function(response) {
          // Si no hay alumnos asignados al curso se puede eliminar.
          if(response.data!=null){
            error.play(); 
            swal({
              title: "La eliminación no se ha efectuado",
              html: '<p class="justificado">El equipamiento <span>"'+equipamiento+'"</span> no se puede eliminar porque está reservado. Debe eliminar las reservas del equipamiento para poder eliminarlo.</p>',
              type: "error",
              confirmButtonColor: color
            });
          }
          else{
            $.ajax({
              type: 'DELETE',
              url: Routing.generate("equipamiento_delete", {id:arr[5]}),
              data: form.serialize(),
        
              success: function() {
                // Se actualiza las pestañas de equipamientos.
                $("#registrar_equipamientos").update_tab();
                $("#reservar_equipamientos").update_tab();
              }
            })
          }
        }
      })
     }, function (dismiss) {

     }
    );
    return false;
  });

  ///////////////////////////////////////////
  //  Reserva Instalaciones/Equipamientos  //
  ////////////////////////////////// ////////

  // Se deshabilita los días no lectivos en el calendario de reservas. 
  $(document).on('click',"#actualizar_calendario_lectivo",function(event){
    //Se obtiene el contenedor principal según si el calendario está en un formulario o no.

    if($(this).hasClass('form'))
    {
      contenedor=$(this).closest("form");
    }
    else{
      contenedor=$(this).closest("div[class*='general_container']");
    }

    event.preventDefault();
    // Retardo para ejecutarlo una vez cargado el datepicker.
    contenedor.find("#div_leyenda").addClass("oculto"); 
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
                  $(this).closest("td:not(.ui-datepicker-week-end) a").addClass(" no_lectivo");//Se le añade la clase no:lectivo a todos los elementos "a" de los días festivos.
                  // Se comprueba que el día festivo es un domingo y que el lunes no hay ningún festivo añadido. 
                  if($(this).closest("td").hasClass("ui-datepicker-week-end") &&  $(this).closest("tr").find("td:last a").text() == $(this).text() && !contenedor.find("#div_leyenda h4[id='"+$(this).closest("tr").next("tr").find("td:nth-child(1) a").text()+"']").length){
                    // Mostramos el traspado del festivo al lunes.
                    $(this).closest("tr").next("tr").find("td:nth-child(1) a").closest("td").addClass("ui-datepicker-unselectable");
                    $(this).closest("tr").next("tr").find("td:nth-child(1) a").closest("td a").addClass("no_lectivo");

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
                $(this).closest("td:not(.ui-datepicker-week-end) a").addClass(" no_lectivo");
                // Se comprueba que el día festivo es un domingo, que el lunes no hay ningún festivo añadido y que el festivo no coincida con el último día del mes. 
                if($(this).closest("td").hasClass("ui-datepicker-week-end") &&  $(this).closest("tr").find("td:last a").text() == $(this).text() && !contenedor.find("#div_leyenda h4[id='"+$(this).closest("tr").next("tr").find("td:nth-child(1) a").text()+"']").length && contenedor.find("tbody tr:last td:last a").text()!=$(this).text() ){
                  // Mostramos el traspado del festivo al lunes.
                  $(this).closest("tr").next("tr").find("td:nth-child(1) a").closest("td").addClass("ui-datepicker-unselectable");
                  $(this).closest("tr").next("tr").find("td:nth-child(1) a").closest("td a").addClass("no_lectivo");
                  
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
                  contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a").filter(function(){return $(this).text()==i;}).closest("td a").addClass("no_lectivo");

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
              contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a").filter(function(){return $(this).text()==i;}).closest("td a").addClass("no_lectivo");
              
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
              contenedor.find(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a").filter(function(){return $(this).text()==i;}).closest("td a").addClass("no_lectivo");

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
              dia_1.closest("td a").addClass("no_lectivo");
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


  $(document).on('click',"#contenedor_reserva_equipamientos button",function(event){ 
    contenedor= $(this).closest("div[id^='reserva_']");

    // Se marca el equipamiento seleccionado.
    if(!$(this).hasClass("elected")){
      contenedor.find("#contenedor_reserva_equipamientos button").removeClass("elected"); 
      $(this).addClass("elected");
    }

    // Se llama a la función de comprobar las horas de reserva cuando seleccionamos una instalación y un día.
    if(contenedor.find("#contenedor_reserva tbody td a").hasClass("ui-state-default ui-state-active")){
      HorasReserva(contenedor.find("#contenedor_reserva_horas"));
    }

    // Se desactiva el botón de guardar al cambiar de día.
    contenedor.find("#reserva_save").prop("disabled",true);
  });

  //Se simula el hacer click en el calendario, haciendo click en un input oculto que contiene el día deleccionado.
  $(document).on('click',"#dia_seleccionado",function(e){
    contenedor= $(this).closest("div[id^='reserva_']");

    if(contenedor.find("#contenedor_reserva_equipamientos button").hasClass("elected")){
      HorasReserva(contenedor.find("#contenedor_reserva_horas"));
    }
  });

  //Se marca las horas de reserva seleccionada y se habilita el botón guardar si existe al menos una hora seleccionada.
  $(document).on('click',"#contenedor_reserva_horas button",function(event){
    contenedor= $(this).closest("div[id^='reserva_']");

    if($(this).hasClass("elected")){
      $(this).removeClass("elected");
    }
    else{
      $(this).addClass("elected");
    }

    if(contenedor.find('#contenedor_reserva_horas .elected').length) {
      contenedor.find("#reserva_save").prop("disabled",false);
    }
    else{
      contenedor.find("#reserva_save").prop("disabled",true);
    }
  });


  $(document).on('click',"div[id^='reserva_'] #reserva_save",function(event){ 
    // Se establece el efecto para la notificación de error en el caso de que se de varias veces seguidas a guardar con algunas opción sin marcar.
    $(".ui-pnotify").remove();

    contenedor= $(this).closest("div[id^='reserva_']");


    if(contenedor.find(".contenedor_reserva .elected").size()>1 || contenedor.find("#contenedor_reserva .ui-state-active").size()>1 ){

      arr=contenedor.attr("id").split("_");
      var texto="";

      if(contenedor.find(".contenedor_reserva .elected").size()>1){

        arr=contenedor.attr("id").split("_");
        if(arr[1]=="instalaciones"){
          texto+="<span>Instalación<span>";
        }
        else{
          texto+="<span>Equipamiento<span>";
        }
      }

      if(contenedor.find("#contenedor_reserva .ui-state-active").size()>1){
        texto+="<span>Fecha de reserva<span>";
      }
      errorPNotify.play();
      new PNotify({
        title: "Las siguientes opciones no pueden tener valores multiples seleccionados:",
        text:texto,
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        width: "335px",
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        stack: left_Stack,
        animate_speed: "fast",
        animate: {
          animate: true,
          in_class: "fadeInLeft",
          out_class: "fadeOutLeft",
        }
      });
      contenedor.find("#reserva_save").prop("disabled",true);
      return false;
    }

    fecha=contenedor.find("#dia_seleccionado").val();
    if(fecha){
      fecha= fecha.split("/");
      fecha= fecha[2]+"-"+fecha[1]+"-"+fecha[0]; 
    }

    equipamiento=contenedor.find("#contenedor_reserva_equipamientos button[class='elected']").text();

    // Se obtiene las horas seleccionadas.
    var objDatos= Array();
    contenedor.find("div[id='contenedor_reserva_horas'] button[class='elected']").each(function(){
      objDatos.push($(this).attr("clase"));
    });
    $.ajax({
      type: 'POST',
      url: Routing.generate('reserva_create'),
      data:{objDatos:objDatos,equipamiento:equipamiento,fecha:fecha}, 
      dataType: 'json',
      success: function(response) {
        nombre_contenedor=contenedor.attr("id");
        array=nombre_contenedor.split("_");
        tipo=array[1];

        contenedor.find("div[id='contenedor_reserva_equipamientos'] button[class='elected']").trigger("click");
        // Se comrpueba si hay alguna opción sin marcar para mostrar la notificación de error.
        if(response.error.length != 0){
          var texto="";
          for (var key in response.error) {
            texto+="<span>"+response.error[key]+"<span>";
          }
          if(tipo=="instalaciones"){
            texto=texto.replace("Equipamiento", "Instalación");
          }
          errorPNotify.play();
          new PNotify({
            title: "Debe seleccionar los siguientes datos para realizar la reserva:",
            text:texto,
            addclass: "custom",
            type: "error",
            shadow: true,
            hide: true,
            width: "335px",
            buttons: {
            sticker: false,
            labels:{close: "Cerrar"}
            },
            stack: left_Stack,
            animate_speed: "fast",
            animate: {
              animate: true,
              in_class: "fadeInLeft",
              out_class: "fadeOutLeft",
            }
          });
          $("#reserva_equipamientos #reserva_save").prop("disabled",true);
          return false;
        }

        if(response.message=="festivo"){
          // Se indica que el día seleccionado es festivo.
          texto="El día seleccionado <span>no es lectivo</span>, por lo que no se puede realizar la reserva.<br><br>";
          dia=contenedor.find("#contenedor_reserva a[class*='ui-state-active']").text();
          mes=contenedor.find("#contenedor_reserva span[class='ui-datepicker-month']").text();
          anyo=contenedor.find("#contenedor_reserva span[class='ui-datepicker-year']").text();
          texto+="<p>Día seleccionado: <span>"+dia+" de "+mes+" de "+anyo+"</span></p><br>";
          motivo=contenedor.find("#contenedor_reserva a[class*='ui-state-active']").attr("title");
          texto+="<p>Motivo no lectivo:  <span>"+motivo+"</span></p>";
          
          error.play();
          swal({
            title: "Se ha producido un error en el sistema",
            html: texto,
            type: "error",
            confirmButtonColor: color,
            closeOnConfirm: false
          });
          return false;
        }
        if(response.data.length != 0)
        {
          var texto= 'El equipamiento <span class="negrita">'+equipamiento+'</span> no se puede reservar en los siguientes módulos de clase porque ya esta reservado:<br><br>';
          for (var key in response.data) {
            hora=contenedor.find("#contenedor_reserva_horas button[clase='"+response.data[key]+"']").text(); 
            texto+="<h4>"+hora+"</h4>";
          }
          error.play();
          swal({
            title: "Se ha producido un error en el sistema al realizar la reserva",
            html: texto,
            type: "error",
            confirmButtonColor: color,
            closeOnConfirm: false
            }).then(function () {

              if(response.message==1){
                exito.play();
                new PNotify({
                  text:"1 reserva efectuada.",
                  addclass: "custom",
                  type: "success",
                  shadow: true,
                  hide: true,
                  buttons: {
                    sticker: false,
                    labels:{close: "Cerrar"}
                  },
                  stack: right_Stack,
                  animate: {
                    animate: true,
                    in_class: "fadeInRight",
                    out_class: "fadeOutRight",
                  }
                });
                // Se actualiza las pestañas de equipamientos o instalaciones
                $("#consultar_"+tipo).update_tab();
                $("#reservar_"+tipo).update_tab();
              }
              else if(response.message!=0){
                titulo=response.message+ " reservas efectuadas.";
                exito.play();

                new PNotify({
                  text:titulo,
                  addclass: "custom",
                  type: "success",
                  shadow: true,
                  hide: true,
                  buttons: {
                    sticker: false,
                    labels:{close: "Cerrar"}
                  },
                  stack: right_Stack,
                  animate: {
                    animate: true,
                    in_class: "fadeInRight",
                    out_class: "fadeOutRight",
                  }
                });
                // Se actualiza las pestañas de equipamientos o instalaciones
                $("#consultar_"+tipo).update_tab();
                $("#reservar_"+tipo).update_tab();
              }
              else{
                errorPNotify.play();

                new PNotify({
                  text:'No se ha realizado ninguna reserva',
                  addclass: "custom",
                  type: "error",
                  shadow: true,
                  hide: true,
                  buttons: {
                    sticker: false,
                    labels:{close: "Cerrar"}
                  },
                  stack: right_Stack,
                  animate: {
                    animate: true,
                    in_class: "fadeInRight",
                    out_class: "fadeOutRight",
                  }
                });
              }
            }, function (dismiss) {

            }
          );
        }
        else{
          if(response.message==1){
            exito.play();
            new PNotify({
              text:"1 reserva efectuada.",
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              animation: "fade",
              animate_speed: 'fast',
              delay: 4000,
              buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
          }
          else{
            titulo=response.message+ " reservas efectuadas.";
            exito.play();

            new PNotify({
              text:titulo,
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
          }
          // Se actualiza las pestañas de equipamientos o instalaciones
          $("#consultar_"+tipo).update_tab();
          $("#reservar_"+tipo).update_tab();
        }
      }
    })
  });
  // Función para deshabilitar los botones de las horas reservadas.
  function HorasReserva(contenedor) {
    container=contenedor.closest("div[id^='reserva_']");
    
    fecha=container.find("#dia_seleccionado").val();
    fecha= fecha.split("/");
    fecha= fecha[2]+"-"+fecha[1]+"-"+fecha[0];
    equipamiento=container.find("#contenedor_reserva_equipamientos button[class='elected']").text();

    // Se comprueba si el usuario tiene reservas en el día indicado y se muestra.
    // Se comprueba si quedan unidades de equipamiento o la instalación está libre para el día indicado.
    $.ajax({
      type: 'POST',
      url: Routing.generate('comprobar_reserva'),
      data: {equipamiento:equipamiento, fecha:fecha},
      dataType: 'json',
      success: function(response) {
        contenedor.find("button").prop("disabled",false);
        contenedor.find("button").removeClass();

        if(response.data!=null)
        {
          for (var key in response.data) { 
            container.find("#contenedor_reserva_horas button[clase="+response.data[key]["horaClase"]+"]").prop("disabled", true);
            container.find("#contenedor_reserva_horas button[clase="+response.data[key]["horaClase"]+"]").addClass("reservado");
          }
        }
        if(response.data2!=null)
        {
          for (var key in response.data2) { 
            container.find("#contenedor_reserva_horas button[clase="+response.data2[key]["horaClase"]+"]").prop("disabled", true);
          }
        }

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

          container.find("#contenedor_reserva_horas button").each(function(){
            var dt = new Date();
            var time = ('0'+dt.getHours()).slice(-2) + ":" + ('0'+dt.getMinutes()).slice(-2);
   
            if($(this).attr("min").replace(':', '') <= time.replace(':', '')){
              $(this).prop("disabled",true);
            }
          });
        }
      }
    })
  }

  /////////////////////////////////////////////////////
  //  Consulta reservas Instalaciones/Equipamientos  //
  /////////////////////////////////////////////////////

  // Función para comprobar si hay scroll vertical en la pantalla.
  (function($) {
    $.fn.hasScrollBar = function() {
        return this.get(0).scrollHeight > this.height();
    }
  })(jQuery);
  //Se utilizaba ésta variable para obtener el día de la semana pero lo cambiamos al número de día.
  var dia_semana = ["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"];
  
  // Se muestra la información del profesor al situar el ratón sobre una reserva de un profesor.
  $(document).on("mouseenter","div[id^='consulta_reservas'] .scrollContent tr[id!='centro']", function () {
    container= $(this).closest("div[id^='consulta_reservas']");

    // Se evita que se muestre el mensaje predeterminado si pasamos de un enlace a otro.
    container.find(".contenido_info #sin_seleccionar").addClass("oculto");

    hover=1;
    tr=$(this);
    //Se comprueba que no es una reserva del centro y que no es un aviso predeterminado.
    if($(this).attr("id")!="centro" && !$(this).find("td").hasClass("dataTables_empty")){
      container= $(this).closest("div[id^='consulta_reservas']");

      from=$(this).find("td:nth-child(3)").attr("data-order").split("/");

      fecha = new Date(from[2], from[0]-1 , from[1]);
      //Se para el número de día de la semana.
      dia_semanal=fecha.getDay();

      hora_inicio=$(this).find("td:nth-child(4)").attr("inicio");
      hora_fin=$(this).find("td:nth-child(4)").attr("fin");
      ini=hora_inicio+":00";
      fin=hora_fin+":00";

      profesor=$(this).attr("id");


      $.ajax({
        type: 'POST',
        url: Routing.generate('datos_imparte'),
        data: {dia_semanal:dia_semanal, ini:ini, fin:fin, profesor:profesor},
        dataType: 'json',
        success: function(response) {
          container.find(".contenido_info #seleccionado").load(Routing.generate('datos_profesor', {id:tr.attr("id")}), function(){
            if(hover==1){
              if(response.data){
                container.find(".contenido_info #grupo").append(response.curso+" "+response.nivel+" "+response.grupo);
                container.find(".contenido_info #asignatura").append(response.asignatura);
              }
              else{
                container.find(".contenido_info #grupo").append("Sin asignar");
                container.find(".contenido_info #asignatura").append("Sin asignar");
              }
              container.find(".contenido_info #seleccionado").removeClass("oculto");
              container.find(".contenido_info #sin_seleccionar").addClass("oculto");
            }
          });
        }
      })
    }
    else{
      container.find(".contenido_info #seleccionado").empty();
      container.find(".contenido_info #seleccionado").addClass("oculto");
      container.find(".contenido_info #sin_seleccionar").removeClass("oculto");
    }
  });

  // Se elimina la información mostrada del profesor al quitar el puntero.
  $(document).on("mouseleave","div[id^='consulta_reservas'] .scrollContent tr[id!='centro'] ", function () {
    hover=0;
    container= $(this).closest("div[id^='consulta_reservas']");

    container.find(".contenido_info #seleccionado").addClass("oculto");
    container.find(".contenido_info #seleccionado").empty();
    container.find(".contenido_info #sin_seleccionar").removeClass("oculto");
  });

  // Efecto cambio de iconos de eliminar registros.
  $(document).on("mouseenter",".scrollContent tr td #eliminar_0", function () {
      $(this).addClass("oculto");
      $(this).next("img").removeClass("oculto");
  });

  $(document).on("mouseleave",".scrollContent tr td #eliminar_1", function () {
      $(this).addClass("oculto");
      $(this).prev("img").removeClass("oculto");  
  });

  $(document).on("mouseleave",".scrollContent tr td:last-child", function () {
      $(this).find("#eliminar_1").addClass("oculto");
      $(this).find("#eliminar_0").removeClass("oculto");
  });

  $(document).on("mouseleave",".scrollContent tr", function () {
      $(this).find("#eliminar_1").addClass("oculto");
      $(this).find("#eliminar_0").removeClass("oculto");
  });

  //Eliminación de una reserva.
  $(document).on("click","div[id^='consulta_reservas'] .scrollContent tr #eliminar_1", function(event){
    event.preventDefault();
    profesor=$(this).closest("tr").attr("id");
    if(profesor=="centro"){
      profesor=null;
    }

    equipamiento=$(this).closest("tr").find("td:nth-child(2)").text();

    from=$(this).closest("tr").find("td:nth-child(3)").attr("data-order").split("/");
    fecha= from[2]+"-"+from[0]+"-"+from[1];
    fecha_aviso= from[1]+"-"+from[0]+"-"+from[2];   

    hora_inicio=$(this).closest("tr").find("td:nth-child(4)").attr("inicio");
    hora_fin=$(this).closest("tr").find("td:nth-child(4)").attr("fin");
    ini=hora_inicio+":00";
    fin=hora_fin+":00";

    nombre_profesor=$(this).closest("tr").find("td:nth-child(1)").text();

    aviso.play();
    swal({
      title: "Eliminación de reserva",
      html: "<table><p>Se va a eliminar la siguiente reserva: <br></p><thead><tr><th>Profesor</th><th>Equipamiento</th><th>Fecha</th><th>Módulo</th></tr></thead><tbody><tr><td>"+nombre_profesor+"</td><td>"+equipamiento+"</td><td>"+fecha_aviso+"</td><td>"+hora_inicio+" - "+hora_fin+"</td></tr></tbody></p></table><br><br>¿Estas seguro de continuar? No podrás deshacer este paso...<br>",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      width: "600px",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {
        $.ajax({
          type: 'POST',
          url: Routing.generate('obtener_reserva'),
          data: {profesor:profesor,equipamiento:equipamiento, ini:ini, fin:fin,fecha:fecha },
          dataType: 'json',
          success: function(response) {
            numReserva=response.num;
            
            $.ajax({
              type: 'DELETE',
              url: Routing.generate("reserva_delete", {id:numReserva}),
              success: function() {
                // Se actualiza las pestañas de consulta y reserva.   
                if(response.tipo=="Instalación"){
                  $("#consultar_instalaciones").update_tab();
                  $("#reservar_instalaciones").update_tab();
                }
                else{
                  $("#consultar_equipamientos").update_tab();
                  $("#reservar_equipamientos").update_tab();
                }
                if(profesor!=null){
                  alert("Se le menda un mensaje privado al profesor avisando de la eliminación de la reserva");
                }
              }
            })
          }
        })
        return false;
      }, function (dismiss) {

      }
    );
    //Se detiene la propagación del evento para que no detecte hacer click en el elemento "tr" que lo contiene.
    event.stopPropagation();   
  });

  ////////////////////////////////////////////
  //    Registro Nº de Alumnos en Cursos    //
  ////////////////////////////////////////////

  // Se quita la marca del input modificado y se actualiza el ratio.
  $(document).on('click',"#contenedor_ratio_grupos button",function(event){
    tr=$(this).closest("tr");
    tr.find("input").removeClass("modified");
    curso=tr.children('td').slice(0, 2).html();
    nivel=tr.children('td').slice(1, 2).html();
    ratio=tr.find("input").val();
    alumnos_asignados=tr.attr("ratio");

    if(parseInt(alumnos_asignados)>parseInt(ratio)){
      /*
      texto="<p class='justificado'>No se puede asignar la tutoría de este grupo al profesor seleccionado ya que no es el tutor del grupo.<br></p>"
      error.play();
      swal({
        title: "Asignación no permitida",
        html: texto,
        type: "error",
        width: "500px",
        confirmButtonColor: color,
        showCancelButton: false
      });
*/
      if($("#button_grupos_all").hasClass('clicked')){
        tr.find("td:nth-child(3) input").addClass('modified');
        tr.addClass('max');
      }
      else{
        texto="<p class='justificado'>No se puede asignar el número de alumnos indicado en el curso <strong class='negrita'>"+tr.find("td:first-child").text()+" de "+tr.find("td:nth-child(2)").text()+"</strong>, ya que hay un mayor número de alumnos matriculados en alguno de los grupos del curso.<br></p>"
        error.play();
        swal({
          title: "Asignación no permitida",
          html: texto,
          type: "error",
          width: "500px",
          confirmButtonColor: color,
          showCancelButton: false
        });
        tr.find("td:nth-child(3) input").addClass('modified');
      }

    }
    else{
      // Se actualiza el atributo ratio de la entidad Curso.
      $.ajax({
        type: 'POST',
        url: Routing.generate('asignar_ratio'),
        data: {curso:curso,nivel:nivel,ratio:ratio},
        success: function() {
          // Se actualiza el nuevo valor inicial.
          tr.find("input").attr("value",ratio);

          // Se actualiza la pestaña asignar grupo.
          $("#asignar_grupo").update_tab();
        }
      })
    }
  });

  // Se modifica el estilo de los input modificados.
  $(document).on("change","#contenedor_ratio_grupos input", function () {
    if($(this).val()!=$(this).attr("value")){
      $(this).addClass("modified");
    }
    else{
      $(this).removeClass("modified");
    }
  });

  // Se quita la marca de los input modificados y actualizan los ratios modificados.
  $(document).on('click',"#registro_ratio #button_grupos_all",function(event){
    //Se indica que se ha pulsado el botón de guardar todos para no mostrar los avisos de errores individuales.
    $(this).addClass('clicked');
    //Se elimina la clase "max" de cada curso por si se ha mostrado avisosde errores anteriores.
    $("#contenedor_ratio_grupos tr").removeClass('max');
    //Se llama al evento principal para que guarde individualmente el ratio modificado.
    $("#contenedor_ratio_grupos input[class='modified']").each(function(){  
      $(this).closest("tr").find("button").trigger("click");
    });

    //Se muestra los avisos de error si hay.
    if($("#contenedor_ratio_grupos tr[class='max']").size()>1){
      texto="<p class='justificado'>No se puede asignar el número de alumnos indicado en los siguientes cursos, ya que hay un mayor número de alumnos matriculados en alguno de los grupos del curso:<br><br></p>";
      $("#contenedor_ratio_grupos tr[class='max']").each (function(){ 
        texto=texto+"<p class='justificado negrita'>- "+$(this).find("td:first-child").text()+" de "+$(this).find("td:nth-child(2)").text()+"</p>";
      });
      
      error.play();
      swal({
        title: "Asignación no permitida",
        html: texto,
        type: "error",
        width: "500px",
        confirmButtonColor: color,
        showCancelButton: false
      });
      tr.find("td:nth-child(3) input").addClass('modified');
      $(this).removeClass('clicked');
    }
    else if($("#contenedor_ratio_grupos tr[class='max']").size()==1)
    {
      texto="<p class='justificado'>No se puede asignar el número de alumnos indicado en el curso <strong class='negrita'>"+tr.find("td:first-child").text()+" de "+tr.find("td:nth-child(2)").text()+"</strong>, ya que hay un mayor número de alumnos matriculados en alguno de los grupos del curso.<br></p>";
      
      error.play();
      swal({
        title: "Asignación no permitida",
        html: texto,
        type: "error",
        width: "500px",
        confirmButtonColor: color,
        showCancelButton: false
      });
      tr.find("td:nth-child(3) input").addClass('modified');
      $(this).removeClass('clicked');
    }
  });

  // Se restablece los valorea iniciales
  $(document).on("click","#registro_ratio #button_grupos_rest", function () {
      $("#ratio_curso").update_tab();
  });
  // Se eliminar el foco del elemento input al pulsar "Enter".
  $(document).on("keydown",'#registro_ratio input', function(e){
    if(e.keyCode == 13)
    {
      $(this).blur();
    }
  });

  ///////////////////////////////////////////
  //    Matricula de Antiguos Alumnos      //
  ////////////////////////////////// ////////

  // Se muestra la información del alumno.
  $(document).on("mouseenter","#consulta_antiguo_alumno .scrollContent tr", function () {

    // Se evita que se muestre el mensaje predeterminado si pasamos de un enlace a otro.
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #sin_seleccionar").addClass("oculto");
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #seleccionado").removeClass("oculto");

    
    tr=$(this);
    alumno=$(this).attr("id");

    $.ajax({
      type: 'POST',
      url: Routing.generate('datos_imparte'),
      data: {alumno:alumno},
      dataType: 'json',
      success: function(response) {
        tr.closest("div[class*='contenedor_registro']").find(".contenido_info #seleccionado").load(Routing.generate('datos_alumno', {id:tr.attr("id")}), function(){
        });
      }
    })
  });

  // Se elimina la información mostrada del alumno al quitar el puntero.
  $(document).on("mouseleave","#consulta_antiguo_alumno .scrollContent tr", function () {
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #seleccionado").addClass("oculto");
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #seleccionado").empty();
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #sin_seleccionar").removeClass("oculto");
  });


  // Se evita que se pueda hacer click en los registros que muestran los avisos predeterminados.
  $(document).on("mouseover"," .dataTable .scrollContent tr td", function () {
    if($(this).hasClass("dataTables_empty")){
      $(this).closest("tr").addClass("no_cursor");
    }
  });

  // Se modifica el select de búsqueda de antiguo alumno.
  $(document).on('change','#consulta_antiguo_alumno #lista_cursos select',function(event) {
    event.preventDefault();
    div=$(this).closest("div[class*='antiguo_alumno']");
    //Se reemplaza el nombre del select añadiendo también una separación antes del "de" para que funcione bien la búsqueda.
    curso=$(this).find("option:selected").text().replace(" de", "");

    //Se elimina el contenido del buscador cuando se selecciona un curso para la búsqueda.
    div.find("#buscador input").val(""); 
    div.find("#buscador input").keyup(); 

    id=1;
    //Se asigna el select correspondiente a la columna "último curso" según la tabla mostrada.
    if($(this).parent().attr("class")=="lista_activos"){
      id=1;
    }
    else{
      id=2;
    }

    valor=div.find("#lista_cursos select option:selected").val();
    // Se modifica el valor en las dos listas.
    if($(".lista_activos select option:selected").val()!= $(".lista_inactivos select option:selected").val()){
      $("#consulta_antiguo_alumno #lista_cursos select").val(valor).change();
    }
    // Se selecciona el option del select oculto con z-index para filtrar el curso.
    if(div.find("select[class='"+id+"'] option[value='"+curso+"']").length){
      div.find("#buscador input").prop("disabled",false);    
      // Se selecciona y se muestra con change().
      div.find("select[class='"+id+"']").val(curso).change();
    }
    else if($(this).find("option:selected").text()=="Todos los cursos"){
      div.find("#buscador input").prop("disabled",false);
      div.find("select[class='"+id+"']").val("").change();
    }
    else{
      div.find("tbody").empty();
      div.find("tbody").append("<tr class='odd no_cursor'><td class='dataTables_empty'>Actualmente no existe antiguos alumnos para el curso seleccionado</td></tr>");
      div.find("thead tr th").removeClass("sorting_asc");
      div.find("#buscador input").prop("disabled",true);
    }
    //Se cambia los estilos según el scroll vertical.
    if( div.find('table tbody').get(0).scrollHeight>div.find('table tbody').height()){
        div.find("table thead tr>th:last-child").attr('style', 'width: 10% !important');
    }
    else{
        div.find("table thead tr>th:last-child").attr('style', 'width: 9% !important');
    }
  });

  // Se muestra la información del alumno.(Forma General)
  $(document).on("mouseenter",".busqueda_general .scrollContent tr", function () {

    // Se evita que se muestre el mensaje predeterminado si pasamos de un enlace a otro.
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #sin_seleccionar").addClass("oculto");
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #seleccionado").removeClass("oculto");

    
    tr=$(this);
    alumno=$(this).attr("id");

    $.ajax({
      type: 'POST',
      url: Routing.generate('datos_imparte'),
      data: {alumno:alumno},
      dataType: 'json',
      success: function(response) {
        tr.closest("div[class*='contenedor_registro']").find(".contenido_info #seleccionado").load(Routing.generate('datos_alumno', {id:tr.attr("id")}), function(){
        });
      }
    })
  });

  // Se elimina la información mostrada del alumno al quitar el puntero.(Forma General)
  $(document).on("mouseleave",".busqueda_general .scrollContent tr", function () {
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #seleccionado").addClass("oculto");
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #seleccionado").empty();
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #sin_seleccionar").removeClass("oculto");
  });


  // Se modifica el select de búsqueda de las tablas de consultas. (Forma General)
  $(document).on('change','.busqueda_general #lista_cursos select',function(event) {
    div=$(this).closest(".busqueda_general");
    //Se añade también una separación antes del "de" para que funcione bien la búsqueda.
    curso=$(this).find("option:selected").text().replace(" de", "");
    valor=div.find("#lista_cursos select option:selected").val();

    //Se elimina el contenido del buscador cuando se selecciona un curso para la búsqueda.
    div.find("#buscador input").val(""); 
    div.find("#buscador input").keyup(); 

    //Se selecciona el option del select oculto con z-index para filtrar el curso.
    if(div.find("select[class='1'] option[value='"+curso+"']").length){
      div.find("#buscador input").prop("disabled",false);
      // Se selecciona y se muestra con change().
      div.find("select[class='1']").val(curso).change();
    }
    else if($(this).find("option:selected").text()=="Todos los cursos"){
      div.find("#buscador input").prop("disabled",false);
      div.find("select[class='1']").val("").change();
    }
    else{
      div.find("tbody").empty();
      div.find("tbody").append("<tr class='odd no_cursor'><td class='dataTables_empty'>Actualmente no existe alumnos para el curso seleccionado</td></tr>");
      div.find("thead tr th").removeClass("sorting_asc");
      div.find("#buscador input").prop("disabled",true);
    }
      //Se cambia los estilos según el scroll vertical.
    if( div.find('table tbody').get(0).scrollHeight>div.find('table tbody').height()){
      div.find("table thead tr>th:last-child").attr('style', 'width: 10% !important');
    }
    else{
      div.find("table thead tr>th:last-child").attr('style', 'width: 9% !important');
    }
  });

  //Se abre el div con la información del antiguo alumno para actualizar y matricular.
  $(document).on("click","div[id^='antiguo_alumno'] td", function(event){ 
    event.preventDefault();
    
    var div= $(this).closest("div[id^='tabs-']");
    //$(div).empty();
    // Se añade un gif para la espera de la carga del contenido actualizado.
    //$(div).html('<div class="ajaxload"><img src="/Symfony/web/bundles/backend/images/loading.gif"/></div>');

    $(div).load(Routing.generate('alumno_old_edit', {id:$(this).closest("tr").attr("id")}));
  });

  // Se habilita/deshabilita el botón enviar selecionados del contenedor correspodiente.
  $(document).on("change",".dataTable input:checkbox", function(event){ 
    cont=$(this).closest(".contenedor_registro");
    event.preventDefault();
    valor=0;
    cont.find("td input:checkbox").each (function(){ 
      // Se comprueba si hay algún registro seleccionado
      if( $(this).is(':checked') ) {
        cont.find(" #enviar_select button").prop("disabled", false);
        valor=1;
        return false;
      }
    });
    if(!valor){
      cont.find(" #enviar_select button").prop("disabled", true);
    }
  });

  //Se selecciona el checkbox del alumno si se da al input, y se registra la matrícula si se da al botón.
  $(document).on("click","#multiples_alumnos td", function(event){ 
    event.preventDefault();
    input=$(this).closest("tr").find("td input");

    if($(event.target).is('input')){
      //Retardo para poder mostar el input seleccionado.
      setTimeout(function(){
        if(input.is(':checked') ){
          input.prop("checked",false);
        }
        else{
          input.prop("checked",true);
        }
        // Se habilita/deshabilita el botón enviar selecionados.
        if( $("#multiples_alumnos td input").is(':checked') ) {
          $("#multiples_alumnos #enviar_select button").prop("disabled", false);
        } 
        else {
          $("#multiples_alumnos #enviar_select button").prop("disabled", true);
        }
      }, 20);
    }
    ///// Se envía la información para matricular al alumno. //////
    else if($(event.target).is('button')){
      id=$(this).closest("tr").attr("id");
      $.ajax({
        type: 'POST',
        url: Routing.generate('multiple_alumno_update', {id}),
        dataType: 'json',
      
        success: function(response) {

          if(response.validate){

            if(response.validate=="curso_incorrecto"){
              titulo="Matrícula cancelada";              
              texto= '<span>'+response.nombre+'</span> ya tiene superado el curso';
            }
            else if(response.validate=="año_incorrecto"){
              titulo="Matrícula cancelada";              
              texto= '<span>'+response.nombre+'</span> ya tiene una matrícula registrada en el Año Académico.';
            }
            else{
              titulo="Matrícula cancelada";              
              texto= 'No hay plazas vacantes en el curso <span>'+response.curso+'</span> para el alumno <span>'+response.nombre+'</span>.';
            }
            error.play();
            new PNotify({
              title:titulo,
              text:texto,
              addclass: "custom",
              type: "error",
              shadow: true,
              hide: true,
              buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
            return false;
          }
          // Se registra la matrícula una vez validado los datos.
          alumno=response.data;
          $.ajax({
            type: 'POST',
            url: Routing.generate('matricular_alumno'),
            data: {alumno:alumno},
            dataType: 'json',
            success: function(response) {

            //Se muestra aviso si se pulsa en enviar uno solo (input no checked)
            if(!input.is(':checked') ){
              exito.play();

              new PNotify({
                text:"Matrícula registrada.",
                addclass: "custom",
                type: "success",
                shadow: true,
                hide: true,
                buttons: {
                  sticker: false,
                  labels:{close: "Cerrar"}
                },
                stack: right_Stack,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              }); 
            }
              // Actualización de pestañas.
              $("#alumnos_antiguo").update_tab();
              //Se actualiza la pestaña de asignar grupos si está abierta y tiene seleccionado el curso actualizado.
              if($("#asignar_grupos #lista_cursos select option:selected").attr("value")==response.curso){
                $("#asignar_grupo").update_tab();   
              }
              $("#anular_matricula").update_tab();
              $("#asignar_optativa").update_tab();
              $("#ficha_alumno").update_tab();


              div=input.closest("div[id^='tabs-']");
              $(div).load(Routing.generate('search_multiple'));
            }
          })
        } 
      })
    }
    else{
      if(input.is(':checked') ){
        input.prop("checked",false);
      }
      else{
        input.prop("checked",true);
      }
              // Se habilita/deshabilita el botón enviar selecionados.
        valor=0;
        $("#multiples_alumnos td input:checkbox").each (function(){ 
          // Se comprueba si hay algún registro seleccionado
          if( $(this).is(':checked') ) {
            $("#multiples_alumnos #enviar_select button").prop("disabled", false);
            valor=1;
            return false;
          }
        });
        if(!valor){
          $("#multiples_alumnos #enviar_select button").prop("disabled", true);
        }
    }
           
  });

  // Se matricula los alumnos seleccionados.
  $(document).on("click","#multiples_alumnos #enviar_select button", function(event){ 

    // Se añade un gif para la espera de la carga del contenido actualizado.
    $("#multiples_alumnos #loading").html('<div class="ajaxload"><img src="/Symfony/web/bundles/backend/images/loading.gif"/></div>');

    num_matriculas_inicial=0;
    contador=0;

    $.ajax({
      type: 'POST',
      url: Routing.generate('num_matriculas'),
      data: {},
      dataType: 'json',
      success: function(response) {
        // Se actualiza la lista de antiguos alumnos.
        num_matriculas_inicial=response.matriculas;
      }
    })

    $("#multiples_alumnos table tbody input:checkbox:checked").each(function() {
        $(this).closest("tr").find("button").trigger('click');
        contador++;
    });

    setTimeout(function(){
      //Se quita el incono de loading.
      $("#multiples_alumnos #loading").empty();
      num_matriculas=0;

      $.ajax({
        type: 'POST',
        url: Routing.generate('num_matriculas'),
        data: {},
        dataType: 'json',
        success: function(response) {
          // Se actualiza la lista de antiguos alumnos.
          num_matriculas=response.matriculas;
          matriculas_registradas= parseInt(num_matriculas) - parseInt(num_matriculas_inicial);
          if(matriculas_registradas){
            exito.play();
            if(contador>1){
              texto=matriculas_registradas+" / "+contador+" matrículas registradas.";
            }
            else if(contador==1 && matriculas_registradas==1 ){
              texto="Matrícula registrada.";
            }
            new PNotify({
              text:texto,
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            }); 
          }
          else{
            errorPNotify.play();

             new PNotify({
              text:'No se ha registrado ninguna matrícula',
              addclass: "custom",
              type: "error",
              shadow: true,
              hide: true,
              buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
          }
        }
      })
    }, 1000);
  });

  // Se restablece el valor inicial del formulario del antiguo alumno.
  $(document).on("click","#antiguo_alumno_rest", function(event){ 
    event.preventDefault();
    alum=$(this).closest("form").attr("alum");
    
    var div= $(this).closest("div[id^='tabs-']");

    $(div).load(Routing.generate('alumno_old_edit', {id:alum}));
  });

  // Se vuelve a la lista de Antiguos Alumnos Activos.
  $(document).on('click',"#antiguo_alumno_buscar", function(event){
    event.preventDefault();
    // Se cierra las notificaciones.
    PNotify.removeAll();

    div=$(this).closest("div[id^='tabs-']");
    $(div).load(Routing.generate('search_old'));
  });

  //Se guarda el formulario antiguo alumno editado con los nuevos datos de matriculación.
  $(document).on("submit","#antiguo_alumno_edit",function(event) {
    event.preventDefault();
    form= $(this).closest("form");

    var val=0;
    //Se comprueba si la lista de curso tiene un valor seleccionado.
    if( $("#antiguo_alumno_edit #lista_cursos").length>0 && $("#antiguo_alumno_edit #lista_cursos select").val()==null){
      $("#antiguo_alumno_edit #lista_cursos select").attr("validated",false);
      $("#antiguo_alumno_edit #lista_cursos select").addClass("invalid");
      $("#antiguo_alumno_edit #lista_cursos select").after("<span class='mensaje' style='display: none;'>Debe seleccionar un curso</span>");
      val=1;
    }
    else{
      $("#antiguo_alumno_edit #lista_cursos select").attr("validated",true);
    }

    // Se recorre los campos del formulario mirando si estan validados o no.
    form.find(":input[type!='file']").each(function(){
      //Si no tiene segundo responsable se asigna true al valor de validated para que sean aceptados en la validación.
      if( $(this).closest("div[id='responsable']").hasClass("oculto")){
        $(this).attr("validated",true);
      }
      if(!$(this).attr("validated") || $(this).attr("validated")==false){
        if($(this).attr("validation")){
          validation($(this));
        }
      }
    });

    //":input"añade a los input radio,select...
    form.find(":input").each(function(){
      if(($(this).attr("validated")=="false")) {
        //Se muestra el input inválido.
        $(this).focus();
        val=1;
        return false;
      }       
    });

    var estado= "";

    if(!form.find("#actual").hasClass("oculto")){
      estado= "actual";
    }
    else if(!form.find("#actualizada").hasClass("oculto")){
      estado= "actualizado";
    }
    else{
      estado= "eliminado";
    }
    
    // Se asigna el valor del curso a matricular.
    if(form.find("#c_old_student_active").length){
      var curso=form.find("#c_old_student_active input").attr("id");
    }
    else{
      var curso=form.find("#lista_cursos select").val();
    }
    
    var formdata=new FormData($(this)[0]);
    formdata.append('estado', estado);
    formdata.append('curso', curso);

    if(val==0){
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formdata, 
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {

          if(response.validate){
            if(response.validate=="curso_incorrecto"){
              titulo="Matrícula cancelada";              
              texto= '<span class="negrita">'+response.nombre+'</span> ya tiene superado este curso.';
            }
            else if(response.validate=="año_incorrecto"){
              titulo="Matrícula cancelada";              
              texto= '<span class="negrita">'+response.nombre+'</span> ya tiene una matrícula registrada en el Año Académico actual.';
            }
            else{
              titulo="Matrícula cancelada";              
              texto= 'No hay plazas vacantes en el curso <span>'+response.curso+'</span> para el alumno <span>'+response.nombre+'</span>.';
            }
            error.play();
            swal({
              title: titulo,
              html: texto,
              type: "error",
              width: "500px",
              showCancelButton: false,
              confirmButtonColor: color
            });
              return false;
          }
          
          // Se registra la matrícula una vez validado los datos.
          alumno=response.data;
          $.ajax({
            type: 'POST',
            url: Routing.generate('matricular_alumno'),
            data: {alumno:alumno},
            dataType: 'json',
            success: function(response) {

              // Se actualiza la lista de multiples alumnos.
              $("#alumnos_multiple").update_tab();

              div=form.closest("div[id^='tabs-']");
              $(div).load(Routing.generate('search_old'), function(responseTxt, statusTxt, xhr){
                if(statusTxt == "success"){
                  // Notificación de confirmación
                  exito.play();
              
                  new PNotify({
                    text:"Alumno Matriculado",
                    addclass: "custom",
                    type: "success",
                    shadow: true,
                    hide: true,
                    buttons: {
                    sticker: false,
                    labels:{close: "Cerrar"}
                    },
                    stack: right_Stack,
                    animate: {
                      animate: true,
                      in_class: "fadeInRight",
                      out_class: "fadeOutRight",
                    }
                  });

                  // Actualización de pestañas.
                  $("#alumnos_antiguo").update_tab();
                  //Se actualiza la pestaña de asignar grupos si está abierta y tiene seleccionado el curso actualizado.
                  if($("#asignar_grupos #lista_cursos select option:selected").attr("value")==response.curso){
                    $("#asignar_grupo").update_tab();   
                  }
                  $("#anular_matricula").update_tab();
                  $("#asignar_optativa").update_tab();
                  $("#ficha_alumno").update_tab();
                }

                if(statusTxt == "error")
                  alert("Error: " + xhr.status + ": " + xhr.statusText);
              });
            }
          })
        } 
      })
    }
  });

  $(document).on('change',"#antiguo_alumno_edit #lista_cursos select",function(event){
    event.preventDefault();
    if($(this).val()!=""){
      $(this).removeClass("invalid");
      $(this).next().remove();
      $("#antiguo_alumno_edit #lista_cursos select").attr("validated",true);
    }
    else{
      $("#antiguo_alumno_edit #lista_cursos select").attr("validated",false);
      $("#antiguo_alumno_edit #lista_cursos select").addClass("invalid");
      $("#antiguo_alumno_edit #lista_cursos select").after("<span class='mensaje' style='display: none;'>Debe seleccionar un curso</span>");
    }
    comprobarSelect($("#antiguo_alumno_edit"));
  });

  $(document).on('blur',"#antiguo_alumno_edit #lista_cursos select",function(event){
    event.preventDefault();
    if($(this).val()!=""){
      $(this).removeClass("invalid");
      $(this).next().remove();
      $("#antiguo_alumno_edit #lista_cursos select").attr("validated",true);
    }
    else{
      $("#antiguo_alumno_edit #lista_cursos select").attr("validated",false);
      $("#antiguo_alumno_edit #lista_cursos select").addClass("invalid");
      $("#antiguo_alumno_edit #lista_cursos select").after("<span class='mensaje' style='display: none;'>Debe seleccionar un curso</span>");
    }
  });
  // Se muestra la lista de antiguos alumnos activos.
  $(document).on('click',"#consulta_antiguo_alumno #btn_activos",function(event){
    $("#antiguo_alumno").addClass("oculto");
    $("#antiguo_alumno_activo").removeClass("oculto");

    //Se cambia los estilos según el scroll vertical.
    if( $("#antiguo_alumno_activo").find('table tbody').get(0).scrollHeight>$("#antiguo_alumno_activo").find('table tbody').height()){
        $("#antiguo_alumno_activo").find("table thead tr>th:last-child").attr('style', 'width: 10.5% !important');
    }
    else{
        $("#antiguo_alumno_activo").find("table thead tr>th:last-child").attr('style', 'width: 9% !important');
    }
  });

  // Se muestra la lista de antiguos alumnos inactivos.
  $(document).on('click',"#consulta_antiguo_alumno #btn_inactivos",function(event){
    $("#antiguo_alumno_activo").addClass("oculto");
    $("#antiguo_alumno").removeClass("oculto");

    //Se cambia los estilos según el scroll vertical.
    if( $("#antiguo_alumno").find('table tbody').get(0).scrollHeight>$("#antiguo_alumno").find('table tbody').height()){
        $("#antiguo_alumno").find("table thead tr>th:last-child").attr('style', 'width: 10% !important');
    }
    else{
        $("#antiguo_alumno").find("table thead tr>th:last-child").attr('style', 'width: 9% !important');
    }
  });

  // Marcar o desmarcar todos los registros de alumnos mostrados en la lista.
  $(document).on('click',"#checkbox-all",function(event){
    if($("#checkbox-all").is(':checked') ){
      $(this).closest("table").find("tbody td input").each (function(){ 
          $(this).prop("checked",true);
      });
    }
    else{
      $(this).closest("table").find("tbody td input").each (function(){ 
          $(this).prop("checked",false);
      });
    }
  });

  ///////////////////////////////////////////
  //            Anular Matrícula           //
  ///////////////////////////////////////////

  // Se modifica el número de matrículas registradas en la tabla de anular matrículas.
  $(document).on("change","#anular_matricula #lista_cursos select", function(event){
    event.preventDefault();
    if($("#anular_matricula #lista_cursos select").val()==0){
      $("#anular_matricula #texto").empty();
      $("#anular_matricula #texto").append('<p>Total de Matrículas</p>');
      $("#anular_matricula #num_matriculas p").text($("#anular_matricula #matriculas tbody tr").size());
    }
    else{
      texto=$("#anular_matricula #lista_cursos select option[value='"+$("#anular_matricula #lista_cursos select").val()+"']").text();
      $("#anular_matricula #texto").empty();
      $("#anular_matricula #texto").append('<p>Matrículas de</p><p class="bold">'+texto+'</p>');
      $("#anular_matricula #num_matriculas p").text($("#anular_matricula #matriculas tbody tr:not(.no_cursor)").size());
    }
  });
  
  // Se elimina la matrícula seleccionada.
  $(document).on("click","#anular_matricula td", function(event){
    event.preventDefault();
    id=$(this).closest("tr").attr("id");
    curso=$(this).closest("tr").find("td:nth-child(2)").attr("id");

    aviso.play();
    swal({
      title: "Anulación de Matrícula",
      html: "<table><p>Se va a eliminar la siguiente matrícula, ¿Estas seguro de continuar? <br></p><thead><tr><th>Alumno</th><th>Curso Matriculado</th><th>Año Académico</th></tr></thead><tbody><tr><td>"+$(this).closest("tr").find("td:nth-child(1)").text()+"</td><td>"+$(this).closest("tr").find("td:nth-child(2)").text()+"</td><td>"+$(this).closest("tr").find("td:nth-child(3)").text()+"</td></tr></tbody><br></p></table>",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      width: "600px",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        $.ajax({
          type: 'POST',
          url: Routing.generate('anular_matricula'),
          data: {id:id},
          dataType: 'json',
          success: function(response) {

            // Notificación de confirmación
            exito.play();
            
            new PNotify({
              text:"Matrícula Anulada.",
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
            //Actualización de pestañas.
            $("#anular_matricula").update_tab();
            $("#alumnos_antiguo").update_tab();
            $("#asignar_optativa").update_tab();
            $("#ficha_alumno").update_tab();
            $("#alumnos_multiple").update_tab();

            //Se actualiza la pestaña de asignar grupos si está abierta y tiene seleccionado el curso actualizado.
            if($("#asignar_grupos #lista_cursos select option:selected").attr("value")==curso){
              $("#asignar_grupo").update_tab();   
            }
          }
        })
      }, function (dismiss) {

      }
    );
  });

  ///////////////////////////////////////////
  //           Antiguos profesores         //
  ///////////////////////////////////////////

  $(document).on('click',"#consulta_antiguo_profesor #old_teacher tbody td",function(event){
    event.preventDefault();
    input=$(this).closest("tr").find("td:last-child input");
    //Se desactiva seleccionar cuando hay selección en la otra tabla.
    if(input.is(":disabled")){
      return false;
    }

    if(!$(event.target).is('input')){

      if(input.is(':checked') ){
        input.prop("checked",false);
      }
      else{
        input.prop("checked",true);
      }
    }
    else{
      //Retardo para poder mostar el input seleccionado.
      setTimeout(function(){
        if(input.is(':checked') ){
          input.prop("checked",false);
        }
        else{
          input.prop("checked",true);
        }
      }, 5);
    }
    // Se habilita/deshabilita el botón enviar selecionados.
    if( $("#consulta_antiguo_profesor #old_teacher td input").is(':checked') ) {
      $("#consulta_antiguo_profesor #baja").addClass("oculto");
      $("#consulta_antiguo_profesor .baja_active").removeClass("oculto");
      $("#consulta_antiguo_profesor #alta").addClass("oculto");
      $("#consulta_antiguo_profesor .alta_limpiar").removeClass("oculto");
      $("#consulta_antiguo_profesor #old_teacher_no_active tbody td input").each (function(){ 
        $(this).prop("disabled",true);
      });
    } 
    else {
      $("#consulta_antiguo_profesor #baja").addClass("oculto");
      $("#consulta_antiguo_profesor .baja_disable").removeClass("oculto");
      $("#consulta_antiguo_profesor #alta").addClass("oculto");
      $("#consulta_antiguo_profesor .alta_disable").removeClass("oculto");
      $("#consulta_antiguo_profesor #old_teacher_no_active tbody td input").each (function(){ 
        $(this).prop("disabled",false);
      });
    }
  });


  $(document).on('click',"#consulta_antiguo_profesor #old_teacher_no_active tbody td",function(event){
    event.preventDefault();
    input=$(this).closest("tr").find("td:first-child input");
    
    //Se desactiva seleccionar cuando hay selección en la otra tabla.
    if(input.is(":disabled")){
      return false;
    }

    if(!$(event.target).is('input')){

      if(input.is(':checked') ){
        input.prop("checked",false);
      }
      else{
        input.prop("checked",true);
      }
    }
    else{
      //Retardo para poder mostar el input seleccionado.
      setTimeout(function(){
        if(input.is(':checked') ){
          input.prop("checked",false);
        }
        else{
          input.prop("checked",true);
        }
      }, 5);
    }
    // Se habilita/deshabilita el botón enviar selecionados.
    if( $("#consulta_antiguo_profesor #old_teacher_no_active td input").is(':checked') ) {
          $("#consulta_antiguo_profesor #alta").addClass("oculto");
          $("#consulta_antiguo_profesor .alta_active").removeClass("oculto");
          $("#consulta_antiguo_profesor #baja").addClass("oculto");
          $("#consulta_antiguo_profesor .baja_limpiar").removeClass("oculto");
      $("#consulta_antiguo_profesor #old_teacher tbody td input").each (function(){ 
        $(this).prop("disabled",true);
      });
    } 
    else {
          $("#consulta_antiguo_profesor #alta").addClass("oculto");
          $("#consulta_antiguo_profesor .alta_disable").removeClass("oculto");
          $("#consulta_antiguo_profesor #baja").addClass("oculto");
          $("#consulta_antiguo_profesor .baja_disable").removeClass("oculto");
      $("#consulta_antiguo_profesor #old_teacher tbody td input").each (function(){ 
        $(this).prop("disabled",false);
      });
    }
  });
  //Se limpia la selección al pulsar el botón correspondiente.
  $(document).on("click","#consulta_antiguo_profesor .baja_limpiar button", function () {
    $("#consulta_antiguo_profesor #old_teacher_no_active tbody td input:checked").each (function(){ 
       //Se realiza el evento en el td ya que en input haría click dos veces.
       $(this).closest("td").click();
    });
  });

  $(document).on("click","#consulta_antiguo_profesor .alta_limpiar button", function () {
    $("#consulta_antiguo_profesor #old_teacher tbody td input:checked").each (function(){ 
      $(this).closest("td").click();
    });
  });

  //Se muestra la info del profesor al colocar el cursor.
  $(document).on("mouseenter","#consulta_antiguo_profesor .scrollContent tr", function(){
    // Se evita que se muestre el mensaje predeterminado si pasamos de un enlace a otro.
    $("#consulta_antiguo_profesor .contenido_info #sin_seleccionar").addClass("oculto");

    //Se comprueba que no es un aviso predeterminado.
    if(!$(this).find("td").hasClass("dataTables_empty")){

      $("#consulta_antiguo_profesor .contenido_info #seleccionado").load(Routing.generate('datos_antiguo_profesor', {id:$(this).attr("id")}), function(){
        $("#consulta_antiguo_profesor .contenido_info #seleccionado").removeClass("oculto");
        $("#consulta_antiguo_profesor .contenido_info #sin_seleccionar").addClass("oculto");     
      });
    }
    else{
      $("#consulta_antiguo_profesor .contenido_info #seleccionado").empty();
      $("#consulta_antiguo_profesor .contenido_info #seleccionado").addClass("oculto");
      $("#consulta_antiguo_profesor .contenido_info #sin_seleccionar").removeClass("oculto");
    }
  });

  // Se elimina la información mostrada del profesor al quitar el puntero.
  $(document).on("mouseleave","#consulta_antiguo_profesor .scrollContent tr", function () {
    $("#consulta_antiguo_profesor .contenido_info #seleccionado").addClass("oculto");
    $("#consulta_antiguo_profesor .contenido_info #seleccionado").empty();
    $("#consulta_antiguo_profesor .contenido_info #sin_seleccionar").removeClass("oculto");
  });

  // Se elimina la información con retraso al salir de la tabla por si aún se estan cargando datos anteriores.
  $(document).on("mouseleave","#old_teacher", function () {
    setTimeout(function() {
      $("#consulta_antiguo_profesor .contenido_info #seleccionado").addClass("oculto");
      $("#consulta_antiguo_profesor .contenido_info #seleccionado").empty();
      $("#consulta_antiguo_profesor .contenido_info #sin_seleccionar").removeClass("oculto");
    }, 300);
  });

  //Se realiza la nueva alta del profesor.
  $(document).on("click","#consulta_antiguo_profesor .alta_active button", function(event) {
    event.preventDefault();
    var array= Array(); 
    $("#consulta_antiguo_profesor #old_teacher_no_active tbody td input:checked").each (function(){ 
      //alert($(this).closest("tr").find("#nivel").text());
      array.push($(this).closest("tr").attr("id"));
    });

    if(array.length==1){
      nombre="";
      nivel="";
    $("#consulta_antiguo_profesor #old_teacher_no_active tbody td input:checked").each (function(){ 
      nombre=$(this).closest("tr").find("td:nth-child(2)").text();
      nivel=$(this).closest("tr").find("#nivel").text();
    });

      //titulo="<table><p>Se va a registrar el alta del antiguo profesor X, ¿Estas seguro de continuar? <br></p><thead><tr><th>Evento</th><th>Fecha</th><th>Hora</th></tr></thead><tbody><tr><td>"+titulo+"</td><td>"+fecha+"</td><td>"+hora+"</td></tr></tbody><br></p></table>",
      titulo="<table><p>Se va a registrar el alta del antiguo profesor:<br></p><thead><tr><th>Nombre</th><th>Nivel</th></tr></thead><tbody><tr><td>"+nombre+"</td><td>"+nivel+"</td></tr></tbody><br></p></table><br>¿Estas seguro de continuar?";
    }
    else{
      titulo="<table ><p>Se va a registrar el alta de los antiguos profesores:<br></p><thead><tr><th>Nombre</th><th>Nivel</th></tr></thead><tbody>";
      $("#consulta_antiguo_profesor #old_teacher_no_active tbody td input:checked").each (function(){ 
        nombre=$(this).closest("tr").find("td:nth-child(2)").text();
        nivel=$(this).closest("tr").find("#nivel").text();
        titulo+="<tr><td>"+nombre+"</td><td>"+nivel+"</td></tr>";
       });
      titulo+="</tbody><br></p></table><br>¿Estas seguro de continuar?";
    }

    aviso.play();
    swal({
      title: "Alta de Profesores",
      html: titulo,
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      width: "500px",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        $.ajax({
          type: 'POST',
          url: Routing.generate('alta_profesor'),
          data: {array:array},
          dataType: 'json',
          success: function(response) {
            if(array.length==1){
              texto="Alta registrada.";
            }else{
              texto=array.length+" altas registradas.";
            }

            // Notificación de confirmación
            exito.play();
            
            new PNotify({
              text:texto,
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });

            // Se actualiza todas las pestañas con tablas de profesores.
            $("#profesor_antiguo").update_tab();
            $("#ficha_profesor").update_tab();
            $("#consultar_equipamientos").update_tab();
            $("#consultar_instalaciones").update_tab();
            $("#tutor_grupo").update_tab();
            $("#profesor_asignar_grupo").update_tab();
            $("#clases_impartidas").update_tab();
            $("#equipo_directivo").update_tab();
            $("#clases_impartidas").update_tab();
          }
        })
      }, function (dismiss) {

      }
    );
  });

  //Se realiza la baja del profesor.
  $(document).on("click","#consulta_antiguo_profesor .baja_active button", function(event)  {
    event.preventDefault();
    var array= Array(); 
    $("#consulta_antiguo_profesor #old_teacher tbody td input:checked").each (function(){ 
      //alert($(this).closest("tr").find("#nivel").text());
      array.push($(this).closest("tr").attr("id"));
    });

    if(array.length==1){
      nombre="";
      nivel="";
    $("#consulta_antiguo_profesor #old_teacher tbody td input:checked").each (function(){ 
      nombre=$(this).closest("tr").find("td:nth-child(2)").text();
      nivel=$(this).closest("tr").find("#nivel").text();
    });

      //titulo="<table><p>Se va a registrar el alta del antiguo profesor X, ¿Estas seguro de continuar? <br></p><thead><tr><th>Evento</th><th>Fecha</th><th>Hora</th></tr></thead><tbody><tr><td>"+titulo+"</td><td>"+fecha+"</td><td>"+hora+"</td></tr></tbody><br></p></table>",
      titulo="<table><p>Se va a registrar la baja del siguiente profesor:<br></p><thead><tr><th>Nombre</th><th>Nivel</th></tr></thead><tbody><tr><td>"+nombre+"</td><td>"+nivel+"</td></tr></tbody><br></table><br><br><span>AVISO:</span><span> Si el profesor está asignado a un grupo o es tutor, se eliminará automáticamente esa asignación y no se podrá recuperar.</span><br><br>¿Estas seguro de continuar?";
    }
    else{
      titulo="<table><p>Se va a registrar la baja de los siguientes profesores:<br></p><thead><tr><th>Nombre</th><th>Nivel</th></tr></thead><tbody>";
      $("#consulta_antiguo_profesor #old_teacher tbody td input:checked").each (function(){ 
        nombre=$(this).closest("tr").find("td:nth-child(2)").text();
        nivel=$(this).closest("tr").find("#nivel").text();
        titulo+="<tr><td>"+nombre+"</td><td>"+nivel+"</td></tr>";
       });
      titulo+="</tbody><br></p></table><br><br><p class='justificado'>AVISO: Si algún profesor está asignado a un grupo o es tutor, se eliminará automáticamente esa asignación y no se podrá recuperar.</p><br>¿Estas seguro de continuar?";
    }

    aviso.play();
    swal({
      title: "Baja de Profesores",
      html: titulo,
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      width: "500px",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {
        $.ajax({
          type: 'POST',
          url: Routing.generate('baja_profesor'),
          data: {array:array},
          dataType: 'json',
          success: function(response) {
            if(array.length==1){
              texto="Baja registrada.";
            }else{
              texto=array.length+" bajas registradas.";
            }

            // Notificación de confirmación
            exito.play();
            
            new PNotify({
              text:texto,
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });

            // Se actualiza todas las pestañas con tablas de profesores.
            $("#profesor_antiguo").update_tab();
            $("#ficha_profesor").update_tab();
            $("#consultar_equipamientos").update_tab();
            $("#consultar_instalaciones").update_tab();
            $("#tutor_grupo").update_tab();
            $("#profesor_asignar_grupo").update_tab();
            $("#clases_impartidas").update_tab();
            $("#equipo_directivo").update_tab();
            $("#clases_impartidas").update_tab();
          }
        })
      }, function (dismiss) {

      }
    );
  });

  ///////////////////////////////////////////
  //            Asignar Grupos             //
  ///////////////////////////////////////////

  $(document).on('change',"#asignar_grupos #lista_cursos select",function(event){
    event.preventDefault();
    div= $(this).closest("div[id^='tabs']");
    curso=$(this).find("option:selected").val();
     // Se añade un gif para la espera de la carga del contenido actualizado.
    $("#asignar_grupos #loading").html('<div class="ajaxload"><img src="/Symfony/web/bundles/backend/images/loading.gif"/></div>');
    div.load(Routing.generate('curso_asignar_grupo', {curso:curso}), function(){
      //Se muestra el select cuando se devuelve un alumno nuevo al contenedor inicial. 
      if($("#asignar_grupos #contenedor_cursos li").size()>0){
        $("#asignar_grupos #btn_generar button").prop("disabled",false);
      }
      else{
        $("#asignar_grupos #btn_generar button").prop("disabled",true);
      } 
    });

  });

  //Se restablece la listas del curso seleccionado.
  $(document).on('click',"#asignar_grupos #button_grupos_rest",function(event){
      $("#asignar_grupos #lista_cursos select option:selected").change();
  });


  //Se actualiza el grupo a todos los alumnos del curso seleccionado.
  $(document).on('click',"#asignar_grupos #button_grupos_all",function(event){
    event.preventDefault();
    div= $(this).closest("div[id^='tabs']");
    var asignaciones = new Object();
    var index = 1;
    $("#asignar_grupos #contenedor_asignar_grupos").each(function(){ 
      letra=$(this).find("ol").attr("id").replace("grupo_","");
      orden=1;
      $(this).find("ol li").each(function(){ 
          alumno=$(this).attr("id").replace("curso-","");
          
          asignaciones[index++]=[alumno, orden, letra];  

          orden++;
      });
    });

    //Se avisa si no existe cambios.
    if($.isEmptyObject(asignaciones)){
      $(".ui-pnotify").remove();

      errorPNotify.play();

      new PNotify({
        text:'No se ha asignado ningún alumno a los grupos.',
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        stack: right_Stack,
        animate: {
          animate: true,
          in_class: "fadeInRight",
          out_class: "fadeOutRight",
        }
      });
      return false;
    }

    $.ajax({
      type: 'POST',
      url: Routing.generate('asignar_grupo_update'),
      data: {asignaciones:asignaciones},
      dataType: 'json',
      success: function(response) {
        // Notificación de confirmación
        exito.play();
        if($("#asignar_grupos #contenedor_asignar_grupos:not(.container_disabled)").size()>1){
          texto="Grupos Asignados";
        }
        else{
          texto="Grupo Asignado";
        }

        new PNotify({
          text:texto,
          addclass: "custom",
          type: "success",
          shadow: true,
          hide: true,
          buttons: {
            sticker: false,
            labels:{close: "Cerrar"}
          },
            stack: right_Stack,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
        });
        div.load(Routing.generate('asignar_grupo'));
        
        //Actualización de pestañas.
        $("#ficha_alumno").update_tab();
        $("#asignar_optativa").update_tab();

      },
      error: function (response, desc, err){
        error.play();
        swal({
          title: "Error en el sistema",
          html: "Se ha producido un error en el sistema, por favor cierra la pestaña <span class='negrita'>Asignar Grupos</span> y vuelva a intentarlo de nuevo.",
          type: "error",
          showCancelButton: false,
          confirmButtonColor: color
        });
      }
    })
  });


  ///////////////////////////////////////////
  //      Asignar tutor a los grupos       //
  ///////////////////////////////////////////


  $(document).on("click"," #tutor_grupos #one",function(event){
    event.preventDefault();

    $("#tutor_grupos #grupos_infantil").removeClass('oculto');
    $("#tutor_grupos #grupos_primaria").addClass('oculto');
  });


  $(document).on("click","#tutor_grupos #two",function(event){
    event.preventDefault();

    $("#tutor_grupos #grupos_infantil").addClass('oculto');
    $("#tutor_grupos #grupos_primaria").removeClass('oculto');
  });

  //Se elimina una asignación de tutor.
  $(document).on("click","#tutor_grupos #lista_grupos img",function(event){
    event.preventDefault();

    id=$(this).prev().find("li").attr("id");
    //Se pasa la lista a su posición inicial en la tabla de profesores y se muestra la lista que estaba oculta.
    // $('#tutor_grupos .lista_profesores tr[id="'+id+'"] ul').append($(this).prev().find("li"));
    $(this).prev().find("li").appendTo('#tutor_grupos .lista_profesores tr[id="'+id+'"] ul');
    $('#tutor_grupos .lista_profesores tr[id="'+id+'"]').removeClass('oculto');
    //Se quita el estilo a la lista en la tabla de tutores aisgnados y ocultamos la imagen en esa lista. 
    $(this).closest('td').removeClass('placeholder_list_table_td_mod');
    $(this).addClass('oculto');
    //Se elimina el título que se le dio a la nueva asignación.
    $(this).closest('td').attr('title',"");
    //Se añade de nuevo la clase "sortable" a la fila para que se pueda realizar otra asignación.
    $(this).closest('td').find("ul").addClass('sortable');

    //Se elimina la clase de asignado por modificado y se cambia el título.
    $(this).closest("td").removeClass('back_asignado');
    if($(this).closest('td').attr("value")!=""){
      $(this).closest("td").attr('title', 'Tutor Eliminado');
      $(this).closest("td").addClass('eliminado');
    }
    else{
      $(this).closest("td").removeClass('back_modificado');
    }

    //Se comprueba si hay modificación para mostrar los botones.
    $("#tutor_grupos #tutor_guardar").prop("disabled",true);
    $("#tutor_grupos #tutor_rest").prop("disabled",true);

    if($("#tutor_grupos #lista_grupos td").hasClass('back_modificado') || $("#tutor_grupos #lista_grupos td").hasClass('eliminado') ){
      $("#tutor_grupos #tutor_guardar").prop("disabled",false);
      $("#tutor_grupos #tutor_rest").prop("disabled",false);
    }
  });

  $(document).on("click","#tutor_grupos #tutor_rest",function(event){
    event.preventDefault();
    nivel= $("#tutor_grupos .head_change label[class='']").attr("id");

    div=$(this).closest("div[id^='tabs-']");
    $(div).load(Routing.generate("tutor_grupo"), function(){
      if(nivel=="one"){
        $("#tutor_grupos #one").click();
      }
      else{
        $("#tutor_grupos #two").click();
      }
    });
  });

  $(document).on("click","#tutor_grupos #tutor_guardar",function(event){

    event.preventDefault();

    var asignaciones = new Object();
    var eliminados = Array();
    var1=0;
    var2=0;
    //Se obtiene los grupos y profesores donde se ha asignado un tutor.(Insertar/Actualizar)
    $("#tutor_grupos #lista_grupos td[class*='placeholder_list_table_td_mod']").each(function(){
      grupo=$(this).closest("tr").attr("grupo");
      id=$(this).find("li").attr("id");

      asignaciones[grupo] = id;
      var1++;
    });


    // Se obtienen los grupos cuyos tutores han sido eliminados.(Eliminar)
    $("#tutor_grupos #lista_grupos .eliminado").each(function(){
      grupo=$(this).closest("tr").attr("grupo");

      if($(this).find("li").size()==0){
        eliminados.push(grupo);
        var2++;
      }
    });

    //Se avisa si no existe cambios.
    if($.isEmptyObject(asignaciones) && $.isEmptyObject(eliminados)){
      $(".ui-pnotify").remove();

      errorPNotify.play();

      new PNotify({
        text:'No se ha modificado ningún tutor en los grupos.',
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        stack: right_Stack,
        animate: {
          animate: true,
          in_class: "fadeInRight",
          out_class: "fadeOutRight",
        }
      });
      return false;
    }
    div=$(this).closest("div[id^='tabs-']");
    $.ajax({
      type: 'POST',
      url: Routing.generate('asignar_tutor_grupo'),
      data: { asignaciones:asignaciones, eliminados:eliminados},
      dataType: 'json',
      success: function(response) {
        if(response.data==null){
          errorPNotify.play();

          new PNotify({
            text:'No se ha modificado ningún tutor en los grupos.',
            addclass: "custom",
            type: "error",
            shadow: true,
            hide: true,
            buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
            },
            stack: right_Stack,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
          });
          return false;
        }
        texto="";
        if(var1==1){
          texto+=" <span>"+var1+" Tutor <strong>registrado</strong><span>";
        }
        else if(var1>1){
          texto+=" <span>"+var1+" Tutores <strong>registrados</strong><span>";
        }

        if(var2==1){
          texto+=" <span>"+var2+" Tutor <strong>eliminado</strong><span>";
        }
        else if(var2>1){
          texto+=" <span>"+var2+" Tutores <strong>eliminados</strong><span>";
        }
        exito.play();
        new PNotify({
          text:texto,
          addclass: "custom",
          type: "success",
          shadow: true,
          hide: true,
          animation: "fade",
          animate_speed: 'fast',
          delay: 4000,
          buttons: {
            sticker: false,
            labels:{close: "Cerrar"}
          },
          stack: right_Stack,
          animate: {
            animate: true,
            in_class: "fadeInRight",
            out_class: "fadeOutRight",
          }
        });

        nivel= $("#tutor_grupos .head_change label[class='']").attr("id");

        $(div).load(Routing.generate("tutor_grupo"), function(){
          if(nivel=="one"){
            $("#tutor_grupos #one").click();
          }
          else{
            $("#tutor_grupos #two").click();
          }
        });
      }
    })
  });







  ///////////////////////////////////////////
  //            Registrar Eventos          //
  ///////////////////////////////////////////

  //Se simula el hacer click en el calendario, haciendo click en un input oculto que contiene el día deleccionado.
  $(document).on('click',"#registrar_evento #dia_seleccionado",function(e){
    //Se habilita todos los campos.
    $("#container_evento :input").prop("disabled",false);
  });

  //Se habilita el botón guardar cuando todos los campos esten completos.
  $(document).on('keyup',"#registrar_evento :input",function(e){
    //Se habilita todos los campos.
    if($("#registrar_evento #titulo").val()!="" && $("#registrar_evento #descripcion").val()!="" && ($("#registrar_evento #hora").val()!="" || $("#registrar_evento #all_day input").prop('checked'))){
      $("#registrar_evento #reserva_save").prop("disabled",false);
    }
    else{
      $("#registrar_evento #reserva_save").prop("disabled",true);
    }
  });

  //Se habilita el botón guardar cuando todos los campos esten completos.(change)
  $(document).on('change',"#registrar_evento :input",function(e){
    //Se habilita todos los campos.
    if($("#registrar_evento #titulo").val()!="" && $("#registrar_evento #descripcion").val()!="" && ($("#registrar_evento #hora").val()!="" || $("#registrar_evento #all_day input").prop('checked'))){
      $("#registrar_evento #reserva_save").prop("disabled",false);
    }
    else{
      $("#registrar_evento #reserva_save").prop("disabled",true);
    }
  });
    //Se habilita el botón guardar cuando todos los campos esten completos.(blur)
    $(document).on('blur',"#registrar_evento :input",function(e){
    //Se habilita todos los campos.
    if($("#registrar_evento #titulo").val()!="" && $("#registrar_evento #descripcion").val()!="" && ($("#registrar_evento #hora").val()!="" || $("#registrar_evento #all_day input").prop('checked'))){
      $("#registrar_evento #reserva_save").prop("disabled",false);
    }
    else{
      $("#registrar_evento #reserva_save").prop("disabled",true);
    }
  });
  //Se eliminar el valor de hora si se marca la opción "Todo el dia".
  $(document).on('click',"#registrar_evento #all_day input",function(e){

    if(!$(this).prop('checked')){
      $("#registrar_evento #hora").prop("disabled",false);
    }
    else{
      $("#registrar_evento #hora").val("");
      $("#registrar_evento #hora").prop("disabled",true);
    }
  });

  $(document).on('click',"#registrar_evento #reserva_save",function(e){
    // Se establece el efecto para la notificación de error en el caso de que se de varias veces seguidas a guardar con algunas opción sin marcar.
    $(".ui-pnotify").remove();

    fecha=$("#registrar_evento #dia_seleccionado").val();
    if(fecha){
      fecha= fecha.split("/");
      fecha= fecha[2]+"-"+fecha[1]+"-"+fecha[0]; 
    }

    titulo=$("#registrar_evento #titulo").val();
    categoria=$("#registrar_evento #categoria").val();
    if($("#registrar_evento #all_day input").prop('checked')){
      hora="Todo el día";
    }
    else{
      hora=$("#registrar_evento #hora").val();
    }
    descripcion=$("#registrar_evento #descripcion").val();

    $.ajax({
      type: 'POST',
      url: Routing.generate('eventos_create'),
      data:{titulo:titulo, categoria:categoria, hora:hora, descripcion:descripcion,fecha:fecha}, 
      dataType: 'json',
      success: function(response){
  
        // Se comrpueba que no sea  un día festivo.
        if(response.message=="festivo"){
          // Se indica que el día seleccionado es festivo.
          texto="El día seleccionado <span>no es lectivo</span>, por lo que no se puede realizar ningún evento.<br><br>";
          dia=$("#contenedor_evento a[class*='ui-state-active']").text();
          mes=$("#contenedor_evento span[class='ui-datepicker-month']").text();
          anyo=$("#contenedor_evento span[class='ui-datepicker-year']").text();
          texto+="<p>Día seleccionado: <span>"+dia+" de "+mes+" de "+anyo+"</span></p><br>";
          motivo=$("#contenedor_evento a[class*='ui-state-active']").attr("title");
          texto+="<p>Motivo no lectivo:  <span>"+motivo+"</span></p>";
          
          error.play();
          swal({
            title: "Se ha producido un error en el sistema",
            html: texto,
            type: "error",
            confirmButtonColor: color
          });
        }

        // Se comprueba si hay alguna opción sin marcar para mostrar la notificación de error.
        if(response.error.length != 0){
          var texto="";
          for (var key in response.error) {
            texto+="<span>"+response.error[key]+"<span>";
          }
          errorPNotify.play();
          new PNotify({
            title: "Debe completar los siguientes datos para registrar el evento:",
            text:texto,
            addclass: "custom",
            type: "error",
            shadow: true,
            hide: true,
            width: "335px",
            buttons: {
            sticker: false,
            labels:{close: "Cerrar"}
            },
            stack: left_Stack,
            animate_speed: "fast",
            animate: {
              animate: true,
              in_class: "fadeInLeft",
              out_class: "fadeOutLeft",
            }
          });
          $("#registrar_evento #reserva_save").prop("disabled",true);
          return false;
        }
        else{
          exito.play();
          new PNotify({
            text:"Evento registrado.",
            addclass: "custom",
            type: "success",
            shadow: true,
            hide: true,
            animation: "fade",
            animate_speed: 'fast',
            delay: 4000,
            buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
            },
            stack: right_Stack,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
          });
          
          // Se actualiza las pestañas de eventos.
          $("#registrar_eventos").update_tab();
          $("#consultar_eventos").update_tab();
        }
      }
    })
  });


  ///////////////////////////////////////////
  //            Consultar Eventos          //
  ///////////////////////////////////////////

  //Se muestra la descripción del evento al hacer click en el registro.
  $(document).on("click","#consultar_eventos .scrollContent tr", function () {

      if($(this).hasClass("tr_select_descrip")){
        $(this).removeClass("tr_select_descrip");
        $("#consultar_eventos .descripcion").slideUp();
        $("#consultar_eventos .descripcion").remove();
      }
      else{
        $("#consultar_eventos .tr_select_descrip").removeClass("tr_select_descrip");
        $("#consultar_eventos .descripcion").remove();
        $(this).after("<div class='descripcion' style='display: none'>"+$(this).find("td:last").text()+"</div>");
        $(this).addClass("tr_select_descrip");
        $("#consultar_eventos .descripcion").slideDown( "fast" );
      }
      setTimeout(function(){ 
        if($('#consulta_eventos tbody').get(0).scrollHeight>$('#consulta_eventos tbody').height() ){
            $("#consulta_eventos thead tr>th:nth-child(5)").attr('style', 'width: 6.3% !important');
        }
        else{
            $("#consulta_eventos thead tr>th:nth-child(5)").attr('style', 'width: 5.7% !important');
        }
      },120);
  });

  // Se elimina el estilo del registro seleccionado si hay antes de ordenar la tabla.
  $(document).on("click","#consultar_eventos th", function () {
    $("#consultar_eventos .tr_select_descrip").removeClass("tr_select_descrip");
  });


  // Efecto cambio de iconos de editar.
  $(document).on("mouseenter",".scrollContent tr td #editar_0", function () {
      $(this).addClass("oculto");
      $(this).next("img").removeClass("oculto");
  });

  $(document).on("mouseleave",".scrollContent tr td #editar_1", function () {
      $(this).addClass("oculto");
      $(this).prev("img").removeClass("oculto");  
  });

  $(document).on("mouseleave",".scrollContent tr td:last-child", function () {
      $(this).find("#editar_1").addClass("oculto");
      $(this).find("#editar_0").removeClass("oculto");
  });

  $(document).on("mouseleave",".scrollContent tr", function () {
      $(this).find("#editar_1").addClass("oculto");
      $(this).find("#editar_0").removeClass("oculto");
  });

  //Eliminación de una reserva.
  $(document).on("click","#consultar_eventos .scrollContent tr #eliminar_1", function(event){
    event.preventDefault();
    evento=$(this).closest("tr").attr("id");

    titulo=$(this).closest("tr").find("td:nth-child(1)").text();

    from=$(this).closest("tr").find("td:nth-child(2)").attr("data-order").split("/");
    fecha= from[1]+"-"+from[0]+"-"+from[2];    

    hora=$(this).closest("tr").find("td:nth-child(3)").attr("hora");

    aviso.play();
    swal({
      title: "Eliminación del evento",
      html: "<table><p>Se va a eliminar el siguiente evento: </p><thead><tr><th>Evento</th><th>Fecha</th><th>Hora</th></tr></thead><tbody><tr><td>"+titulo+"</td><td>"+fecha+"</td><td>"+hora+"</td></tr></tbody><br></p></table><br><br>¿Estas seguro de continuar? <br>",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      width: "550px",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {
        $.ajax({
          type: 'DELETE',
          url: Routing.generate("eventos_delete", {id:evento}),
          success: function() {
          // Se actualiza la pestaña de consulta de eventos..   
            $("#consultar_eventos").update_tab();
          }
        })
        return false;
      }, function (dismiss) {

      }
    );
    //Se detiene la propagación del evento para que no detecte hacer click en el elemento "tr" que lo contiene.
    event.stopPropagation();   
  });

// Se abre la ventana modal de Editar Evento.
  $(document).on('click',"#consultar_eventos #editar_1",function(event){
    event.preventDefault();
    evento=$(this).closest("tr").attr("id");
    
    $('#evento_dialog').load(Routing.generate("eventos_edit", {id:evento}), function(){
      $('#evento_dialog form').attr("evento",evento );
    }).dialog('open'); 

    //Se detiene la propagación del evento para que no detecte hacer click en el elemento "tr" que lo contiene.
    event.stopPropagation(); 
  });

  ///////////////////////////////////////////
  //             Editar Eventos            //
  ///////////////////////////////////////////

  // Se actualiza la hora guardada al cambiar en timepicki
  $(document).on('click',"#eventos_edit .timepicker_wrap .prev ",function(event){
    hora=$("#eventos_edit .time input").val();
    minutos=$("#eventos_edit .mins input").val();
    $("#eventos_edit #eventos_hora").val(hora+":"+minutos);

    //Se quita la opción "Todo el día" si está marcada.
    $("#eventos_edit #all_day input").prop("checked", false);

    comprobarEditForm($(this).closest("form"));

  });

  // Se actualiza la hora guardada al cambiar en timepicki.
  $(document).on('click',"#eventos_edit .timepicker_wrap .next ",function(event){
    hora=$("#eventos_edit .time input").val();
    minutos=$("#eventos_edit .mins input").val();
    $("#eventos_edit #eventos_hora").val(hora+":"+minutos);

    //Se quita la opción "Todo el día" si está marcada.
    $("#eventos_edit #all_day input").prop("checked", false);

    comprobarEditForm($(this).closest("form"));

  });

  //Se eliminar el valor de hora si se marca la opción "Todo el dia".
  $(document).on('click',"#eventos_edit #all_day input",function(e){

    if(!$(this).prop('checked')){
      $("#eventos_edit .mins input").prop("disabled",false);   
      $("#eventos_edit .time input").prop("disabled",false);

      $("#eventos_edit .timepicker_wrap div").removeClass("disabled");

      $("#eventos_edit .mins input").val($("#eventos_edit .mins input").attr("value"));   
      $("#eventos_edit .time input").val($("#eventos_edit .time input").attr("value"));    
    }
    else{
      $("#eventos_edit .mins input").prop("disabled",true);   
      $("#eventos_edit .time input").prop("disabled",true);

      $("#eventos_edit .timepicker_wrap div").addClass("disabled");
      $("#eventos_edit #eventos_hora").val("Todo el día");
      
      $("#eventos_edit .mins input").val("");   
      $("#eventos_edit .time input").val("");

      $("#eventos_edit .mins input").removeClass("modified");   
      $("#eventos_edit .time input").removeClass("modified"); 
    }
    comprobarEditForm($(this).closest("form"));
  });


  $(document).on("submit","#eventos_edit",function(event) {
    event.preventDefault();
    form= $(this).closest("form");

    var val=0;

    // Se recorre los campos del formulario mirando si estan validados o no.
    form.find(":input[type!='file']").each(function(){
      if(!$(this).attr("validated") || $(this).attr("validated")==false){
        if($(this).attr("validation")){
          validation($(this));
        }
      }
    });

    //":input"añade a los input radio,select...
    form.find(":input").each(function(){
      if(($(this).attr("validated")=="false")) {
        //Se muestra el input inválido.
        $(this).focus();
        val=1;
        return false;
      }       
    });

    if(val==0){
      $.ajax({
        type: 'PUT',
        url: $(this).attr('action'),
        data: $(this).serialize(), 

        success: function(response) {

          // Notificación de confirmación
          exito.play();
              
          new PNotify({
            text:"Evento actualizado",
            addclass: "custom",
            type: "success",
            shadow: true,
            hide: true,
            buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
            },
            stack: right_Stack,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
          });
          // Se actualiza las pestañas de eventos.
          $("#registrar_eventos").update_tab();
          $("#consultar_eventos").update_tab();
          $("#evento_dialog").dialog('close');
        },
        error: function (response, desc, err){
          if (response.responseJSON && response.responseJSON.message) {
            //Se elimina las clases de error, para luego añadirlas a los campos que siguen inválidos.
            form.find(":input").each(function(i){  
              $(this).prev().find(".error").remove();
              $(this).next(".mensaje").remove();
              $(this).removeClass("invalid");
              //Si tiene alguna validación se le asigna validate: true, para que se vuelva a validar.
              //En caso contrario se valida con las reglas de validación de Symfony.
              if($(this).attr("validation")){
                $(this).attr("validated", true);
              }
            });
            //Se muestra los campos inválidos.        
            for (var key in response.responseJSON.data) { 
              form.find(":input[id='"+key+"']").addClass("invalid");
              form.find(":input[id='"+key+"']").removeClass("modified");
              //Si tiene alguna validación se le asigna validate: false, para indicar el campo inválido.
              //En caso contrario se valida con las reglas de validación de Symfony.
              if($(this).attr("validation")){
                form.find(":input[id='"+key+"']").attr("validated", false);
              }
              form.find(":input[id='"+key+"']").after("<span class='mensaje'>"+response.responseJSON.data[key]+"</span>");
              if(response.responseJSON.data[key]=="Este valor no debería estar vacío."){
                form.find(":input[id='"+key+"']").prev().append("<span class='error'>Dato requerido</span>");
              }
              else{
                form.find(":input[id='"+key+"']").prev().append("<span class='error'>Dato inválido</span>");
              }
            }
            //Se muestra el primer campo inválido.
            for (var key in response.responseJSON.data) { 
              form.find(":input[id='"+key+"']").focus();
              return false;
            }
          } 
          else {
            error.play();
            swal({
              title: "Error en el sistema",
              html: "Se ha producido un error en el sistema, por favor cierra la ventana <span class='negrita'>Editar Evento</span> y vuelva a intentarlo de nuevo.",
              type: "error",
              showCancelButton: false,
              confirmButtonColor: color
            });
          }
        }
      })
    }
  });


  ///////////////////////////////////////////
  //            Insertar Noticias          //
  ///////////////////////////////////////////

  //Se abre la ventana para insertar la imagen de la noticia.
  $(document).on('click',"#registrar_noticias #insertar_imagen button, #registrar_noticias #cambiar_imagen button[id!='btn_eliminar']",function(event){
    event.preventDefault();
    $('#imagen_noticia_dialog').load(Routing.generate("noticias_imagen"), function(){
      //Se oculta el botón que corresponde a editar noticias.
      $('#imagen_noticia_dialog .upload-result-edit_noticia').addClass('oculto');
      $('#imagen_noticia_dialog .upload-result').removeClass('oculto');

      $('#imagen_noticia_dialog #btn_file #upload').trigger('click');
    }).dialog('open'); 
  }); 

  //Se habilita el botón guardar cuando todos los campos esten completos.
  $(document).on('keyup  input ',"#registrar_noticias :input",function(e){
    //Se habilita todos los campos.

    if($("#registrar_noticias #titulo").val()!="" && tinyMCE.get('descripcion').getContent()!=""){
      $("#registrar_noticias #save").prop("disabled",false);
    }
    else{
      $("#registrar_noticias #save").prop("disabled",true);
    }
  });

  $(document).on('click',"#registrar_noticias #save",function(e){
    e.preventDefault();
    // Se establece el efecto para la notificación de error en el caso de que se de varias veces seguidas a guardar con algunas opción sin marcar.
    $(".ui-pnotify").remove();

    titulo=$("#registrar_noticias #titulo").val();
    categoria=$("#registrar_noticias #categoria").val();
    if(!$("#registrar_noticias #mostrar_imagen").hasClass("oculto")){
      imagen=$("#registrar_noticias #mostrar_imagen img").attr("file");
      pos=$("#registrar_noticias #slider-vertical").attr("value");
    }
    else{
      imagen=null;
      pos=null;
    }
    descripcion=tinyMCE.get('descripcion').getContent();

    if(!$("#registrar_noticias #galeria_noticia").hasClass("oculto")){
      galeria=$("#registrar_noticias #galeria_noticia").attr("title");
    }
    else{
      galeria=null;
    }
    
    if($("#registrar_noticias #imagen_noticia #btn_activos").hasClass("btn_selected")){
      show="yes";
    }
    else{
      show="no";
    }
    
    $.ajax({
      type: 'POST',
      url: Routing.generate('noticias_create'),
      data:{titulo:titulo, categoria:categoria, imagen:imagen, galeria:galeria, pos:pos, descripcion:descripcion, show:show}, 
      dataType: 'json',
      success: function(response){

        // Se comprueba si hay alguna opción sin marcar para mostrar la notificación de error.
        if(response.error.length != 0){
          var texto="";
          for (var key in response.error) {
            texto+="<span>"+response.error[key]+"<span>";
          }
          errorPNotify.play();
          new PNotify({
            title: "Debe completar los siguientes datos para registrar la noticia:",
            text:texto,
            addclass: "custom",
            type: "error",
            shadow: true,
            hide: true,
            width: "335px",
            buttons: {
            sticker: false,
            labels:{close: "Cerrar"}
            },
            stack: left_Stack,
            animate_speed: "fast",
            animate: {
              animate: true,
              in_class: "fadeInLeft",
              out_class: "fadeOutLeft",
            }
          });
          $("#registrar_noticias #save").prop("disabled",true);
          return false;
        }
        else{
          exito.play();
          new PNotify({
            text:"Noticia registrada.",
            addclass: "custom",
            type: "success",
            shadow: true,
            hide: true,
            animation: "fade",
            animate_speed: 'fast',
            delay: 4000,
            buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
            },
            stack: right_Stack,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
          });
          
          // Se actualiza las pestañas de noticias.
          $("#registrar_noticias").update_tab();
          $("#consultar_noticias").update_tab();
        }
      }
    })
  });

  //Se elimina la imagen seleccionada para la lista de noticias.
  $(document).on('click',"#registrar_noticias #imagen_noticia #btn_eliminar",function(event){
    event.preventDefault();
    $("#registrar_noticias #insertar_imagen button").prop("disabled", false);

    $("#registrar_noticias #imagen_noticia").addClass( "oculto" );
  }); 

  // Se activa la imagen en el contenido de la noticia.
  $(document).on('click',"#registrar_noticias #btn_activos",function(event){
    $("#registrar_noticias #btn_activos").addClass("btn_selected");
    $("#registrar_noticias #show").removeClass("oculto");

    $("#registrar_noticias #btn_inactivos").removeClass("btn_selected");
    $("#registrar_noticias #hidden").addClass("oculto");
  });

  // Se oculta la imagen en el contenido de la noticia.
  $(document).on('click',"#registrar_noticias #btn_inactivos",function(event){
    $("#registrar_noticias #btn_activos").removeClass("btn_selected");
    $("#registrar_noticias #show").addClass("oculto");

    $("#registrar_noticias #btn_inactivos").addClass("btn_selected");
    $("#registrar_noticias #hidden").removeClass("oculto");
  });

  // Se abre la ventana para añadir galería.
  $(document).on('click',"#insertar_galeria button",function(event){
    event.preventDefault();
    $('#galeria_noticia_dialog').load(Routing.generate("noticias_galeria"), function(){

      $('#galeria_noticia_dialog #btn_file #btn_files').trigger('click');
    }).dialog('open');
    $.ajax({url: Routing.generate('galerias_delete'),type: "POST"});
  }); 

  // Se abre la ventana para modificar la galería.
  $(document).on('click',"#registrar_noticias #galeria_noticia #btn_cambiar",function(event){
    event.preventDefault();
    $('#galeria_noticia_dialog').load(Routing.generate("noticias_galeria"), function(){
      //Se elimina la clase ".upload-result" que guarda la galería añadida. 
      $('#galeria_noticia_dialog #btn_upload button').removeClass('upload-result');
      // Se añade la clase ".btn-success" para guardar la galería editada.
      $('#galeria_noticia_dialog #btn_upload button').addClass('upload-result-edit');
      //Se añade el título de la galería en el input y como variable para mandarla al servidor.
      titulo=$("#registrar_noticias #galeria_noticia").attr("title");
      $("#galeria_noticia_dialog #titulo").val(titulo.replace(/_/g, " "));
      //Se añade el título como variable para mandarla al servidor.
      $("#galeria_noticia_dialog #titulo").attr("antiguo",titulo);

      //Se añaden las imágenes de la galería.
      $("#galeria_noticia #galeria_show div>img").each (function(item){ 
        array=$(this).attr("src").split("/");
        $('#galeria_noticia_dialog #contenido ul').append('<li><div class="cont_img"><img class="thumb" id="antiguo" src="'+$(this).attr("src")+'" title="'+array[array.length-1]+'"></img></div><div class="eliminar" title="Eliminar imagen"></div></li>');
      });
      //Se indica el número de imágenes.
      $('#galeria_noticia_dialog #num_imagenes').removeClass("red");

      if($('#galeria_noticia_dialog #list li').length>1){
        $('#galeria_noticia_dialog #num_imagenes').text($('#galeria_noticia_dialog #list li').length + " imágenes añadidas");
      }
      else if($('#galeria_noticia_dialog #list li').length==1) {
        $('#galeria_noticia_dialog #num_imagenes').text($('#galeria_noticia_dialog #list li').length + " imagen añadida");
      }
      else{
        $('#galeria_noticia_dialog #num_imagenes').empty();
      }

    }).dialog('open'); 
  }); 

  //Se elimina la imagen seleccionada.
  $(document).on('click',"#galeria_noticia_dialog #list .eliminar",function(event){
    event.preventDefault();
    $(this).closest("li").remove();
    if($('#galeria_noticia_dialog #list li').length>1){
      $('#galeria_noticia_dialog #num_imagenes').text($('#galeria_noticia_dialog #list li').length + " imágenes añadidas");
    }
    else if($('#galeria_noticia_dialog #list li').length==1) {
      $('#galeria_noticia_dialog #num_imagenes').text($('#galeria_noticia_dialog #list li').length + " imagen añadida");
    }
    else{
      $('#galeria_noticia_dialog #num_imagenes').empty();
    }
  }); 

  $(document).on('blur',"#galeria_noticia_dialog #titulo",function(e){
    var filter = /^([A-Za-záéíóúÁÉÍÓÚüÜñÑ0-9]+)([\s][A-Za-záéíóúÁÉÍÓÚüÜñÑ0-9]+)*$/;

    if($(this).val()==""){
      $("#galeria_noticia_dialog #titulo").next().removeClass("oculto");
      $("#galeria_noticia_dialog #titulo").next().next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").addClass("invalid");    
    }
    else if(!filter.test($(this).val())){
      $("#galeria_noticia_dialog #titulo").next().next().removeClass("oculto");
      $("#galeria_noticia_dialog #titulo").addClass("invalid");
    }
    else{
      $("#galeria_noticia_dialog #titulo").next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").next().next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").removeClass("invalid");
    }
  });

  // Se guarda la galería añadida.
  $(document).on('click',"#galeria_noticia_dialog .upload-result",function(event){
    event.preventDefault();
    val=0;
    if($("#galeria_noticia_dialog #titulo").val()==""){
      val=1;
      $("#galeria_noticia_dialog #titulo").next().removeClass("oculto");
      $("#galeria_noticia_dialog #titulo").next().next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").addClass("invalid");
      $("#galeria_noticia_dialog #titulo").focus();
    }
    else if($("#galeria_noticia_dialog #titulo").hasClass('invalid')){
      val=1;
      $("#galeria_noticia_dialog #titulo").next().next().removeClass("oculto");
      $("#galeria_noticia_dialog #titulo").addClass("invalid");
      $("#galeria_noticia_dialog #titulo").focus();
    }
    else{
      $("#galeria_noticia_dialog #titulo").next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").next().next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").removeClass("invalid");
    }

    if($('#galeria_noticia_dialog #list li').length==0){
      val=1;
      $('#galeria_noticia_dialog #num_imagenes').text("Debe añadir alguna imagen a la galería");
      $('#galeria_noticia_dialog #num_imagenes').addClass("red");
    }
    if(!val){
      nombre=$("#galeria_noticia_dialog #titulo").val();
      nombre = nombre.replace(/ /g, "_"); //Se añade la opción g(global) para que cambie todas las ocurrencias.
      var imagenes = new Array();
      $('#galeria_noticia_dialog #list li').each (function(item){ 
        array=$(this).find("img").attr("title").split('.');
        imagenes["Imagen-"+(item+1)+"."+array[array.length-1]]=$(this).find("img").attr("src");
      });
      
      var formdata=new FormData();
      for (var key in imagenes) {
        formdata.append(key, imagenes[key]);
      }
      formdata.append('nombre', nombre);

      $.ajax({
        url: Routing.generate('galeria_create'),
        type: "POST",
        data: formdata, 
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        success: function (data) {
          if(data.validate){     
            error.play();
            swal({
              title:"Nombre de la galería asignado",
              html: 'Ya existe una galería con ese nombre en el sistema.<br>Inserte otro nombre para continuar.',
              type: "error",
              showCancelButton: false,
              confirmButtonColor: color
            });
            return false;
          }

          $("#registrar_noticias #galeria_noticia #galeria_show div").remove();
            imagenes=data.imagenes.replace("[", "").replace("]", "").replace(/"/g, "");
            imagenes=imagenes.split(",");
            imagenes.forEach(function(item){
              $("#registrar_noticias #galeria_show").append("<div id='cont_imagen_mini' class='imgLiquidFill imgLiquid'><img src='/Symfony/web/uploads/noticias/galeria/"+data.directory+item+"' alt='Imagen de la galería'/></div>");
              $(".imgLiquidFill").imgLiquid();
            });


          $("#registrar_noticias #insertar_galeria button").prop("disabled", true);
          $("#registrar_noticias #galeria_noticia").removeClass( "oculto" );
          $("#galeria_noticia_dialog").dialog( "close" );
          //Se muestra el título sin "_"
          //$("#registrar_noticias #galeria_noticia").attr("title", nombre.replace(/_/g, " "));
          $("#registrar_noticias #galeria_noticia").attr("title", nombre);
        }
      });  
    }
  }); 

  // Se guarda la galería editada.
  $(document).on('click',"#galeria_noticia_dialog .upload-result-edit",function(event){
    event.preventDefault();
    val=0;
    if($("#galeria_noticia_dialog #titulo").val()==""){
      val=1;
      $("#galeria_noticia_dialog #titulo").next().removeClass("oculto");
      $("#galeria_noticia_dialog #titulo").next().next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").addClass("invalid");
      $("#galeria_noticia_dialog #titulo").focus();
    }
    else if($("#galeria_noticia_dialog #titulo").hasClass('invalid')){
      val=1;
      $("#galeria_noticia_dialog #titulo").next().next().removeClass("oculto");
      $("#galeria_noticia_dialog #titulo").addClass("invalid");
      $("#galeria_noticia_dialog #titulo").focus();
    }
    else{
      $("#galeria_noticia_dialog #titulo").next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").next().next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").removeClass("invalid");
    }

    if($('#galeria_noticia_dialog #list li').length==0){
      val=1;
      $('#galeria_noticia_dialog #num_imagenes').text("Debe añadir alguna imagen a la galería");
      $('#galeria_noticia_dialog #num_imagenes').addClass("red");
    }
    if(!val){
      nombre=$("#galeria_noticia_dialog #titulo").val();
      nombre = nombre.replace(/ /g, "_"); //Se añade la opción g(global) para que cambie todas las ocurrencias.
      antiguo=$("#galeria_noticia_dialog #titulo").attr("antiguo");
      antiguo = antiguo.replace(/ /g, "_"); //Se añade la opción g(global) para que cambie todas las ocurrencias.
      var imagenes = new Array();
      $('#galeria_noticia_dialog #list li').each (function(item){ 
        if($(this).find("img").attr("id")=="antiguo"){
          array=$(this).find("img").attr("title").split('.');
          imagenes["0Imagen-"+(item+1)+"."+array[array.length-1]]=$(this).find("img").attr("src");
        }else{
          array=$(this).find("img").attr("title").split('.');
          imagenes["Imagen-"+(item+1)+"."+array[array.length-1]]=$(this).find("img").attr("src");
        }
      });
      
      var formdata=new FormData();
      var array= Array(); //Array que contiene el nombre de las imagenes para mostrar en el contenedor.
      for (var key in imagenes) {
        formdata.append(key, imagenes[key]);
        array.push(key);
      }
      formdata.append('nombre', nombre);
      formdata.append('antiguo', antiguo);

      $.ajax({
        url: Routing.generate('galeria_editar'),
        type: "POST",
        data: formdata, 
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        success: function (data) {   
          if(data.validate){     
            error.play();
            swal({
              title:"Nombre de la galería asignado",
              html: 'Ya existe una galería con ese nombre en el sistema.<br>Inserte otro nombre para continuar.',
              type: "error",

              showCancelButton: false,
              confirmButtonColor: color
            });
            return false;
          }

          $("#registrar_noticias #galeria_noticia #galeria_show div").each (function(){ 
            this.remove();
          });
          galeria=nombre;
        $.ajax({
          type: 'POST',
          url: Routing.generate('mostrar_galeria_editada'),
          data:{galeria:galeria}, 
          dataType: 'json',
          success: function(response){
            imagenes=response.imagenes.replace("[", "").replace("]", "").replace(/"/g, "");
            imagenes=imagenes.split(",");
            imagenes.forEach(function(item){
              $("#registrar_noticias #galeria_show").append("<div id='cont_imagen_mini' class='imgLiquidFill imgLiquid'><img src='/Symfony/web/uploads/noticias/galeria/"+data.directory+item+"' alt='Imagen de la galería'/></div>");
              $(".imgLiquidFill").imgLiquid();
            });

            $("#registrar_noticias #insertar_galeria button").prop("disabled", true);
            $("#registrar_noticias #galeria_noticia").removeClass( "oculto" );
            $("#galeria_noticia_dialog").dialog( "close" );
            //Se muestra el título sin "_"
            //$("#registrar_noticias #galeria_noticia").attr("title", galeria.replace(/_/g, " "));
            $("#registrar_noticias #galeria_noticia").attr("title", galeria);
          }
        });
        }
      });  
    }
  }); 

  //Se elimina la galería añadida.
  $(document).on('click',"#registrar_noticias #galeria_noticia #btn_eliminar",function(event){
    event.preventDefault();
    $("#registrar_noticias #insertar_galeria button").prop("disabled", false);

    $("#registrar_noticias #galeria_noticia #galeria_show div").remove();
    $("#registrar_noticias #galeria_noticia").addClass( "oculto" );
  }); 

  ///////////////////////////////////////////
  //           Consultar Noticias          //
  ///////////////////////////////////////////

  //Eliminación de una reserva.
  $(document).on("click","#consultar_noticias .scrollContent tr #eliminar_1", function(event){
    event.preventDefault();
    noticia=$(this).closest("tr").attr("id");

    titulo=$(this).closest("tr").find("td:nth-child(1)").text();

    from=$(this).closest("tr").find("td:nth-child(2)").attr("data-order").split("/");
    fecha= from[1]+"-"+from[0]+"-"+from[2];    

    hora=$(this).closest("tr").find("td:nth-child(3)").attr("hora");


    aviso.play();
    swal({
      title: "Eliminación de noticias",
      html: "<table><p>Se va a eliminar la siguiente noticia:</p><thead><tr><th>Noticia</th><th>Fecha</th><th>Hora</th></tr></thead><tbody><tr><td>"+titulo+"</td><td>"+fecha+"</td><td>"+hora+"</td></tr></tbody><br></p></table><br><br>¿Estas seguro de continuar? <br>",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      width: "600px",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {
        $.ajax({
          type: 'DELETE',
          url: Routing.generate("noticias_delete", {id:noticia}),
          success: function() {
          // Se actualiza las pestañas de consulta de noticias.   
            $("#consultar_noticias").update_tab();
          }
        })
        return false;
      }, function (dismiss) {

      }
    );
    //Se detiene la propagación del evento para que no detecte hacer click en el elemento "tr" que lo contiene.
    event.stopPropagation();   
  });

// Se abre la ventana modal de Editar Noticias.
  $(document).on('click',"#consultar_noticias #editar_1",function(event){
    event.preventDefault();
    noticia=$(this).closest("tr").attr("id");
    
    $('#noticia_dialog').load(Routing.generate("noticias_edit", {id:noticia}), function(){
      $('#noticia_dialog form').attr("noticia",noticia );
    }).dialog('open'); 

    //Se detiene la propagación del evento para que no detecte hacer click en el elemento "tr" que lo contiene.
    event.stopPropagation(); 
  });


  //Se abre la ventana para insertar la imagen de la noticia.
  $(document).on('click',"#insertar_imagen_edit button, #cambiar_imagen_edit button[id!='btn_eliminar']",function(event){
    event.preventDefault();
    $('#imagen_noticia_dialog').load(Routing.generate("noticias_imagen"), function(){
      //Se oculta el botón que corresponde a registrar noticias.
      $('#imagen_noticia_dialog .upload-result-edit_noticia').removeClass('oculto');
      $('#imagen_noticia_dialog .upload-result').addClass('oculto');

      $('#imagen_noticia_dialog #btn_file #upload').trigger('click');
    }).dialog('open'); 
  }); 

  //Se elimina la imagen seleccionada para la lista de noticias.
  $(document).on('click',"#noticias_edit #cambiar_imagen_edit  #btn_eliminar",function(event){
    event.preventDefault();
    $("#noticias_edit #insertar_imagen_edit button").prop("disabled", false);

    $("#noticias_edit #imagen_noticia").addClass( "oculto" );
  /*  titulo=$("#noticias_edit #imagen_noticia #cont_imagen img").attr("file");
    $.ajax({
      type: 'POST',
      url: Routing.generate('imagen_delete'),
      data:{titulo:titulo}, 
      dataType: 'json',
      success: function(response){
      }
    });
*/
  }); 

  // Se activa la imagen en el contenido de la noticia.
  $(document).on('click',"#noticias_edit #btn_activos",function(event){
    $("#noticias_edit #btn_activos").addClass("btn_selected");
    $("#noticias_edit #show").removeClass("oculto");

    $("#noticias_edit #btn_inactivos").removeClass("btn_selected");
    $("#noticias_edit #hidden").addClass("oculto");
  });

  // Se oculta la imagen en el contenido de la noticia.
  $(document).on('click',"#noticias_edit #btn_inactivos",function(event){
    $("#noticias_edit #btn_activos").removeClass("btn_selected");
    $("#noticias_edit #show").addClass("oculto");

    $("#noticias_edit #btn_inactivos").addClass("btn_selected");
    $("#noticias_edit #hidden").removeClass("oculto");
  });

  // Se abre la ventana para añadir galería.
  $(document).on('click',"#noticias_edit #insertar_galeria_edit button",function(event){
    event.preventDefault();
    $('#galeria_noticia_dialog').load(Routing.generate("noticias_galeria"), function(){
      //Se elimina la clase ".upload-result" que guarda la galería añadida. 
      $('#galeria_noticia_dialog #btn_upload button').removeClass('upload-result');
      // Se añade la clase ".btn-success" para guardar la galería editada.
      $('#galeria_noticia_dialog #btn_upload button').addClass('upload-result-edit_noticia');

      $('#galeria_noticia_dialog #btn_file #btn_files').trigger('click');
    }).dialog('open');
    $.ajax({url: Routing.generate('galerias_delete'),type: "POST"});
  }); 

  // Se abre la ventana para modificar la galería.
  $(document).on('click',"#noticias_edit #galeria_noticia #btn_cambiar",function(event){
    event.preventDefault();
    $('#galeria_noticia_dialog').load(Routing.generate("noticias_galeria"), function(){
      //Se elimina la clase ".upload-result" que guarda la galería añadida. 
      $('#galeria_noticia_dialog #btn_upload button').removeClass('upload-result');
      // Se añade la clase ".btn-success" para guardar la galería editada.
      $('#galeria_noticia_dialog #btn_upload button').addClass('upload-result-edit-edit_noticia');
      //Se añade el título de la galería en el input y como variable para mandarla al servidor.
      titulo=$("#noticias_edit #galeria_noticia").attr("title");
      $("#galeria_noticia_dialog #titulo").val(titulo.replace(/_/g, " "));
      //Se añade el título como variable para mandarla al servidor.
      $("#galeria_noticia_dialog #titulo").attr("antiguo",titulo);

      //Se añaden las imágenes de la galería.
      $("#noticias_edit #galeria_noticia #galeria_show div>img").each (function(item){ 
        array=$(this).attr("src").split("/");
        $('#galeria_noticia_dialog #contenido ul').append('<li><div class="cont_img"><img class="thumb" id="antiguo" src="'+$(this).attr("src")+'" title="'+array[array.length-1]+'"></img></div><div class="eliminar" title="Eliminar imagen"></div></li>');
      });
      //Se indica el número de imágenes.
      $('#galeria_noticia_dialog #num_imagenes').removeClass("red");

      if($('#galeria_noticia_dialog #list li').length>1){
        $('#galeria_noticia_dialog #num_imagenes').text($('#galeria_noticia_dialog #list li').length + " imágenes añadidas");
      }
      else if($('#galeria_noticia_dialog #list li').length==1) {
        $('#galeria_noticia_dialog #num_imagenes').text($('#galeria_noticia_dialog #list li').length + " imagen añadida");
      }
      else{
        $('#galeria_noticia_dialog #num_imagenes').empty();
      }


    }).dialog('open'); 
  }); 

  // Se guarda la galería añadida.
  $(document).on('click',"#galeria_noticia_dialog .upload-result-edit_noticia",function(event){
    event.preventDefault();
    val=0;
    if($("#galeria_noticia_dialog #titulo").val()==""){
      val=1;
      $("#galeria_noticia_dialog #titulo").next().removeClass("oculto");
      $("#galeria_noticia_dialog #titulo").next().next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").addClass("invalid");
      $("#galeria_noticia_dialog #titulo").focus();
    }
    else if($("#galeria_noticia_dialog #titulo").hasClass('invalid')){
      val=1;
      $("#galeria_noticia_dialog #titulo").next().next().removeClass("oculto");
      $("#galeria_noticia_dialog #titulo").addClass("invalid");
      $("#galeria_noticia_dialog #titulo").focus();
    }
    else{
      $("#galeria_noticia_dialog #titulo").next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").next().next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").removeClass("invalid");
    }

    if($('#galeria_noticia_dialog #list li').length==0){
      val=1;
      $('#galeria_noticia_dialog #num_imagenes').text("Debe añadir alguna imagen a la galería");
      $('#galeria_noticia_dialog #num_imagenes').addClass("red");
    }
    if(!val){
      nombre=$("#galeria_noticia_dialog #titulo").val();
      nombre = nombre.replace(/ /g, "_"); //Se añade la opción g(global) para que cambie todas las ocurrencias.
      var imagenes = new Array();
      $('#galeria_noticia_dialog #list li').each (function(item){ 
        array=$(this).find("img").attr("title").split('.');
        imagenes["Imagen-"+(item+1)+"."+array[array.length-1]]=$(this).find("img").attr("src");
      });
      
      var formdata=new FormData();
      for (var key in imagenes) {
        formdata.append(key, imagenes[key]);
      }
      formdata.append('nombre', nombre);

      $.ajax({
        url: Routing.generate('galeria_create'),
        type: "POST",
        data: formdata, 
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        success: function (data) {
          if(data.validate){     
            error.play();
            swal({
              title:"Nombre de la galería asignado",
              html: 'Ya existe una galería con ese nombre en el sistema.<br>Inserte otro nombre para continuar.',
              type: "error",
              showCancelButton: false,
              confirmButtonColor: color
            });
            return false;
          }

          $("#noticias_edit #galeria_noticia #galeria_show div").remove();
            imagenes=data.imagenes.replace("[", "").replace("]", "").replace(/"/g, "");
            imagenes=imagenes.split(",");
            imagenes.forEach(function(item){
              $("#noticias_edit #galeria_show").append("<div id='cont_imagen_mini' class='imgLiquidFill imgLiquid'><img src='/Symfony/web/uploads/noticias/galeria/"+data.directory+item+"' alt='Imagen de la galería'/></div>");
              $(".imgLiquidFill").imgLiquid();
            });


          $("#noticias_edit #insertar_galeria_edit button").prop("disabled", true);
          $("#noticias_edit #galeria_noticia").removeClass( "oculto" );
          $("#galeria_noticia_dialog").dialog( "close" );
          //Se muestra el título sin "_"
          //$("#noticias_edit #galeria_noticia").attr("title", nombre.replace(/_/g, " "));

          $("#noticias_edit #galeria_noticia").attr("title", nombre);
        }
      });  
    }
  }); 

  // Se guarda la galería editada.
  $(document).on('click',"#galeria_noticia_dialog .upload-result-edit-edit_noticia",function(event){
    event.preventDefault();
    val=0;
    if($("#galeria_noticia_dialog #titulo").val()==""){
      val=1;
      $("#galeria_noticia_dialog #titulo").next().removeClass("oculto");
      $("#galeria_noticia_dialog #titulo").next().next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").addClass("invalid");
      $("#galeria_noticia_dialog #titulo").focus();
    }
    else if($("#galeria_noticia_dialog #titulo").hasClass('invalid')){
      val=1;
      $("#galeria_noticia_dialog #titulo").next().next().removeClass("oculto");
      $("#galeria_noticia_dialog #titulo").addClass("invalid");
      $("#galeria_noticia_dialog #titulo").focus();
    }
    else{
      $("#galeria_noticia_dialog #titulo").next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").next().next().addClass("oculto");
      $("#galeria_noticia_dialog #titulo").removeClass("invalid");
    }

    if($('#galeria_noticia_dialog #list li').length==0){
      val=1;
      $('#galeria_noticia_dialog #num_imagenes').text("Debe añadir alguna imagen a la galería");
      $('#galeria_noticia_dialog #num_imagenes').addClass("red");
    }
    if(!val){
      nombre=$("#galeria_noticia_dialog #titulo").val();
      nombre = nombre.replace(/ /g, "_"); //Se añade la opción g(global) para que cambie todas las ocurrencias.
      antiguo=$("#galeria_noticia_dialog #titulo").attr("antiguo");
      antiguo = antiguo.replace(/ /g, "_"); //Se añade la opción g(global) para que cambie todas las ocurrencias.
      var imagenes = new Array();
      $('#galeria_noticia_dialog #list li').each (function(item){ 
        if($(this).find("img").attr("id")=="antiguo"){
          array=$(this).find("img").attr("title").split('.');
          imagenes["0Imagen-"+(item+1)+"."+array[array.length-1]]=$(this).find("img").attr("src");
        }else{
          array=$(this).find("img").attr("title").split('.');
          imagenes["Imagen-"+(item+1)+"."+array[array.length-1]]=$(this).find("img").attr("src");
        }
      });
      
      var formdata=new FormData();
      var array= Array(); //Array que contiene el nombre de las imagenes para mostrar en el contenedor.
      for (var key in imagenes) {
        formdata.append(key, imagenes[key]);
        array.push(key);
      }
      formdata.append('nombre', nombre);
      formdata.append('antiguo', antiguo);

      $.ajax({
        url: Routing.generate('galeria_editar'),
        type: "POST",
        data: formdata, 
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        success: function (data) {   
          if(data.validate){     
            error.play();
            swal({
              title:"Nombre de la galería asignado",
              html: 'Ya existe una galería con ese nombre en el sistema.<br>Inserte otro nombre para continuar.',
              type: "error",
              showCancelButton: false,
              confirmButtonColor: color
            });
            return false;
          }

          $("#noticias_edit #galeria_noticia #galeria_show div").each (function(){ 
            this.remove();
          });
          galeria=nombre;
        $.ajax({
          type: 'POST',
          url: Routing.generate('mostrar_galeria_editada'),
          data:{galeria:galeria}, 
          dataType: 'json',
          success: function(response){
            imagenes=response.imagenes.replace("[", "").replace("]", "").replace(/"/g, "");
            imagenes=imagenes.split(",");
            imagenes.forEach(function(item){
              $("#noticias_edit #galeria_show").append("<div id='cont_imagen_mini' class='imgLiquidFill imgLiquid'><img src='/Symfony/web/uploads/noticias/galeria/"+data.directory+item+"' alt='Imagen de la galería'/></div>");
              $(".imgLiquidFill").imgLiquid();
            });
            
            $("#noticias_edit #noticias_galeria").val( $("#galeria_noticia_dialog #titulo").val().replace(/ /g, "_") );

            $("#noticias_edit #insertar_galeria_edit button").prop("disabled", true);
            $("#noticias_edit #galeria_noticia").removeClass( "oculto" );
            $("#galeria_noticia_dialog").dialog( "close" );

            //Se muestra el título sin "_"
            //$("#noticias_edit #galeria_noticia").attr("title", galeria.replace(/_/g, " "));
            $("#noticias_edit #galeria_noticia").attr("title", galeria);
          }
        });
        }
      });  
    }
  }); 

  //Se elimina la galería añadida.
  $(document).on('click',"#noticias_edit #galeria_noticia #btn_eliminar",function(event){
    event.preventDefault();
    $("#insertar_galeria_edit button").prop("disabled", false);
    $("#noticias_edit #galeria_noticia").attr("title","");

    $("#noticias_edit #galeria_noticia #galeria_show div").remove();
    $("#noticias_edit #galeria_noticia").addClass( "oculto" );

    $.ajax({url: Routing.generate('galerias_delete'),type: "POST"});

  }); 
  //Se valida el título de la noticia. (La descripción es validada dentro tinymce)
  $(document).on("change keyup paste click input propertychange blur cut",'#noticias_edit #noticias_titulo', function(){
    $("#noticias_edit #input_titulo").find(".error").remove();
    titulo=$("#noticias_edit #noticias_titulo").val();

    if(titulo==""){
      $("#noticias_edit #noticias_titulo").prev().append("<span class='error'>Dato Requerido</span>");
      $("#noticias_edit #noticias_titulo").addClass("invalid");
      $("#noticias_edit #noticias_titulo").attr("validated",false);
    }
    else{
      $("#noticias_edit #noticias_titulo").removeClass("invalid");
      $("#noticias_edit #noticias_titulo").attr("validated",true);
    }
  });


  //Se actualiza la noticia.
  $(document).on("submit","#noticias_edit",function(event) {
    event.preventDefault();
    form= $(this).closest("form");

    // Se establece el efecto para la notificación de error en el caso de que se de varias veces seguidas a guardar con algunas opción sin marcar.
    $(".ui-pnotify").remove();

    if(!$("#noticias_edit #imagen_noticia").hasClass("oculto")){
      imagen=$("#noticias_edit #mostrar_imagen img").attr("file");
      pos=$("#noticias_edit #slider-vertical").attr("value");
    }
    else{
      imagen="";
      pos=null;
    }
    if(!$("#noticias_edit #galeria_noticia").hasClass("oculto")){
      galeria=$("#noticias_edit #galeria_noticia").attr("title").split(' ').join('_');
    }
    else{
      galeria="";
    }
    if($("#noticias_edit #imagen_noticia #btn_activos").hasClass("btn_selected")){
      show="yes";
    }
    else{
      show="no";
    }
    
    titulo=$("#noticias_edit #noticias_titulo").val();
    descripcion=tinyMCE.get('noticias_descripcion').getContent();  

    var val=0;
    $("#noticias_edit").find(".error").remove();
    if(titulo==""){
      val=1;
      $("#noticias_edit #noticias_titulo").prev().append("<span class='error'>Dato Requerido</span>");
      $("#noticias_edit #noticias_titulo").addClass("invalid");
      $("#noticias_edit #noticias_titulo").attr("validated",false);
      $("#noticias_edit #noticias_titulo").focus();
    }
    else{
      $("#noticias_edit #noticias_titulo").removeClass("invalid");
      $("#noticias_edit #noticias_titulo").attr("validated",true);
    }

    if(descripcion==""){
      val=1;
      $("#noticias_edit .editor>label").append("<span class='error'>Dato Requerido</span>");
      $("#noticias_edit .editor>div").addClass("invalid");
      $("#noticias_edit #noticias_descripcion").attr("validated",false);
      if(titulo!=""){
        tinyMCE.get('noticias_descripcion').focus();
      }
    }
    else{
      $("#noticias_edit .editor>div").removeClass("invalid");
      $("#noticias_edit #noticias_descripcion").attr("validated",true);     
    }


    if(val==0){
      $.ajax({
        type: 'PUT',
        url: $(this).attr('action'),

        data: $(this).serialize()+"&imagen="+imagen+"&pos="+pos+"&galeria="+galeria+"&show="+show, 

        success: function(response) {

          // Notificación de confirmación
          exito.play();
              
          new PNotify({
            text:"Noticia actualizada",
            addclass: "custom",
            type: "success",
            shadow: true,
            hide: true,
            buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
            },
            stack: right_Stack,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
          });
          // Se actualiza las pestañas de eventos.
          $("#registrar_noticias").update_tab();
          $("#consultar_noticias").update_tab();
          $("#noticia_dialog").dialog('close');
        },
        error: function (response, desc, err){
          if (response.responseJSON && response.responseJSON.message) {
            //Se elimina las clases de error, para luego añadirlas a los campos que siguen inválidos.
            form.find(":input").each(function(i){  
              $(this).prev().find(".error").remove();
              $(this).next(".mensaje").remove();
              $(this).removeClass("invalid");
              //Si tiene alguna validación se le asigna validate: true, para que se vuelva a validar.
              //En caso contrario se valida con las reglas de validación de Symfony.
              if($(this).attr("validation")){
                $(this).attr("validated", true);
              }
            });
            //Se muestra los campos inválidos.        
            for (var key in response.responseJSON.data) { 
              form.find(":input[id='"+key+"']").addClass("invalid");
              form.find(":input[id='"+key+"']").removeClass("modified");
              //Si tiene alguna validación se le asigna validate: false, para indicar el campo inválido.
              //En caso contrario se valida con las reglas de validación de Symfony.
              if($(this).attr("validation")){
                form.find(":input[id='"+key+"']").attr("validated", false);
              }
              form.find(":input[id='"+key+"']").after("<span class='mensaje'>"+response.responseJSON.data[key]+"</span>");
              if(response.responseJSON.data[key]=="Este valor no debería estar vacío."){
                form.find(":input[id='"+key+"']").prev().append("<span class='error'>Dato Requerido</span>");
              }
              else{
                form.find(":input[id='"+key+"']").prev().append("<span class='error'>Dato inválido</span>");
              }
            }
            //Se muestra el primer campo inválido.
            for (var key in response.responseJSON.data) { 
              form.find(":input[id='"+key+"']").focus();
              return false;
            }
          } 
          else {
            error.play();
            swal({
              title: "Error en el sistema",
              html: "Se ha producido un error en el sistema, por favor cierra la ventana <span class='negrita'>Editar Evento</span> y vuelva a intentarlo de nuevo.",
              type: "error",
              showCancelButton: false,
              confirmButtonColor: color
            });
          }
        }
      })
    }
  });


 
/////////////////////////////////
// Asignar profesores a grupos //
/////////////////////////////////

  $(document).on('click',"#asignar_profesor a[id$='_modal']",function(event){
    event.preventDefault();
    var curso= $(this).closest("h2").text();
    var id=$(this).closest("div").attr("grupo");

    $.ajax({
      type: 'POST',
      url: Routing.generate('comprobar_asignaturas'),
      success: function(response) {
        // Si no hay asignaturas registradas muestra un aviso.
        if(response.data==null){
          error.play(); 
          swal({
            title:"No se pueden asignar profesores a los grupos",
            text: 'Actualmente no hay asignaturas registrada en el sistema.',
            type: "error",
            showCancelButton: false,
            confirmButtonColor: color
          });
        }
        else{
          $('#profesor_asignatura_grupo_dialog').load(Routing.generate("asignar_grupo_profesores_new", {id:id}), function(){
          }).dialog('open'); 
        }
      }
    })
  });

  $(document).on('click',"#profesor_asignatura_grupo_dialog #div_lista button",function(event){ 
    //Se marca el profesor seleccionado.
    if(!$(this).hasClass("elected")){
      $("#profesor_asignatura_grupo_dialog #div_lista button").removeClass("elected"); 
      $(this).addClass("elected");
    }
    if($("#profesor_asignatura_grupo_dialog #contenedor_asignaturas input:checkbox:checked").size()>0){
      $("#profesor_asignatura_grupo_dialog #btn_asignar").removeClass('disabled');
      $("#profesor_asignatura_grupo_dialog #btn_asignar span").removeClass('disab');
    }
    else{
      $("#profesor_asignatura_grupo_dialog #btn_asignar").addClass('disabled');
      $("#profesor_asignatura_grupo_dialog #btn_asignar span").addClass('disab');
    }
    //Se deshabilita el botón "restablecer".
    $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_restablecer").prop("disabled",false);
  });
  //Se modifica el title del select con el nombre del aula seleccionada.
  $(document).on('change',"#profesor_asignatura_grupo_dialog select",function(event){
    titulo= $(this).find("option:selected").text();
    $(this).attr("title",titulo);
  });

  $(document).on('change',"#profesor_asignatura_grupo_dialog input:checkbox",function(event){ 
    if($("#profesor_asignatura_grupo_dialog #contenedor_asignaturas input:checkbox:checked").size()==0){
      $("#profesor_asignatura_grupo_dialog #btn_asignar").addClass('disabled');
      $("#profesor_asignatura_grupo_dialog #btn_asignar span").addClass('disab');
    }
    else{
      if($("#profesor_asignatura_grupo_dialog #div_lista .elected").size()==1){
        $("#profesor_asignatura_grupo_dialog #btn_asignar").removeClass('disabled');
        $("#profesor_asignatura_grupo_dialog #btn_asignar span").removeClass('disab');
      }
    }
    //Si se marca una asignatura opcional se deshabilita los checkbox de las otras opcionales para que no se pueda añadir el mismo profesor.
    if($(this).closest("li").attr("opcional")==1)
    {
      if($(this).is(':checked')){
        $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[opcional='1'] input:checkbox").each (function(){ 
          $(this).prop('disabled', true);
        });
        $(this).prop('disabled', false);
      }
      else{
        $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[opcional='1'] input:checkbox").each (function(){ 
          $(this).prop('disabled', false);
        });
      }
    }


  });

  $(document).on('click',"#profesor_asignatura_grupo_dialog li",function(event){ 
   event.preventDefault();
   //Se ejecuta solo al marcar los checkbox.
   if(!$(this).find("#no_asignado").hasClass('oculto')){
    input=$(this).find("#no_asignado input");
    //Se pulsa el checkbox.
    if($(event.target).is('input')){
      $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_restablecer").prop("disabled",false);
      //Retardo para poder mostar el input seleccionado.
      setTimeout(function(){
        if(input.is(':checked') ){
          input.prop("checked",false).change();
          // Se habilita el botón de asignar.
          if($("#profesor_asignatura_grupo_dialog #div_lista .elected").size()==1 && $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas #no_asignado input:checkbox:checked").size()>0){
            $("#profesor_asignatura_grupo_dialog #btn_asignar").removeClass('disabled');
            $("#profesor_asignatura_grupo_dialog #btn_asignar span").removeClass('disab');
          }
          else{
            $("#profesor_asignatura_grupo_dialog #btn_asignar").addClass('disabled');
            $("#profesor_asignatura_grupo_dialog #btn_asignar span").addClass('disab');
          }

          if($("#profesor_asignatura_grupo_dialog #div_lista .elected").size()==0 && $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas #no_asignado input:checkbox:checked").size()==0  && $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas .eliminado").size()==0 && $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas .back_modificado").size()==0){
            $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_submit").prop("disabled",true);
            $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_restablecer").prop("disabled",true);
          }
        }
        else{
          input.prop("checked",true).change();
          // Se deshabilita el botón de asignar.
          if($("#profesor_asignatura_grupo_dialog #div_lista .elected").size()==1){
            $("#profesor_asignatura_grupo_dialog #btn_asignar").removeClass('disabled');
            $("#profesor_asignatura_grupo_dialog #btn_asignar span").removeClass('disab');
          }

        }
      }, 5);
    }//Se pulsa la imagen de eliminar asignación.
    else if($(event.target).is('img')){

    }
    else if($(event.target).is('select')){

    }
    //Se pulsa la lista.
    else{
      $(this).find("#no_asignado input:checkbox").click();
    }
   }
  });

  // Marcar o desmarcar todos las asignaturas troncales.
  $(document).on('click',"#profesor_asignatura_grupo_dialog #all",function(event){
    if($(this).is(':checked') ){
      $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[tipo='Troncal']").each (function(){ 
          $(this).find("#no_asignado input").prop("checked",true);
      });
    }
    else{
      $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[tipo='Troncal']").each (function(){ 
          $(this).find("#no_asignado input").prop("checked",false);
      });
    }
  });

  //Se oculta el title con el nombre del aula al abrir el select.
  $(document).on('click',"#profesor_asignatura_grupo_dialog #contenedor_asignaturas select",function(event){
    $(this).attr("title","");
  });
  //Se muestra el title con el nombre del aula al modificar el select.
  $(document).on('change',"#profesor_asignatura_grupo_dialog #contenedor_asignaturas select",function(event){
    $(this).attr("title",$(this).find("option:selected").text());
    //Se comprueba cuando se modifica el aula si el aula o el profesor ha sido modificado para añadirle las clases.
    if($(this).attr("valor")){
      if($(this).attr("valor")!=$(this).val() || $(this).prev().attr("valor")!=$(this).prev().attr("value")){
        $(this).closest('li').addClass('back_modificado');
        $(this).closest('li').removeClass('back_asignado');
        $(this).prev().attr("title", "Profesor Asignado con cambio de Aula");
     }
      else{
        $(this).closest('li').removeClass('back_modificado');
        $(this).closest('li').addClass('back_asignado');
        $(this).prev().attr("title", "Profesor Asignado");
      }
    }

    //Se deshabilita el aula asignada a una asignatura opcional en el listado de las otras.
    $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[opcional='1'] #asignado:not('.oculto') select option").prop('disabled', false);
    
    $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[opcional='1'] #asignado:not('.oculto') select").each (function(){
      aula=$(this).val();
      li=$(this).closest("li").attr("id");
      if(aula!=0){
        $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[opcional='1'] #asignado:not('.oculto') select").each (function(){
          //Se deshabilita las aulas asignadas en los otros select, excepto cuando la opción está seleccionada en el select.
          //(Caso validado desde javascript para que no pueda seleccionarse el mismo, pero es necesario para comprobar aula asignada en php)
          if($(this).closest("li").attr("id")!=li && $(this).val()!=aula){
            $(this).find("option[value='"+aula+"']").prop('disabled', true);
          }

        });
      }
    });

    //Se comprueba si hay modificaciones para habilitar o deshabilitar los botones de "guardar" y "restablecer".
    if($("#profesor_asignatura_grupo_dialog #contenedor_asignaturas .back_modificado").size()>0 || $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas .eliminado").size()>0){
      $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_submit").prop("disabled",false);
      $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_restablecer").prop("disabled",false);
    }else{
      $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_submit").prop("disabled",true);
      $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_restablecer").prop("disabled",true);
    }
    $(this).blur();
  });

  //Se añade el profesor y a las asignaturas seleccionadas en el curso correspondiente.
  $(document).on('click',"#profesor_asignatura_grupo_dialog #btn_asignar",function(event){

    //Se comprueba que esté seleccionado un profesor y alguna asignatura.
    if($("#profesor_asignatura_grupo_dialog #div_lista .elected").size()==0 || $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas input:checkbox:checked").size()==0){
      texto="";
      if($("#profesor_asignatura_grupo_dialog #div_lista .elected").size()==0){
        texto+="<span>- Profesor.</span>";
      }
      if($("#profesor_asignatura_grupo_dialog #contenedor_asignaturas input:checkbox:checked").size()==0){
        texto+="<span>- Asignaturas.</span>";
      }
      errorPNotify.pause();
      errorPNotify.currentTime=0.0;
      $(".ui-pnotify").remove();
        
      errorPNotify.play();
      new PNotify({
        title: "Debe seleccionar los siguientes datos para realizar la asignación:",
        text:texto,
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        width: "335px",
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        animate_speed: "fast",
        stack: right_Stack_dialog,
        animate: {
          animate: true,
          in_class: "fadeInRight",
          out_class: "fadeOutRight",
        }
      });

      $("#profesor_asignatura_grupo_dialog #btn_asignar").addClass('disabled');
      $("#profesor_asignatura_grupo_dialog #btn_asignar span").addClass('disab');
      return false;
    }

    profesor=$("#profesor_asignatura_grupo_dialog .elected").text();
    id=$("#profesor_asignatura_grupo_dialog .elected").attr("id");
    tutoria_no_asignada=0;
    //Se asignan las asignaturas troncales y específicas(excepto las opcionales)
    $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[opcional!='1'] div:not('.oculto') input:checkbox:checked").each (function(){ 
      //Se comrpueba que el profesor seleccionado es el tutor del grupo para poder asignarle la Tutoría.
      if($(this).closest("li").attr("nombre")=="Tutoría" && $("#profesor_asignatura_grupo_dialog #tutor .elected").size()==0){
        tutoria_no_asignada=1;
      }
      else{
        $(this).closest("#no_asignado").addClass('oculto');
        $(this).closest("#no_asignado").next().removeClass('oculto');
        $(this).closest("#no_asignado").next().find('input[type="text"]').attr("value",profesor);
        $(this).closest("#no_asignado").next().find('input[type="text"]').attr("id",id);
        $(this).closest("li").removeClass();
      
        $("#profesor_asignatura_grupo_dialog #div_lista .elected").removeClass('elected');
        $(this).prop("checked", false);
        if($(this).closest("#no_asignado").next().find('input[type="text"]').attr("value")==$(this).closest("#no_asignado").next().find('input[type="text"]').attr("valor")){
          //Se le asigna un color y un title a los profesores ya asignados.
          $(this).closest("li").addClass("back_asignado");
          $(this).closest("#no_asignado").next().find('input[type="text"]').attr("title","Profesor Asignado");

          aula= $(this).closest("#no_asignado").next().find('select').attr("valor");
          $(this).closest("#no_asignado").next().find('select').val(aula);
          $(this).closest("#no_asignado").next().find('select').attr("title",$(this).closest("#no_asignado").next().find('select option:selected').text());

        }else{
          //Se le asigna un color y un title a los profesores pendientes de asignar.
          $(this).closest("li").addClass("back_modificado");
          $(this).closest("#no_asignado").next().find('input[type="text"]').attr("title","Profesor pendiente de Asignar");

          aula=$(this).closest("fieldset").attr("aula");
          $(this).closest("#no_asignado").next().find('select').val(aula);
          $(this).closest("#no_asignado").next().find('select').attr("title",$(this).closest("#no_asignado").next().find('select option:selected').text());
        }
      }
    });
    var asignaturas = Array();
    //Se asignan las asignaturas específicas opcionales.
    $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[opcional='1'] div:not('.oculto') input:checkbox:checked").each (function(){ 
      //Se comprueba si el profesor está asignado a otra asignatura opcional.
      if($("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[opcional='1'] div:not('.oculto') input[type='text']").size()>0 && $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[opcional='1'] div:not('.oculto') input[id='"+id+"']").size()>0){
        contenido=$(this).next("p").text();
        arr=contenido.split("(");
        asignatura=arr[0];

        asignaturas.push(asignatura);
      }
      else{
        div=$(this).closest("#no_asignado").next();
        //Se deshabilita del listado de aulas disponibles las aulas ya asignadas.
        $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[opcional='1'] #asignado:not('.oculto') select").each (function(){ 
          aula=$(this).val();
          if(aula!=0){
            div.find("select option[value='"+aula+"']").prop('disabled', true);
          }
        });

        $(this).closest("#no_asignado").addClass('oculto');
        $(this).closest("#no_asignado").next().removeClass('oculto');
        $(this).closest("#no_asignado").next().find('input[type="text"]').attr("value",profesor);
        $(this).closest("#no_asignado").next().find('input[type="text"]').attr("id",id);
        $(this).closest("li").removeClass();
      
        $("#profesor_asignatura_grupo_dialog #div_lista .elected").removeClass('elected');
        $(this).prop("checked", false);
        if($(this).closest("#no_asignado").next().find('input[type="text"]').attr("value")==$(this).closest("#no_asignado").next().find('input[type="text"]').attr("valor")){
          //Se le asigna un color y un title a los profesores ya asignados.
          $(this).closest("li").addClass("back_asignado");
          $(this).closest("#no_asignado").next().find('input[type="text"]').attr("title","Profesor Asignado");

          $(this).closest("#no_asignado").next().find('select').val(0).change();

          $(this).closest("#no_asignado").next().find('select').attr("title",$(this).closest("#no_asignado").next().find('select option:selected').text());

        }else{

          //Se le asigna un color y un title a los profesores pendientes de asignar.
          $(this).closest("li").addClass("back_modificado");
          $(this).closest("#no_asignado").next().find('input[type="text"]').attr("title","Profesor pendiente de Asignar");

          $(this).closest("#no_asignado").next().find('select').val(0).change();
     
          $(this).closest("#no_asignado").next().find('select').attr("title",$(this).closest("#no_asignado").next().find('select option:selected').text());
        
        }
      }
    });

    //Se comprueba si hay modificaciones para habilitar o deshabilitar los botones de "guardar" y "restablecer".
    if($("#profesor_asignatura_grupo_dialog #contenedor_asignaturas .back_modificado").size()>0 || $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas .eliminado").size()>0){
      $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_submit").prop("disabled",false);
      $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_restablecer").prop("disabled",false);
    }else{
      $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_submit").prop("disabled",true);
      $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_restablecer").prop("disabled",true);
    }
    // Se deshabilita el botón "Asignar".
    $("#profesor_asignatura_grupo_dialog #btn_asignar").addClass('disabled');
    $("#profesor_asignatura_grupo_dialog #btn_asignar span").addClass('disab');
   
    // Se desmarca el checkbox o se oculta si no hay más asignaturas troncales por asignar.
    $("#profesor_asignatura_grupo_dialog #all").prop("checked", false);
    if($("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[tipo='Troncal'] #no_asignado:not(.oculto)").size()<2){
      $("#profesor_asignatura_grupo_dialog #all").addClass('oculto');
    }
    //Se habilita los checkbox restante por si se habían deshabilitado con las asignaturas opcionales.
    $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li input:checkbox").each (function(){ 
      $(this).prop('disabled', false);
      $(this).prop("checked",false).change();
    });

    //Se elimina la clase elected del profesor seleccionado.
    $("#profesor_asignatura_grupo_dialog #div_lista .elected").removeClass('elected');
    
    //Se informa de que el profesor no es tutor del grupo y que no se realiza la asignacion de la tutoria del grupo al profesor.
    if(tutoria_no_asignada==1 && !asignaturas.length){
      texto="<p class='justificado'>No se puede asignar la tutoría de este grupo al profesor seleccionado ya que no es el tutor del grupo.<br></p>"
          error.play();
      swal({
        title: "Asignación no permitida",
        html: texto,
        type: "error",
        width: "500px",
        confirmButtonColor: color,
        showCancelButton: false
      });
    }
    //Se informa de que la asignacion de la asignatura específica opcional no se ha realizado ya que el profesor está asignado a otra.
    else if(tutoria_no_asignada==0 && asignaturas.length){
      texto="<table><p class='justificado'>La asignación siguiente no se puede realizar ya que el profesor tiene asignada una asignatura opcional en el grupo.<br></p><thead><tr><th>Profesor</th><th>Asignatura</th></tr></thead><tbody>"
      for (var asignatura in asignaturas) { 
        texto+="<tr><td>"+profesor+"</td><td>"+asignaturas[asignatura]+"</td></tr>";
      }
      texto+="</tbody><br></p></table>"

            error.play();
      swal({
        title: "Asignación no permitida",
        html: texto,
        type: "error",
        width: "500px",
        confirmButtonColor: color,
        showCancelButton: false
      });
    }
    //Se informa los dos casos anteriores a la vez al coincidir con el mismo profesor.
    else if(tutoria_no_asignada==1 && asignaturas.length){
      texto="<table><p class='justificado'>La asignación siguiente no se puede realizar ya que el profesor tiene asignada una asignatura opcional en el grupo.<br></p><thead><tr><th>Profesor</th><th>Asignatura</th></tr></thead><tbody>"
      for (var asignatura in asignaturas) { 
        texto+="<tr><td>"+profesor+"</td><td>"+asignaturas[asignatura]+"</td></tr>";
      }
      texto+="</tbody><br></p></table>"
      texto+="<br><p class='justificado'>Además, no se puede asignar la tutoría de este grupo al profesor ya que no es el tutor del grupo.<br></p>"

      error.play();
      swal({
        title: "Asignaciones no permitidas",
        html: texto,
        type: "error",
        width: "500px",
        confirmButtonColor: color,
        showCancelButton: false
      });  
    }
  });

  // Eliminar asignación del profesor.
  $(document).on('click',"#profesor_asignatura_grupo_dialog #contenedor_asignaturas img",function(event){
    event.preventDefault();

    $(this).closest("div").addClass('oculto');
    $(this).closest("div").prev().removeClass('oculto');
    //Se añade la clase "eliminado" para eliminar los registros asignados.
    if($(this).closest("li").hasClass('back_asignado')){
      $(this).closest("li").removeClass();
      $(this).closest("li").addClass("eliminado");
    }
    else{
      $(this).closest("li").removeClass();
    }

    //Se deshabilita el aula asignada a una asignatura opcional en el listado de las otras.
    $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[opcional='1'] #asignado:not('.oculto') select option").prop('disabled', false);
    
    $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[opcional='1'] #asignado:not('.oculto') select").each (function(){
      aula=$(this).val();
      li=$(this).closest("li").attr("id");

      $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[opcional='1'][id!='"+li+"'] #asignado:not('.oculto') select").each (function(){
        $(this).find("option[value='"+aula+"']").prop('disabled', true);
      });
    });

    //Se comprueba si hay modificaciones para habilitar o deshabilitar los botones de "guardar" y "restablecer".
    if($("#profesor_asignatura_grupo_dialog #contenedor_asignaturas .back_modificado").size()>0 || $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas .eliminado").size()>0){
      $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_submit").prop("disabled",false);
      $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_restablecer").prop("disabled",false);
    }else{
      if($("#profesor_asignatura_grupo_dialog #contenedor_asignaturas .eliminado").size()>0){
        $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_restablecer").prop("disabled",false);
      }else{
        $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_restablecer").prop("disabled",true);
        $("#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_submit").prop("disabled",true);
      }
    }
    // Se muestra el checkbox si hay más de una asignatura troncal por asignar.
    if($("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[tipo='Troncal'] #no_asignado:not(.oculto)").size()>1){
      $("#profesor_asignatura_grupo_dialog #all").removeClass('oculto');
    }
  });

  $(document).on('click',"#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_restablecer",function(event){
    event.preventDefault();
    $("#asignar_profesor #contenedor_registro:not(.oculto) img").click();
  });

  //Guardar profesores asignados en un grupo.
  $(document).on('click',"#profesor_asignatura_grupo_dialog #profesor_asignatura_grupo_submit ",function(event){
    event.preventDefault();

    var asignaciones = new Object();
    var eliminados = Array();

    var index = 1;
    // Se obtienen las asignaturas modificadas.(Insertar/Actualizar)
    $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[class*='back_modificado']").each(function(){
      id=$(this).attr("id"); //Id asignatura
      valor=$(this).find("input:text").attr("id"); //Id profesor
      aula=$(this).find("select").val();//Id aula
        // Usamos un nuevo index como clave para que al recorrerlo en php no se ordene por el id asignatura
        // Se recorre en el mismo orden introducido con el index nuevo.
        asignaciones[index++] = [id, valor, aula];  
    });

    // Se obtienen las asignaturas que han sido eliminadas y que estaban asignadas al curso.(Eliminar)
    $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[class*='eliminado']").each(function(){
      id=$(this).attr("id");
      eliminados.push(id);
    });

    //Se avisa si no existe cambios.
    if($.isEmptyObject(asignaciones) && $.isEmptyObject(eliminados)){
      $(".ui-pnotify").remove();

      errorPNotify.play();

      new PNotify({
        text:'No se ha modificado ninguna asignación de profesores.',
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        width: "335px",
        stack: right_Stack_dialog,
        animate: {
          animate: true,
          in_class: "fadeInRight",
          out_class: "fadeOutRight",
        }
      });
      return false;
    }
    actualizado=0;
    $("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[class*='back_modificado']").each(function(){
      if($(this).find("input:text").attr("valor") && $(this).find("input:text").attr("value")!=$(this).find("input:text").attr("valor")){
        actualizado=1;
        return false;
      }
    });
    grupo=$(this).closest('.dialog_button').prev().attr("id"); //Id del grupo.
    // Se muestra un aviso en caso de que se haya actualizado alguna asignación registrada. (Se eliminará el horario de dicha asignación).
    if(actualizado){
      aviso.play();
      if($("#profesor_asignatura_grupo_dialog #contenedor_asignaturas li[class*='back_modificado']").attr("opcional")==1){
        texto="<p class='justificado'>Se van a actualizar profesores asignados al grupo y se eliminará el horario de la asignatura impartida por el profesor.</p><br><p class='justificado'>Además, se va a actualizar alguna asignatura específica opcional, por lo que deberá asignar de nuevo el horario de las asignaturas específicas opcionales del grupo.</p><br>¿Estas seguro de continuar? No podrás deshacer este paso...";
      }
      else{
        texto="<p class='justificado'>Se van a actualizar profesores asignados al grupo y se eliminará el horario de la asignatura impartida por el profesor.</p><br>¿Estas seguro de continuar? No podrás deshacer este paso...";
      }
      swal({
        title: "Actualización de Profesores en el Grupo",
        html: texto,
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonColor: color,
        confirmButtonText: "¡Adelante!"}).then(function () {
          $.ajax({
            type: 'POST',
            url: Routing.generate('asignar_grupo_profesores'),
            data: { asignaciones:asignaciones, eliminados:eliminados, grupo:grupo},
            dataType: 'json',
            success: function(response) {
              //Se muestra aviso si no hay nada que registrar.
              if(response.data==null){
                errorPNotify.play();
                new PNotify({
                  text:'No se ha modificado ninguna asignación de profesores.',
                  addclass: "custom",
                  type: "error",
                  shadow: true,
                  hide: true,
                  buttons: {
                    sticker: false,
                    labels:{close: "Cerrar"}
                  },
                  width: "335px",
                  stack: right_Stack_dialog,
                  animate: {
                    animate: true,
                    in_class: "fadeInRight",
                    out_class: "fadeOutRight",
                  }
                });
              }

              //Se obtiene el número de asignaciones registradas, actualizadas y eliminadas.
              var1=response.asignadas;
              var2=response.actualizadas;
              var3=response.eliminadas;
              text="";
              if(var1==1){
                text+=" <span>"+var1+"  Asignación registrada <span>";
              }
              else if(var1>1){
                text+=" <span>"+var1+"  Asignaciones registradas <span>";
              }

              if(var2==1){
                text+=" <span>"+var2+"  Asignación actualizada<span>";
              }
              else if(var2>1){
                text+=" <span>"+var2+"  Asignaciones actualizadas<span>";
              }

              if(var3==1){
                text+=" <span>"+var3+"  Asignación eliminada<span>";
              }
              else if(var3>1){
                text+=" <span>"+var3+"  Asignaciones eliminadas<span>";
              }

              //Se muestra aviso si hay asignaciones que no se han podido realizar debido a que los profesores no tiene horas lectivas disponibles.
              //Se muestra aviso si hay asignaciones que contienen asignaturas específicas opcionales y el aula ya está asignada a otra opcional..
              if(response.error!="" || response.error_opcional!=""){
                texto="";
                if(response.error!=""){
                  texto+="<table><p class='justificado'>Las asignaciones siguientes no se han realizado ya que los profesores no tienen horas lectivas disponibles.<br></p><thead><tr><th>Profesor</th><th>Asignatura</th><th>Grupo</th></tr></thead><tbody>"
                  for (var key in response.error) { 
                    texto+="<tr><td>"+response.error[key][0][0]+"</td><td>"+response.error[key][0][1]+"</td><td>"+response.error[key][0][2]+"</td></tr>"
                  }
                  texto+="</tbody><br></p></table>"  
                }

                if(response.error_opcional!=""){
                  if(response.error!=""){
                    texto+="<br>" ;
                  }
                  texto+="<table><br><p class='justificado'>Las asignaciones siguientes no se han realizado ya que otra asignatura opcional del grupo tiene asignada el mismo aula.<br></p><thead><tr><th>Profesor</th><th>Asignatura</th><th>Grupo</th></tr></thead><tbody>"
                  for (var key in response.error_opcional) { 
                    texto+="<tr><td>"+response.error_opcional[key][0][0]+"</td><td>"+response.error_opcional[key][0][1]+"</td><td>"+response.error_opcional[key][0][2]+"</td></tr>"
                  }
                  texto+="</tbody><br></p></table>"
                }
          
                error.play();
                swal({
                  title: "Asignaciones no realizadas",
                  type: "error",
                  width: "600px",
                  target: "body",
                  html: texto,
                  showCancelButton: false,
                  confirmButtonColor: color
                  }).then(function () {

                    if(var1!=0 || var2!=0 || var3!=0){
                      exito.play();
                      new PNotify({
                        text:text,
                        addclass: "custom",
                        type: "success",
                        shadow: true,
                        hide: true,
                        width: "335px",
                        animation: "fade",
                        animate_speed: 'fast',
                        delay: 4000,
                        buttons: {
                          sticker: false,
                          labels:{close: "Cerrar"}
                        },
                        stack: right_Stack_dialog,
                        animate: {
                          animate: true,
                          in_class: "fadeInRight",
                          out_class: "fadeOutRight",
                        }
                      });
                    }
                    id=$("#asignar_profesor .lista_cursos .elected").attr("id");
                    $("#tabs>div[style='display: block']").load(Routing.generate("asignar_grupo_profesores_show"), function(){
                      $("#asignar_profesor .lista_cursos button[id='"+id+"']").click();
                    });
                    $('#profesor_asignatura_grupo_dialog').dialog('close');
                    $("#asignar_horario").update_tab();
                    $("#asignar_optativa").update_tab();
                  }, function (dismiss) {

                  }
                );
                return false;
              }
              //Se muestra el número de asignaciones si no ha habido ningún aviso anterior.
              if(var1!=0 || var2!=0 || var3!=0){
                exito.play();
                new PNotify({
                  text:text,
                  addclass: "custom",
                  type: "success",
                  shadow: true,
                  hide: true,
                  width: "335px",
                  animation: "fade",
                  animate_speed: 'fast',
                  delay: 4000,
                  buttons: {
                    sticker: false,
                    labels:{close: "Cerrar"}
                  },
                  stack: right_Stack_dialog,
                  animate: {
                    animate: true,
                    in_class: "fadeInRight",
                    out_class: "fadeOutRight",
                  }
                });
              }

              id=$("#asignar_profesor .lista_cursos .elected").attr("id");
              $("#tabs>div[style='display: block']").load(Routing.generate("asignar_grupo_profesores_show"), function(){
                $("#asignar_profesor .lista_cursos button[id='"+id+"']").click();
              });
              $('#profesor_asignatura_grupo_dialog').dialog('close');
              $("#asignar_horario").update_tab();
              $("#asignar_optativa").update_tab();
            }
          })
        }, function (dismiss) {

        }
      );
    }
    //Se realiza lo mismo sin mostrar aviso para el caso de que no exista ninguna actualización.
    else{
      grupo=$(this).closest('.dialog_button').prev().attr("id"); //Id del grupo.
      $.ajax({
        type: 'POST',
        url: Routing.generate('asignar_grupo_profesores'),
        data: { asignaciones:asignaciones, eliminados:eliminados, grupo:grupo},
        dataType: 'json',
        success: function(response) {

          if(response.data==null){
            errorPNotify.play();

            new PNotify({
              text:'No se ha modificado ninguna asignación de profesores.',
              addclass: "custom",
              type: "error",
              shadow: true,
              hide: true,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              width: "335px",
              stack: right_Stack_dialog,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
          }
          //Se obtiene el número de asignaciones registradas, actualizadas y eliminadas.
          var1=response.asignadas;
          var2=response.actualizadas;
          var3=response.eliminadas;
          text="";
          if(var1==1){
            text+=" <span>"+var1+"  Asignación registrada <span>";
          }
          else if(var1>1){
            text+=" <span>"+var1+"  Asignaciones registradas <span>";
          } 

          if(var2==1){
            text+=" <span>"+var2+"  Asignación actualizada<span>";
          }
          else if(var2>1){
            text+=" <span>"+var2+"  Asignaciones actualizadas<span>";
          }

          if(var3==1){
            text+=" <span>"+var3+"  Asignación eliminada<span>";
          }
          else if(var3>1){
            text+=" <span>"+var3+"  Asignaciones eliminadas<span>";
          }

          //Se muestra aviso si hay asignaciones que no se han podido realizar debido a que los profesores no tiene horas lectivas disponibles.
          //Se muestra aviso si hay asignaciones que contienen asignaturas específicas opcionales y el aula ya está asignada a otra opcional..
          if(response.error!="" || response.error_opcional!=""){
            texto="";
            if(response.error!=""){
              texto+="<table><p class='justificado'>Las asignaciones siguientes no se han realizado ya que los profesores no tienen horas lectivas disponibles.<br></p><thead><tr><th>Profesor</th><th>Asignatura</th><th>Grupo</th></tr></thead><tbody>"
              for (var key in response.error) { 
                texto+="<tr><td>"+response.error[key][0][0]+"</td><td>"+response.error[key][0][1]+"</td><td>"+response.error[key][0][2]+"</td></tr>"
              }
              texto+="</tbody><br></p></table>"  
            }

            if(response.error_opcional!=""){
              if(response.error!=""){
                texto+="<br><br>" ;
              }
              texto+="<table><br><p class='justificado'>Las asignaciones siguientes no se han realizado ya que otra asignatura opcional del grupo tiene asignada el mismo aula.<br></p><thead><tr><th>Profesor</th><th>Asignatura</th><th>Grupo</th></tr></thead><tbody>"
              for (var key in response.error_opcional) { 
                texto+="<tr><td>"+response.error_opcional[key][0][0]+"</td><td>"+response.error_opcional[key][0][1]+"</td><td>"+response.error_opcional[key][0][2]+"</td></tr>"
              }
              texto+="</tbody><br></p></table>"
            }
          
            error.play();
            swal({
              title: "Asignaciones no realizadas ",
              html: texto,
              type: "error",
              showCancelButton: false,
              confirmButtonColor: color,
              width: "600px"
              }).then(function () {

                if(var1!=0 || var2!=0 || var3!=0){
                  exito.play();
                  new PNotify({
                    text:text,
                    addclass: "custom",
                    type: "success",
                    shadow: true,
                    hide: true,
                    width: "335px",
                    animation: "fade",
                    animate_speed: 'fast',
                    delay: 4000,
                    buttons: {
                      sticker: false,
                      labels:{close: "Cerrar"}
                    },
                    stack: right_Stack_dialog,
                    animate: {
                      animate: true,
                      in_class: "fadeInRight",
                      out_class: "fadeOutRight",
                    }
                  });
                }
                id=$("#asignar_profesor .lista_cursos .elected").attr("id");
                $("#tabs>div[style='display: block']").load(Routing.generate("asignar_grupo_profesores_show"), function(){
                  $("#asignar_profesor .lista_cursos button[id='"+id+"']").click();
                });
                $('#profesor_asignatura_grupo_dialog').dialog('close');
                $("#asignar_horario").update_tab();
                $("#asignar_optativa").update_tab();
              }, function (dismiss) {

              }
            );
            return false;
          }

          if(var1!=0 || var2!=0 || var3!=0){
            exito.play();
            new PNotify({
              text:text,
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              width: "335px",
              animation: "fade",
              animate_speed: 'fast',
              delay: 4000,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack_dialog,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
          }

          id=$("#asignar_profesor .lista_cursos .elected").attr("id");
          $("#tabs>div[style='display: block']").load(Routing.generate("asignar_grupo_profesores_show"), function(){
            $("#asignar_profesor .lista_cursos button[id='"+id+"']").click();
          });
          $('#profesor_asignatura_grupo_dialog').dialog('close');
          $("#asignar_horario").update_tab();
          $("#asignar_optativa").update_tab();
        }
      })
    }
  });

  //Se elimina las asignaciones de profesores de un grupo.
  $(document).on('click',"#asignar_profesor #eliminar_grupo ",function(event){
    event.preventDefault();

    //Se avisa si no hay profesores asignados al grupo.
    if($("#asignar_profesor #contenedor_registro:not(.oculto) tr:not(.cabecera_tipo) td[class='']").size()==0){
      $(".ui-pnotify").remove();

      errorPNotify.play();

      new PNotify({
        text:'No hay profesores asignados en el grupo.',
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        stack: right_Stack,
        animate: {
          animate: true,
          in_class: "fadeInRight",
          out_class: "fadeOutRight",
        }
      });
      $("#asignar_profesor #eliminar_grupo").prop("disabled",true);
      return false;
    }
    nombre_grupo=$("#asignar_profesor .lista_cursos .elected").text();
    aviso.play();
    swal({
      title: "Eliminación de Asignación de Profesores",
      html: "<p>Se van a eliminar las asignaciones de profesores de "+nombre_grupo+".</p><br>¿Estas seguro de continuar? No podrás deshacer este paso...",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        grupo=$("#asignar_profesor #eliminar_grupo").attr("grupo"); //Id del grupo.
        $.ajax({
          type: 'POST',
          url: Routing.generate('eliminar_asignaciones_grupo'),
          data: {grupo:grupo},
          dataType: 'json',
          success: function(response) {
            if(response.data==null){
              errorPNotify.play();

              new PNotify({
                text:'No hay profesores asignados en el grupo.',
                addclass: "custom",
                type: "error",
                shadow: true,
                hide: true,
                buttons: {
                  sticker: false,
                  labels:{close: "Cerrar"}
                },
                stack: right_Stack,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              });
              return false;
            }
            exito.play();
            new PNotify({
              text:"Se han eliminado los profesores asignados al grupo.",
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              width: "335px",
              animation: "fade",
              animate_speed: 'fast',
              delay: 4000,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
            id=$("#asignar_profesor .lista_cursos .elected").attr("id");
            $("#tabs>div[style='display: block']").load(Routing.generate("asignar_grupo_profesores_show"), function(){
              $("#asignar_profesor .lista_cursos button[id='"+id+"']").click();
            });
          }
        })
      }, function (dismiss) {

      }
    );
  });

  //Se elimina las asignaciones de profesores de todos los grupos.
  $(document).on('click',"#asignar_profesor #eliminar_todo ",function(event){
    event.preventDefault();

    //Se avisa si no hay profesores asignados en ningún grupo.
    if($("#asignar_profesor #contenedor_registro tr:not(.cabecera_tipo) td[class='']").size()==0){
      $(".ui-pnotify").remove();

      errorPNotify.play();

      new PNotify({
        text:'No hay profesores asignados en ningún grupo.',
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        stack: right_Stack,
        animate: {
          animate: true,
          in_class: "fadeInRight",
          out_class: "fadeOutRight",
        }
      });
      $("#asignar_profesor #eliminar_todo").prop("disabled",true);
      return false;
    }
    nombre_grupo=$("#asignar_profesor .lista_cursos .elected").text();
    aviso.play();
    swal({
      title: "Eliminación de Asignación de Profesores",
      html: "<p>Se van a eliminar las asignaciones de todos los profesores.</p><br>¿Estas seguro de continuar? No podrás deshacer este paso...",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        $.ajax({
          type: 'POST',
          url: Routing.generate('eliminar_todas_asignaciones_grupos'),
          dataType: 'json',
          success: function(response) {
            if(response.data==null){
              errorPNotify.play();

              new PNotify({
                text:'No hay profesores asignados en ningún grupo.',
                addclass: "custom",
                type: "error",
                shadow: true,
                hide: true,
                buttons: {
                  sticker: false,
                  labels:{close: "Cerrar"}
                },
                stack: right_Stack,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              });
              return false;
            }
            exito.play();
            new PNotify({
              text:"Se han eliminado las asignaciones de todos los profesores.",
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              width: "335px",
              animation: "fade",
              animate_speed: 'fast',
              delay: 4000,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
            id=$("#asignar_profesor .lista_cursos .elected").attr("id");
            $("#tabs>div[style='display: block']").load(Routing.generate("asignar_grupo_profesores_show"), function(){
              $("#asignar_profesor .lista_cursos button[id='"+id+"']").click();
            });
          }
        })
      }, function (dismiss) {

      }
    );
  });

/////////////////////////////////
//  Asignar horarios a grupos  //
/////////////////////////////////

  //Efecto del botón pulsado en la lista.
  $(document).on('click',"#asignar_horario_grupos .contenido_lista button",function(event){
    event.preventDefault();
    id=$(this).attr("id");
    $("#asignar_horario_grupos #contenedor_registro").addClass('oculto');
    $("#asignar_horario_grupos button").removeClass('elected');
    $(this).addClass('elected');
    $("#asignar_horario_grupos #contenedor_registro[idcurso='"+id+"']").removeClass('oculto');

    //Se asigna el nombre y el id del grupo al botón de eliminar horarios del grupo.
    $("#asignar_horario_grupos #eliminar_grupo span").text($("#asignar_horario_grupos .lista_cursos .elected").text());
    $("#asignar_horario_grupos #eliminar_grupo").attr("grupo",$("#asignar_horario_grupos #contenedor_registro:not(.oculto) #cabecera_lista").attr("grupo"));
      
    //Se muestra o se oculta el botón de Eliminar horario del grupo.
    if($("#asignar_horario_grupos #contenedor_registro:not(.oculto) tr .dataTables_empty").size()>0){
      $("#asignar_horario_grupos #eliminar_grupo").prop("disabled",true);
    }
    else{
      $("#asignar_horario_grupos #eliminar_grupo").prop("disabled",false);
    }
        
    //Se muestra o se oculta la cabecera de la tabla y la información del horario seleccionada.
    if($("#asignar_horario_grupos #contenedor_registro:not(.oculto) tr .dataTables_empty").size()==0){
      $("#asignar_horario_grupos #contenedor_datos").removeClass('oculto');
      $("#asignar_horario_grupos #lista_asignaturas thead").removeClass('oculto');
    }
    else{
      $("#asignar_horario_grupos #contenedor_datos").addClass('oculto');
      $("#asignar_horario_grupos #lista_asignaturas thead").addClass('oculto');  
    }
  });

  //Se muestra la información del horario al colocar el cursor sobre un módulo de clase.
  //Se usa para evitar que se muestre la información del "td" que contiene las asignaturas opcionales.
  $(document).on("mouseenter","#asignar_horario_grupos #contenedor_registro td", function(event){
    event.stopPropagation(); 
  });

  //Se muestra la información del horario al colocar el cursor sobre un módulo de clase.
  $(document).on("mouseenter","#asignar_horario_grupos #contenedor_registro td:not(.horario)", function(event){
    event.preventDefault();

    // Se evita que se muestre el mensaje predeterminado si pasamos de un enlace a otro.
    $("#asignar_horario_grupos #contenedor_datos #sin_seleccionar").addClass("oculto");
    
    //Se comprueba que no es un aviso predeterminado.
    if(!$(this).hasClass("dataTables_empty")){
      if($(this).attr("profesor")){
        profesor=$(this).attr("profesor");
      }
      else{
        profesor="(Sin asignar)";
      }
      $("#asignar_horario_grupos #contenedor_datos #seleccionado #profesor").text(profesor);

      if($(this).attr("asignatura")){
        asignatura=$(this).attr("asignatura");
      }
      else{
        asignatura="(Sin asignar)";
      }
      $("#asignar_horario_grupos #contenedor_datos #seleccionado #asignatura").text(asignatura);

      if($(this).attr("aula")){
        aula=$(this).attr("aula");
      }
      else{
        aula="(Sin asignar)";
      }
      $("#asignar_horario_grupos #contenedor_datos #seleccionado #aula").text(aula);

      $("#asignar_horario_grupos #contenedor_datos #seleccionado").removeClass("oculto");
      $("#asignar_horario_grupos #contenedor_datos #sin_seleccionar").addClass("oculto");     
    }
    else{
      $("#asignar_horario_grupos #contenedor_datos #seleccionado #profesor").text("");
      $("#asignar_horario_grupos #contenedor_datos #seleccionado #asignatura").text("");
      $("#asignar_horario_grupos #contenedor_datos #seleccionado #aula").text("");

      $("#asignar_horario_grupos #contenedor_datos #seleccionado").addClass("oculto");
      $("#asignar_horario_grupos #contenedor_datos #sin_seleccionar").removeClass("oculto");
    }

  });

  // Se elimina la información mostrada del horario al quitar el puntero de un módulo de clase.
  $(document).on("mouseleave","#asignar_horario_grupos  #contenedor_registro td", function () {
    $("#asignar_horario_grupos #contenedor_datos #seleccionado #profesor").text("");
    $("#asignar_horario_grupos #contenedor_datos #seleccionado #asignatura").text("");
    $("#asignar_horario_grupos #contenedor_datos #seleccionado #aula").text("");

    $("#asignar_horario_grupos #contenedor_datos #seleccionado").addClass("oculto");
    $("#asignar_horario_grupos #contenedor_datos #sin_seleccionar").removeClass("oculto");
  });

  $(document).on('click',"#asignar_horario_grupos a[id$='_modal']",function(event){
    event.preventDefault();
    var id=$(this).closest("div").attr("grupo");
    $('#asignar_horario_grupo_dialog').load(Routing.generate("asignar_horario_new", {id:id}), function(){
    }).dialog('open'); 

  });

  //Se elimina el horario de un grupo.
  $(document).on('click',"#asignar_horario_grupos #eliminar_grupo ",function(event){
    event.preventDefault();

    //Se avisa si no hay horario asignado al grupo.
    if($("#asignar_horario_grupos #contenedor_registro:not(.oculto) tr .dataTables_empty").size()>0){
      $(".ui-pnotify").remove();

      errorPNotify.play();

      new PNotify({
        text:'No hay horario asignado al grupo.',
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        stack: right_Stack,
        animate: {
          animate: true,
          in_class: "fadeInRight",
          out_class: "fadeOutRight",
        }
      });
      $("#asignar_horario_grupos #eliminar_grupo").prop("disabled",true);
      return false;
    }
    nombre_grupo=$("#asignar_horario_grupos .lista_cursos .elected").text();
    aviso.play();
    swal({
      title: "Eliminación de Horarios de Grupos",
      html: "<p>Se va a eliminar el horario de <span class='negrita'>"+nombre_grupo+"</span>.</p><br>¿Estas seguro de continuar? No podrás deshacer este paso...",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        grupo=$("#asignar_horario_grupos #eliminar_grupo").attr("grupo"); //Id del grupo.
        $.ajax({
          type: 'POST',
          url: Routing.generate('eliminar_horario_grupo'),
          data: {grupo:grupo},
          dataType: 'json',
          success: function(response) {
            if(response.data==null){
              errorPNotify.play();

              new PNotify({
                text:'No hay horario asignado al grupo.',
                addclass: "custom",
                type: "error",
                shadow: true,
                hide: true,
                buttons: {
                  sticker: false,
                  labels:{close: "Cerrar"}
                },
                stack: right_Stack,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              });
              return false;
            }
            exito.play();
            new PNotify({
              text:"Se ha eliminado el horario asignado al grupo.",
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              animation: "fade",
              width: "335px",
              animate_speed: 'fast',
              delay: 4000,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
            id=$("#asignar_horario_grupos .lista_cursos .elected").attr("id");
            $("#tabs>div[style='display: block']").load(Routing.generate("asignar_horario_show"), function(){
              $("#asignar_horario_grupos .lista_cursos button[id='"+id+"']").click();
            });
          }
        })
      }, function (dismiss) {

      }
    );
  });

  //Se elimina los horarios de todos los grupos.
  $(document).on('click',"#asignar_horario_grupos #eliminar_todo ",function(event){
    event.preventDefault();

    //Se avisa si no hay horarios en ningún grupo.
    if($("#asignar_horario_grupos #contenedor_registro").size()==$("#asignar_horario_grupos #contenedor_registro tr .dataTables_empty").size()){
      $(".ui-pnotify").remove();

      errorPNotify.play();

      new PNotify({
        text:'No hay horarios asignados a ningún grupo.',
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        stack: right_Stack,
        animate: {
          animate: true,
          in_class: "fadeInRight",
          out_class: "fadeOutRight",
        }
      });
      $("#asignar_horario_grupos #eliminar_todo").prop("disabled",true);
      return false;
    }
    nombre_grupo=$("#asignar_horario_grupos .lista_cursos .elected").text();
    aviso.play();
    swal({
      title: "Eliminación de Horarios de Grupos",
      html: "<p>Se van a eliminar los horarios de todos los grupos.</p><br>¿Estas seguro de continuar? No podrás deshacer este paso...",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        $.ajax({
          type: 'POST',
          url: Routing.generate('eliminar_todos_horarios_grupos'),
          dataType: 'json',
          success: function(response) {
            if(response.data==null){
              errorPNotify.play();

              new PNotify({
                text:'No hay horarios asignados a ningún grupo.',
                addclass: "custom",
                type: "error",
                shadow: true,
                hide: true,
                buttons: {
                  sticker: false,
                  labels:{close: "Cerrar"}
                },
                stack: right_Stack,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              });
              return false;
            }
            exito.play();
            new PNotify({
              text:"Se han eliminado los horarios de todos los grupos.",
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              width: "335px",
              animation: "fade",
              animate_speed: 'fast',
              delay: 4000,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
            id=$("#asignar_horario_grupos .lista_cursos .elected").attr("id");
            $("#tabs>div[style='display: block']").load(Routing.generate("asignar_horario_show"), function(){
              $("#asignar_horario_grupos .lista_cursos button[id='"+id+"']").click();
            });
          }
        })
      }, function (dismiss) {

      }
    );
  });

  //Se abre una ventana para seleccionar el tipo de documento a descargar.
  $(document).on('click',"#asignar_horario_grupos #horario_pdf img ",function(event){
    event.preventDefault();
    id=$(this).closest("#contenedor_registro").find("#cabecera_lista").attr("grupo");
    $('#generar_horarios_pdf').load(Routing.generate("generar_horarios_pdf", {id:id}), function(){
    }).dialog('open'); 
  });

  //Se ejecuta el controlador para la descarga el horario del grupo en PDF.
  $(document).on('click',"#generar_horarios_pdf #btn_horario_grupo ",function(event){
    event.preventDefault();
    id=$(this).attr("grupo");
    window.open(Routing.generate('horario_grupo_pdf', {id:id}),"_self");
    $("#asignar_horario_grupos #cargando_horario_pdf").removeClass('oculto');
    $("#asignar_horario_grupos #horario_pdf").addClass('oculto');
    setTimeout(function() {
      $("#generar_horarios_pdf").load(Routing.generate('horario_grupo_pdf', {id:id}), function(){
        $("#asignar_horario_grupos #cargando_horario_pdf").addClass('oculto');
        $("#asignar_horario_grupos #horario_pdf").removeClass('oculto');
        $('#generar_horarios_pdf').empty();
      });
    },20);
    $('#generar_horarios_pdf').dialog('close');
  });

  //Se ejecuta el controlador para la descarga todos los horarios en PDF.
  $(document).on('click',"#generar_horarios_pdf #btn_horarios ",function(event){
    event.preventDefault();
    window.open(Routing.generate('horarios_grupos_pdf'),'_self');
    $("#asignar_horario_grupos #cargando_horario_pdf").removeClass('oculto');
    $("#asignar_horario_grupos #horario_pdf").addClass('oculto');
    setTimeout(function() {
      $("#generar_horarios_pdf").load(Routing.generate('horarios_grupos_pdf'), function(){
        $("#asignar_horario_grupos #cargando_horario_pdf").addClass('oculto');
        $("#asignar_horario_grupos #horario_pdf").removeClass('oculto');
        $('#generar_horarios_pdf').empty();
      });
    },20);
    $('#generar_horarios_pdf').dialog('close');

  });
  
  //Se elimina una asignatura de la lista.
  $(document).on("click","#asignar_horario_grupo_dialog li span",function(event){
    event.preventDefault();
    //Se establece el padding para los elementos en la lista.
    $(this).closest("li").css("padding","7px");

    //Se añade la clase eliminada a la lista cuando anteriormente tenía una asignatura asiganada.
    if($(this).closest("ul").attr("carga")){
      $(this).closest("ul").removeClass('asignada');
      $(this).closest("ul").removeClass('modificada');
      $(this).closest("ul").addClass('eliminada');
    }
    else{
      $(this).closest("ul").removeClass('modificada');
      $(this).closest("ul").removeClass('eliminada');
    }

    id=$(this).closest("li").attr("id");
    //Se pasa el elemento de la lista a su posición inicial en la lista de asignaturas y se muestra la lista que estaba oculta.
    $(this).closest("li").appendTo('#asignar_horario_grupo_dialog #pie_horario ul[asig="'+id+'"]');
    $('#asignar_horario_grupo_dialog #pie_horario ul[asig="'+id+'"]').removeClass('oculto');
    //Se actualiza el nombre por las siglas, se elimina el efecto sombra y el elemento span.
    $(this).closest("li").text($(this).closest("li").attr("siglas"));
    $(this).remove();
    $("#asignar_horario_grupo_dialog #pie_horario li").removeClass('box-shadow');

    //Se añade la clase "sortable" a las listas de la tabla y se elimina luego a las listas que tienen asignatura asignada para que no pueda añadirse otra.
    $("#asignar_horario_grupo_dialog #tabla_horario ul").addClass('sortable');
    $("#asignar_horario_grupo_dialog #tabla_horario li").closest("ul").removeClass('sortable');

    //Se comprueba si hay modificación para mostrar los botones. 
    $("#asignar_horario_grupo_dialog #horario_grupo_submit").prop("disabled",true);
    $("#asignar_horario_grupo_dialog #horario_grupo_restablecer").prop("disabled",true);

    if($("#asignar_horario_grupo_dialog #tabla_horario td ul").hasClass('eliminada') || $("#asignar_horario_grupo_dialog #tabla_horario td ul").hasClass('modificada') ){
      $("#asignar_horario_grupo_dialog #horario_grupo_submit").prop("disabled",false);
      $("#asignar_horario_grupo_dialog #horario_grupo_restablecer").prop("disabled",false);
    }

    //Se elimina la clase "rojo" de las celdas donde hay una asignatura asignada, por si permanece alguna. 
    $("#asignar_horario_grupo_dialog #tabla_horario li").closest("td").removeClass('rojo');

  });


  //Se muestra la info de la asignatura.
  $(document).on("mouseenter","#asignar_horario_grupo_dialog li", function(){
    //Se comprueba que tenga el atributo id para que no se mande 0, que es el valor del id del elemento "opcional".
    if($(this).attr("id")){
      $("#asignar_horario_grupo_dialog #contenedor_info").load(Routing.generate('datos_asignatura', {id:$(this).attr("id"),id_grupo:$(this).closest("fieldset").attr("id")}), function(){
      });
    }
  });

  //Se obtiene los módulos no disponible para la asignaruta seleccionada y se muestra en el horario editable.
  $(document).on("mousedown","#asignar_horario_grupo_dialog li", function(event){
    //Se eliminan todos los iconos añadidos a las celdas anteriormente, para evitar que aparezcan varios al presionar varias veces el ratón.
    $("#asignar_horario_grupo_dialog #tabla_horario td img").remove();
    //Se hace clicksi se mantiene el ratón en el span.
    if($(event.target).is('span')){
      $(this).find("span").click();
      $(this).mouseup();
      $("#asignar_horario_grupo_dialog #tabla_horario li").closest("td").removeClass('rojo');
      return false;
    }
    //Se comprueba que se pulsa el botón derecho del ratón.
    if(event.which == 1){
      //Se elimina la clase "bloqueo" que se añade a los módulos no disponibles.
      $("#asignar_horario_grupo_dialog #tabla_horario td").removeClass('bloqueo');
      //Se devuelve a su sitio todas las listas que han podido quedar en "listas_nulas" tras moverlas al obtener los módulos no disponibles.
      $("#asignar_horario_grupo_dialog #listas_nulas ul").each (function(){ 
        $(this).appendTo($("#asignar_horario_grupo_dialog #tabla_horario tr[id='"+$(this).attr("horario")+"'] td[id='"+$(this).attr("dia")+"']"));
      }); 
      //Se añade la clase "move" al elemento seleccionado para comprobar si entra por error en un módulo no disponible al mover el ratón rapidamente.
      //$(this).addClass('move');

      //Se obtiene los módulos donde el profesor no puede impartir clase.
      v_profesor=$(this).attr("profesor");
      v_aula=$(this).attr("aula");
      grupo=$(this).closest("fieldset").attr("id");
      var profesor= Array();
      var aula= Array();
      if($(this).attr("id")!=0){
        profesor.push(v_profesor);
        aula.push(v_aula);
      }
      else{
        arr_prof=v_profesor.split('-');
        arr_aul=v_aula.split('-');

        for (var key in arr_prof) {
          profesor.push(arr_prof[key]);
        }

        if(arr_aul.length==1 && arr_aul[0]==""){
          aula.push(arr_aul[0]);
        }else{
          for (var key in arr_aul) {
            aula.push(arr_aul[key]);
          }
        }
      }

      $.ajax({
        type: 'POST',
        url: Routing.generate('obtener_modulos_ocupados'),
        data: {profesor:profesor, aula:aula, grupo:grupo},
        dataType: 'json',
        success: function(response) {
          //Se obtiene los módulos donde el profesor está ocupado.
          for (var key in response.profesor){
            dia=key;
            horario=response.profesor[key];
            //Se obtiene la lista no permitida de la tabla.
            ul=$("#asignar_horario_grupo_dialog #tabla_horario tr[id='"+horario+"'] td[id='"+dia+"'] ul");
            //Se añade la clase "bloqueo" a la celda de la lista no permitida, para no permitir insertar un elemento o comprobar posibles errores.
            ul.closest("td").addClass('bloqueo');
            //Se comprueba que la la lista no tenga una asignatura ya asignada.
            //Para que no se inserte elelemento li seleccionado al mover el cursor rápidamente, comprobamos que la lista existente no tenga la clase "ui-state-highligh" del placeholder.
            if(ul.find("li").size()==0 || (ul.find("li").size()>0 && ul.find("li").hasClass('ui-state-highligh'))){
                //Se elimina la clase del placeholder de la celda por si se permanece al soltar.
                ul.closest("td").removeClass('placeholder_list_table_td');
                //Se añade la clase "rojo" para mostrar el color de la celda no disponible de la tabla.
                ul.closest("td").addClass('rojo');
                //Se añade el icono del profesor para indicar que el profesor está ocupado en dicho módulo.
                ul.closest("td").append("<img src='/Symfony/web/bundles/backend/images/menu/teacher_no_dis.png'>");
                //Desplazamos la lista no permitida al div "listas_nulas", para que no se pueda insertar ningún elemento.
                ul.appendTo('#listas_nulas');
            }
          }
          //Se obtiene los módulos donde el aula está ocupada.
          for (var key in response.aula){

            dia=key;
            horario=response.aula[key];
          
            //Se comrpueba  si la lista sigue en la celda de la tabla, por si se ha desplazado anteriormente comprobando al profesor.
            if($("#asignar_horario_grupo_dialog #tabla_horario tr[id='"+horario+"'] td[id='"+dia+"'] ul").size()>0){
              //Se obtiene la lista no permitida de la tabla.
              ul=$("#asignar_horario_grupo_dialog #tabla_horario tr[id='"+horario+"'] td[id='"+dia+"'] ul");
              //Se añade la clase "bloqueo" a la celda de la lista no permitida, para no permitir insertar un elemento o comprobar posibles errores.
              ul.closest("td").addClass('bloqueo');
              //Se comprueba que la la lista no tenga una asignatura ya asignada.
              //Para que no se inserte elelemento li seleccionado al mover el cursor rápidamente, comprobamos que la lista existente no tenga la clase "ui-state-highligh" del placeholder.
              if(ul.find("li").size()==0 || (ul.find("li").size()>0 && ul.find("li").hasClass('ui-state-highligh'))){
                //Se elimina la clase del placeholder de la celda por si se permanece al soltar.
                ul.closest("td").removeClass('placeholder_list_table_td');
                //Se añade la clase "rojo" para mostrar el color de la celda no disponible de la tabla.
                ul.closest("td").addClass('rojo');
                //Se añade el icono del profesor para indicar que el aula está ocupada en dicho módulo.
                ul.closest("td").append("<img src='/Symfony/web/bundles/backend/images/menu/room_no_dis.png'>");
                //Desplazamos la lista no permitida al div "listas_nulas", para que no se pueda insertar ningún elemento.
                ul.appendTo('#listas_nulas');
              }
            }//Si ya estaba desplazada, solo hay que añadir el icono para indicar que el aula está ocupada en dicho módulo.
            else{
              $("#asignar_horario_grupo_dialog #tabla_horario tr[id='"+horario+"'] td[id='"+dia+"']").append("<img src='/Symfony/web/bundles/backend/images/menu/room_no_dis.png'>");
            }
          }
        } 
      })
      //Se elimina la clase "rojo" de las celdas donde hay una asignatura asignada, por si anteriormente se no se eliminase. 
      $("#asignar_horario_grupo_dialog #tabla_horario li").closest("td").removeClass('rojo');
    }
  });

  //Se elimina toda la información de los módulos no disponible al soltar el elemento.
  $(document).on("mouseup","#asignar_horario_grupo_dialog li", function(){
    //Se eliminan todos los iconos añadidos a las celdas anteriormente.
    $("#asignar_horario_grupo_dialog #tabla_horario td img").remove();

    //Se le añade un retraso para que se comprueba todo bien una vez acabada la asignación.
    setTimeout(function() {
      //Se devuelven a su posición anterior los elementos que se hayan podido quedar almacenados en "listas_nulas" por movimientos rápidos de ratón.
      $("#asignar_horario_grupo_dialog #tabla_horario #listas_nulas li").each (function(){
        //Se comprueba si estaba en una celda o en la lista inicial de asignaturas.
        if(dia==0 && horario==0){//Lista Inicial
          id=$(this).attr("id");
          $(this).appendTo('#asignar_horario_grupo_dialog #tabla_horario li[asig="'+id+'"]');
          $('#asignar_horario_grupo_dialog #tabla_horario li[asig="'+id+'"]').removeClass('oculto');
          error.play();
        }
        else{//Celda de la tabla.
          dia=$(this).attr("dia");
          horario=$(this).attr("horario");
          $(this).appendTo('#asignar_horario_grupo_dialog #tabla_horario tr[id="'+horario+'"] td[id="'+dia+'"] ul');
          error.pause();
          error.currentTime=0.0;
          error.play();
        }
      });  

      //Se devuelven a su posición anterior los elementos que se hayan podido quedar almacenados en una lista no disponible por movimientos rápidos de ratón.
      //Se realiza para cada elemento insertado en una celda con la clase "bloqueo" añadida anteriormente a todas las celdas no disponibles.
      $("#asignar_horario_grupo_dialog #tabla_horario .bloqueo li").each (function(){
        //Se comprueba si estaba en una celda o en la lista inicial de asignaturas.
        if(dia==0 && horario==0){//Lista Inicial
          id=$(this).attr("id");
          $(this).appendTo('#asignar_horario_grupo_dialog #tabla_horario li[asig="'+id+'"]');
          $('#asignar_horario_grupo_dialog #tabla_horario li[asig="'+id+'"]').removeClass('oculto');
        }
        else{//Celda de la tabla.
          dia=$(this).attr("dia");
          horario=$(this).attr("horario");
          $(this).appendTo('#asignar_horario_grupo_dialog #tabla_horario tr[id="'+horario+'"] td[id="'+dia+'"] ul');
          error.pause();
          error.currentTime=0.0;
          error.play();
        }
      });

      //Se devuelve a su posición inicial todas las listas no disponibles almacenadas en "listas_nulas".
      $("#asignar_horario_grupo_dialog #listas_nulas ul").each (function(){ 
        $(this).appendTo($("#asignar_horario_grupo_dialog #tabla_horario tr[id='"+$(this).attr("horario")+"'] td[id='"+$(this).attr("dia")+"']"));
      }); 
      //Se elimina la clase "rojo" de todas las celdas.
      $("#asignar_horario_grupo_dialog #tabla_horario td").removeClass('rojo');
      //Se eliminan todos los iconos añadidos a las celdas anteriormente.
      $("#asignar_horario_grupo_dialog #tabla_horario td img").remove();
    },150);
  });

  //Se restablece el horario editable del grupo.
  $(document).on("click","#asignar_horario_grupo_dialog #horario_grupo_restablecer", function(event){
    event.preventDefault();
    $("#asignar_horario_grupos #contenedor_registro:not(.oculto) #añadir_modal #img_2").click();
  });


  //Se guarda el horario editado del grupo.
  $(document).on("click","#asignar_horario_grupo_dialog #horario_grupo_submit", function(event){
    event.preventDefault();

    var modificadas = new Object();
    var eliminadas = new Object();
    
    var index = 1;
    // Se obtienen las asignaturas modificadas.(Nuevas/actualizadas)
    $("#asignar_horario_grupo_dialog #tabla_horario ul[class*='modificada']").each(function(){

      id=$(this).find("li").attr("id");
      dia=$(this).attr("dia");
      horario=$(this).attr("horario");
      modificadas[index++] = [id, dia, horario];  
    });

    var index = 1;
    // Se obtienen las asignaturas eliminadas.
    $("#asignar_horario_grupo_dialog #tabla_horario ul[class*='eliminada']").each(function(){

      id=$(this).attr("carga");
      dia=$(this).attr("dia");
      horario=$(this).attr("horario");
      eliminadas[index++] = [id, dia, horario];  
    });

    //Si no existe cambios se avisa.
    if($.isEmptyObject(modificadas) && $.isEmptyObject(eliminadas)){
      $('#asignatura_curso_dialog').dialog('close');
      errorPNotify.play();

      new PNotify({
        text:'No hay modificaciones en el horario para este grupo.',
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        stack: right_Stack_dialog ,
        animate: {
          animate: true,
          in_class: "fadeInRight",
          out_class: "fadeOutRight",
        }
      });
      $("#asignar_horario_grupo_dialog #horario_grupo_submit").prop("disabled",true);
      $("#asignar_horario_grupo_dialog #horario_grupo_restablecer").prop("disabled",true);
      return false;
    }

    grupo=$(this).closest(".dialog_button").prev().attr("id");

    $.ajax({
      type: 'POST',
      url: Routing.generate('asignar_horario_grupo'),
      data: {grupo:grupo, modificadas:modificadas, eliminadas:eliminadas},
      dataType: 'json',
      success: function(response) {
        if(response.data==null){
          ('#asignar_horario_grupo_dialog').dialog('close');
          errorPNotify.play();

          new PNotify({
            text:'No hay modificaciones en el horario para este grupo.',
            addclass: "custom",
            type: "error",
            shadow: true,
            hide: true,
            buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
            },
            stack: right_Stack_dialog,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
          });
          return false;
        }
      
        $(".ui-pnotify").remove();

        exito.play();
        new PNotify({
          text:"Horario del grupo actualizado.",
          addclass: "custom",
          type: "success",
          shadow: true,
          width: "335px",
          hide: true,
          buttons: {
            sticker: false,
            labels:{close: "Cerrar"}
          },
          stack: right_Stack_dialog,
          animate: {
            animate: true,
            in_class: "fadeInRight",
            out_class: "fadeOutRight",
          }
        });
      
        id=$("#asignar_horario_grupos .lista_cursos .elected").attr("id");
        $("#tabs>div[style='display: block']").load(Routing.generate("asignar_horario_show"), function(){
          $("#asignar_horario_grupos .lista_cursos button[id='"+id+"']").click();
        });

        $('#asignar_horario_grupo_dialog').dialog('close');
        
        //Actualización de la pestaña.
        $("#clases_impartidas").update_tab();
      }
    })
  });


//////////////////////////////////////
//  Clases Impartidas por Profesor  //
//////////////////////////////////////

  //Efecto del botón pulsado en la lista de profesores.
  $(document).on('click',"#clases_impartidas .contenido_lista button",function(event){
    event.preventDefault();
    id=$(this).attr("id");
    $("#clases_impartidas #contenedor_registro").addClass('oculto');
    $("#clases_impartidas button").removeClass('elected');
    $(this).addClass('elected');
    $("#clases_impartidas #contenedor_registro[index='"+id+"']").removeClass('oculto');
  });

  //Se muestra la información del horario al colocar el cursor sobre un módulo de clase.
  $(document).on("mouseenter","#clases_impartidas #contenedor_registro td:not(.horario)", function(event){
    event.preventDefault();

    // Se evita que se muestre el mensaje predeterminado si pasamos de un enlace a otro.
    $("#clases_impartidas #contenedor_datos #sin_seleccionar").addClass("oculto");
    
    //Se comprueba que no es un aviso predeterminado.
    if(!$(this).hasClass("dataTables_empty")){
      if($(this).attr("asignatura")){
        asignatura=$(this).attr("asignatura");
      }
      else{
        asignatura="(Sin asignar)";
      }
      $("#clases_impartidas #contenedor_datos #seleccionado #asignatura").text(asignatura);

      if($(this).attr("aula")){
        aula=$(this).attr("aula");
      }
      else{
        aula="(Sin asignar)";
      }
      $("#clases_impartidas #contenedor_datos #seleccionado #aula").text(aula);

      if($(this).attr("curso")){
        curso=$(this).attr("curso");
      }
      else{
        curso="(Sin asignar)";
      }
      $("#clases_impartidas #contenedor_datos #seleccionado #curso").text(curso);

      $("#clases_impartidas #contenedor_datos #seleccionado").removeClass("oculto");
      $("#clases_impartidas #contenedor_datos #sin_seleccionar").addClass("oculto");     
    }
    else{
      $("#clases_impartidas #contenedor_datos #seleccionado #asignatura").text("");
      $("#clases_impartidas #contenedor_datos #seleccionado #aula").text("");
      $("#clases_impartidas #contenedor_datos #seleccionado #curso").text("");

      $("#clases_impartidas #contenedor_datos #seleccionado").addClass("oculto");
      $("#clases_impartidas #contenedor_datos #sin_seleccionar").removeClass("oculto");
    }

  });

  // Se elimina la información mostrada del horario al quitar el puntero de un módulo de clase.
  $(document).on("mouseleave","#clases_impartidas  #contenedor_registro td", function () {
    $("#clases_impartidas #contenedor_datos #seleccionado #asignatura").text("");
    $("#clases_impartidas #contenedor_datos #seleccionado #aula").text("");
    $("#clases_impartidas #contenedor_datos #seleccionado #curso").text("");

    $("#clases_impartidas #contenedor_datos #seleccionado").addClass("oculto");
    $("#clases_impartidas #contenedor_datos #sin_seleccionar").removeClass("oculto");
  });

  //Se ejecuta el controlador para la descarga el horario del profesor en PDF.
  $(document).on('click',"#clases_impartidas #horario_pdf img ",function(event){
    event.preventDefault();
    id=$(this).closest("#contenedor_registro").find("#cabecera_lista").attr("profesor");
    window.open(Routing.generate('horario_profesor_pdf', {id:id}),"_self");
    $("#asignar_horario_grupos #cargando_horario_pdf").removeClass('oculto');
    $("#asignar_horario_grupos #horario_pdf").addClass('oculto');
    setTimeout(function() {
      $("#generar_horarios_pdf").load(Routing.generate('horario_profesor_pdf', {id:id}), function(){
        $("#asignar_horario_grupos #cargando_horario_pdf").addClass('oculto');
        $("#asignar_horario_grupos #horario_pdf").removeClass('oculto');
        $('#generar_horarios_pdf').empty();
      });
    },20);
    $('#generar_horarios_pdf').dialog('close');
  });


/////////////////////////////////////////
//  Asignación de Asignatura Opcional  //
/////////////////////////////////////////

  // Se muestra la lista de alumnos sin asignación de la asignatura opcional.
  $(document).on('click',"#asignar_optativa #btn_no_asignados",function(event){
    $("#asignar_optativa #asignados").addClass("oculto");
    $("#asignar_optativa #no_asignados").removeClass("oculto");

    //Se cambia los estilos según el scroll vertical.
    if( $("#asignar_optativa  #no_asignados").find('table tbody').get(0).scrollHeight>$("#asignar_optativa #no_asignados").find('table tbody').height()){
        $("#asignar_optativa #no_asignados").find("table thead tr>th:last-child").attr('style', 'width: 10.5% !important');
    }
    else{
        $("#no_asignados").find("table thead tr>th:last-child").attr('style', 'width: 9% !important');
    }
  });

  // Se muestra la lista de alumnos con asignación de la asignatura opcional.
  $(document).on('click',"#asignar_optativa #btn_asignados",function(event){

    $("#asignar_optativa #no_asignados").addClass("oculto");
    $("#asignar_optativa #asignados").removeClass("oculto");

    //Se cambia los estilos según el scroll vertical.

    if( $("#asignar_optativa #asignados").find('table tbody').get(0).scrollHeight>$("#asignar_optativa #asignados").find('table tbody').height()){
        $("#asignar_optativa #asignados").find("table thead tr>th:last-child").attr('style', 'width: 10% !important');
    }
    else{
        $("#asignar_optativa #asignados").find("table thead tr>th:last-child").attr('style', 'width: 9% !important');
    }
  });


  // Se muestra la información del alumno.
  $(document).on("mouseenter","#asignar_optativa .scrollContent tr", function () {
    // Se evita que se muestre el mensaje predeterminado si pasamos de un enlace a otro.
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #sin_seleccionar").addClass("oculto");
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #seleccionado").removeClass("oculto");

    tr=$(this);
    alumno=$(this).attr("id");

    tr.closest("div[class*='contenedor_registro']").find(".contenido_info #seleccionado").load(Routing.generate('datos_alumno_optativas', {id:tr.attr("id")}), function(){
        });
  });

  // Se elimina la información mostrada del alumno al quitar el puntero.
  $(document).on("mouseleave","#asignar_optativa .scrollContent tr", function () {
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #seleccionado").addClass("oculto");
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #seleccionado").empty();
    $(this).closest("div[class*='contenedor_registro']").find(".contenido_info #sin_seleccionar").removeClass("oculto");
  });


  // Se modifica el select de búsqueda de antiguo alumno.
  $(document).on('change','#asignar_optativa #lista_cursos select',function(event) {
    event.preventDefault();
    div=$(this).closest("div[class*='asignar_optativa']");
    //Se reemplaza el nombre del select añadiendo también una separación antes del "de" para que funcione bien la búsqueda.
    curso=$(this).find("option:selected").text().replace(" de", "");

    //Se elimina el contenido del buscador cuando se selecciona un curso para la búsqueda.
    div.find("#buscador input").val(""); 
    div.find("#buscador input").keyup(); 
    
    //Se asigna el select correspondiente a "Curso Matriculado".
    id=1; 

    valor=div.find("#lista_cursos select option:selected").val();
    // Se modifica el valor en las dos listas.
    if($(".lista_asignados select option:selected").val()!= $(".lista_no_asignados select option:selected").val()){
      $("#asignar_optativa #lista_cursos select").val(valor).change();
    }
    // Se selecciona el option del select oculto con z-index para filtrar el curso.
    if(div.find("select[class='"+id+"'] option[value='"+curso+"']").length){
      div.find("#buscador input").prop("disabled",false);    
      // Se selecciona y se muestra con change().
      div.find("select[class='"+id+"']").val(curso).change();
    }
    else if($(this).find("option:selected").text()=="Todos los cursos"){
      div.find("#buscador input").prop("disabled",false);
      div.find("select[class='"+id+"']").val("").change();
    }
    else{
      div.find("tbody").empty();
      div.find("tbody").append("<tr class='odd no_cursor'><td class='dataTables_empty'>Actualmente no existe antiguos alumnos para el curso seleccionado</td></tr>");
      div.find("thead tr th").removeClass("sorting_asc");
      div.find("#buscador input").prop("disabled",true);
    }
    //Se cambia los estilos según el scroll vertical.
    if( div.find('table tbody').get(0).scrollHeight>div.find('table tbody').height()){
        div.find("table thead tr>th:last-child").attr('style', 'width: 10% !important');
    }
    else{
        div.find("table thead tr>th:last-child").attr('style', 'width: 9% !important');
    }
  });

  //Selección de la optativa en la tabla de alumnos sin optativa asignada.
  $(document).on('click',"#asignar_optativa #no_asignados table button",function(event){ 
    td= $(this).closest("td");

    // Se marca la asignatura seleccionada.
    if(!$(this).hasClass("elected")){
      td.find("button").removeClass("elected"); 
      $(this).addClass("elected");

      //Se habilita el botón de guardar.
      $("#asignar_optativa #no_asignados #enviar_select button").prop("disabled",false);
    }
    else{
      $(this).removeClass("elected"); 
      if($("#asignar_optativa #no_asignados .elected").size()==0){
        //Se deshabilita el botón de guardar.
        $("#asignar_optativa #no_asignados #enviar_select button").prop("disabled",true);
      }
    }
 });

  //Cambio del color de los botones de optativas en la tabla.
  $(document).on('mouseenter','#asignar_optativa table tbody button',function(event) {
    $(this).attr('style', 'background:'+$(this).attr("color"));
  });
    
  $(document).on('mouseleave','#asignar_optativa table tbody button',function(event) {
    $(this).attr('style', 'background: ');
  });

  //Selección de la optativa en la tabla de alumnos con optativa asignada.
  $(document).on('click',"#asignar_optativa #asignados table button",function(event){ 
    td= $(this).closest("td");
    // Se marca la asignatura seleccionada.
    if(!$(this).hasClass("elected")){
      td.find("button").removeClass("elected"); 
      $(this).addClass("elected");

      //Se habilita el botón de guardar.
      $("#asignar_optativa #asignados #enviar_select button").prop("disabled",false);

      if($("#asignar_optativa #asignados .elected:not(.asignada)").size()==0){
        //Se deshabilita el botón de guardar.
        $("#asignar_optativa #asignados #enviar_select button").prop("disabled",true);
      }
    }
  });

  //Se guardan las optativas asignadas de la tabla donde se pulse el botón.
  $(document).on('click',"#asignar_optativa #enviar_select button",function(event){ 
    container=$(this).closest(".asignar_optativa");

    asignadas = new Object();

    container.find("table tbody tr .elected:not(.asignada)").each (function(){ 
      alumno=$(this).closest("tr").attr("id");
      optativa=$(this).attr("id");

      asignadas[alumno] = [optativa]; 
    });

    //Se comprueba que hay asignaciones.
    if($.isEmptyObject(asignadas)){
      errorPNotify.play();

      new PNotify({
        text:'No se ha asignado optativa a ningún alumno.',
        addclass: "custom",
        type: "error",
        shadow: true,
        hide: true,
        buttons: {
          sticker: false,
          labels:{close: "Cerrar"}
        },
        stack: right_Stack,
        animate: {
          animate: true,
          in_class: "fadeInRight",
          out_class: "fadeOutRight",
        }
      });
      container.find(" #enviar_select button").prop('disabled', 'true');
      return false;
    }

    $.ajax({
      type: 'POST',
      url: Routing.generate('asignar_optativa'),
      data: {asignadas:asignadas},
      dataType: 'json',
      success: function(response) {
        if(response.data==null){
          errorPNotify.play();

          new PNotify({
            text:'No se ha asignado optativa a ningún alumno.',
            addclass: "custom",
            type: "error",
            shadow: true,
            hide: true,
            buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
            },
            stack: right_Stack,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
          });
          container.find(" #enviar_select button").prop('disabled', 'true');
          return false;
        }
      
        $(".ui-pnotify").remove();
        exito.play();

        if(response.num==1){
          new PNotify({
            text:"1 asignación realizada.",
            addclass: "custom",
            type: "success",
            shadow: true,
            width: "335px",
            hide: true,
            buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
            },
            stack: right_Stack_dialog,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
          });
        }
        else{
          new PNotify({
            text:response.num+" asignaciones realizadas.",
            addclass: "custom",
            type: "success",
            shadow: true,
            width: "335px",
            hide: true,
            buttons: {
              sticker: false,
              labels:{close: "Cerrar"}
            },
            stack: right_Stack_dialog,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
          });
        }
      
        id=container.find(".btn_2opciones .btn_selected").attr("id");
        if(id="btn_asignados"){
          $("#tabs>div[style='display: block']").load(Routing.generate("show_optativas"), function(){
            $("#asignar_optativa .btn_2opciones label[id='btn_asignados']").click();
          });
        }
        else{
          $("#tabs>div[style='display: block']").load(Routing.generate("show_optativas"), function(){
            $("#asignar_optativa .btn_2opciones label[id='btn_no_asignados']").click();
          });
        }
        $("#listado_alumnos").update_tab();
      }
    })

  });
  

  ///////////////////////////////////////////
  //            Administrativos            //
  ///////////////////////////////////////////

$(document).on("submit",".formulario_administrativo",function(event){
    event.preventDefault();
    form= $(this).closest("form");

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
      $(this).find(":input").each(function(){
        if((!$(this).attr("validated") || !$(this).attr("validated")==false)){
          if($(this).attr("validation")){
            validation($(this));
          }
        }
      });

    //":input"añade a los input radio,select...
    $(this).find(":input").each(function(){
      if(($(this).attr("validated")=="false")) {
        //Se muestra el input inválido.
        tab= $(this).closest("div[id^='tab']").attr("id");
        mostrarTab($(this).closest("form"),tab);
        $(this).focus();
        val=1;
        return false;
      }       
    });

          
    var formdata=new FormData($(this)[0])

    if(val==0){
      $.ajax({
        type: 'POST',
        url: Routing.generate('administrativo_create'),//url: $(this).attr('action')
        data:formdata, //$(this).serialize()
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,

        // Mostramos un mensaje con la respuesta de PHP
        success: function(response) {
        
          limpiarForm(form);

          // Notificación de confirmación.
          exito.play();
          new PNotify({
            text:"Personal administrativo registrado",
            addclass: "custom",
            type: "success",
            shadow: true,
            hide: true,
            buttons: {
            sticker: false,
            labels:{close: "Cerrar"}
            },
            stack: right_Stack,
            animate: {
              animate: true,
              in_class: "fadeInRight",
              out_class: "fadeOutRight",
            }
          });
          
          //Actualización de pestañas.
          $("#ficha_administrativo").update_tab();
          $("#administrativo_antiguo").update_tab();


          event.stopPropagation();   
        },
        error: function (response, desc, err){
          if (response.responseJSON && response.responseJSON.message) {
            if(response.responseJSON.result == 0) {
              //Se elimina las clases de error, para luego añadirlas a los campos que siguen inválidos.
               form.find(":input").each(function(i){  
                $(this).prev().find(".error").remove();
                $(this).next(".mensaje").remove();
                $(this).removeClass("invalid");
                $(this).attr("validated", true);
              });
              //Se muestra los campos inválidos.        
              for (var key in response.responseJSON.data) { 
                form.find(":input[id='"+key+"']").addClass("invalid");   
                form.find(":input[id='"+key+"']").attr("validated", false);
                form.find(":input[id='"+key+"']").after("<span class='mensaje'>"+response.responseJSON.data[key]+"</span>");
                form.find(":input[id='"+key+"']").prev().append("<span class='error'>Dato inválido</span>");
              }
            } 
            alert(response.responseJSON.message);
          } 
          else {
            alert(desc);
          }
        }
      })
    }
      return false;
  });

  $(document).on("submit","#administrativo_edit",function(event) {
    event.preventDefault();
    form= $(this).closest("form");

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
    form.find(":input[type!='file']").each(function(){

      if((!$(this).attr("validated") || $(this).attr("validated")==false)){
        if($(this).attr("validation")){
          validation($(this));
        }
      }
    });

    //":input"añade a los input radio,select...
    form.find(":input").each(function(){
      if(($(this).attr("validated")=="false")) {
        //Se muestra el input inválido.
        $(this).focus();
        val=1;
        return false;
      }       
    });

    var formdata=new FormData($(this)[0]);

    if(val==0){
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formdata, 
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
      
          //Actualización de pestañas.
          $("#administrativo_antiguo").update_tab();
          $("#listarlog").update_tab();

          var arr = form.attr('action').split('/');
          div=form.closest("div[id^='tabs-']");
          $(div).load(Routing.generate('administrativo_edit', {id:arr[5]}), function(responseTxt, statusTxt, xhr){
            if(statusTxt == "success"){
              form= $("#administrativo_edit");

              // Notificación de confirmación.
              $(".ui-pnotify").remove();
              exito.play();

              new PNotify({
                text:"Datos actualizados",
                addclass: "custom",
                type: "success",
                shadow: true,
                hide: true,
                buttons: {
                  sticker: false,
                  labels:{close: "Cerrar"}
                },
                stack: right_Stack,
                animate: {
                  animate: true,
                  in_class: "fadeInRight",
                  out_class: "fadeOutRight",
                }
              });
            }

            if(statusTxt == "error")
              alert("Error: " + xhr.status + ": " + xhr.statusText);
          });
        } 
      })
    }
  });

  //Se restablece la contraseña del administrativo.
  $(document).on("click","#administrativo_edit .btn_restablecer",function(event) {
    event.preventDefault();
    id=$(this).attr("id");

    aviso.play();
    swal({
      title: "Restablecer contraseña del administrativo",
      html: "<p class='justificado'>Se va a restablecer la contraseña del administrativo a la inicial y no se podrá recuperar la actual. ¿Estas seguro de continuar?</p>",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        $.ajax({
          type: 'POST',
          url: Routing.generate('restablecer_contraseña_administrativo', {id:id}),
          data:{id:id},
          dataType: 'json',
  
          success: function(response) {
            // Notificación de confirmación.
            $(".ui-pnotify").remove();
            exito.play();

            new PNotify({
              text:"Contraseña restablecida.",
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
          }
        })
      }, function (dismiss) {

      }
    ); 
  });

$(document).on("blur","input[id='administrativo_dni']",function(event) {
  form= $(this).closest("form");

  if($(this).val()!=''){
    var dni=$(this).val();
    $.ajax({
      type: 'POST',
      url: Routing.generate('comprobar_dni_administrativo'),
      data: {dni:dni},
      dataType: 'json',
      success: function(response) {      
        if(response.data!=null){
          form.find("input[id$='administrativo_dni']").addClass("invalid");   
          form.find("input[id$='administrativo_dni']").attr("validated", false);
          form.find("input[id$='administrativo_dni']").after("<span class='mensaje'>Este DNI ya existe en el sistema.</span>");
          //Se comprueba que no exista el aviso de error para no repetirlo.
          if(form.find("input[id$='administrativo_dni']").prev().find('span[class="error"]').size()==0){
            form.find("input[id$='administrativo_dni']").prev().append("<span class='error'>Dato inválido</span>");
          }
          form.find("input[id$='administrativo_nombre']").focus();
          if(form.find("input[id$='administrativo_nombre']").val()==''){
            form.find("input[id$='administrativo_nombre']").removeClass("invalid");
            form.find("input[id$='administrativo_nombre']").prev().find(".error").remove();
            form.find("input[id$='administrativo_nombre']").next(".mensaje").remove();         
          }
        }
      } 
    })
  }
  event.stopPropagation();  
});
  

$(document).on("blur","input[id='edit_administrativo_dni']",function(event) {
  form= $(this).closest("form");

  var arr = form.attr('action').split('/');

  if($(this).val()!=''){
    var dni=$(this).val();

    $.ajax({
      type: 'POST',
      url: Routing.generate('comprobar_dni_administrativo_editado'),
      data: {dni:dni, id:arr[5]},
      dataType: 'json',
      success: function(response) {      
        if(response.data!=null){
          form.find("input[id$='administrativo_dni']").addClass("invalid");   
          form.find("input[id$='administrativo_dni']").attr("validated", false);
          form.find("input[id$='administrativo_dni']").after("<span class='mensaje'>Este DNI ya existe en el sistema.</span>");
          //Se comprueba que no exista el aviso de error para no repetirlo.
          if(form.find("input[id$='administrativo_dni']").prev().find('span[class="error"]').size()==0){
            form.find("input[id$='administrativo_dni']").prev().append("<span class='error'>Dato inválido</span>");
          }
          form.find("input[id$='administrativo_nombre']").focus();
          if(form.find("input[id$='administrativo_nombre']").val()==''){
            form.find("input[id$='administrativo_nombre']").removeClass("invalid");
            form.find("input[id$='administrativo_nombre']").prev().find(".error").remove();
            form.find("input[id$='administrativo_nombre']").next(".mensaje").remove();         
          }
        }
      } 
    })
  }
  event.stopPropagation();
});


  ///////////////////////////////////////////
  //       Antiguos administrativos        //
  ///////////////////////////////////////////


  //Se muestra la info del profesor al colocar el cursor.
  $(document).on("mouseenter","#consulta_antiguo_administrativo .scrollContent tr", function(){
    // Se evita que se muestre el mensaje predeterminado si pasamos de un enlace a otro.
    $("#consulta_antiguo_administrativo .contenido_info #sin_seleccionar").addClass("oculto");

    //Se comprueba que no es un aviso predeterminado.
    if(!$(this).find("td").hasClass("dataTables_empty")){

      $("#consulta_antiguo_administrativo .contenido_info #seleccionado").load(Routing.generate('datos_antiguo_administrativo', {id:$(this).attr("id")}), function(){
        $("#consulta_antiguo_administrativo .contenido_info #seleccionado").removeClass("oculto");
        $("#consulta_antiguo_administrativo .contenido_info #sin_seleccionar").addClass("oculto");     
      });
    }
    else{
      $("#consulta_antiguo_administrativo .contenido_info #seleccionado").empty();
      $("#consulta_antiguo_administrativo .contenido_info #seleccionado").addClass("oculto");
      $("#consulta_antiguo_administrativo .contenido_info #sin_seleccionar").removeClass("oculto");
    }
  });

  // Se elimina la información mostrada del profesor al quitar el puntero.
  $(document).on("mouseleave","#consulta_antiguo_administrativo .scrollContent tr", function () {
    $("#consulta_antiguo_administrativo .contenido_info #seleccionado").addClass("oculto");
    $("#consulta_antiguo_administrativo .contenido_info #seleccionado").empty();
    $("#consulta_antiguo_administrativo .contenido_info #sin_seleccionar").removeClass("oculto");
  });

  // Se elimina la información con retraso al salir de la tabla por si aún se estan cargando datos anteriores.
  $(document).on("mouseleave","#old_admin", function () {
    setTimeout(function() {
      $("#consulta_antiguo_administrativo .contenido_info #seleccionado").addClass("oculto");
      $("#consulta_antiguo_administrativo .contenido_info #seleccionado").empty();
      $("#consulta_antiguo_administrativo .contenido_info #sin_seleccionar").removeClass("oculto");
    }, 300);
  });


  $(document).on('click',"#consulta_antiguo_administrativo #old_admin tbody td",function(event){
    event.preventDefault();
    input=$(this).closest("tr").find("td:last-child input");
    //Se desactiva seleccionar cuando hay selección en la otra tabla.
    if(input.is(":disabled")){
      return false;
    }

    if(!$(event.target).is('input')){

      if(input.is(':checked') ){
        input.prop("checked",false);
      }
      else{
        input.prop("checked",true);
      }
    }
    else{
      //Retardo para poder mostar el input seleccionado.
      setTimeout(function(){
        if(input.is(':checked') ){
          input.prop("checked",false);
        }
        else{
          input.prop("checked",true);
        }
      }, 5);
    }
    // Se habilita/deshabilita el botón enviar selecionados.
    if( $("#consulta_antiguo_administrativo #old_admin td input").is(':checked') ) {
      $("#consulta_antiguo_administrativo #baja").addClass("oculto");
      $("#consulta_antiguo_administrativo .baja_active").removeClass("oculto");
      $("#consulta_antiguo_administrativo #alta").addClass("oculto");
      $("#consulta_antiguo_administrativo .alta_limpiar").removeClass("oculto");
      $("#consulta_antiguo_administrativo #old_admin_no_active tbody td input").each (function(){ 
        $(this).prop("disabled",true);
      });
    } 
    else {
      $("#consulta_antiguo_administrativo #baja").addClass("oculto");
      $("#consulta_antiguo_administrativo .baja_disable").removeClass("oculto");
      $("#consulta_antiguo_administrativo #alta").addClass("oculto");
      $("#consulta_antiguo_administrativo .alta_disable").removeClass("oculto");
      $("#consulta_antiguo_administrativo #old_admin_no_active tbody td input").each (function(){ 
        $(this).prop("disabled",false);
      });
    }
  });


  $(document).on('click',"#consulta_antiguo_administrativo #old_admin_no_active tbody td",function(event){
    event.preventDefault();
    input=$(this).closest("tr").find("td:first-child input");
    
    //Se desactiva seleccionar cuando hay selección en la otra tabla.
    if(input.is(":disabled")){
      return false;
    }

    if(!$(event.target).is('input')){

      if(input.is(':checked') ){
        input.prop("checked",false);
      }
      else{
        input.prop("checked",true);
      }
    }
    else{
      //Retardo para poder mostar el input seleccionado.
      setTimeout(function(){
        if(input.is(':checked') ){
          input.prop("checked",false);
        }
        else{
          input.prop("checked",true);
        }
      }, 5);
    }
    // Se habilita/deshabilita el botón enviar selecionados.
    if( $("#consulta_antiguo_administrativo #old_admin_no_active td input").is(':checked') ) {
          $("#consulta_antiguo_administrativo #alta").addClass("oculto");
          $("#consulta_antiguo_administrativo .alta_active").removeClass("oculto");
          $("#consulta_antiguo_administrativo #baja").addClass("oculto");
          $("#consulta_antiguo_administrativo .baja_limpiar").removeClass("oculto");
      $("#consulta_antiguo_administrativo #old_admin tbody td input").each (function(){ 
        $(this).prop("disabled",true);
      });
    } 
    else {
          $("#consulta_antiguo_administrativo #alta").addClass("oculto");
          $("#consulta_antiguo_administrativo .alta_disable").removeClass("oculto");
          $("#consulta_antiguo_administrativo #baja").addClass("oculto");
          $("#consulta_antiguo_administrativo .baja_disable").removeClass("oculto");
      $("#consulta_antiguo_administrativo #old_admin tbody td input").each (function(){ 
        $(this).prop("disabled",false);
      });
    }
  });
  //Se limpia la selección al pulsar el botón correspondiente.
  $(document).on("click","#consulta_antiguo_administrativo .baja_limpiar button", function () {
    $("#consulta_antiguo_administrativo #old_admin_no_active tbody td input:checked").each (function(){ 
       //Se realiza el evento en el td ya que en input haría click dos veces.
       $(this).closest("td").click();
    });
  });

  $(document).on("click","#consulta_antiguo_administrativo .alta_limpiar button", function () {
    $("#consulta_antiguo_administrativo #old_admin tbody td input:checked").each (function(){ 
      $(this).closest("td").click();
    });
  });

  //Se realiza la nueva alta del administrativo.
  $(document).on("click","#consulta_antiguo_administrativo .alta_active button", function(event) {
    event.preventDefault();
    var array= Array(); 
    $("#consulta_antiguo_administrativo #old_admin_no_active tbody td input:checked").each (function(){ 
      array.push($(this).closest("tr").attr("id"));
    });

    if(array.length==1){
      nombre="";
      nivel="";
    $("#consulta_antiguo_administrativo #old_admin_no_active tbody td input:checked").each (function(){ 
      nombre=$(this).closest("tr").find("td:nth-child(2)").text();
      nivel=$(this).closest("tr").find("#nivel").text();
    });
      titulo="<table><p>Se va a registrar el alta del antiguo administrativo:<br></p><thead><tr><th>Nombre</th><th>Nivel</th></tr></thead><tbody><tr><td>"+nombre+"</td><td>"+nivel+"</td></tr></tbody><br></p></table><br>¿Estas seguro de continuar?";
    }
    else{
      titulo="<table ><p>Se va a registrar el alta de los antiguos administrativos:<br></p><thead><tr><th>Nombre</th><th>Nivel</th></tr></thead><tbody>";
      $("#consulta_antiguo_administrativo #old_admin_no_active tbody td itput:checked").each (function(){ 
        nombre=$(this).closest("tr").find("td:nth-child(2)").text();
        nivel=$(this).closest("tr").find("#nivel").text();
        titulo+="<tr><td>"+nombre+"</td><td>"+nivel+"</td></tr>";
       });
      titulo+="</tbody><br></p></table><br>¿Estas seguro de continuar?";
    }

    aviso.play();
    swal({
      title: "Alta del Personal Administrativo",
      html: titulo,
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      width: "500px",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        $.ajax({
          type: 'POST',
          url: Routing.generate('alta_administrativo'),
          data: {array:array},
          dataType: 'json',
          success: function(response) {
            if(array.length==1){
              texto="Alta registrada.";
            }else{
              texto=array.length+" altas registradas.";
            }
            // Notificación de confirmación
            exito.play();
            
            new PNotify({
              text:texto,
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });

            // Se actualiza todas las pestañas con tablas de administrativos.
            $("#administrativo_antiguo").update_tab();
            $("#ficha_administrativo").update_tab();
          }
        })
      }, function (dismiss) {

      }
    );
  });

  //Se realiza la baja del profesor.
  $(document).on("click","#consulta_antiguo_administrativo .baja_active button", function(event)  {
    event.preventDefault();
    var array= Array(); 
    $("#consulta_antiguo_administrativo #old_admin tbody td input:checked").each (function(){ 
      array.push($(this).closest("tr").attr("id"));
    });

    if(array.length==1){
      nombre="";
      tipo="";
    $("#consulta_antiguo_administrativo #old_admin tbody td input:checked").each (function(){ 
      nombre=$(this).closest("tr").find("td:nth-child(2)").text();
      tipo=$(this).closest("tr").find("#tipo").text();
    });
      titulo="<table><p>Se va a registrar la baja del siguiente administrativo:<br></p><thead><tr><th>Nombre</th><th>Personal</th></tr></thead><tbody><tr><td>"+nombre+"</td><td>"+tipo+"</td></tr></tbody><br></table><br><br>¿Estas seguro de continuar?";
    }
    else{
      titulo="<table><p>Se va a registrar la baja de los siguientes administrativos:<br></p><thead><tr><th>Nombre</th><th>Personal</th></tr></thead><tbody>";
      $("#consulta_antiguo_administrativo #old_admin tbody td input:checked").each (function(){ 
        nombre=$(this).closest("tr").find("td:nth-child(2)").text();
        tipo=$(this).closest("tr").find("#tipo").text();
        titulo+="<tr><td>"+nombre+"</td><td>"+tipo+"</td></tr>";
       });
      titulo+="</tbody><br></p></table><br><br>¿Estas seguro de continuar?";
    }

    aviso.play();
    swal({
      title: "Baja del Personal Administrativo",
      html: titulo,
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      width: "500px",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {
        $.ajax({
          type: 'POST',
          url: Routing.generate('baja_administrativo'),
          data: {array:array},
          dataType: 'json',
          success: function(response) {
            if(array.length==1){
              texto="Baja registrada.";
            }else{
              texto=array.length+" bajas registradas.";
            }

            // Notificación de confirmación
            exito.play();
            
            new PNotify({
              text:texto,
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });
            // Se actualiza todas las pestañas con tablas de administrativos.
            $("#administrativo_antiguo").update_tab();
            $("#ficha_administrativo").update_tab();
          }
        })
      }, function (dismiss) {
      }
    );
  });











































  ///////////////////////////////////////////
  //            Equipo Directivo           //
  ///////////////////////////////////////////

  $(document).on('click',"#equipo_directivo tbody td",function(event){
    event.preventDefault();
    input=$(this).closest("tr").find("td:last-child input");

    if(!$(event.target).is('input')){
        input.prop("checked",true);
    }
    else{
      //Retardo para poder mostar el input seleccionado.
      setTimeout(function(){
          input.prop("checked",true);
      }, 2);
    }

    // Se habilita/deshabilita el botón enviar selecionados.
      $("#equipo_directivo .alta_active").removeClass("oculto");
      $("#equipo_directivo .alta_disable").addClass("oculto");
  });


  //Se muestra la info del profesor al colocar el cursor.
  $(document).on("mouseenter","#equipo_directivo .scrollContent tr", function(){
    // Se evita que se muestre el mensaje predeterminado si pasamos de un enlace a otro.
    $("#equipo_directivo .contenido_info #sin_seleccionar").addClass("oculto");

    //Se comprueba que no es un aviso predeterminado.
    if(!$(this).find("td").hasClass("dataTables_empty")){

      $("#equipo_directivo .contenido_info #seleccionado").load(Routing.generate('datos_antiguo_profesor', {id:$(this).attr("id")}), function(){
        $("#equipo_directivo .contenido_info #seleccionado").removeClass("oculto");
        $("#equipo_directivo .contenido_info #sin_seleccionar").addClass("oculto");     
      });
    }
    else{
      $("#equipo_directivo .contenido_info #seleccionado").empty();
      $("#equipo_directivo .contenido_info #seleccionado").addClass("oculto");
      $("#equipo_directivo .contenido_info #sin_seleccionar").removeClass("oculto");
    }
  });

  // Se elimina la información mostrada del profesor al quitar el puntero.
  $(document).on("mouseleave","#equipo_directivo .scrollContent tr", function () {
    $("#equipo_directivo .contenido_info #seleccionado").addClass("oculto");
    $("#equipo_directivo .contenido_info #seleccionado").empty();
    $("#equipo_directivo .contenido_info #sin_seleccionar").removeClass("oculto");
  });

  // Se elimina la información con retraso al salir de la tabla por si aún se estan cargando datos anteriores.
  $(document).on("mouseleave","#equipo_directivo_teachers", function () {
    setTimeout(function() {
      $("#equipo_directivo .contenido_info #seleccionado").addClass("oculto");
      $("#equipo_directivo .contenido_info #seleccionado").empty();
      $("#equipo_directivo .contenido_info #sin_seleccionar").removeClass("oculto");
    }, 300);
  });



  //Se realiza la nueva alta del profesor.
  $(document).on("click","#consulta_antiguo_profesor .alta_active button", function(event) {
    event.preventDefault();
    var array= Array(); 
    $("#consulta_antiguo_profesor #old_teacher_no_active tbody td input:checked").each (function(){ 
      //alert($(this).closest("tr").find("#nivel").text());
      array.push($(this).closest("tr").attr("id"));
    });

    if(array.length==1){
      nombre="";
      nivel="";
    $("#consulta_antiguo_profesor #old_teacher_no_active tbody td input:checked").each (function(){ 
      nombre=$(this).closest("tr").find("td:nth-child(2)").text();
      nivel=$(this).closest("tr").find("#nivel").text();
    });

      //titulo="<table><p>Se va a registrar el alta del antiguo profesor X, ¿Estas seguro de continuar? <br></p><thead><tr><th>Evento</th><th>Fecha</th><th>Hora</th></tr></thead><tbody><tr><td>"+titulo+"</td><td>"+fecha+"</td><td>"+hora+"</td></tr></tbody><br></p></table>",
      titulo="<table><p>Se va a registrar el alta del antiguo profesor:<br></p><thead><tr><th>Nombre</th><th>Nivel</th></tr></thead><tbody><tr><td>"+nombre+"</td><td>"+nivel+"</td></tr></tbody><br></p></table><br>¿Estas seguro de continuar?";
    }
    else{
      titulo="<table ><p>Se va a registrar el alta de los antiguos profesores:<br></p><thead><tr><th>Nombre</th><th>Nivel</th></tr></thead><tbody>";
      $("#consulta_antiguo_profesor #old_teacher_no_active tbody td input:checked").each (function(){ 
        nombre=$(this).closest("tr").find("td:nth-child(2)").text();
        nivel=$(this).closest("tr").find("#nivel").text();
        titulo+="<tr><td>"+nombre+"</td><td>"+nivel+"</td></tr>";
       });
      titulo+="</tbody><br></p></table><br>¿Estas seguro de continuar?";
    }

    aviso.play();
    swal({
      title: "Alta de Profesores",
      html: titulo,
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      width: "500px",
      confirmButtonColor: color,
      confirmButtonText: "¡Adelante!"
      }).then(function () {

        $.ajax({
          type: 'POST',
          url: Routing.generate('alta_profesor'),
          data: {array:array},
          dataType: 'json',
          success: function(response) {
            if(array.length==1){
              texto="Alta registrada.";
            }else{
              texto=array.length+" altas registradas.";
            }

            // Notificación de confirmación
            exito.play();
            
            new PNotify({
              text:texto,
              addclass: "custom",
              type: "success",
              shadow: true,
              hide: true,
              buttons: {
                sticker: false,
                labels:{close: "Cerrar"}
              },
              stack: right_Stack,
              animate: {
                animate: true,
                in_class: "fadeInRight",
                out_class: "fadeOutRight",
              }
            });

            // Se actualiza todas las pestañas con tablas de profesores.
            $("#profesor_antiguo").update_tab();
            $("#ficha_profesor").update_tab();
            $("#consultar_equipamientos").update_tab();
            $("#consultar_instalaciones").update_tab();
            $("#tutor_grupo").update_tab();
            $("#profesor_asignar_grupo").update_tab();
            $("#clases_impartidas").update_tab();
            $("#equipo_directivo").update_tab();
            $("#clases_impartidas").update_tab();
          }
        })
      }, function (dismiss) {

      }
    );
  });































//////////////////////////////////
//   Finalizar curso académico  //
//////////////////////////////////


  $(document).on("click","#finalizar_curso",function(event){
    event.preventDefault();

    $.ajax({
      type: 'POST',
      url: Routing.generate('obtener_curso_academico'),
      dataType: 'json',
      success: function(response) {

      aviso.play();
      swal({
        title: "Finalización del Curso Académico "+response.inicio+"/"+response.fin,
        html: "<p class='justificado'>Se va a finalizar el curso académico almacenando todos los datos del curso en el expediente de los alumnos y restableciendo los datos del sistema para el nuevo curso.</p></br>¿Estas seguro de continuar? No podrás deshacer este paso...",
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonColor: color_rojo,
        confirmButtonText: "Finalizar"
        }).then(function () {
        /*
          Finalizar curso
        */
        }, function (dismiss) {

        }
      );
      return false;
      }
    })
  });


//////////////////////////////////
//   Finalizar matriculación    //
//////////////////////////////////


  $(document).on("click","#finalizar_matriculacion",function(event){
    event.preventDefault();
   
    $.ajax({
      type: 'POST',
      url: Routing.generate('obtener_curso_academico'),
      dataType: 'json',
      success: function(response) {

      aviso.play();
      swal({
        title: "Finalización del proceso de matriculación del curso "+response.inicio+"/"+response.fin,
        html: "<p class='justificado'>Se va a finalizar el proceso de matriculación del curso académico desactivando a los alumnos del curso anterior que no han sido matriculados.</p><br><p class='justificado'> Los responsables de estos alumnos también serán desactivados en el sistema si no tiene más alumnos matriculados en el centro.</p></br>¿Estas seguro de continuar? No podrás deshacer este paso...",
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonColor: color_rojo,
        confirmButtonText: "Finalizar"
        }).then(function () {

        /*
          Finalizar curso
        */
        }, function (dismiss) {
        }
      );
      return false;
      }
    })
  });













});

