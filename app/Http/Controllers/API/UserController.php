<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TaskResource;
use App\Http\Requests\UpdateTaskStatusRequest;
use Illuminate\Support\Facades\Lang;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

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
            $this->authorize('check-if-dependencies-are-not-completed', $task['dependencies']);//Gate defined in AppServiceProvider
        }
        $task->update($data);
        return response()->Json(['message' => Lang::get('messages.task-status-changed')],200);
    }

    public function index(Request $request)
    {
        $users= User::query()->role('user'); 
        $request['name'] == false? $users : $users= $users->where('name', 'like', '%'. $request['name']. '%'); 
        $users= $users->paginate(15); 
        return UserResource::collection($users)->resource;
    }

    public function show(User $user)
    {
        if($user->hasRole('user')){
            return response()->Json([UserResource::make($user)],200);
        }
        return response()->Json(['message' => 'This member does not have role user'], 403);
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated(); 
        $data['password'] = Hash::make($request['password']);
        User::create($data); 
        return response()->Json(['message' => 'User created successfully'], 201);
    }

    public function destroy(User $user)
    {
        
    }
}
