@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        @livewire('inventory-management-form', key(now()))
    </div>
</div>
@endsection
