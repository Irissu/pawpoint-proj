<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'surname' => 'User',
            'email' => 'admin@pawpoint.com',
            'password' => Hash::make('password'),
            'phone' => '695837655',
            'address' => null,
            'bio' => null,
            'role' => 'admin',
            'img_path' => null,
        ]);

        // create 3 vets
        $veterinarians = [
            [
                'name' => 'Kenzo',
                'surname' => 'Tenma',
                'email' => 'tenma@pawpoint.com',
                'phone' => '678123456',
            ],
            [
                'name' => 'Beth',
                'surname' => 'Smith',
                'email' => 'beth.smith@pawpoint.com',
                'phone' => '679234567',
            ],
            [
                'name' => 'Gregory',
                'surname' => 'House',
                'email' => 'house@pawpoint.com',
                'phone' => '681345678',
            ],
        ];

        foreach ($veterinarians as $vet) {
            User::create([
                'name' => $vet['name'],
                'surname' => $vet['surname'],
                'email' => $vet['email'],
                'password' => Hash::make('password'),
                'phone' => $vet['phone'],
                'address' => null,
                'bio' => 'Experienced veterinarian.',
                'role' => 'vet',
                'img_path' => null,
            ]);
        }


        // Create 5 owners and their pets
        $owners = [
            [
                'name' => 'Philip',
                'surname' => 'J.Fray',
                'email' => 'fray@pawpoint.com',
                'phone' => '682456789',
            ],
            [
                'name' => 'Sabrina',
                'surname' => 'Spellman',
                'email' => 'sabrina@pawpoint.com',
                'phone' => '683567890',
                'address' => 'Greendale',
            ],
            [
                'name' => 'Shinosuke',
                'surname' => 'Nohara',
                'email' => 'shinosuke@pawpoint.com',
                'phone' => '687901234',
                'address' => 'Kasukabe',
            ],
            [
                'name' => 'Ellen',
                'surname' => 'Ripley',
                'email' => 'ripley@pawpoint.com',
                'phone' => '684678901',
                'address' => 'Nostromo',
            ],
            [
                'name' => 'Franklin',
                'surname' => 'Clinton',
                'email' => 'franklin@pawpoint.com',
                'phone' => '685789012',
                'address' => 'Grove Street',
            ],
            [
                'name' => 'Kakashi',
                'surname' => 'Hatake',
                'email' => 'kakashi@pawpoint.com',
                'phone' => '684489012',
                'address' => 'Konoha',
            ],
            [
                'name' => 'Boogie',
                'surname' => 'Orange',
                'email' => 'boogie@pawpoint.com',
                'phone' => '600744012',
                'address' => 'Orange Island',
            ],
            [
                'name' => 'Edward',
                'surname' => 'Wong Hau',
                'email' => 'edward@pawpoint.com',
                'phone' => '610567012',
                'address' => 'Mars',
            ],
            [
                'name' => 'Kiki',
                'surname' => 'Miyazaki',
                'email' => 'kikithewizard@pawpoint.com',
                'phone' => '690488392',
            ],
            [
                'name' => 'Gale',
                'surname' => 'Dekarios',
                'email' => 'gale@pawpoint.com',
                'phone' => '686890123',
                'address' => 'Waterdeep',
            ],
        ];

        foreach ($owners as $owner) {
            $ownerModel = User::create([
                'name' => $owner['name'],
                'surname' => $owner['surname'],
                'email' => $owner['email'],
                'password' => Hash::make('password'),
                'phone' => $owner['phone'],
                'address' => isset($owner['address']) ? $owner['address'] : 'Calle Falsa 123',
                'bio' => null,
                'role' => 'owner',
                'img_path' => null,
            ]);

        }


    }
}
