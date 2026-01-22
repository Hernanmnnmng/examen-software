<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'Directie';

        $rapportageData = [];
        $maand = null;
        $jaar = null;
        $rapportageType = null;

        // Handle rapportage requests
        if ($isAdmin && $request->has('rapportage')) {
            $rapportageType = $request->input('rapportage');
            $maand = $request->input('maand');
            $jaar = $request->input('jaar');

            if ($maand && $jaar) {
                if ($rapportageType === 'productcategorie') {
                    $rapportageData = DB::select('CALL SP_GetMaandoverzichtProductcategorie(?, ?)', [$maand, $jaar]);
                } elseif ($rapportageType === 'postcode') {
                    $rapportageData = DB::select('CALL SP_GetMaandoverzichtPostcode(?, ?)', [$maand, $jaar]);
                }
            }
        }

        return view('dashboard.index', [
            'isAdmin' => $isAdmin,
            'rapportageType' => $rapportageType,
            'rapportageData' => $rapportageData,
            'maand' => $maand,
            'jaar' => $jaar
        ]);
    }
}
