<?php

    session_start();

    require './includes/funciones.php';
      
    $usuario = $_SESSION['username'];
    $numTarjeta = '';
    $fechaExp = '';
    $nombre = '';
    $cvv = '';

    if($_SERVER['REQUEST_METHOD'] === 'POST') { // Ta bueno

       //llamar la funcion para crear el producto
        $usuario = $_SESSION['username'];
        $numTarjeta = $_POST['NumeroTarjeta'];
        $fechaExp = $_POST['FechaExp'];
        $nombre = $_POST['NombreTarjeta'];
        $cvv = $_POST['cvv'];

        $Crear = AgregarTarjeta($usuario, $numTarjeta, $fechaExp, $nombre,$cvv);

       
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js" defer></script>
    <title>Éphémère</title>
</head>
<body onload="mostrarOrden();">

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
            <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <?php if($auth): ?>
                            <li><a class="dropdown-item" href="/auth/cerrar-sesion.php">Logout</a></li>
                        <?php else: ?>
                        <li><a class="dropdown-item" href="/auth/cerrar-sesion.php">Logout</a></li>           
                         <?php endif; ?>   
                    </ul>
                </li>
        </ul>
        <p id="idElemento" class="Elemento"></p>
    </div>
</div>  
</nav>
<!-- Fin nav/header-->

<!--Tabla-->
<main class="Tabla">
            <table id="Orden">
                <thead><!--Titulo columnas-->
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                    <tbody>
                    </tbody>
            </table>
</main>
<!--Fin tabla-->

 <!--Tarjeta-->
 <div class="contenedor">
    <div class="row justify-content-center">
        <div class="col-lg-8"> <!-- Ajustar el ancho de la tarjeta -->
        <form class="formularioC" method="POST" action="/carrito.php">    
            <div class="contenedor-carrito">
                <div class="col-lg-12">
                    <div class="row px-2">
                        <div class="form-group col-md-6">
                        <input type="hidden" name="Username" value="<?php echo $_SESSION['username']; ?>">
                            <label class="form-control-label">Nombre en la tarjeta</label>
                            <input type="text" id="cname" name="NombreTarjeta" placeholder="Nombre Apellido" class="form-control" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-control-label">Numero de tarjeta</label>
                            <input type="text" id="cnum" name="NumeroTarjeta" placeholder="1111 2222 3333 4444" class="form-control" value="<?php echo $numTarjeta; ?>">
                        </div>
                    </div>
                    <div class="row px-2">
                        <div class="form-group col-md-6">
                            <label class="form-control-label">Fecha de expiracion</label>
                            <input type="text" id="exp" name="FechaExp" placeholder="MM/YYYY" class="form-control" value="<?php echo $fechaExp; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-control-label">CVV</label>
                            <input type="text" id="cvv" name="cvv" placeholder="***" class="form-control" value="<?php echo $cvv; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 mt-2">
                    <button type="submit" class="btn btn-primary btn-block tabButton" onclick="event.preventDefault(); window.location.href='recibo.php';">
                        <span id="checkout">Comprar</span>
                    </button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
</body>
</html>