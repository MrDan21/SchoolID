@extends('layouts.app')

@section('content')
<div class="row row-cards">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Bitácora de Entradas y Salidas</h3>
      </div>
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
          <thead>
            <tr>
              <th>Matrícula</th>
              <th>Nombre Alumno</th>
              <th>Evento</th>
              <th>Fecha y Hora</th>
            </tr>
          </thead>
          <tbody>
            @forelse($attendances as $log)
            <tr>
              <td>{{ $log->student->matricula ?? 'N/A' }}</td>
              <td>{{ $log->student->name ?? 'Desconocido' }}</td>
              <td>
                @if($log->event_type === 'in')
                  <span class="badge bg-success me-1"></span> Entrada
                @else
                  <span class="badge bg-warning me-1"></span> Salida
                @endif
              </td>
              <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="text-center text-muted">No hay registros de asistencia aún.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer d-flex align-items-center">
        {{ $attendances->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
