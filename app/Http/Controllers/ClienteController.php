<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::query();

        // Filtrar por categoría si se proporciona
        if ($request->has('categoria') && $request->categoria != '') {
            $query->categoria($request->categoria);
        }

        // Buscar por término si se proporciona
        if ($request->has('buscar') && $request->buscar != '') {
            $query->buscar($request->buscar);
        }

        $clientes = $query->get();

        // Si es una petición AJAX, devolver JSON
        if ($request->ajax()) {
            return response()->json($clientes);
        }

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|in:cultural,natural,historico,gastronomico,aventura',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        // Manejar la subida de imagen
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('imagenes', 'public');
        }

        $cliente = Cliente::create($data);

        if ($request->ajax()) {
            return response()->json($cliente, 201);
        }

        return redirect()->route('clientes.index')->with('success', 'Punto turístico agregado exitosamente.');
    }

    public function show(Cliente $cliente)
    {
        if (request()->ajax()) {
            return response()->json($cliente);
        }
        
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        

        $data = $request->all();

        // Manejar la subida de imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($cliente->imagen) {
                Storage::disk('public')->delete($cliente->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('imagenes', 'public');
        }

        $cliente->update($data);

        if ($request->ajax()) {
            return response()->json($cliente);
        }

        return redirect()->route('clientes.index')->with('success', 'Punto turístico actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        // Eliminar imagen si existe
        if ($cliente->imagen) {
            Storage::disk('public')->delete($cliente->imagen);
        }

        $cliente->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Punto eliminado exitosamente']);
        }

        return redirect()->route('clientes.index')->with('success', 'Punto turístico eliminado exitosamente.');
    }

    // Método específico para obtener coordenadas (para el mapa)
    public function coordenadas()
    {
        $clientes = Cliente::select('id', 'nombre', 'categoria', 'latitud', 'longitud')->get();
        return response()->json($clientes);
    }
}