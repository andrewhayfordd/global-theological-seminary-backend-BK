<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    public function index($student_no)
    {
        // Fetch grades for the student
        $grades = DB::table('tblassmain')
            ->join('tblcourse', 'tblassmain.course_code', '=', 'tblcourse.course_code')
            ->join('tblstudent', 'tblassmain.student_code', '=', 'tblstudent.student_no')
            ->select(
                'tblcourse.course_title',
                'tblassmain.total_test',
                'tblassmain.total_exam',
                DB::raw('(tblassmain.total_test + tblassmain.total_exam) as total_score')
            )
            ->where('tblstudent.student_no', $student_no)
            ->where('tblassmain.deleted', '!=', '1')
            ->get()
            ->map(function ($grade) {
                $grade->letter_grade = $this->calculateGrade($grade->total_score);
                return $grade;
            });

        return view('modules/grades.index', compact('grades'));
    }

    private function calculateGrade($num)
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
}
