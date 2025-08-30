function Modificar(){
    const nombreCompleto = `${$('#nombres').val()} ${$('#apellidos').val()}`;
    const fechaNac = $('#fecha').val();
    const edad = calcularEdad(fechaNac);
    const createTime = getFormattedDate(); // Siempre usa la fecha actual
    const estado = $('#estado').val() || 1;

    const data = {
        Operacion: 'Actualizar',
        nombre: nombreCompleto,
        email: $('#correo').val(),
        clave: $('#password').val(),
        telefono: $('#movil').val(),
        create_time: createTime, // Fecha actual
        estado: estado,
        Direccion: $('#direccion').val(),
        Ubicacion: $('#ubicacion').val(),
        Facebook: $('#redFacebook').val(),
        Twitter: $('#redTwitter').val(),
        Youtube: $('#redYoutube').val(),
        FechaNac: fechaNac,
        edad: edad,
        Username: $('#username').val() // Asegúrate de tener un campo para el Username
    };

    console.log(data);// Añadir console log para depurar

    $.ajax({
        url: 'http://localhost:3000/Controladores/usuario.php', 
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
            console.log('Respuesta:', response);
            // Aquí puedes manejar la respuesta, por ejemplo, cerrar un modal y actualizar una tabla
        },
        error: function(xhr, status, error) {
            console.log('Error:', error);
        }
    });
}


function calcularEdad(fechaNac) {
    const hoy = new Date();
    const nacimiento = new Date(fechaNac);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const m = hoy.getMonth() - nacimiento.getMonth();

    if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
    }

    return edad;
}
function getFormattedDate() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

function CrearProd() {
    // Capturar los valores de los campos
    var Des = $('#descripcion').val();
    var Can = $('#cantidad').val();
    var Pre = $('#precio').val();
    var Mod = $('#modelo').val();
    var Mar = $('#marca').val();
    var Car = $('#caracteristicas').val();

    // Armar JSON
    var Query = {
        type: 1, //tipo de operacion  agregar
        create_time: "2024-08-28 20:14:01",
        descripcion: Des,
        cantidad: Can,
        precio: Pre,
        modelo: Mod,
        marca: Mar,
        caracteristicas: Car,
        estado: "1"
    };

    // Enviar los datos usando AJAX
    $.ajax({
        type: 'POST',
        url: 'http://localhost:3000/controladores/controlador.php',
        contentType: 'application/json; charset=utf-8',
        data: JSON.stringify(Query), // Convertir a JSON string
        dataType: 'json',
        success: function (data) {
            console.log(data);
            alert('Producto agregado exitosamente: ' + data.RegistroCreado);
        },
        error: function (err) {
            console.log(err);
            alert('Error al agregar producto.');
        }
    });
}

function modificarProd() {
    const data = {
        type: 2,
        id: $('#id_prod').val(),
        descripcion: $('#desc_prod').val(),
        cantidad: $('#cant_prod').val(),
        precio: $('#precio_prod').val(),
        modelo: $('#mod_prod').val(),
        marca: $('#marca_prod').val(),
        caracteristicas: $('#carac_prod').val()
    };

    $.ajax({
        url: 'http://localhost:3000/controladores/controlador.php', 
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
            console.log('Respuesta:', response);
            // Manejar la respuesta, por ejemplo, cerrar el modal y actualizar la tabla
        },
        error: function(xhr, status, error) {
            console.log('Error:', error);
        }
    });
}

function eliminarProducto(id) {
    var Query = {
        type: 3, // eliminación
        id: id
    };

    $.ajax({
        type: 'POST',
        url: 'http://localhost:3000/controladores/controlador.php',
        contentType: 'application/json; charset=utf-8',
        data: JSON.stringify(Query), // Convertir a JSON string
        dataType: 'json',
        success: function (data) {
            console.log(data);
            alert('Producto eliminado exitosamente.');

        },
        error: function (err) {
            console.log(err);
            alert('Error al eliminar producto.');
        }
    });
}

function enviarCompra() {
    const username = $('#inputUsername').val();
    const productos = $('#inputProductos').val();
    const subtotal = $('#inputSubtotal').val();
    const total = $('#inputTotal').val();

    const data = {
        username: username,
        productos: productos,
        subtotal: subtotal,
        total: total,
        estado: 1
    };

    console.log(data); 
    $.ajax({
        url: 'http://localhost:3000/Controladores/controlador.php', 
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
            console.log('Respuesta:', response);
        },
        error: function(error) {
            console.log('Error:', error);
        }
    });
}