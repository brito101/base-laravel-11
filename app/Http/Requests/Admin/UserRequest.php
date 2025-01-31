<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'google2fa_secret_enabled' => ! ($this->google2fa_secret_enabled == null),
            'first_access' => $this->first_access == 'true',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            'name' => 'required|min:3|max:100',
            'email' => "required|min:6|max:100|unique:users,email,$this->id,id,deleted_at,NULL",
            'password' => 'max:100',
            'photo' => 'image|mimes:jpg,png,jpeg,gif,svg,webp|max:4096|dimensions:max_width=4000,max_height=4000',
            'telephone' => 'nullable|min:8|max:25',
            'cell' => 'nullable|min:8|max:25',
            'first_access' => 'nullable|boolean',
        ];
    }
}
