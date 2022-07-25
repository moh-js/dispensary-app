@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Service List</h5>
        @can('service-add')
            <div class="float-right">
                    <a href="{{ route('services.add') }}" class="btn btn-primary">Add Service</a>
            </div>
        @endcan
    </div>
    <div class="card-body table-bordered-style">
        <div class="table-responsive">
            <table class="table table-striped table-inverse table-sm">
                <thead class="thead-inverse">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th class="text-right">Price</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $key => $service)
                            <tr>
                                <td scope="row">{{ ++$key }}</td>
                                <td title="{{ $service->item->name??'' }}">{{ $service->name }}</td>
                                <td class="text-right">{{ $service->m_price }}</td>
                                <td>
                                    @if ($service->deleted_at)
                                        <span class="badge badge-danger">Inactive</span>
                                    @else
                                        <span class="badge badge-primary">Active</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($service->deleted_at)
                                        @can('service-activate')
                                            <a href="javascript:void(0)" onclick="$('#{{ $service->slug }}').submit()" title="Activate" class="text-primary">
                                                <i class="fa fa-window-restore"></i>
                                            </a>
                                        @endcan
                                    @else
                                        @can('service-update')
                                            <a href="{{ route('services.edit', $service->slug) }}" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('service-deactivate')
                                            <a href="javascript:void(0)" onclick="$('#{{ $service->slug }}').submit()" title="Deactivate" class="text-danger ml-3">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endcan
                                    @endif

                                    @can('service-deactivate')
                                        <form id="{{ $service->slug }}" action="{{ route('services.destroy', $service->slug) }}" method="post">@csrf @method('DELETE')</form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
