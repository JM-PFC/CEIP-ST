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
  //$("input[type='time']").mask('00:00', {placeholder: "__:__"});


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
    if(fecha[2]<(actual.getFullYear()-18) || fecha[2]>(actual.getFullYear()-2)){
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
      } 
      else {
        $(field).addClass("invalid");   
        $(field).attr("validated", false);
        if(values[i].trim()!="Empty_"){
          $(field).after("<span class='mensaje'>"+message[values[i].trim()]+"</span>");
        }
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
      form.find("#contenedor_lista div[id^='lista_']").empty();
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

  // Se valida en línea los campos de los formularios.
  $(document).on('blur','form :input[type!=file]' ,function() {

    if ($(this).attr('validation')) {    
      validation($(this));
    }
  });

  setInterval(function(){
    // Se añade un índice de tabuláción a todos los input.
  $("form").find(":input").each(function(i){
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
  $(document).on("keyup",'input[class!="text_transform_none"]', function(){
    var txt = $(this).val();
    $(this).val(txt.replace(/^(.)/g, function($1){ return $1.toUpperCase(); }));
    $(this).css("text-transform", "none");
      if($(this).val().length==0){
        $(this).css("text-transform", "capitalize");
      }
  });

  // Al presionar cualquier tecla en cualquier elemento del formulario se ejectua la siguiente función
  // Función para desplazarse mediante teclado por los campos.
  $(document).on("keydown",':input', function(e){
    // Sólo importa si las teclas presionadas fueron TAB o ENTER. (Para ver el código de otras teclas: http://www.webonweboff.com/tips/js/event_key_codes.aspx)
    // Y que no sean botones o textarea.
    if(e.keyCode == 9 || (e.keyCode == 13 && (!($(this).is("textarea")) && !($(this).is("button")))))
    {
      // Se obtiene el número del tabindex del campo actual.
      var currentTabIndex = $(this).attr('tabindex');
      // Se le suma 1 para tener el siguiente.
      var nextTabIndex    = parseInt(currentTabIndex) + 1;
      // Se obtiene (si existe) el siguiente elemento usando la variable nextTabIndex
      var nextField       = $(this).closest("div[id^='tabs-']").find('input[tabindex='+nextTabIndex+']');
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
          nextField= $(this).closest("div[id^='tabs-']").find('input[tabindex='+nextTabIndex+']');
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
          if(confirm(response.message+response.name+"\n\n\n¿Desea obtener sus datos?")==true)
          {
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
          }
          else{
            if(form.find("input[id$='responsable1_nombre']").val()==''){
              form.find("input[id$='responsable1_nombre']").removeClass("invalid");
              form.find("input[id$='responsable1_nombre']").prev().find(".error").remove();
              form.find("input[id$='responsable1_nombre']").next(".mensaje").remove();         
            }
            form.find("input[id$='responsable1_dni']").focus();
            form.find("input[id$='responsable1_dni']").val('');
            form.find("input[id$='responsable1_dni']").keyup();
          }
        }   
        } 
      })
      $(this).attr("edit",false);
    }
  });
/*
$(document).on('keyup',"#alumno_responsable1_dni",function(e){

    if($(this).val().trim().length == 0){
        $("#tab2").find("input[id^='alumno_responsable1_']").each(function(){
          $(this).val("");
          $(this).prev().find(".error").remove();
          $(this).next(".mensaje").remove();
          $(this).removeClass("invalid");
          $(this).attr("validated", false);
          $(this).prop("readonly", false);
        });      
        $("#alumno_responsable1_estadoCivil").prop("readonly", false);  
        $("#alumno_responsable1_dni").prop("disabled", false);
    }
    if(($(this).val().trim().length == 10)){
      $("#tab2").find(":input[id^='alumno_responsable1_']").each(function(){
        $(this).attr("validated", "");
        $(this).prop("disabled", false);
      });
    }
    if(($(this).val().trim().length < 10) && $("#alumno_responsable1_nombre").attr("readonly")=='readonly'){
        var dni=$("#alumno_responsable1_dni").val();
        $("#tab2").find("input[id^='alumno_responsable1_']").each(function(){
          $(this).val("");
          $(this).prev().find(".error").remove();
          $(this).next(".mensaje").remove();
          $(this).removeClass("invalid");
          $(this).attr("validated", false);
          $(this).prop("readonly", false);
        });   
        $("#alumno_responsable1_dni").val(dni);   
        $("#alumno_responsable1_estadoCivil").prop("readonly", false);  
        $("#alumno_responsable1_dni").prop("disabled", false);
    }
  });
*/

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
        form.find("input[id$='responsable2_estadoCivil']").prop("disabled", true);  
        form.find("input[id$='responsable2_dni']").prop("disabled", false);
    }
    if(($(this).val().trim().length == 10)){
      form.find("input[id^='alumno_responsable2_']").each(function(){
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
      $.ajax({
        type: 'POST',
        url: Routing.generate('comprobar_padre'),
        data: {dni:dni},
        dataType: 'json',
        success: function(response) {
      
        if((!($(this).find("#tab2").length) || ($("#tab2").attr("style")=="display: inline;"))&& response.success==true )
        {
          if(confirm(response.message+response.name+"\n\n\n¿Desea obtener sus datos?")==true)
          {
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
          }
          else{
            $("#alumno_responsable2_dni").focus();
            $("#alumno_responsable2_dni").val('');
            $("#alumno_responsable2_dni").keyup();
          }
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
        
          limpiarForm(form);
          //Actualizamos la tabla de alumnos y padres. (Está en alumnos_antiguo de prueba)
          $("#alumnos_antiguo").update_tab();
          form.find("div[id='result']").html("<div id='message'></div>");
          form.find("div[id='message']").html("<h2> Usuario guardado</h2>").hide();
          form.find("div[id='message']").fadeIn('slow').delay(5000).fadeOut('slow');
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

  $(document).on("submit",".formulario_busqueda_alumno",function(event) {
    event.preventDefault();
    form= $(this).closest("form");

    form.find("#contenedor_lista span").remove();

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
    form.find(":input").each(function(){
      if(!$(this).attr("validated") || !$(this).attr("validated")==false){
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
      var nombre_profesor= $("#busqueda_alumno_nombre").val();
      var apellido1_profesor= $("#busqueda_alumno_apellido1").val();
      var apellido2_profesor= $("#busqueda_alumno_apellido2").val();
      $.ajax({
        type: 'POST',
        url: Routing.generate('comprobar_alumno'),
        data: {nombre:nombre_profesor, apellido1:apellido1_profesor, apellido2:apellido2_profesor },
        dataType: 'json',
        success: function(response) {
      
          if(response.data!=null)
          {
            div=$("#buscador_alumno").closest("div[id^='tabs-']");
            $(div).empty();
            $(div).load(Routing.generate('alumno_edit', {id:response.data}));
            }
          else{
            form.find("#contenedor_lista").empty();
            form.find("#contenedor_lista").append("<span >No se ha encontrado el alumno</span>");
            }   
        } 
      })
    }
  });


$(document).on("submit",".formulario_profesor",function(event){
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
    $(this).find("#tab3 :input").each(function(){
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

          alert(response.message);
        
          limpiarForm(form);
          //Hay que actualizar la pestaña que contiene la tabla de profesores.
          form.find("div[id='message']").remove();
          form.find("div[id='result']").html("<div id='message'></div>");
          form.find("div[id='message']").html("<h2> Profesor guardado</h2>").hide();
          form.find("div[id='message']").fadeIn('slow').delay(5000).fadeOut('slow');
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

  $(document).on("submit",".formulario_busqueda_profesor",function(event) {
    event.preventDefault();
    form= $(this).closest("form");

    form.find("#contenedor_lista span").remove();

    var val=0;
    // Se recorre los campos del formulario mirando si estan validados o no.
    form.find(":input").each(function(){
      if(!$(this).attr("validated") || !$(this).attr("validated")==false){
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
      var nombre_profesor= $("#busqueda_profesor_nombre").val();
      var apellido1_profesor= $("#busqueda_profesor_apellido1").val();
      var apellido2_profesor= $("#busqueda_profesor_apellido2").val();
      $.ajax({
        type: 'POST',
        url: Routing.generate('comprobar_profesor'),
        data: {nombre:nombre_profesor, apellido1:apellido1_profesor, apellido2:apellido2_profesor },
        dataType: 'json',
        success: function(response) {
      
          if(response.data!=null)
          {
            div=$("#buscador_profesor").closest("div[id^='tabs-']");
            $(div).empty();
            $(div).load(Routing.generate('profesor_edit', {id:response.data}));
            }
          else{
            form.find("#contenedor_lista").empty();
            form.find("#contenedor_lista").append("<span >No se ha encontrado el profesor</span>"); 
          }   
        } 
      })
    }
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

/*
      if(val==0){
    $( "#dialog-confirm" ).dialog({
      height:300,
      modal: true,
      buttons: {
        "Aceptar": function() {
          $( this ).dialog( "close" );

          $.ajax({
          //................
         })
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
$("#dialog-confirm").prev().hide();
$("#dialog-confirm span").hide();


  }*/
          
  if(confirm("Se va a asignar un nuevo responsable al alumno \ny no se podrá recuperar el anterior.\n\n¿Estas seguro de realizar la nueva asignación?\n\n")==true)
    {
     if(val==0){
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data:$(this).serialize(), 
        dataType: 'json',
  
        // Mostramos un mensaje con la respuesta de PHP
        success: function(response) {

          alert(response.message);

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
              if(responsable=="responsable1"){
                $("#edit_alumno_responsable_dni_1").focus().val(response.data['dni']);
                $("#edit_alumno_responsable1_nombre").focus().val(response.data['nombre']);
                $("#edit_alumno_responsable1_fechaNacimiento").focus().val(response.data['fechaNacimiento']);
                $("#edit_alumno_responsable1_profesion").focus().val(response.data['profesion']);
                $("#edit_alumno_responsable1_estadoCivil").focus().val(response.data['estadoCivil']);
                $("#edit_alumno_responsable1_movil").focus().val(response.data['movil']);
                $("#edit_alumno_responsable1_email").focus().val(response.data['email']); 
              }
              else{
                $("#edit_alumno_responsable_dni_2").focus().val(response.data['dni']);
                $("#edit_alumno_responsable2_nombre").focus().val(response.data['nombre']);
                $("#edit_alumno_responsable2_fechaNacimiento").focus().val(response.data['fechaNacimiento']);
                $("#edit_alumno_responsable2_profesion").focus().val(response.data['profesion']);
                $("#edit_alumno_responsable2_estadoCivil").focus().val(response.data['estadoCivil']);
                $("#edit_alumno_responsable2_movil").focus().val(response.data['movil']);
                $("#edit_alumno_responsable2_email").focus().val(response.data['email']); 
              }
              $("#alumno_edit").find("#noresponsable").addClass("oculto");
              $("#alumno_edit").find("#responsable").removeClass("oculto");
            }
           })  
          }
          else{
            alert("Este responsable ya se encuentra asignado a este alumno");
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
            alert(desc);
          }
        }
      })
     }
    }
      return false;
  });


  $(document).on('click',"#botones_form button[id$='_restablecer']",function(event){
    event.preventDefault();
    form= $(this).closest("form");

    var arr = form.attr('action').split('/');
    div=$(this).closest("div[id^='tabs-']");
    $(div).load(Routing.generate(arr[4]+"_edit", {id:arr[5]}));
  });

  $(document).on('click',"#botones_form button[id$=nuevaFicha]", function(event){
    event.preventDefault();
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

/////////////////////////////////
// Busqueda en los formularios //
/////////////////////////////////

  $(document).on("click","#contenedor_lista a", function(event){ 
    event.preventDefault();
    form= $(this).closest("form");
    
    var arr = form.attr('id').split('_');
    var div= $(this).closest("div[id^='tabs-']");

    $(div).empty();
    $(div).load(Routing.generate(arr[1]+'_edit', {id:$(this).attr("id")}));
  });

  //Cambios en formularios de busqueda
  $(document).on('change','#busqueda_profesor select',function() {
    form= $(this).closest("form");

    form.find("#contenedor_lista span").remove();     
    var curso= form.find("select").val();
    
    form.find("#contenedor_lista").load(Routing.generate('profesores_por_curso', {id:curso}));
  });

  $(document).on('change','#busqueda_alumno select',function() {
    form= $(this).closest("form");

    form.find("#contenedor_lista span").remove();     
    var curso= form.find("select").val();
    
    form.find("#contenedor_lista").load(Routing.generate('alumnos_por_curso', {id:curso}));
  });



//////////////////////////////////
// Formularios de actualización //
//////////////////////////////////

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

  
  $(document).on("submit","#alumno_edit",function(event) {
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
      alert("entra");
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

              form.find("div[id='message']").remove();
              form.find("div[id='result']").html("<div id='message'></div>");
              form.find("div[id='message']").html("<h2> Datos actualizados</h2>").hide();
              form.find("div[id='message']").fadeIn('slow').delay(5000).fadeOut('slow');
            }

            if(statusTxt == "error")
              alert("Error: " + xhr.status + ": " + xhr.statusText);
          });
;
        }
      })
      return false;
 
    }
  });


  $(document).on('click',"#alumno_edit button[id$='_modal']",function(event){
    event.preventDefault();
    form= $(this).closest("form");

    var resp= $(this).attr('id').split('_');
    var alum = $(this).closest(form).attr('action').split('/');

    $('#padres_dialog').load(Routing.generate("padres_new"), function(){
      $('#padres_dialog form').attr("responsable",resp[1]);
      $('#padres_dialog form').attr("alumno",alum[5]);
    }).dialog('open'); 
  });

  $(document).on('click',"#eliminar_responsable",function(event){
    event.preventDefault();
    form= $(this).closest("form");

    var alum = $(this).closest(form).attr('action').split('/');
    var id_alumno =alum[5];

    if(confirm("Se va a eliminar el responsable del alumno \ny no se podrá recuperar.\n\n¿Estas seguro de eliminarlo?\n\n")==true)
    {
      $.ajax({
        type: 'POST',
        url: Routing.generate('eliminar_responsable'),
        data:{id_alumno:id_alumno},
        dataType: 'json',
  
        // Mostramos un mensaje con la respuesta de PHP
        success: function(response) {
          form.find("#responsable").addClass("oculto");
          form.find("#noresponsable").removeClass("oculto");
        }
      })
    }
      return false;
  });

$(document).on("blur","input[id='profesor_dni']",function() {
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
          form.find("input[id$='profesor_dni']").prev().append("<span class='error'>Dato inválido</span>");
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
});
  

$(document).on("blur","input[id='edit_profesor_dni']",function() {
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
          form.find("input[id$='profesor_dni']").prev().append("<span class='error'>Dato inválido</span>");
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
        form.find("#error_foto").append("<p>- No es un archivo válido.<br><br>(Formatos válidos: .png / .jpg / .jpeg)</p>");
      }
      else
      {
        form.find("#por_defecto img").addClass('invalid');
        form.find("#error_foto").append("<p>- El tamaño supera el limite permitido.<br><br>(Tamaño máximo permitido: "+$(this).attr('size')+"KB)</p>");
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
//           Cursos             //
//////////////////////////////////

  $(document).on('click',"#registro_cursos a[id$='_modal']",function(event){
    event.preventDefault();
    var nivel= $(this).attr('id').split('_');
    
    $('#cursos_dialog').load(Routing.generate("curso_new"), function(){
      $('#cursos_dialog form').attr("nivel",nivel[1].charAt(0).toUpperCase() + nivel[1].slice(1) );
      $("#curso_nivel").val($("#curso_nuevo").attr("nivel"));
    }).dialog('open'); 
  });

  $(document).on('click',"#registro_cursos a[href$='edit']",function(event){
    event.preventDefault();
    var arr= $(this).attr('href').split('/');
    $('#cursos_dialog').load(Routing.generate(arr[4]+"_edit", {id:arr[5]}), function(){
    }).dialog('open'); 
  });

  $(document).on("submit","#curso_nuevo",function(event){
    event.preventDefault();
    form= $(this).closest("form");
    $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data:$(this).serialize(), 
      dataType: 'json',
  
      // Mostramos un mensaje con la respuesta de PHP
      success: function(response) {
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

        $("#asignar_aula").update_tab();

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
      return false;
  });

  $(document).on("submit","#curso_edit",function(event){
    event.preventDefault();
    form= $(this).closest("form");

      $.ajax({
        type: 'PUT',
        url: $(this).attr('action'),
        data:$(this).serialize(), 
  
        success: function() {
        $("#cursos_dialog").dialog('close');
        tab=$(".contenido_main").find("div[aria-hidden='false']");
        $(tab).load(Routing.generate('curso'));
        $("#tabs #lista_cursos").empty();
        $("#tabs #lista_cursos").load(Routing.generate('alumno_listaCursos'));

        $("#button_grupos_rest").trigger("click");
        }
      })
      return false;
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
        // Si no hay alumnos asignados al curso se puede eliminar.
        if(response.data==null){
          if(confirm("Se va a eliminar el curso del sistema.\n\n¿Estas seguro de eliminarlo?\n\n")==true)
          {
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

              $("#asignar_aula").update_tab();

              }
            })
          }
        }
        else{
          alert(response.data);
        }
      }
    })
      return false;
  });

/*Registro de Nº de grupos por curso.*/

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
          // Si no hay alumnos asignados al curso se puede eliminar.
          if(response.data!=null){  
            tr.find("select").addClass("modified");
            tr.find("select").addClass("error_guardar");
            tr.find("select").removeClass("modified");
            $("#registro_Ngrupos #aviso_error").removeClass("oculto");
            tr.find("span").removeClass("oculto");
            //alert(response.data);
            tr.find("select").val(num_grupos_ant);
          }
          else{
            // Se actualiza el atributo numGrupos de la entidad Curso y se crean los grupos correspondiente al curso.
            $.ajax({
              type: 'POST',
              url: Routing.generate('Asignar_Ngrupos'),
              data: {curso:curso,nivel:nivel,num_grupos:num_grupos},
              async:false,

            })
            // Se actualiza la pestaña de asignar aula.
            $("#asignar_aula").update_tab();
            tr.find("select").removeClass("modified");

          tr.find("select option:eq("+(num_grupos-1)+")").attr('selected',true);
          tr.find("select option:eq("+(num_grupos_ant-1)+")").attr('selected',false);
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
    $("#contenedor_registro_Ngrupos select[class='modified']").each(function(){  
      $(this).closest("tr").find("button").trigger("click");
    });

    // Se actualiza la pestaña de asignar aula una vez asignado todas.
    $("#select_grupos_all option:eq(0)").prop('selected', true);
       setTimeout(function(){ 
        $("#asignar_aula").update_tab();},3000);
  
  });

  // Se actualiza la pestaña de asignar grupos.
  $(document).on('click',"#registro_Ngrupos #button_grupos_rest",function(event){
    div=$(this).closest("div[id^='tabs-']");
    $(div).load(Routing.generate("curso_show"));
  });


