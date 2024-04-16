<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TaskResource;
use App\Http\Requests\UpdateTaskStatusRequest;

class UserController extends Controller
{
    //

    public function getTasks()
    {
        $userTasks= Auth::user()->tasks; 
        return TaskResource::collection($userTasks);
    }

    public function getSingleTask(Task $task)
    {
        $this->authorize('check-if-user-assigned-to-task', $task);//Gate defined in AppServiceProvider
        return TaskResource::make($task);
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task)
    {
        $data= $request->validated(); 
        $this->authorize('check-if-user-assigned-to-task', $task);//Gate defined in AppServiceProvider
        if($data['status'] == 'completed' && $task['dependencies'] != null ){
            $this->authorize('check-if-dependencies-are-not-completed', $task);//Gate defined in AppServiceProvider
        }
        $task->update($data);
        return response()->Json(['message' => 'Status updated successfully'],200);
    }
}
