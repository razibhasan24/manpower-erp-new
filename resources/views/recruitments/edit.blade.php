@extends('layouts.app')

@section('title', 'Edit Recruitment')

@section('content')
    <style>
        /* =========================================
       PROFESSIONAL GREEN INPUT HOVER EFFECT
    ========================================= */

        /* All Form Fields */
        .form-control,
        .form-select,
        textarea.form-control {
            border: 1px solid #ced8d0;
            border-radius: 8px;
            background-color: #ffffff;
            transition: all .25s ease-in-out;
        }

        /* Mouse Hover - Green Background */
        .form-control:hover,
        .form-select:hover,
        textarea.form-control:hover {
            background-color: #edfff2 !important;
            border-color: #16a34a !important;
            box-shadow: 0 0 7px rgba(22, 163, 74, .20);
        }

        /* Input Focus - Green Glow */
        .form-control:focus,
        .form-select:focus,
        textarea.form-control:focus {
            background-color: #f0fff4 !important;
            border: 1px solid #16a34a !important;

            box-shadow: 0 0 0 3px rgba(22, 163, 74, .18) !important;

            outline: none;
            transition: all .25s ease-in-out;
        }

        /* Placeholder */
        .form-control::placeholder,
        textarea.form-control::placeholder {
            color: #94a3a0;
            transition: color .2s ease;
        }

        .form-control:focus::placeholder,
        textarea.form-control:focus::placeholder {
            color: #16a34a;
        }

        /* Select Dropdown */
        .form-select:hover,
        .form-select:focus {
            cursor: pointer;
        }

        /* File Input */
        input[type="file"].form-control:hover {
            background-color: #edfff2 !important;
        }

        /* Readonly Fields */
        .form-control[readonly]:hover {
            background-color: #f0fff4 !important;
            border-color: #16a34a !important;
        }

        /* Disabled Fields */
        .form-control:disabled,
        .form-select:disabled {
            background-color: #e9ecef !important;
            cursor: not-allowed;
            box-shadow: none !important;
        }

        /* Validation Error */
        .form-control.is-invalid:hover,
        .form-select.is-invalid:hover {
            border-color: #dc3545 !important;
            background-color: #fff5f5 !important;
        }

        /* Smooth Animation */
        @media (prefers-reduced-motion: reduce) {

            .form-control,
            .form-select,
            textarea.form-control {
                transition: none;
            }
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Edit Recruitment</h1>

        <x-button href="{{ route('recruitments.show', $recruitment) }}" variant="dark" size="sm">
            <i class="fas fa-arrow-left me-1"></i> Back to details
        </x-button>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('recruitments.update', $recruitment) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('recruitments._form')

                <div class="d-flex gap-2 mt-4">
                    <x-button type="submit" variant="success">
                        <i class="fas fa-save me-1"></i> Update Recruitment
                    </x-button>

                    <x-button href="{{ route('recruitments.show', $recruitment) }}" variant="danger">
                        Cancel
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
