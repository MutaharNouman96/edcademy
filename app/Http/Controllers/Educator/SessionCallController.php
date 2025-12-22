<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\SessionCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\CoursePurchase;
use App\Models\SessionUser;
use App\Models\Schedule;
use App\Models\SessionUsers;
use Illuminate\Http\JsonResponse;

class SessionCallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $educatorId = auth()->user()->id;

        $sessions = Session::with(['students', 'educator'])
            ->where('educator_id', $educatorId)
            ->orderBy('start_time', 'desc')
            ->get();
        return view('crm.educator.sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $students = CoursePurchase::whereIn('course_id', function ($query) {
            return $query->select('id')->from('courses')->where('user_id', auth()->user()->id);
        })
            ->where('status', 'approved')->get();

        return view('crm.educator.sessions.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'meeting_link' => 'required|url',
            'status' => 'in:booked,completed,cancelled',
            'students' => 'sometimes|array|min:1',
            'title' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $educatorId = auth()->user()->id;



            // Create Session
            $session = SessionCall::create([
                'title' => $request->title,
                'educator_id' => $educatorId,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'meeting_link' => $request->meeting_link,
                'status' => $request->status ?? 'booked',
            ]);

            $students  = $request->students;
            // Attach students to session_users
            if (empty($students)) {
                $students = [];
            }
            foreach ($students as $studentId) {
                SessionUsers::create([
                    'title' => $request->title,
                    'session_id' => $session->id,
                    'user_id' => $studentId,
                    'role' => 'student',
                    'status' => 'booked',
                    'payment_status' => 'completed',
                    'is_active' => 1,
                ]);

                //  Add to student schedule
                Schedule::create([
                    'user_id' => $studentId,
                    'title' => $request->title,
                    'description' => 'Scheduled session  with your educator: "' . auth()->user()->name . '"',
                    'start_date' => $request->start_time,
                    'end_date' => $request->end_time,
                ]);
            }

            //  Add to educator’s schedule
            Schedule::create([
                'user_id' => $educatorId,
                'title' => 'Teaching Session: ' . $request->title,
                'description' => 'You have a session with your students',
                'start_date' => $request->start_time,
                'end_date' => $request->end_time,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Session created successfully.',
                'session' => $session
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create session.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
