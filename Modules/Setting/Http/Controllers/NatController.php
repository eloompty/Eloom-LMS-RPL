<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Company\Entities\Company;
use Modules\Course\Entities\Unit;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentDeliverySite;
use ZipArchive;

class NatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the NAT upload.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin', 'Opened Avetimis Export Menu');
            return view('setting::nat.index');
        } else {
            return abort(404);
        }
    }

    public function export(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $companies = Company::all();
        $sites = StudentDeliverySite::join('students', 'students.id', '=', 'student_delivery_sites.student_id')
            ->join('student_intake_courses', 'students.id', '=', 'student_intake_courses.student_id')
            ->join('student_intake_units', 'student_intake_units.student_intake_course_id', '=', 'student_intake_courses.id')
            ->whereBetween('student_intake_units.starting_date', [$from, $to])
            ->whereBetween('student_intake_units.ending_date', [$from, $to])
            ->where('students.is_enrolled', 1)->where('students.status', 1)
            ->distinct()->get('student_delivery_sites.company_delivery_site_id');
        $courses = IntakeCourse::join('intake_units', 'intake_units.intake_course_id', '=', 'intake_courses.id')
            ->where('intake_courses.status', 1)->whereBetween('intake_units.starting_date', [$from, $to])->whereBetween('intake_units.ending_date', [$from, $to])
            ->distinct()->get('intake_courses.course_id');
        $units = Student::join('student_intake_courses', 'students.id', '=', 'student_intake_courses.student_id')
            ->join('student_intake_units', 'student_intake_units.student_intake_course_id', '=', 'student_intake_courses.id')
            ->join('intake_units', 'intake_units.id', '=', 'student_intake_units.intake_unit_id')
            ->whereBetween('student_intake_units.starting_date', [$from, $to])
            ->whereBetween('student_intake_units.ending_date', [$from, $to])
            ->where('students.is_enrolled', 1)->where('students.status', 1)
            ->where('student_intake_units.status', 1)
            ->where('student_intake_units.is_complete', 1)
            ->distinct()->get('intake_units.unit_id');
        $students = Student::join('student_intake_courses', 'students.id', '=', 'student_intake_courses.student_id')
            ->join('student_intake_units', 'student_intake_units.student_intake_course_id', '=', 'student_intake_courses.id')
            ->whereBetween('student_intake_units.starting_date', [$from, $to])
            ->whereBetween('student_intake_units.ending_date', [$from, $to])
            ->where('students.is_enrolled', 1)->where('students.status', 1)
            ->where('student_intake_units.status', 1)
            ->where('student_intake_units.is_complete', 1)
            ->distinct()->get('students.id');
        $disable_flags = Student::join('student_intake_courses', 'students.id', '=', 'student_intake_courses.student_id')
            ->join('student_intake_units', 'student_intake_units.student_intake_course_id', '=', 'student_intake_courses.id')
            ->whereBetween('student_intake_units.starting_date', [$from, $to])
            ->whereBetween('student_intake_units.ending_date', [$from, $to])
            ->where('students.is_enrolled', 1)->where('students.status', 1)->where('student_intake_units.is_complete', 1)->where('disability_flag', 'Y')
            ->distinct()->get('students.id');
        $prior_education_flags = Student::join('student_intake_courses', 'students.id', '=', 'student_intake_courses.student_id')
            ->join('student_intake_units', 'student_intake_units.student_intake_course_id', '=', 'student_intake_courses.id')
            ->whereBetween('student_intake_units.starting_date', [$from, $to])
            ->whereBetween('student_intake_units.ending_date', [$from, $to])
            ->where('students.is_enrolled', 1)->where('students.status', 1)->where('student_intake_units.is_complete', 1)->where('prior_education', 'Y')
            ->distinct()->get('students.id');

             //add loop to get student unis 
        $student_enrolled_units = [];
        $stdCodeInIntakeUnit = [];
        foreach ($students as $student) {
            $student_courses = $student->intake->where('status', 1);
            foreach ($student_courses as $course) {
                $unitsNew = $course->studentIntakeUnit->where('status', 1)->where('is_complete', 1)->where('outcome', '<>', NULL)->whereBetween('ending_date', [$from, $to]);
                foreach ($unitsNew as $unitn) {
                    $student_enrolled_units[] = $unitn;
                    $stdCodeInIntakeUnit[] = $unitn->intakeUnit->unit->code;
                }
            }
        }
        
        if ($request->export_type == 'csv') {
            // CSV Nat00010
            $fileName1 = 'NAT00010_.csv';
            if (is_dir('nat') == false) {
                mkdir(public_path('nat'), 0700);
            }
            if (is_dir('nat/csv') == false) {
                mkdir(public_path('nat/csv'), 0700);
            }
            $path = public_path('nat/csv/');

            $company_columns = array('Code', 'Name', 'Filler', 'Contact', 'Phone', 'Fax', 'Email');
            $file1 = fopen($path . $fileName1, 'w');
            fputcsv($file1, $company_columns);

            foreach ($companies as $company) {
                $row['Code'] = $company->rto_no;
                $row['Name'] = $company->company_name;
                $row['Filler'] = '';
                $row['Contact'] = $company->company_ceo;
                $row['Phone'] = $company->phone;
                $row['Fax'] = '';
                $row['Email'] = $company->email;

                fputcsv($file1, array($row['Code'], $row['Name'], $row['Filler'], $row['Contact'], $row['Phone'], $row['Fax'], $row['Email']));
            }

            fclose($file1);

            // CSV Nat00020
            $fileName2 = 'NAT00020_.csv';
            $site_columns = array('TrainingId', 'Code', 'Name', 'Postcode', 'State', 'Suburb', 'Country');
            $file2 = fopen($path . $fileName2, 'w');
            fputcsv($file2, $site_columns);

            foreach ($sites as $site) {
                $row['TrainingId'] = $site->companyDeliverySite->company->rto_no;
                $row['Code'] = $site->companyDeliverySite->address->zip_code;
                $row['Name'] = $site->companyDeliverySite->site_name;
                $row['Postcode'] = $site->companyDeliverySite->address->zip_code;
                $row['State'] = $site->companyDeliverySite->address->state;
                $row['Suburb'] = $site->companyDeliverySite->address->suburb;
                $row['Country'] = '1101';

                fputcsv($file2, array($row['TrainingId'], $row['Code'], $row['Name'], $row['Postcode'], $row['State'], $row['Suburb'], $row['Country']));
            }

            fclose($file2);

            // CSV Nat00030
            $fileName3 = 'NAT00030_.csv';
            $course_columns = array('Code', 'Name', 'Hours', 'Filler');
            $file3 = fopen($path . $fileName3, 'w');
            fputcsv($file3, $course_columns);

            foreach ($courses as $course) {
                $row['Code'] = $course->course->course_code;
                $row['Name'] = $course->course->course_name;
                $row['Hours'] = $course->course->hours;
                $row['Filler'] = '';

                fputcsv($file3, array($row['Code'], $row['Name'], $row['Hours'], $row['Filler']));
            }

            fclose($file3);

            // CSV Nat00060
            $fileName4 = 'NAT00060_.csv';
            $unit_columns = array('Code', 'Name', 'EducationField', 'VetFlag', 'Hours');
            $file4 = fopen($path . $fileName4, 'w');
            fputcsv($file4, $unit_columns);

            $unitCodeNew = [];
            foreach ($units as $unit) {
                $a_unit = Unit::find($unit);
                $row['Code'] = $a_unit[0]->code;
                $row['Name'] = $a_unit[0]->name;
                $row['EducationField'] = $a_unit[0]->education_field;
                $row['VetFlag'] = 'Y';
                $row['Hours'] = $a_unit[0]->hours;

                $searchNew = in_array($row['Code'], $stdCodeInIntakeUnit);
                if ($searchNew) {
                    $search = in_array($row['Code'], $unitCodeNew);
                    if (!$search) {
                        $unitCodeNew[] = $row['Code'];
                        fputcsv($file4, array($row['Code'], $row['Name'], $row['EducationField'], $row['VetFlag'], $row['Hours']));
                    }
                }
            }

            fclose($file4);

            // CSV Nat00080
            $fileName5 = 'NAT00080_.csv';
            $student_columns = array(
                'Code', 'Name', 'SchoolLevel', 'Gender', 'DOB', 'Postcode', 'Indigenous', 'Language', 'LabourForce', 'Country', 'DisabilityFlag', 'PriorEducationFlag', 'SchoolFlag',
                'AddressSuburb', 'USI', 'AddressState', 'AddressProperty', 'AddressUnitNo', 'AddressStreetNo', 'AddressStreetName', 'SurveyContactStatus', 'Statistical1', 'Statistical2'
            );
            $file5 = fopen($path . $fileName5, 'w');
            fputcsv($file5, $student_columns);

            foreach ($students as $unique) {
                $student = Student::find($unique->id);
                if ($student->funding_source_national == 31 || $student->funding_source_national == 32) {
                    $zip_code = 'OSPC';
                    $suburb = 'NOT SPECIFIED';
                    $state = '99';
                } else {
                    $zip_code = $student->address->zip_code;
                    $suburb = $student->address->suburb;
                    $state = $student->address->state;
                }
                if ($student->address->street_no == NULL) {
                    $street_no = 'NOT SPECIFIED';
                } else {
                    $street_no = $student->address->street_no;
                }
                if ($student->address->street_address == NULL) {
                    $street_address = 'NOT SPECIFIED';
                } else {
                    $street_address = $student->address->street_address;
                }
                $row['Code'] = $student->id_no;
                $row['Name'] = $student->family_name . ', ' . $student->first_name;
                $row['SchoolLevel'] = $student->school_level_identifier;
                $row['Gender'] = $student->gender;
                $row['DOB'] = $student->date_of_birth;
                $row['Postcode'] = $zip_code;
                $row['Indigenous'] = $student->indigenous_status_identifier;
                $row['Language'] = $student->language_identifier;
                $row['LabourForce'] = $student->labour_force_status_identifier;
                $row['Country'] = $student->country_id;
                $row['DisabilityFlag'] = $student->disability_flag;
                $row['PriorEducationFlag'] = $student->prior_education;
                $row['SchoolFlag'] = $student->at_school;
                $row['AddressSuburb'] = $suburb;
                $row['USI'] = $student->unique_student_identifier;
                $row['AddressState'] = $state;
                $row['AddressProperty'] = $student->address->building_number;
                $row['AddressUnitNo'] = $student->address->flat_unit;
                $row['AddressStreetNo'] = $street_no;
                $row['AddressStreetName'] = $street_address;
                $row['SurveyContactStatus'] = $student->survey_contact_status;
                $row['Statistical1'] = $student->statistical_area_level_1_identifier;
                $row['Statistical2'] = $student->statistical_area_level_2_identifier;

                fputcsv($file5, array(
                    $row['Code'], $row['Name'], $row['SchoolLevel'], $row['Gender'], $row['DOB'], $row['Postcode'], $row['Indigenous'], $row['Language'], $row['LabourForce'], $row['Country'], $row['DisabilityFlag'], $row['PriorEducationFlag'], $row['SchoolFlag'],
                    $row['AddressSuburb'], $row['USI'], $row['AddressState'], $row['AddressProperty'], $row['AddressUnitNo'], $row['AddressStreetNo'], $row['AddressStreetName'], $row['SurveyContactStatus'], $row['Statistical1'], $row['Statistical2']
                ));
            }

            fclose($file5);

            // CSV Nat00085
            $fileName6 = 'NAT00085_.csv';
            $student_columns = array(
                'Code', 'Title', 'FirstName', 'LastName', 'AddressProperty', 'AddressUnitNo', 'AddressStreetNo', 'AddressStreetName',
                'AddressDeliveryBox', 'Suburb', 'Postcode', 'State', 'PhoneHome', 'PhoneWork', 'PhoneMobile', 'Email', 'EmailAlternative'
            );
            $file6 = fopen($path . $fileName6, 'w');
            fputcsv($file6, $student_columns);

            foreach ($students as $unique) {
                $student = Student::find($unique->id);
                if ($student->funding_source_national == 31 || $student->funding_source_national == 32) {
                    $zip_code = 'OSPC';
                    $suburb = NULL;
                    $state = '99';
                } else {
                    $zip_code = $student->address->zip_code;
                    $suburb = $student->address->suburb;
                    $state = $student->address->state;
                }
                if ($student->address->street_no == NULL) {
                    $street_no = 'NOT SPECIFIED';
                } else {
                    $street_no = $student->address->street_no;
                }
                if ($student->address->street_address == NULL) {
                    $street_address = 'NOT SPECIFIED';
                } else {
                    $street_address = $student->address->street_address;
                }
                $row['Code'] = $student->id_no;
                $row['Title'] = $student->salutation;
                $row['FirstName'] = $student->first_name;
                $row['LastName'] = $student->family_name;
                $row['AddressProperty'] = $student->address->building_number;
                $row['AddressUnitNo'] = $student->address->flat_unit;
                $row['AddressStreetNo'] = $street_no;
                $row['AddressStreetName'] = $street_address;
                $row['AddressDeliveryBox'] = $student->address->p_o_box;
                $row['Suburb'] = $suburb;
                $row['Postcode'] = $zip_code;
                $row['State'] = $state;
                $row['PhoneHome'] = $student->phone;
                $row['PhoneWork'] = $student->phone_work;
                $row['PhoneMobile'] = $student->mobile;
                $row['Email'] = $student->email;
                $row['EmailAlternative'] = $student->email_alternative;

                fputcsv($file6, array(
                    $row['Code'], $row['Title'], $row['FirstName'], $row['LastName'], $row['AddressProperty'], $row['AddressUnitNo'], $row['AddressStreetNo'], $row['AddressStreetName'],
                    $row['AddressDeliveryBox'], $row['Suburb'], $row['Postcode'], $row['State'], $row['PhoneHome'], $row['PhoneWork'], $row['PhoneMobile'], $row['Email'], $row['EmailAlternative']
                ));
            }

            fclose($file6);

            // CSV Nat00090
            $fileName7 = 'NAT00090_.csv';
            $disable_columns = array('Code', 'DisabilityCode');
            $file7 = fopen($path . $fileName7, 'w');
            fputcsv($file7, $disable_columns);

            foreach ($disable_flags as $flag) {
                $student = Student::find($flag->id);
                $row['Code'] = $student->id_no;
                $row['DisabilityCode'] = $student->disability_identifier;

                fputcsv($file7, array($row['Code'], $row['DisabilityCode']));
            }

            fclose($file7);

            // CSV Nat00100
            $fileName8 = 'NAT00100_.csv';
            $prior_education_columns = array('Code', 'EducationCode');
            $file8 = fopen($path . $fileName8, 'w');
            fputcsv($file8, $prior_education_columns);

            foreach ($prior_education_flags as $flag) {
                $student = Student::find($flag->id);
                $row['Code'] = $student->id_no;
                $row['EducationCode'] = $student->prior_education_achievement_identifier;

                fputcsv($file8, array($row['Code'], $row['EducationCode']));
            }

            fclose($file8);

            // CSV Nat00120
            $fileName9 = 'NAT00120_.csv';
            foreach ($students as $student) {
                $student_courses = $student->intake->where('status', 1);
                foreach ($student_courses as $course) {
                    $units = $course->studentIntakeUnit->where('status', 1)->where('is_complete', 1)->whereBetween('ending_date', [$from, $to]);
                    foreach ($units as $unit) {
                        $student_units[] = $unit;
                    }
                }
            }
            $student_unit_columns = array(
                'TrainingOrganisation', 'Code', 'DeliveryLocation', 'ModuleCode', 'CourseCode', 'StartDate', 'EndDate', 'DeliveryMode', 'OutcomeId', 'FundingSource',
                'CommencingCourse', 'TrainingContract', 'ClientId', 'StudyReason', 'VETSchoolFlag', 'FundingIdentifer', 'SchoolTypeIdentifier', 'StateOutcome', 'StateFunding', 'ClientTuition', 'FeeExemption',
                'PurchaseContract', 'PurchaseContractSchedule', 'HoursAttend', 'AssocaiatedCourseIdentifier', 'ScheduleHours', 'PredominantDeliveryMode'
            );
            $file9 = fopen($path . $fileName9, 'w');
            fputcsv($file9, $student_unit_columns);

            foreach ($student_units as $student_unit) {
                $site = $student_unit->studentIntakeCourse->student->deliverySite->where('status', 1)->first();
                if ($site) {
                    $delivery_location = $site->companyDeliverySite->id;
                } else {
                    $delivery_location = 0;
                }
                $company = Company::first();
                $row['TrainingOrganisation'] = $company->rto_no;
                $row['DeliveryLocation'] = $delivery_location;
                $row['Code'] = $student_unit->studentIntakeCourse->student->id_no;
                $row['ModuleCode'] = $student_unit->intakeUnit->unit->code;
                $row['CourseCode'] = $student_unit->studentIntakeCourse->intakeCourse->course->course_code;
                $row['StartDate'] = $student_unit->starting_date;
                $row['EndDate'] = $student_unit->ending_date;
                $row['DeliveryMode'] = $student_unit->delivery_mode;
                $row['OutcomeId'] = $student_unit->outcome;
                $row['FundingSource'] = $student_unit->funding_source_national;
                $row['CommencingCourse'] = $student_unit->commencing;
                $row['TrainingContract'] = '';
                $row['ClientId'] = '';
                $row['StudyReason'] = $student_unit->studentIntakeCourse->student->study_reason;
                $row['VETSchoolFlag'] = 'N';
                $row['FundingIdentifer'] = $student_unit->studentIntakeCourse->student->specific_funding_identifier;
                $row['SchoolTypeIdentifier'] = $student_unit->studentIntakeCourse->student->school_type_identifier;
                $row['StateOutcome'] = '';
                $row['StateFunding'] = $student_unit->studentIntakeCourse->student->funding_source_state_training_authority;
                $row['ClientTuition'] = '';
                $row['FeeExemption'] = '';
                $row['PurchaseContract'] = '';
                $row['PurchaseContractSchedule'] = '';
                $row['HoursAttend'] = '';
                $row['AssocaiatedCourseIdentifier'] = '';
                $row['ScheduleHours'] = $student_unit->intakeUnit->unit->hours;;
                $row['PredominantDeliveryMode'] = $student_unit->predominant_delivery_mode;


                fputcsv($file9, array(
                    $row['TrainingOrganisation'], $row['Code'], $row['DeliveryLocation'], $row['ModuleCode'], $row['CourseCode'], $row['StartDate'], $row['EndDate'], $row['DeliveryMode'], $row['OutcomeId'], $row['FundingSource'],
                    $row['CommencingCourse'], $row['TrainingContract'], $row['ClientId'], $row['StudyReason'], $row['VETSchoolFlag'], $row['FundingIdentifer'], $row['SchoolTypeIdentifier'], $row['StateOutcome'], $row['StateFunding'], $row['ClientTuition'], $row['FeeExemption'],
                    $row['PurchaseContract'], $row['PurchaseContractSchedule'], $row['HoursAttend'], $row['AssocaiatedCourseIdentifier'], $row['ScheduleHours'], $row['PredominantDeliveryMode']
                ));
            }

            fclose($file9);


            // CSV Nat00130
            $fileName10 = 'NAT00130_.csv';
            $student_unit_columns = array('TrainingOrganisation', 'CourseCode', 'Code', 'DateCompleted', 'QualificationIssued', 'ParchmentIssueDate', 'ParchmentNumber');
            $file10 = fopen($path . $fileName10, 'w');
            fputcsv($file10, $student_unit_columns);

            $student_intakes = [];
            foreach ($students as $student) {
                $student_courses = $student->intake->where('status', 1);
                foreach ($student_courses as $course) {
                    if ($course->studentIntakeCourseCompetence != NULL && $course->studentIntakeCourseCompetence->award_status == 'Y') {
                        $student_intakes[] = $course;
                    }
                }
            }

            foreach ($student_intakes as $student_course) {
                $student_units = $student_course->studentIntakeUnit->where('status', 1)->where('is_complete', 1)->whereBetween('ending_date', [$from, $to]);
                foreach ($student_units as $student_unit) {
                    $su[] = $student_unit->ending_date;
                }
                $endingDate = collect($su)->max();
                if ($student_course->studentIntakeCourseCompetence == NULL) {
                    $issue_date = NULL;
                    $number = NULL;
                    $qualify = 'N';
                } else {
                    $issue_date = $student_course->studentIntakeCourseCompetence->parchment_issue_date;
                    $number = $student_course->studentIntakeCourseCompetence->parchment_no;
                    $qualify = $student_course->studentIntakeCourseCompetence->award_status;
                }
                $company = Company::first();
                $row['TrainingOrganisation'] = $company->rto_no;
                $row['CourseCode'] = $student_course->intakeCourse->course->course_code;
                $row['Code'] = $student_course->student->id_no;
                $row['DateCompleted'] = $endingDate;
                $row['QualificationIssued'] = $qualify;
                $row['ParchmentIssueDate'] = $issue_date;
                $row['ParchmentNumber'] = $number;

                fputcsv($file10, array($row['TrainingOrganisation'], $row['CourseCode'], $row['Code'], $row['DateCompleted'], $row['QualificationIssued'], $row['ParchmentIssueDate'], $row['ParchmentNumber']));
            }

            fclose($file10);

            $company_vic = Company::first();
            if ($company_vic->address->state == '02') {
                copy(public_path('nat/csv/NAT00010_.csv'), public_path('nat/csv/NAT00010A_.csv'));
                copy(public_path('nat/csv/NAT00030_.csv'), public_path('nat/csv/NAT00030A_.csv'));
                $filePaths = [
                    public_path('nat/csv/NAT00010_.csv'),
                    public_path('nat/csv/NAT00010A_.csv'),
                    public_path('nat/csv/NAT00020_.csv'),
                    public_path('nat/csv/NAT00030_.csv'),
                    public_path('nat/csv/NAT00030A_.csv'),
                    public_path('nat/csv/NAT00060_.csv'),
                    public_path('nat/csv/NAT00080_.csv'),
                    public_path('nat/csv/NAT00085_.csv'),
                    public_path('nat/csv/NAT00090_.csv'),
                    public_path('nat/csv/NAT00100_.csv'),
                    public_path('nat/csv/NAT00120_.csv'),
                    public_path('nat/csv/NAT00130_.csv'),
                    // Add more file paths as needed
                ];
            } else {
                // Combine all the nat files and compress to zip
                // Define the list of files to combine
                $filePaths = [
                    public_path('nat/csv/NAT00010_.csv'),
                    public_path('nat/csv/NAT00020_.csv'),
                    public_path('nat/csv/NAT00030_.csv'),
                    public_path('nat/csv/NAT00060_.csv'),
                    public_path('nat/csv/NAT00080_.csv'),
                    public_path('nat/csv/NAT00085_.csv'),
                    public_path('nat/csv/NAT00090_.csv'),
                    public_path('nat/csv/NAT00100_.csv'),
                    public_path('nat/csv/NAT00120_.csv'),
                    public_path('nat/csv/NAT00130_.csv'),
                    // Add more file paths as needed
                ];
            }
            // Create a unique name for the zip file
            $zipFileName = 'AVETMISS_NATIONAL_' . time() . '.zip';
            $zipFilePath = public_path($zipFileName);

            // Create a new ZipArchive
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
                foreach ($filePaths as $filePath) {
                    // Add each file to the zip archive
                    $zip->addFile($filePath, basename($filePath));
                }
                $zip->close();
            }

            // Set the response headers for downloading the zip file
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } elseif ($request->export_type == 'txt') {

            $fileName1 = 'NAT00010.txt';
            if (is_dir('nat') == false) {
                mkdir(public_path('nat'), 0700);
            }
            if (is_dir('nat/txt') == false) {
                mkdir(public_path('nat/txt'), 0700);
            }

            // TXT Nat00020
            $content1 = '';
            foreach ($companies as $company) {
                $rto_no = natReport('rto_no', $company->rto_no);
                $company_name = natReport('name', $company->company_name);
                $phone = natReport('phone', $company->phone);
                $ceo = natReport('name', $company->company_ceo);
                $email = natReport('email', $company->email);
                $content1 .= "{$rto_no}{$company_name}{$phone}{$phone}{$ceo}{$email}\n";
            }

            // Save the text data to a file
            file_put_contents(public_path('nat/txt/' . $fileName1), $content1);

            // TXT Nat00020
            $fileName2 = 'NAT00020.txt';

            $content2 = '';
            foreach ($sites as $site) {
                $rto_no = natReport('rto_no', $site->companyDeliverySite->company->rto_no);
                $site_code = natReport('site_code', natReport('zip_code', $site->companyDeliverySite->address->zip_code));
                $site_name = natReport('name', $site->companyDeliverySite->site_name);
                $zip_code = natReport('zip_code', $site->companyDeliverySite->address->zip_code);
                $suburb = natReport('suburb', $site->companyDeliverySite->address->suburb);
                $content2 .= "{$rto_no}{$site_code}{$site_name}{$zip_code}{$site->companyDeliverySite->address->state}{$suburb}1101\n";
            }
            // Save the text data to a file
            file_put_contents(public_path('nat/txt/' . $fileName2), $content2);

            // TXT Nat00030
            $fileName3 = 'NAT00030.txt';
            $content3 = '';
            foreach ($courses as $course) {
                $course_code = natReport('course_code', $course->course->course_code);
                $course_name = natReport('name', $course->course->course_name);
                $course_hours = natReport('hours', $course->course->hours);
                $content3 .= "{$course_code}{$course_name}{$course_hours}                \n";
            }
            // Save the text data to a file
            file_put_contents(public_path('nat/txt/' . $fileName3), $content3);

            // TXT Nat00060
            $fileName4 = 'NAT00060.txt';
            $unitCodeNew = [];
            $content4 = '';
            foreach ($units as $unit) {
                $a_unit = Unit::find($unit);
                $a_unit_code = $a_unit[0]->code;
                $unit_code = natReport('unit_code', $a_unit[0]->code);
                $unit_name = natReport('name', $a_unit[0]->name);
                $unit_education_field = natReport('unit_education_field', $a_unit[0]->education_field);
                $unit_hours = natReport('hours', $a_unit[0]->hours);

                $searchNew = in_array($a_unit_code, $stdCodeInIntakeUnit);
                if ($searchNew) {
                    $search = in_array($unit_code, $unitCodeNew);
                    if (!$search) {
                        $unitCodeNew[] = $unit_code;
                        $content4 .= "{$unit_code}{$unit_name}{$unit_education_field}Y{$unit_hours}\n";
                    }
                }
            }
            // Save the text data to a file
            file_put_contents(public_path('nat/txt/' . $fileName4), $content4);


            // TXT Nat00080
            $fileName5 = 'NAT00080.txt';
            $content5 = '';
            foreach ($students as $unique) {
                $student = Student::find($unique->id);
                $code = natReport('student_id', $student->id_no);
                $fullname = natReport('student_name', $student->family_name . ', ' . $student->first_name);
                $school_level = natReport('student_school_level', $student->school_level_identifier);
                $gender = natReport('gender', $student->gender);
                $dob = natReport('date', $student->date_of_birth);
                if ($student->funding_source_national == 31 || $student->funding_source_national == 32) {
                    $zip_code = natReport('zip_code', 'OSPC');
                    $suburb = natReport('suburb', NULL);
                    $state = '99';
                } else {
                    $zip_code = natReport('zip_code', $student->address->zip_code);
                    $suburb = natReport('suburb', $student->address->suburb);
                    $state = $student->address->state;
                }
                $unique_student_identifier = natReport('unique_student_identifier', $student->unique_student_identifier);
                $building_no = natReport('building_no', $student->address->building_number);
                $flat_unit = natReport('flat_unit', $student->address->flat_unit);
                if ($student->address->street_no == NULL) {
                    $street_no = natReport('street_no', 'NOT SPECIFIED');
                } else {
                    $street_no = natReport('street_no', $student->address->street_no);
                }
                if ($student->address->street_address == NULL) {
                    $street_address = natReport('street_address', 'NOT SPECIFIED');
                } else {
                    $street_address = natReport('street_address', $student->address->street_address);
                }
                $survey_contact_status = natReport('survey_contact_status', $student->survey_contact_status);
                $content5 .= "{$code}{$fullname}{$school_level}{$gender}{$dob}{$zip_code}{$student->indigenous_status_identifier}{$student->language_identifier}{$student->labour_force_status_identifier}{$student->country_id}{$student->disability_flag}{$student->prior_education}{$student->at_school}{$suburb}{$unique_student_identifier}{$state}{$building_no}{$flat_unit}{$street_no}{$street_address}{$survey_contact_status}{$student->statistical_area_level_1_identifier}{$student->statistical_area_level_2_identifier}\n";
            }
            // Save the text data to a file
            file_put_contents(public_path('nat/txt/' . $fileName5), $content5);

            // TXT Nat00085
            $fileName6 = 'NAT00085.txt';
            $content6 = '';
            foreach ($students as $unique) {
                $student = Student::find($unique->id);
                $code = natReport('student_id', $student->id_no);
                $title = natReport('student_titile', $student->salutation);
                $first_name = natReport('student_first_name', $student->first_name);
                $family_name = natReport('student_family_name', $student->family_name);
                $building_no = natReport('building_no', $student->address->building_number);
                $flat_unit = natReport('flat_unit', $student->address->flat_unit);
                if ($student->address->street_no == NULL) {
                    $street_no = natReport('street_no', 'NOT SPECIFIED');
                } else {
                    $street_no = natReport('street_no', $student->address->street_no);
                }
                if ($student->address->street_address == NULL) {
                    $street_address = natReport('street_address', 'NOT SPECIFIED');
                } else {
                    $street_address = natReport('street_address', $student->address->street_address);
                }
                $p_o_box = natReport('student_p_o_box', $student->address->p_o_box);
                if ($student->funding_source_national == 31 || $student->funding_source_national == 32) {
                    $zip_code = natReport('zip_code', 'OSPC');
                    $suburb = natReport('suburb', 'NOT SPECIFIED');
                    $state = '99';
                } else {
                    $zip_code = natReport('zip_code', $student->address->zip_code);
                    $suburb = natReport('suburb', $student->address->suburb);
                    $state = $student->address->state;
                }
                $phone = natReport('phone', $student->phone);
                $phone_work = natReport('phone', $student->phone_work);
                $mobile = natReport('phone', $student->mobile);
                $email = natReport('email', $student->email);
                $email_alternative = natReport('email', $student->email_alternative);
                $content6 .= "{$code}{$title}{$first_name}{$family_name}{$building_no}{$flat_unit}{$street_no}{$street_address}{$p_o_box}{$suburb}{$zip_code}{$state}{$phone}{$phone_work}{$mobile}{$email}{$email_alternative}\n";
            }

            // Save the text data to a file
            file_put_contents(public_path('nat/txt/' . $fileName6), $content6);

            // TXT Nat00090
            $fileName7 = 'NAT00090.txt';
            $content7 = '';
            foreach ($disable_flags as $flag) {
                $student = Student::find($flag->id);
                $code = natReport('student_id', $student->id_no);
                $disability_identifier = natReport('disability_identifier', $student->disability_identifier);
                $content7 .= "{$code}{$disability_identifier}\n";
            }

            // Save the text data to a file
            file_put_contents(public_path('nat/txt/' . $fileName7), $content7);

            // TXT Nat00100
            $fileName8 = 'NAT00100.txt';
            $content8 = '';
            foreach ($prior_education_flags as $flag) {
                $student = Student::find($flag->id);
                $code = natReport('student_id', $student->id_no);
                $prior_education_achievement_identifier = natReport('prior_education_achievement_identifier', $student->prior_education_achievement_identifier);
                $content8 .= "{$code}{$prior_education_achievement_identifier}\n";
            }

            // Save the text data to a file
            file_put_contents(public_path('nat/txt/' . $fileName8), $content8);

            // CSV Nat00120
            $fileName9 = 'NAT00120.txt';
            $content9 = '';
            foreach ($students as $student) {
                $student_courses = $student->intake->where('status', 1);
                foreach ($student_courses as $course) {
                    $units = $course->studentIntakeUnit->where('status', 1)->where('is_complete', 1)->whereBetween('ending_date', [$from, $to]);
                    foreach ($units as $unit) {
                        $student_units[] = $unit;
                    }
                }
            }
            foreach ($student_units as $student_unit) {
                $site = $student_unit->studentIntakeCourse->student->deliverySite->where('status', 1)->first();
                if ($site) {
                    $delivery_location = natReport('training_organisation_code', natReport('zip_code', $site->companyDeliverySite->address->zip_code));
                } else {
                    $delivery_location = natReport('training_organisation_code',  natReport('zip_code', 0));
                }
                $company = Company::first();
                $rto_no = natReport('rto_no', $company->rto_no);
                $code = natReport('student_id', $student_unit->studentIntakeCourse->student->id_no);
                $course_code = natReport('course_code', $student_unit->studentIntakeCourse->intakeCourse->course->course_code);
                $unit_code = natReport('unit_code', $student_unit->intakeUnit->unit->code);
                $starting_date = natReport('date', $student_unit->starting_date);
                $ending_date = natReport('date', $student_unit->ending_date);
                $unit_hours = natReport('hours', $student_unit->intakeUnit->unit->hours);
                $specific_funding_identifier = natReport('specific_funding_identifier', $student_unit->studentIntakeCourse->student->specific_funding_identifier);
                $study_reason = natReport('study_reason', $student_unit->studentIntakeCourse->student->study_reason);
                $training_contract = '          ';
                $school_type = natReport('school_type_identifier', $student_unit->studentIntakeCourse->student->school_type_identifier);
                $state_outcome = natReport('outcome', $student_unit->outcome);;
                $clientId = '          ';
                $state_funding = natReport('state_funding', $student_unit->studentIntakeCourse->student->funding_source_state_training_authority);
                $clienttuition = '     ';
                $feeExemption = '';
                $purchaseContract = '            ';
                $purchaseContractSchedule = '   ';
                $associated_course_identifier = '          ';
                $hour_attend = '    ';
                $funding_source_national = natReport('funding_source_national', $student_unit->funding_source_national);
                $content9 .= "{$rto_no}{$delivery_location}{$code}{$unit_code}{$course_code}{$starting_date}{$ending_date}{$student_unit->delivery_mode}{$state_outcome}{$funding_source_national}{$student_unit->commencing}{$training_contract}{$clientId}{$study_reason}N{$specific_funding_identifier}{$school_type}{$state_funding}{$clienttuition}{$feeExemption}{$purchaseContract}{$purchaseContractSchedule}{$hour_attend}{$associated_course_identifier}{$unit_hours}{$student_unit->predominant_delivery_mode}\n";
            }

            // Save the text data to a file
            file_put_contents(public_path('nat/txt/' . $fileName9), $content9);

            // TXT Nat00130
            $fileName10 = 'NAT00130.txt';
            $content10 = '';
            $student_intakes = [];
            foreach ($students as $student) {
                $student_courses = $student->intake->where('status', 1);
                foreach ($student_courses as $course) {
                    if ($course->studentIntakeCourseCompetence != NULL && $course->studentIntakeCourseCompetence->award_status == 'Y') {
                        $student_intakes[] = $course;
                    }
                }
            }
            foreach ($student_intakes as $student_course) {
                $student_units = $student_course->studentIntakeUnit->where('status', 1)->where('is_complete', 1)->whereBetween('ending_date', [$from, $to]);
                $su = [];
                foreach ($student_units as $student_unit) {
                    $su[] = $student_unit->ending_date;
                }
                $endingDate = collect($su)->max();
                if ($student_course->studentIntakeCourseCompetence == NULL) {
                    $issue_date = NULL;
                    $number = NULL;
                    $qualify = 'N';
                } else {
                    $issue_date = $student_course->studentIntakeCourseCompetence->parchment_issue_date;
                    $number = $student_course->studentIntakeCourseCompetence->parchment_no;
                    $qualify = $student_course->studentIntakeCourseCompetence->award_status;
                }
                $company = Company::first();
                $rto_no = natReport('rto_no', $company->rto_no);
                $course_code = natReport('course_code', $student_course->intakeCourse->course->course_code);
                $code = natReport('student_id', $student_course->student->id_no);
                $ending_date = natReport('date', $endingDate);
                $parchment_issue_date = natReport('date', $issue_date);
                $parchment_no = natReport('parchment_no', $number);
                if (count($su) > 0) {
                    $content10 .= "{$rto_no}{$course_code}{$code}{$ending_date}{$qualify}{$parchment_issue_date}{$parchment_no}\n";
                }
            }

            // Save the text data to a file
            file_put_contents(public_path('nat/txt/' . $fileName10), $content10);

            $company_vic = Company::first();
            if ($company_vic->address->state == '02') {
                copy(public_path('nat/txt/NAT00010.txt'), public_path('nat/txt/NAT00010A.txt'));
                copy(public_path('nat/txt/NAT00030.txt'), public_path('nat/txt/NAT00030A.txt'));
                // Combine all the nat files and compress to zip
                // Define the list of files to combine
                $filePaths = [
                    public_path('nat/txt/NAT00010.txt'),
                    public_path('nat/txt/NAT00010A.txt'),
                    public_path('nat/txt/NAT00020.txt'),
                    public_path('nat/txt/NAT00030A.txt'),
                    public_path('nat/txt/NAT00030.txt'),
                    public_path('nat/txt/NAT00060.txt'),
                    public_path('nat/txt/NAT00080.txt'),
                    public_path('nat/txt/NAT00085.txt'),
                    public_path('nat/txt/NAT00090.txt'),
                    public_path('nat/txt/NAT00100.txt'),
                    public_path('nat/txt/NAT00120.txt'),
                    public_path('nat/txt/NAT00130.txt'),
                    // Add more file paths as needed
                ];
            } else {
                // Combine all the nat files and compress to zip
                // Define the list of files to combine
                $filePaths = [
                    public_path('nat/txt/NAT00010.txt'),
                    public_path('nat/txt/NAT00020.txt'),
                    public_path('nat/txt/NAT00030.txt'),
                    public_path('nat/txt/NAT00060.txt'),
                    public_path('nat/txt/NAT00080.txt'),
                    public_path('nat/txt/NAT00085.txt'),
                    public_path('nat/txt/NAT00090.txt'),
                    public_path('nat/txt/NAT00100.txt'),
                    public_path('nat/txt/NAT00120.txt'),
                    public_path('nat/txt/NAT00130.txt'),
                    // Add more file paths as needed
                ];
            }


            // Create a unique name for the zip file
            $zipFileName = 'AVETMISS_NATIONAL_' . time() . '.zip';
            $zipFilePath = public_path($zipFileName);

            // Create a new ZipArchive
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
                foreach ($filePaths as $filePath) {
                    // Add each file to the zip archive
                    $zip->addFile($filePath, basename($filePath));
                }
                $zip->close();
            }

            // Set the response headers for downloading the zip file
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        }
    }
}
