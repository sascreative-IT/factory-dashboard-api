<?php

namespace Database\Seeders;

use App\Models\EmbellishmentSupplier;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SuplierAndEmbellishmentSuplier extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supliers = [
            ['name' => 'BMV Clothing'],
            ['name' => 'Banbury Clothing (Aurora)'],
            ['name' => 'Jb Wear'],
            ['name' => 'Legend Life'],
            ['name' => 'SAS warehouse Stock']
        ];
        Supplier::insert($supliers);

        $embellishmentSuppliers = [
            ['name' => 'Premier Logos'],
            ['name' => 'SAS warehouse'],
        ];

        EmbellishmentSupplier::insert($embellishmentSuppliers);
    }
}
