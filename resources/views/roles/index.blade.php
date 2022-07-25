@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h5>User List</h5>
        <div class="float-right">
                <a href="{{ route('roles.add') }}" class="btn btn-primary">Add Role</a>
        </div>
    </div>
    <div class="card-body table-bordered-style">
        <div class="table-responsive">
            <table class="table table-striped table-inverse table-sm">
                <thead class="thead-inverse">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $key => $role)
                            <tr>
                                <td scope="row">{{ ++$key }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    @if ($role->deleted_at)
                                        <span class="badge badge-danger">Inactive</span>
                                    @else
                                        <span class="badge badge-primary">Active</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($role->deleted_at)
                                        <a href="javascript:void(0)" onclick="$('#{{ $role->name }}').submit()" title="Activate" class="text-primary">
                                            <i class="fa fa-window-restore"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('roles.show', $role->name) }}" title="Permission">
                                            <i class="fas fa-lock"></i>
                                        </a>

                                        <a class="ml-3" href="{{ route('roles.edit', $role->name) }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="javascript:void(0)" onclick="$('#{{ $role->name }}').submit()" title="Deactivate" class="text-danger ml-3">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @endif

                                    <form id="{{ $role->name }}" action="{{ route('roles.destroy', $role->name) }}" method="post">@csrf @method('DELETE')</form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
