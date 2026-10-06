<?php

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Mail;
use Modules\Address\Entities\Address;
use Modules\Company\Entities\Company;
use Modules\Company\Entities\CompanyDeliverySite;
use Modules\Country\Entities\Country;
use Modules\Course\Entities\CourseDeliverySite;
use Modules\Email\Entities\Email;
use Modules\Identifier\Entities\Identifier;
use Modules\Identifier\Entities\IdentifierType;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Log\Entities\Log;
use Modules\Notification\Entities\Notification;
use Modules\Report\Entities\ReportTemplate;
use Modules\Setting\Entities\Setting;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentOffer;
use Modules\Student\Entities\StudentPasswordReset;
use Modules\Student\Entities\StudentTemplateData;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerPasswordReset;
use Modules\User\Entities\UserDeliverySite;
use Modules\User\Entities\UserPasswordReset;
use Modules\User\Entities\UserRole;

/* Save User Activity Logs */
function activityLog($user_type, $action)
{
    if ($user_type == 'Admin') {
        $user = 'user';
    } elseif ($user_type == 'Student') {
        $user = 'student';
    } elseif ($user_type == 'Student API') {
        $user = 'student_api';
        $user_type = 'Student';
    } elseif ($user_type == 'Trainer') {
        $user = 'trainer';
    } elseif ($user_type == 'Trainer API') {
        $user = 'trainer_api';
        $user_type = 'Trainer';
    } elseif ($user_type == 'Agent') {
        $user = 'agent';
    } elseif ($user_type == 'Agent Branch User') {
        $user = 'agent_branch_user';
    }
    $user_id = Auth::guard($user)->user()->id;
    Log::create([
        'user_type' => $user_type,
        'user_id' => $user_id,
        'action' => $action
    ]);
}

/* Check the Admin Role Permission */
function checkRole($key, $value)
{
    $user_type = Auth::guard('user')->user()->user_type;
    $check = UserRole::where('user_type', $user_type)->where('key', $key)->where('value', $value)->where('status', 1)->first();
    if ($check) {
        return true;
    } else {
        return false;
    }
}

/* Get full name of the user */
function userName($user_type, $user_id)
{
    if ($user_type == 'Student') {
        $user = Student::find($user_id);
    } elseif ($user_type == 'Trainer') {
        $user = Trainer::find($user_id);
    } else {
        $user = User::find($user_id);
    }
    if ($user) {
        return $user->salutation . ' ' . $user->first_name . ' ' . $user->family_name;
    } else {
        return '-';
    }
}

function userNameWithoutSalutation($user_type, $user_id)
{
    if ($user_type == 'Student') {
        $user = Student::find($user_id);
    } elseif ($user_type == 'Trainer') {
        $user = Trainer::find($user_id);
    } else {
        $user = User::find($user_id);
    }
    if ($user) {
        return $user->first_name . ' ' . $user->family_name;
    } else {
        return '-';
    }
}

/* Get profile image of the user */
function userImage($user_type, $user_id)
{
    if ($user_type == 'Student') {
        $user = Student::find($user_id);
    } elseif ($user_type == 'Trainer') {
        $user = Trainer::find($user_id);
    } else {
        $user = User::find($user_id);
    }
    if ($user) {
        return asset($user->image);
    } else {
        return '-';
    }
}

/* Check status of unit */
function checkUnitLock($status)
{
    if ($status == 1) {
        return False;
    } else {
        return True;
    }
}

/* Get Austrailian States */
function getStates()
{
    $type = IdentifierType::where('title', 'STATE IDENTIFIER')->first();
    $states = Identifier::where('identifier_type_id', $type->id)->get();
    return $states;
}

/* Get State Name from STATE IDENTIFIER */
function getStateName($value)
{
    $type = IdentifierType::where('title', 'STATE IDENTIFIER')->first();
    $state = Identifier::where('identifier_type_id', $type->id)->where('value', $value)->first();
    if ($state) {
        return $state->description;
    } else {
        return NULL;
    }
}

