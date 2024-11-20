<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            ['category_id' => 1, 'name' => 'Gucci'],
            ['category_id' => 1, 'name' => 'Louis Vuitton'],
            ['category_id' => 1, 'name' => 'Prada'],
            ['category_id' => 1, 'name' => 'Chanel'],
            ['category_id' => 1, 'name' => 'Zara'],

            ['category_id' => 2, 'name' => 'Panasonic'],
            ['category_id' => 2, 'name' => 'Sharp'],
            ['category_id' => 2, 'name' => 'Hitachi'],
            ['category_id' => 2, 'name' => 'Sony'],
            ['category_id' => 2, 'name' => 'Toshiba'],

            ['category_id' => 3, 'name' => 'IKEA'],
            ['category_id' => 3, 'name' => 'Nitori'],
            ['category_id' => 3, 'name' => 'Muji'],
            ['category_id' => 3, 'name' => 'Francfranc'],
            ['category_id' => 3, 'name' => 'Actus'],

            ['category_id' => 4, 'name' => 'Chanel'],
            ['category_id' => 4, 'name' => 'Dior'],
            ['category_id' => 4, 'name' => 'Yves Saint Laurent'],
            ['category_id' => 4, 'name' => 'Hermès'],
            ['category_id' => 4, 'name' => 'Burberry'],

            ['category_id' => 5, 'name' => 'Nike'],
            ['category_id' => 5, 'name' => 'Adidas'],
            ['category_id' => 5, 'name' => 'Puma'],
            ['category_id' => 5, 'name' => 'Levi\'s'],
            ['category_id' => 5, 'name' => 'Uniqlo'],

            ['category_id' => 6, 'name' => 'L\'Oreal'],
            ['category_id' => 6, 'name' => 'Shiseido'],
            ['category_id' => 6, 'name' => 'MAC Cosmetics'],
            ['category_id' => 6, 'name' => 'Clinique'],
            ['category_id' => 6, 'name' => 'NARS'],

            ['category_id' => 7, 'name' => 'Penguin Random House'],
            ['category_id' => 7, 'name' => 'Kodansha'],
            ['category_id' => 7, 'name' => 'Shueisha'],
            ['category_id' => 7, 'name' => 'HarperCollins'],
            ['category_id' => 7, 'name' => 'Simon & Schuster'],

            ['category_id' => 8, 'name' => 'Nintendo'],
            ['category_id' => 8, 'name' => 'Sony PlayStation'],
            ['category_id' => 8, 'name' => 'Microsoft Xbox'],
            ['category_id' => 8, 'name' => 'Capcom'],
            ['category_id' => 8, 'name' => 'Sega'],

            ['category_id' => 9, 'name' => 'Wilson'],
            ['category_id' => 9, 'name' => 'Adidas'],
            ['category_id' => 9, 'name' => 'Nike'],
            ['category_id' => 9, 'name' => 'Puma'],
            ['category_id' => 9, 'name' => 'Under Armour'],

            ['category_id' => 10, 'name' => 'Tefal'],
            ['category_id' => 10, 'name' => 'Zojirushi (象印)'],
            ['category_id' => 10, 'name' => 'Tiger'],
            ['category_id' => 10, 'name' => 'KitchenAid'],
            ['category_id' => 10, 'name' => 'Cuisinart'],

            ['category_id' => 11, 'name' => 'Etsy'],
            ['category_id' => 11, 'name' => 'Minne'],
            ['category_id' => 11, 'name' => 'Creema'],
            ['category_id' => 11, 'name' => 'ArtFire'],
            ['category_id' => 11, 'name' => 'Folksy'],

            ['category_id' => 12, 'name' => 'Tiffany & Co.'],
            ['category_id' => 12, 'name' => 'Cartier'],
            ['category_id' => 12, 'name' => 'Pandora'],
            ['category_id' => 12, 'name' => 'Swarovski'],
            ['category_id' => 12, 'name' => 'BVLGARI'],

            ['category_id' => 13, 'name' => 'LEGO'],
            ['category_id' => 13, 'name' => 'Bandai'],
            ['category_id' => 13, 'name' => 'Hasbro'],
            ['category_id' => 13, 'name' => 'Fisher-Price'],
            ['category_id' => 13, 'name' => 'Mattel'],

            ['category_id' => 14, 'name' => 'Combi'],
            ['category_id' => 14, 'name' => 'Aprica'],
            ['category_id' => 14, 'name' => 'BabyBjorn'],
            ['category_id' => 14, 'name' => 'Fisher-Price'],
            ['category_id' => 14, 'name' => 'Graco'],
        ];
        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}