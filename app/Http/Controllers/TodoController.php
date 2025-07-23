<?php

namespace App\Http\Controllers;

use App\Enums\Priority;
use App\Enums\Status;
use App\Http\Resources\TodoResources;
use App\Models\todo as Todo;
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
    public function index(Request $request)
    {
        $page = $request->query('page');
        $limit = $request->query('limit');
        $sort = $request->query('sort');
        $order = $request->query('order');
        $status = $request->query('status');
        $priority = $request->query('priority');

        if($limit < 10 ) {
            $limit = 10;
        }
        elseif($limit > 50){
            $limit = 50;
        }

        if($page > $limit or !$page){
            $page = 1;
        }

        if($order != "asc" or "desc"){
            $order = "asc";
        }

        if(!$sort){
            $sort = "id";
        }

        $query = Todo::query();

        if($status){
            $query->where('status', $status);
        }
        if($priority){
            $query->where('priority', $priority);
        }

        // $sort sıralama yapılacak sütunu, $order sıralama yönünü belirtir.    
        $query->orderBy($sort, $order);
        
        $todos = $query->paginate($limit, ['*'], 'page', $page);

        
        if(!$todos->isEmpty()){
        
            return response()->json([
                "status"=> "succes",
                "message"=> "İstenen todolar döndürüldü",   
                "data"=> TodoResources::collection($todos),
                              
            ],200);
        }
        
        return response()->json([
            "status"=> "error",
            "message"=> "hata alındı todolar bulunamadı",
        ]);
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

        //dd(Purifier::clean("<script>alert('xss')</script>"));

          

        if ($validator->fails()) {
            return response()->json([
                "status"=> "error",
                "message"=> "Form verisini eksisiz ve doğru bir şekilde doldurun"
                ],422);            
        }

        $validated = $validator->validated();

        $validated["title"] = Purifier::clean($validated["title"]);
        $validated["description"] = Purifier::clean($validated["description"]);  

   

        $todo = Todo::create($validated);

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
        $todo = Todo::find($id);

        if(!$todo){
            return response()->json([
                "status"=> "error",
                "message"=> "Aradığınız todo bulundamadı"
            ],404);
        }

        return response()->json([
            "status"=> "success",
            "message"=> "Todo başarıyla bulundu",
            "data" => new TodoResources(($todo)),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            "title" => [$request->isMethod("patch") ? "sometimes" : "required", "min:3", "max:100"],
            "description"=> ["sometimes", "max:500"],
            "status"=> [$request->isMethod("patch") ? "sometimes" : "required"  , new Enum(Status::class)],
            "priority"=> ["sometimes", new Enum(Priority::class)],
            "due_date"=> ["sometimes", "date_format:Y-m-d", "after:today"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"=> "error",
                "message"=> "Yanlış veya eksik veri girdiniz. Güncelleme işlemi için lütfen veriyi doğru girin.",
            ],422);
        }

        $todo = Todo::find($id);
        if(!$todo){
            return response()->json([
                "status"=> "error",
                "message"=> "Aradığınız todo bulundamadı"
            ],404);
        }
        $todo->update($request->all());

        return response()->json([
            "status"=> "success",
            "message"=> "Güncelleme işlemi başarılı.",
            "process" => $request->isMethod("patch") ? "patch" : "put",
            "data" => new TodoResources($todo)
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::find($id);

        if(!$todo){
            return response()->json([
                "status"=> "error",
                "message"=> "Aradığınız todo bulunamadı."
            ],404);
        }

        if ($todo->trashed())   {}
        else    {   $todo->delete();    }
        
        return response()->json([
            "status"=> "success",
            "message"=> "Silme işlemi başarılı."
        ],200);
        
    }

    public function search(Request $request)
    {
        

        $data = $request->input('q');

        
        if(!$data){
            return response()->json([
                'status'=> 'error',
                'message'=> 'Arama terimi boş olamaz'
            ],400);
        }
        
        $todos = Todo::where('title','like','%'. $data .'%')->orWhere('description','like','%'. $data .'%')->get();

        if(!$todos->isEmpty()){
            return response()->json([
                'status'=> 'success',
                'message'=> 'Aradığınız todo bulundu',
                'data'=> TodoResources::collection($todos)
            ],200);
        }

         return response()->json([
             "status"=> "error",
             "message"=> "Aradığınız  todo bulunamadı.",
         ],404);
       
    }
    
}
