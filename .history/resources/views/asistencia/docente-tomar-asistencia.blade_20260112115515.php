@if($clase->tiene_asistencia_hoy)
                                                                <div class="btn-group btn-group-sm" role="group">
                                                                    <a href="{{ route('asistencia.docente.ver', $clase->sesion_id) }}" class="btn btn-success" title="Ver asistencia registrada">
                                                                        <i class="fas fa-eye"></i> Ver
                                                                    </a>
                                                                    <a href="{{ route('asistencia.docente.editar', $clase->sesion_id) }}" class="btn btn-warning" title="Editar asistencia registrada"
                                                                       onclick="return confirm('¿Estás seguro de que deseas editar la asistencia? Esto puede afectar reportes y estadísticas.')">
                                                                        <i class="fas fa-edit"></i> Editar
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <a href="{{ route('asistencia.docente.tomar-asistencia') }}?sesion={{ $clase->sesion_id }}" class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-edit"></i> Tomar
                                                                </a>
                                                            @endif
