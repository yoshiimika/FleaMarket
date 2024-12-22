<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品出品画面にて必要な情報が保存できることをテスト
     */
    public function test_item_registration_saves_correct_information()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $categories = Category::factory(2)->sequence(
            ['name' => 'スマートフォン'],
            ['name' => '電子機器']
        )->create();
        $brand = Brand::factory()->create([
            'category_id' => $categories->first()->id,
            'name' => 'Apple',
        ]);
        $image = UploadedFile::fake()->image('test-item.jpg');
        $itemData = [
            'category_ids' => $categories->pluck('id')->toArray(),
            'brand_id' => $brand->id,
            'name' => 'テスト商品',
            'color' => 'ブラック',
            'description' => 'これはテスト商品です。',
            'price' => 10000,
            'image' => $image,
            'condition' => '良好',
        ];

        $response = $this->actingAs($user)->get('/sell');
        $response->assertStatus(200);
        $response->assertViewIs('sell.sell');

        $this->actingAs($user)->post('/sell', $itemData);
        $storedFilePath = 'item-images/' . $image->hashName();

        foreach (['スマートフォン', '電子機器'] as $categoryName) {
            $this->assertDatabaseHas('categories', [
                'name' => $categoryName,
            ]);
        }
        $this->assertDatabaseHas('brands', [
            'category_id' => $categories->first()->id,
            'name' => 'Apple',
        ]);
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'brand_id' => $brand->id,
            'name' => 'テスト商品',
            'color' => 'ブラック',
            'description' => 'これはテスト商品です。',
            'price' => 10000,
            'img_url' => 'storage/'.$storedFilePath,
            'condition' => '良好',
        ]);
        Storage::disk('public')->assertExists($storedFilePath);
    }
}
