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
}
