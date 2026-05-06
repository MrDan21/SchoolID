@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <form action="{{ isset($student) ? route('students.update', $student) : route('students.store') }}" method="POST" class="card" enctype="multipart/form-data">
        @csrf
        @if(isset($student))
            @method('PUT')
        @endif
      <div class="card-header">
        <h3 class="card-title">{{ isset($student) ? 'Editar Estudiante' : 'Nuevo Estudiante' }}</h3>
      </div>
      <div class="card-body">
        
        <div class="mb-3">
          <label class="form-label required">Matrícula</label>
          <input type="text" class="form-control @error('matricula') is-invalid @enderror" name="matricula" value="{{ old('matricula', $student->matricula ?? '') }}" placeholder="Ej. 1900213" required>
          @error('matricula')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label required">Nombre Completo</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $student->name ?? '') }}" placeholder="Juan Pérez" required>
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Foto del Estudiante</label>
          @if(isset($student) && $student->photo_path)
            <div class="mb-2">
                <img src="{{ $student->photo_url }}" alt="Foto de {{ $student->name }}" class="img-thumbnail" style="max-width: 150px;">
            </div>
          @endif
          <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo" accept="image/*">
          <small class="form-hint">Formatos soportados: JPEG, PNG, JPG (Máx. 2MB)</small>
          @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" {{ old('is_active', $student->is_active ?? true) ? 'checked' : '' }}>
            <span class="form-check-label">Estudiante Activo</span>
          </label>
        </div>

      </div>
      <div class="card-footer text-end">
        <a href="{{ route('students.index') }}" class="btn btn-link">Cancelar</a>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>
@endsection
