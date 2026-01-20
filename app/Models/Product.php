<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $table = 'producten';

    protected $fillable = [
        'product_naam',
        'categorie_id',
        'ean',
        'aantal_voorraad',
        'is_actief',
        'opmerking',
    ];

    protected $casts = [
        'categorie_id' => 'integer',
        'aantal_voorraad' => 'integer',
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

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(ProductCategorie::class, 'categorie_id', 'id');
    }
}

