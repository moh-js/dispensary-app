<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use App\Models\Item;
use App\Models\Unit;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(InventoryCategory $category)
    {
        $this->authorize('item-view');

        $items = Item::withTrashed()->where('inventory_category_id', $category->id)->paginate(20);

        return view('inventory.index', [
            'items' => $items,
            'category' => $category
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(InventoryCategory $category)
    {
        $this->authorize('item-add');

        return view('inventory.add', [
            'category' => $category
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, InventoryCategory $category)
    {
        $this->authorize('item-add');

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:items,name'],
            'short_name' => ['nullable', 'string', 'max:255'],
            "quantity" => ['required', 'integer'],
            "package_type" => ['nullable', 'string'],
            "manufacture" => ['nullable', 'string'],
            "service_name" => ['sometimes', 'string', "unique:services,name"],
            "service_price" => ['sometimes', 'integer'],
            "price" => ['nullable', 'integer'],
            "expire_date" => ['nullable', 'date']
        ]);

        $data = collect(['inventory_category_id' => $category->id])->merge($request->except(['_token', 'service_name', 'service_price']))->filter()->toArray();

        $item = Item::firstOrCreate($data);

        if ($category->id == 1 || $category->id == 2) {
            $service_category_id = 1;

            $item->service()->firstOrCreate([
                'name' => $request->service_name??$request->name,
                'price' => $request->service_price,
                'service_category_id' => $service_category_id
            ]);
        }


        flash("$category->name added to inventory successfully");
        return redirect()->route('items.index', $category->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $this->authorize('item-update');

        return view('inventory.edit', [
            'item' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $this->authorize('item-update');

        $request->validate([
            'name' => ['required', 'string', 'max:255', "unique:items,name,$item->id,id"],
            'short_name' => ['nullable', 'string', 'max:255'],
            "quantity" => ['required', 'integer'],
            "package_type" => ['nullable', 'string'],
            "manufacture" => ['nullable', 'string'],
            "service_name" => ['sometimes', 'string', "unique:services,name,{$item->service->id},id"],
            "service_price" => ['sometimes', 'integer'],
            "price" => ['nullable', 'integer'],
            "expire_date" => ['nullable', 'date']
        ]);

        $data = collect($request->except(['_token', 'service_name', 'service_price']))->filter()->toArray();

        $item->update($data);

        if ($item->inventory_category_id == 1 || $item->inventory_category_id == 2) {
            $service_category_id = 1;

            $item->service->update([
                'name' => $request->service_name??$request->name,
                'price' => $request->service_price,
                'service_category_id' => $service_category_id
            ]);
        }

        flash("$item->name updated successfully");
        return redirect()->route('items.index', $item->inventoryCategory->slug);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        if ($item->trashed()) {
            $this->authorize('item-activate');
            $item->restore();
            $action = 'restored';
        } else {
            $this->authorize('item-deactivate');

            if ($item->units()->sum('remain')) {
                flash()->error('Item cannot be deleted');
                return back();
            }

            $item->delete();
            $action = 'deleted';
        }

        flash("Item $action successfully");
        return redirect()->route('items.index', $item->inventoryCategory->slug);
    }

    public function managementPage(Item $item)
    {
        $this->authorize('item-management');

        return view('inventory.management', [
            'item_id' => $item->id
        ]);
    }

    public function issue(Request $request)
    {
        $this->authorize('item-issue');


        $request->validate([
            'item_id' => ['required', 'integer'],
            'type' => ['required', 'string'],
            'from' => ['required_if:type,receive', 'string'],
            'to' => ['required', 'string'],
            'quantity' => ['required', 'string'],
            'issued_date' => ['required', 'date']
        ]);

        $type = $request->type;

        $item = Item::findOrFail($request->item_id);
        $unit = Unit::find($request->to);
        $itemUnit = $unit->getItemById($request->item_id);

        $storeUnit = Unit::find(6); // 6 = store
        $itemFromStore = $storeUnit->getItemById($request->item_id);

        // return $itemUnit;



        if ($type == 'receive') {

            $itemUnit->update([
                'remain' => $request->quantity + $itemUnit->remain
            ]);

        } elseif($type == 'sent') {
            if ($request->quantity <= $itemFromStore->remain) {
                $itemFromStore->update([
                    'remain' => $itemFromStore->remain - $request->quantity
                ]);

                $itemUnit->update([
                    'remain' => $itemUnit->remain + $request->quantity
                ]);

                } else {
                flash('Requested Amount is greater than the available amount in the inventory')->error();
                return back()->withInput();
            }

        }

        $item->ledgers()->firstOrCreate([
            'type' => $type,
            'from' => $type == 'sent'? 'Store':$request->from,
            'to' => $request->to,
            'quantity' => $request->quantity,
            'issued_by' => $request->user()->id,
            'created_at' => $request->issued_date,
            'remain_from' => $type == 'receive'? null : $itemFromStore->remain,
            'remain_to' => $itemUnit->remain
        ]);

        flash('Item issued successfully');
        return redirect()->route('items.index', $item->inventoryCategory->slug);

    }
}
