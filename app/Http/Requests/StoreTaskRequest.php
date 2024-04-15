<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class StoreTaskRequest extends FormRequest
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
            "title" => "required|string", 
            "description" => "required|string", 
            "due_date" => "required|date|date_format:Y-m-d|after_or_equal:today", 
            "status" => "required|in:pending,completed,canceled",
            "user_id" => "required|integer|exists:users,id",
            "dependencies" => "nullable|array", 
            "dependencies.*" => "integer|exists:tasks,id"             
        ];
    }
}
