<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Item extends Model implements ContractsAuditable
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;
    use Auditable;

    protected $guarded = [];

    protected $casts = [
        'expire_date' => 'date',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function inventoryCategory()
    {
        return $this->belongsTo(InventoryCategory::class);
    }

    public function ledgers()
    {
        return $this->hasMany(Ledger::class);
    }

    public function units()
    {
        return $this->belongsToMany(Unit::class);
    }

    public function itemUnits()
    {
        return $this->hasMany(ItemUnit::class, 'item_id');
    }

    public function getUnitById($unit_id)
    {
        if ($this->itemUnits()->where('unit_id', $unit_id)->first()) {
            return $this->itemUnits()->where('unit_id', $unit_id)->first();
        } else {
            return ItemUnit::firstOrCreate([
                'unit_id' => $unit_id,
                'item_id' => $this->id
            ]);
        }
    }

    public function unit($unit_id)
    {
        return $this->units()->where('unit_id', $unit_id)->first();
    }

    public function service()
    {
        return $this->hasOne(Service::class);
    }
}
