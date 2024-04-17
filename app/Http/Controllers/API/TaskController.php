<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\AssignUserToTaskRequest;
use Illuminate\Support\Facades\Lang;

class TaskController extends Controller
{
    //

    public function index(Request $request)
    {
        $tasks= Task::query(); 
        $request['status'] == false ? $tasks : $tasks= $tasks->where('status', $request['status']);
        $request['date_from'] == false || $request['date_to'] == false? $tasks : $tasks= $tasks->wherebetween('due_date', [$request['date_from'], $request['date_to']]); //due date range as mentioned in requirements.
        $request['assigned_user'] == false? $tasks : $tasks= $tasks->where('user_id', $request['assigned_user']); 
        $tasks= $tasks->paginate(15);
        return TaskResource::collection($tasks)->resource;
    }

    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        if(! empty($data['user_id'])){
            $this->authorize('check-if-assignee-has-user-role', $data['user_id']); //Gate defined in AppServiceProvider to make sure that assigned has role user
        } 
        if(! empty ($data['dependencies'])){
            $this->authorize('check-if-dependencies-are-not-completed', json_encode($data['dependencies'])); //Gate defined in AppServiceProvider, I suggested that manager can store task with any status he choose.
            $data['dependencies']= json_encode($data['dependencies']); 
        }
        Task::create($data);
        return response()->Json(['message'=> Lang::get('messages.task-created')],201);
    }

    public function show(Task $task)
    {
        return TaskResource::make($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->validated();
        if(! empty ($data['user_id'])){
            $this->authorize('check-if-assignee-has-user-role', $data['user_id']);//Gate defined in AppServiceProvider to make sure that the new assigned has role user
        }
        if(! empty($data['status'])  && $data['status'] == 'completed' && $task['dependencies'] != null ){
            $this->authorize('check-if-dependencies-are-not-completed', $task['dependencies']);//Gate defined in AppServiceProvider
        }
        $task->update($data); 
        return response()->Json(['message' => Lang::get('messages.task-updated')],201);
    }

    public function destroy(Task $task)
    {
        $task->delete(); 
        return response()->Json(['message' => Lang::get('messages.task-deleted')],200);
    }

    public function assignUserToTask(AssignUserToTaskRequest $request, Task $task)
    {
        $data= $request->validated();
        $this->authorize('check-if-assignee-has-user-role', $data['user_id']); //Gate defined in AppServiceProvider to make sure that the new assigned has role user
        $task->update(["user_id" => $data["user_id"]]); 
        return response()->Json(['message' => Lang::get('messages.task-assigned-to-user')], 201);
    }
}