@extends('layouts.app')
@section('title', 'Add Payment Mode')
@section('content')
<h1 class="mb-3">Add Payment Mode</h1>
<div class="card"><div class="card-body">
    @include('accounting.payment-modes._form', ['action' => route('accounting.payment-modes.store'), 'method' => 'POST'])
</div></div>
@endsection
