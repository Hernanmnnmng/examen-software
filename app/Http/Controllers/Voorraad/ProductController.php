<?php

namespace App\Http\Controllers\Voorraad;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategorie;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Throwable;

/**
 * Voorraadbeheer: Product CRUD + listing.
 *
 * Notes (Hernan Martino Molina):
 * - On MySQL we prefer stored procedures (exam requirement).
 * - On SQLite/testing we fall back to Query Builder so the app/tests remain runnable.
 * - Errors are flashed to the session as 'success'/'error' and shown by the Blade views.
 */
class ProductController extends Controller
{
    /**
     * Product overview with optional EAN filter and safe sorting.
     */
    public function index(Request $request): View
    {
        $ean = (string) $request->query('ean', '');
        $sort = (string) $request->query('sort', 'product_naam');
        $dir = strtolower((string) $request->query('dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        $allowedSorts = ['product_naam', 'categorie', 'ean', 'aantal_voorraad'];
        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'product_naam';
        }

        $producten = [];

        try {
            // Prefer stored procedures on MySQL (exam requirement),
            // but fall back to a normal SELECT if the procedure is missing.
            if (DB::connection()->getDriverName() === 'mysql') {
                try {
                    $producten = DB::select('CALL sp_product_list(?, ?, ?)', [
                        $ean !== '' ? $ean : null,
                        $sort,
                        $dir,
                    ]);
                } catch (QueryException $e) {
                    // MySQL error 1305: PROCEDURE ... does not exist
                    $errorInfo = $e->errorInfo;
                    $mysqlCode = is_array($errorInfo) ? ($errorInfo[1] ?? null) : null;
                    if ((int) $mysqlCode !== 1305) {
                        throw $e;
                    }
                }
            }

            if (empty($producten)) {
                // SQLite/testing fallback OR MySQL fallback when SP is missing.
                // Use Eloquent model as the query base (exam requirement: model usage),
                // while keeping the exact same shape expected by the Blade views.
                $query = Product::query()
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

                $producten = $query->orderBy($sortColumn, $dir)->get();
            }
        } catch (Throwable $e) {
            report($e);
            // Keep page usable; show empty list and a flash error.
            session()->flash('error', 'Kon producten niet laden.');
            $producten = [];
        }

        return view('voorraad.producten.index', [
            'producten' => $producten,
            'ean' => $ean,
            'sort' => $sort,
            'dir' => $dir,
        ]);
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        $categorieen = $this->getCategorieen();

        return view('voorraad.producten.create', [
            'categorieen' => $categorieen,
        ]);
    }

    /**
     * Create a new product.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_naam' => ['required', 'string', 'max:255'],
            'categorie_id' => ['required', 'integer', 'exists:product_categorieen,id'],
            'ean' => ['required', 'digits:13'],
            'aantal_voorraad' => ['required', 'integer', 'min:0'],
        ]);

        try {
            if (DB::connection()->getDriverName() === 'mysql') {
                DB::select('CALL sp_product_create(?, ?, ?, ?)', [
                    $validated['product_naam'],
                    $validated['ean'],
                    (int) $validated['categorie_id'],
                    (int) $validated['aantal_voorraad'],
                ]);
            } else {
                // SQLite/testing fallback (Eloquent model)
                Product::query()->create([
                    'product_naam' => $validated['product_naam'],
                    'ean' => $validated['ean'],
                    'categorie_id' => (int) $validated['categorie_id'],
                    'aantal_voorraad' => (int) $validated['aantal_voorraad'],
                    'is_actief' => 1,
                ]);
            }

            return redirect()
                ->route('voorraad.producten.index')
                ->with('success', 'Product aangemaakt');
        } catch (QueryException $e) {
            report($e);
            return back()
                ->withInput()
                ->with('error', $this->friendlyDbMessage($e));
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Product aanmaken mislukt');
        }
    }

    /**
     * Show edit form.
     */
    public function edit(int $id): View
    {
        $categorieen = $this->getCategorieen();
        $product = null;

        if (DB::connection()->getDriverName() === 'mysql') {
            $rows = DB::select('CALL sp_product_get(?)', [$id]);
            $product = $rows[0] ?? null;
        } else {
            $product = Product::query()->where('id', $id)->first();
        }

        abort_if(! $product, 404);

        return view('voorraad.producten.edit', [
            'product' => $product,
            'categorieen' => $categorieen,
        ]);
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'product_naam' => ['required', 'string', 'max:255'],
            'categorie_id' => ['required', 'integer', 'exists:product_categorieen,id'],
            'ean' => ['required', 'digits:13'],
            'aantal_voorraad' => ['required', 'integer', 'min:0'],
        ]);

