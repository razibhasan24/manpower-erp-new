@extends('layouts.app')
@section('title', 'Edit Payment Mode')
@section('content')
<h1 class="mb-3">Edit Payment Mode</h1>
<div class="card"><div class="card-body">
    @include('accounting.payment-modes._form', ['action' => route('accounting.payment-modes.update', $item), 'method' => 'PUT'])
</div></div>
@endsection