/*Asignación de aula.*/

  // Se indica el select modificado.
  $(document).on('change',"#aula",function(event){
    $(this).addClass("modified");
  });

  // Se quita la marca del input modificado y se actualiza el aula.
  $(document).on('click',"#contenedor_asignar_aula button",function(event){
    $(this).closest("tr").find("input").removeClass("modified");
    curso=$(this).closest("tr").children('td').slice(0, 2).html();
    nivel=$(this).closest("tr").children('td').slice(1, 2).html();
    letra=$(this).closest("tr").children('td').slice(2, 3).html();
    aula=$(this).closest("tr").find("input").val();

    // Se actualiza el atributo aula de la entidad Grupo.
    $.ajax({
      type: 'POST',
      url: Routing.generate('asignar_aula'),
      data: {curso:curso,nivel:nivel,letra:letra,aula:aula},
    })

  });

  // Se quita la marca de los input modificados y actualiza el aula.
  $(document).on('click',"#asignar_aula #button_grupos_all",function(event){
    $("#contenedor_asignar_aula input[class='modified']").each(function(){  
      $(this).closest("tr").find("button").trigger("click");
    });
  });

  // Se actualiza la pestaña de asignar aula.
  $(document).on('click',"#asignar_aula #button_grupos_rest",function(event){
    div=$(this).closest("div[id^='tabs-']");
    $(div).load(Routing.generate("grupo_show"));
  });

  // Se hace click en toda la fila de la tabla.
  $(document).on('click',".contenedor_registro .inner_table td",function(event){
    $(this).find("a").trigger("click");
  });


