<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Gradecontroller extends Controller
{
    public function grade($num)
    {
        if ($num >= 80) {
            return "A";
        } elseif ($num >= 75 && $num <= 79) {
            return "B+";
        } elseif ($num >= 70 && $num <= 74) {
            return "B";
        } elseif ($num >= 65 && $num <= 69) {
            return "C+";
        } elseif ($num >= 60 && $num <= 64) {
            return "C";
        } elseif ($num >= 55 && $num <= 59) {
            return "D+";
        } elseif ($num >= 50 && $num <= 54) {
            return "D";
        } elseif ($num >= 0 && $num <= 49) {
            return "E";
        } else {
            return "Invalid Grade";
        }

    }

    public function semestergrade()
    {
        return view('modules.grades.index');
    }
}

