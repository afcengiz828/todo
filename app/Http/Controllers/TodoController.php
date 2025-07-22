<?php

namespace App\Http\Controllers;

use App\Enums\Priority;
use App\Enums\Status;
use App\Http\Resources\TodoResources;
use App\Models\todo as Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {        
        $validator = Validator::make($request->all(), [
            "title" => ["sometimes", "required", "min:3", "max:100"],
            "description"=> ["sometimes", "max:500"],
            "status"=> ["sometimes", new Enum(Status::class)],
            "priority"=> ["sometimes", new Enum(Priority::class)],
            "due_date"=> ["sometimes", "date_format:Y-m-d", "after:today"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"=> "error",
                "message"=> "Form verisini eksisiz ve doğru bir şekilde doldurun"
                ],422);            
        }

        $todo = Todo::create($request->all());

        return response()->json([
            "status"=> "success",
            "message"=> "Todo başarıyla oluşturuldu",
            "data" => new TodoResources(($todo)),
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
