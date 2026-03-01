<?php

namespace App\Http\Requests\Web\Backend\V1\Job;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'salary' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'department' => 'nullable|array',
            'department.*' => 'string|max:255',
            'job_type' => 'nullable|in:full_time,part_time,contract,temporary,other',
            'category_id' => 'sometimes|exists:job_categories,id',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}
