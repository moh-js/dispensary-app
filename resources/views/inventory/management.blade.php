@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        @livewire('inventory-management-form', ['item_id' => $item_id], key(now()))
    </div>
</div>
@endsection
