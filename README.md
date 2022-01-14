### Pasos para la descarga y prueba de la aplicación:

1. clonar el repositorio

2. instalar las dependencias que faltan mediante el comando `composer install`

   > puede que, por problemas de compatibilidad, nos pida correr el comando`composer update`

3. crear el fichero `.env` en el directorio principal del proyecto con el contenido pertinente

   > se puede utilizar el fichero `.env.example` para generar el nuevo `.env`

4. finalmente, Laravel puede pedir que se ejecute el comando `php artisan key:generate` para generar una nueva variable de entorno APP_KEY

5. crear la base de datos mediante migraciones o manualmente (este proyecto utiliza migraciones, se ha de correr el comando `php artisan migrate` para generar la migración)

## Agregar AJAX al desarrollo

AJAX se utiliza para hacer que una parte de la layout de una web recarge su contenido sin la necesitad de recargar la página entera.

La idea es agregar AJAX a las funciones o métodos que generan un cambio (o recarga) en el contenido de la tabla con registros. Podemos observar que los registros de la tabla cambian cada vez que filtramos y/o cada vez que eliminamos un registro.

**Todas las funciones AJAX que definiremos en Laravel serán POST**

1. Crearemos un fichero js en la ruta `pubic/js/ajax.js`

2. Enlazaremos el fichero js en la vista que lo utilice, en este caso en la vista `clientes/index.blade.php`

   > el código que se utiliza para enlazar el fichero js es: `<script src="js/ajax.js"></script>`

   > COMPROBAMOS QUE EL FICHERO JS ESTÁ BIEN ENLAZADO AGREGANDO UN `alert('random');`

3. También hay que agregar en el apartado del `head` el siguiente meta: `<meta name="csrf-token" id="token" content="{{ csrf_token() }}">`

   > este requerimiento es obligatorio al utilizar Laravel + AJAX ya que al renderizar un formulario con js no es posible compilar el método `@csrf`(mirar punto 5.)

   - Referencia: https://laravel.com/docs/8.x/csrf

4. Objeto AJAX:

   ``` js
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
   ```

5. **index.blade.php**

   Transcribimos todos los formuluarios para que se adapten a AJAX. Esto lo hacemos ya que javascript no compila código php ni código blade (https://laravel.com/docs/master/routing#form-method-spoofing)

   - ~@csrf~ -> token (ver apartado 3.)
   
   - ~{{method_field('GET')}}~ -> `<input type="hidden" name="_method" value="GET">`

   - ~{{method_field('POST')}}~ -> `<input type="hidden" name="_method" value="POST">`

   - ~{{method_field('PUT')}}~ -> `<input type="hidden" name="_method" value="PUT">`

   - ~{{method_field('DELETE')}}~ -> `<input type="hidden" name="_method" value="DELETE">`

   - si el formulario utiliza AJAX:
   
     - no es nesesario utilizar el atributo `action`

      ```php
      {{-- Buscador (filtro) --}}
      <form method="post">
         <input type="hidden" name="_method" value="POST" id="postFiltro">
         <div class="form-outline">
            <input type="search" id="" name="nombre" class="form-control" placeholder="Buscar por nombre..." aria-label="Search" />
         </div>
      </form>

      /*......*/

      {{-- Eliminar cliente --}}
      <form method="post">
         <input type="hidden" name="_method" value="DELETE" id="deleteCliente">
         <button class= "btn btn-danger" type="submit" value="Delete">Eliminar</button>
      </form>
      ```

   - si el formulario NO utiliza AJAX
      ```PHP
      {{-- Editar cliente --}}
      <form action="{{route('clientes.edit',['cliente'=>$cliente->id])}}" method="post">
         @csrf
         <input type="hidden" name="_method" value="GET">
         <button class= "btn btn-secondary" type="submit" value="Edit">Editar</button>
      </form>
      ```

6. Filtro implementado con AJAX

   **ClienteController.php**

   - la función ha de devolver registros en formato JSON (JavaScript Object Notation)

      ```php
      public function shows(Request $request)
      {
         $clientes=DB::select('select * from clientes where nombre like ?',['%'.$request->input('nombre').'%']);
         // return view('clientes.index', compact('clientes'));

         return response()->json($clientes);
      }
      ```

   **index.blade.php**

   - asociamos un evento y una función js al input/button, por ejemplo `onkeyup="filtro(); return false;"`

   **pubic/js/ajax.js**

   - Creamos la función `filtro`:

      ```js
      /* Función para filtrar recursos implementada con AJAX */
      function filtro() {
         /* Obtener elemento html donde introduciremos la recarga (datos o mensajes) */

         /* 
         Obtener elemento/s que se pasarán como parámetros: token, method, inputs... 
         var token = document.getElementById('token').getAttribute("content");

         Usar el objeto FormData para guardar los parámetros que se enviarán:
         var formData = new FormData();
         formData.append('_token', token);
         formData.append('clave', valor);
         */

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
                  /* creación de estructura: la estructura que creamos no ha de contener código php ni código blade*/
                  /* utilizamos innerHTML para introduciremos la recarga en el elemento html pertinente */
            }
         }
         /*
         send(string)->Sends the request to the server (used for POST)
         */
         ajax.send(formData)
      }
      ```

7. Botón eliminar implementado con AJAX

   **ClienteController.php**

   - la función ha de devolver registros en formato JSON (JavaScript Object Notation)

      ```php
      public function destroy(Cliente $cliente)
      {
         try {
               DB::delete('delete from clientes where id=?',[$cliente->id]);
               //return redirect()->route('clientes.index');

               return response()->json(array('resultado'=> 'OK'));
         } catch (\Throwable $th) {
               return response()->json(array('resultado'=> 'NOK: '.$th->getMessage()));
         }
      }
      ```

   **index.blade.php**

   - asociamos un evento y una función js al input/button, por ejemplo `onclick="eliminar(id); return false;"`

   **pubic/js/ajax.js**

   - Creamos la función `eliminar`

      ```js
      /* Función para filtrar recursos implementada con AJAX */
      function eliminar(cliente_id) {
         /* Obtener elemento html donde introduciremos la recarga (datos o mensajes) */

         /* 
         Obtener elemento/s que se pasarán como parámetros: token, method, inputs... 
         var token = document.getElementById('token').getAttribute("content");

         Usar el objeto FormData para guardar los parámetros que se enviarán:
         var formData = new FormData();
         formData.append('_token', token);
         formData.append('clave', valor);
         */

         /* Inicializar un objeto AJAX */
         var ajax = objetoAjax();
         /*
         ajax.open("method", "rutaURL", true);
         GET  -> No envía parámetros
         POST -> Sí envía parámetros
         true -> asynchronous
         */
         ajax.open("POST", "clientes/"+cliente_id, true);
         ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
                  var respuesta = JSON.parse(this.responseText);
                  if (respuesta.resultado == "OK") {
                     /* creación de estructura: la estructura que creamos no ha de contener código php ni código blade*/
                     /* utilizamos innerHTML para introduciremos la recarga en el elemento html pertinente */
                  } else {
                     /* creación de estructura: la estructura que creamos no ha de contener código php ni código blade*/
                     /* utilizamos innerHTML para introduciremos la recarga en el elemento html pertinente */
                  }
                  filtro();
            }
         }
         /*
         send(string)->Sends the request to the server (used for POST)
         */
         ajax.send(formData)
      }
      ```