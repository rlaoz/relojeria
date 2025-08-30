//Añadir elementos al carrito
// variables globales
let sumaSubtotalGlobal = 0;
let sumaTotalGlobal = 0;

// funciones
function addElemento() {
    let Total = parseInt(document.querySelector('#CantElemento').value);
    let Nom = document.getElementById('Nombre').innerHTML;
    let Precio = document.getElementById('Precio').textContent;
    let subtotal;
    let totalImp;
    let totalFinal;
    let P;


    if (Total > 0) {
        P = parseFloat(Precio.split('$')[1]);
        subtotal = P * Total;
        totalImp = 0.15 * subtotal;
        totalFinal = subtotal + totalImp;

        setCookie('Productos', Total, 2);

        let ordenCookie = getCookie('Pedido') || "";

        let nuevaOrden = "Producto: " + Nom  + ", Precio: $" + P +  ", Cantidad: " + Total + ", Subtotal:" + subtotal.toFixed(2) + ", Total: " + totalFinal.toFixed(2);

        if (ordenCookie) {
            ordenCookie += "|" + nuevaOrden;
        } else {
            ordenCookie = nuevaOrden;
        }

        setCookie('Pedido', ordenCookie, 2);
        
        document.getElementById('idElemento').innerHTML = Total;        

    } else {
        alert('El número de productos debe ser mayor a 0');
    }
}

//Enviar elementos a la tabla carrito 
function mostrarOrden(){
    let orden = getCookie('Pedido'); 
    
    if (orden) {
        let ordenes = orden.split('|');
        
        for (let i = 0; i < ordenes.length; i++) {
            let pedidos = ordenes[i]; 
            let partes = pedidos.split(','); 

            let producto = partes[0].split(':')[1].trim();
            let precio = partes[1].split(':')[1].trim();
            let cantidad = partes[2].split(':')[1].trim();
            let subtotal= partes[3].split(':')[1].trim();
            let total = partes[4].split(':')[1].trim();

            if (producto && cantidad) {
                let tabla = document.querySelector('#Orden tbody');
                let fila = document.createElement('tr');

                let celdaProd = document.createElement('td');
                celdaProd.textContent = producto;
                fila.appendChild(celdaProd);

                let celdaPre = document.createElement('td');
                celdaPre.textContent = precio;
                fila.appendChild(celdaPre);

                let celdaCant = document.createElement('td');
                celdaCant.textContent = cantidad;
                fila.appendChild(celdaCant);

                let celdaSub = document.createElement('td');
                celdaSub.textContent = subtotal;
                fila.appendChild(celdaSub);

                let celdaTo = document.createElement('td');
                celdaTo.textContent = total;
                fila.appendChild(celdaTo);

                tabla.appendChild(fila);
            } else {
                console.log('Error al leer la información del producto');
            }
        }
    } else {
        console.log('No hay pedidos guardados en las cookies.');
    }
}

/*Eliminar producto */



function leeElemento() {
    let Total = getCookie('Productos');
    if (Total > 0) {
        document.getElementById('idElemento').innerHTML = Total;
    }
}

//cookies 
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i].split('=');
        if (c.length >= 2) {
            if (c[0].trim() === cname.trim()) {
                return c[1];
            }
        }
    }
    return "";
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