//////////////////////////////////
//         Asignaturas          //
//////////////////////////////////

  $(document).on('click',"#registro_asignaturas a[id$='_modal']",function(event){
    event.preventDefault();
    var tipo= $(this).attr('id').split('_');
    
    $('#asignaturas_dialog').load(Routing.generate("asignatura_new"), function(){
      $('#asignaturas_dialog form').attr("tipo",tipo[1].charAt(0).toUpperCase() + tipo[1].slice(1) );
      $("#asignatura_tipo").val($("#asignatura_nueva").attr("tipo"));
    }).dialog('open'); 
  });

  $(document).on('click',"#registro_asignaturas a[href$='edit']",function(event){
    event.preventDefault();
    var arr= $(this).attr('href').split('/');
    $('#asignaturas_dialog').load(Routing.generate(arr[4]+"_edit", {id:arr[5]}), function(){
    }).dialog('open'); 
  });

  $(document).on("submit","#asignatura_nueva",function(event){
    event.preventDefault();
    form= $(this).closest("form");
    $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data:$(this).serialize(), 
      dataType: 'json',
  
      // Mostramos un mensaje con la respuesta de PHP
      success: function(response) {
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
      return false;
  });



  $(document).on("submit","#asignatura_edit",function(event){
    event.preventDefault();
    form= $(this).closest("form");

      $.ajax({
        type: 'PUT',
        url: $(this).attr('action'),
        data:$(this).serialize(), 
  
        success: function() {
        $("#asignaturas_dialog").dialog('close');
        tab=$(".contenido_main").find("div[aria-hidden='false']");
        $(tab).load(Routing.generate('asignatura'));
        //$("#tabs #lista_asignaturas").empty();
        //$("#tabs #lista_asignaturas").load(Routing.generate('alumno_listaAsignatura'));
        }
      })
      return false;
  });


  $(document).on("click","#asignatura_delete button",function(event){
    event.preventDefault();
    form= $(this).closest("form");
    var arr= $('#asignatura_delete').attr('action').split('/');
    
    if(confirm("Se va a eliminar la asignatura del sistema.\n\n¿Estas seguro de eliminarla?\n\n")==true)
    {
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
        }
      })
    }
      return false;
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
  
 
 // Se actualiza el calenndario de festivos
  $(document).on('click',"#actualizar_calendario",function(event){
    event.preventDefault();
    // Retardo para ejecutarlo una vez cargado el datepicker.
        $("#div_leyenda").addClass("oculto");      

    setTimeout(function(){ 
      mes=$("#contenedor_calendar tbody td[data-handler='selectDay']").attr("data-month");
      año=$("#contenedor_calendar tbody td[data-handler='selectDay']").attr("data-year");
      mes++;
      if(mes!=10 && mes!=11 && mes!=12){
        mes='0'+mes;
      }
      //Se carga los festivos en la leyenda.
      $("#div_leyenda").empty();
      $("#div_leyenda").load(Routing.generate('festivos_por_mes', {id:mes}));
      $("#calendario td a").removeClass("festivo");

      $.ajax({
        type: 'POST',
        url: Routing.generate('dias_festivos'),
        data: {mes:mes},
        dataType: 'json',
        success: function(response) {
          for (var key in response.data) { 
            $(".ui-datepicker-calendar td[data-month='"+(mes-1)+"'] a:contains('"+response.data[key]["dia"]+"')").each(function(){  
              var dato=response.data[key]["dia"];
              var comp= $(this).text();
              // Con :contains se obtiene los días del calendario que contiene en sus dígitos el dato dado.
              // Si el dato obtenido tiene un sólo dígito, se excluye los días de dos dígitos del calendario que contiene ese dato.
              if(String(dato).length=="1"){
                if(String(comp).length=="1"){
                  $(this).addClass("festivo");
                  // Se comprueba que el día festivo es un domingo y que el lunes no hay ningún festivo añadido. 
                  if($(this).closest("td").hasClass(" ui-datepicker-week-end ") &&  $(this).closest("tr").find("td:last a").text() == $(this).text() && !$("#div_leyenda h4[id='"+$(this).closest("tr").next("tr").find("td:nth-child(1) a").text()+"']").length){
                    // Mostramos el traspado del festivo al lunes.
                    $(this).closest("tr").next("tr").find("td:nth-child(1) a").addClass("festivo");
                    $(this).closest("tr").next("tr").find("td:nth-child(1) a").attr("tipo","traslado");
                    setTimeout(function(){
                      //Se muestra sólo un traslado del festivo (ya que hay dos elementos con el mismo día).
                      $("#div_leyenda h4[id='"+dato+"']").each(function(){
                        if($(this).next().text().indexOf(" Vacaciones ")<0){
                          $( "<h4 id='"+(dato+1)+"'>"+(dato+1)+"</h4><h4 id='h4_descripcion'>Traslado del Festivo del día "+dato+".</h4>" ).insertAfter( $(this).next());
                        }
                      });
                    }, 50);
                  }
                }
              }
              else{
                $(this).addClass("festivo");
                // Se comprueba que el día festivo es un domingo, que el lunes no hay ningún festivo añadido y que el festivo no coincida con el último día del mes. 
                if($(this).closest("td").hasClass(" ui-datepicker-week-end ") &&  $(this).closest("tr").find("td:last a").text() == $(this).text() && !$("#div_leyenda h4[id='"+$(this).closest("tr").next("tr").find("td:nth-child(1) a").text()+"']").length && $("#calendario tbody tr:last td:last a").text()!=$(this).text() ){
                  // Mostramos el traspado del festivo al lunes.
                  $(this).closest("tr").next("tr").find("td:nth-child(1) a").addClass("festivo");
                  $(this).closest("tr").next("tr").find("td:nth-child(1) a").attr("tipo","traslado");
                  setTimeout(function(){
                    //Se muestra sólo un traslado del festivo (ya que hay dos elementos con el mismo día).
                    $("#div_leyenda h4[id='"+dato+"']").each(function(){
                      if($(this).next().text().indexOf(" Vacaciones ")<0){
                        $( "<h4 id='"+(dato+1)+"'>"+(dato+1)+"</h4><h4 id='h4_descripcion'>Traslado del Festivo del día "+dato+".</h4>" ).insertAfter( $(this).next());
                      }
                    });
                  }, 50);
                }
              }
            });
          }
          // Se comprueba de que existe un día de comienzo/fin de vacaciones.
          if(response.inicio_vacaciones && response.fin_vacaciones){
            var descripcion_inicio=$("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'").next().text().split(" ");
            var descripcion_fin=$("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'").next().text().split(" ");
            // Se comprueba que existe ambos días de vacaciones en el mismo mes y que pertenecen al mismo tipo de vacaciones.
            if(descripcion_inicio[3]==descripcion_inicio[3]){
              // Se retarda para modificar los días festivos dentro de las vacaciones.
              setTimeout(function(){
                $(".ui-datepicker-calendar td[data-month='"+(mes-1)+"']").find("a").filter(function(){return $(this).text()==response.inicio_vacaciones["dia"];}).removeClass("festivo");
                $(".ui-datepicker-calendar td[data-month='"+(mes-1)+"']").find("a").filter(function(){return $(this).text()==response.fin_vacaciones["dia"];}).removeClass("festivo");
                // Se comprueba si existe al inicio de vacaciones día siguiente con festivo de traslado para eliminarlo.
                if($("#div_leyenda h4[id='"+(response.inicio_vacaciones["dia"]+1)+"'").next().text().indexOf("Traslado") >= 0){
                    $("#div_leyenda h4[id='"+(response.inicio_vacaciones["dia"]+1)+"'").next().remove();
                    $("#div_leyenda h4[id='"+(response.inicio_vacaciones["dia"]+1)+"'").remove();
                    $(".ui-datepicker-calendar td[data-month='"+(mes-1)+"']").find("a").filter(function(){return $(this).text()==(response.inicio_vacaciones["dia"]+1);}).removeClass("festivo");                
                }
                // Se añade la clase vacaciones a los días correspondientes.
                for(var i = response.inicio_vacaciones["dia"]; i <= response.fin_vacaciones["dia"]; i++){
                  $(".ui-datepicker-calendar td[data-month='"+(mes-1)+"']").find("a").filter(function(){return $(this).text()==i;}).addClass("vacaciones");
                  $("#div_leyenda h4[id='"+i+"'").addClass("vacaciones");              
                }
                // Si se repite el día de inicio de vacaciones, se elimina la clase vacaciones en los días de la leyenda que no contiene la información de las vacaciones.
                if($("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").length>1){
                  $("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").each(function(){
                    if($(this).next().text().indexOf(" Vacaciones ")<0){
                      $(this).removeClass("vacaciones");
                    }
                  });
                }
                // Si se repite el día de fin de vacaciones, se elimina la clase vacaciones en los días de la leyenda que no contiene la información de las vacaciones.
                if($("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").length>1){
                  $("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").each(function(){
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
                  $("#div_leyenda h4[class='vacaciones']").text(response.inicio_vacaciones["dia"]+"-"+response.fin_vacaciones["dia"]);
                  $("#div_leyenda h4[class='vacaciones']").next().text($("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").next().text().replace("Inicio ",""));
                }
              }, 50);
            }
          }
          else if(response.inicio_vacaciones){

            var date = new Date(año,mes,1);
            var fecha_ultimoDia = new Date(date.getFullYear(), date.getMonth() , 0).toString().split(" ");
            último_día=fecha_ultimoDia[2];
            
            setTimeout(function(){
            // Si se repite el día de inicio de vacaciones, se asigna la clase vacaciones al día de inicio de las vacaciones.
            if($("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").length>1){
              $("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").each(function(){
                if($(this).next().text().indexOf(" Vacaciones ")>0){
                  $(this).addClass("vacaciones")
                }
              });
              // Se comprueba que el festivo está tras las vacaciones en la leyenda, en caso contrario se modifica.
              if($("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").closest("h4[id='"+response.inicio_vacaciones["dia"]+"']").text()==response.inicio_vacaciones["dia"]){
                vacaciones=$("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").next().text();
                $("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").next().remove();
                $("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class='vacaciones']").remove();

                $( "<h4 id='"+response.inicio_vacaciones["dia"]+"'class='vacaciones'>"+response.inicio_vacaciones["dia"]+"</h4><h4 id='h4_descripcion'>"+vacaciones+"</h4>" ).insertBefore( $("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"'][class!='vacaciones']")); 
              }
            }
            else{
              $("#div_leyenda h4[id='"+response.inicio_vacaciones["dia"]+"']").addClass("vacaciones");
            }

            // Se modifica los días desde inicio de vacaciones hasta final de mes puesto que sigue en el siguiente mes.
            for(var i = response.inicio_vacaciones["dia"]; i <= último_día; i++){
              $(".ui-datepicker-calendar td[data-month='"+(mes-1)+"']").find("a").filter(function(){return $(this).text()==i;}).addClass("vacaciones");
            }

            }, 50);
          }
          else if(response.fin_vacaciones){

            setTimeout(function(){

            //Se añade la clase vacaciones, sólo a los elementos de ese día que contiene la información de las vacaciones.
            if($("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").length>1){
              $("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").each(function(){
                if($(this).next().text().indexOf(" Vacaciones ")>0){
                  $(this).addClass("vacaciones");
                }
              });
              // Se comprueba que el festivo está tras las vacaciones en la leyenda, en caso contrario se modifica.
              if($("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class='vacaciones']").closest("h4[id='"+response.fin_vacaciones["dia"]+"']").text()==response.fin_vacaciones["dia"]){
                vacaciones=$("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class='vacaciones']").next().text();
                $("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class='vacaciones']").next().remove();
                $("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class='vacaciones']").remove();

                $( "<h4 id='"+response.fin_vacaciones["dia"]+"'class='vacaciones'>"+response.fin_vacaciones["dia"]+"</h4><h4 id='h4_descripcion'>"+vacaciones+"</h4>" ).insertBefore( $("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"'][class!='vacaciones']")); 
              }
            }
            else{
              $("#div_leyenda h4[id='"+response.fin_vacaciones["dia"]+"']").addClass("vacaciones");
            }

            // Se modifica los días desde inicio del mes hasta final de vacaciones puesto que sigue en el mes anterior.
            for(var i = response.fin_vacaciones["dia"]; i >= 1; i--){
              $(".ui-datepicker-calendar td[data-month='"+(mes-1)+"']").find("a").filter(function(){return $(this).text()==i;}).addClass("vacaciones");
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
                $("#div_leyenda").prepend("<h4 id='"+dia_1.text()+"'>"+dia_1.text()+"</h4><h4 id='h4_descripcion'>Traslado del Festivo del día "+dia_ant+" de "+MES[$("#calendario tbody tr:first td:last").attr("data-month")-1]+" </h4>" );
              }, 20);
            }
          }
        })
      }
      setTimeout(function(){
        // Se muestra la leyenda una vez actualizado el calendario. 
        $("#div_leyenda").removeClass("oculto");
      }, 200);        
    }, 200);
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
          alert("El día y mes introducido ya tiene asignado un festivo en el sistema.");
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
      type: 'POST',
      url: Routing.generate('comprobar_festivo'),
      data: {dia:dia, mes:mes, descripcion:descripcion},
      dataType: 'json',
      success: function(response) {
        if(response.data!=null)
        {
          alert("El día y mes introducido ya tiene asignado un festivo en el sistema.");
          return false;
        }
        else{
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
              $("#actualizar_calendario").trigger("click");
            }
          })
          return false;
        } 
      } 
    })
  });

  $(document).on("click","#festivos_delete button",function(event){
    event.preventDefault();
    form= $(this).closest("form");
    var arr= $('#festivos_delete').attr('action').split('/');
    
    if(confirm("Se va a eliminar el día festivo del sistema.\n\n¿Estas seguro de eliminarlo?\n\n")==true)
    {
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
          $("#actualizar_calendario").trigger("click");
        }
      })
    }
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
        alert("hola");
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
            // Se actualiza la pestaña de periodo lectivo.
            $("#periodo_lectivo").update_tab();
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
          }
        })
      }
    });
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
          alert(response.message);
          // Se actualiza la pestaña de periodo lectivo.
          $("#periodo_lectivo").update_tab();
        }
      })
    }
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
      $("#tabs ul li[aria-selected='true'] span").trigger("click")
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


  //////////////////////////////////
  //        Instalaciones         //
  //////////////////////////////////
  $(document).on("focus","#instalación_nombre",function(event){
    event.preventDefault();
      $("#instalación_nombre").removeClass("invalid_placeholder");
  });

  $(document).on("blur","#instalación_nombre",function(event){
    event.preventDefault();
    if($(this).val()==""){
      $(this).addClass("invalid_placeholder");
    }
    else{
      $(this).removeClass("invalid_placeholder");
    }
  });

  $(document).on("click","#instalación_new",function(event){
    event.preventDefault();

    if($("#instalación_nombre").val()==""){
      $("#instalación_nombre").addClass("invalid_placeholder");
    }
    else{
      $.ajax({
        type: 'POST',
        url: $("#instalación_nueva").attr('action'),
        data:$("#instalación_nueva").serialize(), 
        dataType: 'json',
        success: function(response) {
          // Se actualiza la pestaña de periodo lectivo.
          $("#registrar_instalación").update_tab();
        }
      })
    }
  });

  $(document).on("click","#registro_instalaciones td a",function(event){
    $("#registro_instalaciones td a").closest("tr").removeClass("edit_equipamiento");
    $(this).closest("tr").addClass("edit_equipamiento");
    $("#datos_instalación").load($(this).attr("href"));
    $("#cerrar_instalación_edit").removeClass("oculto");
    $("#instalación_delete").closest("th").removeClass("oculto");
    $("#instalación_edit").closest("th").removeClass("oculto");
    $("#instalación_new").closest("th").addClass("oculto");
    $("#instalación_nombre").removeClass("invalid_placeholder");
    $("#instalación_nombre").val($(this).text());
    setTimeout(function(){ 
      $("#instalación_nombre").attr('placeholder','Inserte el nombre para actualizar la instalación');
    },300);
  });

  $(document).on("click","#cerrar_instalación_edit",function(event){
    $("#registro_instalaciones td a").closest("tr").removeClass("edit_equipamiento");
    $("#datos_instalación").load(Routing.generate('equipamiento_new'));

    $("#cerrar_instalación_edit").addClass("oculto");
    $("#instalación_delete").closest("th").addClass("oculto");
    $("#instalación_edit").closest("th").addClass("oculto");
    $("#instalación_new").closest("th").removeClass("oculto");
    $("#instalación_nombre").val("");
    $("#instalación_nombre").removeClass("invalid_placeholder");
    $("#instalación_nombre").attr('placeholder','Inserte el nombre para añadir nueva instalación');
    $("#datos_instalación").removeClass("oculto");
  });

  $(document).on("click","#instalación_edit",function(event){
    event.preventDefault();

    if($("#instalación_nombre").val()==""){
      $("#instalación_nombre").addClass("invalid_placeholder");
    }
    else{
      $.ajax({
        type: 'PUT',
        url: $("#instalación_editar").attr('action'),
        data:$("#instalación_editar").serialize(), 
        success: function(response) {
          // Se actualiza la pestaña de periodo lectivo.
          $("#registrar_instalación").update_tab();
        }
      })
    }
  });

  $(document).on("click","#instalación_delete",function(event){
    event.preventDefault();
    form= $(this).closest("th").prev().find("div[id='equipamiento_eliminar'] form");

    var arr= form.attr('action').split('/');
    if(confirm("Se va a eliminar la instalación del sistema.\n\n¿Estas seguro de eliminarla?\n\n")==true)
    {
      $.ajax({
        type: 'DELETE',
        url: Routing.generate("equipamiento_delete", {id:arr[5]}),
        data: form.serialize(),
        
        success: function() {
          // Se actualiza la pestaña de periodo lectivo.
          $("#registrar_instalación").update_tab();
        }
      })
    }
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
          // Se actualiza la pestaña de periodo lectivo.
          $("#registrar_equipamiento").update_tab();
        }
      })
    }
  });

