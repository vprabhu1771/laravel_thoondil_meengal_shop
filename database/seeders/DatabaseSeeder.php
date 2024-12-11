<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
        ]);

        User::factory()->create([
            'name' => 'Thanikachalam',
            'email' => 'morning@gmail.com',
            'password' => Hash::make('admin'),
        ]);

        User::factory()->create([
            'name' => 'Niranjan',
            'email' => 'afternoon@gmail.com',
            'password' => Hash::make('admin'),
        ]);

        User::factory()->create([
            'name' => 'Rithika',
            'email' => 'evening@gmail.com',
            'password' => Hash::make('admin'),
        ]);
    

    $products = [
        [
            'name' => 'பாறை மீன் வறுவல்',
            'price' => 60,
            'unit' => '1'
            
        ],
        [
            'name' => 'வஞ்ஐரம் மீன் வறுவல்',
            'price' => 150,
            'unit' => '1'
        ],
        [
            'name' => 'இறால் 65',
            'price' => 100,
            'unit' => '100'
        ],
        [
            'name' => 'நெத்திலி 65 ',
            'price' => 70,
            'unit' => '100'
        ],
        [
            'name' => 'இறால் பிரை',
            'price' => 80,
            'unit' => '100'
        ],
        [
            'name' => 'நெத்திலி பிரை ',
            'price' => 60,
            'unit' => '100'
        ],
        [
            'name' => 'இறால்/கனவா தொக்கு',
            'price' => 60,
            'unit' => '1'
        ],
        [
            'name' => 'இறால்/கனவா தோசை',
            'price' => 30,
            'unit' => '1'
        ],
        [
            'name' => 'தோசை & மீன் குழம்பு',
            'price' => 10,
            'unit' => '1'
        ],
        [
            'name' => 'இட்லி(2) மீன் குழம்பு',
            'price' => 15,
            'unit' => '1'
        ],
        [
            'name' => 'சப்பாத்தி ',
            'price' => 15,
            'unit' => '1'
        ],
        [
            'name' => 'நண்டு சூப்',
            'price' => 40,
            'unit' => '1'
        ],
        [
            'name' => 'பார்சல் நண்டு சூப் ',
            'price' => 50,
            'unit' => '1'
        ],
        [
            'name' => 'மீன் குழம்பு ',
            'price' => 40,
            'unit' => '1'
        ],
        [
            'name' => 'நண்டு கிரெவி',
            'price' => 50,
            'unit' => '1'
        ],
       
    ];
        foreach ($products as $row){
            Product::create($row);
        }
    }
}
