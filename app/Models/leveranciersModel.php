<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class leveranciersModel extends Model
{
     static public function GetActiveLeveranciers()
     {
          return DB::select(
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
     }

     static public function SP_GetAllLeveranciers()
     {
          return DB::select('call SP_GetAllLeveranciers()');
     }

     static public function SP_GetAllLeveringen()
     {
          return DB::select('call SP_GetAllLeveringen()');
     }

     static public function SP_GetLeverancierByBedrijfsnaam($name)
     {
          $result = DB::select('call SP_GetLeverancierByBedrijfsnaam(?)', [$name]);

          return $result;
     }

     static public function SP_CreateNewLeverancier($data)
     {
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

          return $result;
     }

     static public function SoftDeleteLeverancierById(int $id): int
     {
          return DB::update('UPDATE leveranciers SET is_actief = 0 WHERE id = ?', [$id]);
     }
}
