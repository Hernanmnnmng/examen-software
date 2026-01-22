<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    // All active products ophalen uit de database (voorraad beheer)
    static public function SP_GetAllProductenVoorraad()
    {
        try {
            // log dat het is begonnen
            Log::info('SP_GetAllProductenVoorraad gestart');
            // resultaten in $result zetten
            $result = DB::select('CALL SP_GetAllProductenVoorraad()');
            // log dat het succesvol was
            Log::info('SP_GetAllProductenVoorraad succesvol uitgevoerd');
            // $result terug geven
            return $result;
        } catch (\Throwable $e) {
            //log dat het fout is gegaan en waar precies en wat er fout is gegaan
            Log::error('Fout in SP_GetAllProductenVoorraad', ['error' => $e->getMessage()]);
            // lege array terug geven zodat de app niet kapot gaat
            return [];
        }
    }

    // Product ophalen via id met SP_GetProductById
    static public function SP_GetProductById($id)
    {
        try {
            // log begin ophalen product
            Log::info('SP_GetProductById gestart', ['id' => $id]);

            // stored procedure uitvoeren
            $result = DB::select('CALL SP_GetProductById(?)', [$id]);

            // log succes
            Log::info('SP_GetProductById succesvol uitgevoerd');

            // eerste record teruggeven of null als leeg
            return $result[0] ?? null;

        } catch (\Throwable $e) {
            // log foutmelding
            Log::error('Fout in SP_GetProductById', ['error' => $e->getMessage()]);

            // null teruggeven bij fout
            return null;
        }
    }

    // Function om product op te halen op naam om te checken of die al bestaat
    static public function SP_GetProductByNaam($naam)
    {
        try {
            // log begin
            Log::info('SP_GetProductByNaam gestart', ['naam' => $naam]);
            // resultaten in $result zetten
            $result = DB::select('CALL SP_GetProductByNaam(?)', [$naam]);
            
            Log::info('SP_GetProductByNaam succesvol uitgevoerd');
            // result terug geven
            return $result;
        } catch (\Throwable $e) {
            //log dat het fout is gegaan en waar precies en wat er fout is gegaan
            Log::error('Fout in SP_GetProductByNaam', ['error' => $e->getMessage()]);
            // lege array terug geven zodat de app niet kapot gaat
            return [];
        }
    }

    // Function om product op te halen op EAN om te checken of die al bestaat
    static public function SP_GetProductByEan($ean)
    {
        try {
            // log begin
            Log::info('SP_GetProductByEan gestart', ['ean' => $ean]);
            // resultaten in $result zetten
            $result = DB::select('CALL SP_GetProductByEan(?)', [$ean]);
            
            Log::info('SP_GetProductByEan succesvol uitgevoerd');
            // result terug geven
            return $result;
        } catch (\Throwable $e) {
            //log dat het fout is gegaan en waar precies en wat er fout is gegaan
            Log::error('Fout in SP_GetProductByEan', ['error' => $e->getMessage()]);
            // lege array terug geven zodat de app niet kapot gaat
            return [];
        }
    }

    static public function SP_CreateProduct($data)
    {
        try {
            // log begin
            Log::info('SP_CreateProduct gestart', ['data' => $data]);
            // resultaten in $result zetten
            $result = DB::statement('CALL SP_CreateProduct(?, ?, ?, ?)', [
                $data['product_naam'],
                $data['ean'],
                $data['categorie_id'],
                $data['aantal_voorraad'],
            ]);
            // log success
            Log::info('SP_CreateProduct succesvol uitgevoerd');
            // result terug geven
            return $result;
        } catch (\Throwable $e) {
            //log dat het fout is gegaan en waar precies en wat er fout is gegaan
            Log::error('Fout in SP_CreateProduct', ['error' => $e->getMessage()]);
            // leeg terug geven zodat de app niet kapot gaat
            return false;
        }
    }

    // Bestaand product bijwerken via SP_UpdateProduct
    static public function SP_UpdateProduct($data)
    {
        try {
            // log begin update met meegegeven data
            Log::info('SP_UpdateProduct gestart', ['data' => $data]);

            // de stored procedure uitvoeren met alle parameters
            $result = DB::statement('CALL SP_UpdateProduct(?, ?, ?, ?, ?)', [
                $data['id'],             // id van het product
                $data['product_naam'],   // nieuwe productnaam
                $data['ean'],            // nieuwe EAN
                $data['categorie_id'],  // nieuwe categorie
                $data['aantal_voorraad'] // nieuwe voorraad
            ]);

            // loggen dat update succesvol is uitgevoerd
            Log::info('SP_UpdateProduct succesvol uitgevoerd');

            // resultaat teruggeven
            return $result;

        } catch (\Throwable $e) {
            // loggen van foutmelding
            Log::error('Fout in SP_UpdateProduct', ['error' => $e->getMessage()]);

            // false teruggeven zodat de app niet crasht
            return false;
        }
    }

    // Check of product gebruikt wordt in voedselpakketten
    static public function SP_CheckIfProductIsUsedInVoedselpakket($id)
    {
        try {
            // log begin
            Log::info('SP_CheckIfProductIsUsedInVoedselpakket gestart', ['id' => $id]);
            // resultaten in $result zetten
            $result = DB::select('CALL SP_CheckIfProductIsUsedInVoedselpakket(?)', [$id]);
            // log success
            Log::info('SP_CheckIfProductIsUsedInVoedselpakket succesvol uitgevoerd');
            // result terug geven
            return $result;
        } catch (\Throwable $e) {
            //log dat het fout is gegaan en waar precies en wat er fout is gegaan
            Log::error('Fout in SP_CheckIfProductIsUsedInVoedselpakket', ['error' => $e->getMessage()]);
            // lege array terug geven zodat de app niet kapot gaat
            return [];
        }
    }

    static public function SoftDeleteProductById(int $id): int
    {
        try {
            // log begin
            Log::info('SoftDeleteProductById gestart', ['id' => $id]);
            // resultaten in $result zetten
            $result = DB::update('UPDATE producten SET is_actief = 0 WHERE id = ?', [$id]);
            // log success
            Log::info('SoftDeleteProductById succesvol uitgevoerd');
            // result terug geven
            return $result;
        } catch (\Throwable $e) {
            //log dat het fout is gegaan en waar precies en wat er fout is gegaan
            Log::error('Fout in SoftDeleteProductById', ['error' => $e->getMessage()]);
            // leeg terug geven zodat de app niet kapot gaat
            return 0;
        }
    }
}
