<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;

class TaskController extends Controller
{
    //

    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated(); 
        return $data;
    }
}
