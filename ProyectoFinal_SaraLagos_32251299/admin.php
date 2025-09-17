<?php
require __DIR__ . '/includes/funciones.php';
$omitidos = ['fecha_hora_creacion', 'fecha_hora_modificacion'];


// Obtener la tabla desde GET
$tabla = $_GET['tabla'] ?? '';

// Validar que la tabla exista
$tablasPermitidas = obtener_tablas();
if(!in_array($tabla, $tablasPermitidas)){
    die("Tabla no permitida");
}

// Conectar a la base de datos
require './config/database.php';

// Consulta para obtener todos los registros
$sql = "SELECT * FROM `$tabla`";
$resultado = mysqli_query($db, $sql);
if(!$resultado){
    die("Error en la consulta: " . mysqli_error($db));
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo ucfirst($tabla); ?></title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles-admin.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script>
var tablaActual = "<?php echo $tabla; ?>";

$(document).ready(function(){
	// Activate tooltip
	$('[data-toggle="tooltip"]').tooltip();
	
	// seleccionar checkboxes
	var checkbox = $('table tbody input[type="checkbox"]');
	$("#selectAll").click(function(){
		if(this.checked){
			checkbox.each(function(){
				this.checked = true;                        
			});
		} else{
			checkbox.each(function(){
				this.checked = false;                        
			});
		} 
	});
	checkbox.click(function(){
		if(!this.checked){
			$("#selectAll").prop("checked", false);
		}
	});
$(document).ready(function(){

    // ----------------------------
    // AGREGAR
    // ----------------------------
    $('#addProductModal form').on('submit', function(e){
        e.preventDefault(); // Evita el envío normal
        enviarDatos(tablaActual, 1, $('#addProductModal')); // 1 = agregar
    });

    // ----------------------------
    // ELIMINAR
    // ----------------------------
    // Al abrir el modal, se pasa el id del registro a eliminar
$('#deleteProductModal').on('show.bs.modal', function(e){
    var button = $(e.relatedTarget); 
    var id = button.data('prod-id'); 

    // Buscamos el input hidden dentro del modal y le ponemos el valor
    $(this).find('input[type="hidden"]').val(id); 

    console.log("Modal abrir: ID a borrar =", id);
});






    // Cuando se confirma la eliminación
    $('#deleteProductModal form').on('submit', function(e){
        e.preventDefault();
        enviarDatos(tablaActual, 3, $('#deleteProductModal')); // 3 = eliminar
    });

    // ----------------------------
    // EDITAR
    // ----------------------------
    // Al hacer click en editar, llenamos el modal dinámicamente
$('.edit').on('click', function(){
    var row = $(this).closest('tr');
    var modal = $('#editProductModal');

    // Llenar todos los inputs visibles
    row.find('td').each(function(){
        var name = $(this).data('name');
        if(name){
            modal.find('[name="'+name+'"]').val($(this).text().trim());
        }
    });

    // Llenar el input hidden del PK tomando la primera columna (la que tiene data-name)
    var pk_td = row.find('td').first(); // asumimos que la primera columna es PK
    var pk_value = pk_td.text().trim();
    var pk_name = pk_td.data('name');
    modal.find('input[name="'+pk_name+'"]').val(pk_value);

    modal.modal('show');
});

    // Cuando se envía el modal de editar
    $('#editProductModal form').on('submit', function(e){
        e.preventDefault();
        enviarDatos(tablaActual, 2, $('#editProductModal')); // 2 = editar
    });

});

});


</script>
    </head>
    <body class="sb-nav-fixed">
        <div class="container mt-4">
    <h1>Tabla: <?php echo ucfirst($tabla); ?></h1>
    <a href="index.php" class="btn btn-primary mb-3">Volver</a>
    <a href="#addProductModal" class="btn btn-success mb-3" data-toggle="modal">Nuevo Registro</a>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Registros de <?php echo ucfirst($tabla); ?>
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
<thead>
    <tr>
        <?php
 
$primerFila = mysqli_fetch_assoc($resultado);
$columnas = [];
$clavePrimaria = '';

if ($primerFila) {
    $keys = array_keys($primerFila);
    $clavePrimaria = $keys[0]; // PK
    foreach ($keys as $col) {
        if ($col !== $clavePrimaria) {
            $columnas[] = $col; // llenar columnas solo una vez
        }
    }
}

// Imprimir encabezado
echo "<thead><tr>";
echo "<th>" . ucfirst($clavePrimaria) . "</th>";
foreach ($columnas as $col) {
    echo "<th>" . ucfirst($col) . "</th>";
}
echo "<th>Acciones</th>";
echo "</tr></thead>";
        ?>
    </tr>
</thead>

<tbody>
<?php
// Primera fila
if ($primerFila) {
    echo "<tr>";
    echo "<td data-name='{$clavePrimaria}'>" . htmlspecialchars($primerFila[$clavePrimaria]) . "</td>";
    foreach ($columnas as $col) {
        echo "<td data-name='{$col}'>" . htmlspecialchars($primerFila[$col]) . "</td>";
    }
    echo "<td>
        <a href='#editProductModal' class='edit btn btn-sm btn-info' data-prod-id='{$primerFila[$clavePrimaria]}'>Editar</a>
    </td>";
    echo "</tr>";
}


// Resto de filas
while ($fila = mysqli_fetch_assoc($resultado)) {
    echo "<tr>";
    echo "<td data-name='{$clavePrimaria}'>" . htmlspecialchars($fila[$clavePrimaria]) . "</td>";
    foreach ($columnas as $col) {
        echo "<td data-name='{$col}'>" . htmlspecialchars($fila[$col]) . "</td>";
    }
    echo "<td>
        <a href='#editProductModal' class='edit btn btn-sm btn-info' data-prod-id='{$fila[$clavePrimaria]}'>Editar</a>
    </td>";
    echo "</tr>";
}
?>
</tbody>


            </table>
        </div>
    </div>
</div>
<!-- Add Modal HTML -->
<div id="addProductModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formAdd">
        <div class="modal-header">
          <h4 class="modal-title">Agregar <?php echo ucfirst($tabla); ?></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <?php foreach ($columnas as $col): ?>
            <?php if (!in_array($col, $omitidos)): ?>
            <div class="form-group">
              <label><?php echo ucfirst(str_replace("_", " ", $col)); ?></label>
              <input type="text" name="<?php echo $col; ?>" class="form-control" required>
            </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="type" value="1">
          <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
          <input type="submit" class="btn btn-success" value="Agregar">
        </div>
      </form>
    </div>
  </div>
</div>
<div id="editProductModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEdit">
        <div class="modal-header">
          <h4 class="modal-title">Editar <?php echo ucfirst($tabla); ?></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="<?php echo $clavePrimaria; ?>" id="edit_<?php echo $clavePrimaria; ?>">

          <?php foreach ($columnas as $col): ?>
            <?php if (!in_array($col, $omitidos)): ?>
            <div class="form-group">
              <label><?php echo ucfirst(str_replace("_", " ", $col)); ?></label>
              <input type="text" name="<?php echo $col; ?>" id="edit_<?php echo $col; ?>" class="form-control" required>
            </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="type" value="2">
          <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
          <input type="submit" class="btn btn-info" value="Guardar">
        </div>
      </form>
    </div>
  </div>
</div>

</main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Todos los derechos reservados. Sara Lagos 32251299 & Lissy Garcia 32311172</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="js/ajax.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
