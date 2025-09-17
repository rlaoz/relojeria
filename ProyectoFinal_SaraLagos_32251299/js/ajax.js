function obtenerDatosModal(modal) {
    let datos = {};
    modal.find('input, textarea, select').each(function() {
        const name = $(this).attr('name'); // usar name en lugar de data-name
        if (name) {
            datos[name] = $(this).val();
        }
    });
    return datos;
}



function enviarDatos(tabla, tipoOperacion, modal) {
    const datos = obtenerDatosModal(modal);
    datos['type'] = tipoOperacion;  // 1=agregar, 2=editar
    datos['tabla'] = tabla;

    console.log("Datos a enviar:", datos);
   $.ajax({
    url: 'controladores/controlador.php',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify(datos),
    success: function(respuesta) {
        console.log('Respuesta:', respuesta);
        modal.modal('hide');
        location.reload();
    },
    error: function(err) {
        console.log('Error:', err);
        alert('Ocurrió un error al procesar la operación.');
    }
});
}
