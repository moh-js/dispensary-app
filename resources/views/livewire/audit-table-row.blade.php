<tr>
    <td scope="row">{{ $sr }}</td>
    <td>
        <span class=" badge badge-{{ ${$audit->event} }}">
           {{ class_basename($audit->auditable_type) }} {{ $audit->event }}
        </span>
    </td>
    <td>{{ $audit->user->name??'' }}</td>
    <td>
        <div class="text-small">
            @empty(!$audit->new_values)
                @empty(!$audit->old_values)
                    <h6 wire:click="toggleNewValuesVisibility" class="btn btn-sm btn-primary">New {{ str_plural('Value', count($audit->new_values)) }} <i class="feather icon-chevron-{{ $showNewValues?'up':'down' }}"></i></h6>

                @else
                    <h6 wire:click="toggleNewValuesVisibility" class="btn btn-sm btn-primary">{{ str_plural('Value', count($audit->new_values)) }} <i class="feather icon-chevron-{{ $showNewValues?'up':'down' }}"></i></h6>
                @endempty
                <div class="clearfix"></div>
                <small x-data="{showNewValues:false}" x-show.transition.opacity.in.duration.100ms="@json($showNewValues)">
                    @isset($audit->new_values['name'])
                        <strong>Name:</strong> {{ $audit->new_values['name'] }}
                    @endisset
                    <div class="clearfix"></div>
                    @isset($audit->new_values['inventory_category_id'])
                        <strong>Category:</strong> {{ $inventoryCategory::find($audit->new_values['inventory_category_id'])->name }}
                    @endisset
                    @isset($audit->new_values['service_category_id'])
                        <strong>Category:</strong> {{ $serviceCategory::find($audit->new_values['service_category_id'])->name }}
                    @endisset
                    <div class="clearfix"></div>
                    @isset($audit->new_values['quantity'])
                    <strong>Quantity:</strong> {{ $audit->new_values['quantity'] }}
                    @endisset
                    <div class="clearfix"></div>
                    @isset($audit->new_values['uom'])
                    <strong>Unit of Measure:</strong> {{ $audit->new_values['uom'] }}
                    @endisset
                    @isset($audit->new_values['price'])
                    <strong>Price:</strong> {{ $audit->new_values['price'] }} {{ getAppCurrency() }}
                    @endisset
                    <div class="clearfix"></div>
                    @isset($audit->new_values['countable'])
                        <strong>Is Countable:</strong> {{ $audit->new_values['countable']?'True':'False' }}
                    @endisset
                </small>
            @endempty

            @empty(!$audit->old_values)
            <hr>
                <h6 wire:click="toggleOldValuesVisibility" class="btn btn-sm btn-danger">Old {{ str_plural('Value', count($audit->old_values)) }} <i class="feather icon-chevron-{{ $showOldValues?'up':'down' }}"></i></h6>
                <div class="clearfix"></div>
                <small x-data="{showOldValues:false}" x-show.transition.opacity.in.duration.100ms="@json($showOldValues)">
                    @isset($audit->old_values['name'])
                        <strong>Name:</strong> {{ $audit->old_values['name'] }}
                    @endisset
                    <div class="clearfix"></div>
                    @isset($audit->old_values['inventory_category_id'])
                        <strong>Category:</strong> {{ $inventoryCategory::find($audit->old_values['inventory_category_id'])->name }}
                    @endisset
                    @isset($audit->old_values['service_category_id'])
                        <strong>Category:</strong> {{ $serviceCategory::find($audit->old_values['service_category_id'])->name }}
                    @endisset
                    <div class="clearfix"></div>
                    @isset($audit->old_values['quantity'])
                    <strong>Quantity:</strong> {{ $audit->old_values['quantity'] }}
                    @endisset
                    <div class="clearfix"></div>
                    @isset($audit->old_values['uom'])
                    <strong>Unit of Measure:</strong> {{ $audit->old_values['uom'] }}
                    @endisset
                    @isset($audit->old_values['price'])
                    <strong>Price:</strong> {{ $audit->old_values['price'] }} {{ getAppCurrency() }}
                    @endisset
                    <div class="clearfix"></div>
                    @isset($audit->old_values['countable'])
                        <strong>Is Countable:</strong> {{ $audit->old_values['countable']?'True':'False' }}
                    @endisset
                </small>
            @endempty
        </div>
    </td>
    <td>{{ $audit->created_at->format('d-m-Y H:i') }}</td>
</tr>