/* Get full address of the user */
function fullAddress($type, $type_id)
{
    $address = Address::where('type', $type)->where('type_id', $type_id)->first();
    if ($address) {
        if ($address->building_number == NULL) {
            $building_number = '';
        } else {
            $building_number = $address->building_number . ', ';
        }
        if ($address->flat_unit == NULL) {
            $flat_unit = '';
        } else {
            $flat_unit = $address->flat_unit . ', ';
        }
        if ($address->p_o_box == NULL) {
            $p_o_box = ', ';
        } else {
            $p_o_box = ', ' . $address->p_o_box . ', ';
        }
        $state = getStateName($address->state);
        if ($state == NULL) {
            $state = $address->state;
        }
        return $building_number . $flat_unit . $address->street_no . ' ' . $address->street_address . $p_o_box .  $address->suburb . ', ' .  $state . ', ' .  $address->zip_code;
    } else {
        return '-';
    }
}

/* Get Inital Name of the user */
function initialName($user_type, $user_id)
{
    if ($user_type == 'Student') {
        $user = Student::find($user_id);
    } elseif ($user_type == 'Trainer') {
        $user = Trainer::find($user_id);
    }
    $first = $user->first_name;
    $last = $user->family_name;
    return $first[0] . $last[0];
}

/* Get Setting value by key */
function getSettingValue($key)
{
    $setting = Setting::where('key', $key)->first();
    if ($setting) {
        return $setting->value;
    } else {
        return NULL;
    }
}

/* Update Setting value */
function updateSettingValue($key, $value)
{
    $setting = getSettingValue($key);
    if ($setting != NULL) {
        Setting::where('key', $key)->update(['value' => $value]);
    } else {
        Setting::create(['key' => $key, 'value' => $value]);
    }
}

/* Get Logo */
function getLogo()
{
    $logo = getSettingValue('logo');
    if ($logo != NULL) {
        return $logo;
    } else {
        $company = Company::first();
        return $company->logo;
    }
}

/* Get Title */
function getTitle()
{
    $title = getSettingValue('title');
    if ($title != NULL) {
        return $title;
    } else {
        $company = Company::first();
        return $company->company_name;
    }
}

/* Get Fav Icon */
function getFavIcon()
{
    $favIcon = getSettingValue('fav_icon');
    if ($favIcon != NULL) {
        return $favIcon;
    } else {
        return 'fav.png';
    }
}

/* Get Footer */
function getFooter($param)
{
    if ($param == 'text') return config('app.footer_text');
    else return config('app.footer_url');
}

/* Setting for dashboard widgets */
function dashboardWidget($key)
{
    $type = getSettingValue($key);
    if ($type == NULL) {
        return 'on';
    } else {
        return $type;
    }
}

/* Setting for assignment */
function assignmentSetting($key)
{
    $type = getSettingValue($key);
    if ($type == NULL) {
        return 'on';
    } else {
        return $type;
    }
}

/* Setting for assignment resubmission*/
function attendanceSetting($key)
{
    $type = getSettingValue($key);
    if ($type == NULL) {
        return 'off';
    } else {
        return $type;
    }
}

/* Get date format from settings */
function dateFormat($date)
{
    $date_format = getSettingValue('date_format');
    if ($date_format != NULL) {
        return date($date_format, strtotime($date));
    } else {
        // Austrailian date format
        return date("d/m/Y", strtotime($date));
    }
}

/* Get time format from settings */
function timeFormat($time)
{
    $time_format = getSettingValue('time_format');
    if ($time_format != NULL) {
        return date($time_format, strtotime($time));
    } else {
        return date("h:i A", strtotime($time));
    }
}

/* Get full date and time format */
function dateTimeFormat($full_date)
{
    $date = dateFormat($full_date);
    $time = timeFormat($full_date);
    return $date . ' ' . $time;
}

/* Convert Date format for sorting */
function convertDate($date)
{
    return str_replace("-", "", $date);
}

/* Convert formatted date to non formatted date */
function getNonFormattedDate($formatted_date)
{
    $date_format = getSettingValue('date_format');
    if ($date_format == NULL) {
        // Austrailian date format
        $date_format = 'd/m/Y';
    }

    // Create a DateTime object by parsing the input date
    $dateObj = DateTime::createFromFormat($date_format, $formatted_date);

    // Check if the date parsing was successful
    if ($dateObj !== false) {
        // Convert the date to 'Y-m-d' format
        $formattedDate = $dateObj->format('Y-m-d');

        // Output the formatted date
        return $formattedDate;
    } else {
        return "";
    }
}

