<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

/**********************************/
/* Ejemplo de rutas con Modelo cliente:
RequestType Path	                    Action	RouteName
GET	        /clientes	                index	clientes.index
GET	        /clientes/create	        create	clientes.create
POST	    /clientes	                store	clientes.store
GET	        /clientes/{cliente}	        show	clientes.show
GET	        /clientes/{cliente}/edit	edit	clientes.edit
PUT/PATCH	/clientes/{cliente}	        update	clientes.update
DELETE	    /clientes/{cliente}	        destroy	clientes.destroy
***********************************/

/* Display a listing of the resource. */
Route::get('/clientes',[ClienteController::class,'index'])->name('clientes.index');

/* Show the form for editing the specified resource. */
Route::get('/clientes/{cliente}/edit',[ClienteController::class,'edit'])->name('clientes.edit');

/* Update the specified resource in storage. */
Route::put('/clientes/{cliente}',[ClienteController::class,'update'])->name('clientes.update');

/* Remove the specified resource from storage. */
Route::delete('/clientes/{cliente}',[ClienteController::class,'destroy'])->name('clientes.destroy');

/* Show the form for creating a new resource. */
Route::get('/clientes/create',[ClienteController::class,'create'])->name('clientes.create');

/* Store a newly created resource in storage. */
Route::post('/clientes',[ClienteController::class,'store'])->name('clientes.store');

/* Rutas customizadas *******************************************/
/* Display the specified resources. */
Route::post('/clientes/shows',[ClienteController::class,'shows'])->name('clientes.shows');

