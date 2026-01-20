<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategorie extends Model
{
    use HasFactory;

    protected $table = 'product_categorieen';

    protected $fillable = [
        'naam',
        'is_actief',
        'opmerking',
    ];

    protected $casts = [
        'is_actief' => 'boolean',
        'datum_aangemaakt' => 'datetime',
        'datum_gewijzigd' => 'datetime',
    ];

    public const CREATED_AT = 'datum_aangemaakt';
    public const UPDATED_AT = 'datum_gewijzigd';

    public function scopeActive($query)
    {
        return $query->where('is_actief', '=', 1);
    }

    public function producten(): HasMany
    {
        return $this->hasMany(Product::class, 'categorie_id', 'id');
    }
}

