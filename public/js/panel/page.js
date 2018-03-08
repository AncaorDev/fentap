var modified = false;
$("input[type='date'],input[type='mail'],input[type='text'],textarea").change(function () {   
  submitForm()
  modified = true; 
});
$("input[type='text']").focus(function(){
	$(this).keydown(function(){
		if ($(this).val().length >= 2) {
			submitForm()
		}
	})
})
function submitForm(bool = true){
	if (bool) {
		$("#btnenviar").removeAttr("disabled")
	} else {
		$("#btnenviar").attr("disabled" ,"true")
	}
}
/**
 * Funcion que se encargar de buscar la accion en la URL
 * @deprecated  
 * @method showAction
 * @return accion en la url
 */
function showAction(){
	// Declaramos la variable en la que guardaremos nuestra acción
	var action = ""
	// Usamos el metodo match para buscar la accion dentro de la URL
	if (URL.match("edit")) {
		action = "actualizar"
	} else if (URL.match("delete")) {
		action = "eliminar"
	} else {
		action = "registrar"
	}
	// Retornamos la acción
	return action;
}


$(document).ready(function(){
	// Revisamos si hay cambios en la opcion attribute page
	$("#id_AttributePage").change(function(){
		// Obtenemos el valor
		$IdAtrPage = $(this).val();	
		// Verficamos que valor hemos obtenido
		if ($IdAtrPage == 1) {
			// función que muestra el item agrega tecto y coloca placeholder (texto , placeholder)
			showInputSlug("Identificador de Sección: " , "Ingrese un identificador de sección")
		} else if ($IdAtrPage == 2) {
			showInputSlug("Enlace: " , "Ingrese dirección o enlace")
		} else {
			$("#div_slug_page").hide()
		}		
	})
})

$('#form_page').submit(function(e) {  
    e.preventDefault();
    var submit = false;
    // evaluate the form using generic validaing
    validatorResult = validator.checkAll(this);
    //Verificación de los datos del form
    submitForm(false);
    if (!validatorResult) {
      submit = false;
      
    } else {
    	if (modified) {
			var form = $(this).serializeArray(); 
			var object = arrayInObject(form);
			var accion = showAction();
			var accion_form = $('#action').val();
			if (accion_form == accion) {
				if (accion == 'actualizar') {
					savePage(form);
				} else {
					newPage(form);
				}
			}
    	}
    }
    if (submit)
      this.submit();
      return false;
});

function showInputSlug(text , placeholder = ""){
	$("#div_slug_page").show()
	$("#text_slug").text(text);
	$("#slug_Page").attr("placeholder" , placeholder);
}

function newPage(data){
	$.ajax({
		url : 'panel/pages/newPage',
		type : 'POST',
		contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType : 'json',
		// cache : false,
    	// processData : true,
		data : data,
		success : function(data){
			console.log(data);
			if (data.error == 0) {
				alert('Acción realizada con éxito');
				setTimeout(function(){ 
					var URLactual = window.location.href;
					window.location=URLactual;
				},1000);
			} else {
				alert('ERROR');
			}
		}
	}).fail(function(e){
		console.log(e);
	})
}

function savePage(data) {
	$.ajax({
		url      : 'panel/pages/savePage',
		type     : 'POST',
		dataType : 'json',
		data     : data,
		success  : function(data){
			console.log(data);
			if (data.error == 0) {
				alert('Acción realizada con éxito');
				setTimeout(function(){ 
					var URLactual = window.location.href;
					window.location=URLactual;
				},1000);
			} else {
				alert('ERROR');
			}
		}
	}).fail(function(e){
		console.log(e)
	});
}

function confirmDeletePage(tag){
	id_page = tag.parent().data('id_page');
	$.confirm({
	    title: 'Mensaje!',
	    content: '¿Esta seguro que desea eliminar?',
	    type: 'dark',
		typeAnimated: true,
		icon: 'fa fa-spinner fa-spin',
		closeIcon: true,
		closeIconClass: 'fa fa-close',
		theme: 'supervan',
	    buttons: {
	        aceptar: {
	        	btnClass: 'btn-blue',
	        	action : function(){
	       			deletePage(id_page);
	        	}
	        },
	        cancelar: {
	            btnClass: 'btn-red',
	        	action : function(){
	       			
	        	}
	        }
	    }
	});	
}

