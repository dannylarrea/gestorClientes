function objetoAjax() {
    var xmlhttp = false;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

/* Función para filtrar recursos implementada con AJAX */
function filtro() {
    /* Obtener elemento html donde introduciremos la recarga (datos o mensajes) */
    var table = document.getElementById('table');
    /* 
    Obtener elemento/s que se pasarán como parámetros: token, method, inputs... 
    var token = document.getElementById('token').getAttribute("content");


    Usar el objeto FormData para guardar los parámetros que se enviarán:
    var formData = new FormData();
    formData.append('_token', token);
    formData.append('clave', valor);
    */
    var token = document.getElementById('token').getAttribute("content");
    var method = document.getElementById('postFiltro').value;
    var filtro = document.getElementById('search').value;

    var formData = new FormData();
    formData.append('_token', token);
    formData.append('_method', method);
    formData.append('nombre', filtro);

    /* Inicializar un objeto AJAX */
    var ajax = objetoAjax();
    /*
    ajax.open("method", "rutaURL", true);
    GET  -> No envía parámetros
    POST -> Sí envía parámetros
    true -> asynchronous
    */
    ajax.open("POST", "clientes/shows", true);
    ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var respuesta = JSON.parse(this.responseText);
                /* Crear la estructura html que se devolverá dentro de una variable recarga*/
                var recarga = '';

                recarga += '<tr>';
                recarga += '<th scope="col">#</th>';
                recarga += '<th scope="col">Nombre</th>';
                recarga += '<th scope="col">Ocupación</th>';
                recarga += '<th scope="col">Teléfono</th>';
                recarga += '<th scope="col">Sitio Web</th>';
                recarga += '<th scope="col" colspan="2">Acciones</th>';
                recarga += '</tr>';
                for (let i = 0; i < respuesta.length; i++) {
                    recarga += '<tr>';
                    recarga += '<td scope="row">' + respuesta[i].id + '</td>';
                    recarga += '<td>' + respuesta[i].nombre + '</td>';
                    recarga += '<td>' + respuesta[i].ocupacion + '</td>';
                    recarga += '<td>' + respuesta[i].telefono + '</td>'
                    recarga += '<td>' + respuesta[i].website + '</td>';
                    recarga += '<td>';
                    // editar
                    recarga += '<form action="./clientes/' + respuesta[i].id + '/edit" method="post">';
                    recarga += '<input type="hidden" name="_method" value="GET">';
                    recarga += '<button class= "btn btn-secondary" type="submit" value="Edit">Editar</button>';
                    recarga += '</form>';
                    recarga += '</td>';
                    recarga += '<td>';
                    // eliminar
                    recarga += '<form method="post">';
                    recarga += '<input type="hidden" name="_method" value="DELETE" id="deleteCliente">';
                    recarga += '<button class= "btn btn-danger" type="submit" value="Delete" onclick="eliminar(' + respuesta[i].id + '); return false;">Eliminar</button>';
                    recarga += '</form>';
                    recarga += '</td>';
                    recarga += '</tr>';
                }

                table.innerHTML = recarga;
                /* creación de estructura: la estructura que creamos no ha de contener código php ni código blade*/
                /* utilizamos innerHTML para introduciremos la recarga en el elemento html pertinente */
            }
        }
        /*
        send(string)->Sends the request to the server (used for POST)
        */
    ajax.send(formData);
}

/* Función para filtrar recursos implementada con AJAX */
function eliminar(cliente_id) {
    /* Obtener elemento html donde introduciremos la recarga (datos o mensajes) */
    var message = document.getElementById("message");
    /* 
    Obtener elemento/s que se pasarán como parámetros: token, method, inputs... 
    var token = document.getElementById('token').getAttribute("content");
 
    Usar el objeto FormData para guardar los parámetros que se enviarán:
    var formData = new FormData();
    formData.append('_token', token);
    formData.append('clave', valor);
    */
    var token = document.getElementById('token').getAttribute("content");
    var method = document.getElementById('deleteCliente').value;

    var formData = new FormData();
    formData.append('_token', token);
    formData.append('_method', method);

    /* Inicializar un objeto AJAX */
    var ajax = objetoAjax();
    /*
    ajax.open("method", "rutaURL", true);
    GET  -> No envía parámetros
    POST -> Sí envía parámetros
    true -> asynchronous
    */
    ajax.open("POST", "clientes/" + cliente_id, true);
    ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var respuesta = JSON.parse(this.responseText);
                if (respuesta.resultado == "OK") {
                    /* creación de estructura: la estructura que creamos no ha de contener código php ni código blade*/
                    /* utilizamos innerHTML para introduciremos la recarga en el elemento html pertinente */
                    message.innerHTML = 'El registro #' + cliente_id + ' se ha eliminado correctamente.';
                } else {
                    /* creación de estructura: la estructura que creamos no ha de contener código php ni código blade*/
                    /* utilizamos innerHTML para introduciremos la recarga en el elemento html pertinente */
                    message.innerHTML = 'Ha habido un error: ' + respuesta.resultado;
                }
                filtro();
            }
        }
        /*
        send(string)->Sends the request to the server (used for POST)
        */
    ajax.send(formData);
}