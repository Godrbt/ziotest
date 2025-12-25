<?php

namespace App\Http\Controllers;
use App\Models\TodoTable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class TodoController extends Controller
{
    //
    function index()
    {
        return inertia::render('Todo/Index', [
            
        ]);
    }

    function create()
    {
        return inertia::render('Todo/Create');
    }

    function datatableview(){

        if(request()->ajax()){
        $data = TodoTable::query();
        return DataTables::eloquent($data)
        ->addIndexColumn()

         ->addColumn('action', function($data){
                return '
                <button data-id="'.$data->id.'" class="btn btn-sm btn-success btn-sm edit-user">Edit</button>
                <button data-id="'.$data->id.'" class="btn btn-sm btn-danger btn-sm delete-user">Delete</button>
                ';
            })


            ->rawColumns(['action'])
        ->make(true);

        }
        return view('todos.datatable');
    }


    
     function store(Request $request)
    {
       
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        if($request->filled('aws3')){
            $filepath = $request->file('file')->store('uploads','s3');
        }
        else{
            $filepath = $request->file('file')->store('uploads','public');
        }

        TodoTable::create([
            'name' => $request->name,
            'description' => $request->description,
            'file' => $filepath,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
        ]);
    }

   public function destroy($id){
    $todo = TodoTable::find($id);
    if($todo){
            $todo->delete();
            return response()->json(['message' => 'Todo deleted successfully'], 200);
        }
        return response()->json(['message' => 'Todo not found'], 404);

    }

    public function edit($id){
        $todo = TodoTable::find($id);
        dd($todo);
    }

    public function update(Request $request, $id){
        $todo = TodoTable::find($id);
        if($todo){
            $todo->name = $request->name;
            $todo->description = $request->description;
            $todo->due_date = $request->due_date;
            $todo->priority = $request->priority;
            $todo->save();
            return response()->json(['message' => 'Todo updated successfully'], 200);
        }
        return response()->json(['message' => 'Todo not found'], 404);
    }

}
