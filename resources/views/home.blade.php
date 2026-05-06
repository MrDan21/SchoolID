@extends('layouts.app')

@section('page-header')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title">
          Dashboard
        </h2>
      </div>
    </div>
  </div>
</div>
@endsection

@section('content')
<div class="row row-cards mb-4">
  
  <div class="col-sm-6 col-lg-4">
    <div class="card card-sm shadow-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <span class="bg-primary text-white avatar">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
            </span>
          </div>
          <div class="col">
            <div class="font-weight-medium">
              {{ $totalStudents }} Estudiantes
            </div>
            <div class="text-secondary">
              Total registrados
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-4">
    <div class="card card-sm shadow-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <span class="bg-success text-white avatar">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
            </span>
          </div>
          <div class="col">
            <div class="font-weight-medium">
              {{ $activeStudents }} Activos
            </div>
            <div class="text-secondary">
              Listos para reconocimiento
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-4">
    <div class="card card-sm shadow-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <span class="bg-twitter text-white avatar">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 7l0 5l3 3" /></svg>
            </span>
          </div>
          <div class="col">
            <div class="font-weight-medium">
              {{ $todayAttendances }} Registros
            </div>
            <div class="text-secondary">
              Asistencias de hoy
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row row-cards">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header border-0">
        <h3 class="card-title">Últimos Registros de Asistencia</h3>
        <div class="card-actions">
          <a href="{{ route('attendances.index') }}" class="btn btn-primary btn-sm">Ver todos</a>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
          <thead>
            <tr>
              <th class="w-1">Estudiante</th>
              <th>Matrícula</th>
              <th>Tipo de Registro</th>
              <th>Hora Exacta</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentAttendances as $attendance)
            <tr>
              <td>
                <div class="d-flex py-1 align-items-center">
                  <span class="avatar me-2" style="background-image: url('{{ $attendance->student->photo_url ?? '' }}')">
                    @if(!$attendance->student->photo_url)
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                    @endif
                  </span>
                  <div class="flex-fill">
                    <div class="font-weight-medium">{{ $attendance->student->name }}</div>
                  </div>
                </div>
              </td>
              <td>
                <span class="text-secondary">{{ $attendance->student->matricula }}</span>
              </td>
              <td>
                @if($attendance->event_type === 'in')
                    <span class="badge bg-success me-1"></span> Entrada
                @else
                    <span class="badge bg-warning me-1"></span> Salida
                @endif
              </td>
              <td>
                <div class="d-flex flex-column">
                  <span>{{ $attendance->created_at->format('H:i:s') }}</span>
                  <small class="text-secondary">{{ $attendance->created_at->format('d/m/Y') }}</small>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="text-center text-secondary py-4">No hay registros de asistencia recientes.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
