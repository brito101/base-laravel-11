<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
            'name' => 'required|min:1|max:100',
            'capacity' => 'required|in:Proteção Cibernética,Ataque Cibernético,Exploração Cibernética',
            'description' => 'nullable|max:65535',
            'creator'  => 'nullable|exists:users,id',
            'editor'  => 'nullable|exists:users,id',
        ];
    }
}
