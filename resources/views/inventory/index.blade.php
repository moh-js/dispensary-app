@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="">{{ $category->name }} Inventory List</h5>
        <div class="float-right">

            <a href="{{ route('items.add', $category->slug) }}" class="btn btn-primary  ml-3">Add {{ $category->name }}</a>
            <a href="{{ route('items.management') }}" class="btn btn-success ">Manage Inventory</a>
        </div>
    </div>
    <div class="card-body table-bordered-style">
        <div class="table-responsive">
            <table class="table table-striped table-inverse table-sm">
                <thead class="thead-inverse">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Expire Date</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $key => $item)
                            <tr>
                                <td scope="row">1</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->itemUnits()->sum('remain') }}</td>
                                <td>{{ $item->expire_date->format('dS M Y') }}</td>
                                <td>{{ $item->price }}</td>
                                <td>
                                    @if ($item->deleted_at)
                                        <span class="badge badge-danger">Inactive</span>
                                    @else
                                        <span class="badge badge-primary">Active</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->deleted_at)
                                        <a href="javascript:void(0)" onclick="$('#{{ $item->slug }}').submit()" title="Activate" class="text-primary">
                                            <i class="fa fa-window-restore"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('items.edit', $item->slug) }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="javascript:void(0)" onclick="$('#{{ $item->slug }}').submit()" title="Deactivate" class="text-danger ml-3">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @endif

                                    <form id="{{ $item->slug }}" action="{{ route('items.destroy', $item->slug) }}" method="post">@csrf @method('DELETE')</form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
