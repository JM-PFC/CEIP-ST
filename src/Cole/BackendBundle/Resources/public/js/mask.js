$(document).ready(function () {
/* 
 //Máscaras para los campos del formulario.
  $('.dni').on("keyup", function(){
    var character =$(this).val().substring(0,1); 
    if(!character){
       $(this).unmask();
    }
    //Secomprueba si se introduce DNI(comienza por número) ó NIE(comienza por X,Y ó Z).
    //isNaNcomprueba que no es un número.
    else if(isNaN(character))
    {
      $(this).mask('A0000000-S', {
        'translation': {A: {pattern: /[X-Zx-z]/}//Se obliga a que empiece por X,Y,Z.
        }
      });
    }
    else{
      //Modificar!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
      $(this).mask('00000000-S', { 
        // Generamos un evento en el momento que se rellena .addClass("ok") 
        completed:function(){ 

        $("#nombre input:first").val('Jose');
        //$(" input[name='cole_backendbundle_padres[apellido1]']").val('Prueba1');
        //$(" input[name='cole_backendbundle_padres[apellido2]']").val('Prueba2');
        $(" input[name='cole_backendbundle_padres[profesion]']").val('Profesión de prueba');
        
        // document.getElementById('cole_backendbundle_padres_sexo_0').checked = true;
        //$(" input[name='cole_backendbundle_padres[sexo][value='h']").attr('checked', true);
        document.getElementById('cole_backendbundle_padres_estadoCivil').selectedIndex=1;
        } 
      });
    }
  });
 */

  /*
 $('.telefono').mask('000 00 00 00',{
        onComplete: function() {
        alert("prueba");
    }
{


$('#alumno_responsable2_dni').mask('A0000000-S',
     {
        onComplete: function() {
        if($("#alumno_responsable2_dni").attr("id")=="alumno_responsable2_dni"){
            $("form").find(":input[id^='alumno_responsable2_']").each(function(){ 
                $(this).prop("disabled", false);
            });
        }
    }});
 */
  $('.dni').mask('A0000000-S',
  {'translation': {A: {pattern: /[X-Zx-z]|[0-9]/}},
     placeholder: "________-__"});
  $('.telefono').mask('000 00 00 00');
  $('.fecha').mask('00/00/0000', {placeholder: "__/__/____"});
  $('.dni').css( "text-transform", "uppercase");
  
  $(".full_name").parent().css("width", "43%");
  $(".full_name").parent().css("max-width", "45%");
  
  // Función para mostrar una pestaña de un formulario dado el formulario y el id del tab.
  function mostrarTab(form, tab) {
    $("form #content").find("[id^='tab']").hide(); //Oculta todo el contenido
    $("form #tab-container li").attr("class",""); //Resetea el valor de los id
    $("form li a[name="+tab+"]").parent().attr("class","selected"); //Activa la españa actual
    $("form #content #"+ tab).css("display","inline"); //Muestra el contenido de la pestaña actual
    $("form #" +tab+" input:first").focus();
  }
  
  // Se añade un índice de tabuláción a todos los input.
  $("form").find(":input").each(function(i){
    $(this).attr("tabindex",i+1);
  });
  
  // Se deshabilita el campo file inicialmente.
  $("#alumno_foto").prop("disabled", true);  

  // Se habilita y deshabilita el campo file de la foto según la opción del radio.
  $("#foto_no").focus(function(){
    $("#alumno_foto").prop("disabled", true);  
  });

  $("#foto_si").focus(function(){
    $("#alumno_foto").prop("disabled", false);  
  });


  $('#tab-container a').on('keydown', function(e){

    tab=$(this).attr("name");
    if(e.keyCode == 9 || (e.keyCode == 13 ))
    {
      va=$("#"+tab).find(":input").attr("id");
      $("#"+va).focus();
    }
    e.preventDefault();
  });


  // Al presionar cualquier tecla en cualquier elemento del formulario se ejectua la siguiente función
  // Función para desplazarse mediante teclado por los campos.
  $(':input').on('keydown', function(e){
    // Sólo importa si las teclas presionadas fueron TAB o ENTER. (Para ver el código de otras teclas: http://www.webonweboff.com/tips/js/event_key_codes.aspx)
    // Y que no sean botones o textarea.
    if(e.keyCode == 9 || (e.keyCode == 13 && (!($(this).is("textarea")) && !($(this).is("button")))))
    {
      // Se obtiene el número del tabindex del campo actual.
      var currentTabIndex = $(this).attr('tabindex');
      // Se le suma 1 para tener el siguiente.
      var nextTabIndex    = parseInt(currentTabIndex) + 1;
      // Se obtiene (si existe) el siguiente elemento usando la variable nextTabIndex
      var nextField       = $('[tabindex='+nextTabIndex+']');
      // Se salta los elementos no activos.
      while(nextField.attr("disabled")=="disabled")
      {
      var nextTabIndex    = parseInt(nextTabIndex) + 1;
      var nextField       = $('[tabindex='+nextTabIndex+']');
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
          nextField= $('[tabindex='+nextTabIndex+']');
        }
        // Se muestra el tab que contiene el siguiente campo si es diferente al actual.
        tab= $(this).closest("div[id^='tab']").attr("id");
        next_tab=nextField.closest("div[id^='tab']").attr("id");
      
        if(tab!=next_tab && nextField.attr("type")!="submit")
        {
          mostrarTab($(this).closest("form"),next_tab);
        }
    
        // Los campos file y submit se deja por defecto.
        if( $(this).attr("type")=="file")
        {
          $(this)[0].trigger("click");
        }
        // Se selecciona el siguiente campo.
        nextField.focus();
      }
      else{
        $("#botones_form>div:first>button").focus();
      }
      
      // Ignorar el funcionamiento predeterminado (enviar el formulario)
      e.preventDefault();
    }
  });

  // Se muestra por defecto la primera pestaña del formulario y se selecciona el primer campo.
  if($("#tab-container li:first").attr("class")=="selected"){
    $("#content").find("[id^='tab']").hide(); // Se oculta todo el contenido.
    $("#tabs-form li:first").attr("class","selected"); // Se activa la primera pestaña.
    $("#content #tab1").css("display","inline"); // Se muestra el contenido de la primera pestaña.
    $("#tab1 input:first").focus(); // Se selecciona el primer campo por defecto.
  }

  $('#tab-container a').on("click",function(event)  {
    event.preventDefault();
    if ($(this).closest("li").attr("class") == "selected"){ //Se detecta la pestaña actual.
      return;       
    }
    else{            
      $("#content").find("[id^='tab']").hide(); // Se oculta todo el contenido.
      $("#tab-container li").attr("class",""); // Se resetea el valor de los id.
      $(this).parent().attr("class","selected"); // Se activa la españa actual.
      $('#content #'+ $(this).attr('name')).css("display","inline"); // Se muestra el contenido de la pestaña actual.
      //$('#' + $(this).attr('name')).fadeIn(); 
      //$("#"+$(this).attr("name")+" input:first").focus();  Se selecciona el primer campo de la pestaña actual.
    }
  });

  function limpiarForm(form) {
    $("form").each (function(){ 
      this.reset();
    });
    $("form input:radio").prop("checked", false);
    $("#alumno_sexo_0").prop("checked", true);

    $("form :input").prev().find(".error").remove();
    $("form :input").next(".mensaje").remove();
    $("form :input").removeClass("invalid");
    $("#alumno_responsable2_dni").keyup();
    $("form input[validated='true']").attr("validated", "");

    if(form.attr("id")=="alumno_nuevo"){
      $("#foto_no").prop("checked", true);
      $("#alumno_foto").prop("disabled", true);  
    }
    // Si contiene pestañas se activa la primera pestaña.
    if($("div[id='tab-container']").length )
      {
        mostrarTab(form,"tab1");
      }
  }

  // Función para limpiar los campos del formulario.
  $(".limpiar").on("click",function(event)  {
    event.preventDefault();
    form= $(this).closest("form");
    limpiarForm(form);
  });
  
  // Se deshabilita los campos del segundo responsable por defecto, excepto el Dni.
  $("form").find(":input[id^='alumno_responsable2_']").each(function(){ 
      $(this).prop("disabled", true);
  });
  $("#alumno_responsable2_dni").prop("disabled", false);


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

  function validateWords(field) {
    if(!$(field).val()){
      return true;
    }
    var filter = /^[A-Za-záéíóúÁÉÍÓÚüÜñÑ]{2,}([\s][A-Za-záéíóúÁÉÍÓÚüÜñÑ]+)*$/;
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

  function validateNumbers(field) {
    if(!$(field).val()){
      return true;
    }
    var filter = /^[0-9]$/;
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
    if (!filter.test($(field).val())) {
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false
    }
    else{
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
    if(fecha[2]<(actual.getFullYear()-80) || fecha[2]>(actual.getFullYear()-15)){
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false;
    }
    else{
      return true;
    }
  }

  function validateFecha_Niño(field) {
    fecha=$(field).val().split('/');
    if(fecha[2]<(actual.getFullYear()-15) || fecha[2]>(actual.getFullYear()-2)){
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

  var message = {
    "Empty":"Este campo no puede estar vacío.",
    "Words":"Contiene caracteres inválidos.",
    "Letters":"Este campo no puede contener números.",
    "Numbers":"Este campo sólo puede contener números",
    "Length":"La longitud del campo es incorrecta.",
    "Dni":"No es un DNI ó NIE válido.",
    "Fecha":"No es una fecha válida.<br> Formato válido: 31/12/2000",
    "Fecha_Adulto":"No es una fecha válida.<br> Introduzca un año del "+ (actual.getFullYear()-80) + " al "+(actual.getFullYear()-15)+".",
    "Fecha_Niño":"No es una fecha válida.<br> Introduzca un año del "+ (actual.getFullYear()-15) + " al "+(actual.getFullYear()-2)+".",
    "CP":"No es un código postal válido.",
    "Telefono":"No es un número de teléfono válido.",
    "Email":"No es un email válido.",
    "Equal":"Ya existe este representante del alumno.",
  };

  // Se muestra y se oculta el mensaje de error en los campos del formulario.
  $( "form :input").not(":input[type=radio]").hover(function() {
    $(this).next().css("display", "block");
    }, function() {
    $(this).next().css("display", "none");
    }
  );

  // Transforma el texto con capitalize para guardarlo en la base de datos.
  $('input[type="text"]').keyup(function(){
    var txt = $(this).val();
    $(this).val(txt.replace(/^(.)/g, function($1){ return $1.toUpperCase(); }));
    $(this).css("text-transform", "none");
      if($(this).val().length==0){
        $(this).css("text-transform", "capitalize");
      }
  });

  // Se obliga a guardar en mayuscula la letra del Dni con el guión de separación de la máscara del campo.
  $('form .dni').blur(function(){
    $(this).val($(this).val().toUpperCase());
  });

  function validation(field) {

    $(this).prev().find(".error").remove();
    $(field).next(".mensaje").remove();

    var values = $(field).attr('validation').split(",");
    for (var i=0; i<values.length; i++) {
      validate = 'validate' + values[i].trim()+'('+$(field).attr("id")+')';
      isValid=eval(validate);
       
      if (isValid) {
        $(field).removeClass("invalid");
        $(field).attr("validated", true);
      } 
      else {
        $(field).addClass("invalid");   
        $(field).attr("validated", false);
        $(field).after("<span class='mensaje'>"+message[values[i].trim()]+"</span>");
        $(field).focus();
        
        return false;
      }
    }
  }

  $("form :input").blur(function(){
     
    $(this).prev().find(".error").remove();
    $(this).next(".mensaje").remove();

    if ($(this).attr('validation')) {    
      var values = $(this).attr('validation').split(",");
      for (var i=0; i<values.length; i++) {

        validate = 'validate' + values[i].trim()+'('+$(this).attr("id")+')';
        isValid=eval(validate);
       
        if (isValid) {
          $(this).removeClass("invalid");
          $(this).attr("validated", true);
        } 
        else {
          $(this).addClass("invalid");   
          $(this).attr("validated", false);
          $(this).after("<span class='mensaje'>"+message[values[i].trim()]+"</span>");
          $(this).focus();
        
          return false;
        }
      }
    }
  });

  $("#alumno_responsable1_dni").blur(function() {
    // Se evita repetirlo al hacer submit.
    if(!$('div #alumno_submit').attr('class') && $("#alumno_responsable1_dni").val() != '')
    {
      var dni = $("#alumno_responsable1_dni").val();
      $.ajax({
        type: 'POST',
        url: Routing.generate('comprobar_padre'),
        data: {dni:dni},
        dataType: 'json',
        success: function(response) {
      
        if(($("#tab2").attr("style")=="display: inline;"))
        {
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
          }
          else{
            $("#alumno_responsable1_dni").focus();
            $("#alumno_responsable1_dni").val('');
          }
        }   
        } 
      })
    }
  });

  $("#alumno_responsable2_dni").on('keyup', function(e){
    if($(this).val().trim().length == 0){
        $("#tab2").find("input[id^='alumno_responsable2_']").each(function(){
          $(this).val("");
          $(this).prev().find(".error").remove();
          $(this).next(".mensaje").remove();
          $(this).removeClass("invalid");
          $(this).attr("validated", true);
          $(this).prop("disabled", true);
        });        
        $("#alumno_responsable2_dni").prop("disabled", false);
    }
    if(($(this).val().trim().length == 10)){
      $("#tab2").find(":input[id^='alumno_responsable2_']").each(function(){
        $(this).attr("validated", "");
        $(this).prop("disabled", false);
      });
    }
  });

  $("#alumno_responsable2_dni").blur(function() {
    if(($(this).val().trim().length == 0)){
      $("#tab2").find(":input[id^='alumno_responsable2_']").each(function(){
        $(this).prev().find(".error").remove();
        $(this).next(".mensaje").remove();
        $(this).removeClass("invalid");
        $(this).attr("validated", true);
      });
    }
  //Se evita repetirlo al hacer submit.
    if(!$('div #alumno_submit').attr('class') && $("#alumno_responsable2_dni").val() != '')
    {
      var dni = $("#alumno_responsable2_dni").val();
      $.ajax({
        type: 'POST',
        url: Routing.generate('comprobar_padre'),
        data: {dni:dni},
        dataType: 'json',
        success: function(response) {
      
        if(($("#tab2").attr("style")=="display: inline;"))
        {
          if(confirm(response.message+response.name+"\n\n\n¿Desea obtener sus datos?")==true)
          {
            $.ajax({
              type: 'POST',
              url: Routing.generate('obtenerdatos_responsable'),
              data: {dni:dni},
              dataType: 'json',
              success: function(response) {    
                $("#alumno_responsable2_nombre").focus().val(response.data['nombre']);
                $("#alumno_responsable2_fechaNacimiento").focus().val(response.data['fechaNacimiento']);
                $("#alumno_responsable2_profesion").focus().val(response.data['profesion']);
                $("#alumno_responsable2_estadoCivil").focus().val(response.data['estadoCivil']);
                $("#alumno_responsable2_movil").focus().val(response.data['movil']);
                $("#alumno_responsable2_email").focus().val(response.data['email']); 
              }
            })
          }
          else{
            $("#alumno_responsable2_dni").focus();
            $("#alumno_responsable2_dni").val('');
            $("#alumno_responsable2_dni").keyup();
          }
        }   
        }
      })
    }
  });


  $('.formulario_alumno').on("submit",function(event){
    event.preventDefault();
    $('div #alumno_submit').addClass("no");

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
    $(this).find("div[id^='tab']").each(function(){
      $(this).find(":input").each(function(){
        if((!$(this).attr("validated") || !$(this).attr("validated")==false) && $(this).attr("id").substr(0, 20)!= "alumno_responsable2_"){
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
          
    var formdata=new FormData($(this)[0])

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

          alert(response.message);
        
          limpiarForm($(this));
          //Actualizamos la tabla de alumnos y padres. (Está en alumnos_antiguo de prueba)
          $("#alumnos_antiguo").update_tab();
          $('#result').html("<div id='message'></div>");
          $('#message').html("<h2> Usuario guardado</h2>").hide();
          $('#message').fadeIn('slow').delay(5000).fadeOut('slow');
          event.stopPropagation();   
        },
        error: function (response, desc, err){
          if (response.responseJSON && response.responseJSON.message) {
            if(response.responseJSON.result == 0) {
              //Se elimina las clases de error, para luego añadirlas a los campos que siguen inválidos.
              $("form").find(":input").each(function(i){  
                $(this).prev().find(".error").remove();
                $(this).next(".mensaje").remove();
                $(this).removeClass("invalid");
                $(this).attr("validated", true);
              });
              //Se muestra los campos inválidos.        
              for (var key in response.responseJSON.data) { 
                $("form").find(":input[id='"+key+"']").addClass("invalid");   
                $("form").find(":input[id='"+key+"']").attr("validated", false);
                $("form").find(":input[id='"+key+"']").after("<span class='mensaje'>"+response.responseJSON.data[key]+"</span>");
                $("form").find(":input[id='"+key+"']").prev().append("<span class='error'>Dato inválido</span>");
              }
              //Se muestra el primer campo inválido.
              for (var key in response.responseJSON.data) { 
                form=$("form").find(":input[id='"+key+"']").closest("form");
                tab= $("form").find(":input[id='"+key+"']").closest("div[id^='tab']").attr("id");
                mostrarTab(form, tab);
                $("form").find(":input[id='"+key+"']").focus();
                return false;
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
    $('div #alumno_submit').removeClass("no");
      return false;
  });

});