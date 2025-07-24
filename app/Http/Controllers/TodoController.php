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
        $todo = $this->todoProvider = $todoProvider; //DI kullanımı        
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
        $todo = $this->todoProvider->store($request);

        if($todo[0]){
            return response()->json([
                "status"=> "succes",
                "message"=> $todo[1],
                "data"=> $todo[2],
            ],200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $todo = $this->todoProvider->show($id);

        if($todo[0]){
            return response()->json([
                "status"=> "succes",
                "message"=> $todo[1],
                "data"=> $todo[2],
            ],200);
        }
        
        return response()->json([
            "status"=> "error",
            "message"=> $todo[1],
        ], $todo[2]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $todo = $this->todoProvider->update($request, $id);

        if($todo[0]){
            return response()->json([
                "status"=> "succes",
                "message"=> $todo[1],
                "data"=> $todo[2],
            ],200);
        }
        
        return response()->json([
            "status"=> "error",
            "message"=> $todo[1],
        ], $todo[2]);
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
            ], $todo[2]);
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