/* Get Identifiers */
function getIdentifiers($type)
{
    $identifier_type = IdentifierType::where('title', $type)->first();
    $identifiers = Identifier::where('identifier_type_id', $identifier_type->id)->where('status', 1)->get();
    return $identifiers;
}

/* Get Description of Identifier Value */
function getIdentifierValue($type, $value)
{
    $identifier_type = IdentifierType::where('title', $type)->first();
    $identifier = Identifier::where('identifier_type_id', $identifier_type->id)->where('value', $value)->first();
    if ($identifier) {
        return $identifier->description;
    }
    return '';
}

/* Deploying FCM */
function fcm($data)
{
    $fcm_key = getSettingValue('fcm_server_api_key');
    if ($fcm_key != NULL) {
        $SERVER_API_KEY = $fcm_key;
    } else {
        $SERVER_API_KEY = env("SERVER_API_KEY");
    }
    $dataString = json_encode($data);

    $headers = [
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);

    return $response;
}

/* Get Notification of the user */
function notification($user_type)
{
    if ($user_type == 'Admin') {
        $user = 'user';
    } elseif ($user_type == 'Student') {
        $user = 'student';
    } elseif ($user_type == 'Student API') {
        $user = 'student_api';
        $user_type = 'Student';
    } elseif ($user_type == 'Trainer') {
        $user = 'trainer';
    }
    $user_id = Auth::guard($user)->user()->id;
    $notifications = Notification::where('user_type', $user_type)->where('user_id', $user_id)->get();
    return $notifications;
}

/* Generate Password Reset Code */
function randomResetCode($user_type)
{
    $str = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $uniqueCode = false;
    $code = 0;
    while ($uniqueCode == false) {
        $code = substr(str_shuffle($str), 0, 8);
        if ($user_type == 'Student') {
            $check = StudentPasswordReset::where('code', $code)->count();
        } elseif ($user_type == 'Trainer') {
            $check = TrainerPasswordReset::where('code', $code)->count();
        } else {
            $check = UserPasswordReset::where('code', $code)->count();
        }
        if ($check > 0) {
            $uniqueCode = false;
        } else {
            $uniqueCode = true;
        }
    }
    return $code;
}

/* Deploying Zoom Meeting */
function zoom($data)
{
    $dataString = json_encode($data);
    $url = asset('/api/meetings');

    $headers = [
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result;
}

/* Access for zoom recording */
function getZoomRecording($meeting_id)
{
    $zoom_api_url = getSettingValue('zoom_api_url');
    if ($zoom_api_url != NULL) {
        $zoom_url =  $zoom_api_url;
    } else {
        $zoom_url = env('ZOOM_API_URL', '');
    }

    $zoom_jwt = getSettingValue('zoom_api_jwt');
    if ($zoom_jwt != NULL) {
        $jwt =  $zoom_jwt;
    } else {
        $jwt = env('ZOOM_API_JWT', '');
    }

    $url = $zoom_url . 'meetings/' . $meeting_id . '/recordings?access_token=' . $jwt;
    try {
        $result = file_get_contents($url);
    } catch (Exception $e) {
        return false;
    }
    $json_data = json_decode($result);
    return $json_data;
}

/* Get extension and icon of files */
function filePath($filePath)
{
    $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    $extenion = substr($ext, 0, 2);
    if ($extenion == 'do') $path = 'files/word.png';
    elseif ($extenion == 'pp') $path = 'files/ppt.png';
    elseif ($extenion == 'pd') $path = 'files/pdf.png';
    else {
        $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $allowedVideoExtensions = ['mp4', 'avi', 'mov', 'mp3'];
        if (in_array(strtolower($ext), $allowedImageExtensions)) {
            $path = 'files/img.png';
        } elseif (in_array(strtolower($ext), $allowedVideoExtensions)) {
            $path = 'files/video.png';
        } else {
            $path = 'files/files.png';
        }
    }
    return $path;
}

/* Get count of days in a month by year */
function daysInMonth(int $year, int $month)
{
    return now()->setYear($year)
        ->setMonth($month)
        ->daysInMonth;
}

function studentPaymentStatus($status_code)
{
    if ($status_code == 1) $status = 'Remaining';
    elseif ($status_code == 2) $status = 'Paid';
    elseif ($status_code == 3) $status = 'Refunded';
    return $status;
}

/* Get Student Offer Number */
function offerNumber($id)
{
    $letter = StudentOffer::find($id);
    $agent = $letter->student->studentAgent;
    $date = date('dmY') . ' - ' . date('Hi');
    if ($agent) {
        $name = strtoupper(substr($agent->agent->company_name, 0, 3));
        $offer_number = $name . $date;
    } else {
        $offer_number = $date;
    }
    return $offer_number;
}

/* Generate array of hex colors */
function randomHexColor($n)
{
    $colors = [];

    while (count($colors) < $n) {
        // Generate a random color
        $color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);

        // Ensure the color is unique
        if (!isset($colors[$color])) {
            $colors[$color] = true;
        }
    }

    return array_keys($colors);
}

