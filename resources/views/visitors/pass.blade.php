@extends('layouts.app')
@section('title', 'Visitor Pass')
@section('content')
<div class="card mx-auto" style="max-width:480px"><div class="card-body text-center">
    <h3>VISITOR PASS</h3>
    <h4>{{ $item->pass_number }}</h4>
    <hr>
    <p><strong>Name:</strong> {{ $item->name }}</p>
    <p><strong>Phone:</strong> {{ $item->phone }}</p>
    <p><strong>Purpose:</strong> {{ $item->purpose }}</p>
    <p><strong>Person to Meet:</strong> {{ $item->person_to_meet }}</p>
    <p><strong>Entry:</strong> {{ $item->entry_time }}</p>
    <button class="btn btn-primary" onclick="window.print()">Print Pass</button>
    <a href="{{ route('visitors.show', $item) }}" class="btn btn-secondary">Back</a>
</div></div>
@endsection
