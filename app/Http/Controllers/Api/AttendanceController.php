<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use App\Services\FaceMatchingService;
use App\Services\FastApiService;

class AttendanceController extends Controller
{
    protected $faceMatchingService;
    protected $fastApiService;

    public function __construct(FaceMatchingService $faceMatchingService, FastApiService $fastApiService)
    {
        $this->faceMatchingService = $faceMatchingService;
        $this->fastApiService = $fastApiService;
    }

    public function recordAttendance(Request $request)
    {
        $request->validate([
            'photo' => 'required|image'
        ]);

        try {
            $photo = $request->file('photo');

            $currentEmbedding = $this->fastApiService->getFaceEmbedding($photo);

            $students = Student::where('is_active', true)->whereNotNull('face_embedding')->get();

            $matchedStudent = null;

            foreach ($students as $student) {
                if ($this->faceMatchingService->isMatch($student->face_embedding, $currentEmbedding, 1.1)) {
                    $matchedStudent = $student;
                    break;
                }
            }

            if ($matchedStudent) {
                $lastAttendance = Attendance::where('student_id', $matchedStudent->id)
                    ->whereDate('created_at', today())
                    ->orderBy('id', 'desc')
                    ->first();

                $eventType = 'in';
                if ($lastAttendance && $lastAttendance->event_type === 'in') {
                    $eventType = 'out';
                }

                $attendance = Attendance::create([
                    'student_id' => $matchedStudent->id,
                    'event_type' => $eventType
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "Asistencia registrada para: {$matchedStudent->name}",
                    'student' => $matchedStudent,
                    'event' => $eventType
                ]);
            }

            return response()->json(['error' => 'Rostro no reconocido en la base de datos.'], 404);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
