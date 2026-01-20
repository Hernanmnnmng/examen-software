<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoedselpakketModel extends Model
{
    //
    public static function getallklanten(){
        try {
            Log::info("\n\nAlle klanten ophalen uit database...\n");
            $klanten = DB::select('CALL SP_GetAllKlanten()');
            Log::info("\n\nAlle klanten opgehaald uit database.\n");
            return $klanten;
        } catch (\Throwable $th) {
            Log::error("\n\nEFout bij het ophalen van alle klanten uit de database: " . $th->getMessage() . "\n");
            return [];
        }
    }

    public static function getallproducten($klantid){
        try {
            Log::info("\n\nAlle producten ophalen uit database...\n");
            $producten = DB::select('CALL SP_GetAllProducten(?)', [$klantid]);
            Log::info("\n\nAlle producten opgehaald uit database.\n");
            return $producten;
        } catch (\Throwable $th) {
            Log::error("\n\nFout bij het ophalen van alle producten from database: " . $th->getMessage() . "\n");
            return [];
        }
    }

    public static function getallvoedselpakketten(){
        try {
            Log::info("\n\nAlle voedselpakketten ophalen uit database...\n");
            $voedselpakketten = DB::select('CALL SP_GetAllVoedselpakketen()');
            Log::info("\n\nAlle voedselpakketten opgehaald uit database.\n");
            return $voedselpakketten;
        } catch (\Throwable $th) {
            Log::error("\n\nFout bij het ophalen van alle voedselpakketten uit de database: " . $th->getMessage() . "\n");
            return [];
        }
    }

    public static function getvoedselpakketbyid($id){
        try {
            Log::info("\n\nVoedselpakket ophalen uit database met ID: $id...\n");
            $voedselpakket = DB::select('CALL SP_GetVoedselpakketById(?)', [$id]);
            Log::info("\n\nVoedselpakket opgehaald uit database.\n");
            return $voedselpakket;
        } catch (\Throwable $th) {
            Log::error("\n\nFout bij het ophalen van alle voedselpakketen uit de database: " . $th->getMessage() . "\n");
            return [];
        }
    }

    public static function getvoedselpakketproducten($id){
        try {
            Log::info("\n\nVoedselpakket producten ophalen: $id...\n");
            $producten = DB::select('CALL SP_GetVoedselpakketProducten(?)', [$id]);
            Log::info("\n\nVoedselpakket producten opgehaald.\n");
            return $producten;
        } catch (\Throwable $th) {
            Log::error("\n\nFout bij het ophalen van producten: " . $th->getMessage() . "\n");
            return [];
        }
    }

    public static function createvoedselpakket($data){
        try {
            Log::info("\n\nVoedselpakket aanmaken in database...\n");
            $res = DB::select('CALL SP_CreateVoedselpakket(?, ?)', [
                $data['klantid']
                ,$data['pakketnmr']
            ]);
            if($res && count($res) > 0) {
                Log::info("\n\nVoedselpakket aangemaakt in database.\n");
                return $res[0]->Affected;
            }
            throw new \Exception("Geen resultaat bij het aanmaken van voedselpakket.");
        } catch (\Throwable $th) {
            Log::error("\n\nFout bij het aanmaken van voedselpakket in de database: " . $th->getMessage() . "\n");
            return -1;
        }
    }

    public static function createvoedselpakketproduct($data){
        try {
            Log::info("\n\nVoedselpakket product aanmaken in database...\n");
            $res = DB::select('CALL SP_CreateVoedselpakketProduct(?, ?, ?)', [
                $data['voedselpakketid']
                ,$data['productid']
                ,$data['aantal']
            ]);

            if($res && count($res) > 0) {
                Log::info("\n\nVoedselpakket product aangemaakt in database.\n");
                return $res[0]->Affected;
            }

            throw new \Exception("Geen resultaat bij het aanmaken van voedselpakket product.");
        } catch (\Throwable $th) {
            Log::error("\n\nFout bij het aanmaken van voedselpakket product in de database: " . $th->getMessage() . "\n");
            return -1;
        }
    }

    public static function updatevoedselpakketproduct($id, $data){
        try {
            Log::info("\n\nVoedselpakket product bijwerken in database met ID: $id...\n");
            $res = DB::select('CALL SP_UpdateVoedselpakketProduct(?, ?, ?)', [
                $id
                ,$data['productid']
                ,$data['aantal']
                ,$data['verschil']
            ]);

            if($res && count($res) > 0) {
                Log::info("\n\nVoedselpakket product bijgewerkt in database.\n");
                return $res[0]->Affected;
            }

            throw new \Exception("Geen resultaat bij het bijwerken van voedselpakket product.");
        } catch (\Throwable $th) {
            Log::error("\n\nFout bij het bijwerken van voedselpakket product in de database: " . $th->getMessage() . "\n");
            return -1;
        }
    }

    public static function deletevoedselpakket($id){
        try {
            Log::info("\n\nVoedselpakket verwijderen uit database met ID: $id...\n");
            $res = DB::select('CALL SP_DeleteVoedselpakket(?)', [$id]);

            if($res && count($res) > 0) {
                Log::info("\n\nVoedselpakket verwijderd uit database.\n");
                return $res[0]->Affected;
            }

            throw new \Exception("Geen resultaat bij het verwijderen van voedselpakket.");
        } catch (\Throwable $th) {
            Log::error("\n\nFout bij het verwijderen van voedselpakket uit de database: " . $th->getMessage() . "\n");
            return -1;
        }
    }

    public static function delivervoedselpakket($id){
        try {
            Log::info("\n\nVoedselpakket uitreiken in database met ID: $id...\n");
            $res = DB::select('CALL SP_DeliverVoedselpakket(?)', [$id]);

            if($res && count($res) > 0) {
                Log::info("\n\nVoedselpakket uitgereikt in database.\n");
                return $res[0]->Affected;
            }
             // Even if row_count is 0 (already done), it returns a row with Affected=0 often.
             // But if specific select logic is used, handled here. SP returns ROW_COUNT() as Affected.
            return 0;
        } catch (\Throwable $th) {
            Log::error("\n\nFout bij het uitreiken van voedselpakket in de database: " . $th->getMessage() . "\n");
            return -1;
        }
    }

}
