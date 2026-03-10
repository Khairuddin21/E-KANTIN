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

        // Sellers
        $seller1 = User::firstOrCreate(
            ['email' => 'seller@ecanteen.com'],
            ['name' => 'Kantin Ibu Sari', 'password' => Hash::make('password'), 'role' => 'seller']
        );

        $seller2 = User::firstOrCreate(
            ['email' => 'seller2@ecanteen.com'],
            ['name' => 'Kantin Pak Budi', 'password' => Hash::make('password'), 'role' => 'seller']
        );

        $seller3 = User::firstOrCreate(
            ['email' => 'seller3@ecanteen.com'],
            ['name' => 'Kantin Bu Dewi', 'password' => Hash::make('password'), 'role' => 'seller']
        );

        $seller4 = User::firstOrCreate(
            ['email' => 'seller4@ecanteen.com'],
            ['name' => 'Kantin Mas Eko', 'password' => Hash::make('password'), 'role' => 'seller']
        );

        // Student
        User::firstOrCreate(
            ['email' => 'siswa@ecanteen.com'],
            ['name' => 'Ahmad Siswa', 'password' => Hash::make('password'), 'role' => 'student', 'balance' => 50000]
        );

        // Menus - Kantin Ibu Sari
        $menus1 = [
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

        foreach ($menus1 as $menu) {
            Menu::firstOrCreate(
                ['name' => $menu['name']],
                array_merge($menu, ['seller_id' => $seller1->id])
            );
        }

        // Menus - Kantin Pak Budi
        $menus2 = [
            ['name' => 'Nasi Padang', 'description' => 'Nasi padang dengan rendang, ayam pop, dan sayur nangka', 'price' => 18000, 'category' => 'makanan'],
            ['name' => 'Bakso Urat Jumbo', 'description' => 'Bakso urat jumbo dengan mie dan tahu goreng', 'price' => 15000, 'category' => 'makanan'],
            ['name' => 'Nasi Kuning', 'description' => 'Nasi kuning komplit dengan lauk pauk', 'price' => 12000, 'category' => 'makanan'],
            ['name' => 'Es Jeruk Peras', 'description' => 'Jeruk peras segar dengan es batu', 'price' => 5000, 'category' => 'minuman'],
            ['name' => 'Tahu Crispy', 'description' => 'Tahu crispy goreng dengan sambal kecap', 'price' => 4000, 'category' => 'snack'],
            ['name' => 'Cireng Isi', 'description' => 'Cireng isi ayam dan sayuran', 'price' => 5000, 'category' => 'snack'],
        ];

        foreach ($menus2 as $menu) {
            Menu::firstOrCreate(
                ['name' => $menu['name']],
                array_merge($menu, ['seller_id' => $seller2->id])
            );
        }

        // Menus - Kantin Bu Dewi
        $menus3 = [
            ['name' => 'Nasi Pecel', 'description' => 'Nasi pecel dengan sayuran segar dan sambal kacang', 'price' => 10000, 'category' => 'makanan'],
            ['name' => 'Gado-Gado', 'description' => 'Gado-gado dengan lontong dan bumbu kacang', 'price' => 12000, 'category' => 'makanan'],
            ['name' => 'Ketoprak', 'description' => 'Ketoprak lontong dengan tahu dan bumbu kacang', 'price' => 10000, 'category' => 'makanan'],
            ['name' => 'Es Cendol', 'description' => 'Es cendol dengan gula merah dan santan', 'price' => 6000, 'category' => 'minuman'],
            ['name' => 'Wedang Jahe', 'description' => 'Wedang jahe hangat dengan gula merah', 'price' => 4000, 'category' => 'minuman'],
            ['name' => 'Onde-Onde', 'description' => 'Onde-onde isi kacang hijau', 'price' => 3000, 'category' => 'snack'],
        ];

        foreach ($menus3 as $menu) {
            Menu::firstOrCreate(
                ['name' => $menu['name']],
                array_merge($menu, ['seller_id' => $seller3->id])
            );
        }

        // Menus - Kantin Mas Eko
        $menus4 = [
            ['name' => 'Burger Ayam', 'description' => 'Burger ayam crispy dengan saus spesial', 'price' => 15000, 'category' => 'makanan'],
            ['name' => 'Kebab Daging', 'description' => 'Kebab daging sapi dengan sayuran dan saus', 'price' => 18000, 'category' => 'makanan'],
            ['name' => 'Roti Bakar Coklat', 'description' => 'Roti bakar dengan selai coklat dan keju', 'price' => 8000, 'category' => 'makanan'],
            ['name' => 'Milkshake Oreo', 'description' => 'Milkshake dengan oreo dan whipped cream', 'price' => 12000, 'category' => 'minuman'],
            ['name' => 'Thai Tea', 'description' => 'Thai tea dingin dengan susu creamy', 'price' => 8000, 'category' => 'minuman'],
            ['name' => 'French Fries', 'description' => 'Kentang goreng crispy dengan saus sambal dan mayo', 'price' => 10000, 'category' => 'snack'],
        ];

        foreach ($menus4 as $menu) {
            Menu::firstOrCreate(
                ['name' => $menu['name']],
                array_merge($menu, ['seller_id' => $seller4->id])
            );
        }
    }
}
