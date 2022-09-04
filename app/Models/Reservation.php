<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['price'];

    public function vacancies()
    {
        return $this->belongsToMany(Vacancy::class);
    }

    public function scopeNotCanceled($query)
    {
        return $query->whereNull('canceled_at');
    }

    protected function price(): Attribute
    {
        return new Attribute(
            get: fn () => $this->attributes['total_price'] / 100
        );
    }
}