function acronym($text)
{
    $words = explode(" ", $text);
    return $words[0];
}

/* Adding space to data for nat report */
function natReport($field, $data)
{
    if ($data == NULL) {
        $data = '';
    }
    // dd($data, $field);
    if ($field == 'rto_no') {
        $space_count = 10 - strlen($data);
    } elseif ($field == 'name') {
        $data = preg_replace('/[^A-Za-z0-9 ]/', '', $data);
        $space_count = 100 - strlen($data);
    } elseif ($field == 'phone') {
        $space_count = 20 - strlen($data);
    } elseif ($field == 'fax') {
        $space_count = 20 - strlen($data);
    } elseif ($field == 'email') {
        $space_count = 80 - strlen($data);
    } elseif ($field == 'site_code') {
        $space_count = 10 - strlen($data);
    } elseif ($field == 'suburb') {
        $space_count = 50 - strlen($data);
    } elseif ($field == 'building_no') {
        $space_count = 50 - strlen($data);
    } elseif ($field == 'flat_unit') {
        $space_count = 30 - strlen($data);
    } elseif ($field == 'street_no') {
        $space_count = 15 - strlen($data);
    } elseif ($field == 'street_address') {
        $space_count = 70 - strlen($data);
    } elseif ($field == 'zip_code') {
        if (strlen($data) == 1) {
            return '000' . $data;
        } elseif (strlen($data) == 2) {
            return '00' . $data;
        } elseif (strlen($data) == 3) {
            return '0' . $data;
        } else {
            return $data;
        }
    } elseif ($field == 'survey_contact_status') {
        $space_count = 1 - strlen($data);
    } elseif ($field == 'course_code') {
        $space_count = 10 - strlen($data);
    } elseif ($field == 'hours') {
        if ($data == '' || $data == '0') {
            $data = '0000';
        }
        $space_count = 4 - strlen($data);
        return str_repeat('0', $space_count) . $data;
    } elseif ($field == 'unit_code') {
        $space_count = 12 - strlen($data);
    } elseif ($field == 'unit_education_field') {
        $space_count = 6 - strlen($data);
    } elseif ($field == 'student_id') {
        $space_count = 10 - strlen($data);
    } elseif ($field == 'student_name') {
        $space_count = 60 - strlen($data);
    } elseif ($field == 'student_titile') {
        $space_count = 4 - strlen($data);
    } elseif ($field == 'student_first_name' || $field == 'student_family_name') {
        $space_count = 40 - strlen($data);
    } elseif ($field == 'student_p_o_box') {
        $space_count = 22 - strlen($data);
    } elseif ($field == 'student_school_level') {
        if ($data == '') {
            $data = '@@';
        }
        $space_count = 2 - strlen($data);
    } elseif ($field == 'gender') {
        if ($data == '') {
            $data = 'X';
        }
        $space_count = 1 - strlen($data);
    } elseif ($field == 'unique_student_identifier') {
        $space_count = 10 - strlen($data);
    } elseif ($field == 'disability_identifier') {
        $space_count = 2 - strlen($data);
    } elseif ($field == 'prior_education_achievement_identifier') {
        $space_count = 2 - strlen($data);
    } elseif ($field == 'specific_funding_identifier') {
        $space_count = 10 - strlen($data);
    } elseif ($field == 'school_type_identifier') {
        $space_count = 2 - strlen($data);
    } elseif ($field == 'date') {
        if ($data != '') {
            $date = dateFormat($data);
            return str_replace(array('-', '/'), '', $date);
        } else {
            return '        ';
        }
    } elseif ($field == 'training_organisation_code') {
        $space_count = 10 - strlen($data);
    } elseif ($field == 'study_reason') {
        $space_count = 2 - strlen($data);
    } elseif ($field == 'state_funding') {
        $space_count = 2 - strlen($data);
    } elseif ($field == 'outcome') {
        $space_count = 2 - strlen($data);
    } elseif ($field == 'funding_source_national') {
        $space_count = 2 - strlen($data);
    } elseif ($field == 'parchment_no') {
        $space_count = 25 - strlen($data);
    } else {
        $space_count = 0;
    }
    return $data . str_repeat(' ', abs($space_count));
}

