<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Candidate CV</title>

    <style>
        @page {
            margin: 20px 25px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333333;
            font-size: 10px;
            line-height: 1.5;
        }

        .top-bar {
            height: 6px;
            background-color: #0056b3;
            margin-bottom: 20px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table>tbody>tr>td {
            vertical-align: top;
        }

        .left-col {
            width: 32%;
            padding-right: 20px;
            border-right: 1px solid #e5e7eb;
        }

        .right-col {
            width: 68%;
            padding-left: 25px;
        }

        /* Profile Photo */
        .profile-img {
            width: 120px;
            height: 150px;
            /* object-fit: cover;  <-- DomPDF এর জন্য এটি সরানো হয়েছে */
            border: 2px solid #0056b3;
            border-radius: 5px;
            margin-bottom: 15px;
            display: block;
        }

        .photo-placeholder {
            width: 120px;
            height: 150px;
            border: 2px solid #0056b3;
            background-color: #f1f5f9;
            text-align: center;
            color: #64748b;
            font-weight: bold;
            padding-top: 65px;
            margin-bottom: 15px;
        }

        .name {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 12px;
            color: #0056b3;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .tracking {
            font-size: 9px;
            color: #6b7280;
            margin-bottom: 15px;
        }

        /* Section Titles */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #0056b3;
            text-transform: uppercase;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 3px;
            margin: 20px 0 10px 0;
            letter-spacing: 0.5px;
        }

        .left-col .section-title {
            border-bottom: 1px solid #d1d5db;
            color: #374151;
            font-size: 10px;
            margin-top: 25px;
        }

        .contact-item {
            margin-bottom: 8px;
            font-size: 9.5px;
            word-wrap: break-word;
        }

        .contact-item strong {
            color: #111827;
            display: block;
            font-size: 10px;
        }

        .info-row {
            margin-bottom: 7px;
        }

        .info-label {
            font-weight: bold;
            color: #4b5563;
            width: 100px;
            display: inline-block;
        }

        .about-text {
            font-size: 10px;
            color: #4b5563;
            text-align: justify;
            margin-bottom: 15px;
        }

        .exp-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .exp-table td {
            padding: 4px 0;
            vertical-align: top;
            font-size: 10px;
        }

        .exp-label {
            width: 110px;
            font-weight: bold;
            color: #111827;
        }

        .exp-value {
            color: #4b5563;
        }

        .responsibilities-title {
            font-weight: bold;
            margin-top: 8px;
            margin-bottom: 4px;
            color: #111827;
        }

        .custom-list {
            margin: 0 0 15px 0;
            padding-left: 15px;
            list-style-type: none;
        }

        .custom-list li {
            position: relative;
            margin-bottom: 4px;
            font-size: 10px;
            color: #4b5563;
        }

        .custom-list li:before {
            content: "•";
            color: #0056b3;
            font-size: 14px;
            position: absolute;
            left: -12px;
            top: -2px;
        }

        .footer {
            margin-top: 25px;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            font-size: 8px;
            color: #9ca3af;
            text-align: center;
        }

        .clear {
            clear: both;
        }

        .text-muted {
            color: #6b7280;
        }
    </style>
</head>

