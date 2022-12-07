<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $this->authorize('service-view');

        return view('services.index', [
            'services' => Service::withTrashed()->with('item')->get()
        ]);
    }

    public function create()
    {
        $this->authorize('service-add');

        return view('services.add');
    }

    public function store(Request $request)
    {
        $this->authorize('service-add');

        $request->validate([
            "service_name" => ['required', 'string', "unique:services,name"],
            "service_price" => ['required', 'string'],
            'service_category_id' => ['required', 'integer'],
            'item_id' => ['nullable', 'integer']
        ]);

        $service = Service::firstOrCreate([
            'name' => $request->service_name,
            'price' => $request->service_price,
            'item_id' => $request->item_id,
            'service_category_id' => $request->service_category_id,
        ]);

        flash('Services added successfully');
        return redirect()->route('services.index');
    }

    public function edit($slug)
    {
        $this->authorize('service-update');

        $service = Service::where('slug', $slug)->first();

        return view('services.edit', [
            'service' => $service
        ]);
    }

    public function update(Request $request, $slug)
    {
        $this->authorize('service-update');

        $service = Service::where('slug', $slug)->first();

        $request->validate([
            "service_name" => ['required', 'string', "unique:services,name,$service->id,id"],
            "service_price" => ['required', 'string'],
            'service_category_id' => ['required', 'integer'],
            'item_id' => ['nullable', 'integer']
        ]);

        $service->update([
            'name' => $request->service_name,
            'item_id' => $request->item_id,
            'price' => $request->service_price,
            'service_category_id' => $request->service_category_id,
        ]);

        flash('Services updated successfully');
        return redirect()->route('services.index');
    }

    public function destroy($slug)
    {
        $service = Service::withTrashed()->where('slug', $slug)->first();

        if ($service->trashed()) {
            $this->authorize('service-activate');
            $service->restore();
            $action = 'restored';
        } else {
            $this->authorize('service-deactivate');
            $service->delete();
            $action = 'deleted';
        }

        flash("Service $action successfully");
        return redirect()->route('services.index');
    }
}
