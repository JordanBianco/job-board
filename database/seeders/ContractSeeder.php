<?php

namespace Database\Seeders;

use App\Models\Contract;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contractTypes = [
            'Contratto indeterminato',
            'Contratto determinato',
            'Contratto giornaliero',
            'Tirocinio',
            'Stage'
        ];

        foreach ($contractTypes as $type) {
            Contract::factory()->create([
                'name' => $type,
                'slug' => Str::slug($type)
            ]);
        }
    }
}
