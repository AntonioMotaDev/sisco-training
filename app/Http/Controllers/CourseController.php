<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display the courses dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        return view('admin.courses.dashboard', compact('user'));
    }

    /**
     * Display courses list
     */
    public function index()
    {
        $user = Auth::user();
        return view('admin.courses.index', compact('user'));
    }

    /**
     * Show the form for creating a new course
     */
    public function create()
    {
        $user = Auth::user();
        return view('admin.courses.create', compact('user'));
    }

    /**
     * Store a newly created course
     */
    public function store(Request $request)
    {
        // Logic to store course
        return redirect()->route('admin.courses.index')->with('success', 'Curso creado exitosamente');
    }

    /**
     * Display videos dashboard
     */
    public function videosDashboard()
    {
        $user = Auth::user();
        return view('admin.courses.videos.dashboard', compact('user'));
    }

    /**
     * Display quizzes dashboard
     */
    public function quizzesDashboard()
    {
        $user = Auth::user();
        return view('admin.courses.quizzes.dashboard', compact('user'));
    }

    /**
     * Show the form for creating a new quiz
     */
    public function createQuiz()
    {
        $user = Auth::user();
        return view('admin.courses.quizzes.create', compact('user'));
    }

    /**
     * Display users management
     */
    public function usersManagement()
    {
        $user = Auth::user();
        return view('admin.courses.users.index', compact('user'));
    }
} 