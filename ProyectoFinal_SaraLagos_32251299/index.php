<?php
     require __DIR__ . '/includes/funciones.php';
    $tablas = obtener_tablas();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="96x96" href="img/favicon-96x96.png">
    <link rel="preload" href="css/styles.css" as="style">
    <link href="css/styles.css" rel="stylesheet">
    <link rel="preload" href="css/normalize.css" as="style">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
    <title>Éphémère</title>
</head>
<body onload="leeElemento()">

<!--Nav/Header-->
<nav class="custom-navbar navbar navbar-expand-md" aria-label="nav-bar">

<div class="container">
    <a class="navbar-brand" href="index.php">Manjares de Honduras<span>.</span></a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbars">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
            <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
            <li><a class="nav-link" href="index.php#overview-section">Overview</a></li>
            <li><a class="nav-link" href="index.php#about-section">About us</a></li>
            <li><a class="nav-link" href="#contact-section">Contact us</a></li>
            <li><a class="nav-link" href="products.php">Products</a></li>
            <li><a class="nav-link" href="#test-section">Testimonials</a></li>
        </ul>

        <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
            <li><a class="nav-link" href="/auth/login.php"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#5c715e" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg></a></li>
            <li><a class="nav-link" href="/auth/login.php"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#5c715e" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17h-11v-14h-2" /><path d="M6 5l14 1l-1 7h-13" /></svg></a></li>
        </ul>
        <p id="idElemento" class="Elemento"></p>
    </div>
</div>  
</nav>
<!-- Fin nav/header-->

<!--Inicio productos-->
<main class="contenedor">
  <h1>Tablas Disponibles</h1>
  <div class="grid">
    <?php foreach($tablas as $tabla) { ?>
      <div class="productos">
        <a href="admin.php?tabla=<?php echo $tabla; ?>">
          <img class="producto-imagen" src="img/<?php echo $tabla; ?>.png" alt="Imagen <?php echo $tabla; ?>">
          <div class="producto-informacion">
            <p class="producto-nombre"><?php echo ucfirst($tabla); ?></p>
          </div>
        </a>
      </div>
    <?php } ?>
  </div>
</main>
<!--Fin productos-->

<!-- Inicio Footer -->
<footer class="footer-section" id="contact-section">
    <div class="container relative">
      <div class="row g-5 mb-5">
        <!-- Logo y descripción -->
        <div class="col-lg-4">
          <div class="mb-4 footer-logo-wrap">
            <a href="#" class="footer-logo">Éphémère<span>.</span></a>
          </div>
          <p class="mb-4">
            Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique.
          </p>
          <ul class="custom-social">
            <li><a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-facebook" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f3eae6" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
            </svg></a></li>
            <li><a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-twitter" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f3eae6" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c0 -.249 1.51 -2.772 1.818 -4.013z" />
            </svg></a></li>
            <li><a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-instagram" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f3eae6" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
              <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
              <path d="M16.5 7.5l0 .01" />
            </svg></a></li>
            <li><a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-youtube" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f3eae6" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M2 8a4 4 0 0 1 4 -4h12a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-12a4 4 0 0 1 -4 -4v-8z" />
              <path d="M10 9l5 3l-5 3z" />
            </svg></a></li>
          </ul>
        </div>
  
        <!-- Información de contacto -->
        <div class="col-lg-4">
          <h5 class="mb-4">Contáctanos</h5>
          <p class="mb-2"><span class="fa fa-envelope"></span> info@tuempresa.com</p>
          <p class="mb-2"><span class="fa fa-phone"></span> +123 456 7890</p>
          <p class="mb-2"><span class="fa fa-map-marker"></span> Calle 123, Ciudad, País</p>
        </div>
  
        <!-- Enlaces del sitio -->
        <div class="col-lg-4">
          <h5 class="mb-4">Enlaces rápidos</h5>
          <ul class="list-unstyled links-wrap">
            <li><a href="#about-section">About us</a></li>
            <li><a href="products.html">Products</a></li>
          </ul>
        </div>
      </div>
  
      <div class="border-top copyright">
        <div class="row pt-4">
          <div class="col-lg-6 text-center text-lg-start">
            <p class="mb-2"> Copyright 2024 Éphémère. Todos los derechos reservados Sara Lagos 32251299.</p>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- Fin Footer -->
</body>
</html>