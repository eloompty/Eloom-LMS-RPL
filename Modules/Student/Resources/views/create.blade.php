@extends('user::layouts.master')
@section('title', 'Admin | Add Student')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Student</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($table=='offer' ) href="{{ route('admin.student.offer.index') }}" @else href="{{ route('admin.student.index') }}" @endif>Students</a></li>
                    <li class="breadcrumb-item active">Add Student</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"> Add Student</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!-- <div class="container"> -->
                        <ul class="nav nav-tabs mb-3" id="tabNavigation">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#personal">Personal Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#contact">Address</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#agent">Agent</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#other">Others</a>
                            </li>
                        </ul>
                        <form id="multiStepForm" action="{{ route('admin.student.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="table" value="{{ $table }}">
                            <div class="tab-content" id="formTabs">
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <h3>Personal Information</h3>
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <label for="salutation">Salutation</label> <span class="required">*</span>
                                            <select name="salutation" class="form-control" id="salutation">
                                                <option value="" selected disabled>-- Select Salutation --</option>
                                                <option value="Mr.">Mr.</option>
                                                <option value="Ms.">Ms.</option>
                                                <option value="Miss">Miss</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="first_name">First Name</label> <span class="required">*</span>
                                            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter First Name">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="family_name">Family Name</label> <span class="required">*</span>
                                            <input type="text" name="family_name" class="form-control" id="family_name" placeholder="Enter Family Name">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="date_of_birth">Date of Birth</label> <span class="required">*</span>
                                            <input type="date" name="date_of_birth" class="form-control" id="date_of_birth" placeholder="Enter Date of Birth">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="mobile">Mobile</label> <span class="required">*</span>
                                            <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Enter Mobile">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="phone_work">Phone Work</label>
                                            <input type="text" name="phone_work" class="form-control" id="phone_work" placeholder="Enter Phone Work">
                                        </div>
                                        <div class="form-group col-sm-3"></div>
                                        <div class="form-group col-sm-3">
                                            <label for="passport_no">Passport No</label> <span class="required">*</span>
                                            <input type="text" name="passport_no" class="form-control" id="passport_no" placeholder="Enter Passport No">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="citizenship_country">Citizenship Country</label> <span class="required">*</span>
                                            <select class="form-control" name="citizenship_country" id="citizenship_country">
                                                <option value="">-- Select Citizenship Country --</option>
                                                @foreach(getIdentifiers('COUNTRY IDENTIFIER') as $citizenship_country)
                                                <option value="{{ $citizenship_country->value }}">{{ $citizenship_country->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3"></div>
                                        <div class="form-group col-sm-3"></div>
                                        <div class="form-group col-sm-3">
                                            <label for="email">Email</label> <span class="required">*</span>
                                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="email_alternative">Email Alternative</label>
                                            <input type="text" name="email_alternative" class="form-control" id="email_alternative" placeholder="Enter Email Alternative">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="password">Password</label> <span class="required">*</span>
                                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="gender">Gender</label>
                                            <select class="form-control" name="gender" id="gender">
                                                <option value="">-- Select Gender --</option>
                                                @foreach(getIdentifiers('GENDER') as $gender)
                                                <option value="{{ $gender->value }}">{{ $gender->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="id_no">Student ID</label>
                                            <input type="text" name="id_no" class="form-control" id="id_no" placeholder="Enter Student Id">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="student_id_national">Student Id National</label>
                                            <input type="text" name="student_id_national" class="form-control" id="student_id_national" placeholder="Enter Student Id National">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="student_id_apprenticeships">Student Id Apprenticeships</label>
                                            <input type="text" name="student_id_apprenticeships" class="form-control" id="student_id_apprenticeships" placeholder="Enter Student Id Apprenticeships">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="unique_student_identifier">Unique Student Identifier</label>
                                            <input type="text" name="unique_student_identifier" class="form-control" id="unique_student_identifier" placeholder="Enter Unique Student Identifier">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="status">Delivery Site</label> <span class="required">*</span>
                                            <select class="form-control" name="company_delivery_site_id">
                                                <option value="">-- Select Delivery Site --</option>
                                                @foreach($delivery_sites as $key => $value)
                                                <option value="{{ $value->id }}">{{ $value->site_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="allow_submission_after_due_date">Allow Assignment Submission After Due Date</label>
                                            <select name="allow_submission_after_due_date" class="form-control" id="allow_submission_after_due_date">
                                                <option value="" selected disabled>-- Select Allow Assignment Submission After Due Date --</option>
                                                <option value="on">On</option>
                                                <option value="off">Off</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="image">Image</label> <span class="required">*</span>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="image" class="custom-file-input" id="image" onchange="readURL(this);">
                                                    <label class="custom-file-label" for="image">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="box-image" alt="" style="width: 80px; border: #ebebeb 1px solid;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label for="student_type">Student Type</label> <span class="required">*</span>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="student_type" value="onshore" id="type_onshore" checked>
                                                <label class="form-check-label" for="type_onshore">
                                                    Onshore
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="student_type" value="offshore" id="type_offshore">
                                                <label class="form-check-label" for="type_offshore">
                                                    Offshore
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary next">Next</button>
                                </div>
                                <div class="tab-pane fade" id="contact" role="tabpanel">
                                    <h3>Current Address Information</h3>
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <label for="building_number">Building Name</label>
                                            <input type="text" name="building_number" class="form-control" id="building_number" placeholder="Enter Building Name">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="flat_unit">Flat/Unit</label>
                                            <input type="text" name="flat_unit" class="form-control" id="flat_unit" placeholder="Enter Flat/Unit">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="street_no">Street No</label>
                                            <input type="text" name="street_no" class="form-control" id="street_no" onchange="myChangeFunction(this)" placeholder="Enter Street No">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="street_address">Street Address</label>
                                            <input type="text" name="street_address" class="form-control" id="street_address" onchange="myChangeFunction(this)" placeholder="Enter Street Address">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="p_o_box">P.O.Box</label>
                                            <input type="text" name="p_o_box" class="form-control" id="p_o_box" placeholder="Enter P.O.Box">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="suburb">Suburb</label>
                                            <input type="text" name="suburb" class="form-control" id="suburb" onchange="myChangeFunction(this)" placeholder="Enter Suburb">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="state">State</label>
                                            <select class="form-control" name="state" id="state" onchange="myChangeFunction(this)">
                                                <option value="">-- Select State --</option>
                                                @foreach(getStates() as $state)
                                                <option value="{{ $state->value }}">{{ $state->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="zip_code">Postal Code</label>
                                            <input type="text" name="zip_code" class="form-control" id="zip_code" onchange="myChangeFunction(this)" placeholder="Enter Postal Code">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="country_id">Country</label>
                                            <select class="form-control" name="country_id" id="country" onchange="myChangeFunction(this)">
                                                <option value="">-- Select Country --</option>
                                                @foreach(getIdentifiers('COUNTRY IDENTIFIER') as $country)
                                                <option value="{{ $country->value }}">{{ $country->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <h3>Overseas Address</h3>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="overseas_address">Overseas Address</label> <span class="required">*</span>
                                            <textarea id="overseas_address" name="overseas_address" class="form-control" placeholder="Enter Overseas Address"></textarea>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="overseas_country_id">Overseas Country</label> <span class="required">*</span>
                                            <select class="form-control" name="overseas_country_id" id="overseas_country_id">
                                                <option value="">-- Select Overseas Country --</option>
                                                @foreach($countries as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <hr>
                                    <h3>Emergency Contact Information</h3>
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <label for="emergency_contact_person">Emergency Contact Person</label> <span class="required">*</span>
                                            <input type="text" name="emergency_contact_person" class="form-control" id="emergency_contact_person" placeholder="Enter Emergency Contact Person">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="emergency_contact_number">Emergency Contact Number</label> <span class="required">*</span>
                                            <input type="text" name="emergency_contact_number" class="form-control" id="emergency_contact_number" placeholder="Enter Emergency Contact Number">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="emergency_contact_relation">Emergency Contact Relation</label> <span class="required">*</span>
                                            <input type="text" name="emergency_contact_relation" class="form-control" id="emergency_contact_relation" placeholder="Enter Emergency Contact Relation">
                                        </div>
                                        <input type="hidden" id="address" name="current_address" />
                                    </div>
                                    <button type="button" class="btn btn-primary prev">Previous</button>
                                    <button type="button" class="btn btn-primary next">Next</button>
                                </div>
                                <div class="tab-pane fade" id="agent" role="tabpanel">
                                    <h3>Agent</h3>
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <label for="agent_id">Agent</label>
                                            <select id="agent_id" name="agent_id" class="form-control">
                                                <option value="" selected disabled>-- Select Agent --</option>
                                                @foreach($agents as $key => $value)
                                                <option value="{{ $key }}"> {{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="branch_id">Branch</label>
                                            <select name="branch_id" id="branch_id" class="form-control">
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="user_id">User</label>
                                            <select name="user_id" id="user_id" class="form-control">
                                            </select>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary prev">Previous</button>
                                    <button type="button" class="btn btn-primary next">Next</button>
                                </div>
                                <div class="tab-pane fade" id="other" role="tabpanel">
                                    <h4>Language and cultural diversity </h4>
                                    <div class="form-group">
                                        <label for="language_identifier">Do you speak a language other than English at home?</label>
                                        <select class="form-control" name="language_identifier" id="language_identifier">
                                            <option value="">-- Select Language Identifier --</option>
                                            @foreach(getIdentifiers('LANGUAGE IDENTIFIER') as $language_identifier)
                                            <option value="{{ $language_identifier->value }}">{{ $language_identifier->description }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">(If more than one language, indicate the one that is spoken most often)</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="indigenous_status_identifier">Are you of Aboriginal or Torres Strait Islander origin?</label>
                                        <select class="form-control" name="indigenous_status_identifier" id="indigenous_status_identifier">
                                            <option value="">-- Select Indigenos Status Identifier --</option>
                                            @foreach(getIdentifiers('INDIGENOUS STATUS IDENTIFIER') as $indigenous_status_identifier)
                                            <option value="{{ $indigenous_status_identifier->value }}">{{ $indigenous_status_identifier->description }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">(For persons of both Aboriginal and Torres Strait Islander origin, select one) </div>
                                    </div>
                                    <hr>
                                    <h4>Disability </h4>
                                    <div class="form-group">
                                        <label for="disability_flag">Do you consider yourself to have a disability, impairment or long-term condition? </label>
                                        <select name="disability_flag" class="form-control" id="disability_flag" onchange="disYesNo(this);">
                                            <option value="" selected disabled>-- Select Disabitity Flag --</option>
                                            <option value="Y">Yes</option>
                                            <option value="N">No</option>
                                        </select>
                                    </div>
                                    <div id="disability" class="form-group" style='display: none;'>
                                        <label for="disability_identifier">If you indicated the presence of a disability, impairment or long-term condition, please select the area in the following list:</label>
                                        <select class="form-control" name="disability_identifier" id="disability_identifier">
                                            <option value="">-- Select Disability Type Identifier --</option>
                                            @foreach(getIdentifiers('DISABILITY TYPE IDENTIFIER') as $disability_identifier)
                                            <option value="{{ $disability_identifier->value }}">{{ $disability_identifier->description }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">Please refer to the Disability supplement for an explanation of the following disabilities. .</div>
                                    </div>
                                    <hr>
                                    <h4>Schooling </h4>
                                    <div class="form-group">
                                        <label for="high_school_level_completed_identifier">What is your highest COMPLETED school level? (Choose ONE only) </label>
                                        <select class="form-control" name="high_school_level_completed_identifier" id="high_school_level_completed_identifier">
                                            <option value="">-- Select High School Level Completed Identifier --</option>
                                            @foreach(getIdentifiers('HIGHEST SCHOOL LEVEL COMPLETED IDENTIFIER') as $high_school_level_completed_identifier)
                                            <option value="{{ $high_school_level_completed_identifier->value }}">{{ $high_school_level_completed_identifier->description }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">If you are currently enrolled in secondary education, the Highest school level completed refers to the highest school level you have actually completed and not the level you are currently undertaking. For example, if you are currently in Year 10 the Highest school level completed is Year 9. </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="at_school">Are you still enrolled in secondary or senior secondary education?</label>
                                        <select name="at_school" class="form-control" id="at_school">
                                            <option value="" selected disabled>-- Select At School --</option>
                                            <option value="Y">Yes</option>
                                            <option value="N">No</option>
                                        </select>
                                    </div>
                                    <hr>
                                    <h4>Previous qualifications achieved </h4>
                                    <div class="form-group">
                                        <label for="prior_education">Have you SUCCESSFULLY completed any of the qualifications listed? </label>
                                        <select name="prior_education" class="form-control" id="prior_education" onchange="priorYesNo(this);">
                                            <option value="" selected disabled>-- Select Prior Education --</option>
                                            <option value="Y">Yes</option>
                                            <option value="N">No</option>
                                        </select>
                                    </div>
                                    <div id="prior" class="form-group" style='display: none;'>
                                        <label for="prior_education_achievement_identifier">If YES, choose applicable option.</label>
                                        <select class="form-control" name="prior_education_achievement_identifier" id="prior_education_achievement_identifier">
                                            <option value="">-- Select Prior Education Achievement Identifier --</option>
                                            @foreach(getIdentifiers('PRIOR EDUCATIONAL ACHIEVEMENT IDENTIFIER') as $prior_education_achievement_identifier)
                                            <option value="{{ $prior_education_achievement_identifier->value }}">{{ $prior_education_achievement_identifier->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <hr>
                                    <h4>Employment</h4>
                                    <div class="form-group">
                                        <label for="labour_force_status_identifier">Of the following categories, which BEST describes your current employment status? (Choose One) </label>
                                        <select class="form-control" name="labour_force_status_identifier" id="labour_force_status_identifier">
                                            <option value="">-- Select Labour Force Status Identifier --</option>
                                            @foreach(getIdentifiers('LABOUR FORCE STATUS IDENTIFIER') as $labour_force_status_identifier)
                                            <option value="{{ $labour_force_status_identifier->value }}">{{ $labour_force_status_identifier->description }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">For casual, seasonal, contract and shift work, use the current number of hours worked per week to determine whether full time (35 hours or more per week) or part-time employed (less than 35 hours per week). </div>
                                    </div>
                                    <hr>
                                    <h4>Study Reason</h4>
                                    <div class="form-group">
                                        <label for="study_reason">Of the following categories, select the one which BEST describes the main reason you are undertaking this course/traineeship/apprenticeship (Choose One) </label>
                                        <select class="form-control" name="study_reason" id="study_reason">
                                            <option value="">-- Select Study Reason --</option>
                                            @foreach(getIdentifiers('STUDY REASON IDENTIFIER') as $study_reason)
                                            <option value="{{ $study_reason->value }}">{{ $study_reason->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <hr>
                                    <h4>Funding Source</h4>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="funding_source_national">Funding Source National</label>
                                            <select class="form-control" name="funding_source_national" id="funding_source_national">
                                                <option value="">-- Select Funding Source National --</option>
                                                @foreach(getIdentifiers('FUNDING SOURCE - NATIONAL') as $funding_source_national)
                                                <option value="{{ $funding_source_national->value }}" @if($funding_source_national->value == 32) selected @endif>{{ $funding_source_national->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="funding_source_state_training_authority">Funding Source State Training Authority</label>
                                            <input type="text" name="funding_source_state_training_authority" class="form-control" id="funding_source_state_training_authority" placeholder="Enter Funding Source State Training Authority">
                                        </div>
                                    </div>
                                    <hr>
                                    <h4>Extra</h4>
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <label for="school_based_flag">School Based Flag</label>
                                            <select name="school_based_flag" class="form-control" id="school_based_flag" onchange="sbfYesNo(this);">
                                                <option value="" selected disabled>-- Select School Based Flag --</option>
                                                <option value="Y">Yes</option>
                                                <option value="N">No</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-9">
                                            <div id="school_based" class="row" style='display: none;'>
                                                <div class="form-group col-sm-4">
                                                    <label for="school_level_identifier">School Level Identifier</label>
                                                    <select class="form-control" name="school_level_identifier" id="school_level_identifier">
                                                        <option value="">-- Select School Level Identifier --</option>
                                                        @foreach(getIdentifiers('SCHOOL LEVEL IDENTIFIER') as $school_level_identifier)
                                                        <option value="{{ $school_level_identifier->value }}">{{ $school_level_identifier->description }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="school_type_identifier">School Type Identifier</label>
                                                    <select class="form-control" name="school_type_identifier" id="school_type_identifier">
                                                        <option value="">-- Select School Type Identifier --</option>
                                                        @foreach(getIdentifiers('SCHOOL TYPE IDENTIFIER') as $school_type_identifier)
                                                        <option value="{{ $school_type_identifier->value }}">{{ $school_type_identifier->description }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="specific_funding_identifier">Specific Funding Identifier</label>
                                                    <input type="text" name="specific_funding_identifier" class="form-control" id="specific_funding_identifier" placeholder="Enter Specific Funding Identifier">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <label for="statistical_area_level_1_identifier">School Statistical Area Level 1 Identifier</label>
                                            <input type="text" name="statistical_area_level_1_identifier" class="form-control" id="statistical_area_level_1_identifier" placeholder="Enter School Statistical Area Level 1 Identifier">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="statistical_area_level_2_identifier">School Statistical Area Level 2 Identifier</label>
                                            <input type="text" name="statistical_area_level_2_identifier" class="form-control" id="statistical_area_level_2_identifier" placeholder="Enter School Statistical Area Level 2 Identifier">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="hours_attended">Hours Attended</label>
                                            <input type="text" name="hours_attended" class="form-control" id="hours_attended" placeholder="Enter Hours Attended">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="survey_contact_status">Survey Contact Status</label>
                                            <select class="form-control" name="survey_contact_status" id="survey_contact_status">
                                                <option value="">-- Select Survey Contact Status --</option>
                                                @foreach(getIdentifiers('SURVEY CONTACT STATUS') as $survey_contact_status)
                                                <option value="{{ $survey_contact_status->value }}">{{ $survey_contact_status->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <label for="anzsco">Anzsco</label>
                                            <input type="text" name="anzsco" class="form-control" id="anzsco" placeholder="Enter Anzsco">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="anzsic">Anzsic</label>
                                            <input type="text" name="anzsic" class="form-control" id="anzsic" placeholder="Enter Anzsic">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label> <span class="required">*</span>
                                        <select name="status" class="form-control" id="status">
                                            <option value="" selected disabled>-- Select Status --</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    @if ($table == 'offer')
                                    <input type="hidden" name="is_enrolled" class="form-control" value="0">
                                    @endif
                                    <button type="button" class="btn btn-primary prev">Previous</button>
                                    <button type="submit" class="btn btn-primary submit">Submit</button>
                                </div>
                            </div>
                        </form>
                        <!-- </div> -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<!-- jquery-validation -->
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
    function myChangeFunction(input1) {
        var address = document.getElementById('address');
        address.value = input1.value;

        console.log('address: ', address.value);
        $('#address').trigger('input');
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#box-image')
                    .attr('src', e.target.result)
                    .width(80)
                    .height(80);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#agent_id').change(function() {
        var agentID = $(this).val();
        if (agentID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/student/branch')}}?agent_id=" + agentID,
                success: function(res) {
                    if (res) {
                        $("#branch_id").empty();
                        $("#branch_id").append('<option value="">-- Select Branch --</option>');
                        $.each(res, function(key, value) {
                            $("#branch_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#branch_id").empty();
                    }
                }
            });
        } else {
            $("#branch_id").empty();
        }
    });

    $('#branch_id').change(function() {
        var branchID = $(this).val();
        if (branchID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/student/branch/user')}}?branch_id=" + branchID,
                success: function(res) {
                    if (res) {
                        $("#user_id").empty();
                        $("#user_id").append('<option value="">-- Select User --</option>');
                        $.each(res, function(key, value) {
                            $("#user_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#user_id").empty();
                    }
                }
            });
        } else {
            $("#user_id").empty();
        }
    });

    function sbfYesNo(that) {
        if (that.value == "Y") {
            document.getElementById("school_based").style.display = "flex";
        } else {
            document.getElementById("school_based").style.display = "none";
        }
    }

    function disYesNo(that) {
        if (that.value == "Y") {
            document.getElementById("disability").style.display = "block";
        } else {
            document.getElementById("disability").style.display = "none";
        }
    }

    function priorYesNo(that) {
        if (that.value == "Y") {
            document.getElementById("prior").style.display = "block";
        } else {
            document.getElementById("prior").style.display = "none";
        }
    }

    $(document).ready(function() {
        // Tab navigation
        $('#tabNavigation a').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });

        // Validation rules for tab 1
        var tab1ValidationRules = {
            salutation: {
                required: true,
            },
            first_name: {
                required: true,
            },
            family_name: {
                required: true,
            },
            date_of_birth: {
                required: true,
            },
            passport_no: {
                required: true,
            },
            citizenship_country: {
                required: true,
            },
            phone: {
                digits: true,
                minlength: 7
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 7
            },
            phone_work: {
                digits: true,
                minlength: 7
            },
            email: {
                required: true,
            },
            password: {
                required: true,
            },
            image: {
                required: true,
            },
            company_delivery_site_id: {
                required: true,
            },
            student_type: {
                required: true,
            },
        };

        var tab1ValidationMessages = {
            salutation: "Please choose one salutation",
            first_name: "Please enter first name",
            family_name: "Please enter family name",
            date_of_birth: "Please enter date of birth",
            passport_no: "Please enter passport no",
            citizenship_country: "Please choose one citizenship country",
            phone: {
                required: "Please enter phone number",
                digits: "Phone number must only be digits",
                minlength: "Phone number must be at least 7 characters long"
            },
            mobile: {
                required: "Please enter mobile number",
                digits: "Mobile number must only be digits",
                minlength: "Mobile number must be at least 7 characters long"
            },
            email: "Please enter email",
            password: "Please enter password",
            image: "Please upload image",
            company_delivery_site_id: "Please choose one site",
            student_type: "Please choose one type",
        };

        // Validation rules for tab 2
        var tab2ValidationRules = {
            overseas_address: {
                required: true
            },
            overseas_country_id: {
                required: true
            },
            emergency_contact_person: {
                required: true
            },
            emergency_contact_number: {
                required: true,
                digits: true,
                minlength: 7
            },
            emergency_contact_relation: {
                required: true
            },
        };

        // Validation messages for tab 2
        var tab2ValidationMessages = {
            overseas_address: "Please enter overseas address",
            overseas_country_id: "Please enter overseas country",
            emergency_contact_person: "Please enter emergency contact person",
            emergency_contact_number: {
                required: "Please enter emergency contact number",
                digits: "Emergency contact number must only be digits",
                minlength: "Emergency contact number must be at least 7 characters long"
            },
            emergency_contact_relation: "Please enter emergency contact relation",
        };


        // Validation rules for tab 2
        var tab2AddressValidationRules = {
            building_number: {
                maxlength: 50
            },
            flat_unit: {
                maxlength: 30
            },
            street_no: {
                required: true,
                maxlength: 15
            },
            street_address: {
                required: true,
                maxlength: 70
            },
            suburb: {
                required: true,
                maxlength: 50
            },
            state: {
                required: true
            },
            zip_code: {
                required: true,
                maxlength: 4
            },
            country_id: {
                required: true
            },
            overseas_address: {
                required: true
            },
            overseas_country_id: {
                required: true
            },
            emergency_contact_person: {
                required: true
            },
            emergency_contact_number: {
                required: true,
                digits: true,
                minlength: 7
            },
            emergency_contact_relation: {
                required: true
            },
        };

        // Validation messages for tab 2
        var tab2AddressValidationMessages = {
            building_number: {
                maxlength: "Building Name cannot be longer than 50 characters"
            },
            flat_unit: {
                maxlength: "Flat unit cannot be longer than 30 characters"
            },
            street_no: {
                required: "Please enter street number",
                maxlength: "Street Address cannot be longer than 15 characters"
            },
            street_address: {
                required: "Please enter street address",
                maxlength: "Street Address cannot be longer than 70 characters"
            },
            suburb: {
                required: "Please enter suburb",
                maxlength: "Street Address cannot be longer than 50 characters"
            },
            state: "Please enter state",
            zip_code: {
                required: "Please enter postal code",
                maxlength: "Postal cannot be longer than 4 characters"
            },
            country_id: "Please enter country",
            overseas_address: "Please enter overseas address",
            overseas_country_id: "Please enter overseas country",
            emergency_contact_person: "Please enter emergency contact person",
            emergency_contact_number: {
                required: "Please enter emergency contact number",
                digits: "Emergency contact number must only be digits",
                minlength: "Emergency contact number must be at least 7 characters long"
            },
            emergency_contact_relation: "Please enter emergency contact relation",
        };

        // Validation rules for tab 4
        var tab4ValidationRules = {
            salutation: {
                required: true,
            },
            first_name: {
                required: true,
            },
            family_name: {
                required: true,
            },
            date_of_birth: {
                required: true,
            },
            passport_no: {
                required: true,
            },
            citizenship_country: {
                required: true,
            },
            phone: {
                digits: true,
                minlength: 7
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 7
            },
            phone_work: {
                digits: true,
                minlength: 7
            },
            email: {
                required: true,
            },
            password: {
                required: true,
            },
            image: {
                required: true,
            },
            company_delivery_site_id: {
                required: true,
            },
            student_type: {
                required: true,
            },
            overseas_address: {
                required: true
            },
            overseas_country_id: {
                required: true
            },
            emergency_contact_person: {
                required: true
            },
            emergency_contact_number: {
                required: true,
                digits: true,
                minlength: 7
            },
            emergency_contact_relation: {
                required: true
            },
            status: {
                required: true
            },
            specific_funding_identifier: {
                maxlength: 10
            },
            statistical_area_level_1_identifier: {
                maxlength: 11
            },
            statistical_area_level_2_identifier: {
                maxlength: 9
            },
            hours_attended: {
                maxlength: 4,
                digits: true
            },
            unique_student_identifier: {
                maxlength: 10
            },
            funding_source_state_training_authority: {
                maxlength: 3
            },
            anzsco: {
                maxlength: 6
            },
            anzsic: {
                maxlength: 4
            },
            student_id_national: {
                maxlength: 10
            },
            student_id_apprenticeships: {
                maxlength: 10
            },
        };

        // Validation messages for tab 3
        var tab4ValidationMessages = {
            salutation: "Please choose one salutation",
            first_name: "Please enter first name",
            family_name: "Please enter family name",
            date_of_birth: "Please enter date of birth",
            passport_no: "Please enter passport no",
            citizenship_country: "Please choose one citizenship country",
            phone: {
                digits: "Phone number must only be digits",
                minlength: "Phone number must be at least 7 characters long"
            },
            mobile: {
                required: "Please enter mobile number",
                digits: "Mobile number must only be digits",
                minlength: "Mobile number must be at least 7 characters long"
            },
            email: "Please enter email",
            password: "Please enter password",
            image: "Please upload image",
            company_delivery_site_id: "Please choose one site",
            student_type: "Please choose one type",
            overseas_address: "Please enter overseas address",
            overseas_country_id: "Please enter overseas country",
            emergency_contact_person: "Please enter emergency contact person",
            emergency_contact_number: {
                required: "Please enter emergency contact number",
                digits: "Emergency contact number must only be digits",
                minlength: "Emergency contact number must be at least 7 characters long"
            },
            emergency_contact_relation: "Please enter emergency contact relation",
            status: "Please select one status",
            specific_funding_identifier: "Specific Funding Identifier cannot be longer than 10 characters",
            statistical_area_level_1_identifier: "Statistical Area Level 1 Identifier cannot be longer than 11 characters",
            statistical_area_level_2_identifier: "Statistical Area Level 2 Identifier cannot be longer than 9 characters",
            hours_attended: {
                digits: "Hours Attended must only be digits",
                maxlength: "Hours Attended cannot be longer than 4 characters"
            },
            unique_student_identifier: "Unique Student Identifier cannot be longer than 10 characters",
            funding_source_state_training_authority: "Funding Source State Training Authority cannot be longer than 3 characters",
            anzsco: "Anzsco cannot be longer than 6 characters",
            anzsic: "Anzsic cannot be longer than 4 characters",
            student_id_national: "Student Id National cannot be longer than 10 characters",
            student_id_apprenticeships: "Student Id Apprenticeships cannot be longer than 10 characters",
        };

        // Initialize form validation
        $('#multiStepForm').validate({
            ignore: [],
            rules: tab1ValidationRules, // Set initial rules for tab 1
            messages: tab1ValidationMessages, // Set initial messages for tab 1
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });


        $('#address').on('input', function() {
            var address = $(this).val();
            console.log('address changed: ', address);
            if (address.length == 0) {
                console.log('no address changed');
                $('#multiStepForm').validate().settings.rules = tab2ValidationRules;
                $('#multiStepForm').validate().settings.messages = tab2ValidationMessages;
            } else if (address.length > 0) {
                console.log('address changed');
                $('#multiStepForm').validate().settings.rules = tab2AddressValidationRules;
                $('#multiStepForm').validate().settings.messages = tab2AddressValidationMessages;
            }
        });

        // Handle next button click
        $('.next').click(function() {
            var $activeTab = $('.tab-pane.active');
            var $nextTab = $activeTab.next('.tab-pane');

            if ($activeTab.attr('id') === 'personal') {
                if ($('#multiStepForm').valid()) {
                    $activeTab.removeClass('active show');
                    $nextTab.addClass('active show');
                    $('#tabNavigation a[href="#' + $nextTab.attr('id') + '"]').tab('show');
                }
            } else if ($activeTab.attr('id') === 'contact') {
                if ($('#multiStepForm').valid()) {
                    $activeTab.removeClass('active show');
                    $nextTab.addClass('active show');
                    $('#tabNavigation a[href="#' + $nextTab.attr('id') + '"]').tab('show');
                }
            } else if ($activeTab.attr('id') === 'agent') {
                if ($('#multiStepForm').valid()) {
                    $activeTab.removeClass('active show');
                    $nextTab.addClass('active show');
                    $('#tabNavigation a[href="#' + $nextTab.attr('id') + '"]').tab('show');
                }
            } else if ($activeTab.attr('id') === 'other') {
                if ($('#multiStepForm').valid()) {
                    $activeTab.removeClass('active show');
                    $nextTab.addClass('active show');
                    $('#tabNavigation a[href="#' + $nextTab.attr('id') + '"]').tab('show');
                }
            }
        });

        // Handle previous button click
        $('.prev').click(function() {
            var $activeTab = $('.tab-pane.active');
            var $prevTab = $activeTab.prev('.tab-pane');

            $activeTab.removeClass('active show');
            $prevTab.addClass('active show');
            $('#tabNavigation a[href="#' + $prevTab.attr('id') + '"]').tab('show');
        });

        // Handle tab change event
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            var targetTab = $(e.target).attr("href"); // activated tab
            if (targetTab === '#contact') {
                // Set validation rules for tab 2
                var address = document.getElementById('address');
                console.log('address:', address);
                console.log('address:', address.value.length);
                if (address.value.length == 0) {
                    console.log('no address');
                    $('#multiStepForm').validate().settings.rules = tab2ValidationRules;
                    $('#multiStepForm').validate().settings.messages = tab2ValidationMessages;
                } else if (address.value.length > 0) {
                    console.log('address');
                    $('#multiStepForm').validate().settings.rules = tab2AddressValidationRules;
                    $('#multiStepForm').validate().settings.messages = tab2AddressValidationMessages;
                }

                // $('#multiStepForm').valid(); // Trigger validation
            } else if (targetTab === '#personal') {
                // Set validation rules for tab 1
                $('#multiStepForm').validate().settings.rules = tab1ValidationRules;
                $('#multiStepForm').validate().settings.messages = tab1ValidationMessages;
                // $('#multiStepForm').valid(); // Trigger validation
            } else if (targetTab === '#other') {
                // Set validation rules for tab 4
                $('#multiStepForm').validate().settings.rules = tab4ValidationRules;
                $('#multiStepForm').validate().settings.messages = tab4ValidationMessages;
                // $('#multiStepForm').valid(); // Trigger validation
            }
        });

        // Handle submit button click
        $('.submit').click(function() {
            var $activeTab = $('.tab-pane.active');
            var $prevTab = $activeTab.prev('.tab-pane');

            if ($activeTab.attr('id') === 'other') {
                $('#multiStepForm').validate();
            }
        });
    });
</script>
@endsection