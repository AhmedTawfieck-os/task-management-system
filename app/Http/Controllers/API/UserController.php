<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TaskResource;

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
}
