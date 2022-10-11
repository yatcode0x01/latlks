<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Choice;

class ChoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Choice::create([
            'choice' => 'Ayam Bakar',
            'poll_id' => 1
        ]);

        Choice::create([
            'choice' => 'Rendang',
            'poll_id' => 1
        ]);

        Choice::create([
            'choice' => 'Soto Babad',
            'poll_id' => 1
        ]);

        Choice::create([
            'choice' => 'Gudeg',
            'poll_id' => 1
        ]);
    }
}
