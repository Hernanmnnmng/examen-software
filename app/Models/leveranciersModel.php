<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class leveranciersModel extends Model
{    
     // All actieve leveranciers ophalen uit de database
     static public function GetActiveLeveranciers()
     {    
          // try catch voor veiligheid en error handeling
          try {
               //loggen dat het is begonnen
               Log::info('GetActiveLeveranciers gestart');
               
               // opgehaalde results in $result zetten
               $result = DB::select(
                    'SELECT 
                         lvrn.id,
                         lvrn.bedrijfsnaam,
                         adrs.straat,
                         adrs.huisnummer,
                         adrs.postcode,
                         adrs.plaats,
                         cprs.contact_naam,
                         cprs.email,
                         cprs.telefoon
                    FROM leveranciers lvrn
                    INNER JOIN adressen adrs 
                         ON lvrn.adres_id = adrs.id
                    INNER JOIN contactpersonen cprs 
                         ON lvrn.contactpersoon_id = cprs.id
                    WHERE lvrn.is_actief = 1'
               );

               // log dat het succesvol was
               Log::info('GetActiveLeveranciers succesvol uitgevoerd');

               // $result terug geven
               return $result;
          // catch als er een fout is
          } catch (\Throwable $e) {
               // de fout loggen
               Log::error('Fout in GetActiveLeveranciers', ['error' => $e->getMessage()]);

               //lege array terug geven zodat de app niet kapot gaat
               return [];
          }
     }

     // function om alle leveranciers op te halen
     static public function SP_GetAllLeveranciers()
     {
          try {
               //log dat het is begonnen
               Log::info('SP_GetAllLeveranciers gestart');
               //result van de stored procedure zetten in $result
               $result = DB::select('CALL SP_GetAllLeveranciers()'); 
               // log dat het succesvol was
               Log::info('SP_GetAllLeveranciers succesvol uitgevoerd');
               // $result terug geven
               return $result;
          } catch (\Throwable $e) {
               //log dat het fout is gegaan
               Log::error('Fout in SP_GetAllLeveranciers', ['error' => $e->getMessage()]);
               //lege array terug geven zodat de app niet kapot gaat
               return [];
          }
     }

     //alle leveringen ophalen
     static public function SP_GetAllLeveringen()
     {
          try {    
               //log dat het is begonnen
               Log::info('SP_GetAllLeveringen gestart');
               // resultaten in $result zetten
               $result = DB::select('CALL SP_GetAllLeveringen()');
               // log dat het goed is gegaan
               Log::info('SP_GetAllLeveringen succesvol uitgevoerd');
               // $result teruggeven
               return $result;
          } catch (\Throwable $e) {
               //log dat het fout is gegaan en waar precies en wat er fout is gegaan
               Log::error('Fout in SP_GetAllLeveringen', ['error' => $e->getMessage()]);
               // lege array terug geven zodat de app niet kapot gaat
               return [];
          }
     }
     
     // fucntion om leverancier op te halen op naam om te checken of die al bestaat
     static public function SP_GetLeverancierByBedrijfsnaam($name)
     {
          try {
               // log begin
               Log::info('SP_GetLeverancierByBedrijfsnaam gestart', ['name' => $name]);
               // resultaten in $result zetten
               $result = DB::select('CALL SP_GetLeverancierByBedrijfsnaam(?)', [$name]);
               
               Log::info('SP_GetLeverancierByBedrijfsnaam succesvol uitgevoerd');
               // result terug geven
               return $result;
          } catch (\Throwable $e) {
               //log dat het fout is gegaan en waar precies en wat er fout is gegaan
               Log::error('Fout in SP_GetLeverancierByBedrijfsnaam', ['error' => $e->getMessage()]);
               // lege array terug geven zodat de app niet kapot gaat
               return [];
          }
     }

     static public function SP_CreateNewLeverancier($data)
     {
          try {
               // log begin
               Log::info('SP_CreateNewLeverancier gestart', ['data' => $data]);
               // resultaten in $result zetten
               $result = DB::statement('CALL SP_CreateNewLeverancier(?, ?, ?, ?, ?, ?, ?, ?)', [
                    $data['bedrijfsnaam'],
                    $data['straat'],
                    $data['huisnummer'],
                    $data['postcode'],
                    $data['plaats'],
                    $data['contact_naam'],
                    $data['email'],
                    $data['telefoon'],
               ]);
               // log success
               Log::info('SP_CreateNewLeverancier succesvol uitgevoerd');
               // result terug geven
               return $result;
          } catch (\Throwable $e) {
               //log dat het fout is gegaan en waar precies en wat er fout is gegaan
               Log::error('Fout in SP_CreateNewLeverancier', ['error' => $e->getMessage()]);
               // leeg terug geven zodat de app niet kapot gaat
               return false;
          }
     }

     static public function SoftDeleteLeverancierById(int $id): int
     {
          try {
               // log begin
               Log::info('SoftDeleteLeverancierById gestart', ['id' => $id]);
               // resultaten in $result zetten
               $result = DB::update('UPDATE 
                                        leveranciers 
                                   SET is_actief = 0 WHERE id = ?', [$id]);
               // log success
               Log::info('SoftDeleteLeverancierById succesvol uitgevoerd');
               // result terug geven
               return $result;
          } catch (\Throwable $e) {
               //log dat het fout is gegaan en waar precies en wat er fout is gegaan
               Log::error('Fout in SoftDeleteLeverancierById', ['error' => $e->getMessage()]);
               // leeg terug geven zodat de app niet kapot gaat
               return 0;
          }
     }

     static public function SoftDeleteLeveringenByLeverancierId(int $id): int
     {
     try {
          // log begin
          Log::info('SoftDeleteLeveringenByLeverancierId gestart', ['leverancier_id' => $id]);

          // update alle leveringen voor deze leverancier
          $result = DB::update('UPDATE leveringen SET is_actief = 0 WHERE leverancier_id = ?', [$id]);

          // log success
          Log::info('SoftDeleteLeveringenByLeverancierId succesvol uitgevoerd', ['aantal_affected' => $result]);

          // return aantal gewijzigde rijen
          return $result;
     } catch (\Throwable $e) {
          // log error
          Log::error('Fout in SoftDeleteLeveringenByLeverancierId', ['error' => $e->getMessage()]);
          return 0;
     }
     }


     static public function SoftDeleteLeveringById(int $id): int
     {
          try {
               // log begin
               Log::info('SoftDeleteLeveringById gestart', ['id' => $id]);
               // resultaten in $result zetten
               $result = DB::update('UPDATE leveringen SET is_actief = 0 WHERE id = ?', [$id]);
               // log success
               Log::info('SoftDeleteLeveringById succesvol uitgevoerd');
               // result terug geven
               return $result;
          } catch (\Throwable $e) {
               //log dat het fout is gegaan en waar precies en wat er fout is gegaan
               Log::error('Fout in SoftDeleteLeveringById', ['error' => $e->getMessage()]);
               // leeg terug geven zodat de app niet kapot gaat
               return 0;
          }
     }

     static public function SP_CheckIfBedrijfIsActiefById($id): bool
     {
          try {
               // log begin
               Log::info('SP_CheckIfBedrijfIsActiefById gestart', ['id' => $id]);
               // resultaten in $result zetten
               $result = DB::select('CALL SP_CheckIfBedrijfIsActiefById(?)', [$id]);
               // status klaar maken om te returnen moet TRUE of FALSE zijn andere waarden mogen niet
               $status = !empty($result) ? (bool)$result[0]->is_actief : false;
               // log success
               Log::info('SP_CheckIfBedrijfIsActiefById succesvol uitgevoerd', ['status' => $status]);
               // result terug geven
               return $status;
          } catch (\Throwable $e) {
               //log dat het fout is gegaan en waar precies en wat er fout is gegaan
               Log::error('Fout in SP_CheckIfBedrijfIsActiefById', ['error' => $e->getMessage()]);
               // leeg terug geven zodat de app niet kapot gaat
               return false;
          }
     }

     // Nieuwe levering aanmaken via de stored procedure SP_CreateLevering
     static public function SP_CreateLevering($data)
     {
          try {
               // loggen dat het proces gestart is en welke data meegegeven is
               Log::info('SP_CreateLevering gestart', ['data' => $data]);

               // de stored procedure uitvoeren met de juiste parameters
               $result = DB::statement('CALL SP_CreateLevering(?, ?, ?)', [
                    $data['leverancier_id'],        // id van de leverancier
                    $data['leverdatum_tijd'],       // datum en tijd van de levering
                    $data['eerstvolgende_levering'] // datum van eerstvolgende levering
               ]);

               // loggen dat het succesvol uitgevoerd is
               Log::info('SP_CreateLevering succesvol uitgevoerd');

               // resultaat teruggeven
               return $result;

          } catch (\Throwable $e) {
               // loggen dat er een fout is opgetreden, inclusief foutmelding
               Log::error('Fout in SP_CreateLevering', ['error' => $e->getMessage()]);

               // false teruggeven zodat de app niet crasht
               return false;
          }
     }

     // Bestaande leverancier bijwerken via SP_UpdateLeverancier
     static public function SP_UpdateLeverancier($data)
     {
          try {
               // log begin update met meegegeven data
               Log::info('SP_UpdateLeverancier gestart', ['data' => $data]);

               // de stored procedure uitvoeren met id en nieuwe bedrijfsnaam
               $result = DB::statement('CALL SP_UpdateLeverancier(?, ?)', [
                    $data['id'],             // id van de leverancier
                    $data['bedrijfsnaam']    // nieuwe bedrijfsnaam
               ]);

               // loggen dat update succesvol is uitgevoerd
               Log::info('SP_UpdateLeverancier succesvol uitgevoerd');

               // resultaat teruggeven
               return $result;

          } catch (\Throwable $e) {
               // loggen van foutmelding
               Log::error('Fout in SP_UpdateLeverancier', ['error' => $e->getMessage()]);

               // false teruggeven zodat de app niet crasht
               return false;
          }
     }

     // Leverancier ophalen via id met SP_GetLeverancierById
     static public function SP_GetLeverancierById($id)
     {
          try {
               // log begin ophalen leverancier
               Log::info('SP_GetLeverancierById gestart', ['id' => $id]);

               // stored procedure uitvoeren
               $result = DB::select('CALL SP_GetLeverancierById(?)', [$id]);

               // log succes
               Log::info('SP_GetLeverancierById succesvol uitgevoerd');

               // eerste record teruggeven of null als leeg
               return $result[0] ?? null;

          } catch (\Throwable $e) {
               // log foutmelding
               Log::error('Fout in SP_GetLeverancierById', ['error' => $e->getMessage()]);

               // null teruggeven bij fout
               return null;
          }
     }

     // Levering ophalen via id met SP_GetLeveringById
     static public function SP_GetLeveringById($id)
     {
          try {
               // log begin ophalen levering
               Log::info('SP_GetLeveringById gestart', ['id' => $id]);

               // stored procedure uitvoeren
               $result = DB::select('CALL SP_GetLeveringById(?)', [$id]);

               // log succes
               Log::info('SP_GetLeveringById succesvol uitgevoerd');

               // eerste record teruggeven of null als leeg
               return $result[0] ?? null;

          } catch (\Throwable $e) {
               // log foutmelding
               Log::error('Fout in SP_GetLeveringById', ['error' => $e->getMessage()]);

               // null teruggeven bij fout
               return null;
          }
     }

     // Bestaande levering updaten via SP_UpdateLevering
     static public function SP_UpdateLevering($id, $data)
     {
          try {
               // log begin update levering met id en meegegeven data
               Log::info('SP_UpdateLevering gestart', ['id' => $id, 'data' => $data]);

               // stored procedure uitvoeren met alle benodigde parameters
               $result = DB::statement('CALL SP_UpdateLevering(?, ?, ?, ?)', [
                    $id,                            // id van de levering
                    $data['leverdatum_tijd'],       // nieuwe leverdatum & tijd
                    $data['eerstvolgende_levering'],// nieuwe eerstvolgende levering datum
                    $data['leverancier_id']         // id van de leverancier
               ]);

               // log succes
               Log::info('SP_UpdateLevering succesvol uitgevoerd');

               // resultaat teruggeven
               return $result;

          } catch (\Throwable $e) {
               // log foutmelding
               Log::error('Fout in SP_UpdateLevering', ['error' => $e->getMessage()]);

               // false teruggeven bij fout
               return false;
          }
     }
}