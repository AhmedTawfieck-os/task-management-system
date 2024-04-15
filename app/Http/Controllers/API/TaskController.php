<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    //

    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated(); 
        $this->authorize('check-if-assignee-has-user-role', $data['user_id']);
        if(! empty ($data['dependencies'])){
            $data['dependencies']= json_encode($data['dependencies']); 
        }
        Task::create($data);
        return response()->Json(['message'=> 'Task Created successfully'],201);
    }
}
