<?php

namespace Modules\Student\Http\Controllers\Student;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentAnswer;
use Modules\Assignment\Entities\AssignmentQuestion;
use Modules\Assignment\Entities\AssignmentResubmission;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Attendance\Entities\Attendance;
use Modules\Document\Entities\DocumentType;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeCourseTime;
use Modules\Intake\Entities\IntakeUnitTime;
use Modules\OnlineClass\Entities\OnlineClass;
use Modules\OnlineClass\Entities\OnlineClassTeam;
use Modules\Resource\Entities\Resource;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeUnit;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Display a listing of the courses.
     * @return Renderable
     */
    public function index()
    {
        activityLog('Student', 'Opened Course menu from web');
        $id = Auth::guard('student')->user()->id;
        $courses = StudentIntakeCourse::where('student_id', $id)->whereIn('status', [1, 3])->get();
        foreach ($courses as $key => $value) {
            $courses[$key]['intakeCourseTime'] = IntakeCourseTime::where('intake_course_id', $value->intake_course_id)->where('status', 1)->get();
            $courses[$key]['trainer'] = IntakeCourse::where('id', $value->intake_course_id)->first();
        }
        return view('student::student.course.index', compact('courses'))->with('no', 1);
    }

    /* Student Unit List */
    public function unit($id)
    {
        $course = StudentIntakeCourse::where('id', $id)->whereIn('status', [1, 3])->first();
        $student_id = Auth::guard('student')->user()->id;
        if ($course && $course->student_id == $student_id) {
            activityLog('Student', 'Opened units list from web');
            $units = StudentIntakeUnit::where('student_intake_course_id', $id)->whereIn('status', [1, 3])->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
            foreach ($units as $key => $value) {
                $units[$key]['intakeUnitTime'] = IntakeUnitTime::where('intake_unit_id', $value->intake_unit_id)->where('status', 1)->get();
            }
            return view('student::student.unit.index', compact('units', 'course'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Resource List By Unit Id */
    public function resource($id)
    {
        $studentIntake = StudentIntakeUnit::where('id', $id)->first();
        $student_id = Auth::guard('student')->user()->id;
        if ($studentIntake && $studentIntake->studentIntakeCourse->student_id == $student_id) {
            $resources = Resource::where('unit_id', $studentIntake->intakeUnit->unit_id)->where('user_type', 'Student')->where('status', 1)->get();
            activityLog('Student', 'Opened resources of ' . $studentIntake->intakeUnit->unit->name . 'from web');
            return view('student::student.unit.resource.index', compact('resources', 'studentIntake'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Assignment List By Unit Id */
    public function assigment($id)
    {
        $studentIntake = StudentIntakeUnit::findorfail($id);
        $assignments = Assignment::where('intake_unit_id', $studentIntake->intake_unit_id)->where('status', 1)->orderBy('id', 'desc')->get();
        foreach ($assignments as $key => $value) {
            $student_id = Auth::guard('student')->user()->id;
            if ($studentIntake->due_date != NULL) {
                $student_due_date = $studentIntake->due_date;
            } else {
                if ($studentIntake->intakeUnit->due_date != NULL) {
                    $student_due_date = $studentIntake->intakeUnit->due_date;
                } else {
                    $student_due_date = $value->due_date;
                }
            }
            $resubmission_due_date = false;
            $submission = AssignmentSubmission::where('assignment_id', $value->id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
            if ($submission) {
                if ($submission->assignment_grade_id == NULL) {
                    $assignments[$key]['grade'] = 'Waiting to be Graded';
                } else {
                    $assignments[$key]['grade'] = $submission->assignmentGrade->name;
                }
                if ($submission->assignment_resubmission_idsion > 0) {
                    $resubmission = AssignmentResubmission::find($submission->assignment_resubmission_id);
                    if ($resubmission->due_date != NULL) {
                        $due_date = $resubmission->due_date;
                        $resubmission_due_date = true;
                    } else {
                        $due_date = $student_due_date;
                    }
                } else {
                    $due_date = $student_due_date;
                }
            } else {
                $due_date = $student_due_date;
                if ($due_date < date('Y-m-d')) {
                    $assignments[$key]['grade'] = 'Over Due';
                } else {
                    $assignments[$key]['grade'] = 'Due';
                }
            }
            $assignments[$key]['submission_count'] = AssignmentSubmission::where('assignment_id', $value->id)->where('student_id', $student_id)->orderBy('id', 'desc')->count();
            $assignments[$key]['resubmission'] = AssignmentResubmission::where('assignment_id', $value->id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
        }
        // dd($assignments);
        activityLog('Student', 'Opened assignments of ' . $studentIntake->intakeUnit->unit->name . 'from web');
        return view('student::student.unit.assignment.index', compact('assignments', 'studentIntake', 'due_date', 'resubmission_due_date'))->with('no', 1);
    }

    /* Student Unit Assignment Questions List */
    public function assigmentQuestion($id, $student_intake_id)
    {
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        $assignment = Assignment::find($id);
        if ($studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id && $assignment->type == 'question') {
            activityLog('Student', 'Opened assignment questions list from web');
            $questions = AssignmentQuestion::where('assignment_id', $id)->orderBy('id', 'asc')->get();
            $resubmission = AssignmentResubmission::where('assignment_id', $id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
            if ($resubmission) $resubmission_id = $resubmission->id;
            else $resubmission_id = 0;
            return view('student::student.unit.assignment.question.index', compact('assignment', 'questions', 'studentIntakeUnit', 'resubmission_id'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Assignment MCQ List */
    public function assigmentMCQ($id, $student_intake_id)
    {
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        $assignment = Assignment::find($id);
        if ($studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id && $assignment->type == 'mcq') {
            activityLog('Student', 'Opened assignment MCQ list from web');
            $questions = AssignmentQuestion::where('assignment_id', $id)->orderBy('id', 'asc')->get();
            $resubmission = AssignmentResubmission::where('assignment_id', $id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
            if ($resubmission) $resubmission_id = $resubmission->id;
            else $resubmission_id = 0;
            return view('student::student.unit.assignment.mcq.index', compact('assignment', 'questions', 'studentIntakeUnit', 'resubmission_id'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Assignment Question Submit */
    public function submitAssignmentQuestion(Request $request, $id, $student_intake_id)
    {
        $data = $request->all();
        $answers = $data['answers'];
        $data['assignment_id'] = $id;
        $data['student_id'] = Auth::guard('student')->user()->id;
        $submission = AssignmentSubmission::create($data);
        foreach ($answers as $key => $value) {
            AssignmentAnswer::create([
                'assignment_question_id' => $key,
                'answer' => $value,
                'assignment_submission_id' => $submission->id
            ]);
        }
        if ($data['assignment_resubmission_id'] > 0) {
            AssignmentResubmission::where('id', $data['assignment_resubmission_id'])->update(['status' => 3]);
        }
        activityLog('Student', 'Assignment has been submitted');
        return redirect()->route('student.submission.index', [$id, $student_intake_id])->with('success', 'Assignment has been submitted');
    }

    /* Student Unit Assignment MCQ Submit */
    public function submitAssignmentMCQ(Request $request, $id, $student_intake_id)
    {
        $data = $request->all();
        $questions = $data['questions'];
        $data['assignment_id'] = $id;
        $data['student_id'] = Auth::guard('student')->user()->id;
        $submission = AssignmentSubmission::create($data);
        foreach ($questions as $key => $value) {
            AssignmentAnswer::create([
                'assignment_question_id' => $key,
                'answer' => $value,
                'assignment_submission_id' => $submission->id,
            ]);
        }
        if ($data['assignment_resubmission_id'] > 0) {
            AssignmentResubmission::where('id', $data['assignment_resubmission_id'])->update(['status' => 3]);
        }
        activityLog('Student', 'Assignment has been submitted');
        return redirect()->route('student.submission.mcq.index', [$submission->id, $student_intake_id])->with('success', 'Assignment has been submitted');
    }

    /* Student Unit Assignment Submission List By Unit Id */
    public function submission($id, $student_intake_id)
    {
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        if ($studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id) {
            activityLog('Student', 'Opened assignments submission list from web');
            $assignment = Assignment::find($id);
            $submissions = AssignmentSubmission::where('assignment_id', $id)->where('student_id', $student_id)->orderBy('id', 'desc')->get();
            foreach ($submissions as $key => $value) {
                if ($value->assignmentSubmissionGrade == NULL) {
                    $submissions[$key]['show_to_student'] = 0;
                } else {
                    $submissions[$key]['graded_file'] = $value->assignmentSubmissionGrade->path;
                    $submissions[$key]['show_to_student'] = $value->assignmentSubmissionGrade->show_to_student;
                }
            }
            $resubmission = AssignmentResubmission::where('assignment_id', $id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
            $last_submission = AssignmentSubmission::where('assignment_id', $id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
            if ($last_submission && $last_submission->assignment_grade_id != NULL) {
                if ($resubmission) {
                    if ($resubmission->status == 2 ||  $resubmission->status == 3) {
                        $resubmission_request = true;
                    } else {
                        $resubmission_request = false;
                    }
                } else {
                    $resubmission_request = true;
                }
            } else {
                $resubmission_request = false;
            }
            return view('student::student.unit.assignment.submission.index', compact('submissions', 'assignment', 'studentIntakeUnit', 'resubmission', 'resubmission_request'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Assignment Question Submit List*/
    public function questionSubmission($id, $student_intake_id)
    {
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        $submission = AssignmentSubmission::find($id);
        if ($studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id && $submission->student_id == $student_id && $submission->assignment->type == 'question') {
            activityLog('Student', 'Opened assignments submission list from web');
            $answers = AssignmentAnswer::where('assignment_submission_id', $id)->get();
            return view('student::student.unit.assignment.submission.question.index', compact('submission', 'studentIntakeUnit', 'answers'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Assignment MCQ Submit List*/
    public function mcqSubmission($id, $student_intake_id)
    {
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        $submission = AssignmentSubmission::find($id);
        if ($studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id && $submission->student_id == $student_id && $submission->assignment->type == 'mcq') {
            activityLog('Student', 'Opened assignments submission list from web');
            $answers = AssignmentAnswer::where('assignment_submission_id', $id)->get();
            return view('student::student.unit.assignment.submission.mcq.index', compact('submission', 'studentIntakeUnit', 'answers'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Create Student Unit Assignment Submission */
    public function createSubmission($id, $student_intake_id)
    {
        $assignment = Assignment::find($id);
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $document_types = DocumentType::where('status', 1)->get();
        $student_id = Auth::guard('student')->user()->id;
        if ($assignment && $studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id) {
            $resubmission = AssignmentResubmission::where('assignment_id', $id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
            $today = date('Y-m-d');
            if ($resubmission) {
                $resubmission_id = $resubmission->id;
                $resubmission_date = $resubmission->due_date;
            } else {
                $resubmission_id = 0;
                $resubmission_date = NULL;
            }
            if ($resubmission_date != NULL) {
                $due_date = $resubmission_date;
            } else {
                if ($studentIntakeUnit->due_date != NULL) {
                    $due_date = $studentIntakeUnit->due_date;
                } else {
                    if ($studentIntakeUnit->intakeUnit->due_date != NULL) {
                        $due_date = $studentIntakeUnit->intakeUnit->due_date;
                    } else {
                        $due_date = $assignment->due_date;
                    }
                }
            }
            $student_submission_allow =  Auth::guard('student')->user()->allow_submission_after_due_date;
            if ($student_submission_allow == 'on') {
                $allow = true;
            } else {
                if ($due_date < $today) {
                    if ($resubmission_id > 0) {
                        $allow = true;
                    } else {
                        $allow = false;
                    }
                } else {
                    $allow = true;
                }
            }
            if ($allow == true) {
                activityLog('Student', 'Opened assignments submission add page');
                return view('student::student.unit.assignment.submission.create', compact('assignment', 'studentIntakeUnit', 'resubmission_id', 'document_types'));
            } else {
                return back()->with('failure', 'Due date has passed');
            }
        } else {
            return abort(404);
        }
    }

    /* Submit Student Unit Assignment */
    public function storeSubmission(Request $request, $id, $student_intake_id)
    {
        $data = $request->all();
        if ($data['document_type'] == 'Video Link') {
            $data['path'] = $data['video_link'];
        } else {
            $imageName = time() . '.' . request()->path->getClientOriginalExtension();
            request()->path->move(public_path('/images/assignments/submissions'), $imageName);
            $data['path'] = 'images/assignments/submissions/' . $imageName;
        }
        $data['assignment_id'] = $id;
        $data['student_id'] = Auth::guard('student')->user()->id;
        AssignmentSubmission::create($data);
        if ($data['assignment_resubmission_id'] > 0) {
            AssignmentResubmission::where('id', $data['assignment_resubmission_id'])->update(['status' => 3]);
        }
        activityLog('Student', 'Assignment has been submitted');
        return redirect()->route('student.submission.index', [$id, $student_intake_id])->with('success', 'Assignment has been submitted');
    }

    /* Request for Unit Assignment Resubmission */
    public function requestResubmission($id)
    {
        $student_id = Auth::guard('student')->user()->id;
        AssignmentResubmission::create([
            'student_id' => $student_id,
            'assignment_id' => $id
        ]);
        activityLog('Student', 'Request for assignment resubmission created');
        return redirect()->back()->with('success', 'Request for assignment resubmission has been sent');
    }

    /* Student Unit Online Zoom Classes */
    public function onlineClass($id)
    {
        $studentIntake = StudentIntakeUnit::find($id);
        $student_id = Auth::guard('student')->user()->id;
        if ($studentIntake && $studentIntake->studentIntakeCourse->student_id == $student_id) {
            $zooms = OnlineClass::where('intake_unit_id', $studentIntake->intake_unit_id)->where('status', 1)->get();
            foreach ($zooms as $key => $value) {
                $recording = $value->recording;
                if ($recording) {
                    $zooms[$key]['recording'] = $recording->play_url;
                    $zooms[$key]['password'] = $recording->password;
                } else {
                    $zooms[$key]['recording'] = NULL;
                    $zooms[$key]['password'] = NULL;
                }
            }
            activityLog('Student', 'Opened zoom classes of ' . $studentIntake->intakeUnit->unit->name . 'from web');
            return view('student::student.unit.onlineclass.index', compact('zooms', 'studentIntake'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Online Team Classes */
    public function onlineClassTeam($id)
    {
        $studentIntake = StudentIntakeUnit::find($id);
        $student_id = Auth::guard('student')->user()->id;
        if ($studentIntake && $studentIntake->studentIntakeCourse->student_id == $student_id) {
            $teams = OnlineClassTeam::where('intake_unit_id', $studentIntake->intake_unit_id)->where('status', 1)->get();
            activityLog('Student', 'Opened teams classes of ' . $studentIntake->intakeUnit->unit->name . 'from web');
            return view('student::student.unit.teams.index', compact('teams', 'studentIntake'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* List of student attendace by Intake Unit */
    public function attendance($id)
    {
        $studentIntake = StudentIntakeUnit::find($id);
        $student_id = Auth::guard('student')->user()->id;
        if ($studentIntake && $studentIntake->studentIntakeCourse->student_id == $student_id && attendanceSetting('display_student_attendance')=='on') {
            $period = new DatePeriod(
                new DateTime($studentIntake->intakeUnit->starting_date),
                new DateInterval('P1D'),
                new DateTime($studentIntake->intakeUnit->ending_date)
            );
            $dateArray = [];
            foreach ($period as $key => $value) {
                $dateArray[] = $value->format('Y-m-d');
                foreach ($dateArray as $date) {
                    $studentAttendance = Attendance::where('student_id', $student_id)->where('intake_unit_id', $studentIntake->intake_unit_id)->where('date', $date)->first();
                    if ($studentAttendance) {
                        $attendance = 'Present';
                    } else {
                        $attendance = 'Absent';
                    }
                    $dateArray[$key] = [
                        'date' => $date,
                        'attendance' => $attendance
                    ];
                }
            }
            activityLog('Student', 'Opened attendance of ' . $studentIntake->intakeUnit->unit->name . 'from web');
            return view('student::student.unit.attendance.index', compact('student_id', 'dateArray', 'studentIntake'))->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
