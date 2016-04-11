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
  
  $("input[id^='año_acamedico']").mask('0000');
  $("input[id^='año_profesional']").mask('00');
  $("input[id^='edit_año_acamedico']").mask('0000');
  $("input[id^='edit_año_profesional']").mask('00');
  

  
  // Se añade un índice de tabuláción a todos los input.
  $("form").find(":input").each(function(i){
    $(this).attr("tabindex",i+1);
  });
    // Asígnamos un valor por defecto al input radio de los formularios de creación.
  $("form[id$='nuevo'] input[id$='_sexo_0']").attr("checked","checked");

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
      $("#"+va).focus()
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
  var sel= $("#tabs div[aria-hidden='false']").attr("id");
  // Se muestra por defecto la primera pestaña del formulario y se selecciona el primer campo.
  if($("#"+sel+" #tab-container li:first").attr("class")=="selected"){
    $("#"+sel+" #content-form-menu").find("[id^='tab']").hide(); // Se oculta todo el contenido.
    $("#"+sel+" #tabs-form li:first").attr("class","selected"); // Se activa la primera pestaña.
    $("#"+sel+" #content-form-menu #tab1").css("display","inline"); // Se muestra el contenido de la primera pestaña.
    $("#"+sel+" #tab1 input:first").focus(); // Se selecciona el primer campo por defecto.
  }

  $('#tab-container a').on("click",function(event){
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




  
  // Se deshabilita los campos del segundo responsable por defecto, excepto el Dni.
  $("form").find(":input[id^='alumno_responsable2_']").each(function(){ 
      $(this).prop("disabled", true);
  });
  $("#alumno_responsable2_dni").prop("disabled", false);





  // Se muestra y se oculta el mensaje de error en los campos del formulario.
  $( "form :input").not(":input[type=radio]").hover(function() {
      if($(this).next().prop("tagName")=="SPAN"){
        $(this).next().css("display", "block");
      }
    }, function() {
      if($(this).next().prop("tagName")=="SPAN"){
        $(this).next().css("display", "none");
      }
    }
  );

    // Para no ocultar el button
  $( "form :button").hover(function() {

    $(this).next().css("display", "block");

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


  // Se valida en línea los campos de los formularios.
  $("form :input").not(":input[type=file]").blur(function(){

    if ($(this).attr('validation')) {    
      validation($(this));
    }
  });







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
    var filter = /^[A-Za-záéíóúÁÉÍÓÚüÜñÑ]{2,}([\s][A-Za-záéíóúÁÉÍÓÚüÜñÑ]+[\.]?)*$/;
    if (!filter.test($(field).val().trim())) {
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
    var filter = /^[0-9]+$/;
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
    if(!$(field).val()){
        return true;
    }
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
    if(!$(field).val()){
        return true;
    }
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
    if(!$(field).val()){
        return true;
    }
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
    if(!$(field).val()){
        return true;
    }
    fecha=$(field).val().split('/');
    if(fecha[2]<(actual.getFullYear()-18) || fecha[2]>(actual.getFullYear()-2)){
      $(field).prev().append("<span class='error'>Dato inválido</span>");
      return false;
    }
    else{
      return true;
    }
  }

  function validateCP(field) {
    if(!$(field).val()){
        return true;
    }
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
    "Words":"Contiene caracteres inválidos.",
    "Letters":"Este campo no puede contener números.",
    "LetterInitial":"Este campo debe empezar con una letra",
    "Numbers":"Este campo sólo puede contener números",
    "Length":"La longitud del campo es incorrecta.",
    "Dni":"No es un DNI ó NIE válido.",
    "Fecha":"No es una fecha válida.<br> Formato válido: 31/12/2000",
    "Fecha_Adulto":"No es una fecha válida.<br> Introduzca un año del "+ (actual.getFullYear()-80) + " al "+(actual.getFullYear()-15)+".",
    "Fecha_Niño":"No es una fecha válida.<br> Introduzca un año del "+ (actual.getFullYear()-18) + " al "+(actual.getFullYear()-2)+".",
    "CP":"No es un código postal válido.",
    "Telefono":"No es un número de teléfono válido.",
    "Email":"No es un email válido.",
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
      form.find("div[id^='lista_']").empty();
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












  

  


 


/////////////////////////////////
// Busqueda en los formularios //
/////////////////////////////////

  //$(".formulario_busqueda_profesor").off().on("submit",function(event) {
    //$(this).off("submit");



  $("#busqueda_profesor select").change(function(){ 
    form= $(this).closest("form");

    form.find("#contenedor_lista span").remove();     
    var curso= form.find("select").val();
    
    form.find("#contenedor_lista").load(Routing.generate('profesores_por_curso', {id:curso}));
  });

$("#busqueda_profesor #contenedor_lista a").on('click', function(event){ 
  event.preventDefault();

  var div= $(this).closest("div[id^='tabs-']");

  $(div).empty();
  $(div).load(Routing.generate('profesor_edit', {id:$(this).attr("id")}));


});



//Formularios de Alumnos

  $("#busqueda_alumno select").change(function(){ 

    form= $(this).closest("form");

    form.find("#contenedor_lista span").remove();     
    var curso= form.find("select").val();
    
    form.find("#contenedor_lista").load(Routing.generate('alumnos_por_curso', {id:curso}));
  });

$("#busqueda_alumno #contenedor_lista a").on('click', function(event){ 
  event.preventDefault();

  var div= $(this).closest("div[id^='tabs-']");

  $(div).empty();
  $(div).load(Routing.generate('alumno_edit', {id:$(this).attr("id")}));


});








//////////////////////////////////
// Formularios de actualización //
//////////////////////////////////

 $("#profesor_edit").on("submit",function(event) {
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
          //Hay que actualizar la pestaña que contiene la tabla de profesores.

          
          //Función para retrasar la ejecución siguiente.
          //setTimeout(function(){ 
          //  $("#editar_profesor_restablecer").trigger('click');
          //}, 6000);

          var arr = form.attr('action').split('/');
          div=form.closest("div[id^='tabs-']");
          $(div).load(Routing.generate('profesor_edit', {id:arr[5]}), function(responseTxt, statusTxt, xhr){
            if(statusTxt == "success"){
              form= $("#profesor_edit");

              form.find("div[id='message']").remove();
              form.find("div[id='result']").html("<div id='message'></div>");
              form.find("div[id='message']").html("<h2> Datos actualizados</h2>").hide();
              form.find("div[id='message']").fadeIn('slow').delay(5000).fadeOut('slow');
            }

            if(statusTxt == "error")
              alert("Error: " + xhr.status + ": " + xhr.statusText);
          });
        } 
      })
 
    }
  });


 $("#alumno_edit").on("submit",function(event) {
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
          //Hay que actualizar la pestaña que contiene la tabla de profesores.

          
          //Función para retrasar la ejecución siguiente.
          //setTimeout(function(){ 
          //  $("#editar_profesor_restablecer").trigger('click');
          //}, 6000);

          var arr = form.attr('action').split('/');
          div=form.closest("div[id^='tabs-']");
          $(div).load(Routing.generate('alumno_edit', {id:arr[5]}), function(responseTxt, statusTxt, xhr){
            if(statusTxt == "success"){
              form= $("#alumno_edit");

              form.find("div[id='message']").remove();
              form.find("div[id='result']").html("<div id='message'></div>");
              form.find("div[id='message']").html("<h2> Datos actualizados</h2>").hide();
              form.find("div[id='message']").fadeIn('slow').delay(5000).fadeOut('slow');
            }

            if(statusTxt == "error")
              alert("Error: " + xhr.status + ": " + xhr.statusText);
          });
        } 
      })
 
    }
  });

  // Añadir foto
$('#iconos_foto a').on('click', function(event){ 
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

      if(form.find("#actual img").attr("src").indexOf("SinFoto") < 0)
        {
          form.find("#icono_restablecer").removeClass("disable");    
        }
      return false;
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

      return false;
    }
    else{
      form.find("input[type='file']").trigger('click'); 
    }
    event.stopPropagation(); 
});




  $("#botones_form button[id$=nuevaFicha]").on('click', function(event){
    event.preventDefault();
    form= $(this).closest("form");

    div=$(this).closest("div[id^='tabs-']");
    var arr = form.attr('id').split('_');
    $(div).load(Routing.generate(arr[0]+'_search'));
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
 
  $("form[id$='edit'] input[type='file']").change(function(){ 
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
        form.find("#error_foto").append("<p>- No es un archivo válido.<br><br>(Formatos válidos: .png / .jpg / .jpeg)</p>");
      }
      else
      {
        form.find("#por_defecto img").addClass('invalid');
        form.find("#error_foto").append("<p>- El tamaño supera el limite permitido.<br><br>(Tamaño máximo permitido: "+$(this).attr('size')+"KB)</p>");
      }
    }
  });

  $("form[id$='nuevo'] input[type='file']").change(function(){ 
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



  







});