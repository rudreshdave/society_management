@extends('layouts/blankLayout')

@section('title', 'Society Registration')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])

<style>
    /* ===== GLOBAL ===== */
    body {
        margin: 0;
        background: #f4f6ff;
        font-family: Inter, sans-serif;
    }

    /* ===== SPLIT LAYOUT ===== */
    .split-wrapper {
        min-height: 100vh;
        display: flex;
    }

    /* ===== LEFT IMAGE ===== */
    .left-image {
        flex: 1;
        background:
            linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)),
            url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c') center/cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        padding: 60px;
    }

    .left-content {
        max-width: 420px;
    }

    .left-content h2 {
        font-size: 2.5rem;
        font-weight: 700;
    }

    .left-content p {
        opacity: .9;
    }

    /* ===== RIGHT FORM ===== */
    .right-form {
        flex: 1;
        overflow-y: auto;
    }

    .register-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 40px 20px;
    }

    .register-container {
        max-width: 900px;
        width: 100%;
        margin: auto;
    }

    /* ===== STEP INDICATOR ===== */
    .stepper {
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
    }

    .step {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #999;
    }

    .step.active {
        color: #696cff;
    }

    .step-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #e5e7ff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .step.active .step-circle {
        background: #696cff;
        color: #fff;
    }

    .step-line {
        width: 80px;
        height: 2px;
        background: #ddd;
        margin: 0 15px;
    }

    /* ===== CARD ===== */
    .section-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .07);
        padding: 30px;
    }

    /* ===== FORM ===== */
    .form-label {
        font-weight: 500;
    }

    .form-label.required::after {
        content: " *";
        color: #dc3545;
        font-weight: 600;
    }

    .form-control {
        border-radius: 10px;
        padding: 12px 14px;
    }

    .form-control:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 4px rgba(105, 108, 255, .15);
    }

    /* ===== BUTTON ===== */
    .btn-primary {
        background: linear-gradient(135deg, #696cff, #5a5df0);
        border: none;
        border-radius: 12px;
        padding: 12px 36px;
        font-weight: 600;
    }

    /* ===== ANIMATION ===== */
    .step-content {
        display: none;
        animation: fadeSlide .4s ease;
    }

    .step-content.active {
        display: block;
    }

    .error {
        display: block;
        margin-top: 6px;
        font-size: 0.85rem;
        color: #dc3545;
    }

    @keyframes fadeSlide {
        from {
            opacity: 0;
            transform: translateX(20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* ===== RESPONSIVE ===== */
    @media(max-width:991px) {
        .left-image {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="split-wrapper">

    {{-- LEFT IMAGE --}}
    <div class="left-image">
        <div class="left-content">
            <h2 style="color:white;">Smart Society Platform</h2>
            <p class="mt-3">
                Register your society in just 2 simple steps and
                start managing residents, billing & maintenance.
            </p>
            <ul class="mt-4">
                <li>✔ Secure Admin Setup</li>
                <li>✔ Simple & Fast Onboarding</li>
                <li>✔ Trusted by Communities</li>
            </ul>
        </div>
    </div>

    {{-- RIGHT FORM --}}
    <div class="right-form">
        <div class="register-wrapper">
            <div class="register-container">

                <h3 class="text-center mb-2">Register Society</h3>

                {{-- STEPPER --}}
                <div class="stepper">
                    <div class="step active" id="step1Indicator">
                        <div class="step-circle">1</div> Society
                    </div>
                    <div class="step-line"></div>
                    <div class="step" id="step2Indicator">
                        <div class="step-circle">2</div> Society Admin
                    </div>
                </div>

                <form id="societyRegisterForm" method="POST" action="{{ route('register.submit') }}">
                    @csrf

                    {{-- STEP 1 --}}
                    <div class="section-card step-content active" id="step1">
                        <h5 class="mb-4">Society Information</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Society Name</label>
                                <input type="text" name="society_name" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Registration No</label>
                                <input type="text" name="registration_no" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Address</label>
                            <input type="text" name="address_line_1" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address 2</label>
                            <input type="text" name="address_line_2" class="form-control">
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">State</label>
                                <select name="state_id" id="state_id" class="form-select">
                                    <option value="">Select State</option>
                                    @foreach($states as $id => $state)
                                    $state
                                    <option value="{{ $id }}">{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">City</label>
                                <select name="city_id" id="city_id" class="form-select">
                                    <option value="">Select City</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">Pincode</label>
                                <input type="text" name="pincode" class="form-control">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-primary" id="nextStep">
                                Next →
                            </button>
                        </div>
                    </div>

                    {{-- STEP 2 --}}
                    <div class="section-card step-content" id="step2">
                        <h5 class="mb-4">Administrator Details</h5>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="mobile" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer toggle-password"><i class="icon-base bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="confirm_password" class="form-control" name="confirm_password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="confirm_password" />
                                    <span class="input-group-text cursor-pointer toggle-password"><i class="icon-base bx bx-hide"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-light" id="prevStep">
                                ← Back
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Create Society
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
@vite([
'resources/js/app.js',
'resources/js/register.js'
])

@endsection