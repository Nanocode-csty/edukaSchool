<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Usuario;
use App\Models\Persona;
use App\Models\InfDocente;
use App\Models\InfRepresentante;
use App\Models\InfEstudiante;

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario actual
     */
    public function show()
    {
        $user = Auth::user();
        $persona = $user->persona;

        // Obtener información específica del rol
        $roleSpecificData = $this->getRoleSpecificData($user);

        return view('profile.show', compact('user', 'persona', 'roleSpecificData'));
    }

    /**
     * Mostrar formulario de edición del perfil
     */
    public function edit()
    {
        $user = Auth::user();
        $persona = $user->persona;

        // Obtener información específica del rol
        $roleSpecificData = $this->getRoleSpecificData($user);

        return view('profile.edit', compact('user', 'persona', 'roleSpecificData'));
    }

    /**
     * Actualizar el perfil del usuario
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $persona = $user->persona;

        // Validación básica para persona
        $personaRules = [
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'genero' => 'nullable|in:M,F',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ];

        // Validación para usuario
        $userRules = [
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('usuarios', 'username')->ignore($user->usuario_id, 'usuario_id')
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('usuarios', 'email')->ignore($user->usuario_id, 'usuario_id')
            ],
        ];

        // Agregar validación de contraseña solo si se proporciona
        if ($request->filled('current_password') || $request->filled('password')) {
            $userRules['current_password'] = 'required_with:password';
            $userRules['password'] = 'nullable|string|min:8|confirmed';

            // Validar contraseña actual
            if ($request->filled('current_password')) {
                if (!Hash::check($request->current_password, $user->password_hash)) {
                    return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
                }
            }
        }

        // Validación específica por rol
        $roleSpecificRules = $this->getRoleSpecificValidationRules($user);

        // Agregar validación de foto si se sube
        if ($request->hasFile('photo')) {
            $roleSpecificRules['photo'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $allRules = array_merge($personaRules, $userRules, $roleSpecificRules);

        $validatedData = $request->validate($allRules);

        \Log::info('Profile update - Validation passed', [
            'validated_data_keys' => array_keys($validatedData),
            'has_photo' => isset($validatedData['photo'])
        ]);

        try {
            \Log::info('Starting photo processing', [
                'hasFile' => $request->hasFile('photo'),
                'fileValid' => $request->hasFile('photo') ? $request->file('photo')->isValid() : 'N/A',
                'allFiles' => $request->allFiles()
            ]);

            // Manejar subida de foto si existe
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                \Log::info('Processing photo upload', [
                    'file_name' => $request->file('photo')->getClientOriginalName(),
                    'file_size' => $request->file('photo')->getSize(),
                    'file_mime' => $request->file('photo')->getMimeType()
                ]);

                // Eliminar foto anterior si existe
                if ($user->foto_url && Storage::disk('public')->exists($user->foto_url)) {
                    Storage::disk('public')->delete($user->foto_url);
                    \Log::info('Old photo deleted successfully');
                }

                // Generar nombre único para la foto
                $fileName = 'profile_' . $user->usuario_id . '_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
                $path = $request->file('photo')->storeAs('profile-photos', $fileName, 'public');

                $userData['foto_url'] = $path;
                \Log::info('New photo saved at path: ' . $path);

                // Verificar que el archivo se guardó correctamente
                if (!Storage::disk('public')->exists($path)) {
                    \Log::error('Photo file was not saved correctly at path: ' . $path);
                    return back()->withErrors(['photo' => 'Error al guardar la foto de perfil.']);
                }
            } else {
                \Log::info('No photo to process or photo is invalid', [
                    'hasFile' => $request->hasFile('photo'),
                    'isValid' => $request->hasFile('photo') ? $request->file('photo')->isValid() : 'N/A',
                    'errors' => $request->file('photo') ? $request->file('photo')->getError() : 'N/A'
                ]);
            }

            // Actualizar datos de persona
            $personaData = array_merge($personaData ?? [], [
                'nombres' => $validatedData['nombres'],
                'apellidos' => $validatedData['apellidos'],
                'fecha_nacimiento' => $validatedData['fecha_nacimiento'] ?? null,
                'genero' => $validatedData['genero'] ?? null,
                'direccion' => $validatedData['direccion'] ?? null,
                'telefono' => $validatedData['telefono'] ?? null,
            ]);

            // Si hay email en persona, actualizarlo también
            if (isset($validatedData['email'])) {
                $personaData['email'] = $validatedData['email'];
            }

            $persona->update($personaData);
            \Log::info('Persona updated', ['persona_foto_url_after' => $persona->foto_url]);

            // Actualizar datos de usuario
            $userData = array_merge($userData ?? [], [
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
            ]);

            // Actualizar contraseña si se proporciona
            if ($request->filled('password')) {
                $userData['password_hash'] = Hash::make($validatedData['password']);
            }

            \Log::info('Updating user with data', $userData);
            $user->update($userData);
            \Log::info('User updated successfully', ['user_foto_url_after' => $user->foto_url]);

            // Actualizar datos específicos del rol
            $this->updateRoleSpecificData($user, $validatedData);

            return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar el perfil: ' . $e->getMessage()]);
        }
    }

    /**
     * Actualizar foto de perfil
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        try {
            // Eliminar foto anterior si existe
            if ($user->persona->foto_url && Storage::disk('public')->exists($user->persona->foto_url)) {
                Storage::disk('public')->delete($user->persona->foto_url);
            }

            // Guardar nueva foto
            $path = $request->file('photo')->store('profile-photos', 'public');

            // Actualizar ruta en la base de datos
            $user->persona->update(['foto_url' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Foto de perfil actualizada correctamente.',
                'photo_url' => asset('storage/' . $path)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener datos específicos del rol del usuario
     */
    private function getRoleSpecificData(Usuario $user)
    {
        $data = [];

        if ($user->hasRole('Docente')) {
            $data['docente'] = $user->persona->docente;
        }

        if ($user->hasRole('Representante')) {
            $data['representante'] = $user->persona->representante;
            $data['estudiantes'] = $user->persona->representante->estudiantes ?? collect();
        }

        if ($user->hasRole('Estudiante')) {
            $data['estudiante'] = $user->persona->estudiante;
        }

        return $data;
    }

    /**
     * Obtener reglas de validación específicas del rol
     */
    private function getRoleSpecificValidationRules(Usuario $user)
    {
        $rules = [];

        if ($user->hasRole('Docente')) {
            $rules = array_merge($rules, [
                'especialidad' => 'nullable|string|max:100',
                'grado_academico' => 'nullable|string|max:100',
                'experiencia' => 'nullable|integer|min:0',
            ]);
        }

        if ($user->hasRole('Representante')) {
            $rules = array_merge($rules, [
                'ocupacion' => 'nullable|string|max:100',
                'empresa' => 'nullable|string|max:100',
            ]);
        }

        return $rules;
    }

    /**
     * Actualizar datos específicos del rol
     */
    private function updateRoleSpecificData(Usuario $user, array $validatedData)
    {
        if ($user->hasRole('Docente') && $user->persona->docente) {
            $user->persona->docente->update([
                'especialidad' => $validatedData['especialidad'] ?? null,
                'grado_academico' => $validatedData['grado_academico'] ?? null,
                'experiencia' => $validatedData['experiencia'] ?? null,
            ]);
        }

        if ($user->hasRole('Representante') && $user->persona->representante) {
            $user->persona->representante->update([
                'ocupacion' => $validatedData['ocupacion'] ?? null,
                'empresa' => $validatedData['empresa'] ?? null,
            ]);
        }
    }
}
