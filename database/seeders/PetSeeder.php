<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\User;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the owners
        $owners = User::where('role', 'owner')->get();

    
        // registros de mascotas sueltas, fuera del bucle
        Pet::create([
            'owner_id' => $owners->first()->id, // Assign to the first owner
            'name' => 'Seymour',
            'type' => 'dog',
            'breed' => 'mixed',
            'date_of_birth' => '2015-11-10',
            'weight' => 12.3,
        ]);

        Pet::create([
            'owner_id' => $owners->last()->id, // Assign to the last owner
            'name' => 'Tara',
            'type' => 'cat',
            'breed' => 'tressym',
            'date_of_birth' => '2022-02-14',
            'weight' => 3.6,
        ]);

        Pet::create([
            'owner_id' => 6,
            'name' => 'Salem',
            'type' => 'cat',
            'breed' => 'bombay',
            'date_of_birth' => '2020-09-30',
            'weight' => 4.1,
        ]);

        Pet::create([
            'owner_id' => 7,
            'name' => 'Nevado',
            'type' => 'dog',
            'breed' => 'bichon maltes',
            'date_of_birth' => '2019-07-15',
            'weight' => 7.7,
        ]);

        Pet::create([
            'owner_id' => 8,
            'name' => 'Jonesy',
            'type' => 'cat',
            'breed' => 'tabby',
            'date_of_birth' => '2018-03-30',
            'weight' => 5.4,
        ]);

        Pet::create([
            'owner_id' => 9,
            'name' => 'Chop',
            'type' => 'dog',
            'breed' => 'rotweiller',
            'date_of_birth' => '2016-10-27',
            'weight' => 34.5,
        ]);
        
        Pet::create([
            'owner_id' => 10,
            'name' => 'Pakkun',
            'type' => 'dog',
            'breed' => 'pug',
            'date_of_birth' => '2012-10-27',
            'weight' => 3.5,
        ]);

        Pet::create([
            'owner_id' => 10,
            'name' => 'Buru',
            'type' => 'dog',
            'breed' => 'bulldog ingles',
            'date_of_birth' => '2015-06-01',
            'weight' => 7.8,
        ]);
        
        Pet::create([
            'owner_id' => 11,
            'name' => 'Shushu',
            'type' => 'dog',
            'breed' => 'mixed',
            'date_of_birth' => '2019-08-09',
            'weight' => 5.5,
        ]);
        Pet::create([
            'owner_id' => 12,
            'name' => 'Ein',
            'type' => 'dog',
            'breed' => 'corgy',
            'date_of_birth' => '2017-02-01',
            'weight' => 6.5,
        ]);
        Pet::create([
            'owner_id' => 13,
            'name' => 'Jiji',
            'type' => 'cat',
            'breed' => 'bombay',
            'date_of_birth' => '2021-08-16',
            'weight' => 2.9,
        ]);
    }
}
