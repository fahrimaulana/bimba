<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $casts = ['address' => 'object'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('active', function(Builder $builder) {
            $builder->where('clients.active', 1);
        });
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'entity');
    }

    public function parent()
    {
        return $this->belongsTo(Client::class);
    }
}
