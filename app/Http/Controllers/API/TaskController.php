<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    //

    public function index(Request $request)
    {
        $tasks= Task::query(); 
        $request['status'] == false ? $tasks : $tasks= $tasks->where('status', $request['status']);
        $request['due_date'] == false? $tasks : $tasks= $tasks->where('due_date', $request['due_date']);
        $request['assigned_user'] == false? $tasks : $tasks= $tasks->where('user_id', $request['assigned_user']); 
        $tasks= $tasks->get();
        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        //when storing new task, should check on the dependencies status?
        $data = $request->validated(); 
        $this->authorize('check-if-assignee-has-user-role', $data['user_id']); //Gate defined in AppServiceProvider
        if(! empty ($data['dependencies'])){
            $data['dependencies']= json_encode($data['dependencies']); 
        }
        Task::create($data);
        return response()->Json(['message'=> 'Task Created successfully'],201);
    }

    public function show(Task $task)
    {
        return TaskResource::make($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->validated();
        if(! empty ($data['user_id'])){
            $this->authorize('check-if-assignee-has-user-role', $data['user_id']);//Gate defined in AppServiceProvider
        }
        if(! empty($data['status'])  && $data['status'] == 'completed' && $task['dependencies'] != null ){
            $this->authorize('check-if-dependencies-are-not-completed', $task);//Gate defined in AppServiceProvider
        }
        $task->update($data); 
        return response()->Json(['message' => 'Task Updated Successfully'],201);
    }

    public function destroy(Task $task)
    {
        $task->delete(); 
        return response()->Json(['message' => 'Task Deleted successfully'],200);
    }
}
