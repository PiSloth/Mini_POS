<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@nexgen.com',
        // ]);

        DB::table('invoice_statuses')->insert([
            'name' => 'New'
        ]);
        DB::table('invoice_statuses')->insert([
            'name' => 'Confirm'
        ]);
        DB::table('invoice_statuses')->insert([
            'name' => 'COD'
        ]);
        DB::table('invoice_statuses')->insert([
            'name' => 'Paid'
        ]);
        DB::table('invoice_statuses')->insert([
            'name' => 'Canceled'
        ]);
    }
}