        try {
            if (DB::connection()->getDriverName() === 'mysql') {
                DB::select('CALL sp_product_update(?, ?, ?, ?, ?)', [
                    $id,
                    $validated['product_naam'],
                    $validated['ean'],
                    (int) $validated['categorie_id'],
                    (int) $validated['aantal_voorraad'],
                ]);
            } else {
                // SQLite/testing fallback (Eloquent model)
                Product::query()->where('id', $id)->update([
                    'product_naam' => $validated['product_naam'],
                    'ean' => $validated['ean'],
                    'categorie_id' => (int) $validated['categorie_id'],
                    'aantal_voorraad' => (int) $validated['aantal_voorraad'],
                ]);
            }

            return redirect()
                ->route('voorraad.producten.index')
                ->with('success', 'Wijzigingen opgeslagen');
        } catch (QueryException $e) {
            report($e);
            return back()
                ->withInput()
                ->with('error', $this->friendlyDbMessage($e));
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Product wijzigen mislukt');
        }
    }

    /**
     * Delete a product.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            if (DB::connection()->getDriverName() === 'mysql') {
                DB::select('CALL sp_product_delete(?)', [$id]);
            } else {
                // SQLite/testing fallback: if the linkage table exists, enforce the same rule.
                if (Schema::hasTable('voedselpakket_producten')) {
                    $usedCount = (int) DB::table('voedselpakket_producten')
                        ->where('product_id', $id)
                        ->count();

                    if ($usedCount > 0) {
                        return redirect()
                            ->route('voorraad.producten.index')
                            ->with('error', 'Product kan niet worden verwijderd, het is al gebruikt in een voedselpakket');
                    }
                }

                Product::query()->where('id', $id)->delete();
            }

            return redirect()
                ->route('voorraad.producten.index')
                ->with('success', 'Product verwijderd');
        } catch (QueryException $e) {
            report($e);
            return redirect()
                ->route('voorraad.producten.index')
                ->with('error', $this->friendlyDbMessage($e));
        } catch (Throwable $e) {
            report($e);
            return redirect()
                ->route('voorraad.producten.index')
                ->with('error', 'Product verwijderen mislukt');
        }
    }

    /**
     * Categories for dropdowns.
     */
    private function getCategorieen()
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            try {
                return DB::select('CALL sp_category_list()');
            } catch (QueryException $e) {
                $errorInfo = $e->errorInfo;
                $mysqlCode = is_array($errorInfo) ? ($errorInfo[1] ?? null) : null;
                // 1305: procedure does not exist → fall back
                if ((int) $mysqlCode !== 1305) {
                    throw $e;
                }
            }
        }

        return ProductCategorie::query()
            ->active()
            ->orderBy('naam', 'asc')
            ->get(['id', 'naam']);
    }

    /**
     * Convert low-level DB exceptions to a user-facing message.
     *
     * (Hernan Martino Molina) We keep the controller DB-focused; views can further
     * "prettify" overly-technical messages if needed.
     */
    private function friendlyDbMessage(QueryException $e): string
    {
        // Prefer MySQL SIGNAL messages (SQLSTATE 45000) when provided.
        $info = $e->errorInfo;
        $sqlState = is_array($info) ? ($info[0] ?? null) : null;
        $message = $e->getMessage();

        if ($sqlState === '45000') {
            // MySQL embeds SIGNAL message at the end; still good enough for UX.
            return $message;
        }

        // Handle common unique constraint cases (SQLite and MySQL).
        if (str_contains(strtolower($message), 'unique') || str_contains(strtolower($message), 'duplicate')) {
            return 'Productnaam of EAN-code al bestaat';
        }

        return 'Er ging iets mis bij het opslaan.';
    }
}

