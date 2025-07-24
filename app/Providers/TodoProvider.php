<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Enums\Priority;
use App\Enums\Status;
use App\Http\Resources\TodoResources;
use App\Models\todo as Todo;
use Eloquent;
use Illuminate\Http\Request;
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
        
            return [true, "Aradığınız kriterlere uygun todo'lar bulundu.",  TodoResources::collection($todos)];
        }
        
        return [false, "Aradığınız kriterlere uygun todo'lar bulunamadı.",  404];
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $todo = Todo::create($request->all());

        return [true, "Todo başarıyla oluşturuldu.",new TodoResources($todo)];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $todo = Todo::find($id);

        if(!$todo){
            return [false, "Aradığınz todo bulunamadı", 404];
        }

        return  [true, "Aradığınız todo bulundu.", new TodoResources($todo)];
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

        return  [true, "Todo güncellendi.", new TodoResources($todo)];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::find($id);

        if(!$todo){
            return [false, 'Aradığınız todo bulunamadı.', 404];
        }

        if ($todo->trashed())   {}
        else    {   $todo->delete();    }
        
        return [true, "Silme işlemi başarılı.", 200];
        
    }

    public function search(Request $request)
    {
        

        $data = $request->input('q');

        
        if(!$data){
            return [false, "Arama terimi boş olamaz.", 400];
        }
        
        $todos = Todo::where('title','like','%'. $data .'%')->orWhere('description','like','%'. $data .'%')->get();

        if(!$todos->isEmpty()){
            return [true, "Aradığınız todo bulundu.", TodoResources::collection($todos)];
        }

         return [false, "Aradığınız  todo bulunamadı.", 404];
       
    }
}
