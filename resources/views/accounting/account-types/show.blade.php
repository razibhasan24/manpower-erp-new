@extends('layouts.app')
@section('title', 'Account Type')
@section('content')
    <h1 class="mb-3">{{ $item->name }}</h1>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <tr>
                    <th style="width:200px">Name</th>
                    <td>{{ $item->name }}</td>
                </tr>
                <tr>
                    <th>Normal Balance</th>
                    <td>{{ $item->normal_balance }}</td>
                </tr>
            </table>
            <h5 class="mt-4">Accounts</h5>
            <ul>
                @foreach ($item->accounts as $account)
                    <li>{{ $account->code }} — {{ $account->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
