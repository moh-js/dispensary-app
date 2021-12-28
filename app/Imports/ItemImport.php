<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Unit;
use App\Models\ItemUnit;
use Maatwebsite\Excel\Row;
use App\Models\InventoryCategory;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ItemImport implements OnEachRow, WithHeadingRow, WithChunkReading
{
    use Importable;

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();

        $category = InventoryCategory::where('name', $row['category'])->first();

        // Save Item
        $item = Item::firstOrCreate([
            'name' => $row['name'],
            'short_name' => $row['short_name'],
            'manufacturer' => $row['manufacturer'],
            'package_type' => $row['package_type'],
            'price' => $row['price'],
            'quantity' => $row['quantity'],
            'inventory_category_id' => $category->id,
        ]);

        // Create its service bill
        if ($category->id == 1 || $category->id == 2) {
            $service_category_id = 1;

            $item->service()->firstOrCreate([
                'name' => "{$row['name']} - {$row['package_type']}",
                'price' => $row['price'],
                'service_category_id' => $service_category_id
            ]);
        }

        $this->manageItems($row, $item);

    }

    public function manageItems($row ,$item)
    {
        /*
            1 => 'Dispensing',
            2 => 'Lab',
            3 => 'RCH',
            4 => 'OPD',
            5 => 'Injection',
            6 => 'Store',
        */

        if ($row['store_qty']) {
            $this->updateItems($item, 6, $row['store_qty']);
        }

        // if ($row['dispensing_qty']) {
        //     $this->updateItems($item, 1, $row['dispensing_qty']);
        // }

        // if ($row['lab_qty']) {
        //     $this->updateItems($item, 2, $row['lab_qty']);
        // }

        // if ($row['injection_qty']) {
        //     $this->updateItems($item, 5, $row['injection_qty']);
        // }
    }

    public function updateItems($item, $unit_id, $remain) {
        $unit = Unit::find($unit_id);
        $itemFromUnit = $unit->getItemById($item->id);

        $itemFromUnit->update([
            'remain' => $remain
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
