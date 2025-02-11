<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/pawpoint-pointer-teal.png') }}" type="image/png">

    <title>Pawpoint</title>

    <!-- Fonts -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/1.1.1/typed.min.js"></script>
</head>


<body>
<main class="d-flex flex-column">
<div class="whole-header">
  

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg p-4 fixed-top">
  <div class="container-fluid d-flex align-items-center">
    <a class="navbar-brand" href="#"><img src="{{ asset('images/pawpointblack.png') }}" alt="" width="165" height="auto"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#inicio">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#nosotros">Nosotros</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#servicios">Servicios</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#filosofia">Filosofía</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#cita">Cita</a>
        </li>
      </ul>
      <form class="d-flex">
        <a href="#" class="button-75"><span class="text">Acceder</span></a>
      </form>
    </div>
  </div>
</nav>
<!-- NAVBAR ENDS -->

 <!-- LANDING  -->

 <header class="second-section flex-grow-1 d-flex flex-column justify-content-center mb-5" id="inicio">
            <div class="container px-4">
                <div class="row gx-4 align-items-center">
                    <div class="col-xxl-6">
                        <div class="d-flex justify-content-center mt-xxl-0">       
                                <img src="{{ asset('images/cathi.webp') }}" alt="Hero" width=auto height="750">
                        </div>
                    </div>

                    <div class="col-xxl-6">
                        <div class="text-center text-xxl-start">
                            <div class="p-0 fw-bold text-center">
                                <div class="text-uppercase fs-6 mb-3">Quiere - Cuida - Confía</div>
                            </div>
                            <div class="fs-3 fw-light text-center">El mejor cuidado para tus mascotas</div>
                            <h1 class="display-4 fw-bolder mb-4 text-center">
                                <span class="d-inline">Somos <span class="resalta">tu clínica</span> veterinaria</span>
                            </h1>
                            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-center mb-3">
                                <a href="#" class="button-75" role="button"><span class="text">Iniciar sesión</span></a>
                                <a href="#" class="button-75" role="button"><span class="text">Registrarse</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        </div> <!-- whole-header -->
  
        <section class="hero" id="nosotros">
        <div class="px-4 py-3 my-2 mx-1 text-center">
    <img class="d-block mx-auto mb-0" mt-3 src="{{ asset('images/pawpoint-pointer-teal.png') }}" alt="" width="62" height="auto">
    <h1 class="display-5 fw-bold text-body-emphasis">Nosotros</h1>
    <div class="col-lg-6 mx-auto">
      <p class="lead mb-4">Si has llegado hasta aquí, es porque tal vez estabas buscando una clinica veterinaria. ¡Bienvenido! estas en las mejores manos. Somos una clinica especializada en perros y gatos y llevamos mas de 10 años ofreciendo calidad y cuidado para tus masscotas, porque sabemos que son un miembro mas de la familia.</p>
      </div>
    </div>
        </section>

        <section class="servicios" id="servicios">
        <div class="container px-4 py-1 text-center" id="featured-3">
    <h2 class="pb-2 border-bottom">Nuestros Servicios</h2>
    <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">

      <div class="feature col">
        <div class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-1 p-2 mb-3 rounded">
          <i class="fa-solid fa-syringe" width="1em" height="1em"></i><use xlink:href="#collection"/></svg>
        </div>
        <h3 class="fs-2 text-body-emphasis">Vacunación</h3>
        <p>
            Mantén a tu mascota protegida de enfermedades con nuestro servicio de vacunación. 
            Consulta nuestro calendario de vacunación y mantén a tu mascota sana y feliz.
        </p>
     
      </div>
      <div class="feature col">
      <div class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-1 p-2 mb-3 rounded">
      <i class="fa-solid fa-user-doctor"></i><use xlink:href="#people-circle"/></svg>
        </div>
        <h3 class="fs-2 text-body-emphasis">Cirugías</h3>
        <p>Quirofanos especializados con la última tecnología y cirujanos experimentados para que tu mascota esté en las mejores manos</p>
      </div>
      <div class="feature col">
      <div class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-1 p-2 mb-3 rounded">
      <i class="fa-solid fa-hospital"></i><use xlink:href="#toggles2"/></svg>
        </div>
        <h3 class="fs-2 text-body-emphasis">Hospitalización</h3>
        <p>
            En caso de que tu mascota necesite hospitalización, contamos con instalaciones y un equipo de profesionales 
            que se encargarán de cuidar de ella y de mantenerla en las mejores condiciones posibles.
        </p>
       
      </div>

      <div class="feature col">
      <div class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-1 p-2 mb-3 rounded">
      <i class="fa-solid fa-pump-medical"></i><use xlink:href="#toggles2"/></svg>
        </div>
        <h3 class="fs-2 text-body-emphasis">Dermatología</h3>
        <p>
            Si tu mascota tiene problemas de piel, en PawPoint contamos con un equipo de dermatólogos 
            que se encargarán de diagnosticar y tratar cualquier problema dermatológico que pueda tener.
        </p>
    
      </div>

      <div class="feature col">
      <div class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-1 p-2 mb-3 rounded">
      <i class="fa-solid fa-tooth"></i><use xlink:href="#toggles2"/></svg>
        </div>
        <h3 class="fs-2 text-body-emphasis">Odontología</h3>
        <p>
            La salud dental de tu mascota es muy importante. En PawPoint contamos con un equipo de 
            odontólogos que se encargarán de mantener la boca de tu mascota sana y libre de problemas.
        </p>
      
      </div>

      <div class="feature col">
      <div class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-1 p-2 mb-3 rounded">
      <i class="fa-solid fa-microchip"></i><use xlink:href="#toggles2"/></svg>
        </div>
        <h3 class="fs-2 text-body-emphasis">Pasaporte/Microchip</h3>
        <p>
            Si vas a viajar con tu mascota, es importante que tenga su pasaporte y microchip en regla. 
            En PawPoint nos encargamos de todo para que puedas viajar con tu mascota y esta este identificada.
        </p>
       
      </div>
    </div>
  </div>

  <section class="filosofia" id="filosofia">
    <!-- Columna de Texto -->
    <div class="columna-izquierda col-sm-12 col-md-6">
        <h2>Filosofía</h2>
        <hr>
        <p class="text-filosofia">
            Estamos muy concienciados con cuestiones como <strong>el abandono, las colonias felinas descontroladas</strong> y los problemas que sufren <strong>los animales más desfavorecidos</strong>, que por desgracia no tienen una familia o no pueden asumir los costes de su salud. <br><br>
            Es por eso que <strong>colaboramos con protectoras activamente</strong>, ofreciéndoles hasta un 40% de descuento en tratamientos. Además, si vienes con tu mascota adoptada, recibirás siempre un 10% de descuento en todos los tratamientos. <br><br>
            También realizamos campañas de esterilización y trabajamos por el control de las colonias felinas. <br><strong>Porque adoptar es dar una nueva vida.</strong>
        </p>
    </div>

    <!-- Columna con la imagen -->
    <div class="columna-derecha col-md-6 d-flex justify-content-center">
        <img src="{{ asset('images/enfocar3.png') }}" alt="Veterinaria con perro" class="filosofia-img">
    </div>
