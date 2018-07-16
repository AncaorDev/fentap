var check = 0;
var modified = false;

$("input[type='date'],input[type='mail'],input[type='text'],textarea").keyup(function() {
    var mail      = $('#mail').val();
    var telefono  = $('#telefax').val();
    var direccion = $('#direccion').val();
    var valid     = true;
    if(mail.length == 0 || telefono.length == 0 || direccion.length == 0) valid = false;
    submitForm(valid)
    modified = true;
});

$("input[type='text']").focus(function() {
    $(this).keyup(function() {
        if ($(this).val().length >= 2) {
            var valid     = true;
            if(mail.length == 0 || telefono.length == 0 || direccion.length == 0) valid = false;
            submitForm(valid)
        }
    })
})

function submitForm(bool = true) {
    // validatorResult = validator.checkAll($('#form_gen1'));
    if (bool) {
        $("#btnenviar1").removeAttr("disabled")
    } else {
        $("#btnenviar1").attr("disabled", "true")
    }
}

$('#form_gen1').submit(function(e) {
    e.preventDefault();
    // evaluate the form using generic validaing
    validatorResult = validator.checkAll(this);
    //Verificación de los datos del form
    if (!validatorResult) {
        $("#btnenviar1").removeAttr("disabled");
    } else {
        $("#btnenviar1").removeAttr("disabled")
        if (modified) {
           saveData();
        }
    }
});

function saveData() {
    console.log
    var data = new FormData();
    data.append('mail'        , $('#mail').val());
    data.append('telefax'     , $('#telefax').val());
    data.append('direccion'   , $('#direccion').val());
    $.ajax({
        url: 'panel/general/saveGeneral',
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