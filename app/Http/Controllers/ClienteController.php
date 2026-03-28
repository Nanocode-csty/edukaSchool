<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Models\Product;

class ClienteController extends Controller
{
    const PAGINATION = 6;

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');
        $cliente=Cliente::where('estado','=','1')->where('dniCliente','like','%'.$buscarpor.'%')->paginate($this::PAGINATION);

        return view('cplantilla.index',compact('cliente','buscarpor'));
    }

    public function store(Request $request)     
    {         
        $data=request()->validate([             
            'nombreProducto'=>'required|max:30'
        ],         
        [             
            'nombreProducto.required'=>'Ingrese nombre de Producto',             
            'nombreProducto.max'=>'Maximo 30 caracteres para el nombre de producto'
        ]);

        $data = request()->validate([             
            'stockProducto' => 'required|integer|min:0|max:9999999999'
        ], [             
            'stockProducto.required' => 'Ingrese stock de Producto',             
            'stockProducto.integer' => 'El stock debe ser un número entero',             
            'stockProducto.min' => 'El stock no puede ser negativo',             
            'stockProducto.max' => 'El stock no puede ser mayor a 9,999,999,999'
        ]);

        $data = request()->validate([             
            'precioCompraProducto' => 'required|numeric|between:0,999999.99|regex:/^\d{1,6}(\.\d{1,2})?$/'
        ], [             
            'precioCompraProducto.required' => 'Ingrese precio de compra de Producto',
            'precioCompraProducto.regex' => 'El precio puede tener hasta 6 dígitos y 2 decimales',             
            'precioCompraProducto.numeric' => 'El precio de compra debe ser un número',             
            'precioCompraProducto.between' => 'El precio debe ser entre 0 y 999999.99'
        ]);

        $data = request()->validate([             
            'precioVentaProducto' => 'required|numeric|between:0,999999.99|regex:/^\d{1,6}(\.\d{1,2})?$/'
        ], [             
            'precioVentaProducto.required' => 'Ingrese precio de compra de Producto',
            'precioVentaProducto.regex' => 'El precio puede tener hasta 6 dígitos y 2 decimales',             
            'precioVentaProducto.numeric' => 'El precio de compra debe ser un número',             
            'precioVentaProducto.between' => 'El precio debe ser entre 0 y 999999.99'
        ]);
        /*VERIFICAMOS QUE EXISTA LA CATEGORIA*/
        $data = request()->validate([             
            'idCategoria' => 'required|exists:categorias,idcategoria',   
        ],         
        [             
            'idCategoria.required' => 'Ingrese categoría valida de Producto',             
            'idCategoria.exists' => 'La categoría ingresada no existe',
        ]);
        
        $cliente=new Cliente();

        $cliente->nombreProducto=$request->nombreProducto;
        $cliente->stockProducto=$request->stockProducto;
        $cliente->precioCompraProducto=$request->precioCompraProducto;
        $cliente->precioVentaProducto=$request->precioVentaProducto;
        $cliente->idCategoria=$request->idCategoria; 
        $cliente->save();
        return redirect()->route('producto.index')->with('datos','Producto Nuevo Guardado...!'); 
    }

}
