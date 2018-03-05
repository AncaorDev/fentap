
$(document).ready(function(){
	var time = $("#tduracion").data('time');
	// console.log(time);
	if (typeof(time) === "undefined") {
		time = 'Idefinido';
	} else {
		if (time == (60*60*24)) {
		document.getElementById('tduracion').innerHTML = "24 horas";
		} else {
			var timeSet = setInterval(timeRegresiva,1000);
			function timeRegresiva(){
			    if (time < 0) {
					clearInterval(timeSet);
					alert("Inactividad , Por favor vuelve a iniciar la sesión") 
					logout();
				} else {
					var minutes = Math.floor( time / 60 );
					var seconds = time % 60;
					//Anteponiendo un 0 a los minutos si son menos de 10 
					minutes = minutes < 10 ? '0' + minutes : minutes;		 
					//Anteponiendo un 0 a los segundos si son menos de 10 
					seconds = seconds < 10 ? '0' + seconds : seconds;			 
					var result = minutes + ":" + seconds; 
					document.getElementById('tduracion').innerHTML = result;
				        time--;
				} 
			};	
		}
	}

	$("#logout").click(function(){
		logout();
	});
	$("#ingresar,#limpiar").click(function(){
		$("#ajaxlogin").html("");
	});
	$("#formlogin").submit(function(){
	user = $.trim($("#user").val());
	pass = $.trim($("#pass").val());
	sesion = document.getElementById("sesion").checked ? 1 : 0;
	datos = {"user" : user, "pass" : pass ,"sesion" : sesion};
		if ((user != '' || user.length != 0) && (pass.length != 0 || pass != '')) {
			$("#ajaxlogin").html("<p><i class='fa fa-spinner fa-spin'></i> Logueando...</p>");
			setTimeout(function(){ajaxlogin(datos)},2000);	
		} else {
			if ((user == '' || user == 0) && (pass == 0 || pass == '')) {
				$("#user").animate({ marginLeft: "554px"},30, function(){
					$("#user").animate({ marginLeft: "0px" },30,function(){
						$("#user").animate({ marginLeft: "554px" },30,function(){
							$("#user").animate({ marginLeft: "0px" },30);
						});
					});
				});
				$("#pass").animate({ marginLeft: "-554px" },30, function(){
					$("#pass").animate({ marginLeft: "0px" },30,function(){
						$("#pass").animate({ marginLeft: "-554px" },30,function(){
							$("#pass").animate({ marginLeft: "0px" },30);
						});
					});
				});	
			}else if (user == '' || user == 0) {
				$("#user").animate({ marginLeft: "554px" },30, function(){
					$("#user").animate({ marginLeft: "0px" },30,function(){
						$("#user").animate({ marginLeft: "554px" },30,function(){
							$("#user").animate({ marginLeft: "0px" },30);
						});
					});
				});
			} else if (pass == '' || pass == 0){
				$("#pass").animate({ marginLeft: "4px" },100, function(){
					$("#pass").animate({ marginLeft: "0px" },100,function(){
						$("#pass").animate({ marginLeft: "4px" },100,function(){
							$("#pass").animate({ marginLeft: "0px" },100);
						});
					});
				});
			}
		}
		return false;
	});
});


function logout() {
	$.ajax({
		url      :'panel/logout',
		type     :'POST',
		dataType : 'json',
		success  : function (data) {
			console.log(data);
			if (data.error == 0) {
				var URLactual = window.location.href;
				window.location=URLactual;
			}
		}
	}).fail(function(e) {
		console.log(e);
	});
}

function ajaxlogin(datos){
$.ajax({
	url      :'panel/login',
	type     :'POST',
	dataType : 'json',
	data     : datos,
	success: function(data){
		console.log(data);
		if (data.error == 0) {
			if (data.url == "login") {
				$("#ajaxlogin").html(data.msj);
				var time = 3;
				var timeSet = setInterval(timeRegresiva,1000);
				function timeRegresiva(){
				    if (time < 0) {
				        clearInterval(timeSet);   
				    } else {
				        document.getElementById('redirectl').innerHTML = time;
				        time--;
				    } 
				};		
				setTimeout(function(){ 
					var URLactual = window.location.href;
					window.location=URLactual;},3000);
			} else {
				var URLactual = window.location.href;
				window.location=URLactual;
			}
		} else {
			console.log('asdasdsa');
			$("#ajaxlogin").html(data.msj);
		}
	}
}).fail(function(e) {
	console.log(e);
});
}

