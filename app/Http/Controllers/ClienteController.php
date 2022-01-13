<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes=DB::select('select * from clientes');
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::insert('insert into clientes (nombre, ocupacion, telefono, website) values (?,?,?,?)',[$request->input('nombre'),$request->input('ocupacion'),$request->input('telefono'),$request->input('website')]);
        return redirect()->route('clientes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        $clientes=DB::select('select * from clientes where id=?',[$cliente->id]);
        $cliente=$clientes[0];
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        DB::update('update clientes set nombre=?, ocupacion=?,telefono=?,website=? where id=?',[$request->input('nombre'),$request->input('ocupacion'),$request->input('telefono'),$request->input('website'),$cliente->id]);
        return redirect()->route('clientes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        DB::delete('delete from clientes where id=?',[$cliente->id]);
        return redirect()->route('clientes.index');
    }

    /* Funciones customizadas *******************************************/

    /**
     * Display the specified resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    ********************************************************************/
    public function shows(Request $request)
    {
        $clientes=DB::select('select * from clientes where nombre like ?',['%'.$request->input('nombre').'%']);
        return view('clientes.index', compact('clientes'));
    }
}
