
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
            <div class="row">

                <div class="col-sm-7">
                    <div class="form-group">
                        <input type="text" class="form-control" wire:model="search" placeholder="Search {{ $category->name }} Name">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                      <select wire:model="collection" id="action" class="form-control">
                          <option value="50" >50</option>
                          <option value="100" >100</option>
                          <option value="300" >300</option>
                          <option value="500" >500</option>
                          <option value="1000" >1000</option>
        
                      </select>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm">
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
                        @foreach ($items as $key => $item)
                            <tr wire:loading.remove wire:target="search">
                                <td scope="row">{{ $items->firstItem() + $key }}</td>
                                <td>{{ strtoupper($item->name) }}
                                    @if (!$item->service)
                                        <span class="badge badge-danger">Service not linked</span>
                                    @endif
                                </td>

                                <td>
                                    <a data-toggle="collapse" href="#{{ $item->slug }}"  role="button" aria-expanded="false" aria-controls="{{ $item->slug }}">
                                        {{ $item->itemUnits()->sum('remain') }} {{ $item->uom }}
                                    </a>
                                </td>
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
                            @if ($item->itemUnits()->sum('remain'))
                                <tr class="collapse" id="{{ $item->slug }}">
                                    <td></td>
                                    <td></td>
                                    <td colspan="">
                                        @foreach ($item->itemUnits as $itemUnit)
                                            @if ($itemUnit->remain)
                                            <span class="mr-3">
                                                {{ $itemUnit->unit->name }}: {{ $itemUnit->remain }} {{ $item->uom }}
                                            </span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-center">
                <div wire:loading wire:target="search" class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <div class="float-right" wire:loading.remove wire:target="search">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>

