<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

try {
    // Buscar el usuario
    $usuario = Usuario::where('username', 'representante')->first();
    
    if (!$usuario) {
        echo "Usuario no encontrado\n";
        exit(1);
    }

    echo "Usuario encontrado: " . $usuario->username . "\n";
    echo "Usuario ID: " . $usuario->id . "\n";

    // Verificar relaciones
    if ($usuario->persona) {
        echo "Persona asociada: " . $usuario->persona->id_persona . "\n";
        echo "Nombre: " . $usuario->persona->nombres . " " . $usuario->persona->apellidos . "\n";

        if ($usuario->persona->representante) {
            echo "Representante asociado: " . $usuario->persona->representante->representante_id . "\n";

            // Verificar estudiantes
            $estudiantes = $usuario->persona->representante->estudiantes()->get();
            echo "Estudiantes asociados: " . $estudiantes->count() . "\n";
            foreach ($estudiantes as $est) {
                echo "  - Estudiante ID: " . $est->estudiante_id . "\n";
                if ($est->matricula) {
                    echo "    Matricula: " . $est->matricula->matricula_id . "\n";
                } else {
                    echo "    Sin matrícula activa\n";
                }
            }
        } else {
            echo "NO tiene representante asociado\n";
        }
    } else {
        echo "NO tiene persona asociada\n";
    }

    echo "\nDatos verificados correctamente\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
