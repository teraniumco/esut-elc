<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;

class AboutController extends Controller
{
    public function index()
    {
        $lecturers = TeamMember::active()->lecturers()->get();
        $students  = TeamMember::active()->students()->get();

        return view('about.index', compact('lecturers', 'students'));
    }
}
