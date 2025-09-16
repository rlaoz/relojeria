<?php
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
        <title>CRUD <?php echo ucfirst($tabla); ?></title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles-admin.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
      <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
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
        var button = $(e.relatedTarget); // Botón que abrió el modal
        var id = button.data('prod-id');
        $(this).find('#id_d').val(id);
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
    var row = $(this).closest('tr'); // fila completa
    var modal = $('#editProductModal');

    // Recorrer cada celda y asignar al input correspondiente
    row.find('td').each(function(){
        var name = $(this).data('name');
        if(name){
            name = name.toLowerCase(); // asegurar coincidencia
            modal.find('[name="'+name+'"]').val($(this).text().trim());
        }
    });

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
    <h1>CRUD: <?php echo ucfirst($tabla); ?></h1>
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
        // Sacar una fila para conocer las columnas
        $primerFila = mysqli_fetch_assoc($resultado);
        $columnas = [];
        $clavePrimaria = '';

        if ($primerFila) {
            $keys = array_keys($primerFila);
            $clavePrimaria = $keys[0]; // Asumimos PK en la primera columna

            echo "<th>" . ucfirst($clavePrimaria) . "</th>";
            foreach ($keys as $col) {
                if ($col !== $clavePrimaria) {
                    echo "<th>" . ucfirst($col) . "</th>";
                    $columnas[] = $col;
                }
            }
            echo "<th>Acciones</th>";
        }

        // Regresar el puntero al inicio para no perder la primera fila
        mysqli_data_seek($resultado, 0);
        ?>
    </tr>
</thead>

<tbody>
<?php
while ($fila = mysqli_fetch_assoc($resultado)) {
    echo "<tr>";
    // PK
    echo "<td data-name='".strtolower($clavePrimaria)."'>" . htmlspecialchars($fila[$clavePrimaria]) . "</td>";
    // Otras columnas
    foreach ($columnas as $col) {
        echo "<td data-name='".strtolower($col)."'>" . htmlspecialchars($fila[$col]) . "</td>";
    }
    echo "<td>
        <a href='#editProductModal' class='edit btn btn-sm btn-info' data-prod-id='{$fila[$clavePrimaria]}'>Editar</a>
        <a href='#deleteProductModal' class='delete btn btn-sm btn-danger' data-prod-id='{$fila[$clavePrimaria]}'>Borrar</a>
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
            <div class="form-group">
              <label><?php echo ucfirst(str_replace("_", " ", $col)); ?></label>
              <input type="text" name="<?php echo $col; ?>" class="form-control" required>
            </div>
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

<!-- Edit Modal HTML -->
<div id="editProductModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEdit">
        <div class="modal-header">
          <h4 class="modal-title">Editar <?php echo ucfirst($tabla); ?></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <!-- Hidden PK -->
          <input type="hidden" name="<?php echo strtolower($clavePrimaria); ?>" id="edit_<?php echo strtolower($clavePrimaria); ?>">

          <?php foreach ($columnas as $col): ?>
            <div class="form-group">
              <label><?php echo ucfirst(str_replace("_", " ", $col)); ?></label>
              <input type="text" name="<?php echo strtolower($col); ?>" id="edit_<?php echo strtolower($col); ?>" class="form-control" required>
            </div>
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


<!-- Delete Modal HTML -->
<div id="deleteProductModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form>
				<div class="modal-header">						
					<h4 class="modal-title">Borrar Producto</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="id_d" name="id" class="form-control">                					
					<p>Esta seguro que desea borrar este producto?</p>
					<p class="text-warning"><small>Esta accion sera permanente.</small></p>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
					<input type="submit" class="btn btn-danger" value="Borrar" id="delete">
				</div>
			</form>
		</div>
	</div>
</div>
</main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Sara Lagos 32251299</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
      <!--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> -->
        <script src="js/ajax.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>