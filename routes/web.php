<?php

use Mpdf\Mpdf;
use App\Models\Order;
use Mike42\Escpos\Printer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Mike42\Escpos\ImagickEscposImage;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\BillingController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\EncounterController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use  Meneses\LaravelMpdf\Facades\LaravelMpdf as PDF;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::view('dashboard', 'dashboard')
	->name('dashboard')
	->middleware(['auth:sanctum', 'verified']);

Route::prefix('dashboard')->middleware('auth:sanctum')->group(function ()
{
    Route::prefix('users')->group(function () // Users
    {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/add', [UserController::class, 'create'])->name('users.add');
        Route::post('/add', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}/edit', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('role/add', [RoleController::class, 'create'])->name('roles.add');
    Route::get('role/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::post('role/{role}/grant-permission', [RoleController::class, 'grantPermission'])->name('roles.grant');
    Route::put('role/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::post('role/add', [RoleController::class, 'store'])->name('roles.store');
    Route::get('role/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::delete('role/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Inventory
    Route::get('item/{category}/inventory', [ItemController::class, 'index'])->name('items.index');
    Route::get('item/{category}/add', [ItemController::class, 'create'])->name('items.add');
    Route::post('item/{category}/add', [ItemController::class, 'store'])->name('items.store');
    Route::get('item/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('item/{item}/edit', [ItemController::class, 'update'])->name('items.update');
    Route::delete('item/{item}/destroy', [ItemController::class, 'destroy'])->name('items.destroy');
    Route::get('inventory/management', [ItemController::class, 'managementPage'])->name('items.management');
    Route::get('inventory/{item}/management', [ItemController::class, 'managementPage'])->name('items.management.with-item');
    Route::post('inventory/management', [ItemController::class, 'issue'])->name('items.issue');

    // POS
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');

    // User Profile
    Route::get('profile', [UserController::class, 'myProfile'])->name('my.profile');

    // Patient
    Route::get('patient/add', [PatientController::class, 'create'])->name('patient.create');
    Route::post('patient/add', [PatientController::class, 'store'])->name('patient.store');
    Route::get('patient/{patient}/show', [PatientController::class, 'show'])->name('patient.show');

    // Service Bill
    Route::get('bill/{patient}/{invoice_id?}', [BillingController::class, 'patientBillPage'])->name('bill.patient.page');
    Route::post('bill/{patient}/add/{invoice_id?}', [BillingController::class, 'addBillService'])->name('bill.patient.add');
    Route::delete('bill/{billService}', [BillingController::class, 'removeBillService'])->name('bill.service.delete');
    Route::get('completed/{receipt_id?}/bill', [BillingController::class, 'completedBill'])->name('bill.completed');
    Route::post('bill/{invoice_id?}/complete', [BillingController::class, 'completeBill'])->name('bill.complete');

    // Configuration
    Route::get('general', [ConfigurationController::class, 'generalPage'])->name('general.index');
    Route::post('general', [ConfigurationController::class, 'updateGeneralConfig'])->name('general.save');

    // Importing Data
    Route::get('data-import', [ConfigurationController::class, 'dataPage'])->name('data.index');
    Route::post('service/data-import', [ConfigurationController::class, 'serviceImport'])->name('service.data.import');
    Route::post('item/data-import', [ConfigurationController::class, 'itemImport'])->name('item.data.import');

    // Report
    Route::get('/reports/inventory-ledgers', [ReportController::class, 'inventoryLedgersPage'])->name('inventory-ledger.index');
    Route::post('/reports/inventory-ledgers', [ReportController::class, 'inventoryLedgersSearch'])->name('inventory-ledger.search');

    Route::get('/reports/dispensing', [ReportController::class, 'dispensingPage'])->name('dispensing.index');
    Route::post('/reports/dispensing', [ReportController::class, 'dispensingSearch'])->name('dispensing.search');

    Route::get('/{encounter}/encounter', [EncounterController::class, 'index'])->name('encounter');
    Route::post('/encounter', [EncounterController::class, 'createEncounter'])->name('encounter.create');
});

Route::get('/test', function ()
{
    $arr = preg_split('/(?<=[0-9])(?=[a-z]+)/i','pc/121');
    return $arr;
});
//     $mpdf=new mPDF();

//     $mpdf->WriteHTML(View::make('invoice', [], [])->render());
//     $pageHeigt = $mpdf->y;
//     unset($mpdf);

//     $pdf = PDF::loadView('invoice', [] ,[] ,[
//       'format' => [80,$pageHeigt],
//       'default_font_size' => '10'
//     ]);

//     $pdfString = $pdf->output();
//     return dd($pdfString);

// $connector = new WindowsPrintConnector("XP-80C");

// $printer = new Printer($connector);
// // $pdf->save(storage_path('app/public/file.pdf'));
// // return storage_path('app/public/file.pdf');
// // $pdfPath = public_path('storage/file.pdf');

// // $pages = ImagickEscposImage::loadPdf($pdfPath, 80);
// // foreach ($pages as $page) {
//     // $printer -> graphics($page);
// // }

// $printer -> cut();
// $printer -> close();
// });
