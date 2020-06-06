<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Counter extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function(Builder $builder) {
            $builder->where('counters.client_id', clientId());
        });
    }

    public function scopeOf($query, $key)
    {
        return $query->where('key', $key)->lockForUpdate();
    }

    public static function valueOf($key)
    {
        $counter = static::of($key)->first();

        return $counter ? $counter->value : 0;
    }

    public function scopeUpdateValueOf($query, $key, $newValue)
    {
        return $query->where('key', $key)->update(['value' => $newValue]);
    }
}
