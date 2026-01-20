<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\leveranciersModel as Leverancier;

class leveranciersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Alleen actieve leveranciers ophalen (soft-deleted leveranciers worden verborgen)
        $leveranciers = Leverancier::SP_GetAllLeveranciers();
        $leveringen = Leverancier::SP_GetAllLeveringen();
        
        // dd($leveringen); // debug optie

        // View laden met alle leveranciers en leveringen
        return view('leveranciers.index', [
            'leveranciers' => $leveranciers,
            'leveringen' => $leveringen
        ]);
    }

    public function storeLeverancier(Request $request) 
    {  
        // Data valideren van formulier input
        $data = $request->validate([
             'bedrijfsnaam'  =>  'required'
            ,'straat'        =>  'required'
            ,'huisnummer'    =>  'required'
            ,'postcode'      =>  'required'
            ,'plaats'        =>  'required'
            ,'contact_naam'  =>  'required'
            ,'email'         =>  'required'
            ,'telefoon'      =>  'required'
        ]);

        // Bedrijfsnaam apart opslaan voor check
        $name = $data['bedrijfsnaam'];

        // Checken of de bedrijfsnaam al bestaat via stored procedure
        $checkNameExists = Leverancier::SP_GetLeverancierByBedrijfsnaam($name);

        // Resultaat in $count zetten (0 = bestaat niet, >0 = bestaat)
        $count = $checkNameExists[0]->totaal ?? 0;

        // Als naam al bestaat, terug met foutmelding
        // Zo niet, leverancier aanmaken
        if ($count > 0) {
            return redirect()->back()->with(
                'error', 'deze leverancier bestaat al'
            );
        } else {
            $result = Leverancier::SP_CreateNewLeverancier($data);
        }

        // Meldingen geven op basis van resultaat
        if($result) {
            return redirect()->back()->with(
                'success', 'leverancier succesvol toegevoegd'
            );
        } else {
            return redirect()->back()->with(
                'error', 'leverancier niet succesvol toegevoegd'
            );
        }
    }

    public function storeLevering(Request $request)
    {
        // Data valideren van levering formulier
        $data = $request->validate([
            'leverancier_id'         => 'required|integer',
            'leverdatum_tijd'        => 'required',
            'eerstvolgende_levering' => 'required'
        ]);

        // Check of leverancier nog actief is
        $id = $data['leverancier_id'];
        $checkIsActief = Leverancier::SP_CheckIfBedrijfIsActiefById($id);
        
        // Als actief, levering aanmaken via stored procedure
        if($checkIsActief) {
            $result = Leverancier::SP_CreateLevering($data);
        } else {
            // Foutmelding als leverancier niet actief is
            return redirect()->back()->with('error', 'de geselecteerde bedrijf is niet meer actief');
        }

        // Succes/foutmelding tonen aan gebruiker
        if($result) {
            return redirect()->back()->with('success', 'Levering succesvol toegevoegd');
        } else {
            return redirect()->back()->with('error', 'Levering niet succesvol toegevoegd');
        }
    }

    public function softDeleteLeverancier(string $id)
    {
        // Soft-delete uitvoeren op leverancier
        $affected = Leverancier::SoftDeleteLeverancierById((int) $id);

        // Succes/foutmelding tonen
        if ($affected > 0) {
            return redirect()->back()->with('success', 'leverancier succesvol verwijderd');
        }

        return redirect()->back()->with('error', 'leverancier niet gevonden of al verwijderd');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    // Pagina laden om leverancier te bewerken
    public function editleverancier($id)
    {
        $leverancier = Leverancier::SP_GetLeverancierById($id);

        // Checken of leverancier bestaat
        if(!$leverancier) {
            return redirect()->route('leveranciers.index')->with('error', 'Leverancier niet gevonden.');
        }

        // Edit view laden
        return view('leveranciers.editleverancier', compact('leverancier'));
    }

    // Leverancier updaten
    public function updateleverancier(Request $request, $id)
    {
        // Input valideren
        $data = $request->validate([
            'bedrijfsnaam' => 'required'
        ]);

        // Bedrijfsnaam apart opslaan voor check
        $name = $data['bedrijfsnaam'];

        // Checken of de bedrijfsnaam al bestaat via stored procedure
        $checkNameExists = Leverancier::SP_GetLeverancierByBedrijfsnaam($name);

        // Resultaat in $count zetten (0 = bestaat niet, >0 = bestaat)
        $count = $checkNameExists[0]->totaal ?? 0;

        // Als naam al bestaat, terug met foutmelding
        // Zo niet, leverancier aanmaken
        if ($count > 0) {
            return redirect()->back()->with(
                'error', 'deze leverancier bestaat al'
            );
        } else {
            // id toevoegen aan data array
            $data['id'] = $id;
            $updated = Leverancier::SP_UpdateLeverancier($data);
        }
        // Succes/foutmelding teruggeven
        if($updated) {
            return redirect()->route('leveranciers.index')->with('success', 'Leverancier succesvol geüpdatet.');
        } else {
            return redirect()->back()->with('error', 'Er ging iets mis bij het updaten.');
        }
    }

    // Pagina laden om levering te bewerken
    public function editLevering($id)
    {
        // Huidige levering ophalen
        $levering = Leverancier::SP_GetLeveringById($id);
        // Alle leveranciers ophalen voor select dropdown
        $leveranciers = Leverancier::SP_GetAllLeveranciers();

        // Checken of levering bestaat
        if (!$levering) {
            return redirect()->route('leveranciers.index')
                ->with('error', 'Levering niet gevonden');
        }

        // Edit view laden
        return view('leveranciers.editlevering', [
            'levering' => $levering,
            'leveranciers' => $leveranciers
        ]);
    }

    // Levering updaten
    public function updateLevering(Request $request, $id)
    {
        // Input valideren
        $data = $request->validate([
            'leverdatum_tijd' => 'required|date',
            'eerstvolgende_levering' => 'required|date',
            'leverancier_id' => 'required|integer'
        ]);

        // Check of leverancier nog actief is
        $id = $data['leverancier_id'];
        $checkIsActief = Leverancier::SP_CheckIfBedrijfIsActiefById($id);
        
        // Als actief, SP uitvoeren om levering te updaten
        if($checkIsActief) {
            $result = Leverancier::SP_CreateLevering($data);
        } else {
            return redirect()->back()->with('error', 'de leverancier van deze levering is niet meer actief');
        }

        // Levering updaten via SP
        $result = Leverancier::SP_UpdateLevering($id, $data);

        // Succes/foutmelding teruggeven
        if ($result) {
            return redirect()->route('leveranciers.index')
                ->with('success', 'Levering succesvol bijgewerkt');
        }

        return redirect()->back()->with('error', 'Fout bij bijwerken levering');
    }
}
