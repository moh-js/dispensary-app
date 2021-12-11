<?php

namespace App\Http\Controllers;

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
            'file' => ['required', 'file', 'mimes:xls,xlsx']
        ]);

        (new ServiceImport)->import($request->file);

        flash('Services imported successfully');
        return redirect()->route('data.index');
    }
}
