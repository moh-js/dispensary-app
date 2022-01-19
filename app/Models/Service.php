<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use OwenIt\Auditing\Auditable;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Service extends Model implements ContractsAuditable
{
    use SoftDeletes;
    use HasFactory;
    use HasSlug;
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

    public function getProperNameAttribute()
    {
        return $this->item->name. ' - '. " [ {$this->price} Tsh]";
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public function orderServices()
    {
        return $this->hasMany(OrderService::class, 'service_id');
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

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