/* Dynamic email setting */
function sendEmail($id, $to_name, $to_email, $data, $type)
{
    $emailSettings = Email::find($id);
    if ($emailSettings) {
        $config = [
            'driver'     => 'smtp',
            'host'       => $emailSettings->host,
            'port'       => $emailSettings->port,
            'username'   => $emailSettings->username,
            'password'   => $emailSettings->password,
            'encryption' => $emailSettings->encryption,
            'port'       => $emailSettings->port,
            // Add other email configuration settings here
        ];

        // Set the email configuration dynamically
        Config::set('mail', $config);

        $from_address = $emailSettings->from_address;
        $from_name = $emailSettings->from_name;
        $reply_to = $emailSettings->reply_to;
        $subject = $data['subject'];

        if ($type == 'Student' || $type == 'Trainer' || $type == 'Agent' || $type == 'User') {
            Mail::send('student::email.template.email', $data, function ($message) use ($to_name, $to_email, $from_address, $from_name, $reply_to, $subject) {
                $message->to($to_email, $to_name)
                    ->subject($subject);
                $message->from($from_address, $from_name);
                $message->replyTo($reply_to);
            });
            return true;
        }
    }
}

/* Get value of student template data */
function getStudentTemplateDataValue($student_template_id, $key)
{
    $student_template_data = StudentTemplateData::where('student_template_id', $student_template_id)->where('key', $key)->first();
    if ($student_template_data) {
        return $student_template_data->value;
    } else {
        return '';
    }
}

/* Get Delivery Site List */
function getDeliverySites()
{
    $user = Auth::guard('user')->user();
    $user_id = $user->id;
    $role = $user->user_type;
    $user_delivery_sites = UserDeliverySite::where('user_id', $user_id)->where('status', 1)->get();
    if (count($user_delivery_sites) == 0 || $role == 'super_admin') {
        $sites = CompanyDeliverySite::where('status', 1)->get();
    } else {
        foreach ($user_delivery_sites as $key => $value) {
            $ids[] = $value->company_delivery_site_id;
        }
        $sites = CompanyDeliverySite::whereIn('id', $ids)->where('status', 1)->get();
    }
    return $sites;
}

/* Get Delivery Site Ids List */
function getDeliverySiteIds()
{
    $user = Auth::guard('user')->user();
    $user_id = $user->id;
    $role = $user->user_type;
    $user_delivery_sites = UserDeliverySite::where('user_id', $user_id)->where('status', 1)->get();
    if (count($user_delivery_sites) == 0 || $role == 'super_admin') {
        $sites = CompanyDeliverySite::where('status', 1)->get();
    } else {
        foreach ($user_delivery_sites as $key => $value) {
            $ids[] = $value->company_delivery_site_id;
        }
        $sites = CompanyDeliverySite::whereIn('id', $ids)->where('status', 1)->get();
    }
    foreach ($sites as $site) {
        $site_ids[] = $site->id;
    }
    return $site_ids;
}

/* Get Course Count By Intake */
function getCourseCountByIntake($intake_id)
{
    $ids = getDeliverySiteIds();
    $intakeCourseCount = IntakeCourse::join('courses', 'courses.id', '=', 'intake_courses.course_id')
        ->leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
        ->where(function ($query) use ($ids) {
            $query->whereNull('course_delivery_sites.course_id')
                ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
        })
        ->select('intake_courses.*')
        ->where('courses.status', 1)
        ->where('intake_courses.intake_id', $intake_id)
        ->count();
    return $intakeCourseCount;
}

