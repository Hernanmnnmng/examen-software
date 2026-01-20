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
        // Alle leveranciers ophalen (actief + niet-actief).
        $leveranciers = Leverancier::GetAllLeveranciers();
        // Alle leveringen ophalen (actief + niet-actief).
        $leveringen = Leverancier::GetAllLeveringen();
        
        // dd($leveringen); // debug optie

        // View laden met alle leveranciers en leveringen
        return view('leveranciers.index', [
            'leveranciers' => $leveranciers,
            'leveringen' => $leveringen
        ]);
    }

    public function createLeverancier()
    {
        return view('leveranciers.createleverancier');
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
            return redirect()
                ->route('leveranciers.index')
                ->with('success', 'leverancier succesvol toegevoegd');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'leverancier niet succesvol toegevoegd');
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

    public function softDeleteLevering(string $id)
    {
        // Soft-delete uitvoeren op leverancier
        $affected = Leverancier::SoftDeleteLeveringById((int) $id);

        // Succes/foutmelding tonen
        if ($affected > 0) {
            return redirect()->back()->with('success', 'levering succesvol verwijderd');
        }

        return redirect()->back()->with('error', 'levering niet gevonden of al verwijderd');
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
        // #region agent log
        @file_put_contents(
            base_path('.cursor/debug.log'),
            json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H1',
                'location' => 'leveranciersController.php:updateleverancier:entry',
                'message' => 'Enter updateleverancier',
                'data' => ['route_id' => (int) $id],
                'timestamp' => (int) (microtime(true) * 1000),
            ]).PHP_EOL,
            FILE_APPEND
        );
        // #endregion

        // Input valideren
        $data = $request->validate([
            'bedrijfsnaam' => 'required'
        ]);

        // Bedrijfsnaam apart opslaan voor check
        $name = $data['bedrijfsnaam'];

        // #region agent log
        @file_put_contents(
            base_path('.cursor/debug.log'),
            json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H2',
                'location' => 'leveranciersController.php:updateleverancier:validated',
                'message' => 'Validated input for updateleverancier',
                'data' => ['route_id' => (int) $id, 'bedrijfsnaam_len' => strlen((string) $name)],
                'timestamp' => (int) (microtime(true) * 1000),
            ]).PHP_EOL,
            FILE_APPEND
        );
        // #endregion

        // Checken of de bedrijfsnaam al bestaat via stored procedure
        $checkNameExists = Leverancier::SP_GetLeverancierByBedrijfsnaam($name);

        // Resultaat in $count zetten (0 = bestaat niet, >0 = bestaat)
        $count = $checkNameExists[0]->totaal ?? 0;

        // #region agent log
        @file_put_contents(
            base_path('.cursor/debug.log'),
            json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H2',
                'location' => 'leveranciersController.php:updateleverancier:nameCheck',
                'message' => 'Name existence check result',
                'data' => ['route_id' => (int) $id, 'count' => (int) $count],
                'timestamp' => (int) (microtime(true) * 1000),
            ]).PHP_EOL,
            FILE_APPEND
        );
        // #endregion

        // Als naam al bestaat, alleen toestaan wanneer dit dezelfde leverancier is (merge-friendly UX).
        if ($count > 0) {
            $current = Leverancier::SP_GetLeverancierById($id);
            $currentName = $current->bedrijfsnaam ?? null;

            if ($currentName !== null && (string) $currentName === (string) $name) {
                // ok: name unchanged
            } else {
                // #region agent log
                @file_put_contents(
                    base_path('.cursor/debug.log'),
                    json_encode([
                        'sessionId' => 'debug-session',
                        'runId' => 'pre-fix',
                        'hypothesisId' => 'H3',
                        'location' => 'leveranciersController.php:updateleverancier:blockedDuplicate',
                        'message' => 'Blocked update due to duplicate name',
                        'data' => ['route_id' => (int) $id, 'currentName_present' => $currentName !== null],
                        'timestamp' => (int) (microtime(true) * 1000),
                    ]).PHP_EOL,
                    FILE_APPEND
                );
                // #endregion

                return redirect()->back()->with(
                    'error', 'deze leverancier bestaat al'
                );
            }
        } else {
            // id toevoegen aan data array
            $data['id'] = $id;
            $updated = Leverancier::SP_UpdateLeverancier($data);
        }

        // If name existed but unchanged, still run update (id + name)
        if (!isset($updated)) {
            $data['id'] = $id;
            $updated = Leverancier::SP_UpdateLeverancier($data);
        }

        // #region agent log
        @file_put_contents(
            base_path('.cursor/debug.log'),
            json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H4',
                'location' => 'leveranciersController.php:updateleverancier:afterUpdate',
                'message' => 'After SP_UpdateLeverancier call',
                'data' => ['route_id' => (int) $id, 'updated' => (bool) $updated],
                'timestamp' => (int) (microtime(true) * 1000),
            ]).PHP_EOL,
            FILE_APPEND
        );
        // #endregion

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
        // #region agent log
        @file_put_contents(
            base_path('.cursor/debug.log'),
            json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H5',
                'location' => 'leveranciersController.php:updateLevering:entry',
                'message' => 'Enter updateLevering',
                'data' => ['route_id' => (int) $id],
                'timestamp' => (int) (microtime(true) * 1000),
            ]).PHP_EOL,
            FILE_APPEND
        );
        // #endregion

        // Input valideren
        $data = $request->validate([
            'leverdatum_tijd' => 'required|date',
            'eerstvolgende_levering' => 'required|date',
            'leverancier_id' => 'required|integer'
        ]);

        // Check of leverancier nog actief is
        $leveringId = (int) $id;
        $leverancierId = (int) $data['leverancier_id'];
        $checkIsActief = Leverancier::SP_CheckIfBedrijfIsActiefById($leverancierId);

        // #region agent log
        @file_put_contents(
            base_path('.cursor/debug.log'),
            json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H5',
                'location' => 'leveranciersController.php:updateLevering:validated',
                'message' => 'Validated updateLevering + active check',
                'data' => [
                    'leveringId' => $leveringId,
                    'leverancierId' => $leverancierId,
                    'checkIsActief' => (bool) $checkIsActief,
                ],
                'timestamp' => (int) (microtime(true) * 1000),
            ]).PHP_EOL,
            FILE_APPEND
        );
        // #endregion
        
        // Als niet actief, foutmelding
        if (! $checkIsActief) {
            return redirect()->back()->with('error', 'de leverancier van deze levering is niet meer actief');
        }

        // Levering updaten via SP
        $result = Leverancier::SP_UpdateLevering($leveringId, $data);

        // #region agent log
        @file_put_contents(
            base_path('.cursor/debug.log'),
            json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H6',
                'location' => 'leveranciersController.php:updateLevering:afterUpdate',
                'message' => 'After SP_UpdateLevering call',
                'data' => ['leveringId' => $leveringId, 'result' => (bool) $result],
                'timestamp' => (int) (microtime(true) * 1000),
            ]).PHP_EOL,
            FILE_APPEND
        );
        // #endregion

        // Succes/foutmelding teruggeven
        if ($result) {
            return redirect()->route('leveranciers.index')
                ->with('success', 'Levering succesvol bijgewerkt');
        }

        return redirect()->back()->with('error', 'Fout bij bijwerken levering');
    }
}
