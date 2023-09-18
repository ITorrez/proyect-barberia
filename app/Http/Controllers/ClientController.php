<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients=Client::select('clients.id',
                                'clients.name',
                                'apellidos',
                                'telefono',
                                'observacion',
                                'employee_id',
                                'employees.name as personal')
                            ->join('employees', 'employees.id','=','clients.employee_id')
                            ->where('clients.estado','1')
                            ->paginate(10);
        return Inertia::render('Clients/Index',['clients'=>$clients]);
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
    {
        $request->validate([
            'name'=>'required|max:150'
        ]);
        
        $employee = DB::table("employees")->where("user_id",auth()->user()->id)->first();
        
        $cliente=new Client();
        $cliente->name=$request->name;
        $cliente->apellidos=$request->apellidos;
        $cliente->telefono=$request->telefono;
        $cliente->observacion=$request->observacion;
        $cliente->estado=1;
        $cliente->employee_id=$employee->id;
        $cliente->save();
        return redirect('clients');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //return view('recetas.show', compact('receta'));
        $images=Image::select('images.id',
                              'imagen',
                              'descripcion',
                              'client_id')
                        ->join('clients','clients.id','=','images.client_id')
                        ->where([
                                ['images.client_id','=',$client->id],
                                ['images.estado','1']
                                ])->paginate(10);
        return Inertia::render('Clients/Datos',['client'=>$client,
                                                'images'=>$images]);
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
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name'=>'required|max:150'
        ]);
        $employee = DB::table("employees")->where("user_id",auth()->user()->id)->first();

        $client->name=$request->name;
        $client->apellidos=$request->apellidos;
        $client->telefono=$request->telefono;
        $client->observacion=$request->observacion;
        $client->employee_id=$employee->id;
        $client->save();
        return redirect('clients');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $employee = DB::table("employees")->where("user_id",auth()->user()->id)->first();

        $client->estado=0;
        $client->employee_id=$employee->id;
        $client->save();
        return redirect('clients');
    }
}