$(document).on("click","#registro_equipamientos td a",function(event){
    $("#registro_equipamientos td a").closest("tr").removeClass("edit_equipamiento");
    $(this).closest("tr").addClass("edit_equipamiento");
    $("#datos_equipamiento").load($(this).attr("href"));
    $("#cerrar_equipamiento_edit").removeClass("oculto");
    $("#equipamiento_delete").closest("th").removeClass("oculto");
    $("#equipamiento_edit").closest("th").removeClass("oculto");
    $("#equipamiento_new").closest("th").addClass("oculto");
    $("#equipamiento_nombre").removeClass("invalid_placeholder");
    $("#equipamiento_nombre").val($(this).text());
    setTimeout(function(){ 
      $("#equipamiento_nombre").attr('placeholder','Inserte el nombre para actualizar el equipamiento');
    },300);
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
          // Se actualiza la pestaña de periodo lectivo.
          $("#registrar_equipamiento").update_tab();
        }
      })
    }
  });

  $(document).on("click","#equipamiento_delete",function(event){
    event.preventDefault();
    form= $(this).closest("th").prev().find("div[id='equipamiento_eliminar'] form");

    var arr= form.attr('action').split('/');
    if(confirm("Se va a eliminar el equipamiento del sistema.\n\n¿Estas seguro de eliminarlo?\n\n")==true)
    {
      $.ajax({
        type: 'DELETE',
        url: Routing.generate("equipamiento_delete", {id:arr[5]}),
        data: form.serialize(),
        
        success: function() {
          // Se actualiza la pestaña de periodo lectivo.
          $("#registrar_equipamiento").update_tab();
        }
      })
    }
      return false;
  });



  //////////////////////////////////
  //        Horario Clase         //
  //////////////////////////////////

  $(document).on("click","#registro_horario #button_generate",function(event){
    // Se vacia el contenedor para el nuevo horario.
    $("#contenedor_nuevo_horario tbody").empty();
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
      contenido+='<td>'+i+'ª';
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

  // Se ejecuta al pulsar una tecla en los input de horario escolar.
  $(document).on('keyup',"#contenedor_nuevo_horario input",function(e){
    // Se elimina el placeholder para que no aparezca al borrar el valor del input.
    $(this).attr('placeholder','');
    // Se ejecuta al introducir una hora completa en un input.Si se pulsa borrar se ignora.
    if($(this).val().trim().length==5 && e.keyCode != 8){
      $(this).attr("value",$(this).val());
      tab=parseInt($(this).attr("tabindex"));
      if($("#contenedor_nuevo_horario input[tabindex="+(tab+1)+"]").val()==""){
        $("#contenedor_nuevo_horario input[tabindex="+(tab+1)+"]").focus();
      }
      // Se valida en línea la hora introducida.
      val=$(this).val().split(':');
      if((val[0]<08 || val[0]>19) || (val[1]<00 || val[1]>59)){
        $(this).addClass("invalid");
        $(this).closest("tr").find("td>span").removeClass("oculto");
        $("#registro_horario #aviso_error").removeClass("oculto");
      }
      else{
        $(this).removeClass("invalid");
        $("#contenedor_nuevo_horario input").each (function(){
          if($(this).hasClass("invalid")){
            $(this).closest("tr").find("td>span").removeClass("oculto");
            return false;
          }
          $(this).closest("tr").find("td>span").addClass("oculto");
        });

        $("#contenedor_nuevo_horario input").each (function(){
          if($(this).hasClass("invalid")){
            $("#registro_horario #aviso_error").removeClass("oculto");
            return false;
          }
          $("#registro_horario #aviso_error").addClass("oculto");
        });

        $("#contenedor_nuevo_horario input").each (function(){
          if($(this).val()=="" || $(this).hasClass("invalid")){
            $("#button_horario_save").prop("disabled", true);  
            return false;
          }
          $("#button_horario_save").prop("disabled", false);  
        });
      }
      $("#contenedor_nuevo_horario input").each (function(){
        if($(this).val()!=""){
          $("#button_horario_clear").prop("disabled", false);  
          return false;
        }
        $("#button_horario_clear").prop("disabled", true);
      });

      validateTime($(this));
      if(!$(this).hasClass("invalid")){

        tab_prev=parseInt($(this).attr("tabindex"))-1;
        if($("#contenedor_nuevo_horario input[tabindex="+tab_prev+"]").val()!=""){
          validateTime($("#contenedor_nuevo_horario input[tabindex="+tab_prev+"]"));
        }

        tab_next=parseInt($(this).attr("tabindex"))+1;
        if($("#contenedor_nuevo_horario input[tabindex="+tab_next+"]").val()!=""){
          validateTime($("#contenedor_nuevo_horario input[tabindex="+tab_next+"]"));
        }
      }
    }
    if($(this).val().trim().length==0){
      $(this).removeClass("invalid");

      $("#contenedor_nuevo_horario input").each (function(){
        if($(this).hasClass("invalid")){
          $(this).closest("tr").find("td>span").removeClass("oculto");
          return false;
        }
        $(this).closest("tr").find("td>span").addClass("oculto");
      });

      $("#contenedor_nuevo_horario input").each (function(){
        if($(this).hasClass("invalid")){
          $("#registro_horario #aviso_error").removeClass("oculto");
          return false;
        }
          $("#registro_horario #aviso_error").addClass("oculto");
      });

      $("#contenedor_nuevo_horario input").each (function(){
        if($(this).val()!=""){
          $("#button_horario_clear").prop("disabled", false);  
          return false;
        }
        $("#button_horario_clear").prop("disabled", true);
      });
    }

  });
  // Función para validar las horas del horario Escolar.
  function validateTime(tab) {
    // Se valida los input impares, excepto el primero.
    if($(tab).attr("tabindex")!=1 && $(tab).attr("tabindex")%2==1){
      h_act=$(tab).val().split(':');

      tab_prev=parseInt($(tab).attr("tabindex"))-1;
      h_prev=$("#contenedor_nuevo_horario input[tabindex="+tab_prev+"]").val().split(':');

      tab_next=parseInt($(tab).attr("tabindex"))+1;
      h_next=$("#contenedor_nuevo_horario input[tabindex="+tab_next+"]").val().split(':');
      
      if((h_act[0]<h_prev[0] && h_prev[0]!="")||(h_act[0]>h_next[0] && h_next[0]!="")||((h_act[0]==h_next[0]) && (h_act[1]>=h_next[1]) && h_next[1]!="") || ((h_act[0]==h_prev[0]) &&  (h_act[1]<h_prev[1]) && (h_prev[1]!=""))){
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
      h_next=$("#contenedor_nuevo_horario input[tabindex="+tab_next+"]").val().split(':');
        
      if((h_act[0]>h_next[0] && h_next[0]!="" )||((h_act[0]==h_next[0]) && (h_act[1]>=h_next[1])&& h_next[1]!="") ){
        $(tab).addClass("invalid");
      }
      else{
        $(tab).removeClass("invalid");  
      }
    }
    // Se valida los input pares, excepto el último.
    if($(tab).attr("tabindex")!=$("#contenedor_nuevo_horario input:last ").attr("tabindex") && $(tab).attr("tabindex")%2==0){
      h_act=$(tab).val().split(':');

      tab_prev=parseInt($(tab).attr("tabindex"))-1;
      h_prev=$("#contenedor_nuevo_horario input[tabindex="+tab_prev+"]").val().split(':');

      tab_next=parseInt($(tab).attr("tabindex"))+1;
      h_next=$("#contenedor_nuevo_horario input[tabindex="+tab_next+"]").val().split(':');
        //||(h_act[0]>h_next[0] && h_next[0]!="")||((h_act[0]==h_next[0]) && (h_act[1]>h_next[1]) && h_next[1]!="")
      if((h_act[0]<h_prev[0] && h_prev[0]!="") ||((h_act[0]==h_prev[0]) && (h_act[1]<=h_prev[1]) && h_prev[1]!="")){
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
      h_prev=$("#contenedor_nuevo_horario input[tabindex="+tab_prev+"]").val().split(':');

      if((h_act[0]<h_prev[0] && h_prev[0]!="") ||((h_act[0]==h_prev[0]) && (h_act[1]<=h_prev[1]) && h_prev[1]!="")){
        $(tab).addClass("invalid");
      }
      else{
        $(tab).removeClass("invalid");
      }
    }
    // Se comprueba todos los valores de los input para añadir o quitar los avisos de error.
    $("#contenedor_nuevo_horario input").each (function(){
      if($(this).hasClass("invalid")){
        $(this).closest("tr").find("td>span").removeClass("oculto");
        return false;
      }
      $(this).closest("tr").find("td>span").addClass("oculto");
    });

    $("#contenedor_nuevo_horario input").each (function(){
      if($(this).hasClass("invalid")){
        $("#registro_horario #aviso_error").removeClass("oculto");
        return false;
      }
      $("#registro_horario #aviso_error").addClass("oculto");
    });

    $("#contenedor_nuevo_horario input").each (function(){
      if($(this).val()=="" || $(this).hasClass("invalid")){
        $("#button_horario_save").prop("disabled", true);  
        return false;
      }
      $("#button_horario_save").prop("disabled", false);  
    });
  }

  // Se obliga a introducir valores entre 08:00 -18:00 para no validar.
  $(document).on('keydown',"#contenedor_nuevo_horario input",function(e){
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
        $(this).mask('AB:CB',
        {'translation': {A: {pattern: /[0-1]/}, B: {pattern: /[0-9]/}, C: {pattern: /[0-5]/}},
        placeholder: "__:__"});
      }
    }
  });

  // Se oculta el placeholder en el input seleccionado.
  $(document).on("focus","#contenedor_nuevo_horario input",function(event){
    $(this).data('placeholder',$(this).attr('placeholder')).attr('placeholder','');
  });

  // Se deja el valor en blanco en caso de no introducir una hora completa.
  $(document).on("blur","#contenedor_nuevo_horario input",function(event){
    $(this).attr('placeholder',$(this).data('placeholder'));
    if($(this).val().length<5){
      $(this).val("");
    }
    if($(this).val()==""){
      $("#contenedor_nuevo_horario input").each (function(){
        if($(this).val()==""){
          $("#button_horario_save").prop("disabled", true);  
          return false;
        }
        $("#button_horario_save").prop("disabled", false);  
      });
    }

    /*if($(this).val()==""){
      $(this).addClass("invalid");
      $("#registro_horario #aviso_error").removeClass("oculto");
      $(this).closest("tr").find("td>span").removeClass("oculto");
      $("#button_horario_save").prop("disabled", true);  
    }
    else{
      $("#button_horario_clear").prop("disabled", false);
    }
    */
  });

  // Se añade ":" tras introducir dos cifras.
  $(document).on('keyup',"input[type='time']",function(e){
    if($(this).val().trim().length==2 && e.keyCode != 8){
      $(this).val($(this).val()+":");
    }
  });

  $(document).on("click","#registro_horario #button_rest",function(event){
    // Se restablece el valor inicial de cada select y desactivamos el botón.
    $("#registro_horario .block_insert select").prop('selectedIndex',0);
    $("#button_rest").prop("disabled", true);
    $("#button_generate").prop("disabled", true);
  });

  $(document).on('change',"#registro_horario .block_insert select",function(event){
    $("#button_rest").prop("disabled", false);
    // Se desactiva la primera opción del select.
    $(this).find("option:eq('0')").prop("disabled", true);
    // Se comprueba que todos los select activos tienen un valor para activar el botón de generar nuevo horario.
    $("#registro_horario .block_insert").find("select:enabled").each (function(){
      if($(this).val()==0){
        $("#button_generate").prop("disabled", true);  
        return false;
      }
      $("#button_generate").prop("disabled", false);  
    });
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
    //Se restablece el valor inicial de cada input.
    $("#contenedor_registro_horario input").each (function(){
      $(this).val($(this).attr("value"));
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























//alert($('#tabs ul li:eq(0)').outerWidth(true));


(function($) {
    $.fn.hasScrollBar = function() {
        return this.get(0).scrollHeight > this.height();
    }
})(jQuery);

  });