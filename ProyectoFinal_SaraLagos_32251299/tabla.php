<?php
// Incluir tus funciones
require __DIR__ . '/includes/funciones.php';

// Obtener la tabla desde GET
$tabla = $_GET['tabla'] ?? '';

// Validar que la tabla exista
$tablasPermitidas = obtener_tablas();
if(!in_array($tabla, $tablasPermitidas)){
    die("Tabla no permitida");
}

// Conectar a la base de datos
require './config/database.php';

// Consulta para obtener todos los registros activos
$sql = "SELECT * FROM `$tabla` ";
$resultado = mysqli_query($db, $sql);

if(!$resultado){
    die("Error en la consulta: " . mysqli_error($db));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla <?php echo ucfirst($tabla); ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1>Tabla: <?php echo ucfirst($tabla); ?></h1>
    <a href="index.php" class="btn btn-primary mb-3">Volver</a>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <?php
                // Mostrar encabezados según los campos de la primera fila
                $primerFila = mysqli_fetch_assoc($resultado);
                if($primerFila){
                    foreach(array_keys($primerFila) as $columna){
                        echo "<th>" . ucfirst($columna) . "</th>";
                    }
                    echo "</tr></thead><tbody>";
                    // Mostrar la primera fila
                    echo "<tr>";
                    foreach($primerFila as $valor){
                        echo "<td>" . htmlspecialchars($valor) . "</td>";
                    }
                    echo "</tr>";
                }
                // Mostrar el resto de las filas
                while($fila = mysqli_fetch_assoc($resultado)){
                    echo "<tr>";
                    foreach($fila as $valor){
                        echo "<td>" . htmlspecialchars($valor) . "</td>";
                    }
                    echo "</tr>";
                }
                ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
// Cerrar conexión
mysqli_close($db);
?>
