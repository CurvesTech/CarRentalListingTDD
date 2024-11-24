<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Listing extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function images(): HasMany {
        return $this->hasMany(Image::class);
    }

    public function maker() {
        return $this->belongsTo(Maker::class);
    }

    public function model() {
        return $this->belongsTo(CarModel::class);
    }
}
