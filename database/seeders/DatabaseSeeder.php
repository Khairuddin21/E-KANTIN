<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@ecanteen.com'],
            ['name' => 'Super Admin', 'password' => Hash::make('password'), 'role' => 'super_admin']
        );

        // Seller
        $seller = User::firstOrCreate(
            ['email' => 'seller@ecanteen.com'],
            ['name' => 'Kantin Ibu Sari', 'password' => Hash::make('password'), 'role' => 'seller']
        );

        // Student
        User::firstOrCreate(
            ['email' => 'siswa@ecanteen.com'],
            ['name' => 'Ahmad Siswa', 'password' => Hash::make('password'), 'role' => 'student', 'balance' => 50000]
        );

        // Sample menus
        $menus = [
            ['name' => 'Nasi Goreng Spesial', 'description' => 'Nasi goreng dengan telur, ayam, dan sayuran segar', 'price' => 15000, 'category' => 'makanan'],
            ['name' => 'Mie Ayam Bakso', 'description' => 'Mie ayam dengan bakso sapi dan pangsit goreng', 'price' => 12000, 'category' => 'makanan'],
            ['name' => 'Ayam Geprek', 'description' => 'Ayam crispy dengan sambal geprek level 1-5', 'price' => 13000, 'category' => 'makanan'],
            ['name' => 'Nasi Uduk Komplit', 'description' => 'Nasi uduk dengan lauk ayam, tempe, dan sambal', 'price' => 10000, 'category' => 'makanan'],
            ['name' => 'Soto Ayam', 'description' => 'Soto ayam kampung dengan nasi dan kerupuk', 'price' => 12000, 'category' => 'makanan'],
            ['name' => 'Es Teh Manis', 'description' => 'Teh manis dingin segar', 'price' => 3000, 'category' => 'minuman'],
            ['name' => 'Jus Jeruk', 'description' => 'Jus jeruk segar tanpa pengawet', 'price' => 7000, 'category' => 'minuman'],
            ['name' => 'Es Campur', 'description' => 'Es campur dengan buah segar dan sirup', 'price' => 8000, 'category' => 'minuman'],
            ['name' => 'Risol Mayo', 'description' => 'Risol isi mayo, smoked beef, dan sayuran', 'price' => 5000, 'category' => 'snack'],
            ['name' => 'Pisang Goreng Coklat', 'description' => 'Pisang goreng crispy dengan topping coklat', 'price' => 5000, 'category' => 'snack'],
        ];

        foreach ($menus as $menu) {
            Menu::firstOrCreate(
                ['name' => $menu['name']],
                array_merge($menu, ['seller_id' => $seller->id])
            );
        }
    }
}
