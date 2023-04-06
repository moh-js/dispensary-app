@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>User List</h5>
            <div class="float-right">
                <a href="{{ route('users.add') }}" class="btn btn-primary">Add User</a>
            </div>
        </div>
        <div class="card-body table-bordered-style">
            <div class="table-responsive">
                <table class="table table-striped table-inverse table-sm">
                    <thead class="thead-inverse">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <td scope="row">{{ ++$key }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach ($user->roles as $role)
                                        <span class="badge badge-info">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    {{ $user->phone ? '+255' . $user->phone : '' }}
                                </td>
                                <td>
                                    @if ($user->deleted_at)
                                        <span class="badge badge-danger">Inactive</span>
                                    @else
                                        <span class="badge badge-primary">Active</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($user->deleted_at)
                                        <a href="javascript:void(0)" onclick="$('#{{ $user->username }}').submit()"
                                            title="Activate" class="text-primary">
                                            <i class="fa fa-window-restore"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('users.edit', $user->username) }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="javascript:void(0)" onclick="$('#{{ $user->username }}-reset').submit()"
                                            title="Reset Password" class="text-warning ml-3">
                                            <i class="fas fa-unlock"></i>
                                        </a>

                                        <a href="javascript:void(0)" onclick="$('#{{ $user->username }}').submit()"
                                            title="Deactivate" class="text-danger ml-3">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @endif

                                    <form id="{{ $user->username }}" action="{{ route('users.destroy', $user->username) }}"
                                        method="post">@csrf @method('DELETE')</form>
                                    <form id="{{ $user->username }}-reset" action="{{ route('users.password.reset', $user->username) }}"
                                        method="post">@csrf @method('PUT')</form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
