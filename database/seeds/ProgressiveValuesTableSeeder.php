<?php

use Illuminate\Database\Seeder;
use App\Models\Master\ProgressiveValue;

class ProgressiveValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::set('platform', 'Client');

        DB::select('SET FOREIGN_KEY_CHECKS=0;');
        ProgressiveValue::truncate();
        DB::select('SET FOREIGN_KEY_CHECKS=1;');

        ProgressiveValue::insert([
            ["client_id" => 1, "position_id" => 1, "start_fm" => 0, "end_fm" => 9, "rates" => 0, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 10, "end_fm" => 29, "rates" => 210000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 30, "end_fm" => 49, "rates" => 420000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 50, "end_fm" => 69, "rates" => 630000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 70, "end_fm" => 89, "rates" => 840000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 90, "end_fm" => 109, "rates" => 1050000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 110, "end_fm" => 129, "rates" => 1260000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 130, "end_fm" => 149, "rates" => 1470000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 150, "end_fm" => 169, "rates" => 1680000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 170, "end_fm" => 189, "rates" => 1890000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 190, "end_fm" => 209, "rates" => 2100000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 210, "end_fm" => 229, "rates" => 2310000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 230, "end_fm" => 249, "rates" => 2520000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 250, "end_fm" => 269, "rates" => 2730000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 270, "end_fm" => 289, "rates" => 2940000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 290, "end_fm" => 309, "rates" => 3150000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 310, "end_fm" => 329, "rates" => 3360000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 330, "end_fm" => 349, "rates" => 3570000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 350, "end_fm" => 369, "rates" => 3780000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 370, "end_fm" => 389, "rates" => 3990000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 390, "end_fm" => 409, "rates" => 4200000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 410, "end_fm" => 429, "rates" => 4410000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 430, "end_fm" => 449, "rates" => 4620000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 450, "end_fm" => 469, "rates" => 4830000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 1, "start_fm" => 470, "end_fm" => 489, "rates" => 5040000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 0, "end_fm" => 5, "rates" => 0, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 6, "end_fm" => 10, "rates" => 100000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 11, "end_fm" => 15, "rates" => 200000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 16, "end_fm" => 20, "rates" => 300000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 21, "end_fm" => 25, "rates" => 450000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 26, "end_fm" => 30, "rates" => 600000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 31, "end_fm" => 35, "rates" => 800000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 36, "end_fm" => 40, "rates" => 1000000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 41, "end_fm" => 45, "rates" => 1200000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 46, "end_fm" => 50, "rates" => 1400000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 51, "end_fm" => 55, "rates" => 1600000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 56, "end_fm" => 60, "rates" => 1800000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 61, "end_fm" => 65, "rates" => 2000000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 66, "end_fm" => 70, "rates" => 2200000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 71, "end_fm" => 75, "rates" => 2400000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 1, "position_id" => 3, "start_fm" => 76, "end_fm" => 80, "rates" => 2600000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 0, "end_fm" => 9, "rates" => 0, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 10, "end_fm" => 29, "rates" => 210000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 30, "end_fm" => 49, "rates" => 420000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 50, "end_fm" => 69, "rates" => 630000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 70, "end_fm" => 89, "rates" => 840000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 90, "end_fm" => 109, "rates" => 1050000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 110, "end_fm" => 129, "rates" => 1260000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 130, "end_fm" => 149, "rates" => 1470000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 150, "end_fm" => 169, "rates" => 1680000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 170, "end_fm" => 189, "rates" => 1890000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 190, "end_fm" => 209, "rates" => 2100000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 210, "end_fm" => 229, "rates" => 2310000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 230, "end_fm" => 249, "rates" => 2520000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 250, "end_fm" => 269, "rates" => 2730000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 270, "end_fm" => 289, "rates" => 2940000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 290, "end_fm" => 309, "rates" => 3150000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 310, "end_fm" => 329, "rates" => 3360000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 330, "end_fm" => 349, "rates" => 3570000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 350, "end_fm" => 369, "rates" => 3780000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 370, "end_fm" => 389, "rates" => 3990000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 390, "end_fm" => 409, "rates" => 4200000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 410, "end_fm" => 429, "rates" => 4410000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 430, "end_fm" => 449, "rates" => 4620000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 450, "end_fm" => 469, "rates" => 4830000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 11, "start_fm" => 470, "end_fm" => 489, "rates" => 5040000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 0, "end_fm" => 5, "rates" => 0, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 6, "end_fm" => 10, "rates" => 100000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 11, "end_fm" => 15, "rates" => 200000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 16, "end_fm" => 20, "rates" => 300000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 21, "end_fm" => 25, "rates" => 450000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 26, "end_fm" => 30, "rates" => 600000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 31, "end_fm" => 35, "rates" => 800000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 36, "end_fm" => 40, "rates" => 1000000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 41, "end_fm" => 45, "rates" => 1200000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 46, "end_fm" => 50, "rates" => 1400000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 51, "end_fm" => 55, "rates" => 1600000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 56, "end_fm" => 60, "rates" => 1800000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 61, "end_fm" => 65, "rates" => 2000000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 66, "end_fm" => 70, "rates" => 2200000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 71, "end_fm" => 75, "rates" => 2400000, "created_at" => now(), "updated_at" => now()],
            ["client_id" => 2, "position_id" => 13, "start_fm" => 76, "end_fm" => 80, "rates" => 2600000, "created_at" => now(), "updated_at" => now()]
        ]);
    }
}
