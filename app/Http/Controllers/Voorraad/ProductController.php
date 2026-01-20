<?php

namespace App\Http\Controllers\Voorraad;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategorie;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

/**
 * Voorraadbeheer: Product CRUD + listing.
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

        try {
            // Logic moved to Model
            $producten = Product::listProducts($ean, $sort, $dir);
        } catch (Throwable $e) {
            report($e);
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
            Product::createProduct($validated);

            return redirect()
                ->route('voorraad.producten.index')
                ->with('success', 'Product aangemaakt');
        } catch (QueryException $e) {
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
        $product = Product::getProduct($id);

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
            Product::updateProduct($id, $validated);

            return redirect()
                ->route('voorraad.producten.index')
                ->with('success', 'Wijzigingen opgeslagen');
        } catch (QueryException $e) {
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
            Product::deleteProduct($id);

            return redirect()
                ->route('voorraad.producten.index')
                ->with('success', 'Product verwijderd');
        } catch (QueryException $e) {
            return redirect()
                ->route('voorraad.producten.index')
                ->with('error', $this->friendlyDbMessage($e));
        } catch (Throwable $e) {
            // Catch custom exception from Eloquent fallback or general errors
            report($e);
            return redirect()
                ->route('voorraad.producten.index')
                ->with('error', $e->getMessage() ?: 'Product verwijderen mislukt');
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
                if ((int) ($e->errorInfo[1] ?? 0) !== 1305) throw $e;
            }
        }

        return ProductCategorie::query()
            ->active()
            ->orderBy('naam', 'asc')
            ->get(['id', 'naam']);
    }

    /**
     * Convert low-level DB exceptions to a user-facing message.
     */
    private function friendlyDbMessage(QueryException $e): string
    {
        $info = $e->errorInfo;
        $sqlState = is_array($info) ? ($info[0] ?? null) : null;
        $message = $e->getMessage();
        $lowerMsg = strtolower($message);

        // 1. User-defined signal (45000)
        if ($sqlState === '45000') {
            return $message;
        }

        // 2. Duplicate entry
        if (str_contains($lowerMsg, 'unique') || str_contains($lowerMsg, 'duplicate')) {
            return 'Productnaam of EAN-code bestaat al.';
        }

        // 3. Foreign key / usage constraint
        if (str_contains($lowerMsg, 'integrity constraint') ||
            str_contains($lowerMsg, 'foreign key') ||
            str_contains($lowerMsg, 'constraint fails')) {
            return 'Product kan niet worden verwijderd omdat het gekoppeld is aan andere gegevens.';
        }

        // 4. Specific text check (for manual exceptions or Dutch signals if state wasn't 45000)
        if (str_contains($lowerMsg, 'verwijderd')) {
             return 'Product kan niet worden verwijderd, het is al gebruikt.';
        }

        // 5. Fallback with code for debugging
        return 'Er ging iets mis bij het opslaan (SQL Code: ' . ($sqlState ?? 'Unknown') . ').';
    }
}

