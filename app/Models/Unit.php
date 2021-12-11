<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use OwenIt\Auditing\Auditable;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Unit extends Model implements ContractsAuditable
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;
    use Auditable;


    protected $guarded = [];

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

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }

    public function unitItems()
    {
        return $this->hasMany(ItemUnit::class, 'unit_id');
    }

    /**
     * Get the the unitItem instance by unit id.
     * @param  integer  $item_id
     * @return Model
     */
    public function getItemById($item_id)
    {
        if ($this->unitItems()->where('item_id', $item_id)->first()) {
            return $this->unitItems()->where('item_id', $item_id)->first();
        } else {
            return ItemUnit::firstOrCreate([
                'item_id' => $item_id,
                'unit_id' => $this->id
            ]);
        }
    }
}
