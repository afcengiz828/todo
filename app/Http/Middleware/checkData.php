<?php

namespace App\Http\Middleware;

use App\Enums\Priority;
use App\Enums\Status;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Mews\Purifier\Facades\Purifier;
use Symfony\Component\HttpFoundation\Response;

class checkData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
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

        $validated = $validator->validated();

        $validated["title"] = Purifier::clean($validated["title"]);
        $validated["description"] = Purifier::clean($validated["description"]);  
        $request->merge($validated);

        return $next($request);
    }
}
