@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Service Audit List</h5>
    </div>
    <div class="card-body table-bordered-style">
        <div class="table-responsive">

            @livewire('audit-table', ['function' => 'getServiceAudits'])

        </div>
    </div>
</div>

@endsection
