<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseCompetence;

class StudentIntakeCourseCompetenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student intake course competence.
     * @return Renderable
     */
    public function index($id)
    {
        $studentIntakeCourse = StudentIntakeCourse::findorfail($id);
        if (checkRole('student_intake_course', 'edit') == true) {
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeCourse->student_id) . ' Intake Competence Page Opened');
            return view('student::intake.course.competence.index', compact('studentIntakeCourse'));
        } else {
            return redirect()->route('admin.student.intake.course.index', $studentIntakeCourse->student_id)->with('failure', 'This user does not have permission to edit student intake');
        }
    }

    /**
     * Store a newly created student intake course competence in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $studentIntakeCourse = StudentIntakeCourse::findorfail($id);
        if (checkRole('student_intake_course', 'edit') == true) {
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeCourse->student_id) . ' Intake Competence Page Updated');
            $data = $request->all();
            $data['student_intake_course_id'] = $id;
            StudentIntakeCourseCompetence::create($data);
            return redirect()->back()->with('success', 'Competence has been updated');
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified student intake course competence in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $studentIntakeCourse = StudentIntakeCourse::findorfail($id);
        if (checkRole('student_intake_course', 'edit') == true) {
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeCourse->student_id) . ' Intake Competence Page Updated');
            $data = $request->all();
            unset($data['_token'], $data['fee_id']);
            StudentIntakeCourseCompetence::where('student_intake_course_id', $id)->update($data);
            return redirect()->back()->with('success', 'Competence has been updated');
        } else {
            abort(404);
        }
    }
}
