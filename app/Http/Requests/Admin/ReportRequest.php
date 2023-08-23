<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:191',
            'ip' => 'nullable|ip',
            'system' => 'nullable|max:191',
            'keys' => 'nullable|max:191',
            'platform' => 'nullable|max:191',
            'description' => 'nullable|max:4000000000',
            'file' => 'nullable|file|max:1048576|mimes:pdf',
            'status' => 'required|in:Rascunho,Publicado',
        ];
    }
}
