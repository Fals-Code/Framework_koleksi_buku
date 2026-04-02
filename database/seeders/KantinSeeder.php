<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Menu;
use Illuminate\Support\Facades\Hash;

class KantinSeeder extends Seeder
{
    public function run(): void
    {
        $v1 = Vendor::create([
            'nama_warung' => 'Warung Bu Siti',
            'owner_name'  => 'Bu Siti',
            'email'       => 'busiti@kantin.com',
            'password'    => Hash::make('password'),
            'image'       => 'vendors/busiti.jpg'
        ]);

        $v2 = Vendor::create([
            'nama_warung' => 'Kantin Pak Budi',
            'owner_name'  => 'Pak Budi',
            'email'       => 'pakbudi@kantin.com',
            'password'    => Hash::make('password'),
            'image'       => 'vendors/pakbudi.jpg'
        ]);

        Menu::create([
            'vendor_id'    => $v1->id,
            'nama_makanan' => 'Nasi Goreng Spesial',
            'deskripsi'    => 'Nasi goreng dengan telur dan ayam suwir',
            'harga'        => 15000,
            'stok'         => 50,
            'foto'         => 'menu/nasgor.jpg'
        ]);

        Menu::create([
            'vendor_id'    => $v1->id,
            'nama_makanan' => 'Mie Ayam Pangsit',
            'deskripsi'    => 'Mie ayam dengan pangsit goreng renyah',
            'harga'        => 12000,
            'stok'         => 30,
            'foto'         => 'menu/mieayam.jpg'
        ]);

        Menu::create([
            'vendor_id'    => $v2->id,
            'nama_makanan' => 'Soto Ayam Lamongan',
            'deskripsi'    => 'Soto ayam dengan koya gurih',
            'harga'        => 13000,
            'stok'         => 40,
            'foto'         => 'menu/soto.jpg'
        ]);
    }
}
