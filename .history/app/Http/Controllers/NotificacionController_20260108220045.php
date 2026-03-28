<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /**
     * Get notifications for the current user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Notificacion::paraUsuario($user->usuario_id)
            ->orderBy('created_at', 'desc');

        // Filter by read status if specified
        if ($request->has('leidas')) {
            if ($request->leidas === 'true') {
                $query->leidas();
            } elseif ($request->leidas === 'false') {
                $query->noLeidas();
            }
        }

        $notificaciones = $query->paginate(20);

        // Determinar el módulo según el rol del usuario
        $modulo = $this->getModuloPorRol($user->rol);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $notificaciones
            ]);
        }

        return view('notificaciones.index', compact('notificaciones', 'modulo'));
    }

    /**
     * Determina el módulo según el rol del usuario
     */
    private function getModuloPorRol($rol)
    {
        return match($rol) {
            'Administrador' => 'admin',
            'Docente', 'Profesor' => 'docente',
            'Representante' => 'representante',
            default => 'general'
        };
    }

    /**
     * Get unread notifications count
     */
    public function countUnread()
    {
        $count = Notificacion::paraUsuario(Auth::id())
            ->noLeidas()
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, Notificacion $notificacion)
    {
        // Ensure user owns the notification
        if ($notificacion->usuario_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para esta notificación.'
            ], 403);
        }

        $notificacion->marcarComoLeida();

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída.'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notificacion::paraUsuario(Auth::id())
            ->noLeidas()
            ->update(['leido_en' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Todas las notificaciones han sido marcadas como leídas.'
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy(Notificacion $notificacion)
    {
        // Ensure user owns the notification
        if ($notificacion->usuario_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para esta notificación.'
            ], 403);
        }

        $notificacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificación eliminada.'
        ]);
    }

    /**
     * Get recent unread notifications for dropdown
     */
    public function recent()
    {
        $notificaciones = Notificacion::paraUsuario(Auth::id())
            ->noLeidas() // Only unread notifications
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notificaciones
        ]);
    }
}
