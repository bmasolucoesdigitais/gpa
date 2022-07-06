<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('companies')->insert([
            'id' => 1,
            'name' => 'Geral',
            'address' => 'R. General Glicério',
            'number' => '45',
            'neighborhood' => 'Centro',
            'citie' => 'Santo André',
            'state' => 'SP',
            'country' => 'Brasil',
            'cnpj' => '00000000000000',
            'fl_aprove' => 1,
            'fl_active' => 1,
            'fl_billing' => 1,
            'company_id' => 1,
            'company_id' => 1,
            'fl_deleted' => 0
        ]);
    }
}
