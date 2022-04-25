@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Inventory Audit List</h5>
        <div class="float-right">
            <a href="{{ route('users.add') }}" class="btn btn-primary">Inventory Management Audits</a>
        </div>
    </div>
    <div class="card-body table-bordered-style">
        <div class="table-responsive">

            @livewire('audit-table', ['function' => 'getInventoryAudits'])

        </div>
    </div>
</div>

@endsection
