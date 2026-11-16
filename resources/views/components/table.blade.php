{{--
    x-table
    Two supported shapes:
      1. Legacy/table-wrapper: <x-table> ...full <table> markup... </x-table>
         (what every existing list screen uses)
      2. Header-driven: pass :headers="[...]" and put only <tr> rows in the slot.

    Props:
      - headers: array of column headings (optional)
      - empty: message shown when there are no rows (header-driven shape only)
      - total: total record count for the footer (optional)
--}}
@props(['headers' => [], 'empty' => null, 'total' => null])

@if($headers !== [])
    <div {{ $attributes->merge(['class' => 'table-responsive']) }}>
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'table-responsive']) }}>
        {{ $slot }}
    </div>
@endif

@if($total !== null)
    <div class="card-footer text-muted small">{{ number_format($total) }} {{ __('record(s)') }}</div>
@endif
