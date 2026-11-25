<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <style>
        @page {
            margin: 20px 25px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
        }

        .card {
            border: 2px solid #0a2a5e;
            border-radius: 6px;
            height: 340px;
            position: relative;
            padding: 0;
            overflow: hidden;
            background: #ffffff;
        }

        /* Side Strip */
        .side {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 42px;
            background: #0a2a5e;
            color: #ffffff;
        }

        .side-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-90deg);
            white-space: nowrap;
            font-weight: bold;
            letter-spacing: 2px;
            font-size: 10px;
            text-transform: uppercase;
        }

        .main {
            padding-right: 42px;
            height: 100%;
            position: relative;
        }

        /* Header */
        .header {
            background: #0a2a5e;
            color: #ffffff;
            padding: 8px 14px;
        }

        .header .brand {
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .header .title {
            font-size: 9px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #c9d6ea;
            margin-top: 2px;
        }

        /* Barcode */
        .barcode-wrap {
            text-align: center;
            padding: 5px 0 3px;
            border-bottom: 1px solid #e5e7eb;
            height: 65px;
        }

        .barcode-wrap img {
            height: 42px;
            max-width: 100%;
        }

        .barcode-label {
            font-size: 7px;
            letter-spacing: 2px;
            color: #6b7280;
            margin-top: 2px;
            text-transform: uppercase;
        }

        /* Candidate Photo - Small */
        .candidate-photo-box {
            position: absolute;
            top: 82px;
            right: 52px;
            width: 48px;
            height: 58px;
            text-align: center;
            z-index: 5;
        }

        .candidate-photo {
            width: 48px;
            height: 58px;
            object-fit: cover;
            border: 1px solid #0a2a5e;
            border-radius: 3px;
            display: block;
        }

        .photo-placeholder {
            width: 48px;
            height: 58px;
            border: 1px solid #0a2a5e;
            background: #f1f5f9;
            color: #64748b;
            font-size: 6px;
            text-align: center;
            padding-top: 24px;
        }

        /* Data Grid */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            padding: 0;
            margin: 0;
        }

        .data-table td {
            padding: 4px 10px;
            vertical-align: top;
            font-size: 10px;
            line-height: 1.4;
            border-bottom: 1px solid #f1f3f6;
            width: 50%;
            word-wrap: break-word;
        }

        .data-table td.cell-left {
            border-right: 1px solid #f1f3f6;
        }

        .label {
            display: block;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 1px;
        }

        .value {
            display: block;
            font-size: 11px;
            font-weight: bold;
            color: #0a2a5e;
        }

        .value.normal {
            font-weight: normal;
            color: #1f2937;
        }

        .status-pending {
            color: #b45309;
        }

        .status-ok {
            color: #14804a;
        }

        .status-fail {
            color: #b42318;
        }

        /* Footer */
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: #f6f8fb;
            border-top: 1px dashed #94a3b8;
            padding: 6px 14px;
            font-size: 9px;
            color: #475569;
        }

        .footer .left {
            float: left;
            font-weight: bold;
            text-transform: uppercase;
            color: #0a2a5e;
        }

        .footer .right {
            float: right;
            font-family: monospace;
        }
    </style>
</head>

<body>

    @php
        $medicalStatus = strtoupper($recruitment->medical?->result ?? 'PENDING');

        $statusClass = match ($medicalStatus) {
            'FIT', 'PASSED', 'OK' => 'status-ok',
            'UNFIT', 'FAILED', 'FAIL' => 'status-fail',
            default => 'status-pending',
        };

        $agentCode = $recruitment->agent ? 'AG' . str_pad((string) $recruitment->agent->id, 5, '0', STR_PAD_LEFT) : '—';
    @endphp

    <div class="card">

        {{-- Side Strip --}}
        <div class="side">
            <div class="side-text">
                {{ strtoupper(config('app.name', 'RECRUITMENT')) }}
            </div>
        </div>

        <div class="main">

            {{-- Header --}}
            <div class="header">
                <div class="brand">
                    {{ config('app.name', 'MANPOWER RECRUITMENT & OVERSEAS EMPLOYMENT ERP') }}
                </div>

                <div class="title">
                    Candidate Entry Card
                </div>
            </div>

            {{-- Barcode --}}
            <div class="barcode-wrap">

                @if (!empty($barcodeBase64))
                    <img src="{{ $barcodeBase64 }}" alt="Barcode">
                @elseif (!empty($barcodeSvg))
                    <div style="font-size: 10px; color: red;">
                        Barcode data missing
                    </div>
                @endif

                <div class="barcode-label">
                    Scan for verification
                </div>

            </div>

            {{-- Small Passport Photo --}}
            <div class="candidate-photo-box">

                @if (!empty($photoData))
                    <img src="{{ $photoData }}" alt="Candidate Photo" class="candidate-photo">
                @else
                    <div class="photo-placeholder">
                        NO PHOTO
                    </div>
                @endif

            </div>

            {{-- Data Grid --}}
            <table class="data-table">

                <tr>
                    <td class="cell-left">
                        <span class="label">Entry Date</span>
                        <span class="value normal">
                            {{ $recruitment->created_at?->format('d/m/Y') ?? '—' }}
                        </span>
                    </td>

                    <td>
                        <span class="label">Company</span>
                        <span class="value normal">
                            {{ $recruitment->company?->name ?? '—' }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td class="cell-left">
                        <span class="label">Candidate Code</span>
                        <span class="value">
                            {{ $recruitment->tracking_number ?? '—' }}
                        </span>
                    </td>

                    <td style="padding-right: 65px;">
                        <span class="label">Name</span>
                        <span class="value normal">
                            {{ $holder?->full_name ?? '—' }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td class="cell-left">
                        <span class="label">Passport No</span>
                        <span class="value normal">
                            {{ $holder?->passport_number ?? '—' }}
                        </span>
                    </td>

                    <td>
                        <span class="label">Passport Expiry</span>
                        <span class="value normal">
                            {{ $holder?->passport_expiry_date?->format('Y-m-d') ?? '—' }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td class="cell-left">
                        <span class="label">Medical Date</span>
                        <span class="value normal">
                            {{ $recruitment->medical?->medical_date?->format('d/m/Y') ?? '—' }}
                        </span>
                    </td>

                    <td>
                        <span class="label">Medical Status</span>
                        <span class="value {{ $statusClass }}">
                            {{ $medicalStatus }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td class="cell-left">
                        <span class="label">Country</span>
                        <span class="value normal">
                            {{ $recruitment->country?->name ?? '—' }}
                        </span>
                    </td>

                    <td>
                        <span class="label">Agent Code</span>
                        <span class="value normal">
                            {{ $agentCode }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td class="cell-left">
                        <span class="label">Agent Name</span>
                        <span class="value normal">
                            {{ $recruitment->agent?->name ?? '—' }}
                        </span>
                    </td>

                    <td>
                        <span class="label">Printed On</span>
                        <span class="value normal">
                            {{ now()->format('d/m/Y H:i') }}
                        </span>
                    </td>
                </tr>

            </table>

            {{-- Footer --}}
            <div class="footer">
                <span class="left">
                    {{ config('app.name') }} · Official Document
                </span>

                <span class="right">
                    {{ $recruitment->tracking_number }}
                </span>
            </div>

        </div>
    </div>

</body>

</html>
