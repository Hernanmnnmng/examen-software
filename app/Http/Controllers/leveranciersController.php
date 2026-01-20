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
        // Only show active suppliers (soft-deleted suppliers are hidden)
        $leveranciers = Leverancier::GetActiveLeveranciers();
        $leveringen = Leverancier::SP_GetAllLeveringen();
        
        // dd($leveringen);

        return view('leveranciers.index', [
            'leveranciers' => $leveranciers,
            'leveringen' => $leveringen
        ]);
    }

    public function storeLeverancier(Request $request) 
    {  
        // data 
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

        $name = $data['bedrijfsnaam'];
        $checkNameExists = Leverancier::SP_GetLeverancierByBedrijfsnaam($name);

        $count = $checkNameExists[0]->totaal ?? 0;

        if ($count > 0) {
            return redirect()->back()->with(
                'error', 'deze leverancier bestaat al'
            );
        } else {
            $result = Leverancier::SP_CreateNewLeverancier($data);
        }

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
        $data = $request->validate([
            'leverancier_id'         => 'required|integer',
            'leverdatum_tijd'        => 'required',
            'eerstvolgende_levering' => 'required'
        ]);

        $id = $data['leverancier_id'];
        $checkIsActief = Leverancier::SP_CheckIfBedrijfIsAciefById($id);
        
        if($checkIsActief) {
            $result = Leverancier::SP_CreateLevering($data);
        } else {
            return redirect()->back()->with('error', 'de geselecteerde bedrijf is niet meer actief');
        }

        if($result) {
            return redirect()->back()->with('success', 'Levering succesvol toegevoegd');
        } else {
            return redirect()->back()->with('error', 'Levering niet succesvol toegevoegd');
        }
    }


    public function softDeleteLeverancier(string $id)
    {
        $affected = Leverancier::SoftDeleteLeverancierById((int) $id);

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

    public function editleverancier($id)
    {
        $leverancier = Leverancier::SP_GetLeverancierById($id);

        if(!$leverancier) {
            return redirect()->route('leveranciers.index')->with('error', 'Leverancier niet gevonden.');
        }

        return view('leveranciers.editleverancier', compact('leverancier'));
    }

    public function updateleverancier(Request $request, $id)
    {
        $data = $request->validate([
            'bedrijfsnaam' => 'required'
        ]);

        $data['id'] = $id;

        $updated = Leverancier::SP_UpdateLeverancier($data);

        if($updated) {
            return redirect()->route('leveranciers.index')->with('success', 'Leverancier succesvol geüpdatet.');
        } else {
            return redirect()->back()->with('error', 'Er ging iets mis bij het updaten.');
        }
    }

    public function editLevering($id)
    {
        $levering = Leverancier::SP_GetLeveringById($id);
        $leveranciers = Leverancier::SP_GetAllLeveranciers();

        if (!$levering) {
            return redirect()->route('leveranciers.index')
                ->with('error', 'Levering niet gevonden');
        }

        return view('leveranciers.editlevering', [
            'levering' => $levering,
            'leveranciers' => $leveranciers
        ]);
    }

    public function updateLevering(Request $request, $id)
    {
        $data = $request->validate([
            'leverdatum_tijd' => 'required|date',
            'eerstvolgende_levering' => 'required|date',
            'leverancier_id' => 'required|integer'
        ]);

        $id = $data['leverancier_id'];
        $checkIsActief = Leverancier::SP_CheckIfBedrijfIsAciefById($id);
        
        if($checkIsActief) {
            $result = Leverancier::SP_CreateLevering($data);
        } else {
            return redirect()->back()->with('error', 'de geselecteerde bedrijf is niet meer actief');
        }

        $result = Leverancier::SP_UpdateLevering($id, $data);

        if ($result) {
            return redirect()->route('leveranciers.index')
                ->with('success', 'Levering succesvol bijgewerkt');
        }

        return redirect()->back()->with('error', 'Fout bij bijwerken levering');
    }
}
