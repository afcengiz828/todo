<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Enums\Priority;
use App\Enums\Status;
use App\Http\Resources\TodoResources;
use App\Models\todo as Todo;
use Eloquent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Mews\Purifier\Facades\Purifier;


class TodoProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function __construct()
    {

    }
    

    public function index(Request $request)
    {
        $page = (int)$request->query('page');
        $limit = (int)$request->query('limit');
        $sort = $request->query('sort');
        $order = $request->query('order');
        $status = $request->query('status');
        $priority = $request->query('priority');

        $countQuery = Todo::count();
        
        //return null;
        if($limit and $limit < 10 ) { $limit = 10; }
        elseif($limit and $limit > 50){ $limit = 50; }

        if($page<0){ $page = 1; }

        if($order != "asc" && $order != "desc"){ $order = "asc"; }

        if(!$sort){  $sort = "id"; }

        $userId = Auth::guard('api')->id();
        $query = Todo::where("user_id", $userId)->with("categories");


        if($status){ $query->where('status', $status); }

        if($priority) { $query->where('priority', $priority);  }
    
        // $sort sıralama yapılacak sütunu, $order sıralama yönünü belirtir.    
        $query->orderBy($sort, $order);
        
        if($limit and $page){
            $todos = $query->paginate($limit, ['*'], 'page', $page);
        }
        else{
            $todos = $query->get();
        }


        
        if(!$todos->isEmpty()){       
            return [true, "Aradığınız kriterlere uygun todo'lar bulundu.",  [TodoResources::collection($todos), $countQuery]];
        }
        
        return [false, "Aradığınız todo bulunamadı.",  404];
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //$request->due_date->format("Y-m-d");
        $todo = Todo::create($request->all());
        $todo->load('categories');

        return [true, "Todo başarıyla oluşturuldu.", new TodoResources($todo)];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $todo = Todo::with("categories")->find($id);


        if($todo){
            return  [
                true, 
                "Aradığınız todo bulundu.", 
                new TodoResources($todo), 
            ];
        }

        return  [
            false, 
            "Aradığınız todo bulunamadı.", 
            404
        ];

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {      

        $todo = Todo::find($id);


        if(!$todo){
            return [false, "Aradığınz todo bulunamadı", 404];
        }

        $todo->update($request->all());
        $todo->load('categories');


        return  [
            true, 
            "Todo güncellendi.", 
            new TodoResources($todo),
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::find($id);
        

        if(!$todo){
            return [false, 'Aradığınız todo bulunamadı.',404];
        }

        if ($todo->trashed())   { return [true, "Zaten silinmiş.",200]; }
        else    { $todo->delete(); } 
        
        return [true, "Silme işlemi başarılı.",$todo];
        
    }

    public function search(Request $request)
    {
        

        $data = $request->input('q');

        
        if(!$data){
            return [false, "Arama terimi boş olamaz.", 400];
        }
        
        
        $todos = Todo::with('categories')
            ->where(function($query) use ($data) {
                $query->where('title', 'like', '%'. $data .'%')
                      ->orWhere('description', 'like', '%'. $data .'%');
            })
            ->get();

      

        if(!$todos->isEmpty()){
            return [true, "Aradığınız todo bulundu.", TodoResources::collection($todos)];
        }

         return [false, "Aradığınız  todo bulunamadı.", 404];
       
    }
}
