<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceLogController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('student')
            ->latest()
            ->paginate(15);
            
        return view('attendances.index', compact('attendances'));
    }
}