<body>

    <div class="top-bar"></div>

    <table class="main-table">
        <tbody>
            <tr>

                {{-- LEFT COLUMN --}}
                <td class="left-col">

                    {{-- Candidate Photo --}}
                    @if (!empty($photoData))
                        {{-- ✅ খেয়াল করুন: {{ }} এর বদলে {!! !!} ব্যবহার করা হয়েছে --}}
                        <img src="{!! $photoData !!}"
                            style="width: 120px; height: 150px; border: 2px solid #0056b3; display: block; margin-bottom: 15px;">
                    @else
                        <div
                            style="width: 120px; height: 150px; border: 2px solid #0056b3; background-color: #f1f5f9; text-align: center; color: #64748b; font-weight: bold; padding-top: 65px; margin-bottom: 15px;">
                            No Photo
                        </div>
                    @endif

                    <h1 class="name">
                        {{ $holder?->full_name ?? 'Candidate' }}
                    </h1>

                    <div class="subtitle">
                        CURRICULUM VITAE
                    </div>

                    <div class="tracking">
                        ID: {{ $recruitment->tracking_number }}
                    </div>

                    {{-- CONTACT --}}
                    <div class="section-title">CONTACT</div>

                    <div class="contact-item">
                        <strong>Phone</strong>
                        {{ $holder?->phone ?? '—' }}
                    </div>

                    @if ($holder?->email)
                        <div class="contact-item">
                            <strong>Email</strong>
                            {{ $holder->email }}
                        </div>
                    @endif

                    <div class="contact-item">
                        <strong>Address</strong>
                        {{ $holder?->present_address ?? '—' }}
                    </div>

                    {{-- PERSONAL DETAILS --}}
                    <div class="section-title">PERSONAL DETAILS</div>

                    <div class="info-row">
                        <span class="info-label">Nationality:</span>
                        {{ $holder?->nationality ?? '—' }}
                    </div>

                    <div class="info-row">
                        <span class="info-label">Date of Birth:</span>
                        {{ $holder?->dob?->format('d M Y') ?? '—' }}
                    </div>

                    <div class="info-row">
                        <span class="info-label">Gender:</span>
                        {{ ucfirst($holder?->gender ?? '—') }}
                    </div>

                    <div class="info-row">
                        <span class="info-label">Marital Status:</span>
                        {{ ucfirst($holder?->marital_status ?? '—') }}
                    </div>

                    <div class="info-row">
                        <span class="info-label">Religion:</span>
                        {{ $holder?->religion ?? '—' }}
                    </div>

                    <div class="info-row">
                        <span class="info-label">Passport No:</span>
                        {{ $holder?->passport_number ?? '—' }}
                    </div>

                    <div class="info-row">
                        <span class="info-label">Passport Expiry:</span>
                        {{ $holder?->passport_expiry_date?->format('d M Y') ?? '—' }}
                    </div>

                </td>

                {{-- RIGHT COLUMN --}}
                <td class="right-col">

                    {{-- ABOUT ME --}}
                    <div class="section-title" style="margin-top: 0;">
                        ABOUT ME
                    </div>

                    <div class="about-text">
                        {{ $recruitment->remarks ?: 'Dedicated and responsible candidate registered for overseas employment. Experienced in handling daily operations, maintaining strong communication, and achieving targets.' }}
                    </div>

                    {{-- WORK EXPERIENCE --}}
                    <div class="section-title">
                        WORK EXPERIENCE
                    </div>

                    <table class="exp-table">
                        <tbody>
                            <tr>
                                <td class="exp-label">Designation</td>
                                <td class="exp-value">
                                    : {{ $recruitment->position?->title ?? '—' }}
                                </td>
                            </tr>

                            <tr>
                                <td class="exp-label">Company</td>
                                <td class="exp-value">
                                    : {{ $recruitment->company?->name ?? '—' }}
                                </td>
                            </tr>

                            <tr>
                                <td class="exp-label">Location</td>
                                <td class="exp-value">
                                    : {{ $recruitment->country?->name ?? '—' }}
                                </td>
                            </tr>

                            <tr>
                                <td class="exp-label">Experience</td>
                                <td class="exp-value">
                                    : {{ $recruitment->local_experience_years ?? '0' }} Years (Local)
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="responsibilities-title">
                        Responsibilities:
                    </div>

                    <ul class="custom-list">
                        <li>Manage and supervise daily operations.</li>
                        <li>Maintain professional communication.</li>
                        <li>Complete assigned responsibilities.</li>
                        <li>Maintain good relationships with customers.</li>
                        <li>Work effectively with team members.</li>
                    </ul>

                    {{-- EDUCATION --}}
                    <div class="section-title">
                        EDUCATION
                    </div>

                    <table class="exp-table">
                        <tbody>
                            <tr>
                                <td class="exp-label">Degree</td>
                                <td class="exp-value">
                                    : {{ $recruitment->education_type ?? '—' }}
                                    {{ $recruitment->education_degree ?? '' }}
                                </td>
                            </tr>

                            <tr>
                                <td class="exp-label">Institution</td>
                                <td class="exp-value">
                                    : {{ $recruitment->education_school ?? '—' }}
                                </td>
                            </tr>

                            <tr>
                                <td class="exp-label">Year / Grade</td>
                                <td class="exp-value">
                                    : {{ $recruitment->education_year ?? '—' }}
                                    / {{ $recruitment->education_grade ?? '—' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- LANGUAGE SKILLS --}}
                    <div class="section-title">
                        LANGUAGE SKILLS
                    </div>

                    <div class="about-text">
                        {{ is_array($recruitment->language_proficiency)
                            ? implode(', ', $recruitment->language_proficiency)
                            : $recruitment->language_proficiency ?? '—' }}
                    </div>

                    {{-- PROFESSIONAL SKILLS --}}
                    <div class="section-title">
                        PROFESSIONAL SKILLS
                    </div>

                    <ul class="custom-list">
                        <li>Customer Service</li>
                        <li>Communication</li>
                        <li>Time Management</li>
                        <li>Team Leadership</li>
                    </ul>

                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Generated by Manpower ERP | Candidate CV
    </div>

</body>

</html>
