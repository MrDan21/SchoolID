<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalStudents = \App\Models\Student::count();
        $activeStudents = \App\Models\Student::where('is_active', true)->count();
        $todayAttendances = \App\Models\Attendance::whereDate('created_at', today())->count();
        
        $recentAttendances = \App\Models\Attendance::with('student')
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact(
            'totalStudents', 
            'activeStudents', 
            'todayAttendances', 
            'recentAttendances'
        ));
    }
}
