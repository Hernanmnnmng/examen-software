<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

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

    /**
     * Stored Procedure Wrappers / Data Access Methods
     * These handle the choice between MySQL SPs and SQLite/Eloquent fallback.
     */

    public static function listProducts(?string $ean, string $sort, string $dir): array
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            try {
                return DB::select('CALL sp_product_list(?, ?, ?)', [
                    $ean !== '' ? $ean : null,
                    $sort,
                    $dir,
                ]);
            } catch (QueryException $e) {
                // Return empty if SP missing (exam fallback safety)
                if ((int) ($e->errorInfo[1] ?? 0) !== 1305) throw $e;
            }
        }

        // Eloquent fallback
        $query = self::query()
            ->from('producten as p')
            ->join('product_categorieen as c', 'c.id', '=', 'p.categorie_id')
            ->select([
                'p.id',
                'p.product_naam',
                'p.ean',
                'p.aantal_voorraad',
                'p.categorie_id',
                DB::raw('c.naam as categorie_naam'),
            ])
            ->where('p.is_actief', '=', 1)
            ->where('c.is_actief', '=', 1);

        if ($ean !== '') {
            $query->where('p.ean', '=', $ean);
        }

        $sortColumn = match ($sort) {
            'ean' => 'p.ean',
            'categorie' => 'c.naam',
            'aantal_voorraad' => 'p.aantal_voorraad',
            default => 'p.product_naam',
        };

        return $query->orderBy($sortColumn, $dir)->get()->all();
    }

    public static function createProduct(array $data): int
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            $rows = DB::select('CALL sp_product_create(?, ?, ?, ?)', [
                $data['product_naam'],
                $data['ean'],
                (int) $data['categorie_id'],
                (int) $data['aantal_voorraad'],
            ]);
            return (int) ($rows[0]->id ?? 0);
        }

        // Eloquent fallback
        $product = self::query()->create([
            'product_naam' => $data['product_naam'],
            'ean' => $data['ean'],
            'categorie_id' => (int) $data['categorie_id'],
            'aantal_voorraad' => (int) $data['aantal_voorraad'],
            'is_actief' => 1,
        ]);

        return $product->id;
    }

    public static function getProduct(int $id): ?object
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            try {
                $rows = DB::select('CALL sp_product_get(?)', [$id]);
                return $rows[0] ?? null;
            } catch (QueryException $e) {
                if ((int) ($e->errorInfo[1] ?? 0) !== 1305) throw $e;
            }
        }

        return self::query()->where('id', $id)->first();
    }

    public static function updateProduct(int $id, array $data): bool
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            $res = DB::select('CALL sp_product_update(?, ?, ?, ?, ?)', [
                $id,
                $data['product_naam'],
                $data['ean'],
                (int) $data['categorie_id'],
                (int) $data['aantal_voorraad'],
            ]);
            // Returns affected rows
             return isset($res[0]->affected);
        }

        return (bool) self::query()->where('id', $id)->update([
            'product_naam' => $data['product_naam'],
            'ean' => $data['ean'],
            'categorie_id' => (int) $data['categorie_id'],
            'aantal_voorraad' => (int) $data['aantal_voorraad'],
        ]);
    }

    public static function deleteProduct(int $id): bool
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            $res = DB::select('CALL sp_product_delete(?)', [$id]);
            return isset($res[0]->affected);
        }

        // Eloquent fallback with check
        if (Schema::hasTable('voedselpakket_producten')) {
            $usedCount = (int) DB::table('voedselpakket_producten')
                ->where('product_id', $id)
                ->count();

            if ($usedCount > 0) {
                // Simulate the MySQL signal error for consistency
                throw new \Exception('Product kan niet worden verwijderd, het is al gebruikt in een voedselpakket');
            }
        }

        return (bool) self::query()->where('id', $id)->delete();
    }
}

