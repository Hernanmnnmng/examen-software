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

    public function softDeleteLeverancier(string $id)
    {
        $affected = Leverancier::SoftDeleteLeverancierById((int) $id);

        if ($affected > 0) {
            return redirect()->back()->with('success', 'leverancier succesvol verwijderd');
        }

        return redirect()->back()->with('error', 'leverancier niet gevonden of al verwijderd');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
