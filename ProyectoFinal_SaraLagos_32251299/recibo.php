<?php
session_start();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Invitado';

// desde las cookies
function obtenerDetallesCarrito() {
    $productos = [];
    if (isset($_COOKIE['Pedido']) && !empty($_COOKIE['Pedido'])) {
        $orden = $_COOKIE['Pedido'];
        $items = explode('|', $orden);

        foreach ($items as $item) {
            $detalles = explode(',', $item);
            $nombre = str_replace('Producto:', '', $detalles[0]);
            $precio = str_replace('Precio:', '', $detalles[1]);
            $cantidad = str_replace('Cantidad:', '', $detalles[2]);
            $subtotal = str_replace('Subtotal:', '', $detalles[3]);

            $productos[] = [
                'nombre' => trim($nombre),
                'precio' => floatval($precio),
                'cantidad' => intval($cantidad),
                'subtotal' => floatval($subtotal)
            ];
        }
    }
    return $productos;
}


$Carrito = obtenerDetallesCarrito();
$total = array_sum(array_column($Carrito, 'subtotal'));

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


<div class="container mt-5">
    <h1 class="mb-4">Recibo de Compra</h1>
    
    <h3>Nombre de Usuario: <?php echo ($username); ?></h3>
    
    <h4>Productos Comprados:</h4>
    <ul class="list-group mb-4">
        <?php foreach ($Carrito as $producto): ?>
            <li class="list-group-item">
                <?php echo ($producto['nombre']); ?> - Cantidad: <?php echo $producto['cantidad']; ?> - Subtotal: $<?php echo $producto['subtotal'], 2 ?>
            </li>
        <?php endforeach; ?>
    </ul>
    
    <h4>Total: $<?php echo $total,2 ?></h4>

    <a href="index.php" class="btn btn-primary mt-4" >Volver a la Página Principal</a>
</div>

</body>
</html>