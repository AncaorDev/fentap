var check           = 0;
var modified        = false;
var id_departamento = null;

$('#id_departamento').on('change' , function(){
    id_departamento = $(this).val();
    $.ajax({
    url: 'panel/mapa/verificarDepartamento',
    type: 'POST',
    dataType: 'json',
    data: {'id_departamento' : id_departamento },
    success: function(data) {
        if (data.error == 0) {
            if (data.exists == 1) {
                editMapa(data.url);
            }  
            id_departamento  = id_departamento;         
        } else {
            alert('ERROR');
        }
    }
    })
    .fail(function(e) {
        console.log(e);
    });
})
$("input[type='date'],input[type='mail'],input[type='text'],textarea,select").change(function() {
    submitForm();
    modified = true;
});

$("input[type='text']").focus(function() {
    $(this).keydown(function() {
        if ($(this).val().length >= 2) {
            submitForm()
        }
    })
})

function submitForm(bool = true) {
    validatorResult = validator.checkAll($('#form_mapa'));
    if (validatorResult) {
        $("#btnenviar").removeAttr("disabled")
    } else {
        $("#btnenviar").attr("disabled", "true")
    }
}

function showAction() {
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


$('#form_mapa').submit(function(e) {
    e.preventDefault();
    // evaluate the form using generic validaing
    validatorResult = validator.checkAll(this);
    //Verificación de los datos del form
    if (!validatorResult) {
        $("#btnenviar").removeAttr("disabled");
    } else {
        $("#btnenviar").removeAttr("disabled")
        if (modified) {
            var html = $('.summernote').summernote('code');
            var accion = showAction();
            var accion_form = $('#action').val();
            if (accion_form == accion) {
                if (accion == 'actualizar') {
                    saveMapa(html);
                } else {
                    newMapa(html);
                }
            }
        }
    }
});

function newMapa(html) {
    var data = new FormData();
    data.append('name_User', $('#name_User').val());
    data.append('id_departamento', id_departamento);
    data.append('html_mapa', html);
    $.ajax({
        url: 'panel/mapa/newMapa',
        type: 'POST',
        dataType: 'json',
        contentType: false,
        // cache: false,
        processData: false,
        data: data,
        success: function(data) {
            console.log(data);
            if (data.error == 0) {
                alert('Acción realizada con éxito');
                setTimeout(function() {
                    var URLactual = window.location.href;
                    window.location = URLactual;
                }, 1000);
            } else {
                alert('ERROR');
            }
        }
    })
    .fail(function(e) {
        console.log(e);
    });
}

function saveBoletin(file, html) {
    var data = new FormData();
    console.log(file);
    if (file !== undefined) {
        if (!file.type.includes('image')) {
            alert("El tipo de archivo que intentaste subir no es una imagen");
            return;
        }
        var name = file.name.split(".");
        name = name[0];
        data.append('file', file);
    }
    data.append('name_User', $('#name_User').val());
    data.append('title_boletin', $('#title_boletin').val());
    data.append('descrip_boletin', $('#descrip_boletin').val());
    data.append('id_User', $('#id_User').val());
    data.append('id_boletin', $('#id_boletin').val());
    data.append('flg_destacado', check);
    data.append('html_boletin', html);
    console.log(data);
    $.ajax({
        url: 'panel/boletin/saveBoletin',
        type: 'POST',
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        data: data,
        success: function(data) {
            console.log(data);
            if (data.error == 0) {
                alert('Acción realizada con éxito');
                setTimeout(function() {
                    var URLactual = window.location.href;
                    window.location = URLactual;
                }, 1000);
            } else {
                alert('ERROR');
            }
        }
    }).fail(function(e) {
        console.log(e)
    });
}

function confirmDeleteBoletin(tag) {
    id_boletin = tag.parent().data('id_boletin');
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
                    deleteBoletin(id_boletin);
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

function editMapa(url){
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
                    window.location = url;
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

function deleteBoletin(id_boletin) {
    $.ajax({
        url: 'panel/boletin/deleteBoletin',
        type: 'POST',
        dataType: 'json',
        data: {
            'id_boletin': id_boletin
        },
        success: function(data) {
            if (data.error == 0) {
                alert('Acción realizada con éxito');
                setTimeout(function() {
                    var URLactual = window.location.href;
                    window.location = URLactual;
                }, 1000);
            } else {
                alert('ERROR');
            }
        }
    }).fail(function(e) {
        console.log(e)
    });
}

$(document).ready(function() {
    $('.summernote').on('summernote.focus', function() {
        $("#btnGuardarCambios").removeClass("disabled");
        $("#msg").html("");
        // $( "#btnenlace" ).attr( "href", "" );
    });

    $('.summernote').summernote({
        disableDragAndDrop: true,
        fontNamesIgnoreCheck: ['Bree Serif', 'Baloo Chettan'],
        fontNames: ['Verdana', 'Ubuntu', 'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Baloo Chettan', 'Asap', 'Bree Serif'],
        lang: 'es-ES',
        codemirror: { // codemirror options
            theme: 'monokai'
        },
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['fontname', ['fontname']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'hr','video']],
            ['view', ['fullscreen', 'codeview']],
            ['color', ['color']],
            ['help', ['help']]
        ],
        minHeight: 300,
        callbacks: {
            onImageUpload: function(files) {
                for (var i = 0; i < files.length; i++) {
                    SubirImagen(files[i]);
                }
            }
        },
        codemirror: { // codemirror options
        theme: 'blackboard',
        mode: "text/html",
        extraKeys: {"Ctrl-Space": "autocomplete"},
        lineWrapping: true, // Tam
        lineNumbers: true,
        textWrapping: true,
        tabMode: 'indent',
        // firstLineNumber: 'lineNum',
        styleActiveLine: true,
        indentwrap     : 'renderLine',
        matchBrackets: true
      },
        popover: {
            image: [
                ['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
                ['float', ['floatLeft', 'floatRight', 'floatNone']],
                ['custom', ['imageAttributes', 'imageShape']],
                ['remove', ['removeMedia']]
            ],
            link: [
                ['link', ['linkDialogShow', 'unlink']]
            ],
            air: [
                ['color', ['color']],
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']]
            ]
        },
    });
    $("#btnGuardarCambios").click(function() {
        var guardar = true;
        if ($(this).hasClass('disabled')) {
            var guardar = false;
        }
        $(this).addClass("disabled");
        var editPage = $('#editPage').val();
        var html = $('.summernote').summernote('code');
        if (guardar) {
            guardarHtml(html, editPage);
        }
    });
});
// Powered https://github.com/summernote/summernote/issues/72
function SubirImagen(file) {
    if (file.type.includes('image')) {
        var name = file.name.split(".");
        name = name[0];
        var data = new FormData();
        console.log(file);
        data.append('file', file);
        console.log(data);
        $.ajax({
                url: 'panel/pages/subirImagen',
                type: 'POST',
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                data: data,
                success: function(data) {
                    if (data.error == 0) {
                        console.log(data.url);
                        $('.summernote').summernote('insertImage', data.url, function($image) {
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
    } else {
        alert("El tipo de archivo que intentaste subir no es una imagen");
    }
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
        url: 'panel/boletin/resetAutoIncrement',
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

// summernote.keyup
$('.summernote').on('summernote.keyup', function(we, e) {
    // console.log('Key is released:', e.keyCode);
    modified = true;
    submitForm(true);
});
