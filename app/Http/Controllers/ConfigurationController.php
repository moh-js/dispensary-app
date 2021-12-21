<?php

namespace App\Http\Controllers;

use App\Imports\ItemImport;
use App\Imports\ServiceImport;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function generalPage()
    {
        $this->authorize('configuration-general');

        return view('configuration.general');
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
