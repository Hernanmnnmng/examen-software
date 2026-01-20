<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VoedselpakketModel;

class VoedselpakketController extends Controller
{
    //
    public function index(){
        $klanten = VoedselpakketModel::getallklanten();
        $voedselpakketten = VoedselpakketModel::getallvoedselpakketten();

        return view('voedselpakketten.index', compact('klanten', 'voedselpakketten'));
    }

    public function getproducten($id){
        $producten = VoedselpakketModel::getallproducten($id);
        return response()->json($producten);
    }

    public function show($voedselpakketid){
        $voedselpakket = VoedselpakketModel::getvoedselpakketbyid($voedselpakketid);
        return view('voedselpakketten.show', compact('voedselpakket'));
    }

    public function edit($voedselpakketid){
        $voedselpakket = VoedselpakketModel::getvoedselpakketbyid($voedselpakketid);
        if(empty($voedselpakket)){
             return redirect()->route('voedselpakketten.index')->with('error', 'Pakket niet gevonden.');
        }
        $producten = VoedselpakketModel::getvoedselpakketproducten($voedselpakketid);
        return view('voedselpakketten.edit', compact('voedselpakket', 'producten'));
    }


    public function store(Request $request){
        $validatedData = $request->validate([
            'klant_id' => 'required|integer',
            'producten' => 'required|array',
            'producten.*.product_id' => 'required|integer',
            'producten.*.aantal' => 'required|integer|min:1',
        ]);

        // Generate Packagenumber
        $pakketnummer = uniqid(); // Simple unique ID fitting in 12 chars if truncated, but uniqid is 13 chars.
        // Let's use a shorter random string or timestamp.
        // Char(12). format: VP-12345678 (11 chars)
        $pakketnummer = 'VP-' . rand(10000000, 99999999);

        // Prepare data for Model
        $modelData = [
            'klantid' => $validatedData['klant_id'],
            'pakketnmr' => $pakketnummer
        ];

        // Create Package
        $result = VoedselpakketModel::createvoedselpakket($modelData);

        // Retrieve the created package ID to attach products
        // We assume success if result is not -1 or exception
        if($result != -1){
            // Fetch ID by pakketnummer since SP doesn't return it
            $pakket = \Illuminate\Support\Facades\DB::table('voedselpakketten')
                        ->where('pakketnummer', $pakketnummer)
                        ->first();

            if($pakket){
                foreach($validatedData['producten'] as $prod){
                    VoedselpakketModel::createvoedselpakketproduct([
                        'voedselpakketid' => $pakket->id,
                        'productid' => $prod['product_id'],
                        'aantal' => $prod['aantal']
                    ]);
                }

                return redirect()->route('voedselpakketten.index')->with('success', 'Voedselpakket en producten succesvol aangemaakt.');
            }
        }

        return redirect()->route('voedselpakketten.index')->with('error', 'Fout bij het aanmaken van het voedselpakket.');
    }

    public function destroy($voedselpakketid){
        $result = VoedselpakketModel::deletevoedselpakket($voedselpakketid);

        if($result){
            return redirect()->route('voedselpakketten.index')->with('success', 'Voedselpakket succesvol verwijderd.');
        } else {
            return redirect()->route('voedselpakketten.index')->with('error', 'Fout bij het verwijderen van het voedselpakket.');
        }

    }

    public function update(Request $request, $voedselpakketid){
        $validatedData = $request->validate([
            'klant_id' => 'required|integer',
            'producten' => 'required|array',
            'producten.*.product_id' => 'required|integer',
            'producten.*.aantal' => 'required|integer|min:1',
        ]);

        // Get Old Products
        $oldProductsRaw = VoedselpakketModel::getvoedselpakketproducten($voedselpakketid);
        $oldProducts = [];
        foreach($oldProductsRaw as $p){
            $oldProducts[$p->product_id] = $p->aantal;
        }
        
        // Process New Products
        foreach($validatedData['producten'] as $newProd){
            $pid = $newProd['product_id'];
            $qty = $newProd['aantal'];
            
            if(isset($oldProducts[$pid])){
                // Update
                $oldQty = $oldProducts[$pid];
                $diff = $qty - $oldQty;
                
                if($diff != 0){
                    VoedselpakketModel::updatevoedselpakketproduct($voedselpakketid, [
                        'productid' => $pid,
                        'aantal' => $qty,
                        'verschil' => $diff
                    ]);
                }
                unset($oldProducts[$pid]);
            } else {
                // Create
                VoedselpakketModel::createvoedselpakketproduct([
                    'voedselpakketid' => $voedselpakketid,
                    'productid' => $pid,
                    'aantal' => $qty
                ]);
            }
        }
        
        // Process Removed Products
        foreach($oldProducts as $pid => $oldQty){
            VoedselpakketModel::updatevoedselpakketproduct($voedselpakketid, [
                'productid' => $pid,
                'aantal' => 0,
                'verschil' => 0 
            ]);
        }
        
        return redirect()->route('voedselpakketten.index')->with('success', 'Voedselpakket succesvol bijgewerkt.');
    }
}
