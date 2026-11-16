<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h1 class="h4 mb-0">{{ $company['name'] ?? config('app.name') }}</h1>
                <div class="text-muted small">
                    {{ $company['address'] ?? '' }}
                    @if (! empty($company['phone'])) &middot; {{ $company['phone'] }} @endif
                    @if (! empty($company['email'])) &middot; {{ $company['email'] }} @endif
                </div>
                <h2 class="h5 mt-3">{{ $title }}</h2>
                <div class="text-muted small">Generated: {{ $generatedAt }}</div>
            </div>
            <button onclick="window.print()" class="btn btn-primary no-print">Print</button>
        </div>

        @if (! empty($summary))
            <div class="mb-3">
                @foreach ($summary as $label => $value)
                    <span class="me-3"><strong>{{ $label }}:</strong> {{ is_array($value) ? collect($value)->map(fn ($v, $k) => $k.': '.$v)->implode(', ') : $value }}</span>
                @endforeach
            </div>
        @endif

        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    @foreach ($columns as $label)
                        <th>{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr>
                        @foreach (array_keys($columns) as $key)
                            <td>{{ $row[$key] ?? '' }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr><td colspan="{{ count($columns) }}" class="text-center text-muted">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <script>window.addEventListener('load', () => { /* print is manual via the button */ });</script>
</body>
</html>
