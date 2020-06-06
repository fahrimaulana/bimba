<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('platform', function(Builder $builder) {
            $builder->where(function($qry) {
                $qry->when(platform() == 'Client', function($qry) {
                        $qry->where('preferences.platform', platform())
                            ->where('preferences.client_id', clientId());
                    })
                    ->when(platform() == 'Admin', function($qry) {
                        $qry->where('preferences.platform', platform());
                    })
                    ->orWhere('preferences.platform', 'General');
            });
        });
    }

    public function scopeOf($query, $key)
    {
        return $query->where('key', $key);
    }

    public static function valueOf($key)
    {
        $preference = static::of($key)->first();

        return $preference ? $preference->value : null;
    }

    public function scopeUpdateValueOf($query, $key, $newValue)
    {
        return $query->where('key', $key)->update(['value' => $newValue]);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
