function __(id){
	return document.getElementById(id);
}
function url(componente){
	if (componente == "completa") {
		var componente = window.location.href;
		return componente;
	} else if (componente == "dominio") {
		var componente = window.location.host;
		return componente;
	} else if (componente == "pathname") {
		var componente = window.location.pathname;
		return componente;
	}	
} 
var https = window.location.protocol; 
var URL = url("completa");
var DOM = url("dominio");
var PATHNAME  = url("pathname");
var folder = PATHNAME.split('/');
if(/localhost/.test(DOM)) {
	var DOMINIO = https + "//" + DOM + "/" + folder[1] + "/";
} else {
	var DOMINIO = https + "//" + DOM + "/";
}