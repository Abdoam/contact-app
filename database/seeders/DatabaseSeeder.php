<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Contact;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::factory(3)->has(
            Company::factory(3)->hasContacts(3, function (array $attributes, Company $company) {
                return ['user_id' => $company->user_id];
            })
        )->create();
        // Contact::factory()->count(100)->create();
        //$this->call([CompanySeeder::class, ContactSeeder::class]);
        //$this->call(CompaniesTableSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
