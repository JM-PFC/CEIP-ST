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
  $(document).on("keyup",'input[type="text"]', function(){
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
        $("#cursos_dialog").dialog('close');
        limpiarForm(form);
        
        div=$("#registro_cursos").closest("div[id^='tabs-']");
        $(div).empty();
        $(div).load(Routing.generate('curso'));
        $("#tabs #lista_cursos").empty();
        $("#tabs #lista_cursos").load(Routing.generate('alumno_listaCursos'));
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
        }
      })
      return false;
  });


  $(document).on("click","#curso_delete button",function(event){
    event.preventDefault();
    form= $(this).closest("form");
    var arr= $('#curso_delete').attr('action').split('/');
    
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
        }
      })
    }
      return false;
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
        //Actualizamoslas listas de asignaturas de las demás pestañas.
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





  });