/* Check for course delivery site */
function checkCourseDeliverySite($course_id)
{
    $delivery_site = CourseDeliverySite::where('course_id', $course_id)->first();
    if ($delivery_site) {
        $ids = getDeliverySiteIds();
        $sites = CourseDeliverySite::whereIn('company_delivery_site_id', $ids)->where('course_id', $course_id)->get();
        if (count($sites) == 0) $site = false;
        else $site = true;
    } else {
        $site = true;
    }
    return $site;
}

function getReportTemplateList()
{
    return ReportTemplate::where('status', 1)->get();
}

function getDefaultIdentifier($identifier)
{
    if ($identifier == 'DELIVERY MODE IDENTIFIER') {
        $value = 'NNN';
    } elseif ($identifier == 'PREDOMINANT DELIVERY MODE') {
        $value = 'N';
    }
    return $value;
}

/* Get Country Value from nationality */
function getAvetmissCountry($nationality)
{
    $country = Country::where('nationality', 'LIKE', '%' . $nationality . '%')->first();
    if ($nationality == 'American') {
        $country->name = 'United States of America';
    }
    $identifier_type = IdentifierType::where('title', 'COUNTRY IDENTIFIER')->first();
    $identifier = Identifier::where('identifier_type_id', $identifier_type->id)->where('description', $country->name)->first();
    if ($identifier) {
        return $identifier->value;
    } else {
        return '@@@@';
    }
}

/* Get Country Value from nationality */
function getCountryFromNationality($nationality)
{
    $country = Country::where('nationality', 'LIKE', '%' . $nationality . '%')->first();
    return $country->id;
}

/* Get Value of Identifier Description */
function getIdentifierDescription($type, $description)
{
    $identifier_type = IdentifierType::where('title', $type)->first();
    $identifier = Identifier::where('identifier_type_id', $identifier_type->id)->where('description', $description)->first();
    if ($identifier) {
        return $identifier->value;
    }
    return '';
}

/* Dynamic email from setting chosen */
function sendEmailSetting()
{
    $emailSetting = getSettingValue('email_setting_type');
    if ($emailSetting == 'mailgun') {
        $config = [
            'driver'       => 'smtp',
            'host'         => getSettingValue('mailgun_mail_host'),
            'port'         => getSettingValue('mailgun_mail_port'),
            'username'     => getSettingValue('mailgun_mail_username'),
            'password'     => getSettingValue('mailgun_mail_password'),
            'encryption'   => getSettingValue('mailgun_mail_encryption'),
            'port'         => getSettingValue('mailgun_mail_port'),
            'from' => [
                'address'  => getSettingValue('mailgun_mail_from_address'),
                'name'     => getSettingValue('mailgun_mail_from_name'),
            ],
            'mailgun' => [
                'domain'   => getSettingValue('mailgun_domain'),
                'secret'   => getSettingValue('mailgun_secret'),
            ],
            // Add other email configuration settings here
        ];
        FacadesLog::info('Mail configuration', $config);
        Config::set('mail', $config);

        config([
            'services.mailgun.domain' => getSettingValue('mailgun_domain'),
            'services.mailgun.secret' => getSettingValue('mailgun_secret'),
        ]);
    } elseif($emailSetting == 'mailchimp') {
        $config = [
            'driver'       => 'smtp',
            'host'         => getSettingValue('mailchimp_mail_host'),
            'port'         => getSettingValue('mailchimp_mail_port'),
            'username'     => getSettingValue('mailchimp_mail_username'),
            'password'     => getSettingValue('mailchimp_mail_password'),
            'encryption'   => getSettingValue('mailchimp_mail_encryption'),
            'port'         => getSettingValue('mailchimp_mail_port'),
            'from' => [
                'address'  => getSettingValue('mailchimp_mail_from_address'),
                'name'     => getSettingValue('mailchimp_mail_from_name'),
            ],
            // Add other email configuration settings here
        ];
        FacadesLog::info('Mail configuration', $config);
        Config::set('mail', $config);
    } elseif($emailSetting == 'office365') {
        $config = [
            'driver'       => 'smtp',
            'host'         => getSettingValue('office365_mail_host'),
            'port'         => getSettingValue('office365_mail_port'),
            'username'     => getSettingValue('office365_mail_username'),
            'password'     => getSettingValue('office365_mail_password'),
            'encryption'   => getSettingValue('office365_mail_encryption'),
            'port'         => getSettingValue('office365_mail_port'),
            'from' => [
                'address'  => getSettingValue('office365_mail_from_address'),
                'name'     => getSettingValue('office365_mail_from_name'),
            ],
            // Add other email configuration settings here
        ];
        FacadesLog::info('Mail configuration', $config);
        Config::set('mail', $config);
    }
    return true;
}

