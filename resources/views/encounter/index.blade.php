@extends('layouts.app')

@section('content')
    @livewire('encounter', ['encounter' => $encounter], key($encounter->id))
@endsection

@push('css')
    <style>
        label {
            font-weight: bold;
        }
    </style>
@endpush
