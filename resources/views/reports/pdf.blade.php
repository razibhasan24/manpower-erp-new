<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 18px; margin: 0; }
        .meta { color: #666; font-size: 10px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; text-align: left; }
        th { background: #f0f0f0; }
        .summary { margin: 12px 0; }
        .summary span { margin-right: 16px; }
    </style>
</head>
<body>
    <h1>{{ $company['name'] ?? config('app.name') }}</h1>
    <div class="meta">
        {{ $company['address'] ?? '' }}
        @if (! empty($company['phone'])) &middot; {{ $company['phone'] }} @endif
        @if (! empty($company['email'])) &middot; {{ $company['email'] }} @endif
    </div>
    <h2 style="font-size:14px;">{{ $title }}</h2>
    <div class="meta">Generated: {{ $generatedAt }}</div>

    @if (! empty($summary))
        <div class="summary">
            @foreach ($summary as $label => $value)
                <span><strong>{{ $label }}:</strong> {{ is_array($value) ? collect($value)->map(fn ($v, $k) => $k.': '.$v)->implode(', ') : $value }}</span>
            @endforeach
        </div>
    @endif

    <table>
        <thead>
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
                <tr><td colspan="{{ count($columns) }}">No records found.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
