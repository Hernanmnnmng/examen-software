<?php

namespace App\Http\Controllers\Voorraad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategorie;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Alleen actieve categorieen ophalen (soft-deleted categorieen worden verborgen)
        $categorieen = ProductCategorie::SP_GetAllCategorieen();

        // View laden met alle categorieen
        return view('voorraad.categorieen.index', [
            'categorieen' => $categorieen
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('voorraad.categorieen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Data valideren van formulier input
        $data = $request->validate([
            'naam' => 'required|string|max:100'
        ]);

        // Categorienaam apart opslaan voor check
        $naam = $data['naam'];

        // Checken of de categorienaam al bestaat via stored procedure
        $checkNameExists = ProductCategorie::SP_GetCategorieByNaam($naam);

        // Resultaat in $count zetten (0 = bestaat niet, >0 = bestaat)
        $count = $checkNameExists[0]->totaal ?? 0;

        // Als naam al bestaat, terug met foutmelding
        // Zo niet, categorie aanmaken
        if ($count > 0) {
            return redirect()->back()->with(
                'error', 'deze categorie bestaat al'
            );
        } else {
            $result = ProductCategorie::SP_CreateCategorie($data);
        }

        // Meldingen geven op basis van resultaat
        if($result) {
            return redirect()->back()->with(
                'success', 'categorie succesvol toegevoegd'
            );
        } else {
            return redirect()->back()->with(
                'error', 'categorie niet succesvol toegevoegd'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Huidige categorie ophalen
        $categorie = ProductCategorie::SP_GetCategorieById($id);

        // Checken of categorie bestaat
        if (!$categorie) {
            return redirect()->route('voorraad.categorieen.index')
                ->with('error', 'Categorie niet gevonden');
        }

        // Edit view laden
        return view('voorraad.categorieen.edit', [
            'categorie' => $categorie
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Input valideren
        $data = $request->validate([
            'naam' => 'required|string|max:100'
        ]);

        // Categorienaam apart opslaan voor check
        $naam = $data['naam'];

        // Checken of de categorienaam al bestaat via stored procedure
        $checkNameExists = ProductCategorie::SP_GetCategorieByNaam($naam);

        // Resultaat in $count zetten (0 = bestaat niet, >0 = bestaat)
        $count = $checkNameExists[0]->totaal ?? 0;

        // Huidige categorie ophalen om te checken of naam hetzelfde is
        $currentCategorie = ProductCategorie::SP_GetCategorieById($id);

        // Als naam gewijzigd is en al bestaat
        if ($currentCategorie && $currentCategorie->naam !== $naam && $count > 0) {
            return redirect()->back()->with(
                'error', 'deze categorie bestaat al'
            );
        }

        // id toevoegen aan data array
        $data['id'] = $id;
        $updated = ProductCategorie::SP_UpdateCategorie($data);

        // Succes/foutmelding teruggeven
        if($updated) {
            return redirect()->route('voorraad.categorieen.index')->with('success', 'Categorie succesvol geüpdatet.');
        } else {
            return redirect()->back()->with('error', 'Er ging iets mis bij het updaten.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Soft-delete uitvoeren op categorie
        $affected = ProductCategorie::SoftDeleteCategorieById((int) $id);

        // Succes/foutmelding tonen
        if ($affected > 0) {
            return redirect()->back()->with('success', 'categorie succesvol verwijderd');
        }

        return redirect()->back()->with('error', 'categorie niet gevonden of al verwijderd');
    }
}
