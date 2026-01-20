<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoorraadDemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $categorieen = [
            'AGF (Aardappelen, Groente, Fruit)',
            'Kaas, vleeswaren',
            'Zuivel, plantaardig en eieren',
            'Bakkerij en banket',
            'Frisdrank, sappen, koffie en thee',
            'Pasta, rijst en wereldkeuken',
            'Soepen, sauzen, kruiden en olie',
            'Snoep, koek, chips en chocolade',
            'Baby, verzorging en hygiëne',
        ];

        $categorieIds = [];
        foreach ($categorieen as $naam) {
            DB::table('product_categorieen')->updateOrInsert(
                ['naam' => $naam],
                [
                    'is_actief' => 1,
                    'datum_aangemaakt' => $now,
                    'datum_gewijzigd' => $now,
                ]
            );

            $categorieIds[$naam] = (int) DB::table('product_categorieen')->where('naam', $naam)->value('id');
        }

        $producten = [
            [
                'product_naam' => 'Bananen',
                'ean' => '1234567890123',
                'categorie' => 'AGF (Aardappelen, Groente, Fruit)',
                'aantal_voorraad' => 30,
            ],
            [
                'product_naam' => 'Melk 1L',
                'ean' => '2234567890123',
                'categorie' => 'Zuivel, plantaardig en eieren',
                'aantal_voorraad' => 20,
            ],
            [
                'product_naam' => 'Rijst 1kg',
                'ean' => '3234567890123',
                'categorie' => 'Pasta, rijst en wereldkeuken',
                'aantal_voorraad' => 15,
            ],
            [
                'product_naam' => 'Luiers maat 3',
                'ean' => '4234567890123',
                'categorie' => 'Baby, verzorging en hygiëne',
                'aantal_voorraad' => 12,
            ],
        ];

        foreach ($producten as $p) {
            $categorieId = $categorieIds[$p['categorie']] ?? null;
            if (! $categorieId) {
                continue;
            }

            DB::table('producten')->updateOrInsert(
                ['ean' => $p['ean']],
                [
                    'product_naam' => $p['product_naam'],
                    'categorie_id' => $categorieId,
                    'aantal_voorraad' => $p['aantal_voorraad'],
                    'is_actief' => 1,
                    'datum_aangemaakt' => $now,
                    'datum_gewijzigd' => $now,
                ]
            );
        }
    }
}

