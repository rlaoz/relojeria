<?php
    require __DIR__ . '/includes/funciones.php';

    if (isset($_GET['idProd'])) {
        $idProducto = $_GET['idProd'];
        $producto = mostrar_productos($idProducto);
    }
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
    <script src="js/script.js" defer></script>
    <title>Éphémère</title>
</head>
<body onload="leeElemento();">

<!--Nav/Header-->
<nav class="custom-navbar navbar navbar-expand-md" aria-label="nav-bar">

<div class="container">
    <a class="navbar-brand" href="index.php">Éphémère<span>.</span></a>

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
<?php if($producto = mysqli_fetch_assoc($producto)){ ?>
        <!-- Nombre del producto -->
        <h1 id="Nombre"><?php echo $producto['descripcion']; ?></h1>
        <div class="producto-detalle">
            <!-- Imagen del producto -->
            <img id="ImagenProducto" class="producto-img" src="img/<?php echo $producto['id']; ?>.jpg" alt="Imagen del producto">
            <div class="producto-contenido">
                <h2 id="Precio">$<?php echo $producto['precio']; ?></h2>
                <h3 id="CanProd"></h3>
                <p id="Desc"><?php echo $producto['caracteristicas']; ?></p>
                <input id="CantElemento" class="campo" type="number" placeholder="cantidad" min="1">
                <input type=button class ="prodButton" value ="AGREGAR" onclick="addElemento();">
            </div>
        </div>
    </main>
<?php } ?>
<!--Fin productos-->
