<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\LessonVideoViews;
use Illuminate\Http\Request;

class LessonVideoViewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function viewsChart()
    {
        $days = collect(range(0, 13))->map(function ($i) {
            return now()->subDays($i)->format('Y-m-d');
        })->reverse()->values();

        $data = [];

        foreach ($days as $day) {
            $count = LessonVideoViews::whereIn('lesson_id', function ($query) {
                $lessons = $query->select('id')->from('lessons')->whereIn('course_id', function ($query) {
                    $query->select('id')->from('courses')->where('user_id', auth()->user()->id);
                });
                return $lessons;
            })->whereDate('created_at', $day)->count();
            $data[] = $count;
        }

        return response()->json([
            'labels' => $days,
            'data' => $data
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LessonVideoViews  $lessonVideoViews
     * @return \Illuminate\Http\Response
     */
    public function show(LessonVideoViews $lessonVideoViews)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LessonVideoViews  $lessonVideoViews
     * @return \Illuminate\Http\Response
     */
    public function edit(LessonVideoViews $lessonVideoViews)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LessonVideoViews  $lessonVideoViews
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LessonVideoViews $lessonVideoViews)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LessonVideoViews  $lessonVideoViews
     * @return \Illuminate\Http\Response
     */
    public function destroy(LessonVideoViews $lessonVideoViews)
    {
        //
    }
}
