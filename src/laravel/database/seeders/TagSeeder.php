<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class Tagseeder extends Seeder
{

    public function run(): void
    {
        $tags = ['食費', '外食', '日用品', '交通費', '趣味', '医療費', '水道光熱費', 'その他'];

        foreach ($tags as $tagName) {
            Tag::firstOrCreate(['name' => $tagName]);
        }
    }
}
