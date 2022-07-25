<?php

namespace App\Http\Controllers;

use App\Imports\ItemImport;
use App\Imports\ServiceImport;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function generalPage(GeneralSettings $settings)
    {
        $this->authorize('configuration-general');

        return view('configuration.general', [
            'settings' => $settings
        ]);
    }

    public function updateGeneralConfig(GeneralSettings $settings)
    {
        $this->authorize('configuration-general');

        request()->validate([
            "app_name" => ['required', 'string'],
            "app_short_name" => ['required', 'string'],
            "app_currency" => ['required', 'string'],
            "address" => ['required', 'string'],
            "phone" => ['required', 'string']
        ]);

        $settings->app_name = request('app_name');
        $settings->app_short_name = request('app_short_name');
        $settings->app_currency = request('app_currency');
        $settings->app_address = request('address');
        $settings->app_phone = request('phone');

        $settings->save();

        flash('Setting updated successfully');

        return redirect()->route('general.index');
    }

    public function dataPage()
    {
        $this->authorize('configuration-data-import');

        return view('configuration.data-import');
    }

    public function serviceImport(Request $request)
    {
        $request->validate([
            'service_file' => ['required', 'file', 'mimes:xls,xlsx']
        ]);

        (new ServiceImport)->import($request->service_file);

        flash('Services imported successfully');
        return redirect()->route('data.index');
    }

    public function itemImport(Request $request)
    {
        $request->validate([
            'item_file' => ['required', 'file', 'mimes:xls,xlsx']
        ]);

        (new ItemImport)->import($request->item_file);

        flash('Items imported successfully');
        return redirect()->route('data.index');
    }
}
