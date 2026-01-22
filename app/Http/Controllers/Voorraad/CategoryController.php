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
 * Voorraadbeheer: Category CRUD.
 *
 * Notes (Hernan Martino Molina):
 * - Uses MySQL stored procedures when available.
 * - Falls back to Query Builder for SQLite/testing or when SPs are missing.
 */
class CategoryController extends Controller
{
    /**
     * List active categories.
     */
    public function index(): View
    {
        $categorieen = [];

        try {
            if (DB::connection()->getDriverName() === 'mysql') {
                try {
                    $categorieen = DB::select('CALL sp_category_list()');
                } catch (QueryException $e) {
                    $errorInfo = $e->errorInfo;
                    $mysqlCode = is_array($errorInfo) ? ($errorInfo[1] ?? null) : null;
                    if ((int) $mysqlCode !== 1305) {
                        throw $e;
                    }
                    // // Fall back if procedure doesn't exist (Eloquent model)
                    // $categorieen = ProductCategorie::query()
                    //     ->active()
                    //     ->orderBy('naam', 'asc')
                    //     ->get(['id', 'naam']);
                }
            } else {
                $categorieen = ProductCategorie::query()
                    ->active()
                    ->orderBy('naam', 'asc')
                    ->get(['id', 'naam']);
            }
        } catch (Throwable $e) {
            report($e);
            session()->flash('error', 'Kon categorieën niet laden.');
            $categorieen = [];
        }

        return view('voorraad.categorieen.index', [
            'categorieen' => $categorieen,
        ]);
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        return view('voorraad.categorieen.create');
    }

    /**
     * Create a category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'naam' => ['required', 'string', 'max:100'],
        ]);

        try {
            if (DB::connection()->getDriverName() === 'mysql') {
                DB::select('CALL sp_category_create(?)', [$validated['naam']]);
            } else {
                ProductCategorie::query()->create([
                    'naam' => $validated['naam'],
                    'is_actief' => 1,
                ]);
            }

            return redirect()
                ->route('voorraad.categorieen.index')
                ->with('success', 'Categorie aangemaakt');
        } catch (QueryException $e) {
            report($e);
            return back()
                ->withInput()
                ->with('error', $this->friendlyDbMessage($e));
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Categorie aanmaken mislukt');
        }
    }

    /**
     * Show edit form.
     */
    public function edit(int $id): View
    {
        $categorie = null;

        if (DB::connection()->getDriverName() === 'mysql') {
            // category_get not needed; list is small
            $rows = DB::select('CALL sp_category_list()');
            foreach ($rows as $row) {
                if ((int) $row->id === $id) {
                    $categorie = $row;
                    break;
                }
            }
        } else {
            $categorie = ProductCategorie::query()->where('id', $id)->first();
        }

        abort_if(! $categorie, 404);

        return view('voorraad.categorieen.edit', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * Update a category.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'naam' => ['required', 'string', 'max:100'],
        ]);

        try {
            if (DB::connection()->getDriverName() === 'mysql') {
                DB::select('CALL sp_category_update(?, ?)', [$id, $validated['naam']]);
            } else {
                ProductCategorie::query()->where('id', $id)->update([
                    'naam' => $validated['naam'],
                ]);
            }

            return redirect()
                ->route('voorraad.categorieen.index')
                ->with('success', 'Categorie gewijzigd');
        } catch (QueryException $e) {
            report($e);
            return back()
                ->withInput()
                ->with('error', $this->friendlyDbMessage($e));
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Categorie wijzigen mislukt');
        }
    }

    /**
     * Delete a category.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            if (DB::connection()->getDriverName() === 'mysql') {
                DB::select('CALL sp_category_delete(?)', [$id]);
            } else {
                // SQLite/testing fallback: block if products exist
                $count = (int) Product::query()->where('categorie_id', $id)->count();
                if ($count > 0) {
                    return redirect()
                        ->route('voorraad.categorieen.index')
                        ->with('error', 'Categorie kan niet worden verwijderd, er zijn producten aan gekoppeld');
                }

                ProductCategorie::query()->where('id', $id)->delete();
            }

            return redirect()
                ->route('voorraad.categorieen.index')
                ->with('success', 'Categorie verwijderd');
        } catch (QueryException $e) {
            report($e);
            return redirect()
                ->route('voorraad.categorieen.index')
                ->with('error', $this->friendlyDbMessage($e));
        } catch (Throwable $e) {
            report($e);
            return redirect()
                ->route('voorraad.categorieen.index')
                ->with('error', 'Categorie verwijderen mislukt');
        }
    }

    /**
     * Convert low-level DB exceptions to a user-facing message.
     */
    private function friendlyDbMessage(QueryException $e): string
    {
        $message = $e->getMessage();
        $lowerMsg = strtolower($message);

        // MySQL stored procedure sp_category_delete blocks delete if products exist
        // and raises SQLSTATE 45000 with a custom message.
        if (
            $e->getCode() === '45000' ||
            str_contains($lowerMsg, 'categorie kan niet worden verwijderd')
        ) {
            return 'Categorie kan niet worden verwijderd, er zijn producten aan gekoppeld';
        }

        if (
            str_contains($lowerMsg, 'integrity constraint') ||
            str_contains($lowerMsg, 'foreign key') ||
            str_contains($lowerMsg, 'constraint fails')
        ) {
            return 'Categorie kan niet worden verwijderd omdat het gekoppeld is aan andere gegevens.';
        }

        if (str_contains($lowerMsg, 'categorie bestaat al')) {
            return 'Categorie bestaat al';
        }

        if (str_contains($lowerMsg, 'unique') || str_contains($lowerMsg, 'duplicate')) {
            return 'Categorie bestaat al';
        }

        $info = $e->errorInfo;
        $sqlState = is_array($info) ? ($info[0] ?? null) : null;
        return 'Er ging iets mis bij het opslaan (SQL Code: ' . ($sqlState ?? $e->getCode()) . ').';
    }
}

