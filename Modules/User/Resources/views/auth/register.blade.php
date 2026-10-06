@extends('user::layouts.register')
@section('title', 'Admin | Register')

@section('content')
<div class="container">
    <div class="title">LMS - Registration</div>
    <div class="content">
        <form action="{{ route('register') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="user-details">
                <div class="input-box">
                    <span class="details">First Name</span>
                    <input type="text" class="form-control" placeholder="First Name" name="first_name" required>
                </div>
                <div class="input-box">
                    <span class="details">Family Name</span>
                    <input type="text" class="form-control" placeholder="Family Name" name="family_name" required>
                </div>
                <div class="input-box">
                    <span class="details">Email</span>
                    <input type="text" placeholder="Email" name="email" required>
                </div>
                <div class="input-box">
                    <span class="details">Password</span>
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div>
                <!-- <div class="input-box">
                    <span class="details">Confirm Password</span>
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div> -->
                <div class="input-box">
                    <span class="details">Phone Number</span>
                    <input type="text" class="form-control" placeholder="Phone" name="phone" required>
                </div>
                <div class="input-box">
                    <span class="details">C.E.O.</span>
                    <input type="text" class="form-control" placeholder="C.E.O" name="company_ceo" required>
                </div>
                <div class="input-box">
                    <span class="details">Upload Image</span>
                    <input type="file" class="form-control" placeholder="Upload Image" name="image" required>
                </div>
                <div class="input-box">
                    <span class="details">Country</span>
                    <select class="form-control" name="country_id">
                        <option value="">-- Select Country --</option>
                        @foreach($countries as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="gender-details">
                <div class="title">Company Details</div>
            </div>
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Company Name</span>
                    <input type="text" class="form-control" placeholder="Company Name" name="company_name" required>
                </div>
                <div class="input-box">
                    <span class="details">Trading Name</span>
                    <input type="text" class="form-control" placeholder="Trading Name" name="trading_name" required>
                </div>
                <div class="input-box">
                    <span class="details">RTO Number</span>
                    <input type="text" class="form-control" placeholder="RTO Number" name="rto_no">
                </div>
                <div class="input-box">
                    <span class="details">CRICOS Number</span>
                    <input type="text" class="form-control" placeholder="CRICOS Number" name="cricos_no">
                </div>
                <div class="input-box">
                    <span class="details">Company Email</span>
                    <input type="email" class="form-control" placeholder="Company Email" name="company_email" required>
                </div>
                <div class="input-box">
                    <span class="details">Company Phone</span>
                    <input type="number" class="form-control" placeholder="Company Phone" name="company_phone" required>
                </div>
                <div class="input-box">
                    <span class="details">Company Logo</span>
                    <input type="file" class="form-control" placeholder="Company Logo" name="logo">
                </div>
                <div class="input-box">
                    <span class="details">Company Building Name</span>
                    <input type="text" class="form-control" placeholder="Company Building Name" name="company_building_number">
                </div>
                <div class="input-box">
                    <span class="details">Company Flat/Unit</span>
                    <input type="text" class="form-control" placeholder="Company Flat/Unit" name="company_flat_unit">
                </div>
                <div class="input-box">
                    <span class="details">Company Street Number</span>
                    <input type="text" class="form-control" placeholder="Company Street Number" name="company_street_no" required>
                </div>
                <div class="input-box">
                    <span class="details">Company Street Address</span>
                    <input type="text" class="form-control" placeholder="Company Street Address" name="company_street_address" required>
                </div>
                <div class="input-box">
                    <span class="details">Company P.O.Box</span>
                    <input type="text" class="form-control" placeholder="Company P.O.Box" name="company_p_o_box">
                </div>
                <div class="input-box">
                    <span class="details">Company Subrub</span>
                    <input type="text" class="form-control" placeholder="Company Suburb" name="company_suburb" required>
                </div>
                <div class="input-box">
                    <span class="details">Company State</span>
                    <select class="form-control" name="company_state" id="company_state" required>
                        <option value="">-- Select State --</option>
                        @foreach(getStates() as $state)
                        <option value="{{ $state->value }}">{{ $state->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-box">
                    <span class="details">Company Postal Code</span>
                    <input type="number" class="form-control" placeholder="Company Postal Code" name="company_postal_code" required>
                </div>
                <div class="input-box">
                    <span class="details">Country</span>
                    <select class="form-control" name="company_country_id">
                        <option value="">-- Select Company Country --</option>
                        @foreach($countries as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="gender-details">
                <div class="title">Company Delivery Site Details</div>
            </div>
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Company Delivery Site Name</span>
                    <input type="text" class="form-control" placeholder="Company Delivery Site Name" name="site_name" required>
                </div>
                <div class="input-box">
                    <span class="details">Company Delivery Site Phone</span>
                    <input type="number" class="form-control" placeholder="Company Delivery Phone" name="site_phone" required>
                </div>
                <div class="input-box">
                    <span class="details">Company Delivery Site Building Name</span>
                    <input type="text" class="form-control" placeholder="Company Delivery Site Building Name" name="site_building_name">
                </div>
                <div class="input-box">
                    <span class="details">Company Delivery Site Flat/Unit</span>
                    <input type="text" class="form-control" placeholder="Company Delivery Site Flat/Unit" name="site_flat_unit">
                </div>
                <div class="input-box">
                    <span class="details">Company Delivery Site Street Number</span>
                    <input type="text" class="form-control" placeholder="Company Delivery Site Street Number" name="site_street_no" required>
                </div>
                <div class="input-box">
                    <span class="details">Company Delivery Site Address</span>
                    <input type="text" class="form-control" placeholder="Company Delivery Site Street Address" name="site_street_address" required>
                </div>
                <div class="input-box">
                    <span class="details">Company  Delivery Site P.O.Box</span>
                    <input type="text" class="form-control" placeholder="Company  Delivery Site P.O.Box" name="site_p_o_box">
                </div>
                <div class="input-box">
                    <span class="details">Company Delivery Site Subrub</span>
                    <input type="text" class="form-control" placeholder="Company Delivery Site Suburb" name="site_suburb" required>
                </div>
                <div class="input-box">
                    <span class="details">Company Delivery Site State</span>
                    <select class="form-control" name="site_state" id="site_state" required>
                        <option value="">-- Select State --</option>
                        @foreach(getStates() as $state)
                        <option value="{{ $state->value }}">{{ $state->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-box">
                    <span class="details">Company Delivery Site Postal Code</span>
                    <input type="number" class="form-control" placeholder="Company Delivery Site  Postal Code" name="site_postal_code" required>
                </div>
                <div class="input-box">
                    <span class="details">Company Delivery Site Country</span>
                    <select class="form-control" name="site_country_id">
                        <option value="">-- Select Company Delivery Site Country --</option>
                        @foreach($countries as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="button">
                <input type="submit" value="Register">
            </div>
        </form>
    </div>
</div>
@endsection