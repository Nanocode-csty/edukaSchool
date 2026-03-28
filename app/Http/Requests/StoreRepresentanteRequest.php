<?php

namespace App\Http\Requests;

class StoreRepresentanteRequest extends PersonaRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        //TRAEMOS TODAS LAS VALIDACIONES DEL FORMULARIO DE PersonaRequest
        return array_merge(parent::rules(), [

            'ocupacion' => ['nullable', 'string', 'max:100'],

        ]);
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        //TRAEMOS LOS MENSAJES DE LAS VALIDACIONES DEL FORMULARIO DE PersonaRequest
        return array_merge(parent::messages(), []);
    }
}
