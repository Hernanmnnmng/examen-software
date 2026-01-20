<?php

namespace App\Http\Controllers\Voorraad;

use App\Http\Controllers\Controller;
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
                    // Fall back if procedure doesn't exist
                    $categorieen = DB::table('product_categorieen')
                        ->select(['id', 'naam'])
                        ->where('is_actief', '=', 1)
                        ->orderBy('naam', 'asc')
                        ->get();
                }
            } else {
                $categorieen = DB::table('product_categorieen')
                    ->select(['id', 'naam'])
                    ->where('is_actief', '=', 1)
                    ->orderBy('naam', 'asc')
                    ->get();
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
                DB::table('product_categorieen')->insert([
                    'naam' => $validated['naam'],
                    'is_actief' => 1,
                    'datum_aangemaakt' => now(),
                    'datum_gewijzigd' => now(),
                ]);
            }

            return redirect()
                ->route('voorraad.categorieen.index')
                ->with('success', 'Categorie aangemaakt');
        } catch (QueryException $e) {
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
            $categorie = DB::table('product_categorieen')->where('id', $id)->first();
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
                DB::table('product_categorieen')->where('id', $id)->update([
                    'naam' => $validated['naam'],
                    'datum_gewijzigd' => now(),
                ]);
            }

            return redirect()
                ->route('voorraad.categorieen.index')
                ->with('success', 'Categorie gewijzigd');
        } catch (QueryException $e) {
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
                $count = (int) DB::table('producten')->where('categorie_id', $id)->count();
                if ($count > 0) {
                    return redirect()
                        ->route('voorraad.categorieen.index')
                        ->with('error', 'Categorie kan niet worden verwijderd, er zijn producten aan gekoppeld');
                }

                DB::table('product_categorieen')->where('id', $id)->delete();
            }

            return redirect()
                ->route('voorraad.categorieen.index')
                ->with('success', 'Categorie verwijderd');
        } catch (QueryException $e) {
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

        if (str_contains(strtolower($message), 'categorie bestaat al')) {
            return 'Categorie bestaat al';
        }

        if (str_contains(strtolower($message), 'unique') || str_contains(strtolower($message), 'duplicate')) {
            return 'Categorie bestaat al';
        }

        return 'Er ging iets mis bij het opslaan.';
    }
}

