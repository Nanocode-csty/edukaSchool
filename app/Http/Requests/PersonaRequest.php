<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonaRequest extends FormRequest
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
        return [
            'dni' => 'required|digits:8|unique:personas,dni',
            'nombres' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:100',
            'apellidoMaterno' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:M,F',

            'email' => 'required|email|unique:personas,email',
            'telefono' => 'required|regex:/^[0-9]{9}$/',

            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'region' => 'required',
            'provincia' => 'required',
            'distrito' => 'required',

            'calle' => 'required|string|max:255',
            'referencia' => 'nullable|string|max:255',

        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'dni.required' => 'El DNI es obligatorio',
            'dni.digits' => 'El DNI debe tener 8 dígitos',
            'dni.unique' => 'El DNI ingresado, ya está registrado',

            'genero.required' => 'El género es obligatorio',


            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',

            'nombres.required' => 'Los nombres son obligatorios',
            'apellidoPaterno.required' => 'El apellido paterno es obligatorio',
            'apellidoMaterno.required' => 'El apellido materno es obligatorio',

            'email.required' => 'El correo es obligatorio',
            'email.email' => 'Debe ingresar un correo válido',
            'email.unique' => 'Este correo ya está registrado',

            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono debe tener 9 dígitos',

            'region.required' => 'Debe seleccionar una región',
            'provincia.required' => 'Debe seleccionar una provincia',
            'distrito.required' => 'Debe seleccionar un distrito',

            'calle.required' => 'Debe ingresar la dirección',
        ];
    }
}
