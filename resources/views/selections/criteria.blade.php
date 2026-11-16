@extends('layouts.app')
@section('title', __('Grading Criteria'))
@section('content')
<h1 class="mb-3">{{ __('Grading Criteria') }}</h1>
<p class="text-muted">{{ __('Criteria are configurable here and snapshotted onto each selection decision, so later changes never rewrite past decisions.') }}</p>
<form action="{{ route('selections.criteria.store') }}" method="POST" class="row g-2 mb-3">@csrf
    <div class="col-md-4"><input type="text" name="name" class="form-control" placeholder="{{ __('Criterion name') }}" required></div>
    <div class="col-md-2"><input type="number" step="0.01" name="max_score" class="form-control" placeholder="{{ __('Max score') }}" required></div>
    <div class="col-md-2"><input type="number" step="0.01" name="weight" class="form-control" placeholder="{{ __('Weight') }}"></div>
    <div class="col-md-2"><x-button type="submit">{{ __('Add Criterion') }}</x-button></div>
</form>
<div class="table-responsive"><table class="table table-striped"><thead><tr><th>{{ __('Name') }}</th><th>{{ __('Max Score') }}</th><th>{{ __('Weight') }}</th><th>{{ __('Active') }}</th></tr></thead><tbody>
@forelse($criteria as $criterion)<tr><td>{{ $criterion->name }}</td><td>{{ $criterion->max_score }}</td><td>{{ $criterion->weight }}</td><td>{{ $criterion->status }}</td></tr>@empty<tr><td colspan="4" class="text-center text-muted">{{ __('No criteria yet.') }}</td></tr>@endforelse
</tbody></table></div>
{{ $criteria->links() }}
@endsection
