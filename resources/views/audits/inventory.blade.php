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
            <table class="table table-striped table-inverse table-sm">
                <thead class="thead-inverse">
                    <tr>
                        <th>#</th>
                        <th>Action</th>
                        <th>Causer</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php
                            $created = 'primary';
                            $updated = 'warning';
                            $deleted = 'danger';
                        @endphp
                        @foreach ($audits as $key => $audit)
                            <tr>
                                <td scope="row">{{ $key + $audits->firstItem() }}</td>
                                <td>
                                    <span class=" badge badge-{{ ${$audit->event} }}">
                                        {{ $audit->event }}
                                    </span>
                                </td>
                                <td>{{ $audit->user->name }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
            </table>

            <div class="float-right">
                {{ $audits->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
