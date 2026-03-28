<?php

namespace App\Http\Requests;

class StoreEstudianteRequest extends PersonaRequest
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

            'referenciaEstudiante' => ['nullable', 'string', 'max:100'],
            'inputBuscar' => 'required|digits:8|exists:personas,dni', //DNI DEL REP1
            'inputBuscar2' => 'nullable|digits:8|exists:personas,dni', //DNI DEL REP2

        ]);
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        //TRAEMOS LOS MENSAJES DE LAS VALIDACIONES DEL FORMULARIO DE PersonaRequest
        return array_merge(parent::messages(), [
            'inputBuscar.required' => 'Debe asignar su representante de manera correcta.',
        ]);
    }
}
