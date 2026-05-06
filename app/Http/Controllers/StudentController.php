<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Services\FastApiService;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::latest()->paginate(10);
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.form');
    }

    public function store(Request $request, FastApiService $fastApiService)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'matricula' => 'required|string|unique:students,matricula|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'matricula' => $request->matricula,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            $data['face_embedding'] = $fastApiService->getFaceEmbedding($photo);
            $data['photo_path'] = $photo->store('students', 'public');
        }

        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Estudiante creado exitosamente.');
    }

    public function edit(Student $student)
    {
        return view('students.form', compact('student'));
    }

    public function update(Request $request, Student $student, FastApiService $fastApiService)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'matricula' => 'required|string|max:255|unique:students,matricula,' . $student->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'matricula' => $request->matricula,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            $data['face_embedding'] = $fastApiService->getFaceEmbedding($photo);

            if ($student->photo_path) {
                Storage::disk('public')->delete($student->photo_path);
            }
            $data['photo_path'] = $photo->store('students', 'public');
        }

        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Estudiante actualizado exitosamente.');
    }

    public function destroy(Student $student)
    {
        if ($student->photo_path) {
            Storage::disk('public')->delete($student->photo_path);
        }
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Estudiante eliminado.');
    }
}
