<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Wens extends Model
{
    // All active wensen ophalen uit de database
    static public function SP_GetAllWensen()
    {
        try {
            // log dat het is begonnen
            Log::info('SP_GetAllWensen gestart');
            // resultaten in $result zetten
            $result = DB::select('CALL SP_GetAllWensen()');
            // log dat het succesvol was
            Log::info('SP_GetAllWensen succesvol uitgevoerd');
            // $result terug geven
            return $result;
        } catch (\Throwable $e) {
            //log dat het fout is gegaan en waar precies en wat er fout is gegaan
            Log::error('Fout in SP_GetAllWensen', ['error' => $e->getMessage()]);
            // lege array terug geven zodat de app niet kapot gaat
            return [];
        }
    }
}
