var permisos = [];

function selectPermiso(tag) {
	id = tag.data('id_permiso');
	var index 	= permisos.indexOf(id);
	if (index<0) {
		permisos.push(id);
	} else {
	    permisos.splice(index, 1);
	}
}

var modified = false;
$("input[type='date'],input[type='mail'],input[type='text'],textarea").change(function () {
  submitForm()
  modified = true;
});
$("input[type='text']").focus(function(){
	$(this).bind('keyup', function() {
		var act = false;
		if ($(this).val().length >= 2) {
			act = true;
		}
		submitForm(act)
	})
})
function submitForm(bool = true){
	if (bool) {
		$("#btnenviar").removeAttr("disabled")
	} else {
		$("#btnenviar").attr("disabled" ,"true")
	}
}

$('#form_user').submit(function(e) {
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
        	crearUsuario(form);
    	}
    }
    if (submit)
      this.submit();
      return false;
});

function arrayInObject(array){
	var object = {}
	for (var i = 0; i < array.length; i++) {
		object[array[i]['name']] = array[i]['value']
	}
	return object
}

function crearUsuario(datos) {
	datos.push({'name' : 'permisos' , 'value' : permisos});
	$.ajax({
		url      :'panel/users/newUser',
		type     :'POST',
		dataType : 'json',
		data     : datos,
		success: function(data){
			if (data.error == 0) {
				alert('Acción realizada con éxito');
				setTimeout(function(){
					var URLactual = window.location.href;
					window.location=URLactual;
				},1000);
			}
		}
	}).fail(function(e) {
		console.log(e);
	});
}

function getPermisos() {

}

function confirmDeleteUsuario(tag) {
	var id_user = tag.parent().data('id_user');
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
                action: function() {
                    deleteUsuario(id_user);
                }
            },
            cancelar: {
                btnClass: 'btn-red',
                action: function() {

                }
            }
        }
    });
}

function deleteUsuario(id_user) {
	$.ajax({
		url      :'panel/users/deleteUser',
		type     :'POST',
		dataType : 'json',
		data     : {'id_user' : id_user},
		success: function(data){
			if (data.error == 0) {
				alert('Acción realizada con éxito');
				setTimeout(function(){
					var URLactual = window.location.href;
					window.location=URLactual;
				},1000);
			}
		}
	}).fail(function(e) {
		console.log(e);
	});
}

function getPermisos(tag) {
	var id_user = tag.parent().data('id_user');
	$.ajax({
		url      :'panel/users/getPermisosbyIdUser',
		type     :'POST',
		dataType : 'json',
		data     : {'id_user' : id_user},
		success: function(data){
			if (data.error == 0) {
				$("#content-permisos").html(data.html);
				modal('modalPermisos');
			}
		}
	}).fail(function(e) {
		console.log(e);
	});
}

$("#btnincauto").click(function() {
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
                action: function() {
                    resetAI();
                }
            },
            cancelar: {
                btnClass: 'btn-red',
                action: function() {

                }
            }
        }
    });
});

function resetAI(num = 0) {
    $.ajax({
        url: 'panel/users/resetAutoIncrement',
        type: 'POST',
        dataType: 'json',
        data: {
            'num': num
        },
        success: function(data) {
            if (data.error == 0) {
                alert('Acción realizada con éxito');
                setTimeout(function() {
                    var URLactual = window.location.href;
                    window.location = URLactual;
                }, 1000);
            } else {
                console.log(data.error);
            }
        }
    }).fail(function(e) {
        console.log(e);
    });
}

