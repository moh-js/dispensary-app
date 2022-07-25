@extends('layouts.app')

@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <h5>Permissions for {{title_case($role->name). ' Role'}}</h5>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('roles.grant', $role->name) }}" class="">
                @csrf

                    @php
                        $header = '';
                        $count = 0;
                    @endphp

                    @foreach ($permissions as $key => $object)
                    <div style="border-bottom:1px solid lightgrey; border-spacing: 5px;">
                        @php
                        $count = 0;
                        @endphp
                        <div class="row">
                            @foreach ($object as $permission)
                            @if (!$count)
                                <div class="text-danger col-12 mb-2" style="font-size:15px;">{{title_case($key).' Management'}}</div>
                                @php
                                    $count++
                                @endphp
                            @endif
                                <div class="col-3">
                                    <div class="checkbox checkbox-info checkbox-circle">
                                        <input id="checkbox{{ $permission->name }}" name="{{ $permission->name }}" value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name)? 'checked':'' }} type="checkbox">
                                        <label for="checkbox{{ $permission->name }}">
                                            {{ $permission->name}}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <br>
                    @endforeach

                    <div class="row">
                        <div class="form-group col-sm-12">
                            <button type="submit" class="btn btn-primary float-right">Grant</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>


@endsection
