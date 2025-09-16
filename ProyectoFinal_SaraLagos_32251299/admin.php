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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script>
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

	//agregar
    $('#addProductModal form').on('submit', function (e) {
        e.preventDefault(); // Evitar el envío del formulario por defecto
        CrearProd();
    });


	//borrar
	$('#deleteProductModal').on('show.bs.modal', function (e) {
    var button = $(e.relatedTarget); // Botón que activó el modal
    var id = button.data('prod-id');
    
    var modal = $(this);
    modal.find('#id_d').val(id);
	});

	$('#deleteProductModal form').on('submit', function (e) {
    e.preventDefault();
    var id = $('#id_d').val();
    eliminarProducto(id);
	});

    //editar
	$(document).ready(function() {
	$('#update').on('click', function() {
        modificarProd();
    });

    $('.edit').on('click', function() {
        var id = $(this).data('prod-id');
        var descripcion = $(this).data('desc-id');
        var cantidad = $(this).data('cant-id');
        var precio = $(this).data('precio-id');
        var modelo = $(this).data('mod-id');
        var marca = $(this).data('marca-id');
        var caracteristicas = $(this).data('carac-id');
		console.log(id, descripcion, cantidad, precio, modelo, marca, caracteristicas);

        $('#id_prod').val(id);
        $('#desc_prod').val(descripcion);
        $('#cant_prod').val(cantidad);
        $('#precio_prod').val(precio);
        $('#mod_prod').val(modelo);
        $('#marca_prod').val(marca);
        $('#carac_prod').val(caracteristicas);

        $('#editProductModal').modal('show');
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
                        <th>ID</th>
                        <?php
                        // Obtener los nombres de columnas
                        $primerFila = mysqli_fetch_assoc($resultado);
                        $columnas = [];
                        if($primerFila){
                            foreach(array_keys($primerFila) as $columna){
                                if($columna != 'id'){ // id ya está como primera columna
                                    echo "<th>" . ucfirst($columna) . "</th>";
                                    $columnas[] = $columna;
                                }
                            }
                        }
                        ?>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($primerFila){
                        echo "<tr>";
                        echo "<td>" . $primerFila['id'] . "</td>";
                        foreach($columnas as $col){
                            echo "<td>" . htmlspecialchars($primerFila[$col]) . "</td>";
                        }
                        echo "<td>
                            <a href='#editProductModal' class='edit btn btn-sm btn-info' data-prod-id='{$primerFila['id']}'>Editar</a>
                            <a href='#deleteProductModal' class='delete btn btn-sm btn-danger' data-prod-id='{$primerFila['id']}'>Borrar</a>
                        </td>";
                        echo "</tr>";
                    }

                    while($fila = mysqli_fetch_assoc($resultado)){
                        echo "<tr>";
                        echo "<td>" . $fila['id'] . "</td>";
                        foreach($columnas as $col){
                            echo "<td>" . htmlspecialchars($fila[$col]) . "</td>";
                        }
                        echo "<td>
                            <a href='#editProductModal' class='edit btn btn-sm btn-info' data-prod-id='{$fila['id']}'>Editar</a>
                            <a href='#deleteProductModal' class='delete btn btn-sm btn-danger' data-prod-id='{$fila['id']}'>Borrar</a>
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
			<form>
				<div class="modal-header">						
					<h4 class="modal-title">Agregar Productos</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">					
					<div class="form-group">
						<label>Descripcion</label>
						<input type="text" id="descripcion" name="descripcion" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Cantidad</label>
						<input type="number" id="cantidad" name= "cantidad" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Precio</label>
						<input type="text" id="precio" name= "precio" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Modelo</label>
						<input type="text" id="modelo" name= "modelo" class="form-control" required>
					</div>	
                    <div class="form-group">
						<label>Marca</label>
						<input type="text" id="marca" name= "marca" class="form-control" required>
					</div>	
                    <div class="form-group">
						<label>Caracteristicas</label>
                        <textarea class="form-control" id="caracteristicas" name= "caracteristicas" required></textarea>
					</div>					
				</div>
				<div class="modal-footer">
					<input type="hidden" value="1" name="type">
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
			<form>
				<div class="modal-header">						
					<h4 class="modal-title">Editar Producto</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">					
					<input type="hidden" id="id_prod" name="id" class="form-control" required>       
					<div class="form-group">
						<label>Descripcion</label>
						<input type="text" id="desc_prod" name="descripcion" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Cantidad</label>
						<input type="number" id="cant_prod" name="cantidad" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Precio</label>
						<input type="text" id="precio_prod" name="precio" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Modelo</label>
						<input type="text" id="mod_prod" name="modelo" class="form-control" required>
					</div>	
                    <div class="form-group">
						<label>Marca</label>
						<input type="text" id="marca_prod" name="marca" class="form-control" required>
					</div>	
                    <div class="form-group">
						<label>Caracteristicas</label>
                        <textarea class="form-control" id="carac_prod" name="caracteristicas" required></textarea>
					</div>				
				</div>
				<div class="modal-footer">
					<input type="hidden" value="2" name="type">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
					<input type="submit" class="btn btn-info" id="update" value="Guardar">
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/ajax.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>