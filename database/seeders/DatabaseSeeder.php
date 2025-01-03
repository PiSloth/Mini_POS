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

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@nexgen.com',
        ]);

        $invoiceStatus = [
            'new',
            'confirmed',
            'cancled'
        ];

        $paymentMethod = [
            'cash',
            'AYA 1060',
            'KBZ 340'
        ];
        foreach ($invoiceStatus as $item) {
            DB::table('invoice_statuses')->insert([
                'name' => $item
            ]);
        }

        foreach ($paymentMethod as $item) {
            DB::table('payment_methods')->insert([
                'name' => $item,
            ]);
        }
    }
}
