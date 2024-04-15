<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Task;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "title" => $this->title, 
            "description" => $this->description, 
            "due_date" => $this->due_date, 
            "status" => $this->status, 
            "assignee" => $this->user->name,
            "dependencies" => $this->dependencies == null? null :
            TaskResource::collection(Task::whereIn('id', json_decode($this->dependencies))->get()),
        ];
    }
}
