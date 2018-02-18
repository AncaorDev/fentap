$("#change_phrase").click(function(){
  //AJAX//
  var datos = {mode : "obtener" , data : "frase"}; 
  $.ajax({
    url:'ajax.php?mode=inicio',
    type:'POST',
    dataType: 'json',
    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
    data: datos,
    complete: function(xhr, statusText) {
      console.log(xhr.responseText);
    },
    success: function(response){
    // SUCCESS
    // $("#text-frase").html("<img src='./images/svg/loading.svg'>")
    $("#text-frase").addClass("animate-right")
    $("#text-frase").html(response.frase)
    setTimeout(function(){
      $("#text-frase").removeClass("animate-right")
    },600)
    // FIN - SUCCESS
    },
    error: function (xhr, ajaxOptions, thrownError) {
        console.log(xhr.status);
        console.log(thrownError);
        console.log(xhr.responseText);
    }
  }).fail(function(e) {
    console.log(e);
  });
  //FIN - AJAX//

})

