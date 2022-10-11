<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Poll;
use Carbon\Carbon;

class PollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Poll::create([
            'title' => 'Apa nama makanan khas dari Minang Padang?',
            'description' => 'Pilih Salah Satu Dari Opsi Berikut',
            'deadline' => Carbon::parse('22-09-2022'),
            'created_by' => 1
        ]);
    }
}
