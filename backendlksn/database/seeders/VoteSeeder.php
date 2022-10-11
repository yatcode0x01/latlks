<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vote;
class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vote::create([
            'user_id' => 1,
            'division_id' => 1,
            'poll_id' => 1,
            'choice_id' => 2,
        ]);
    }
}
