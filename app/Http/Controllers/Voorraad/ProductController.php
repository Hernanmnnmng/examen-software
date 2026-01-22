<?php

namespace App\Http\Controllers\Voorraad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategorie;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get query parameters for search and sorting
        $ean = (string) $request->query('ean', '');
        $sort = (string) $request->query('sort', 'product_naam');
        $dir = strtolower((string) $request->query('dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Alleen actieve producten ophalen (soft-deleted producten worden verborgen)
        $producten = Product::SP_GetAllProductenVoorraad();

        // Filter by EAN if provided (merge-friendly: simple PHP filter)
        if ($ean !== '') {
            $producten = array_filter($producten, function($product) use ($ean) {
                return isset($product->ean) && $product->ean === $ean;
            });
            // Re-index array after filter
            $producten = array_values($producten);
        }

        // Sort products (merge-friendly: simple PHP sort)
        // Map view column names to actual property names
        $sortMap = [
            'product_naam' => 'product_naam',
            'categorie' => 'categorie_naam',  // View uses 'categorie' but SP returns 'categorie_naam'
            'ean' => 'ean',
            'aantal_voorraad' => 'aantal_voorraad'
        ];
        
        $allowedSorts = array_keys($sortMap);
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'product_naam';
        }

        $sortColumn = $sortMap[$sort] ?? 'product_naam';
        
        usort($producten, function($a, $b) use ($sortColumn, $dir) {
            $aVal = $a->$sortColumn ?? '';
            $bVal = $b->$sortColumn ?? '';
            
            if ($dir === 'desc') {
                return $bVal <=> $aVal;
            }
            return $aVal <=> $bVal;
        });

        // View laden met alle producten
        return view('voorraad.producten.index', [
            'producten' => $producten,
            'ean' => $ean,
            'sort' => $sort,
            'dir' => $dir
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Alle actieve categorieen ophalen voor dropdown
        $categorieen = ProductCategorie::SP_GetAllCategorieen();

        return view('voorraad.producten.create', [
            'categorieen' => $categorieen
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Data valideren van formulier input
        $data = $request->validate([
            'product_naam' => 'required|string|max:255',
            'ean' => 'required|string|size:13',
            'categorie_id' => 'required|integer',
            'aantal_voorraad' => 'required|integer|min:0'
        ]);

        // Productnaam apart opslaan voor check
        $naam = $data['product_naam'];
        $ean = $data['ean'];

        // Checken of de productnaam al bestaat via stored procedure
        $checkNameExists = Product::SP_GetProductByNaam($naam);
        $countName = $checkNameExists[0]->totaal ?? 0;

        // Checken of de EAN al bestaat via stored procedure
        $checkEanExists = Product::SP_GetProductByEan($ean);
        $countEan = $checkEanExists[0]->totaal ?? 0;

        // Als naam of EAN al bestaat, terug met foutmelding
        if ($countName > 0) {
            return redirect()->back()->with(
                'error', 'deze productnaam bestaat al'
            );
        }

        if ($countEan > 0) {
            return redirect()->back()->with(
                'error', 'deze EAN-code bestaat al'
            );
        }

        // Product aanmaken via stored procedure
        $result = Product::SP_CreateProduct($data);

        // Meldingen geven op basis van resultaat
        if($result) {
            return redirect()->back()->with(
                'success', 'product succesvol toegevoegd'
            );
        } else {
            return redirect()->back()->with(
                'error', 'product niet succesvol toegevoegd'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Huidige product ophalen
        $product = Product::SP_GetProductById($id);
        // Alle categorieen ophalen voor select dropdown
        $categorieen = ProductCategorie::SP_GetAllCategorieen();

        // Checken of product bestaat
        if (!$product) {
            return redirect()->route('voorraad.producten.index')
                ->with('error', 'Product niet gevonden');
        }

        // Edit view laden
        return view('voorraad.producten.edit', [
            'product' => $product,
            'categorieen' => $categorieen
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Input valideren
        $data = $request->validate([
            'product_naam' => 'required|string|max:255',
            'ean' => 'required|string|size:13',
            'categorie_id' => 'required|integer',
            'aantal_voorraad' => 'required|integer|min:0'
        ]);

        // Productnaam en EAN apart opslaan voor check
        $naam = $data['product_naam'];
        $ean = $data['ean'];

        // Checken of de productnaam al bestaat (exclusief huidige record)
        $checkNameExists = Product::SP_GetProductByNaam($naam);
        $countName = $checkNameExists[0]->totaal ?? 0;

        // Checken of de EAN al bestaat (exclusief huidige record)
        $checkEanExists = Product::SP_GetProductByEan($ean);
        $countEan = $checkEanExists[0]->totaal ?? 0;

        // Huidig product ophalen om te checken of naam/EAN hetzelfde zijn
        $currentProduct = Product::SP_GetProductById($id);
        
        if ($currentProduct) {
            // Als naam gewijzigd is en al bestaat
            if ($currentProduct->product_naam !== $naam && $countName > 0) {
                return redirect()->back()->with(
                    'error', 'deze productnaam bestaat al'
                );
            }

            // Als EAN gewijzigd is en al bestaat
            if ($currentProduct->ean !== $ean && $countEan > 0) {
                return redirect()->back()->with(
                    'error', 'deze EAN-code bestaat al'
                );
            }
        }

        // id toevoegen aan data array
        $data['id'] = $id;
        $updated = Product::SP_UpdateProduct($data);

        // Succes/foutmelding teruggeven
        if($updated) {
            return redirect()->route('voorraad.producten.index')->with('success', 'Product succesvol geüpdatet.');
        } else {
            return redirect()->back()->with('error', 'Er ging iets mis bij het updaten.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Soft-delete uitvoeren op product
        $affected = Product::SoftDeleteProductById((int) $id);

        // Succes/foutmelding tonen
        if ($affected > 0) {
            return redirect()->back()->with('success', 'product succesvol verwijderd');
        }

        return redirect()->back()->with('error', 'product niet gevonden of al verwijderd');
    }
}
