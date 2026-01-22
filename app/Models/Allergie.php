<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Allergie extends Model
{
    // All active allergenen ophalen uit de database
    static public function SP_GetAllAllergenen()
    {
        try {
            // log dat het is begonnen
            Log::info('SP_GetAllAllergenen gestart');
            // resultaten in $result zetten
            $result = DB::select('CALL SP_GetAllAllergenen()');
            // log dat het succesvol was
            Log::info('SP_GetAllAllergenen succesvol uitgevoerd');
            // $result terug geven
            return $result;
        } catch (\Throwable $e) {
            //log dat het fout is gegaan en waar precies en wat er fout is gegaan
            Log::error('Fout in SP_GetAllAllergenen', ['error' => $e->getMessage()]);
            // lege array terug geven zodat de app niet kapot gaat
            return [];
        }
    }
}