</section> <!--filosofia-->

        <section class="cita container py-5 pt-0 mt-5" id="cita"> <!-- seccion citas-->
  <div class="row align-items-center">
    <!-- Columna izquierda: Imagen -->
    <div class="col-md-6 text-center">
      <img src="{{ asset('images/cita.png') }}" alt="un perro feliz con una corona un hueso y un comedero dibujados" class="img-fluid">
    </div>

    <!-- Columna derecha: Título y texto -->
    <div class="flex-md-row col-md-6 cita-col-2">
      <h2 class="mb-3">Reserva tu cita</h2>
      <p>
        En PawPoint, ponemos a tu disposición un sistema fácil y rápido para reservar citas. 
        Elige el día y la hora que más te convengan y nosotros nos encargamos del resto, porque tu mascota merece la mejor atención.
        Si lo prefieres, también <strong>puedes llamarnos al 666 66 66 66</strong> o enviarnos un email a <a href="mailto:hello@pawpoint.com">hello@pawpoint.com</a>.
      </p>
      <div class="button-contenedor-cita"><a href="#" class="button-cita button-75"><span class="text">Reservar cita</span></a> </div>
    </div>
  </div>

        </section> <!-- seccion cita -->

        <footer class="third-section mt-auto d-flex align-items-end justify-content-center text-center">
            <div class="px-5">
                <div class="row flex-column flex-sm-row mb-2">
                        <div class="mt-auto p-2 text-center footer-text">© 2025 Pawpoint, todos los derechos reservados.</div>
                    </div>
                </div>
            </div>
        </footer> 

        </main>

<!-- Bootstrap JS -->
 <script src="{{ asset('js/custom.js') }} "></script>
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</body>



</html>