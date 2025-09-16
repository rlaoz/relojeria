<?php
    require __DIR__ . '/includes/funciones.php';
/*   $auth = estaAutenticado();
    if(!$auth){
        header('location: ./auth/login.php'); 
    }
    //$auth = $_SESSION['login'] ?? false;*/
    $consulta = obtener_productos();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>CRUD ADMIN</title>
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
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">Éphémère</a>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <?php if($auth): ?>
                            <li><a class="dropdown-item" href="/auth/cerrar-sesion.php">Logout</a></li>
                        <?php else: ?>
                        <li><a class="dropdown-item" href="/auth/login.php">Login</a></li>           
                         <?php endif; ?>   
                    </ul>
                </li>
            </ul>
        </nav>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Start Bootstrap
                    </div>
            </div>
            <div id="layoutSidenav_content">
            <main class="container-fluid px-4">
    <h1 class="mt-4">CRUD ADMIN</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">CRUD</li>
    </ol>

    <!-- Botones de Acciones -->
    <div class="mb-3">
    <a href="#addProductModal" class="btn btn-success" data-toggle="modal"> <span>Nuevo Producto</span></a>
    					
    </div>

    <!-- Tarjeta de la Tabla -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            CRUD
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Caracteristicas</th>
                        <th>Acciones</th> <!-- Nueva columna para los botones -->
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Caracteristicas</th>
                        <th>Acciones</th>
                    </tr>
                </tfoot>
                <tbody>
                
                <?php while($producto = mysqli_fetch_assoc($consulta)){ ?>
                    <tr>
                        
                        <td>
                            <span class="custom-checkbox">
                            <input type="checkbox" id="checkbox1" name="options[]" class="user_checkbox" data-prod-id="<?php echo $producto['id']; ?>">
                            <label for="checkbox1"></label>
                            </span>
                        <?php echo $producto['id']; ?>
                        </td>
                        <td><?php echo $producto['descripcion']; ?></td>
                        <td><?php echo $producto['cantidad']; ?></td>
                        <td><?php echo $producto['precio']; ?></td>
                        <td><?php echo $producto['modelo']; ?></td>
                        <td><?php echo $producto['marca']; ?></td>
                        <td><?php echo $producto['caracteristicas']; ?></td>
                        <td>
                            <!-- Botones de Acciones -->
                        <a href="#editProductModal" class="edit" data-toggle="modal"
							data-prod-id="<?php  echo $producto['id']; ?>" 
							data-desc-id="<?php  echo $producto['descripcion']; ?>" 
							data-cant-id="<?php  echo $producto['cantidad']; ?>" 
							data-precio-id="<?php  echo $producto['precio']; ?>" 
							data-mod-id="<?php  echo $producto['modelo']; ?>"
							data-marca-id="<?php  echo $producto['marca']; ?>"
							data-carac-id="<?php  echo $producto['caracteristicas']; ?>" 
							title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
							<a href="#deleteProductModal" class="delete" data-prod-id="<?php  echo $producto['id']; ?>" data-toggle="modal"><i data-toggle="tooltip" title="Delete" class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
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