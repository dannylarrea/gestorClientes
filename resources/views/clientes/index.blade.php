<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>App gestor de clientes | CRUD Laravel + AJAX</title>
</head>
<body>
    <h1 class="p-4">App gestor de clientes</h1>

    <div class="p-5"> 
        <div class="row pb-3">
            <div class="col">
                {{-- Buscador (filtro) --}}
                <form action="{{route('clientes.shows')}}" method="post">
                    @csrf
                    {{method_field('POST')}}
                    <div class="form-outline">
                        <input type="search" id="" name="nombre" class="form-control" placeholder="Buscar por nombre..." aria-label="Search" />
                    </div>
                </form>
            </div>
            <div class="col">
                {{-- Route::get('/clientes/create',[ClienteController::class,'create'])->name('clientes.create'); --}}
                <form action="{{route('clientes.create')}}" method="post">
                    @csrf
                    {{method_field('GET')}}
                    <input type="submit" class="btn btn-primary" value="Nuevo cliente">
                </form>
            </div>
            <div class="col"></div>
        </div>       
        <table class="table">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Ocupación</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Sitio Web</th>
                <th scope="col" colspan="2">Acciones</th>
            </tr>
            @forelse ($clientes as $cliente)
            <tr>
                <td scope="row">{{$cliente->id}}</td>
                <td>{{$cliente->nombre}}</td>
                <td>{{$cliente->ocupacion}}</td>
                <td>{{$cliente->telefono}}</td>
                <td>{{$cliente->website}}</td>
                <td>
                    {{-- Route::get('/clientes/{cliente}/edit',[ClienteController::class,'edit'])->name('clientes.edit'); --}}
                    <form action="{{route('clientes.edit',['cliente'=>$cliente->id])}}" method="post">
                        @csrf
                        {{method_field('GET')}}
                        <button class= "btn btn-secondary" type="submit" value="Edit">Editar</button>
                    </form>
                </td>
                <td>
                    {{-- Route::delete('/clientes/{cliente}',[ClienteController::class,'destroy'])->name('clientes.destroy'); --}}
                    <form action="{{route('clientes.destroy',['cliente'=>$cliente->id])}}" method="post">
                        @csrf
                        {{method_field('DELETE')}}
                        <button class= "btn btn-danger" type="submit" value="Delete">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7">No hay registros</td></tr>
            @endforelse
        </table>
    </div>
</body>
</html>