
<div>
    <div class="row mb-3">
        <div class="float-right">
            <a href="{{ route('items.add', $category->slug) }}" class="btn btn-primary  ml-3">Add {{ $category->name }}</a>
            <a href="{{ route('items.management') }}" class=" ml-2 btn btn-success ">Manage Inventory</a>
        </div>
    </div>

    <div class="card">
        {{-- <div class="card-header">
            <h5 class="">{{ $category->name }} Inventory List</h5>
        </div> --}}
        <div class="card-body table-bordered-style">
            <div class="form-group">
                <input type="text" class="form-control" wire:model="search" placeholder="Search {{ $category->name }} Name">
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-inverse table-sm">
                    <thead class="thead-inverse">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="spinner-border text-primary" wire:loading wire:target="search" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                            @foreach ($items as $key => $item)
                                <tr wire:loading.remove wire:target="search">
                                    <td scope="row">{{ $items->firstItem() + $key }}</td>
                                    <td>{{ strtoupper($item->name) }}</td>
                                    <td>{{ $item->itemUnits()->sum('remain') }}{{ $item->uom }}</td>
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

                                            <a href="{{ route('items.management.with-item', $item->slug) }}" class="ml-3" title="Manage Item"><i class="feather icon-sliders"></i></a>


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
            <div class="float-right" wire:loading.remove wire:target="search">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