function deletePage(id_Page){
	$.ajax({
		url      : 'panel/pages/deletePage',
		type     : 'POST',
		dataType : 'json',
		data     :  {'id_Page' : id_Page},
		success  : function(data){
			if (data.error == 0) {
				alert('Acción realizada con éxito');
				setTimeout(function(){ 
					var URLactual = window.location.href;
					window.location=URLactual;
				},1000);
			} else {
				alert('ERROR');
			}
		}
	}).fail(function(e){
		console.log(e)
	});
}


$(document).ready(function(){
  $('.summernote').on('summernote.focus', function() {
    $("#btnGuardarCambios").removeClass("disabled");
    $("#msg").html("");
    // $( "#btnenlace" ).attr( "href", "" );
  });

$('.summernote').summernote({
  lang: 'es-ES',
  codemirror: { // codemirror options
    theme: 'monokai'
  },
  callbacks: {
    onImageUpload: function(files) {
    for(var i = 0; i < files.length; i++){
            SubirImagen(files[i]);
    }     
    }
  },
   popover: {
            image: [
                ['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
                ['float', ['floatLeft', 'floatRight', 'floatNone']],
                ['custom', ['imageAttributes', 'imageShape']],
                ['remove', ['removeMedia']]
            ],
        },
});


 
$("#btnGuardarCambios").click(function(){
	var guardar = true;
	if ($(this).hasClass('disabled')) {
        var guardar = false;
	} 
    $(this).addClass("disabled");
    var editPage = $('#editPage').val();
    var html = $('.summernote').summernote('code');  
    if (guardar) { 
       guardarHtml(html,editPage);
    } 
  });
});

// Powered https://github.com/summernote/summernote/issues/72
function SubirImagen(file) {
  if(file.type.includes('image')) {
      var name = file.name.split(".");
      name = name[0]; 
      var data = new FormData();
      data.append('file', file);
      $.ajax({
        url: 'panel/pages/subirImagen',
        type: 'POST',
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        data: data,
        success: function (data){
          	if(data.error == 0){
          		console.log(data.url);
            	$('.summernote').summernote('insertImage', data.url , function ($image) {
				  	$image.css('width', $image.width() / 3);
				  	$image.attr('data-filename', name);
				});
			} else {
				console.log(data.error);
			}
        }
      })
      .fail(function(e) {
        console.log(e);
      });
    }
    else {
      alert("El tipo de archivo que intentaste subir no es una imagen");
    }
}


function guardarHtml(html,editPage) {
   	$.ajax({
        url      : 'panel/pages/guardarHtml',
        type     : 'POST',
        dataType : 'json',
        data     : {"html_Page" : html,
        			"id_Page"   : editPage},
        success  : function (data){
			if(data.error == 0) {
				$("#msg").html(data.msj);
			} else{
				console.log(data.error);
			}
        }
  	}).fail(function(e) {
    	console.log(e);
  	});
}

$("#btnincauto").click(function(){
	event.preventDefault();
	$.confirm({
    title: 'Mensaje!',
    content: 'El AUTO_INCREMENT se asignara autmaticamente',
    type: 'dark',
	typeAnimated: true,
	icon: 'fa fa-spinner fa-spin',
	closeIcon: true,
	closeIconClass: 'fa fa-close',
	theme: 'supervan',
    buttons: {
        aceptar: {
        	btnClass: 'btn-blue',
        	action : function(){
       			resetAI();
        	}
        },
        cancelar: {
            btnClass: 'btn-red',
        	action : function(){
       			
        	}
        }
    }
	});	
});

function resetAI(num = 0) {
   	$.ajax({
        url      : 'panel/pages/resetAutoIncrement',
        type     : 'POST',
        dataType : 'json',
        data     : {'num' : num},
        success  : function (data){
			if(data.error == 0) {
				alert('Acción realizada con éxito');
				setTimeout(function(){ 
					var URLactual = window.location.href;
					window.location=URLactual;
				},1000);
			} else{
				console.log(data.error);
			}
        }
  	}).fail(function(e) {
    	console.log(e);
  	});
}