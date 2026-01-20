<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class leveranciersModel extends Model
{
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
}
