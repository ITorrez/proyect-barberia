<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Si el usuario autenticado es de tipo 2 validamos para negarle el acceso
        //if(auth()->user()->tipo==2)
        //{
            $this->authorize('viewAny');
        //}
        $departments=Department::all();
        return Inertia::render('Departments/Index',['departments'=>$departments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(auth()->user()->tipo==2)
        {
            $this->authorize('create');
        }
        return Inertia::render('Departments/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name'=>'required|max:100']);
        $department=new Department($request->input());
        $department->save();
        return redirect('departments');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
            $this->authorize('update',$department);
       
        return Inertia::render('Departments/Edit',['department'=>$department]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $this->authorize('update',$department);
        $request->validate(['name'=>'required|max:100']);
        $department->update($request->all());
        return redirect('departments');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $this->authorize('delete',$department);
        $department->delete();
        return redirect('departments');
    }
}
