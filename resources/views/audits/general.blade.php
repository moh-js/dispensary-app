@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>General Audit List</h5>
    </div>
    <div class="card-body table-bordered-style">
        <div class="table-responsive">

            @livewire('audit-table', ['function' => 'getGeneralAudits', 'component' => 'audit-table-general-row'])

        </div>
    </div>
</div>

@endsection
