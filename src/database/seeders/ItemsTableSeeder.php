<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'user_id' => 1,
                'categories' => [1, 5],
                'name' => '腕時計',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition' => '良好',
            ],
            [
                'user_id' => 1,
                'categories' => [2],
                'name' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition' => '目立った傷や汚れなし',
            ],
            [
                'user_id' => 1,
                'categories' => [10],
                'name' => '玉ねぎ3束',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition' => 'やや傷や汚れあり',
            ],
            [
                'user_id' => 1,
                'categories' => [1, 5],
                'name' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition' => '状態が悪い',
            ],
            [
                'user_id' => 1,
                'categories' => [2],
                'name' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition' => '良好',
            ],
            [
                'user_id' => 1,
                'categories' => [2],
                'name' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition' => '目立った傷や汚れなし',
            ],
            [
                'user_id' => 1,
                'categories' => [1, 4],
                'name' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition' => 'やや傷や汚れあり',
            ],
            [
                'user_id' => 1,
                'categories' => [10],
                'name' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition' => '状態が悪い',
            ],
            [
                'user_id' => 1,
                'categories' => [10],
                'name' => 'コーヒーミル',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition' => '良好',
            ],
            [
                'user_id' => 1,
                'categories' => [4, 6],
                'name' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition' => '目立った傷や汚れなし',
            ],
        ];
        foreach ($items as $itemData) {
            // カテゴリ情報を別に取り出し、アイテムデータから削除
            $categories = $itemData['categories'];
            unset($itemData['categories']);

            // 画像をダウンロードしてストレージに保存
            $imageName = basename($itemData['img_url']);
            $imageContent = file_get_contents($itemData['img_url']);
            Storage::put('public/item-images/' . $imageName, $imageContent);
            $storedImagePath = 'storage/item-images/' . $imageName;

            // アイテムデータに保存先の画像URLを設定
            $itemData['img_url'] = $storedImagePath;

            // アイテムをデータベースに挿入
            $item = Item::create([
                'user_id' => $itemData['user_id'],
                'name' => $itemData['name'],
                'description' => $itemData['description'],
                'price' => $itemData['price'],
                'condition' => $itemData['condition'],
                'img_url' => $itemData['img_url'],
            ]);

            // 中間テーブルにカテゴリを関連付け
            $item->categories()->attach($categories);
        }
    }
}
