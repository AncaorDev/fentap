{% extends "base.twig" %}
{% block title %} Inicio {% endblock %}
{% block head %}{{parent()}}
{% endblock %}
{% block content %}

<main>
    <h2 class="title-services pt-4 blue-text">Nuestros Servicios</h2>
 <!--Main layout-->
<div class="container">
	<!--Primera División-->
    <div class="row pt-4">
        <!--Card-1-->
        <div class="col-lg-4">
            <!--Card-->
            <div class="card wow fadeIn" data-wow-delay="0.4s">
                <!--Card image-->
                <img class="img-fluid" src="{{R_IMAGE}}segeta/constructora.jpg" alt="Constructora">
                <!--Card content-->
                <div class="card-body">
                    <!--Title-->
                    <h4 class="card-title text-center">Constructora</h4>
                    <hr>
                    <!--Text-->
                    <p class="card-text">Servicios de calidad con respecto a la contrucción.</p>
                    <a href="#" class="blue-text mt-0 d-flex flex-row-reverse">
                        <h5 class="waves-effect p-2">Leer Mas</h5>
                    </a>
                </div>

            </div>
            <!--/.Card-->
        </div>
        <!--First columnn-->

        <!--Second columnn-->
        <div class="col-lg-4">
            <!--Card-->
            <div class="card wow fadeIn" data-wow-delay="0.6s">
                <!--Card image-->
                <img class="img-fluid" src="{{R_IMAGE}}segeta/redes.jpg" alt="Telecomunicaciones">
                <!--Card content-->
                <div class="card-body">
                    <!--Title-->
                    <h4 class="card-title text-center">Telecomunicaciones</h4>
                    <hr>
                    <!--Text-->
                    <p class="card-text">Servicios de calidad con respecto a las telecomincaciones.</p>
                    <a href="#" class="blue-text mt-0 d-flex flex-row-reverse">
                        <h5 class="waves-effect p-2">Leer Mas</h5>
                    </a>
                </div>
            </div>
            <!--/.Card-->
        </div>
        <!--Second columnn-->

        <!--Third columnn-->
        <div class="col-lg-4">
            <!--Card-->
            <div class="card wow fadeIn" data-wow-delay="0.8s">
            	<!--Card image-->
 				<img class="img-fluid" src="{{R_IMAGE}}segeta/seguridad.jpg" alt="Telecomunicaciones">
                <!--Card content-->
                <div class="card-body">
                    <!--Title-->
                    <h4 class="card-title text-center">Seguridad Electr&oacute;nica</h4>
                    <hr>
                   <!--Text-->
                    <p class="card-text">Servicios de calidad con respecto a la seguridad electr&oacute;nica.</p>
                    <a href="#" class="blue-text mt-0 d-flex flex-row-reverse">
                        <h5 class="waves-effect p-2">Leer Mas</h5>
                    </a>
                </div>

            </div>
            <!--/.Card-->
        </div>
        <!--Third columnn-->
    </div>
    <!--/.Second row-->
</div>
<!--/.Main layout-->
</main>
{% endblock %}