function getTicketStatus($status)
{
    if ($status == 1) return 'Opened';
    elseif ($status == 2) return 'In Progress';
    elseif ($status == 3) return 'Closed';
    elseif ($status == 4) return 'Reopened';
}

function getChatStatus($status)
{
    if ($status == 1) return 'Active';
    elseif ($status == 2) return 'Deleted';
    elseif ($status == 0) return 'InActive';
}

/* Message Emitter to Socket Server */
function emitMessageToSocket($chatId, $userId, $userName, $userType, $userImage, $message, $messageType)
{
    try {
        $client = new Client();
        $response = $client->post(rtrim(config('services.socket.url'), '/') . '/emit', [
            'json' => [
                'chat_id' => $chatId,
                'user_id' => $userId,
                'user_name' => $userName,
                'user_type' => $userType,
                'user_image' => $userImage,
                'message' => $message,
                'messageType' => $messageType,
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            FacadesLog::info('Message emitted successfully.');
        }
    } catch (\Exception $e) {
        FacadesLog::error('Error emitting message to socket server: ' . $e->getMessage());
    }
}

/**
 * Converts a Microsoft Word document (.docx) to HTML format
 *
 * @param string $file to the Word document file
 * @return string The HTML content of the Word document
 */
function convertDocxToHtml($file)
{
    $converterUrl = config('services.docx_converter.url');
    if (empty($converterUrl)) {
        return ['success' => false, 'message' => 'DOCX converter is not configured. Set DOCX_CONVERTER_URL in .env.'];
    }

    if ($file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
        // Initialize cURL request
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => rtrim($converterUrl, '/') . '/convert',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "accept: application/pdf",
                "Content-Type: multipart/form-data"
            ],
            CURLOPT_POSTFIELDS => [
                "conversion_type" => "docx_to_html",
                "files" => new \CURLFile($file->getPathname(), $file->getMimeType(), $file->getClientOriginalName())
            ]
        ]);
    } else {
        // $file is not an instance of UploadedFile, so you need to handle this case
        // For example, you could throw an exception or return an error message
        throw new \Exception('Invalid file object');
    }

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        return ['success' => false, 'message' => "Conversion failed: $error"];
    }
    return ['success' => true, 'htmlContent' => $response];
}

/* Get Next Student Id */
function generateNextStudentId() {
    // Fetch settings from the database
    $prefix = getSettingValue('student_id_prefix') ?? ''; // Default to empty if NULL
    $suffix = getSettingValue('student_id_suffix') ?? ''; // Default to empty if NULL
    $startNumber = getSettingValue('student_id_number_start') ?? 1; // Default start number if NULL
    $format = getSettingValue('student_id_format') ?? 'None'; // Default to 'None'

    // Get the last student with a valid ID
    $last = Student::whereNotNull('id_no')->orderBy('id', 'desc')->first();

    if ($last) {
        $lastIdNo = $last->id_no;

        // Remove prefix and suffix based on format
        if ($format === 'Prefix' || $format === 'Both') {
            $lastIdNo = preg_replace('/^' . preg_quote($prefix, '/') . '/', '', $lastIdNo);
        }
        if ($format === 'Suffix' || $format === 'Both') {
            $lastIdNo = preg_replace('/' . preg_quote($suffix, '/') . '$/', '', $lastIdNo);
        }

        // Convert the remaining part to an integer and increment by 1
        $numericPart = intval($lastIdNo) + 1;
    } else {
        // Use default start number if no students exist
        $numericPart = intval($startNumber);
    }

    // Ensure numeric part is at least 3 digits
    $numericPart = str_pad($numericPart, 3, '0', STR_PAD_LEFT);

    // Build the new student ID based on format
    $newId = '';
    if ($format === 'Prefix' || $format === 'Both') {
        $newId .= $prefix;
    }
    $newId .= $numericPart;
    if ($format === 'Suffix' || $format === 'Both') {
        $newId .= $suffix;
    }

    return $newId;
}
