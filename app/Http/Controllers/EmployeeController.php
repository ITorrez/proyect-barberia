<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    //public function __construct()
    //{
        //Protege todas las rutas y funciones excepto show
       // $this->middleware('auth',['except'=>'show']);
   // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Si el usuario autenticado es de tipo 2 validamos para negarle el acceso
        if(auth()->user()->tipo==2)
        {
            $this->authorize('viewAny');
        }
       
        $employees=Employee::select('employees.id',
                                    'employees.name',
                                    'email',
                                    'clave',
                                    'phone',
                                    'tipo',
                                    'departments_id',
                                    'departments.name as department')
                                ->join('departments','departments.id','=','employees.departments_id')
                                ->where('employees.estado',1)
                                ->orderByRaw('employees.name ASC')
                                ->paginate(10);
        $departments=Department::all();
        return Inertia::render('Employees/Index',['employees'=>$employees,
                                                  'departments'=>$departments]);
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
        //return redirect('departments');
        $request->validate([
            'name'=>'required|max:150',
            'email'=>'required|max:100|unique:'.User::class,
            'phone'=>'required|max:20',
            'clave'=>'required|min:8',
            'tipo'=>'required|numeric',
            //'departments_id'=>'required|numeric',
        ]);
        //dd($request);
        //$employee=new Employee($request->input());
       // $employee->save();

       //Registro en la tabla user
       /*$user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->clave),
       ]);*/

       $user = new User;
       $user->name=$request->name;
       $user->email=$request->email;
       $user->password=Hash::make($request->clave);
       $user->tipo=$request->tipo;
       $user->save();

        $employee=new Employee;
        $employee->name=$request->name;
        $employee->email=$request->email;
        $employee->clave=$request->clave;
        $employee->phone=$request->phone;
        $employee->user_id=$user->id;
        $employee->user_reg=auth()->user()->id;
        $employee->tipo=$request->tipo;
        $employee->estado=1;
        $employee->departments_id=1;//$request->departments_id;
        $employee->save();
        return redirect('employees');

        // $product =new Product;
        // $product->name= $request->name;
        // $product->description=$request->description;
        // $product->amount=$request->amount;
        // $product->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $this->authorize('update',$employee);
        $request->validate([
            'name'=>'required|max:150',
            'email'=>'required|max:100',
            'clave'=>'required|min:8',
            'phone'=>'required|max:20',
            
            //'user_id'=>'required|numeric',
            'tipo'=>'required|numeric',
            //'departments_id'=>'required|numeric',
        ]);
        //$employee->update($request->input());

        //Modificacion en la tabla user
        $user= User::find($employee->user_id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->clave);
        $user->tipo=$request->tipo;
        $user->save();

        //$employee=new Employee;
        $employee->name=$request->name;
        $employee->email=$request->email;
        $employee->clave=$request->clave;
        $employee->phone=$request->phone;
        //$employee->user_id=auth()->user()->id;
        $employee->user_reg=auth()->user()->id;
        $employee->tipo=$request->tipo;
       // $employee->departments_id=$request->departments_id;
        $employee->save();
        return redirect('employees');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $this->authorize('delete',$employee);
       // $employee->delete();

       $user= User::find($employee->user_id);
       $user->delete();

        $employee->estado=0;
        $employee->save();
       
        return redirect('employees');

        
    }

    public function EmployeeByDepartment()
    {
        $data=Employee::select(DB::raw('count(employees.id) as count, departments.name as name'))
                                ->join('departments','departments.id','=','employees.departments_id')
                                ->groupBy('departments.name')->get();
        return Inertia::render('Employees/Graphic',['data'=> $data]);
    }
    public function reports()
    {
        $employees=Employee::select('employees.id',
                                    'employees.name',
                                    'email',
                                    'phone',
                                    'departments_id',
                                    'departments.name as department')
                                ->join('departments','departments.id','=','employees.departments_id')
                                ->get();

        $departments=Department::all();
        return Inertia::render('Employees/Reports',['employees'=>$employees,
                                                  'departments'=>$departments]);
    }
}
