<?php

namespace Database\Seeders;

use App\Models\ApiConnection;
use Illuminate\Database\Seeder;

class ApiConnectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApiConnection::updateOrCreate(
            ['name' => '1DG API'],
            [
                'api_url' => 'https://1dg.me/api/v2',
                'api_key' => '97865********', // IMPORTANT: Replace with your actual API key
                'is_active' => true,
            ]
        );
    }
}
