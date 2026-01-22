<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductCategorie extends Model
{
    // All active categorieen ophalen uit de database
    static public function SP_GetAllCategorieen()
    {
        try {
            // log dat het is begonnen
            Log::info('SP_GetAllCategorieen gestart');
            // resultaten in $result zetten
            $result = DB::select('CALL SP_GetAllCategorieen()');
            // log dat het succesvol was
            Log::info('SP_GetAllCategorieen succesvol uitgevoerd');
            // $result terug geven
            return $result;
        } catch (\Throwable $e) {
            //log dat het fout is gegaan en waar precies en wat er fout is gegaan
            Log::error('Fout in SP_GetAllCategorieen', ['error' => $e->getMessage()]);
            // lege array terug geven zodat de app niet kapot gaat
            return [];
        }
    }

    // Categorie ophalen via id met SP_GetCategorieById
    static public function SP_GetCategorieById($id)
    {
        try {
            // log begin ophalen categorie
            Log::info('SP_GetCategorieById gestart', ['id' => $id]);

            // stored procedure uitvoeren
            $result = DB::select('CALL SP_GetCategorieById(?)', [$id]);

            // log succes
            Log::info('SP_GetCategorieById succesvol uitgevoerd');

            // eerste record teruggeven of null als leeg
            return $result[0] ?? null;

        } catch (\Throwable $e) {
            // log foutmelding
            Log::error('Fout in SP_GetCategorieById', ['error' => $e->getMessage()]);

            // null teruggeven bij fout
            return null;
        }
    }

    // Function om categorie op te halen op naam om te checken of die al bestaat
    static public function SP_GetCategorieByNaam($naam)
    {
        try {
            // log begin
            Log::info('SP_GetCategorieByNaam gestart', ['naam' => $naam]);
            // resultaten in $result zetten
            $result = DB::select('CALL SP_GetCategorieByNaam(?)', [$naam]);
            
            Log::info('SP_GetCategorieByNaam succesvol uitgevoerd');
            // result terug geven
            return $result;
        } catch (\Throwable $e) {
            //log dat het fout is gegaan en waar precies en wat er fout is gegaan
            Log::error('Fout in SP_GetCategorieByNaam', ['error' => $e->getMessage()]);
            // lege array terug geven zodat de app niet kapot gaat
            return [];
        }
    }

    static public function SP_CreateCategorie($data)
    {
        try {
            // log begin
            Log::info('SP_CreateCategorie gestart', ['data' => $data]);
            // resultaten in $result zetten
            $result = DB::statement('CALL SP_CreateCategorie(?)', [
                $data['naam'],
            ]);
            // log success
            Log::info('SP_CreateCategorie succesvol uitgevoerd');
            // result terug geven
            return $result;
        } catch (\Throwable $e) {
            //log dat het fout is gegaan en waar precies en wat er fout is gegaan
            Log::error('Fout in SP_CreateCategorie', ['error' => $e->getMessage()]);
            // leeg terug geven zodat de app niet kapot gaat
            return false;
        }
    }

    // Bestaande categorie bijwerken via SP_UpdateCategorie
    static public function SP_UpdateCategorie($data)
    {
        try {
            // log begin update met meegegeven data
            Log::info('SP_UpdateCategorie gestart', ['data' => $data]);

            // de stored procedure uitvoeren met id en nieuwe naam
            $result = DB::statement('CALL SP_UpdateCategorie(?, ?)', [
                $data['id'],      // id van de categorie
                $data['naam']     // nieuwe naam
            ]);

            // loggen dat update succesvol is uitgevoerd
            Log::info('SP_UpdateCategorie succesvol uitgevoerd');

            // resultaat teruggeven
            return $result;

        } catch (\Throwable $e) {
            // loggen van foutmelding
            Log::error('Fout in SP_UpdateCategorie', ['error' => $e->getMessage()]);

            // false teruggeven zodat de app niet crasht
            return false;
        }
    }

    // Check of categorie gebruikt wordt in producten
    static public function SP_CheckIfCategorieIsUsedInProducten($id)
    {
        try {
            // log begin
            Log::info('SP_CheckIfCategorieIsUsedInProducten gestart', ['id' => $id]);
            // resultaten in $result zetten
            $result = DB::select('CALL SP_CheckIfCategorieIsUsedInProducten(?)', [$id]);
            // log success
            Log::info('SP_CheckIfCategorieIsUsedInProducten succesvol uitgevoerd');
            // result terug geven
            return $result;
        } catch (\Throwable $e) {
            //log dat het fout is gegaan en waar precies en wat er fout is gegaan
            Log::error('Fout in SP_CheckIfCategorieIsUsedInProducten', ['error' => $e->getMessage()]);
            // lege array terug geven zodat de app niet kapot gaat
            return [];
        }
    }

    static public function SoftDeleteCategorieById(int $id): int
    {
        try {
            // log begin
            Log::info('SoftDeleteCategorieById gestart', ['id' => $id]);
            // resultaten in $result zetten
            $result = DB::update('UPDATE product_categorieen SET is_actief = 0 WHERE id = ?', [$id]);
            // log success
            Log::info('SoftDeleteCategorieById succesvol uitgevoerd');
            // result terug geven
            return $result;
        } catch (\Throwable $e) {
            //log dat het fout is gegaan en waar precies en wat er fout is gegaan
            Log::error('Fout in SoftDeleteCategorieById', ['error' => $e->getMessage()]);
            // leeg terug geven zodat de app niet kapot gaat
            return 0;
        }
    }
}
