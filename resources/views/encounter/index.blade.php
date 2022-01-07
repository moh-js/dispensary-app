@extends('layouts.app')

@section('content')
    @livewire('encounter', ['patient' => $patient], key($patient->id))
@endsection

@push('css')
    <style>
        label {
            font-weight: bold;
        }
    </style>
@endpush
