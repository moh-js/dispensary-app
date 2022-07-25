<?php

namespace App\Imports;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ServiceImport implements ToModel, WithBatchInserts, WithHeadingRow, WithChunkReading
{
    use Importable;
    /**
    * @param $array
    */
    public function model(array $row)
    {
        $category = $row['category'];
        return new Service([
            'name' => $row['name'],
            'slug' => str_slug($row['name']),
            'service_category_id' => ServiceCategory::where('name', 'like', "%$category%")->first()->id,
            'price' => $row['price'],
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
