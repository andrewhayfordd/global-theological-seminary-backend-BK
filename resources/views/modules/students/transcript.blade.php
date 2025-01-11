<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transcript</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- <link href="https://fonts.googleapis.com/css2?family=Old+Standard+TT:ital,wght@0,400;0,700;1,400&display=swap"

        rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            /* font-family: 'Old Standard TT', serif; */
            font-family: 'Quicksand', sans-serif;
        }
    </style>
</head>

<body>

    <div class="lg:mx-auto max-w-5xl ">
        <div
            class="w-full flex flex-col justify-center border-gray-500 items-center gap-3 mt-5 py-5 border-2 border-dashed">
            <h2 class="text-3xl text-black">
                {{ $school->school_name }}
            </h2>
            <span class="capitalize text-black text-base">Transcript of academic records</span>
        </div>
        <div class="w-full grid grid-cols-2 mt-3 mb-5 gap-2 text-dark">
            <div>Name: <span class="text-gray-700">{{ $student->fname }} {{ $student->mname }}
                    {{ $student->lname }}</span></div>
            <div>Student Id: <span class="text-gray-700"> {{ $student->student_no }}</span></div>
            <div>Year of Entry:<span class="text-gray-700"> {{ $student->admyear }}</span></div>
            {{-- <div>years of completion:<span class="text-gray-700"> </span></div> --}}
            <div>Programme:<span class="text-gray-700"> {{ $prog->prog_desc }}</span></div>
        </div>


        @foreach ($semesters as $semester)
            @if (count($courses["$semester->sem_code"]))
                <table class="w-full mt-2 mb-10 p">
                    <caption class="caption-top bg-purple-600 py-3 text-white w-full text-start text-xl">
                        {{ $semester->sem_desc }}
                    </caption>
                    <thead class="bg-purple-600 text-white">
                        <th class="text-start text-lg font-bold capitalize">Code</th>
                        <th class="text-start text-lg font-bold capitalize">Course title</th>
                        <th class="text-start text-lg font-bold capitalize">credit</th>
                        <th class="text-start text-lg font-bold capitalize">grade</th>
                        <th class="text-start text-lg font-bold capitalize">grade point</th>
                        <th class="text-start text-lg font-bold capitalize"> GPA</th>
                    </thead>
                    <tbody class="text-black">
                        @foreach ($courses["$semester->sem_code"] as $course)
                            <tr>
                                <td class="text-md">{{ $course->course_code }}</td>
                                <td class="text text-md">{{ $course->course_title }}</td>
                                <td class="text text-md">{{ $grade[$course->course_code]['credit'] }}</td>
                                <td class="text text-md">{{ $grade[$course->course_code]['grade'] }}</td>
                                <td class="text text-md">{{ $grade[$course->course_code]['gp'] }}</td>
                                <td class="text text-md">{{ null }}</td>

                            </tr>
                        @endforeach
                        <tr class="text-start border-t-2 border-gray-500 border-dashed">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="">
                                {{ $gpa[$semester->sem_code] }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif
        @endforeach

    </div>

</body>

</html>
