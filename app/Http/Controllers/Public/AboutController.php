<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\TeamMember;

class AboutController extends Controller
{
    public function index()
    {
        $lecturers = TeamMember::active()->lecturers()->get();
        $students  = TeamMember::active()->students()->get();

        $mission = SiteSetting::get('about_mission', 'To provide accessible, free, and confidential legal guidance to members of the ESUT community and the broader public.');
        $vision  = SiteSetting::get('about_vision', 'A society where every person has access to basic legal information and the ability to assert their rights.');

        return view('about.index', compact('lecturers', 'students', 'mission', 'vision'));
    }
}
