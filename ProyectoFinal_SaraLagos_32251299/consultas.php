<?php
require __DIR__ . '/includes/funciones.php';
require './config/database.php';

// Obtener la consulta seleccionada
$consulta = $_GET['consulta'] ?? '';
$param = $_GET['param'] ?? ''; // parámetro opcional (fecha, id acompañante, ciudad)

$resultado = null;
$mensaje = '';

switch($consulta){
    case '1': // Platos por acompañante
        $sql = "SELECT a.nombre AS acompaniante, COUNT(ap.cod_plato) AS total_platos
                FROM acompaniante a
                JOIN acompaniante_plato ap ON a.cod_acompaniante = ap.cod_acompaniante
                WHERE a.cod_acompaniante = ?
                GROUP BY a.cod_acompaniante";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $param);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $mensaje = "Platos para el acompañante ID $param";
        break;

    case '2': // Platos por fecha de menú
        $sql = "SELECT p.nombre AS plato
                FROM platos p
                JOIN menu_plato mp ON p.cod_plato = mp.cod_plato
                JOIN menu m ON mp.cod_menu = m.cod_menu
                WHERE m.fecha_elaboracion = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, 's', $param);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $mensaje = "Platos del menú de la fecha $param";
        break;

    case '3': // Platos y acompañantes por menú
        $sql = "SELECT m.cod_menu, m.fecha_elaboracion, p.nombre AS plato, 
                       GROUP_CONCAT(a.nombre SEPARATOR ', ') AS acompanantes
                FROM menu m
                JOIN menu_plato mp ON m.cod_menu = mp.cod_menu
                JOIN platos p ON mp.cod_plato = p.cod_plato
                LEFT JOIN acompaniante_plato ap ON p.cod_plato = ap.cod_plato
                LEFT JOIN acompaniante a ON ap.cod_acompaniante = a.cod_acompaniante
                GROUP BY m.cod_menu, p.cod_plato";
        $resultado = mysqli_query($db, $sql);
        $mensaje = "Platos y acompañantes de cada menú";
        break;

    case '4': // Cantidad de platos por menú
        $sql = "SELECT m.cod_menu, m.fecha_elaboracion, COUNT(mp.cod_plato) AS total_platos
                FROM menu m
                JOIN menu_plato mp ON m.cod_menu = mp.cod_menu
                GROUP BY m.cod_menu";
        $resultado = mysqli_query($db, $sql);
        $mensaje = "Cantidad de platos por menú";
        break;

    case '5': // Proveedores y cantidad de productos
        $sql = "SELECT pr.nombre AS proveedor, COUNT(p.cod_producto) AS total_productos
                FROM proveedores pr
                LEFT JOIN producto_proveedor pp ON pr.cod_proveedor = pp.cod_proveedor
                LEFT JOIN productos p ON pp.cod_producto = p.cod_producto
                GROUP BY pr.cod_proveedor";
        $resultado = mysqli_query($db, $sql);
        $mensaje = "Proveedores y cantidad de productos";
        break;

    case '6': // Productos de proveedores por ciudad
        $sql = "SELECT pr.nombre AS proveedor, p.nombre_producto AS producto
                FROM proveedores pr
                JOIN producto_proveedor pp ON pr.cod_proveedor = pp.cod_proveedor
                JOIN productos p ON pp.cod_producto = p.cod_producto
                WHERE pr.ciudad_actual = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, 's', $param);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $mensaje = "Productos de proveedores en la ciudad $param";
        break;

    default:
        $mensaje = "Seleccione una consulta para mostrar resultados";
        break;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consultas Especiales</title>
<link rel="stylesheet" href="css/styles.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Navbar -->
<nav class="custom-navbar navbar navbar-expand-md">
    <div class="container">
        <a class="navbar-brand" href="index.php">Manjares de Honduras<span>.</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbars">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
            <li class="nav-item active"><a class="nav-link" href="index.php">Tablas</a></li>
            <li><a class="nav-link" href="consultas.php">Consultas</a></li>

        </ul>


        <p id="idElemento" class="Elemento"></p>
    </div>
    </div>
</nav>

<main class="container my-4 flex-fill">
        <!-- Botón de regreso -->
    <div class="mb-3">
        <a href="index.php" class="btn btn-secondary">&larr; Regresar a la página principal</a>
    </div>

    <h1>Consultas Especiales</h1>

    <!-- Formulario de selección -->
    <form method="GET" class="mb-3 row g-2 align-items-center">
        <div class="col-auto">
            <label>Seleccione consulta:</label>
            <select name="consulta" id="consulta" class="form-select" onchange="mostrarInputParam()">
                <option value="">--Seleccione--</option>
                <option value="1" <?= ($consulta=='1')?'selected':'' ?>>Platos por acompañante</option>
                <option value="2" <?= ($consulta=='2')?'selected':'' ?>>Platos por fecha de menú</option>
                <option value="3" <?= ($consulta=='3')?'selected':'' ?>>Platos y acompañantes por menú</option>
                <option value="4" <?= ($consulta=='4')?'selected':'' ?>>Cantidad de platos por menú</option>
                <option value="5" <?= ($consulta=='5')?'selected':'' ?>>Proveedores y cantidad de productos</option>
                <option value="6" <?= ($consulta=='6')?'selected':'' ?>>Productos de proveedores por ciudad</option>
            </select>
        </div>
        <div class="col-auto">
            <input type="text" name="param" id="param" placeholder="Ingrese parámetro" value="<?= htmlspecialchars($param) ?>" class="form-control" style="display:none;">
        </div>
        <div class="col-auto">
            <input type="submit" value="Buscar" class="btn btn-primary">
        </div>
    </form>

    <h4><?= $mensaje ?></h4>

    <!-- Tabla de resultados -->
    <?php if($resultado && mysqli_num_rows($resultado) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <?php
                $firstRow = mysqli_fetch_assoc($resultado);
                if($firstRow){
                    echo "<th>" . implode("</th><th>", array_keys($firstRow)) . "</th></tr></thead><tbody>";
                    echo "<tr><td>" . implode("</td><td>", array_map('htmlspecialchars', $firstRow)) . "</td></tr>";
                    while($row = mysqli_fetch_assoc($resultado)){
                        echo "<tr><td>" . implode("</td><td>", array_map('htmlspecialchars', $row)) . "</td></tr>";
                    }
                    echo "</tbody>";
                }
                ?>
        </table>
    <?php else: ?>
        <p>No hay resultados.</p>
    <?php endif; ?>
</main>

<!-- Footer -->
<footer class="footer-section bg-blue text-light py-4 mt-auto">
    <div class="container text-center">
        <p>Copyright &copy; 2025. Todos los derechos reservados. Sara Lagos 32251299 & Lissy Garcia 32311172</p>
    </div>
</footer>

<script>
function mostrarInputParam(){
    let consulta = document.getElementById('consulta').value;
    let input = document.getElementById('param');
    if(consulta=='1' || consulta=='2' || consulta=='6'){
        input.style.display='inline-block';
    } else {
        input.style.display='none';
    }
}
mostrarInputParam();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
