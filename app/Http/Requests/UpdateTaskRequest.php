<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "title" => "string", 
            "description" => "string", 
            "due_date" => "date|date_format:Y-m-d|after_or_equal:today", 
            "status" => "in:pending,completed,canceled",
            "user_id" => "integer|exists:users,id",
            
            //According to the task requirement, dependencies are not required to be updated
            //
        ];
    }
}
