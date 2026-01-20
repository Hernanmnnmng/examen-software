<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * VoedselpakketModel
 *
 * Dit model fungeert als de Data Access Layer voor voedselpakketten.
 * In plaats van directe Eloquent queries, worden hier Stored Procedures aangeroepen
 * om interactie met de database te hebben. Dit zorgt voor centralisatie van SQL logica
 * en scheiding tussen applicatie en database regels.
 */
class VoedselpakketModel extends Model
{
    /**
     * Haalt alle klanten (gezinnen) op uit de database.
     *
     * Roept stored procedure: SP_GetAllKlanten
     * Resultaten worden gebruikt om dropdowns in de UI te vullen.
     *
     * @return array Lijst van klanten objecten met o.a. naam en ID.
     */
    public static function getallklanten(){
        try {
            Log::info("\n\nAlle klanten ophalen uit database...\n");
            // Voer de Stored Procedure uit zonder parameters
            $klanten = DB::select('CALL SP_GetAllKlanten()');
            Log::info("\n\nAlle klanten opgehaald uit database.\n");
            return $klanten;
        } catch (\Throwable $th) {
            // Log de fout en retourneer een lege array zodat de applicatie niet crasht
            Log::error("\n\nFout bij het ophalen van alle klanten uit de database: " . $th->getMessage() . "\n");
            return [];
        }
    }

    /**
     * Haalt alle producten op die beschikbaar/toegestaan zijn voor een specifieke klant.
     * De Stored Procedure filtert producten mogelijk op basis van dieetwensen of allergieën.
     *
     * Roept stored procedure: SP_GetAllProducten(?)
     *
     * @param int $klantid Het ID van de klant waarvoor producten gezocht worden.
     * @return array Lijst van producten inclusief huidige voorraad.
     */
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

    /**
     * Haalt een overzicht van alle voedselpakketten op.
     * Dit wordt getoond op de hoofdpagina (index).
     *
     * Roept stored procedure: SP_GetAllVoedselpakketen
     *
     * @return array Lijst van voedselpakketten met klantnamen en totalen.
     */
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

    /**
     * Haalt de details van één specifiek voedselpakket op.
     * Wordt gebruikt bij het tonen (show) en bewerken (edit).
     *
     * Roept stored procedure: SP_GetVoedselpakketById(?)
     *
     * @param int $id ID van het voedselpakket.
     * @return array Array met objecten (meestal 1 resultaat met header info).
     */
    public static function getvoedselpakketbyid($id){
        try {
            Log::info("\n\nVoedselpakket ophalen uit database met ID: $id...\n");
            $voedselpakket = DB::select('CALL SP_GetVoedselpakketById(?)', [$id]);
            Log::info("\n\nVoedselpakket opgehaald uit database.\n");
            return $voedselpakket;
        } catch (\Throwable $th) {
            Log::error("\n\nFout bij het ophalen van voedselpakket uit de database: " . $th->getMessage() . "\n");
            return [];
        }
    }

    /**
     * Haalt de producten die gekoppeld zijn aan een voedselpakket op.
     * Deze worden getoond in de lijst bij het bewerken.
     *
     * Roept stored procedure: SP_GetVoedselpakketProducten(?)
     *
     * @param int $id ID van het voedselpakket.
     * @return array Lijst van gekoppelde producten met de aantallen in het pakket.
     */
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

    /**
     * Maakt een nieuw voedselpakket (header) aan.
     * Dit creëert het pakket zonder producten.
     *
     * Roept stored procedure: SP_CreateVoedselpakket(?, ?)
     *
     * @param array $data Bevat 'klantid' en 'pakketnmr'.
     * @return int Aantal beïnvloede rijen (affected rows).
     */
    public static function createvoedselpakket($data){
        try {
            Log::info("\n\nVoedselpakket aanmaken in database...\n");
            $res = DB::select('CALL SP_CreateVoedselpakket(?, ?)', [
                $data['klantid']
                ,$data['pakketnmr']
            ]);

            // Controleer of de SP een resultaat teruggaf (bijv. affected rows)
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

    /**
     * Koppelt een product aan een voedselpakket.
     * Wordt aangeroepen in een loop na het aanmaken van het pakket.
     *
     * Roept stored procedure: SP_CreateVoedselpakketProduct(?, ?, ?)
     *
     * @param array $data Bevat 'voedselpakketid', 'productid', 'aantal'.
     * @return int Aantal beïnvloede rijen.
     */
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

    /**
     * Werkt het aantal van een product in een voedselpakket bij.
     * Update ook de voorraad via de 'verschil' parameter in de SP.
     *
     * Roept stored procedure: SP_UpdateVoedselpakketProduct(?, ?, ?, ?)
     * Note: Er zijn 4 parameters nodig (PakketID, ProductID, NieuwAantal, Verschil)
     *
     * @param int $id Het voedselpakket ID.
     * @param array $data Bevat 'productid', 'aantal', 'verschil'.
     * @return int Aantal beïnvloede rijen.
     */
    public static function updatevoedselpakketproduct($id, $data){
        try {
            Log::info("\n\nVoedselpakket product bijwerken in database met ID: $id...\n");
            // Verschil wordt gebruikt door de SP om de voorraadmutatie te berekenen
            // Query string fixed om 4 parameters te verwachten
            $res = DB::select('CALL SP_UpdateVoedselpakketProduct(?, ?, ?, ?)', [
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

    /**
     * Verwijderd een volledig voedselpakket (en cascadede producten).
     * Roept stored procedure: SP_DeleteVoedselpakket(?)
     *
     * @param int $id Voedselpakket ID.
     * @return int Aantal beïnvloede rijen.
     */
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

    /**
     * Markeert een voedselpakket als uitgereikt (datum_uitgifte = NU).
     * Hierdoor wordt het pakket 'gelocked' voor bewerking.
     *
     * Roept stored procedure: SP_DeliverVoedselpakket(?)
     *
     * @param int $id Voedselpakket ID.
     * @return int Aantal beïnvloede rijen (0 of 1).
     */
    public static function delivervoedselpakket($id){
        try {
            Log::info("\n\nVoedselpakket uitreiken in database met ID: $id...\n");
            $res = DB::select('CALL SP_DeliverVoedselpakket(?)', [$id]);

            if($res && count($res) > 0) {
                Log::info("\n\nVoedselpakket uitgereikt in database.\n");
                return $res[0]->Affected;
            }

            return 0;
        } catch (\Throwable $th) {
            Log::error("\n\nFout bij het uitreiken van voedselpakket in de database: " . $th->getMessage() . "\n");
            return -1;
        }
    }
}
