<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class leveranciersModel extends Model
{    
     // All leveranciers ophalen (actief + niet-actief) voor overzicht
     static public function GetAllLeveranciers()
     {
          try {
               Log::info('GetAllLeveranciers gestart');

               $result = DB::select(
                    'SELECT 
                         lvrn.id,
                         lvrn.bedrijfsnaam,
                         lvrn.is_actief,
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
                    ORDER BY lvrn.is_actief DESC, lvrn.bedrijfsnaam ASC'
               );

               Log::info('GetAllLeveranciers succesvol uitgevoerd');
               return $result;
          } catch (\Throwable $e) {
               Log::error('Fout in GetAllLeveranciers', ['error' => $e->getMessage()]);
               return [];
          }
     }

     // Alle leveringen ophalen (actief + niet-actief) voor overzicht
     static public function GetAllLeveringen()
     {
          try {
               Log::info('GetAllLeveringen gestart');

               $result = DB::select(
                    'SELECT 
                         lvng.id,
                         lvng.is_actief,
                         lvrn.bedrijfsnaam,
                         cprs.contact_naam,
                         lvng.eerstvolgende_levering
                     FROM leveringen lvng
                     INNER JOIN leveranciers lvrn 
                         ON lvng.leverancier_id = lvrn.id
                     INNER JOIN contactpersonen cprs 
                         ON lvrn.contactpersoon_id = cprs.id
                     ORDER BY lvng.is_actief DESC, lvng.eerstvolgende_levering ASC'
               );

               Log::info('GetAllLeveringen succesvol uitgevoerd');
               return $result;
          } catch (\Throwable $e) {
               Log::error('Fout in GetAllLeveringen', ['error' => $e->getMessage()]);
               return [];
          }
     }

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

               // Merge-friendly safety: different classmates may have different SP definitions in MySQL.
               // If the SP output doesn't match what the UI expects, fall back to the known-good SELECT.
               if (!empty($result)) {
                    $row = $result[0];
                    $hasExpectedShape = isset($row->id) && isset($row->bedrijfsnaam);

                    if ($hasExpectedShape) {
                         // Validate that the returned id actually matches the returned bedrijfsnaam
                         // in the leveranciers table. If not, the SP is likely outdated/wrong.
                         try {
                              $dbRow = DB::table('leveranciers')
                                   ->select(['bedrijfsnaam'])
                                   ->where('id', (int) $row->id)
                                   ->first();

                              if (!$dbRow || (string) $dbRow->bedrijfsnaam !== (string) $row->bedrijfsnaam) {
                                   Log::warning('SP_GetAllLeveranciers output mismatch; using fallback SELECT', [
                                        'sp_id' => $row->id ?? null,
                                        'sp_bedrijfsnaam' => $row->bedrijfsnaam ?? null,
                                        'db_bedrijfsnaam' => $dbRow->bedrijfsnaam ?? null,
                                   ]);
                                   return self::GetActiveLeveranciers();
                              }
                         } catch (\Throwable $e) {
                              // If this validation query fails, still keep UI usable.
                              Log::warning('SP_GetAllLeveranciers validation failed; using fallback SELECT', [
                                   'error' => $e->getMessage(),
                              ]);
                              return self::GetActiveLeveranciers();
                         }
                    } else {
                         Log::warning('SP_GetAllLeveranciers missing expected columns; using fallback SELECT');
                         return self::GetActiveLeveranciers();
                    }
               }

               // log dat het succesvol was
               Log::info('SP_GetAllLeveranciers succesvol uitgevoerd');
               // $result terug geven
               return $result;
          } catch (\Throwable $e) {
               //log dat het fout is gegaan
               Log::error('Fout in SP_GetAllLeveranciers', ['error' => $e->getMessage()]);
               //lege array terug geven zodat de app niet kapot gaat
               return self::GetActiveLeveranciers();
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

               // Merge-friendly safety: SP output can differ; ensure id exists for edit/delete buttons.
               if (!empty($result)) {
                    $hasValidIds = true;
                    foreach ($result as $row) {
                         if (!isset($row->id)) {
                              $hasValidIds = false;
                              break;
                         }
                         // Validate that levering id exists in leveringen table
                         $exists = DB::table('leveringen')->where('id', (int) $row->id)->exists();
                         if (!$exists) {
                              $hasValidIds = false;
                              break;
                         }
                    }

                    if (!$hasValidIds) {
                         Log::warning('SP_GetAllLeveringen returned invalid/missing ids; using fallback SELECT');
                         $result = DB::select(
                              'SELECT 
                                   lvng.id,
                                   lvng.is_actief,
                                   lvrn.bedrijfsnaam,
                                   cprs.contact_naam,
                                   lvng.eerstvolgende_levering
                               FROM leveringen lvng
                               INNER JOIN leveranciers lvrn 
                                   ON lvng.leverancier_id = lvrn.id
                               INNER JOIN contactpersonen cprs 
                                   ON lvrn.contactpersoon_id = cprs.id
                               WHERE lvng.is_actief = 1'
                         );
                    }
               }

               // log dat het goed is gegaan
               Log::info('SP_GetAllLeveringen succesvol uitgevoerd');
               // $result teruggeven
               return $result;
          } catch (\Throwable $e) {
               //log dat het fout is gegaan en waar precies en wat er fout is gegaan
               Log::error('Fout in SP_GetAllLeveringen', ['error' => $e->getMessage()]);
               // lege array terug geven zodat de app niet kapot gaat
               try {
                    // Fallback SELECT so the page can still render without SPs.
                    return DB::select(
                         'SELECT 
                              lvng.id,
                              lvng.is_actief,
                              lvrn.bedrijfsnaam,
                              cprs.contact_naam,
                              lvng.eerstvolgende_levering
                          FROM leveringen lvng
                          INNER JOIN leveranciers lvrn 
                              ON lvng.leverancier_id = lvrn.id
                          INNER JOIN contactpersonen cprs 
                              ON lvrn.contactpersoon_id = cprs.id
                          WHERE lvng.is_actief = 1'
                    );
               } catch (\Throwable $e2) {
                    Log::error('Fallback SELECT for leveringen failed', ['error' => $e2->getMessage()]);
                    return [];
               }
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
               // Only affect active rows; makes "already deleted" return 0 reliably.
               $result = DB::update('UPDATE leveranciers SET is_actief = 0 WHERE id = ? AND is_actief = 1', [$id]);
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
               // Merge-friendly fallback: if stored procedure doesn't exist (1305), check the table directly.
               if (isset($e->errorInfo) && is_array($e->errorInfo) && (int)($e->errorInfo[1] ?? 0) === 1305) {
                    try {
                         $row = DB::table('leveranciers')
                              ->select(['is_actief'])
                              ->where('id', (int) $id)
                              ->first();
                         return (bool) ($row->is_actief ?? 0);
                    } catch (\Throwable $e2) {
                         Log::error('Fallback SELECT is_actief failed', ['error' => $e2->getMessage()]);
                         return false;
                    }
               }

               // default: false so app doesn't crash
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

               // #region agent log
               @file_put_contents(
                    base_path('.cursor/debug.log'),
                    json_encode([
                         'sessionId' => 'debug-session',
                         'runId' => 'pre-fix',
                         'hypothesisId' => 'H4',
                         'location' => 'leveranciersModel.php:SP_UpdateLeverancier:afterStatement',
                         'message' => 'SP_UpdateLeverancier DB::statement returned',
                         'data' => [
                              'id' => (int) ($data['id'] ?? 0),
                              'result' => (bool) $result,
                         ],
                         'timestamp' => (int) (microtime(true) * 1000),
                    ]).PHP_EOL,
                    FILE_APPEND
               );
               // #endregion

               // loggen dat update succesvol is uitgevoerd
               Log::info('SP_UpdateLeverancier succesvol uitgevoerd');

               // resultaat teruggeven
               return $result;

          } catch (\Throwable $e) {
               // loggen van foutmelding
               Log::error('Fout in SP_UpdateLeverancier', ['error' => $e->getMessage()]);

               // Merge-friendly fallback: some DBs don't have the stored procedure.
               // If MySQL error 1305 (procedure does not exist), fall back to a direct UPDATE.
               if (isset($e->errorInfo) && is_array($e->errorInfo) && (int)($e->errorInfo[1] ?? 0) === 1305) {
                    try {
                         $affected = DB::update(
                              'UPDATE leveranciers SET bedrijfsnaam = ? WHERE id = ?',
                              [(string) $data['bedrijfsnaam'], (int) $data['id']]
                         );
                         return $affected > 0;
                    } catch (\Throwable $e2) {
                         Log::error('Fallback UPDATE leveranciers failed', ['error' => $e2->getMessage()]);
                         return false;
                    }
               }

               // #region agent log
               @file_put_contents(
                    base_path('.cursor/debug.log'),
                    json_encode([
                         'sessionId' => 'debug-session',
                         'runId' => 'pre-fix',
                         'hypothesisId' => 'H4',
                         'location' => 'leveranciersModel.php:SP_UpdateLeverancier:exception',
                         'message' => 'Exception in SP_UpdateLeverancier',
                         'data' => [
                              'id' => (int) ($data['id'] ?? 0),
                              'error' => $e->getMessage(),
                         ],
                         'timestamp' => (int) (microtime(true) * 1000),
                    ]).PHP_EOL,
                    FILE_APPEND
               );
               // #endregion

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
               if (!empty($result)) {
                    return $result[0];
               }

               // Fallback: direct query (merge-friendly if SP is missing/outdated)
               $fallback = DB::select(
                    'SELECT id, bedrijfsnaam
                     FROM leveranciers
                     WHERE id = ?
                     LIMIT 1',
                    [(int) $id]
               );

               return $fallback[0] ?? null;

          } catch (\Throwable $e) {
               // log foutmelding
               Log::error('Fout in SP_GetLeverancierById', ['error' => $e->getMessage()]);

               try {
                    $fallback = DB::select(
                         'SELECT id, bedrijfsnaam
                          FROM leveranciers
                          WHERE id = ?
                          LIMIT 1',
                         [(int) $id]
                    );
                    return $fallback[0] ?? null;
               } catch (\Throwable $e2) {
                    Log::error('Fallback SELECT for leverancier by id failed', ['error' => $e2->getMessage()]);
                    return null;
               }
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

               // #region agent log
               @file_put_contents(
                    base_path('.cursor/debug.log'),
                    json_encode([
                         'sessionId' => 'debug-session',
                         'runId' => 'pre-fix',
                         'hypothesisId' => 'H6',
                         'location' => 'leveranciersModel.php:SP_UpdateLevering:afterStatement',
                         'message' => 'SP_UpdateLevering DB::statement returned',
                         'data' => [
                              'leveringId' => (int) $id,
                              'leverancierId' => (int) ($data['leverancier_id'] ?? 0),
                              'result' => (bool) $result,
                         ],
                         'timestamp' => (int) (microtime(true) * 1000),
                    ]).PHP_EOL,
                    FILE_APPEND
               );
               // #endregion

               // log succes
               Log::info('SP_UpdateLevering succesvol uitgevoerd');

               // resultaat teruggeven
               return $result;

          } catch (\Throwable $e) {
               // log foutmelding
               Log::error('Fout in SP_UpdateLevering', ['error' => $e->getMessage()]);

               // #region agent log
               @file_put_contents(
                    base_path('.cursor/debug.log'),
                    json_encode([
                         'sessionId' => 'debug-session',
                         'runId' => 'pre-fix',
                         'hypothesisId' => 'H6',
                         'location' => 'leveranciersModel.php:SP_UpdateLevering:exception',
                         'message' => 'Exception in SP_UpdateLevering',
                         'data' => [
                              'leveringId' => (int) $id,
                              'error' => $e->getMessage(),
                         ],
                         'timestamp' => (int) (microtime(true) * 1000),
                    ]).PHP_EOL,
                    FILE_APPEND
               );
               // #endregion

               // false teruggeven bij fout
               return false;
          }
     }
}