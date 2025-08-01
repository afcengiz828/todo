<?php

namespace App\Http\Controllers;

use App\Enums\Priority;
use App\Enums\Status;
use App\Http\Resources\TodoResources;
use App\Models\todo as Todo;
use App\Providers\TodoProvider;
use Eloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Mews\Purifier\Facades\Purifier;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $todoProvider;

    public function __construct(TodoProvider $todoProvider)
    {
        $this->todoProvider = $todoProvider; //DI kullanımı        
    }


    public function index(Request $request)
    {
        $todos = $this->todoProvider->index($request);

        if($todos[0]){
            return response()->json([
                "status"=> "succes",
                "message"=> $todos[1],
                "data"=> $todos[2],
            ],200);
        }
        
        return response()->json([
            "status"=> "error",
            "message"=> $todos[1],
        ], $todos[2]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {       
        $validator = Validator::make($request->all(), [
            "title" => [ $request->isMethod("patch") ?  "sometimes" : "required", "min:3", "max:100"],
            "description"=> ["nullable", "max:500"],
            "status"=> ["sometimes", new Enum(Status::class)],
            "priority"=> ["sometimes", new Enum(Priority::class)],
            "due_date"=> ["sometimes", "date_format:Y-M-d", "after:today"],
        ]);
        

        $validated = $validator->validated();

        if($request->isMethod("put")){
            $validated["title"] = Purifier::clean($validated["title"]);
            $validated["description"] = Purifier::clean($validated["description"]);  
            $request->merge($validated);
        }

        $todos = $this->todoProvider->store($request);

        
        return response()->json([
            "status"=> "success",
            "message"=> $todos[1],
            "data"=> $todos[2],
        ],200);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $todos = $this->todoProvider->show($id);

        if($todos[0]){
            return response()->json([
                "status"=> "succes",
                "message"=> $todos[1],
                "data"=> $todos[2],
            ],200);
        }
        
        return response()->json([
                "status"=> "succes",
                "message"=> $todos[1],
            ],$todos[2]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $todos = $this->todoProvider->update($request, $id);

        if($todos[0]){
            return response()->json([
                "status"=> "succes",
                "message"=> $todos[1],
                "data"=> $todos[2],
            ],200);
        }
        
        return response()->json([
            "status"=> "error",
            "message"=> $todos[1],
        ], $todos[2]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = $this->todoProvider->destroy($id);   

        if($todo[0]){
            return response()->json([
                "status"=> "succes",
                "message"=> $todo[1],
            ], 200);
        }

        return response()->json([
                "status"=> "error",
                "message"=> $todo[1],
            ], $todo[2]);       
    }

    public function search(Request $request)
    {
        $todos = $this->todoProvider->search($request);

        if($todos[0]){
            return response()->json([
            'status'=> 'success',
            'message'=> $todos[1],
            'data'=> $todos[2],
        ],200);
        }

        return response()->json([
            'status'=> 'error',
            'message'=> $todos[1],
        ],$todos[2]);
    }
    
}
