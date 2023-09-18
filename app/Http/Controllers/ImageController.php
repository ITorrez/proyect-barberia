<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { //dd($request->all());
        $request->validate([
            'descripcion'=>'required|max:150',
            'imagen'=>'required|image|'
        ]);
        //dd($request['imagen']->store('upload-imgclients','public'));
        $employee = DB::table("employees")->where("user_id",auth()->user()->id)->first();
        
        $cliente_id=$request->client_id;
        $ruta_imagen=$request['imagen']->store('upload-imgclients','public');
        $image=new Image();
        $image->imagen=$ruta_imagen;
        $image->descripcion=$request->descripcion;
        $image->client_id=$request->client_id;
        $image->estado=1;
        $image->employee_id=$employee->id;
        $image->save();
        //return redirect('clients');

        return redirect('clients/'.$cliente_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        //Si el usuario sube una nueva imagen
       // dd($request['imagen']->store('upload-imgclients','public'));
        dd($request->id );
        $cliente_id=$request->client_id;
        $image->descripcion=$request->descripcion;
        if(request('imagen')){
            //obtener la ruta de la imagen 
            $rutaimagen=$request['imagen']->store('upload-imgclients','public'); 
            //Asignar al objeto
            $image->imagen=$rutaimagen;
        }
        $image->save();
        return redirect('clients/'.$cliente_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        $image->estado=0;
        $image->save();
        $cliente_id=$image->client_id;
        return redirect('clients/'.$cliente_id);
    }
}
