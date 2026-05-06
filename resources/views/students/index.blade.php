@extends('layouts.app')

@section('content')
<div class="row row-cards">
  <div class="col-12">
    <div class="card">
      <div class="card-header border-0 d-flex justify-content-between align-items-center">
        <h3 class="card-title">Listado de Estudiantes</h3>
        <a href="{{ route('students.create') }}" class="btn btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
          Nuevo Estudiante
        </a>
      </div>
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Foto</th>
              <th>Matrícula</th>
              <th>Nombre</th>
              <th>Estatus</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($students as $student)
            <tr>
              <td><span class="text-muted">{{ $student->id }}</span></td>
              <td>
                @if($student->photo_path)
                  <span class="avatar avatar-sm" style="background-image: url({{ $student->photo_url }})"></span>
                @else
                  <span class="avatar avatar-sm">{{ substr($student->name, 0, 2) }}</span>
                @endif
              </td>
              <td>{{ $student->matricula }}</td>
              <td>{{ $student->name }}</td>
              <td>
                @if($student->is_active)
                  <span class="badge bg-success me-1"></span> Activo
                @else
                  <span class="badge bg-danger me-1"></span> Inactivo
                @endif
              </td>
              <td>
                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="card-footer d-flex align-items-center">
        {{ $students->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
