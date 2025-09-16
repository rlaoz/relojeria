function obtenerDatosModal(modal) {
    let datos = {};
    modal.find('input, textarea, select').each(function() {
        const name = $(this).data('name'); // antes era .attr('name')
        if (name) {
            datos[name] = $(this).val();
        }
    });
    return datos;
}


function enviarDatos(tabla, tipoOperacion, modal) {
    const datos = obtenerDatosModal(modal);
    datos['type'] = tipoOperacion;  // 1=agregar, 2=editar, 3=eliminar
    datos['tabla'] = tabla;

    $.ajax({
        url: 'http://localhost:3000/controladores/controlador.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(datos),
        success: function(respuesta) {
            console.log('Respuesta:', respuesta);
            modal.modal('hide'); // Cierra el modal
            location.reload();   // Recarga la tabla
        },
        error: function(err) {
            console.log('Error:', err);
            alert('Ocurrió un error al procesar la operación.');
        }
    });
}
