@extends('layouts.app')

@section('content')

@livewire('inventory-item-list', ['category' => $category], key($category->slug))

@endsection
